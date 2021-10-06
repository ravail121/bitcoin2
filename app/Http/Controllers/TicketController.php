<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TicketComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\Ticket\StoreFormRequest;
use App\Http\Requests\Ticket\StoreReplyFormRequest;
use Intervention\Image\Facades\Image;
use App\Models\Notification;
use App\Models\Admin;
use PragmaRX\Google2FA\Google2FA;
use Storage;
use Files;
class TicketController extends Controller
{
    public function ticketIndex()
    {
        $all_ticket = Ticket::where('customer_id', Auth::user()->id)
           ->orderBy('id', 'desc')->paginate(5);

        return view('user.tickets.index', compact('all_ticket'));
    }

    public function ticketCreate()
    {
        return view('user.tickets.add');
    }

    public function ticketStore(StoreFormRequest $request)
    {
       
        $filename='';
        if($request->hasFile('files')){
            $image = $request->file('files');
            $filename = 'files'.time().'.'.$image->getClientOriginalExtension();
            if($image->getClientOriginalExtension() == 'pdf'){
                $content = file_get_contents($image);
                \Storage::put('images/attach/'.$filename, $content, 'public');
            }else{
                $img = \Image::make($image->getRealPath());
                $img->resize(350 , null, function ($constraint) {
                    $constraint->aspectRatio();                 
                });

                $img->stream(); // <-- Key point
                \Storage::put('images/attach/'.$filename, $img, 'public');
            }
            
           
        }
        
        $a = strtoupper(md5(uniqid(rand(), true)));

        $ticket = Ticket::create([
           'subject' => $request->subject,
            'ticket' => substr($a, 0, 8),
            'customer_id' => Auth::user()->id,
            'status' => 1,
        ]);

        TicketComment::create([
           'ticket_id' => $ticket->ticket,
           'type' => 1,
           'comment' => $request->detail,
           'issue' => $request->issue,
           'replyto' => $request->replyto,
           'files1' =>$filename,
        ]);
        $sbjct = "Ticket submission confirmed";
        $msg ="<p>We are confirming that we received your support ticket with the 
        subject name: ".$request->subject.". Your new ticket number is: <b>".$ticket->ticket."</b>.</p> ";
        $msg .="<p>Someone from our support team will be in touch soon.
         </p><p>To view the full ticket or add a reply, please click the button below</p>";
        $url11="/user/support/reply/$ticket->ticket";
         $msg .= '<p><a  href="'. config('app.url').$url11.'"  style="	background-color: #23373f;
         padding: 10px;
         margin: 10px;
     
         text-decoration: none;
         color: #ffff;
         font-weight: 600;
         border-radius: 4px;"> Click To See</a></p>';
                    


         $sbjct1 = "New support ticket submitted";
         $msg1 ="<p>You received a support ticket from ".Auth::user()->name." with the subject name: ".$request->subject.".</p> ";
         
         $msg1 .="<p>To view the full ticket or add a reply, please click the button below</p>";
         $url12="/adminio/support/reply/$ticket->ticket";
         $msg1 .= '<p><a  href="'. config('app.url').$url12.'"  style="	background-color: #23373f;
         padding: 10px;
         margin: 10px;
     
         text-decoration: none;
         color: #ffff;
         font-weight: 600;
         border-radius: 4px;"> Click To See</a></p>';
         $admin = Admin::first();
         $notification=[];
                    $notification['from_user'] = $admin->id;
                    $notification['to_user'] =Auth::user()->id;
                    $notification['noti_type'] ='support';
                    $notification['action_id'] =$ticket->id;
                    $notification['message']= 'You opened support ticket'.$ticket->id;
                    
                    $notification['url'] =$url11;
                    
                    
                    Notification::create($notification);
         try{
            send_email(Auth::user()->email, Auth::user()->name, $sbjct, $msg);
            send_email( $admin->email,  $admin->name, $sbjct1, $msg1);
           
        }catch(\Exception $ee){
            // return $ee;
        }
        Session::flash('message', 'Successfully Created Ticket');
        return redirect()->route('ticket.customer.reply', $ticket->ticket);
    }

    public function ticketReply($ticket)
    {
        $ticket_object = Ticket::where('customer_id', Auth::user()->id)
            ->where('ticket', $ticket)->first();
        $ticket_data = TicketComment::where('ticket_id', $ticket)->get();

        if ($ticket_object  == '') {
            return back();
        } else {
            return view('user.tickets.view', compact('ticket_data', 'ticket_object'));
        }
    }

