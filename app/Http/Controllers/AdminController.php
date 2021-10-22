<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use App\Models\Currency;
use App\Models\Ticket;
use App\Models\Deposit;
use App\Models\Trx;
use App\Models\AdminRoles;
use App\Models\Transaction;
use App\Models\Admin;
use App\Models\User;
use App\Models\UserLogin;
use App\Models\WithdrawRequest;
use App\Models\AdvertiseDeal;
use App\Models\Advertisement;
use App\Models\Country;
use App\Models\Cities;
use App\Models\UserCryptoBalance;
use App\Models\WalletAddresses;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use App\Models\DealConvertion;
use Storage;
use App\Models\GeneralSettings;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Admin\createUsers;
use App\Models\InternalTransactions;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class AdminController extends Controller
{
    public function cronJobsIndex(){
        $data['page_title'] = 'Cron Jobs';
        return view('admin.cron-jobs', $data);
    }

    public function cronJobsUpdate(Request $request){
        if (isset($request->action_type)){
            // $process = new Process("/sbin/service cron $request->action_type");
            // $process->run(); 

            // // executes after the command finishes
            // if (!$process->isSuccessful()) {
            //     throw new ProcessFailedException($process);
            // }
            // sudo service cron status
            echo exec("/usr/bin/sudo /usr/sbin/service cron $request->action_type");
            return redirect()->back()->with('message', "Updated Successfully!");
        }
        else return redirect()->back()->with('message', 'Try Again!');
    }

    public function trigerEvent(){
        event(new \App\Events\DashboardCountersEvent(array(
            'channel' => 'admin_dashboard_stats',
            'event' => 'updates_sindhu',
            'message' => array(
                'id' => 1,
                'value' => 2
            )
        )));
    }

    public function dashboard()
    {
        $admin=Auth::guard('admin')->user();
        $data['page_title'] = 'DashBoard';
        if($admin->is_pro==1){
            $data['dashboard_type'] = 'Counter';
            // $data['page_title'] = 'DashBoard';
                $data['ads24']=Advertisement::whereDate('created_at', Carbon::today())->count();
            $data['Gset'] = GeneralSettings::first();
             $data['method'] = PaymentMethod::where('status', 1)->count();
             $data['currency'] = Currency::where('status', 1)->count();
             $data['user'] = User::count();
                $data['signups']=User::whereDate('created_at', Carbon::today())->count();
             $data['user_active'] = User::where('status', 1)->count();
$data['user_deactive'] = User::where('status', 0)->count();
                //dd($data['signups']);
$data['pro']=User::where('email', 'like', '%@tbe.email')->count();
$data['global']=User::where('email', 'not like', '%@tbe.email')->count();
                //$data['global'] = User::where('is_pro', 0)->count();
             $data['unverified'] = User::where('verified', 0)->count();
            $data['autoverified'] = User::where('auto_verified', 1)->count();
             $data['email_active'] = User::where('email_verify', 0)->count();
             $data['phone_active'] = User::where('phone_verify', 0)->count();
             $data['ticket'] = Ticket::where('status', 1)->orWhere('status', 3)->count();
             $data['active_today'] = UserLogin::whereDate('created_at', Carbon::today())->count();
                        $data['ads_active']=Advertisement::where('status', 1)->count();
                $data['ads_inactive']=Advertisement::where('status', 0)->count();
             $data['totalDeposited'] = Transaction::deposits()->sum('amount');
             $data['completed_deals']=AdvertiseDeal::where('status', 1)->latest()->paginate(5, ['*'], 'p');
             $data['disputed_deals']=AdvertiseDeal::where('status', 10)->ORwhere('status', 11)->latest()->paginate(5, ['*'], 'q');
                 $data['disputed_deals_count']=AdvertiseDeal::where('status', 10)->ORwhere('status', 11)->count();
             $data['active_deposits']= Transaction::where('type','deposit')->latest()->paginate(5, ['*'], 'r');
             $data['active_withdraw']=WithdrawRequest::latest()->paginate(5, ['*'], 's');

            return view('admin.dashboard',$data);


           
        }else{
            $data['page_title'] = 'DashBoard';
            return view('admin.others_dashboard', $data);
        }
        
    }

    public function dashboard_table()
    {
        $admin=Auth::guard('admin')->user();
        if($admin->is_pro==1){
            $data['dashboard_type'] = 'Table';
            $data['page_title'] = 'DashBoard';
            $data['Gset'] = GeneralSettings::first();
            $data['method'] = PaymentMethod::where('status', 1)->count();
            $data['currency'] = Currency::where('status', 1)->count();
            $data['user'] = User::count();
            $data['user_active'] = User::where('status', 1)->count();
            $data['unverified'] = User::where('document_uploaded', 1)->count();
            $data['autoverified'] = User::where('auto_verified', 1)->count();
            $data['email_active'] = User::where('email_verify', 0)->count();
            $data['phone_active'] = User::where('phone_verify', 0)->count();
            $data['ticket'] = Ticket::where('status', 1)->orWhere('status', 3)->count();
            $data['active_today'] = UserLogin::whereDate('created_at', Carbon::today())->count();
            $data['totalDeposited'] = Transaction::deposits()->sum('amount');
            $data['completed_deals']=AdvertiseDeal::where('status', 1)->latest()->paginate(5, ['*'], 'p');
            $data['disputed_deals']=AdvertiseDeal::where('status', 10)->ORwhere('status', 11)->latest()->paginate(5, ['*'], 'q');
            $data['active_deposits']= Transaction::where('type','deposit')->latest()->paginate(5, ['*'], 'r');
            $data['active_withdraw']=WithdrawRequest::latest()->paginate(5, ['*'], 's');
            $data['active_send']=InternalTransactions::latest()->paginate(5, ['*'], 'i');
            $data['active_ticket']=Ticket::latest()->paginate(5, ['*'], 't');

            return view('admin.dashboard', $data);
        }else{
            $data['page_title'] = 'DashBoard';
            return view('admin.others_dashboard', $data);
        }
        
    }

    public function dashboard_chart()
    {
        $admin=Auth::guard('admin')->user();
        if($admin->is_pro==1){
            $data['dashboard_type'] = 'Chart';
            $data['page_title'] = 'DashBoard';
            $data['Gset'] = GeneralSettings::first();
            $data['method'] = PaymentMethod::where('status', 1)->count();
            $data['currency'] = Currency::where('status', 1)->count();
            $data['user'] = User::count();
            $data['user_active'] = User::where('status', 1)->count();
            $data['unverified'] = User::where('document_uploaded', 1)->count();
            $data['autoverified'] = User::where('auto_verified', 1)->count();
            $data['email_active'] = User::where('email_verify', 0)->count();
            $data['phone_active'] = User::where('phone_verify', 0)->count();
            $data['ticket'] = Ticket::where('status', 1)->orWhere('status', 3)->count();
            $data['active_today'] = UserLogin::whereDate('created_at', Carbon::today())->count();
            $data['totalDeposited'] = Transaction::deposits()->sum('amount');
            $data['completed_deals']=AdvertiseDeal::where('status', 1)->latest()->paginate(5, ['*'], 'p');
            $data['disputed_deals']=AdvertiseDeal::where('status', 10)->ORwhere('status', 11)->latest()->paginate(5, ['*'], 'q');
            $data['active_deposits']= Transaction::where('type','deposit')->latest()->paginate(5, ['*'], 'r');
            $data['active_withdraw']=WithdrawRequest::latest()->paginate(5, ['*'], 's');

            return view('admin.dashboard', $data);
        }else{
            $data['page_title'] = 'DashBoard';
            return view('admin.others_dashboard', $data);
        }
        
    }

    /**
     * Get dashboard charts data
     *
     * @param Illuminate\Http\Request $request
     *
     * @return JsonResponse
     */
    public function getChartsData()
    {
        $now = Carbon::today()->subDays(7);
        $data['trxes'] = Trx::where('created_at', '>=', $now)->get()->groupBy(function ($d) {
            return $d->created_at->format('Y-m-d');
        });
        $data['withdraws'] = WithdrawRequest::where('created_at', '>=', $now)->get()->groupBy(function ($d) {
            return $d->created_at->format('Y-m-d');
        });
        $data['deposits'] = Deposit::where('created_at', '>=', $now)->get()->groupBy(function ($d) {
            return $d->created_at->format('Y-m-d');
        });

        return response()->json($data);
    }
    public function AdminSendMessage(Request $request)
    {
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time().'.jpg';
            $img = Image::make($image->getRealPath());
            $ratio = 4/3;

            $img->resize(350 , null, function ($constraint) {
                $constraint->aspectRatio();                 
            });

            $img->stream(); // <-- Key point
            Storage::put('images/attach/'.$filename, $img, 'public');
            $in['image'] = $filename;
        }
        $in['deal_detail'] = $request->message;
        $in['deal_id'] = $request->id;
        $in['type'] = 0;

        $data = DealConvertion::create($in);
        $deal =  $aa=AdvertiseDeal::where('id', $request->id)->first();
        $data['from_name']= $deal->from_user->username;
        $data['to_name']= $deal->to_user->username;
        $data['admin_name']= Auth::user();
        $data['created_at'] = Carbon::parse($data['created_at'])->timezone(Auth::guard('admin')->user()->timezone)->format('Y-m-d H:i:s') ;
        return response()->json($data);
    }
    public function deal_messages($id){
        $add = AdvertiseDeal::where('id', $id)->first();
        $msgs=[];
        foreach($add->conversation->reverse() as $data){
            if($data->type != Auth::id()){
                $convo=DealConvertion::find($data->id);
                $convo->read_message ='read';
                $convo->update();
            }
        }
        $add = AdvertiseDeal::where('id', $id)->first();
        foreach($add->conversation->reverse() as $data){
            $data1=[];
            $data1['from_name']=$add->from_user->username;
            $data1['from_user_id']=$add->from_user_id;
            $data1['to_name']=$add->to_user->username;
            $data1['to_user_id']=$add->to_user_id;
            $data1['deal_detail'] =$data->deal_detail;
            $data1['image'] =$data->image;
            $data1['type'] =$data->type;
            $data1['read_message'] =$data->read_message;
            $data1['created_at'] = Carbon::parse($data->created_at)->timezone(Auth::guard('admin')->user()->timezone)->format('Y-m-d H:i:s') ;
            
            $msgs[]=$data1;

        }
        $add->msgs=$msgs;
        return response()->json($add);
    }
    public function deal_hold($id,$status){
        $add = AdvertiseDeal::findOrFail($id);
        $add->status = $status;
        $add->save();
        return response()->json($add);
    }

    public function systemOverAllBalanceDetails(Request $request){
        $users = User::get();
        $bal = UserCryptoBalance::get();
        foreach($users as $u){
            echo $u->cryptoBalance->balance;
        }
        // return response()->json($add);
    }

    // public function confirmPaid($id)
    // {
       
    //     $general = GeneralSettings::first();
    //     $add = AdvertiseDeal::findOrFail($id);
    //     $price = $add->price;
        
        
    //     $charge = number_format((float)(($add->coin_amount * $general->trx_charge)/100) , 8, '.', '');
        
    //     $bal = $add->coin_amount;

    //     if ($add->add_type == 1) {
    //         $user = User::findOrFail($add->from_user_id);
    //         $user_adress = UserCryptoBalance::where('user_id', $user->id)
    //             ->where('gateway_id', $add->gateway_id)->first();
    //         $new_balance = $user_adress->balance + $add->coin_amount ;
    //         $user_adress->balance = $new_balance;
    //         $user_adress->save();

    //         $to_user = User::findOrFail($add->to_user_id);
    //         $url22="/user/deal/$add->trans_id";
    //         Trx::create([
    //             'user_id' => $user->id,
    //             'amount' => $bal .' '.$add->gateway->currency,
    //             'main_amo' => number_format((float)$new_balance , 8, '.', '').' '.$add->gateway->currency,
    //             'charge' => 0,
    //             'type' => '+',
    //             'title' => 'Buy from '.$to_user->username,
    //             'trx' => 'Buy'.$add->gateway->currency.time(),
    //             'deal_url' => $url22
    //         ]);

    //         $notification=[];
    //         $notification['from_user'] = $add->to_user_id;
    //         $notification['to_user'] =$add->from_user_id;
    //         $notification['noti_type'] ='deal';
    //         $notification['action_id'] =$add->id;
    //         $notification['message']= 'You recieved '.$add->coin_amount .' BTC from '.$to_user->username;
            
    //         $notification['url'] ='/user/transactions';
    //         $notification['add_type']=$add->add_type;
    //         $notification['deal_id']=$add->id;
    //         $notification['advertisement_id']=$add->advertisement_id;
            
    //         Notification::create($notification);


    //         $to_user = User::findOrFail($add->to_user_id);
    //         $to_user_adress = UserCryptoBalance::where('user_id', $to_user->id)
    //             ->where('gateway_id', $add->gateway_id)->first();
    //         $new_balance = $to_user_adress->balance - $charge;
    //         $to_user_adress->balance = $new_balance;
    //         $to_user_adress->save();
    //         $url21="/user/deal-reply/$add->trans_id";
    //         Trx::create([
    //             'user_id' => $to_user->id,
    //             'amount' => number_format((float)$bal , 8, '.', '') .' '.$add->gateway->currency,
    //             'main_amo' => number_format((float)$to_user_adress->balance , 8, '.', '') .' '.$add->gateway->currency,
    //             'charge' => $charge.' '.$add->gateway->currency,
    //             'type' => '-',
    //             'title' => 'Sell to '.$user->username,
    //             'trx' => 'Sell'.$add->gateway->currency.time(),
    //             'deal_url' => $url21
    //         ]);

    //         $notification=[];
    //         $notification['from_user'] = $add->from_user_id;
    //         $notification['to_user'] =$to_user->id;
    //         $notification['noti_type'] ='deal';
    //         $notification['action_id'] =$add->id;
    //         $notification['message']= 'You transferred '.$add->coin_amount .' BTC to '.$user->username;
            
    //         $notification['url'] ='/user/transactions';
    //         $notification['add_type']=$add->add_type;
    //         $notification['deal_id']=$add->id;
    //         $notification['advertisement_id']=$add->advertisement_id;
            
    //         Notification::create($notification);
            
    //     } else {
    //         $user = User::findOrFail($add->to_user_id);

    //         $user_adress = UserCryptoBalance::where('user_id', $add->to_user_id)
    //             ->where('gateway_id', $add->gateway_id)->first();

    //         $new_balance = $user_adress->balance + ($add->coin_amount -$charge );
    //         $user_adress->balance = $new_balance ;
    //         $user_adress->save();

    //         $to_user = User::findOrFail($add->to_user_id);
    //         $url21="/user/deal-reply/$add->trans_id";
    //         Trx::create([
    //             'user_id' => $user->id,
    //             'amount' =>number_format((float)$bal , 8, '.', '').' '.$add->gateway->currency,
    //             'main_amo' =>number_format((float)$bal , 8, '.', '').' '.$add->gateway->currency,
    //             'charge' => $charge.' '.$add->gateway->currency,
    //             'type' => '+',
    //             'title' => 'Buy from '.$to_user->username,
    //             'trx' => 'Buy'.$add->gateway->currency.time(),
    //             'deal_url'=>$url21
    //         ]);
    //         $notification=[];
    //         $notification['from_user'] = $to_user->id;
    //         $notification['to_user'] =$user->id;
    //         $notification['noti_type'] ='deal';
    //         $notification['action_id'] =$add->id;
    //         $notification['message']= 'You recieved '.$add->coin_amount .' BTC from '.$to_user->username;
            
    //         $notification['url'] ='/user/transactions';
    //         $notification['add_type']=$add->add_type;
    //         $notification['deal_id']=$add->id;
    //         $notification['advertisement_id']=$add->advertisement_id;
            
    //         Notification::create($notification);

    //         $to_user = User::findOrFail($add->from_user_id);

    //         $to_user_adress = UserCryptoBalance::where('user_id', $to_user->id)
    //             ->where('gateway_id', $add->gateway_id)->first();
    //         $new_balance = $to_user_adress->balance ;
    //         $to_user_adress->balance = $new_balance;
    //         $to_user_adress->save();    
    //         $url22="/user/deal/$add->trans_id";
    //         Trx::create([
    //             'user_id' => $to_user->id,
    //             'amount' =>number_format((float)$bal , 8, '.', '') .' '.$add->gateway->currency,
    //             'main_amo' =>number_format((float)$to_user_adress->balance , 8, '.', '') .' '.$add->gateway->currency,
    //             'charge' => number_format((float)0, 8, '.', '') . ' ' . $add->gateway->currency,
    //             'type' => '-',
    //             'title' => 'Sell to '.$user->username,
    //             'trx' => 'Sell'.$add->gateway->currency.time(),
    //             'deal_url'=>$url22
    //         ]);

    //         $notification=[];
    //         $notification['from_user'] = $user->id;
    //         $notification['to_user'] =$to_user->id;
    //         $notification['noti_type'] ='deal';
    //         $notification['action_id'] =$add->id;
    //         $notification['message']= 'You transferred '.$add->coin_amount .' BTC to '.$user->username;
            
    //         $notification['url'] ='/user/transactions';
    //         $notification['add_type']=$add->add_type;
    //         $notification['deal_id']=$add->id;
    //         $notification['advertisement_id']=$add->advertisement_id;
            
    //         Notification::create($notification);
    //     }

    //     $add->status = 1;
    //     $add->save();

    //     return redirect()->back()->with('message', 'BTC Released to Buyer');
    // }

    public function confirmPaid($id)
    {
        $general = GeneralSettings::first();
        $add = AdvertiseDeal::findOrFail($id);
        $price = $add->price;
        
        
        // $charge = number_format((float)(($add->coin_amount * $general->trx_charge)/100) , 8, '.', '');

        $bal = $add->coin_amount;

        if ($add->add_type == 1) {
            $charge = number_format((float)($general->buy_user_fixed_fee) + (($add->coin_amount * $general->buy_user_percentage_fee)/100) , 8, '.', '');
            
            $user = User::findOrFail($add->from_user_id);
            $user_adress = UserCryptoBalance::where('user_id', $user->id)
                ->where('gateway_id', $add->gateway_id)->first();

            $old_balance = $user_adress->balance;
            $new_balance = $user_adress->balance + $add->coin_amount ;
            if($charge < $new_balance) $new_balance -= $charge;
            else $charge = number_format((float)0.00000000, 8, '.', '');
            $user_adress->balance = $new_balance;
            $user_adress->save();

            $to_user = User::findOrFail($add->to_user_id);
            $url22="/user/deal/$add->trans_id";
            Trx::create([
                'user_id' => $user->id,
                'pre_main_amo' =>number_format((float)$old_balance , 8, '.', '').' '.$add->gateway->currency,
                'amount' => number_format((float)$bal , 8, '.', '')  .' '.$add->gateway->currency,
                'main_amo' =>number_format((float)$new_balance , 8, '.', '') .' '.$add->gateway->currency,
                'charge' => $charge.' BTC',
                'type' => '+',
                'title' => 'Buy from '.$to_user->username,
                'trx' => 'Buy'.$add->gateway->currency.time(),
                'deal_url' => $url22
            ]);

            $notification=[];
            $subject ="Trade completed successfully";
            $notification['from_user'] = $add->to_user_id;
            $notification['to_user'] =$add->from_user_id;
            $notification['noti_type'] ='deal';
            $notification['action_id'] =$add->id;
            $notification['message']= 'You recieved '.$add->coin_amount .' BTC from '.$to_user->username;
            
            $notification['url'] =$url22;
            $notification['add_type']=$add->add_type;
            $notification['deal_id']=$add->id;
            $notification['advertisement_id']=$add->advertisement_id;
            Notification::create($notification);
            $notification['message'] .= '<a  href="'. config('app.url').$notification['url'].'"> Click To See</a>';
            $email_user=User::find($notification['to_user']);
            $message="<p>Congratulation! The bitcoin exchange has cleared your transaction and ".$add->coin_amount ." BTC is now available in your wallet. Thank you for trading on Bitcoin.ngo and we look forward to seeing you again.</p>";
            try{
                send_email($email_user->email, $email_user->username, $subject, $message);

            }catch(\Exception $e){

            }
        } else {
            $charge = number_format((float)($general->buy_advertiser_fixed_fee) + (($add->coin_amount * $general->buy_advertiser_percentage_fee)/100) , 8, '.', '');
            
            $user = User::findOrFail($add->to_user_id);

            $user_adress = UserCryptoBalance::where('user_id', $add->to_user_id)
                ->where('gateway_id', $add->gateway_id)->first();

            $old_balance = $user_adress->balance;
            $new_balance = $user_adress->balance + $add->coin_amount;
            if($charge < $new_balance) $new_balance -= $charge;
            else $charge = number_format((float)0.00000000, 8, '.', '');
            $user_adress->balance = $new_balance ;
            $user_adress->save();

            $to_user = User::findOrFail($add->from_user_id);
            $url21="/user/deal-reply/$add->trans_id";
            Trx::create([
                'user_id' => $user->id,
                'pre_main_amo' =>number_format((float)$old_balance , 8, '.', '').' '.$add->gateway->currency,
                'amount' =>number_format((float)$bal , 8, '.', '').' '.$add->gateway->currency,
                'main_amo' =>number_format((float)$new_balance , 8, '.', '') .' '.$add->gateway->currency,
                'charge' => $charge.' '.$add->gateway->currency,
                'type' => '+',
                'title' => 'Buy from '.$to_user->username,
                'trx' => 'Buy'.$add->gateway->currency.time(),
                'deal_url'=>$url21
            ]);
            $notification=[];
            $subject ="Trade completed successfully";
            $notification['from_user'] = $to_user->id;
            $notification['to_user'] =$user->id;
            $notification['noti_type'] ='deal';
            $notification['action_id'] =$add->id;
            $notification['message']= 'You recieved '.$add->coin_amount .' BTC from '.$to_user->username;
            
            $notification['url'] =$url21;
            $notification['add_type']=$add->add_type;
            $notification['deal_id']=$add->id;
            $notification['advertisement_id']=$add->advertisement_id;
            Notification::create($notification);
            $notification['message'] .= '<a  href="'. config('app.url').$notification['url'].'"> Click To See</a>';
            $email_user=User::find($notification['to_user']);
            $message="<p>Congratulation! The bitcoin exchange has cleared your transaction and ".$add->coin_amount ." BTC is now available in your Bitcoin.ngo wallet. Thank you for trading on Bitcoin.ngo and we look forward to seeing you again.</p>";

            try{
                send_email($email_user->email, $email_user->username, $subject, $message);

            }catch(\Exception $e){

            }
            
        }

        $add->status = 1;
        $add->save();

        return redirect()->back()->with('message', 'Released To Buyer');
    }
    
    public function confirmCencel($id)
    {
        $general = GeneralSettings::first();
        $deal = AdvertiseDeal::findOrFail($id);
        
            $deal->status = 2;
            if ($deal->add_type == 1) {
                $charge = number_format((float)($general->sell_advertiser_fixed_fee) + (($deal->coin_amount * $general->sell_advertiser_percentage_fee)/100) , 8, '.', '');
                $to_user = User::findOrFail($deal->to_user_id);
                $to_user_adress = UserCryptoBalance::where('user_id', $to_user->id)
                    ->where('gateway_id', $deal->gateway_id)->first();
                $old_balance = $to_user_adress->balance;
                $main_bal = $to_user_adress->balance + $deal->coin_amount + $charge;
                $to_user_adress->balance = $main_bal;
                $to_user_adress->save();
    
                $url="/user/deal-reply/$deal->trans_id";
                Trx::create([
                    'user_id' => $to_user->id,
                    'pre_main_amo' => number_format((float)$old_balance , 8, '.', '') .' '.$deal->gateway->currency,
                    'amount' => number_format((float)$deal->coin_amount + $charge , 8, '.', '') .' '.$deal->gateway->currency,
                    'main_amo' => number_format((float)$main_bal , 8, '.', '') .' '.$deal->gateway->currency,
                    'charge' => number_format((float)0, 8, '.', '') . ' ' . $deal->gateway->currency,
                    'type' => '+',
                    'title' => 'Refunded by Admin',
                    'trx' => 'Refund' . $deal->gateway->currency . time(),
                    'deal_url' =>$url
                ]);
            } else {
                $charge = number_format((float)($general->sell_user_fixed_fee) + (($deal->coin_amount * $general->sell_user_percentage_fee)/100) , 8, '.', '');
                $to_user = User::findOrFail($deal->from_user_id);
                $to_user_adress = UserCryptoBalance::where('user_id', $to_user->id)
                    ->where('gateway_id', $deal->gateway_id)->first();
                $old_balance = $to_user_adress->balance;
                $main_bal = $to_user_adress->balance + $deal->coin_amount + $charge;
                $to_user_adress->balance = $main_bal;
                $to_user_adress->save();
    
                $url="/user/deal/$deal->trans_id";
                Trx::create([
                    'user_id' => $to_user->id,
                    'pre_main_amo' => number_format((float)$old_balance , 8, '.', '') .' '.$deal->gateway->currency,
                    'amount' => number_format((float)$deal->coin_amount + $charge , 8, '.', '') .' '.$deal->gateway->currency,
                    'main_amo' => number_format((float)$main_bal , 8, '.', '') .' '.$deal->gateway->currency,
                    'charge' => number_format((float)0, 8, '.', '') . ' ' . $deal->gateway->currency,
                    'type' => '+',
                    'title' => 'Refunded by Admin',
                    'trx' => 'Refund' . $deal->gateway->currency . time(),
                    'deal_url' =>$url
                ]);
            }
            if($deal->advertiser_id == $deal->to_user_id){
                if($deal->add_type==2){
                    $url="user/deal/$deal->trans_id";
                    $url1="user/deal-reply/$deal->trans_id";
                }else{
                    $url="user/deal-reply/$deal->trans_id";
                    $url1="user/deal/$deal->trans_id";
                }
                $ee=$deal->to_user_id;
                $rr=$deal->from_user_id;
                $notification=[];
               $notification['from_user'] = $rr;
               $notification['to_user'] =$ee;
               $notification['noti_type'] ='deal';
               $notification['action_id'] =$deal->id;
               $notification['message']= 'Admin Cancelled Your Deal '.$deal->trans_id;
               
               $notification['url'] =$url;
               $notification['add_type']=$deal->add_type;
               $notification['deal_id']=$deal->id;
               $notification['advertisement_id']=$deal->advertisement_id;
               
               Notification::create($notification);

               $notification=[];
               $notification['from_user'] = $ee;
               $notification['to_user'] =$rr;
               $notification['noti_type'] ='deal';
               $notification['action_id'] =$deal->id;
               $notification['message']= 'Admin Cancelled Your Deal '.$deal->trans_id;
               
               $notification['url'] =$url1;
               $notification['add_type']=$deal->add_type;
               $notification['deal_id']=$deal->id;
               $notification['advertisement_id']=$deal->advertisement_id;
               
               Notification::create($notification);
            }
        
        
        
        $deal->save();

        return redirect()->back()->with('message', 'BTC Returned to Seller');
    }
    public function confirmDispute($id)
    {
        $deal = AdvertiseDeal::findOrFail($id);
        
        $deal->status = 10;
        $deal->save();

        return redirect()->back()->with('message', 'Marked Disputed');
    }
    public function addresses(){
        $data['page_title'] = 'Upload Addresses';
        return view('admin.addresses', $data);
    }
    public function addressesUpload(Request $request){
        $file = $request->file('file');

      // File Details 
      $filename = $file->getClientOriginalName();

      $extension = $file->getClientOriginalExtension();
      $tempPath = $file->getRealPath();
      $fileSize = $file->getSize();
      $mimeType = $file->getMimeType();
      $filename = time().'.csv';
      // Valid File Extensions
      $valid_extension = array("csv");

      // 2MB in Bytes
      $maxFileSize = 4097152; 

      // Check file extension
      if(in_array(strtolower($extension),$valid_extension)){

        // Check file size
        if($fileSize <= $maxFileSize){

          // File upload location
          $location = 'images/attach/';

          // Upload file
        //   $file->move($location,$filename);
        $cont = file_get_contents($file);
          Storage::put($location.$filename, $cont, 'public');

          // Import CSV to Database
          $filepath = storage_path().'/app/public/'.$location.$filename;

          // Reading file
          $file = fopen($filepath,"r");

          $importData_arr = array();
          $i = 0;

          while (($filedata = fgetcsv($file, 1000, ",")) !== FALSE) {
             $num = count($filedata );
             
             // Skip first row (Remove below comment if you want to skip the first row)
             /*if($i == 0){
                $i++;
                continue; 
             }*/
             for ($c=0; $c < $num; $c++) {
                $importData_arr[$i][] = $filedata [$c];
             }
             $i++;
          }
          fclose($file);

          Storage::delete($filepath);
          // Insert to MySQL database
          foreach($importData_arr as $importData){
            try{
                $insertData = array(
                "addresses"=>$importData[0],
                "status"=>$importData[1],
                "wallet"=>$importData[2]);
              
                WalletAddresses::create($insertData);
            }catch(\Illuminate\Database\QueryException $ex){ 
                // dd($ex->getMessage()); 
                \Session::flash('alert','Duplicate address inserted in file');
                // Note any method of class PDOException can be called on $ex.
            }

          }

          \Session::flash('message','Import Successful.');
        }else{
          \Session::flash('alert','File too large. File must be less than 4MB.');
        }

      }else{
         \Session::flash('alert','Invalid File Extension.');
      }
      return redirect()->back();


    }
    public function admins(){
        $data['page_title'] = 'Manage Admin Users';
        $data['admins'] =Admin::where('is_pro','0')->get();
        return view('admin.admins', $data);
    }
    public function adminsCreate(){
        $data['page_title'] = 'Create Admin Users';
        $data['roles'] = AdminRoles::get();
        return view('admin.admins_view_add', $data);
    }
    public function adminsCreateSave(createUsers $request){
        
        $data =$request->all();
        if ($request->hasFile('image')) {
            
            $file = $request->file('image');
            $location = 'admin/';
            $filename=time().'.jpg';
          
            $file=file_get_contents($file);
          Storage::put($location.$filename, $file, 'public');

            $data['image'] = 'storage/admin/'.$filename;
            
        }
       $data['username']= str_replace(' ','',$data['name']);
       $data['password']=Hash::make($data['password']);
        Admin::create($data);

        $notification =  array('message' => 'Admin Created Successfully', 'alert-type' => 'success');
        return redirect('adminio/admins')->with($notification);


    }
    public function adminsEdit($id){
        $data['admin']=Admin::find($id);
        $data['page_title'] = 'Edit Admin User';
        $data['roles'] = AdminRoles::get();
        return view('admin.admins_view_add', $data);
    }
    public function adminsEditSave(createUsers $request,$id){
        $admin= Admin::find($id);
        $data =$request->all();
        if ($request->hasFile('image')) {
            
            $file = $request->file('image');
            $location = 'admin/';
            $filename=time().'.jpg';
          
        $file=file_get_contents($file);
          Storage::put($location.$filename, $file, 'public');

            $data['image'] = 'storage/admin/'.$filename;
            
        }
       $data['username']= str_replace(' ','',$data['name']);
       if($data['password'] != ''){
        $data['password']=Hash::make($data['password']);
       }else{
           unset($data['password']);
       }
       $admin->update($data);

       $notification =  array('message' => 'Admin Updated Successfully', 'alert-type' => 'success');
        return redirect('adminio/admins')->with($notification);
       
    }public function adminsDelete($id){
        $admin= Admin::find($id);
        $admin->delete();
        $notification =  array('message' => 'Admin Deleted Successfully', 'alert-type' => 'success');
        return redirect('adminio/admins')->with($notification);
        
    }
    public function logout()
    {
        Auth::guard('admin')->logout();
        session()->flash('message', 'Just Logged Out!');
        return redirect('/adminio');
    }
    public function overview(){

        $query = User::with(['country', 'cryptoBalances', 'cryptoAddvertises'])->latest();
        $page_title = "Overview Report";
        $users = $query->get();
        foreach($users as $user){
            $user->adds =Advertisement::where('user_id', $user->id)->count();
            $ee =UserCryptoBalance::where('user_id', $user->id)->first();
            if(!empty($ee)){
                $user->blnce =$ee->balance;
            }else{
                $user->blnce = 0;
            }
            $trade_btc = AdvertiseDeal::where('gateway_id', 505)->where('status', 1)->where(function ($query) use ($user) {
                $query->where('to_user_id', $user->id);
                $query->orWhere('from_user_id', $user->id);
            });
            $disputes = AdvertiseDeal::where('gateway_id', 505)->where('status', 10)->where(function ($query) use ($user) {
                $query->where('to_user_id', $user->id);
                $query->orWhere('from_user_id', $user->id);
            })->count();
            $user->trade_btc =number_format((float)$trade_btc->sum('coin_amount') , 8, '.', '') ;
            $user->opendeals = AdvertiseDeal::where('gateway_id', 505)->where('status','!=', 1)->where('status','!=', 21)->where('status','!=', 2)->where(function ($query) use ($user) {
                $query->where('to_user_id', $user->id);
                $query->orWhere('from_user_id', $user->id);
            })->count();
            $user->disputes =$disputes;
        }
        $countries = Country::all();
        $cities = Cities::all();
        $country_id='';
        $city='';
        $verified='';
        $active='';
        $ads='';

        return view('admin.report.overview', compact('page_title', 'users','countries','cities','country_id','city','verified','active','ads'));
    }
    public function overviewSearch(Request $request){
        $country_id='';
        $city='';
        $verified='';
        $active='';
        $ads='';
        $query = User::with(['country', 'cryptoBalances', 'cryptoAddvertises'])->latest();
        $page_title = "Overview Report";
        if($request->has('country_id') && $request->country_id !=''){
            $query->orWhere('country_id', $request->country_id);
            $country_id=$request->country_id;
        }
        if($request->has('city') && $request->city !=''){
            $query->orWhere('city', $request->city);
            $city=$request->city;
        }
        if($request->has('verified') && $request->verified !=''){
            $query->orWhere('verified', $request->verified);
            $verified=$request->verified;
        }
        if($request->has('active') && $request->active !=''){
            $query->orWhere('status', $request->active);
            $active=$request->active;
        }
        if($request->has('ads') && $request->ads !=''){
            $ads =$request->ads;
        }
        
        // print_r($query->get()) ;exit;
        $users = $query->get();
        foreach($users as $key => $user){
            $user->adds =Advertisement::where('user_id', $user->id)->count();
            if($request->has('ads') && $request->ads !=''){
                if($request->ads == '0'){
                    if($user->adds > '0'){
                        $users->forget($key);
                        continue;
                    }
                }
                elseif($request->ads == '1'){
                    if($user->adds <= '0'){
                        $users->forget($key);
                        continue;
                    }
                }
                
            }
            
            $ee =UserCryptoBalance::where('user_id', $user->id)->first();
            if(!empty($ee)){
                $user->blnce =$ee->balance;
            }else{
                $user->blnce = 0;
            }
            $trade_btc = AdvertiseDeal::where('gateway_id', 505)->where('status', 1)->where(function ($query) use ($user) {
                $query->where('to_user_id', $user->id);
                $query->orWhere('from_user_id', $user->id);
            });
            $disputes = AdvertiseDeal::where('gateway_id', 505)->where('status', 10)->where(function ($query) use ($user) {
                $query->where('to_user_id', $user->id);
                $query->orWhere('from_user_id', $user->id);
            })->count();
            $user->trade_btc = $trade_btc->sum('coin_amount');
            $user->opendeals = AdvertiseDeal::where('gateway_id', 505)->where('status','!=', 1)->where(function ($query) use ($user) {
                $query->where('to_user_id', $user->id);
                $query->orWhere('from_user_id', $user->id);
            })->count();
            $user->disputes =$disputes;
        }
        $countries = Country::all();
        $cities = Cities::all();
        
        return view('admin.report.overview', compact('page_title', 'users','countries','cities','country_id','city','verified','active','ads'));

    }
    public function searchCity($search){
      return  $cities = Cities::where('city', $search)
        ->orWhere('city', 'like', '%' . $search . '%')->take(10)->pluck('city')->toArray();
    }
    public function ads(Request $request){
        $pm_id='';
        $username='';
        $add_id='';
        $currency='';
        $ads='';
        $country_id='';

        $page_title = "Advertisements";
        $adds=Advertisement::orderBy('id', 'desc')->paginate(10);
        
        $currencies=currency::all();
        $countries=Country::all();
        $pms=PaymentMethod::all();
        $users=User::where('status',1)->get();
        
        return view('admin.ads', compact('page_title','countries','country_id','users','add_id' ,'adds','ads','currency','pm_id','username','currencies','pms'));
        
    }
    public function activeAds(Request $request){
        $pm_id='';
        $username='';
        $add_id='';
        $currency='';
        $ads='';
        $country_id='';

        $page_title = "Advertisements";
        $adds=Advertisement::where('status', 1)->orderBy('id', 'desc')->paginate(10);
        
        $currencies=currency::all();
        $countries=Country::all();
        $pms=PaymentMethod::all();
        $users=User::where('status',1)->get();
        // echo json_encode(array(
        //     "page_title" => $page_title,
        //     "adds" => $adds,
        //     "currencies" => $currencies,
        //     "countries" => $countries,
        //     "pms" => $pms,
        //     "users" => $users,
        // ));exit;
        return view('admin.ads', compact('page_title','countries','country_id','users','add_id' ,'adds','ads','currency','pm_id','username','currencies','pms'));
        
    }

    public function inactiveAds(Request $request){
        $pm_id='';
        $username='';
        $add_id='';
        $currency='';
        $ads='';
        $country_id='';

        $page_title = "Advertisements";
        $adds=Advertisement::where('status', 0)->orderBy('id', 'desc')->paginate(10);
        
        $currencies=currency::all();
        $countries=Country::all();
        $pms=PaymentMethod::all();
        $users=User::where('status',1)->get();
        
        return view('admin.ads', compact('page_title','countries','country_id','users','add_id' ,'adds','ads','currency','pm_id','username','currencies','pms'));
        
    }
    public function ads24hours(Request $request){
        $pm_id='';
        $username='';
        $add_id='';
        $currency='';
        $ads='';
        $country_id='';

        $page_title = "Advertisements";
        $adds=Advertisement::where('created_at', '>', Carbon::now()->subDay())->where('created_at', '<=', Carbon::now())->orderBy('id', 'desc')->paginate(10);
        
        $currencies=currency::all();
        $countries=Country::all();
        $pms=PaymentMethod::all();
        $users=User::where('status',1)->get();
        
        return view('admin.ads', compact('page_title','countries','country_id','users','add_id' ,'adds','ads','currency','pm_id','username','currencies','pms'));
        
    }
    public function adsSearch($pm_id, $username, $add_id, $country_id, $currency){
        $pm_id == 'null' ? $pm_id = '' : '';
        $username == 'null' ? $username = '' : '';
        $add_id == 'null' ? $add_id = '' : '';
        $country_id == 'null' ? $country_id = '' : '';
        $currency == 'null' ? $currency = '' : '';
        $ads='';
        $adds=Advertisement::orderBy('id', 'desc');
        if($pm_id != null && $pm_id!=''){
            $adds->Where('method_id', $pm_id);
            $pm_id=$pm_id;
        }
        if($username != null && $username!=''){
            $uss =User::where('username',$username)->first();
            if(!empty($uss)){
                $adds->Where('user_id', $uss->id);
                
            }$username=$username;
            
        }
        if($add_id != null && $add_id!=''){
            $adds->Where('id', $add_id);
            $add_id=$add_id;
        }
        if($currency != null && $currency!=''){
            $adds->Where('currency_id', $currency);
            $currency=$currency;
        }
        if($country_id != null && $country_id!=''){
            $adds->Where('country_id', $country_id);
            $country_id=$country_id;
        }
        $page_title = "Advertisements";
        $adds=$adds->paginate(10);
        
        $currencies=currency::all();
        $pms=PaymentMethod::all();
        $countries=Country::all();
        $users=User::where('status',1)->get();
        return view('admin.ads', compact('page_title','country_id','countries','users','add_id' ,'adds','ads','currency','pm_id','username','currencies','pms'));
        
    }
    public function adsSearch_bkp(Request $request){
        $pm_id='';
        $username='';
        $add_id='';
        $country_id='';
        $currency='';
        $ads='';
        $adds=Advertisement::orderBy('id', 'desc');
        if($request->has('pm_id') && $request->pm_id!=''){
            $adds->Where('method_id', $request->pm_id);
            $pm_id=$request->pm_id;
        }
        if($request->has('username') && $request->username!=''){
            $uss =User::where('username',$request->username)->first();
            if(!empty($uss)){
                $adds->Where('user_id', $uss->id);
                
            }$username=$request->username;
            
        }
        if($request->has('add_id') && $request->add_id!=''){
            $adds->Where('id', $request->add_id);
            $add_id=$request->add_id;
        }
        if($request->has('currency') && $request->currency!=''){
            $adds->Where('currency_id', $request->currency);
            $currency=$request->currency;
        }
        if($request->has('country_id') && $request->country_id!=''){
            $adds->Where('country_id', $request->country_id);
            $country_id=$request->country_id;
        }
        $page_title = "Advertisements";
        $adds=$adds->paginate(10);
        
        $currencies=currency::all();
        $pms=PaymentMethod::all();
        $countries=Country::all();
        $users=User::where('status',1)->get();
        return view('admin.ads', compact('page_title','country_id','countries','users','add_id' ,'adds','ads','currency','pm_id','username','currencies','pms'));
        
    }

}