    public function ticketReplyStore(StoreReplyFormRequest $request, $ticket)
    {
        $filename='';
        $store_file = $filename; 
        if($request->hasFile('files')){
            $image = $request->file('files');
            $filename = 'files'.time().'.'.$image->getClientOriginalExtension();
            $store_file = $filename;
            if($image->getClientOriginalExtension() == 'pdf'){
                $content = file_get_contents($image);
                \Storage::put('images/attach/'.$filename, $content, 'public');
            }else{
                $filename = 'files'.time();
                $filename_watermark = $filename.'.png';
                $store_file = $filename_watermark;
                $image = $request->file('files');
                $filename = $filename.'.'.$image->getClientOriginalExtension();
                $ext = $image->getClientOriginalExtension();
                $img = Image::make($image->getRealPath());
                
                $img->resize(1024 , null, function ($constraint) {
                    $constraint->aspectRatio();                 
                });

                

                $img->stream(); // <-- Key point
                // echo storage_path().'/app/public/images/attach/'.$filename;exit;
                Storage::put('images/attach/'.$filename, $img, 'public');
                Storage::put('images/private/'.$filename, $img, 'public');

                
                // adding watermark on image
                $watermark = imagecreatefrompng('images/bitcoin_watermark.png');
                if($ext == 'jpg' || $ext == 'jpeg' || $ext == 'JPG' || $ext == 'JPEG')
                    $imageURL = imagecreatefromjpeg(storage_path().'/app/public/images/attach/'.$filename);
                elseif($ext == 'png' || $ext == 'PNG')
                    $imageURL = imagecreatefrompng(storage_path().'/app/public/images/attach/'.$filename);
                else
                    return redirect('/user'.'/'.Auth::user()->username.'/home')->with('success', 'Image should be in JPG, JPEG or PNG');
                

                // removing image without watermark
                unlink(storage_path().'/app/public/images/attach/'.$filename);

                $watermarkX = imagesx($watermark);
                $watermarkY = imagesy($watermark);
                imagecopy($imageURL, $watermark, 0, 0, 0, 0, $watermarkX, $watermarkY);
                header('Content-type: image/png');
                imagepng($imageURL, storage_path().'/app/public/images/attach/'.$filename_watermark, 0);
                imagedestroy($imageURL);
                
                //old
                // $img = \Image::make($image->getRealPath());
                // $ratio = 4/3;

                // $img->resize(350 , null, function ($constraint) {
                //     $constraint->aspectRatio();                 
                // });

                // $img->stream(); // <-- Key point
                // \Storage::put('images/attach/'.$filename, $img, 'public');
            }
            
           
        }
        TicketComment::create([
            'ticket_id' => $ticket,
            'type' => 1,
            'files1' =>$store_file,
            'comment' => $request->comment,
        ]);

        Ticket::where('ticket', $ticket)
            ->update([
               'status' => 3
            ]);

        return redirect()->back()->with('message', 'Message Send Successful');
    }

    public function indexSupport()
    {
        $all_ticket = Ticket::where('status',9)->orderBy('id', 'desc')->paginate(20);
        $page_title  = "Support Tickets";
        return view('admin.support.support', compact('all_ticket', 'page_title'));
    }

    public function adminSupport($ticket)
    {
        $page_title  = "Support Reply";
        $ticket_object = Ticket::where('ticket', $ticket)->first();
        $ticket_data = TicketComment::where('ticket_id', $ticket)->get();
        return view('admin.support.view_reply', compact('ticket_object', 'ticket_data', 'page_title'));
    }

    public function adminReply(StoreReplyFormRequest $request, $ticket)
    {
        TicketComment::create([
            'ticket_id' => $ticket,
            'type' => 0,
            'comment' => $request->comment,
        ]);

        $Ticket=Ticket::where('ticket', $ticket)->first();
            
        $Ticket->status=2;

            $notification=[];
            $notification['from_user'] = Admin::first()->id ;
            $notification['to_user'] =$Ticket->customer_id;
            $notification['noti_type'] ='support';
            $notification['action_id'] =$Ticket->id;
            $notification['message']= 'Admin replied to your support ticket'.$ticket;
            
            $notification['url'] ="/user/support/reply/$ticket";
            
            
            Notification::create($notification);
            $Ticket->save();
        return redirect()->back()->with('message', 'Message Send Successful');
    }

    public function ticketClose($ticket)
    {
        Ticket::where('ticket', $ticket)
            ->update([
                'status' => 9
            ]);
        return redirect()->back()->with('message', 'Conversation closed, But you can start again');
    }

    public function pendingTicketAdmin()
    {
        $all_ticket = Ticket::where('status', 1)->orWhere('status', 2)->orWhere('status', 3)->paginate(20);
        $page_title  = "Support Tickets";
        return view('admin.support.support', compact('all_ticket', 'page_title'));
    }
}
