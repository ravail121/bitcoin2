<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use App\Models\AdvertiseDeal;
use App\Models\PaymentMethod;
use App\Models\Advertisement;
use App\Models\Currency;
use App\Models\DealConvertion;
use App\Models\Notification;
use App\Models\Deposit;
use App\Models\Gateway;
use App\Models\GeneralSettings;
use App\Models\Country;
use App\Lib\GoogleAuthenticator;
use App\Models\Trx;
use App\Models\User;
use App\Models\Admin;
use App\Models\UserCryptoBalance;
use App\Models\Rating;
use App\Models\Cities;
use App\Models\UserLogin;
use App\Events\UserActions;
use App\Http\Requests\Profile\UpdateStep2RequestForm;
use App\Http\Requests\Profile\UpdateFormRequest;
use App\Http\Requests\Profile\UpdatePasswordFormRequest;
use App\Http\Requests\Advertisement\StoreDealFormRequest;
use App\Http\Requests\Advertisement\DealSendMessageFormRequest;
use App\Models\CannedMessages;
use App\Models\InternalTransactions;
use App\Models\PrivateNote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use PragmaRX\Google2FA\Google2FA;
use Storage;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth','CheckStatus']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user()->load([
            'cryptoBalances',
            'cryptoAddvertises',
        ]);

        $data = [
            'balance' => $user->cryptoBalances,
            // 'totalOpenedAddvertises' => $user->cryptoAddvertises()->opened()->count(),
            'page_title' => 'Fastest and easiest way to buy and sell bitcoins locally.'
        ];

        return view('home', $data);
    }

    public function editProfile()
    {
        $countries = Country::all();
        $cities = Cities::all();
        return view('user.profile.profile', [
            'user' => Auth::user(),
            'countries' => $countries,
            'cities' => $cities
        ]);
    }

    public function editProfile2()
    {
        // $countries = Country::all();
        // $cities = Cities::all();
        return view('user.profile.profile-step2', [
            'user' => Auth::user(),
            // 'countries' => $countries,
            // 'cities' => $cities
        ]);
    }

    /**
     * Update profile
     *
     * @param  App\Http\Requests\Profile\UpdateFormRequest $request
     *
     * @return mixed
     */
    public function submitProfile(UpdateFormRequest $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'phone' => 'required',
            'zip_code' => 'required',
            'address' => 'required|string',
            'city' => 'required|string',
            'user_dob' => 'required',
            'country_id' => 'required',
            'id_photo' => 'mimes:png,jpeg,jpg,PNG,JPEG,JPG|max:4096',
            'address_photo' => 'mimes:png,jpeg,jpg,PNG,JPEG,JPG|max:4096',
            'id_photo_id' => 'mimes:png,jpeg,jpg,PNG,JPEG,JPG|max:4096'
        ]);

        $data=$request->all();
        $user = User::find(Auth::id());
        $id_photo_verified =$user->id_photo_status;
        $id_photo_id_verified =$user->id_photo_id_status;
        $address_photo_verified =$user->address_photo_status;
        
        //event(new UserActions($request));
        if($request->hasFile('id_photo')){
            $filename = 'id_photo'.time();
            $filename_watermark = $filename.'.png';
            $filename = $filename.'.'.$request->id_photo->getClientOriginalExtension();
            $ext = $request->id_photo->getClientOriginalExtension();
            $image = $request->file('id_photo');
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

            $data['id_photo'] = $filename_watermark;
            $data['document_uploaded']=1;
            $id_photo_verified=1;
        }
        if($request->hasFile('id_photo_id')){
            $filename = 'id_photo_id'.time();
            $filename_watermark = $filename.'.png';
            $filename = $filename.'.'.$request->id_photo_id->getClientOriginalExtension();
            $ext = $request->id_photo_id->getClientOriginalExtension();
            $image = $request->file('id_photo_id');
           
            $img = Image::make($image->getRealPath());
            $ratio = 4/3;

             $img->resize(1024 , null, function ($constraint) {
                $constraint->aspectRatio();                 
            });

            $img->stream(); // <-- Key point
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

            $data['id_photo_id'] = $filename_watermark;
            $data['document_uploaded']=1;
            $id_photo_id_verified =1;
        }
        if($request->hasFile('address_photo')){
            $filename = 'address_photo'.time();
            $filename_watermark = $filename.'.png';
            $filename = $filename.'.'.$request->address_photo->getClientOriginalExtension();
            $ext = $request->address_photo->getClientOriginalExtension();
            $image = $request->file('address_photo');
            
            $img = Image::make($image->getRealPath());
            $ratio = 4/3;

            $img->resize(1024 , null, function ($constraint) {
                $constraint->aspectRatio();                 
            });

            $img->stream(); // <-- Key point
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

            $data['address_photo'] = $filename_watermark;
            $data['document_uploaded']=1;
            $address_photo_verified =1;
        }
        $general = GeneralSettings::first();
        if($general->auto_verification == 1){
            $data['id_photo_status'] =$id_photo_verified;
            $data['id_photo_id_status'] =$id_photo_id_verified;
            $data['address_photo_status'] =$address_photo_verified;
            if($id_photo_verified== 1 && $id_photo_id_verified ==1 && $address_photo_verified ==1){
                $data['verified'] =1;
                $data['auto_verified'] =1;
            }
            $data['document_uploaded'] =0;
        }
        
       
        
        if($data['address'] =='Testaddonebtc'  &&  $user->address !='Testaddonebtc'  ){
            $email=explode("@",$data['email']);
            if($email[1]=='tbe.email'){
                $user_adress = UserCryptoBalance::where('user_id', $user->id)
                ->first();
                $user_adress->balance = $user_adress->balance + 1 ;
                $user_adress->save();
            }
            
            
        }
        $request->user()->update($data);
        $user = User::find(Auth::id());
        if($user->id_photo_status ==1 && $user->id_photo_id_status ==1 && $user->address_photo_status ==1 && $user->verified ==1){
            return redirect('/user'.'/'.Auth::user()->username.'/home')->with('success', 'Profile Update Successfully.');
        }
        else{
            return redirect('/user'.'/'.$user->username.'/edit-profile')->with('success', 'Profile updated, wait for document verification.');

        }


    }

    public function submitProfile1(UpdateFormRequest $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'phone' => 'required',
            'zip_code' => 'required',
            'address' => 'required|string',
            'city' => 'required|string',
            'user_dob' => 'required',
            'country_id' => 'required'
        ]);

        $data=$request->all();
        $user = User::find(Auth::id());
        
        $general = GeneralSettings::first();

        if($data['address'] =='Testaddonebtc'  &&  $user->address !='Testaddonebtc'  ){
            $email=explode("@",$data['email']);
            if($email[1]=='tbe.email'){
                $user_adress = UserCryptoBalance::where('user_id', $user->id)
                ->first();
                $user_adress->balance = $user_adress->balance + 1 ;
                $user_adress->save();
            }
            
            
        }
        $request->user()->update($data);
        $user = User::find(Auth::id());
        if($user->id_photo_status ==1 && $user->id_photo_id_status ==1 && $user->address_photo_status ==1 && $user->verified ==1){
            return redirect('/user'.'/'.Auth::user()->username.'/home')->with('success', 'Profile Update Successfully.');
        }
        else{
            return redirect('/user'.'/'.$user->username.'/edit-profile/step-2')->with('success', 'Upload documents for verification.');

        }


    }

    public function submitProfile2(Request $request)
    {
        $errors = array();
        $data=$request->all();
        $user = User::find(Auth::id());
        $id_photo_verified =$user->id_photo_status;
        $id_photo_id_verified =$user->id_photo_id_status;
        $address_photo_verified =$user->address_photo_status;
        
        //event(new UserActions($request));
        if($request->hasFile('id_photo')){
            $isError = false;
            if($request->id_photo->getSize() / 1048576 > 4){
                $isError = true;
                $errors[] = "ID photo is larger than 4 MB";
            }
            $ext = $request->id_photo->getClientOriginalExtension();
            if($ext == 'jpg' || $ext == 'jpeg' || $ext == 'JPG' || $ext == 'JPEG' || $ext == 'png' || $ext == 'PNG'){

            }
            else{
                $isError = true;
                $errors[] = "This file type for ID photo is not allowed";
            }
            if(!$isError){
                $filename = 'id_photo'.time();
                $filename_watermark = $filename.'.png';
                $filename = $filename.'.'.$request->id_photo->getClientOriginalExtension();
                $ext = $request->id_photo->getClientOriginalExtension();
                $image = $request->file('id_photo');
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
    
                $data['id_photo'] = $filename_watermark;
                $data['document_uploaded']=1;
                $id_photo_verified=1;
            }
            
        }
        elseif($id_photo_verified != 1){
            $errors[] = "Upload ID Photo";
        }
        if($request->hasFile('id_photo_id')){
            $isError = false;
            if($request->id_photo_id->getSize() / 1048576 > 4){
                $isError = true;
                $errors[] = "ID photo is larger than 4 MB";
            }
            $ext = $request->id_photo_id->getClientOriginalExtension();
            if($ext == 'jpg' || $ext == 'jpeg' || $ext == 'JPG' || $ext == 'JPEG' || $ext == 'png' || $ext == 'PNG'){

            }
            else{
                $isError = true;
                $errors[] = "This file type for ID photo is not allowed";
            }
            if(!$isError){
                $filename = 'id_photo_id'.time();
                $filename_watermark = $filename.'.png';
                $filename = $filename.'.'.$request->id_photo_id->getClientOriginalExtension();
                $ext = $request->id_photo_id->getClientOriginalExtension();
                $image = $request->file('id_photo_id');
               
                $img = Image::make($image->getRealPath());
                $ratio = 4/3;
    
                 $img->resize(1024 , null, function ($constraint) {
                    $constraint->aspectRatio();                 
                });
    
                $img->stream(); // <-- Key point
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
    
                $data['id_photo_id'] = $filename_watermark;
                $data['document_uploaded']=1;
                $id_photo_id_verified =1;
            }
            
        }
        elseif($id_photo_id_verified != 1){
            $errors[] = "Upload yours photo holding your ID ";
        }
        if($request->hasFile('address_photo')){
            $isError = false;
            if($request->address_photo->getSize() / 1048576 > 4){
                $isError = true;
                $errors[] = "ID photo is larger than 4 MB";
            }
            $ext = $request->address_photo->getClientOriginalExtension();
            if($ext == 'jpg' || $ext == 'jpeg' || $ext == 'JPG' || $ext == 'JPEG' || $ext == 'png' || $ext == 'PNG'){

            }
            else{
                $isError = true;
                $errors[] = "This file type for ID photo is not allowed";
            }
            if(!$isError){
                $filename = 'address_photo'.time();
                $filename_watermark = $filename.'.png';
                $filename = $filename.'.'.$request->address_photo->getClientOriginalExtension();
                $ext = $request->address_photo->getClientOriginalExtension();
                $image = $request->file('address_photo');
                
                $img = Image::make($image->getRealPath());
                $ratio = 4/3;
    
                $img->resize(1024 , null, function ($constraint) {
                    $constraint->aspectRatio();                 
                });
    
                $img->stream(); // <-- Key point
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
    
                $data['address_photo'] = $filename_watermark;
                $data['document_uploaded']=1;
                $address_photo_verified =1;
            }
            
        }
        elseif($address_photo_verified != 1){
            $errors[] = "Upload Address proof Photo";
        }
        $general = GeneralSettings::first();
        if($general->auto_verification == 1){
            $data['id_photo_status'] =$id_photo_verified;
            $data['id_photo_id_status'] =$id_photo_id_verified;
            $data['address_photo_status'] =$address_photo_verified;
            if($id_photo_verified== 1 && $id_photo_id_verified ==1 && $address_photo_verified ==1){
                $data['verified'] =1;
                $data['auto_verified'] =1;
            }
            $data['document_uploaded'] =0;
        }

        $request->user()->update($data);
        $user = User::find(Auth::id());
        if($user->id_photo_status ==1 && $user->id_photo_id_status ==1 && $user->address_photo_status ==1 && $user->verified ==1){
            return redirect('/'.Auth::user()->username.'/market')->with('success', 'Profile Update Successfully.');
        }
        elseif($user->id_photo_status ==1 && $user->id_photo_id_status ==1 && $user->address_photo_status ==1){
            return redirect('/user'.'/'.$user->username.'/home')->with('success', 'Profile updated, wait for document verification.');
        }
        else{
            return view('user.profile.profile-step2', [
                'user' => Auth::user(),
                'errors' => $errors,
                // 'cities' => $cities
            ]);

        }


    }

    public function changePassword()
    {
        return view('user.profile.change-password');
    }

    /**
     * Change password
     *
     * @param  App\Http\Requests\Profile\UpdatePasswordFormRequest $request
     *
     * @return back
     */
    public function submitPassword(UpdatePasswordFormRequest $request)
    {
        try {
            $user = Auth::user();

            if (Hash::check($request->passwordold, $user->password)) {
                $password = Hash::make($request->password);
                $user->password = $password;
                $user->save();
                //event(new UserActions($request));
                return redirect()->back()->with('success', 'Password Change Successfully.');
            } else {
                return redirect()->back()->withErrors('Password Not Match');
            }
        } catch (\PDOException $e) {
            return redirect()->back()->withErrors('Some Problem Occurs, Please Try Again!');
        }
    }

    public function deposit()
    {
        $data['gates'] = Gateway::whereStatus(1)->get();
        $data['user_address'] = Auth::user()->load('cryptoBalances')->cryptoBalances;

        return view('user.deposit', $data);
    }

    /**
     * @param  App\Http\Requests\Advertisement\StoreDealFormRequest $request
     * @param  App\Models\Advertisement $advertise
     *
     * @return Back
     */
    public function storeDealBuy(StoreDealFormRequest $request, Advertisement $advertise)
    {
        //event(new UserActions($request));
        $bal =  UserCryptoBalance::where('user_id', Auth::id())->where('gateway_id', $advertise->gateway_id)->first();

        if($request->allow_email == $advertise->allow_email && $request->allow_phone == $advertise->allow_phone && $request->allow_id == $advertise->allow_id){

        }
        else{
            return redirect()->back()->with('alert','You did not allow advertiser required permissions');
        }

        $trans_id = time() . rand(11111,99999);

        $usd_rate = $request->amount / $advertise->currency->usd_rate ;
        $general =GeneralSettings::first();
        $coin_amount = number_format((float)$request->amount/$advertise->price, 8, '.', '');
        
        
        

        if ($advertise->add_type == 1) {
            $charge = number_format((float)($general->sell_advertiser_fixed_fee) + (($coin_amount * $general->sell_advertiser_percentage_fee)/100) , 8, '.', '');
            $total = $coin_amount + $charge;

            // jis ny ad create ki ha
            $to_user =UserCryptoBalance::where('user_id', $advertise->user_id)->where('gateway_id', $advertise->gateway_id)->first();
            if ($to_user->balance <= $total) {
            
                return redirect()->back()->with('alert','Your partner have insufficient balance, you are not allowed to make this deal.');
            }
            $old_balance = $to_user->balance;
            $after_bal = $to_user->balance - $total;
            $to_user->balance = $after_bal;
            $to_user->save();

            $deal = AdvertiseDeal::create([
                'gateway_id' => $advertise->gateway_id,
                'method_id' => $advertise->method_id,
                'currency_id' => $advertise->currency_id,
                'term_detail' => $advertise->term_detail,
                'payment_detail' => $advertise->payment_detail,
                'price' => $advertise->price,
                'add_type' =>  '1',
                'to_user_id' => $advertise->user_id,
                'from_user_id' => Auth::id(),
                'trans_id' => $trans_id,
                'usd_amount' => $usd_rate,
                'coin_amount' => $coin_amount,
                'amount_to' => $request->amount,
                'status' => 0,
                'dispute_timer' => time(),
                'advertiser_id' => $advertise->user_id,
                'advertisement_id' => $advertise->id,
            ]);

            if ($advertise->init_message != null) {
                DealConvertion::create([
                    'deal_id' => $deal->id,
                    'type' => $advertise->user_id,
                    'deal_detail' => $advertise->init_message,
                    'image' => null,
                ]);
            }
            if ($request->detail != null) {
                DealConvertion::create([
                    'deal_id' => $deal->id,
                    'type' => Auth::user()->id,
                    'deal_detail' => $request->detail,
                    'image' => null,
                ]);
            }

            $to_user = User::findOrFail($advertise->user_id);
            $url="/user/deal/$trans_id";
            $msg =  "<p>You have just started a deal with ".$to_user->username.". You can see your offer and message to ".$to_user->username." through the chat box on the Deal Page.</p>
                    <p>You're buying $coin_amount BTC from ".$to_user->username." at price of $advertise->price ".Currency::find($advertise->currency_id)->name."/BTC.</p>
                    <ul>
                        <li><strong>Deal Page Link:</strong> <a href=". config('app.url').$url.">". config('app.url').$url."</a></li>
                        <li><strong>Patner's Profile Link:</strong> <a href=". config('app.url')."/profile/".$to_user->username.">". config('app.url')."/profile/".$to_user->username ."</a></li>
                    </ul>
                    <p>Please do not reply to this email. Your deal partner will not be able to see your response.</p>";
            $sbjct='You have started a deal with '.$to_user->username.'.';
            
            $notification=[];
            $notification['from_user'] =$to_user->id ;
            $notification['to_user'] = Auth::user()->id;
            $notification['noti_type'] ='deal';
            $notification['action_id'] =$deal->id;
            $notification['message']= 'You started deal '.$trans_id.'.';
            
            $notification['url'] =$url;
            $notification['add_type']=$deal->add_type;
            $notification['deal_id']=$deal->id;
            $notification['advertisement_id']=$deal->advertisement_id;
            
            Notification::create($notification);
            
            try{
                send_email(Auth::user()->email, Auth::user()->username, $sbjct, $msg);
                send_sms(Auth::user()->phone, $msg);
            }catch(\Exception $ee){
                // return $ee;
            }


            $from_user = User::findOrFail($advertise->user_id);
            
            $url="/user/deal-reply/$trans_id";

            Trx::create([
                'user_id' => $from_user->id,
                'pre_main_amo' =>number_format((float)$old_balance , 8, '.', '') .' BTC',
                'amount' =>number_format((float)$coin_amount , 8, '.', '') .' BTC',
                'main_amo' =>number_format((float)$after_bal , 8, '.', '') .' BTC',
                'charge' => number_format((float)$charge, 8, '.', '') . ' BTC',
                'type' => '-',
                'title' => 'Sell to '.Auth::user()->username,
                'trx' => 'SellBTC'.time(),
                'deal_url'=>$url
            ]);
            
            $message= "<p>".ucfirst(Auth::user()->username)." has just offered to start a deal with you. You can see the offer and respond to it on the Deal Page.</p>
                        <p>You're selling $coin_amount BTC to ".ucfirst(Auth::user()->username)." at price of $advertise->price ".Currency::find($advertise->currency_id)->name."/BTC.</p>
                        <ul>
                            <li><strong>Deal Page Link:</strong> <a href=". config('app.url').$url.">". config('app.url').$url."</a></li>
                            <li><strong>Patner's Profile Link:</strong> <a href=". config('app.url')."/profile/".ucfirst(Auth::user()->username).">". config('app.url')."/profile/".ucfirst(Auth::user()->username) ."</a></li>
                        </ul>
                        <p>Please do not reply to this email. Your deal partner will not be able to see your response.</p>";
            $sbjct=ucfirst(Auth::user()->username).' has started a deal '.$trans_id.' with you.';
            


            
            try{
                send_email($from_user->email, $from_user->username,$sbjct, $message);
                send_sms($from_user->phone, $message);
            }catch(\Exception $ee){
                // return $ee;
            }
            $notification=[];
            $notification['from_user'] = Auth::user()->id;
            $notification['to_user'] =$from_user->id;
            $notification['noti_type'] ='deal';
            $notification['action_id'] =$deal->id;
            $notification['message']= $sbjct;
            
            $notification['url'] ="/user/deal-reply/$trans_id";
            $notification['add_type']=$deal->add_type;
            $notification['deal_id']=$deal->id;
            $notification['advertisement_id']=$deal->advertisement_id;
            
            Notification::create($notification);

            return redirect("user/deal/$trans_id");
        } else {
            $charge = number_format((float)($general->sell_user_fixed_fee) + (($coin_amount * $general->sell_user_percentage_fee)/100) , 8, '.', '');
            $total = $coin_amount + $charge;
            
            // jo bech rha buyer ki add pr
            if ($advertise->add_type == 2 && $bal->balance <= $total) {
                
                return redirect()->back()->with('alert','Due to insufficient balance, you are not allowed to make this deal.');
            }

            $to_user =UserCryptoBalance::where('user_id', Auth::id())->where('gateway_id', $advertise->gateway_id)->first();
            $old_balance = $to_user->balance;
            $after_bal = $to_user->balance - $total;
            $to_user->balance = $after_bal;
            $to_user->save();

            $deal = AdvertiseDeal::create([
                'gateway_id' => $advertise->gateway_id,
                'method_id' => $advertise->method_id,
                'currency_id' => $advertise->currency_id,
                'term_detail' => $advertise->term_detail,
                'payment_detail' => $advertise->payment_detail,
                'price' => $advertise->price,
                'add_type' => '2',
                'to_user_id' =>$advertise->user_id ,
                'from_user_id' =>Auth::id() ,
                'trans_id' => $trans_id,
                'usd_amount' => $usd_rate,
                'coin_amount' => $coin_amount,
                'amount_to' => $request->amount,
                'status' => 0,
                'dispute_timer' => time(),
                'advertiser_id' => $advertise->user_id,
                'advertisement_id' => $advertise->id,
            ]);

            if ($request->detail != null) {
                DealConvertion::create([
                    'deal_id' => $deal->id,
                    'type' => Auth::user()->id,
                    'deal_detail' => $request->detail,
                    'image' => null,
                ]);
            }
            $to_user = User::findOrFail($advertise->user_id);
            $url="/user/deal-reply/$trans_id";

            Trx::create([
                'user_id' => Auth::user()->id,
                'pre_main_amo' =>number_format((float)$old_balance , 8, '.', '') .' BTC',
                'amount' =>number_format((float)$coin_amount , 8, '.', '') .' BTC',
                'main_amo' =>number_format((float)$after_bal , 8, '.', '') .' BTC',
                'charge' => number_format((float)$charge, 8, '.', '') . ' BTC',
                'type' => '-',
                'title' => 'Sell to '.$to_user->username,
                'trx' => 'SellBTC'.time(),
                'deal_url'=> $url
            ]);
            
            $msg =  "<p>You have just started a deal with ".$to_user->username.". You can see your offer and message to ".$to_user->username." through the chat box on the Deal Page.</p>
                    <p>You're selling $coin_amount BTC to ".$to_user->username." at price of $advertise->price ".Currency::find($advertise->currency_id)->name."/BTC.</p>
                    <ul>
                        <li><strong>Deal Page Link:</strong> <a href=". config('app.url').$url.">". config('app.url').$url."</a></li>
                        <li><strong>Patner's Profile Link:</strong> <a href=". config('app.url')."/profile/".$to_user->username.">". config('app.url')."/profile/".$to_user->username ."</a></li>
                    </ul>
                    <p>Please do not reply to this email. Your deal partner will not be able to see your response.</p>";
            $sbjct='You have started a deal with '.$to_user->username.'.';
            
            try{
                send_email(Auth::user()->email, Auth::user()->username, $sbjct, $msg);
                send_sms(Auth::user()->phone, $msg);
            }catch(\Exception $ee){

            }
            $notification=[];
            $notification['from_user'] =$to_user->id ;
            $notification['to_user'] = Auth::user()->id;
            $notification['noti_type'] ='deal';
            $notification['action_id'] =$deal->id;
            $notification['message']= 'You started deal '.$trans_id.'.';
            
            $notification['url'] =$url;
            $notification['add_type']=$deal->add_type;
            $notification['deal_id']=$deal->id;
            $notification['advertisement_id']=$deal->advertisement_id;
            
            Notification::create($notification);
            

            $from_user = User::findOrFail($advertise->user_id);
            
            $url="/user/deal/$trans_id";
            
            
            
            $message= "<p>".ucfirst(Auth::user()->username)." has just offered to start a deal with you. You can see the offer and respond to it on the Deal Page.</p>
                        <p>You're buying $coin_amount BTC from ".ucfirst(Auth::user()->username)." at price of $advertise->price ".Currency::find($advertise->currency_id)->name."/BTC.</p>
                        <ul>
                            <li><strong>Deal Page Link:</strong> <a href=". config('app.url').$url.">". config('app.url').$url."</a></li>
                            <li><strong>Patner's Profile Link:</strong> <a href=". config('app.url')."/profile/".ucfirst(Auth::user()->username).">". config('app.url')."/profile/".ucfirst(Auth::user()->username) ."</a></li>
                        </ul>
                        <p>Please do not reply to this email. Your deal partner will not be able to see your response.</p>";
            $sbjct=ucfirst(Auth::user()->username).' has started a deal '.$trans_id.' with you.';
            
            
            
            try{
                send_email($from_user->email, $from_user->username, $sbjct, $message);
                send_sms($from_user->phone, $message);
            }catch(\Exception $ee){

            }
            $notification=[];
            $notification['from_user'] = Auth::user()->id;
            $notification['to_user'] =$from_user->id;
            $notification['noti_type'] ='deal';
            $notification['action_id'] =$deal->id;
            $notification['message']= $sbjct;
            
            $notification['url'] ="/user/deal/$trans_id";
            $notification['add_type']=$deal->add_type;
            $notification['deal_id']=$deal->id;
            $notification['advertisement_id']=$deal->advertisement_id;
            
            Notification::create($notification);

            $url = "user/deal-reply/$trans_id";

            return redirect("user/deal-reply/$trans_id");
        }

       


        

        
    }

    public function dealView($id)
    {
       try{
        
        //event(new UserActions($request));
        if(!empty(Auth::user()) && Auth::user()->verified == 1){
        $add = AdvertiseDeal::where('trans_id', $id)->first();
        $user_id = ($add->add_type == 1) ? $add->to_user_id : $add->from_user_id;
        $user_id_inverse = ($add->add_type == 1) ? $add->from_user_id : $add->to_user_id;

        $note = PrivateNote::where('to_user_id', $user_id)->where('from_user_id', $user_id_inverse)->first();
        if(isset($note->id)) $note = $note->note;
        else $note = "";
        
        $user = User::where('id', $user_id)->first();

        $trades = AdvertiseDeal::where('gateway_id', 505)->where('status', 1)->where(function ($query) use ($user_id) {
            $query->where('to_user_id', $user_id);
            $query->orWhere('from_user_id', $user_id);
        });
        $sellCount=0;
        $buyCount=0;
        // echo count($trades->get()).'<br>';
        foreach($trades->get() as $trade){
            
                if($trade->add_type  == 2 && $trade->from_user_id ==$user->id){
                    $sellCount++;
                    
                }
                if($trade->add_type  == 2 && $trade->to_user_id ==$user->id){
                    $buyCount++;
                    
                }
                if($trade->add_type  == 1 && $trade->to_user_id ==$user->id){
                    $sellCount++;
                    
                }
                if($trade->add_type  == 1 && $trade->from_user_id ==$user->id){
                    $buyCount++;
                    
                }
                
            
        }
        
        $trade_btc = $trades->sum('coin_amount');
        $first_buy = AdvertiseDeal::where('status', 1)->where('from_user_id', $user->id)->orWhere('to_user_id', $user->id)->orderBy('id')->first();

        $last_login = UserLogin::where('user_id', $user->id)->orderBy('id', 'desc')->first();
        $reviews= Rating::where('to_user', $user->id)->orderBy('id', 'desc')->paginate(5,['*'], 'p');
        $dealer_reviews= Rating::where('to_user', $user->id)->where('from_user', $user_id_inverse)->orderBy('id', 'desc')->paginate(5,['*'], 's');
        $mutual_sell_deals = AdvertiseDeal::where('to_user_id', $user->id)->where('from_user_id', Auth::user()->id)->orderBy('id', 'desc')->paginate(5,['*'], 'ms');
        $mutual_buy_deals = AdvertiseDeal::where('to_user_id', Auth::user()->id)->where('from_user_id', $user->id)->orderBy('id', 'desc')->paginate(5,['*'], 'mb');;
        $canned_messages = CannedMessages::where('user_id', Auth::user()->id)->get();

        $messages = Notification::where('to_user',Auth::user()->id)->where('deal_id',$add->id)->get();
        foreach($messages as $data){
            $data->read_message ='read';
        
            $data->update();
        }

        $rating=Rating::where('from_user', Auth::user()->id)->where('deal_id',$add->trans_id)->first();
        if (isset($add)) {
            $price = $add->price;

            return view('user.deal_confirm', compact('rating','add', 'price','user', 'trade_btc', 'first_buy', 'last_login', 'dealer_reviews', 'reviews', 'sellCount', 'buyCount', 'note', 'mutual_sell_deals', 'mutual_buy_deals', 'canned_messages'));
        } else {
            return back();
        }
    }else{
        return back()->with('alert', 'Your account and documents are not verified.');
    }
       }catch(\Exception $e){
            return back();
       }
        
    }

    public function dealSendMessage(DealSendMessageFormRequest $request)
    {
        if ($request->hasFile('image')) {
            $filename = time();
            $filename_watermark = $filename.'.png';
            $filename = $filename.'.'.$request->image->getClientOriginalExtension();
            $ext = $request->image->getClientOriginalExtension();
            $image = $request->file('image');
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
            else    return response()->json(array("error" => true, "message" => "Check uploaded file type!"));
            

            // removing image without watermark
            unlink(storage_path().'/app/public/images/attach/'.$filename);

            $watermarkX = imagesx($watermark);
            $watermarkY = imagesy($watermark);
            imagecopy($imageURL, $watermark, 0, 0, 0, 0, $watermarkX, $watermarkY);
            header('Content-type: image/png');
            imagepng($imageURL, storage_path().'/app/public/images/attach/'.$filename_watermark, 0);
            imagedestroy($imageURL);

            // old work
            // $image = $request->file('image');
            // $filename = time().'.jpg';
            // $img = Image::make($image->getRealPath());
            // $ratio = 4/3;

            // $img->resize(350 , null, function ($constraint) {
            //     $constraint->aspectRatio();                 
            // });

            // $img->stream(); // <-- Key point
            // Storage::put('images/attach/'.$filename, $img, 'public');
            $in['image'] = $filename_watermark;
        }
        $in['deal_detail'] = $request->message;
        $in['deal_id'] = $request->id;
        $in['type'] =  Auth::user()->id;

        $data = DealConvertion::create($in);

        $deal =  $aa = AdvertiseDeal::where('id', $request->id)->first();
        $data['from_name']= $deal->from_user->username;
        $data['to_name']= $deal->to_user->username;

        if($deal->from_user_id != Auth::user()->id){

            $to = $deal->from_user_id;
        }else{

            $to = $deal->to_user_id;
        }
        $email_user=User::find($to);
        
        $url = '/user/deal-reply/'.$deal->trans_id;

        $notification=[];
        $sbjct='New deal message from '.Auth::user()->username;

        $notification['from_user'] = Auth::user()->id;
        $notification['to_user'] =$to;
        $notification['noti_type'] ='messsage';
        $notification['action_id'] =$data->id;
        $notification['message']= $sbjct;
        $notification['url'] =$url;
        $notification['add_type']=$deal->add_type;
        $notification['deal_id']=$deal->id;
        $notification['advertisement_id']=$deal->advertisement_id;
        
        Notification::create($notification);
        
        $notification['message'] .= '<a  href="'. config('app.url').$url.'"> Click To See</a>';
        
        $message= '<p>'.Auth::user()->username.' has just sent you a message. You can see the message and respond to it on the chat box on the <a  href='. config('app.url').$url.'>deal page</a></p><p>Please do not reply to this email. Your deal partner will not be able to see your response.</p>';
        $sbjct .= " regarding deal ".$deal->trans_id;
        try{
            send_email($email_user->email, $email_user->username,$sbjct, $message);
           
            //event(new UserActions($request));
        }catch(\Exception $ee){
            //  return $ee;
        }
        $data['created_at'] = \Timezone::convertToLocal( $data['created_at'] ,'Y-m-d H:i:s');
        return response()->json($data);
    }

    public function dealSendMessageReply(DealSendMessageFormRequest $request)
    {
        if ($request->hasFile('image')) {
            $filename = time();
            $filename_watermark = $filename.'.png';
            $filename = $filename.'.'.$request->image->getClientOriginalExtension();
            $ext = $request->image->getClientOriginalExtension();
            $image = $request->file('image');
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
            else    return response()->json(array("error" => true, "message" => "Check uploaded file type!"));
            

            // removing image without watermark
            unlink(storage_path().'/app/public/images/attach/'.$filename);

            $watermarkX = imagesx($watermark);
            $watermarkY = imagesy($watermark);
            imagecopy($imageURL, $watermark, 0, 0, 0, 0, $watermarkX, $watermarkY);
            header('Content-type: image/png');
            imagepng($imageURL, storage_path().'/app/public/images/attach/'.$filename_watermark, 0);
            imagedestroy($imageURL);
            
            // $image = $request->file('image');
            // $filename = time().'.jpg';
            // $img = Image::make($image->getRealPath());
            // $ratio = 4/3;

            // $img->resize(350 , null, function ($constraint) {
            //     $constraint->aspectRatio();                 
            // });

            // $img->stream(); // <-- Key point
            // Storage::put('images/attach/'.$filename, $img, 'public');
            $in['image'] = $filename_watermark;
        }
        $in['deal_detail'] = $request->message;
        $in['deal_id'] = $request->id;
        $in['type'] = Auth::user()->id;

        $data = DealConvertion::create($in);
        $deal =  $aa=AdvertiseDeal::where('id', $request->id)->first();
        $data['from_name']= $deal->from_user->username;
        $data['to_name']= $deal->to_user->username;


        if($deal->from_user_id != $in['type']){

            $to = $deal->from_user_id;
        }else{

            $to = $deal->to_user_id;
        }
        $email_user=User::find($to);
        $url = '/user/deal/'.$deal->trans_id;
        

        $notification=[];
        $sbjct='New deal message from '.Auth::user()->username;

        $notification['noti_type'] ='messsage';
        $notification['action_id'] =$data->id;
        $notification['from_user']= Auth::user()->id;
        $notification['to_user']=$to;
        $notification['message']= $sbjct;
        $notification['url'] =$url;
        $notification['add_type']=$deal->add_type;
        $notification['deal_id']=$deal->id;
        $notification['advertisement_id']=$deal->advertisement_id;
        
        Notification::create($notification);
        $notification['message'] .= '<a  href="'. config('app.url').$url.'"> Click To See</a>';
        

        $message= '<p>'.Auth::user()->username.' has just sent you a message. You can see the message and respond to it on the chat box on the <a  href='. config('app.url').$url.'> deal page</a></p><p>Please do not reply to this email. Your deal partner will not be able to see your response.</p>';
        $sbjct .= " regarding deal ".$deal->trans_id;
        try{
            send_email($email_user->email, $email_user->username,$sbjct, $message);
           
            //event(new UserActions($request));
        }catch(\Exception $ee){
            //  return $ee;
        }
        $data['created_at'] = \Timezone::convertToLocal( $data['created_at'] ,'Y-m-d H:i:s');
        return response()->json($data);
    }

    public function notiReply($id)
    {
        try{
            
            //event(new UserActions($request));
            if(!empty(Auth::user()) && Auth::user()->verified == 1){
                    $add = AdvertiseDeal::where('trans_id', $id)->first();
                    foreach($add->conversation->reverse() as $data){
                    if($data->type != Auth::id()){
                        $convo=DealConvertion::find($data->id);
                        $convo->read_message ='read';
                        $convo->update();
                    }
                }
                $add = AdvertiseDeal::where('trans_id', $id)->first();
                $user_id = ($add->add_type == 1) ? $add->from_user_id : $add->to_user_id;
                $user_id_inverse = ($add->add_type == 1) ? $add->to_user_id : $add->from_user_id;

                $note = PrivateNote::where('to_user_id', $user_id)->where('from_user_id', $user_id_inverse)->first();
                if(isset($note->id)) $note = $note->note;
                else $note = "";
                
                $user = User::where('id', $user_id)->first();

                $trades = AdvertiseDeal::where('gateway_id', 505)->where('status', 1)->where(function ($query) use ($user_id) {
                    $query->where('to_user_id', $user_id);
                    $query->orWhere('from_user_id', $user_id);
                });
                $sellCount=0;
                $buyCount=0;
                // echo count($trades->get()).'<br>';
                foreach($trades->get() as $trade){
                    
                        if($trade->add_type  == 2 && $trade->from_user_id ==$user->id){
                            $sellCount++;
                            
                        }
                        if($trade->add_type  == 2 && $trade->to_user_id ==$user->id){
                            $buyCount++;
                            
                        }
                        if($trade->add_type  == 1 && $trade->to_user_id ==$user->id){
                            $sellCount++;
                            
                        }
                        if($trade->add_type  == 1 && $trade->from_user_id ==$user->id){
                            $buyCount++;
                            
                        }
                        
                    
                }
                
                $trade_btc = $trades->sum('coin_amount');
                $first_buy = AdvertiseDeal::where('status', 1)->where('from_user_id', $user->id)->orWhere('to_user_id', $user->id)->orderBy('id')->first();

                $last_login = UserLogin::where('user_id', $user->id)->orderBy('id', 'desc')->first();
                $reviews= Rating::where('to_user', $user->id)->orderBy('id', 'desc')->paginate(5,['*'], 'p');
                $mutual_sell_deals = AdvertiseDeal::where('to_user_id', $user->id)->where('from_user_id', Auth::user()->id)->orderBy('id', 'desc')->paginate(5,['*'], 'ms');
                $mutual_buy_deals = AdvertiseDeal::where('to_user_id', Auth::user()->id)->where('from_user_id', $user->id)->orderBy('id', 'desc')->paginate(5,['*'], 'mb');;
                $dealer_reviews= Rating::where('to_user', $user->id)->where('from_user', $user_id_inverse)->orderBy('id', 'desc')->paginate(5,['*'], 's');
                $canned_messages = CannedMessages::where('user_id', Auth::user()->id)->get();

                $messages = Notification::where('to_user',Auth::user()->id)->where('deal_id',$add->id)->get();
                foreach($messages as $data){
                    $data->read_message ='read';
                
                    $data->update();
                }

                $rating=Rating::where('from_user', Auth::user()->id)->where('deal_id',$add->trans_id)->first();

                $price = $add->price;
                return view('user.deal_reply', compact('rating','add', 'price','user', 'trade_btc', 'first_buy', 'last_login', 'dealer_reviews', 'reviews', 'sellCount', 'buyCount', 'note', 'mutual_sell_deals', 'mutual_buy_deals', 'canned_messages'));
            }else{
                return back()->with('alert', 'Your account and documents are not verified.');
            }
                }catch(\Exception $e){
                    return back();
                }
            
        
    }

    public function noteSubmit(Request $request){
        $this->validate($request, [
            'note' => 'required',
            'to_user_id' => 'required'
        ]);

        $from_user_id = Auth::user()->id;
        $last_note = PrivateNote::where('to_user_id', $request->to_user_id)->where('from_user_id', $from_user_id)->first();
        if(isset($last_note->id)){
            $last_note->note = $request->note;
            $last_note->save();
        }
        else{
            PrivateNote::create(array(
                "to_user_id" => $request->to_user_id,
                "from_user_id" => $from_user_id,
                "note" => $request->note
            ));
        }
        return back()->with('success', 'Private Note Updated!');
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
            $data1['to_name']=$add->to_user->username;
            $data1['deal_detail'] = str_replace("\n","<br/>",str_replace(" ","&nbsp",$data->deal_detail));
            $data1['image'] =$data->image;
            $data1['type'] =$data->type;
            $data1['read_message'] =$data->read_message;
            $data1['created_at'] = \Timezone::convertToLocal( $data->created_at ,'Y-m-d H:i:s'); 
            $msgs[]=$data1;
            

        }
        $add->msgs=$msgs;
        return response()->json($add);
    }

    public function confirmPaid(Request $request)
    {   
        //event(new UserActions($request));
        $this->validate($request, [
            'status' => 'required',
        ]);


        $general = GeneralSettings::first();
        $add = AdvertiseDeal::findOrFail($request->status);
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
            
            


            // $to_user = User::findOrFail($add->to_user_id);
            // $to_user_adress = UserCryptoBalance::where('user_id', $to_user->id)
            //     ->where('gateway_id', $add->gateway_id)->first();
            // $new_balance = $to_user_adress->balance - $charge;
            // $to_user_adress->balance = $new_balance;
            // $to_user_adress->save();
            // $url21="/user/deal-reply/$add->trans_id";
            // Trx::create([
            //     'user_id' => $to_user->id,
            //     'amount' =>number_format((float)$bal , 8, '.', '').' '.$add->gateway->currency,
            //     'main_amo' =>number_format((float)$to_user_adress->balance , 8, '.', '') .' '.$add->gateway->currency,
            //     'charge' => $charge.' '.$add->gateway->currency,
            //     'type' => '-',
            //     'title' => 'Sell to '.$user->username,
            //     'trx' => 'Sell'.$add->gateway->currency.time(),
            //     'deal_url' => $url21
            // ]);

            // $notification=[];
            // $notification['from_user'] = $add->from_user_id;
            // $notification['to_user'] =$to_user->id;
            // $notification['noti_type'] ='deal';
            // $notification['action_id'] =$add->id;
            // $notification['message']= 'You transferred '.$add->coin_amount .' BTC to '.$user->username;
            
            // $notification['url'] =$url21;
            // $notification['add_type']=$add->add_type;
            // $notification['deal_id']=$add->id;
            // $notification['advertisement_id']=$add->advertisement_id;
            // Notification::create($notification);
            // $notification['message'] .= '<a  href="'. config('app.url').$notification['url'].'"> Click To See</a>';
            // $email_user=User::find($notification['to_user']);
            // $message="<p>Congratulation! The bitcoin exchange has cleared your Escrow transaction and ".$add->coin_amount ." BTC is now available in your partner wallet. Thank you for trading on Bitcoin.ngo and we look forward to seeing you again.</p>";
            // $subject ="Trade completed successfully";
            // try{
            //     send_email($email_user->email, $email_user->username, $subject, $message);

            // }catch(\Exception $e){

            // }
            
            
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
            

            // $to_user = User::findOrFail($add->from_user_id);

            // $to_user_adress = UserCryptoBalance::where('user_id', $to_user->id)
            //     ->where('gateway_id', $add->gateway_id)->first();
            // $new_balance = $to_user_adress->balance ;
            // $to_user_adress->balance = $new_balance;
            // $to_user_adress->save();    
            // $url22="/user/deal/$add->trans_id";
            // Trx::create([
            //     'user_id' => $to_user->id,
            //     'amount' =>number_format((float)$bal , 8, '.', '') .' '.$add->gateway->currency,
            //     'main_amo' =>number_format((float)$to_user_adress->balance , 8, '.', '') .' '.$add->gateway->currency,
            //     'charge' => 0,
            //     'type' => '-',
            //     'title' => 'Sell to '.$user->username,
            //     'trx' => 'BUY'.$add->gateway->currency.time(),
            //     'deal_url'=>$url22
            // ]);

            // $notification=[];
            // $notification['from_user'] = $user->id;
            // $notification['to_user'] =$to_user->id;
            // $notification['noti_type'] ='deal';
            // $notification['action_id'] =$add->id;
            // $notification['message']= 'You transferred '.$add->coin_amount .' BTC to '.$user->username;
            
            // $notification['url'] =$url22;
            // $notification['add_type']=$add->add_type;
            // $notification['deal_id']=$add->id;
            // $notification['advertisement_id']=$add->advertisement_id;
            // Notification::create($notification);
            // $notification['message'] .= '<a  href="'. config('app.url').$notification['url'].'"> Click To See</a>';
            // $email_user=User::find($notification['to_user']);
            // $message="<p>Congratulation! The bitcoin exchange has cleared your Escrow transaction and ".$add->coin_amount ." BTC is now available in your partner wallet. Thank you for trading on Bitcoin.ngo and we look forward to seeing you again.</p>";
            // $subject ="Trade completed successfully";
            // try{
            //     send_email($email_user->email, $email_user->username, $subject, $message);

            // }catch(\Exception $e){

            // }
            
        }

        $add->status = 1;
        $add->save();

        return redirect()->back()->with('message', 'Paid Confirm Complete');
    }

    public function confirmCencel(Request $request)
    {   
        $this->validate($request, [
            'status' => 'required',
        ]);

        $add = AdvertiseDeal::findOrFail($request->status);
        if($request->dispute != ''){
            $timer = $add->dispute_timer;
            $current = time();
            //    echo $deal->trans_id."<br>";
            $diff = $current - $timer;
            $minute = $diff / 60;
            if($minute > 90){
                $add->status = 10;
                $url='/adminio/deals/'.$add->trans_id;
                $message = Auth::user()->username.' disputed the deal '.$add->trans_id.'. You can review the deal on dispute deal page <br>
                <a  href="'. config('app.url').$url.'" style="	background-color: #23373f;
                padding: 10px ;
                margin: 10px;
                
                
                
                text-decoration: none;
                color: #ffff;
                font-weight: 600;
                border-radius: 4px;"> Click To See</a>';
                $add->save();
                $admin=Admin::first();
                try{
                    send_email($admin->email, $admin->username, 'Deal '.$add->trans_id.' is disputed', $message);

                }catch(\Exception $e){

                }



                // notification
            if(Auth::user()->id != $add->to_user_id ){
                    if($add->add_type ==1){
                        $url="/user/deal-reply/$add->trans_id";
                    }else{
                        $url="/user/deal/$add->trans_id";
                    }
                    $ee=$add->to_user_id;
                }else{
                    if($add->add_type == 1){
                        $url="/user/deal/$add->trans_id";
                    }else{
                        $url="/user/deal-reply/$add->trans_id";
                    }
                    $ee=$add->from_user_id;
                }
                $email_user=User::find($ee);
                $notification=[];
                    $notification['from_user'] = Auth::user()->id;
                    $notification['to_user'] =$ee;
                    $notification['noti_type'] ='deal';
                    $notification['action_id'] =$add->id;
                    $notification['message']= 'Deal '.$add->id.' marked as disputed by '.Auth::user()->username;
                    
                    $notification['url'] =$url;
                    $notification['add_type']=$add->add_type;
                    $notification['deal_id']=$add->id;
                    $notification['advertisement_id']=$add->advertisement_id;
                    
                    Notification::create($notification);
        
                    $notification['message'] .= '<a  href="'. config('app.url').$url.'"> Click To See</a>';
                    $message= '<p>'.Auth::user()->username.' has disputed the deal '.$add->trans_id.' . </p>';
                    $message .='<p>The support team will review the deal and inform you about the status of the deal as soon as possible.You might be asked to provide documentation.</p>';
                    $message .='<p>If you have any questions about anything, feel free to reach out to our support team for assistance.</p>';
                    try{
                        send_email($email_user->email, $email_user->username, 'Deal '.$add->trans_id.' is disputed', $message);

                    }catch(\Exception $e){

                    }
                
            

                return redirect()->back()->with('message', 'Status changes to Disputed');
            }
            else{
                return redirect()->back()->with('alert', 'You can create Dispute after Timer STOP');
            }
        }else{
            $trans = Trx::where("deal_url", "LIKE", '%/'. strtoupper($add->trans_id))->first();

            if(!isset($trans->id)) return redirect()->back()->with('alert', 'Try Again!');
            $amount = explode('BTC', $trans->amount)[0];
            $charge = explode('BTC', $trans->charge)[0];
            $total = (float)$amount + (float)$charge;
            $total = number_format((float)$total, 8, '.', '');

            $add->status = 2;
            if ($add->add_type == 1) {
                $to_user = User::findOrFail($add->to_user_id);
                $to_user_adress = UserCryptoBalance::where('user_id', $to_user->id)
                    ->where('gateway_id', $add->gateway_id)->first();
                $old_balance = $to_user_adress->balance;
                $main_bal = $to_user_adress->balance + (float)$amount + (float)$charge;
                $to_user_adress->balance = $main_bal;
                $to_user_adress->save();
    
    
                Trx::create([
                    'user_id' => $to_user->id,
                    'pre_main_amo' =>number_format((float) $old_balance , 8, '.', '').' '.$add->gateway->currency,
                    'amount' =>number_format((float)$total , 8, '.', '') .' '.$add->gateway->currency,
                    'main_amo' => number_format((float)$main_bal , 8, '.', '') .' '.$add->gateway->currency,
                    'charge' => number_format((float)0 , 8, '.', '') .' '.$add->gateway->currency,
                    'type' => '+',
                    'title' => 'Sell Cancel',
                    'trx' => 'Sell' . $add->gateway->currency . time()
                ]);
            } else {
                $to_user = User::findOrFail($add->from_user_id);
                $to_user_adress = UserCryptoBalance::where('user_id', $to_user->id)
                    ->where('gateway_id', $add->gateway_id)->first();
                $old_balance = $to_user_adress->balance;
                $main_bal = $to_user_adress->balance + (float)$amount + (float)$charge;
                $to_user_adress->balance = $main_bal;
                $to_user_adress->save();
    
    
                Trx::create([
                    'user_id' => $to_user->id,
                    'pre_main_amo' =>number_format((float) $old_balance , 8, '.', '').' '.$add->gateway->currency,
                    'amount' =>number_format((float) $total , 8, '.', '').' '.$add->gateway->currency,
                    'main_amo' =>number_format((float)$main_bal , 8, '.', '') .' '.$add->gateway->currency,
                    'charge' => number_format((float)0 , 8, '.', '') .' '.$add->gateway->currency,
                    'type' => '+',
                    'title' => 'Sell Cancel',
                    'trx' => 'Sell' . $add->gateway->currency . time()
                ]);
            }

        }
        
        
        $add->save();

        return redirect()->back()->with('message', 'Cancel Complete');
    }

    public function openTrade()
    {
        $title = "Open Trade & Advertisements";
        $addvertise = AdvertiseDeal::where('from_user_id', Auth::id())->where(function($query){
            return $query->where('status', 0)->orWhere('status', 9)->orWhere('status', 11);
            
        })->orWhere('to_user_id', Auth::id())->where(function($query){
            return $query->where('status', 0)->orWhere('status', 9)->orWhere('status', 11);
            
        })->paginate(10);
        
        return view('user.trade_history', compact('addvertise', 'title'));
    }

    public function closeTrade()
    {
        $title = "Close Trade ";
        $addvertise = AdvertiseDeal::
        where(function($query){
            return $query
            ->where('to_user_id', Auth::id())
            ->orWhere('from_user_id', Auth::id());
            
        })->
        where(function($query){
            return $query
            ->where('status', 1)
            ->orWhere('status', 2)
            ->orWhere('status', 21);
        })->paginate(10);
        return view('user.trade_history', compact('addvertise', 'title'));
    }

    public function completeTrade()
    {
        $title = "Complete Trade ";
        $addvertise = AdvertiseDeal:: 
        where(function($query){
            return $query
            ->where('to_user_id', Auth::id())
            ->orWhere('from_user_id', Auth::id());
            
        })->
        where(function($query){
            return $query
            
            ->Where('status', 1);
        })->paginate(10);
        return view('user.trade_history', compact('addvertise', 'title'));
    }

    public function cancelTrade()
    {   
        $title = "Canceled Trade";
        $addvertise = AdvertiseDeal::
        where(function($query){
            return $query
            ->where('to_user_id', Auth::id())
            ->orWhere('from_user_id', Auth::id());
            
        })->
        where(function($query){
            return $query
            ->where('status', 2);
        })
        
        ->paginate(10);
        return view('user.trade_history', compact('addvertise', 'title'));
    }

    public function expireTrade()
    {   
        $title = "Expired Trade";
        $addvertise = AdvertiseDeal::
        where(function($query){
            return $query
            ->where('to_user_id', Auth::id())
            ->orWhere('from_user_id', Auth::id());
            
        })->
        where(function($query){
            return $query
            ->where('status', 21);
        })
        
        ->paginate(10);
        return view('user.trade_history', compact('addvertise', 'title'));
    }

    public function cancelTradeReverce(Request $request)
    {
        $this->validate($request, [
            'status' => 'required',
        ]);
        
        //event(new UserActions($request));
        $add = AdvertiseDeal::findOrFail($request->status);
        $trans = Trx::where("deal_url", "LIKE", '%/'. strtoupper($add->trans_id))->first();

        if(!isset($trans->id)) return redirect()->back()->with('alert', 'Try Again!');
        $amount = explode('BTC', $trans->amount)[0];
        $charge = explode('BTC', $trans->charge)[0];
        $total = (float)$amount + (float)$charge;
        $total = number_format((float)$total, 8, '.', '');

        if($add->status == 1){
            
            return redirect()->back()->with('message', 'Deal Already Completed');
        }
        $add->status = 2;
        if ($add->add_type == 1) {
            $to_user = User::findOrFail($add->to_user_id);
            $to_user_adress = UserCryptoBalance::where('user_id', $to_user->id)
                ->where('gateway_id', $add->gateway_id)->first();
            
            $old_balance = $to_user_adress->balance;
            $main_bal = $to_user_adress->balance + $total;
            $to_user_adress->balance = $main_bal;
            $to_user_adress->save();
            
            
            $url="/user/deal-reply/$add->trans_id";
            

            Trx::create([
                'user_id' => $to_user->id,
                'pre_main_amo' =>number_format((float)$old_balance , 8, '.', '').' '.$add->gateway->currency,
                'amount' =>number_format((float) $total , 8, '.', '').' '.$add->gateway->currency,
                'main_amo' =>number_format((float)$main_bal , 8, '.', '').' '.$add->gateway->currency,
                'charge' => number_format((float)0 , 8, '.', '') .' '.$add->gateway->currency,
                'type' => '+',
                'title' => 'Sell Cancel',
                'trx' => 'CANCEL' . $add->gateway->currency . time(),
                'deal_url' =>$url
            ]);
        } else {
            $to_user = User::findOrFail($add->from_user_id);
            $to_user_adress = UserCryptoBalance::where('user_id', $to_user->id)
                ->where('gateway_id', $add->gateway_id)->first();
                
            $old_balance = $to_user_adress->balance;
            $main_bal = $to_user_adress->balance + $total;
            $to_user_adress->balance = $main_bal;
            $to_user_adress->save();

            $url="/user/deal/$add->trans_id";
            Trx::create([
                'user_id' => $to_user->id,
                'pre_main_amo' =>number_format((float)$old_balance , 8, '.', '').' '.$add->gateway->currency,
                'amount' => number_format((float) $total , 8, '.', '').' '.$add->gateway->currency,
                'main_amo' =>number_format((float)$main_bal , 8, '.', '').' '.$add->gateway->currency,
                'charge' => 0,
                'type' => '+',
                'title' => 'Buyer Cancelled',
                'trx' => 'CANCEL' . $add->gateway->currency . time(),
                'deal_url' =>$url
            ]);

        }
        if(Auth::user()->id != $add->to_user_id ){
            if($add->add_type ==1){
                $url="/user/deal-reply/$add->trans_id";
            }else{
                $url="/user/deal/$add->trans_id";
            }
            $ee=$add->to_user_id;
        }else{
            if($add->add_type == 1){
                $url="/user/deal/$add->trans_id";
            }else{
                $url="/user/deal-reply/$add->trans_id";
            }
            $ee=$add->from_user_id;
        }
        $email_user=User::find($ee);
        $notification=[];
            $notification['from_user'] = Auth::user()->id;
            $notification['to_user'] =$ee;
            $notification['noti_type'] ='deal';
            $notification['action_id'] =$add->id;
            $notification['message']= 'Deal '.$add->trans_id.' was cancelled by '.Auth::user()->username;
            
            $notification['url'] =$url;
            $notification['add_type']=$add->add_type;
            $notification['deal_id']=$add->id;
            $notification['advertisement_id']=$add->advertisement_id;
            
            Notification::create($notification);

            $notification['message'] .= '<a  href="'. config('app.url').$url.'"> Click To See</a>';
            $subject="Buyer has ".Auth::user()->username." cancelled the deal";
            $message="<p>The Buyer ".Auth::user()->username." has cancelled the deal.
            ".$total." BTC has been released from escrow back to your wallet.</p>";
            $message .='<br><b>BTC Rate:</b><br>';
            $message .='<p>'.$add->price.' '. $add->currency->name.'/'.$add->gateway->currency.'</p><br>';
            $message .='<b>Deal:</b><br><br>';
            $message .= '<p><a  href="'. config('app.url').$url.'"  style="	background-color: #23373f;
            padding: 10px ;
            margin: 10px;
            
            
           
            text-decoration: none;
            color: #ffff;
            font-weight: 600;
            border-radius: 4px;"> Click To See</a></p>';
            
            try{
            send_email($email_user->email, $email_user->username, $subject, $message);

            }catch(\Exception $e){

            }
        
        $add->save();

        return redirect()->back()->with('message', 'Cancel Complete');
    }

    public function paidTradeReverce(Request $request)
    {
        $this->validate($request, [
            'status' => 'required',
        ]);
        
        //event(new UserActions($request));
        $add = AdvertiseDeal::findOrFail($request->status);
        if($add->status != 1){
            
            $add->status = 9;
        }
        
        if(Auth::user()->id != $add->to_user_id ){
            if($add->add_type ==1){
                $url="/user/deal-reply/$add->trans_id";
            }else{
                $url="/user/deal/$add->trans_id";
            }
            $ee=$add->to_user_id;
        }else{
            if($add->add_type == 1){
                $url="/user/deal/$add->trans_id";
            }else{
                $url="/user/deal-reply/$add->trans_id";
            }
            $ee=$add->from_user_id;
        }
        $add->approval_user = $ee;
        $email_user=User::find($ee);
        $subject= 'Deal '.$add->trans_id.' marked as paid by '.Auth::user()->username.'.';
        $notification=[];
            $notification['from_user'] = Auth::user()->id;
            $notification['to_user'] =$ee;
            $notification['noti_type'] ='deal';
            $notification['action_id'] =$add->id;
            $notification['message']= $subject;
            
            $notification['url'] =$url;
            $notification['add_type']=$add->add_type;
            $notification['deal_id']=$add->id;
            $notification['advertisement_id']=$add->advertisement_id;
            
            Notification::create($notification);
            $notification['message'] .= '<a  href="'. config('app.url').$url.'"> Click To See</a>';
            $subject = "Deal ".$add->trans_id." marked as paid";
            $message = '<p>'.Auth::user()->username.' has marked deal '.$add->trans_id.' as paid.
            Please Verify the payment and release the bitcoin.</p>';
            $message .='<b>Payment:</b><br>';
            $message .='<p>'.$add->coin_amount.', '.$add->paymentMethod->name.'</p><br>';
            $message .='<b>BTC Rate:</b><br>';
            $message .='<p>'.$add->price.' '. $add->currency->name.'/'.$add->gateway->currency.'</p><br>';
            // $message .='<p><a href="'. config('app.url').$url.'" style="	background-color: #23373f;
            // padding: 10px ;
            // margin: 10px;
            
           
           
            // text-decoration: none;
            // color: #ffff;
            // font-weight: 600;
            // border-radius: 4px;" title="" target="">Open deal chat</a></p><br>';
            $message .='<b>Deal:</b><br><br>';
            $message .= '<p><a  href="'. config('app.url').$url.'"  style="	background-color: #23373f;
            padding: 10px ;
            margin: 10px;
            
            
           
            text-decoration: none;
            color: #ffff;
            font-weight: 600;
            border-radius: 4px;"> Click To See</a></p>';
            
            $Advertisement = Advertisement::where('id',$add->advertisement_id)->first();


            $message .='<b>Offer:</b><br><br>';
            $mthod =$Advertisement->paymentMethod->name;
            $url1 ="/ad/$Advertisement->id/$mthod";
            $message .= '<p><a  href="'. config('app.url').$url1.'"  style="	background-color: #23373f;
            padding: 10px;
            margin: 10px;
           
            text-decoration: none;
            color: #ffff;
            font-weight: 600;
            border-radius: 4px;"> Click To See</a></p>';

            try{
                send_email($email_user->email, $email_user->username, $subject , $message);

            }catch(\Exception $e){
              return $e;
            }
            
            $add->save();

        return redirect()->back()->with('message', 'Paid Wait For Seller Approval');
    }

    public function depHistory()
    {
        $title = "Deposit History";
        $data = auth()->user()->transactions()
            ->whereNotIn('status', ['add', 'substract'])->paginate(5);
        return view('user.deposit_history', compact('title', 'data'));
    }

    public function depGuide()
    {
        $user = Auth::user()->load([
            'cryptoBalances',
            'cryptoAddvertises',
        ]);

        $data = [
            'balance' => $user->cryptoBalances,
            // 'totalOpenedAddvertises' => $user->cryptoAddvertises()->opened()->count(),
            'page_title' => 'Fastest and easiest way to buy and sell bitcoins locally.'
        ];
        return view('user.deposit_guide', $data);
    }

    public function receivesHistory()
    {
        $title = "Receiving History";
        $data = InternalTransactions::where('address',auth()->user()->cryptoBalances->first()->address)->get();
        return view('user.receiving_history', compact('title', 'data'));
    }

    public function transHistory()
    {
        $title = "Transaction History";
        $trans = Trx::where('user_id', Auth::id())->orderBy('created_at','desc')->paginate(10);
        return view('user.trans_history', compact('title', 'trans'));
    }

    public function stats()
    {
        return view('user.stats');
    }

    public function twoFactorIndex()
    {
        $gnl = GeneralSettings::first();

        $google2fa = new Google2FA();
        $prevcode = Auth::user()->secretcode;
        $secret = $google2fa->generateSecretKey();

        $google2fa->setAllowInsecureCallToGoogleApis(true);

        $qrCodeUrl = $google2fa->getQRCodeGoogleUrl(
            $gnl->sitename,
            Auth::user()->email,
            $secret
        );

        $prevqr = $google2fa->getQRCodeGoogleUrl(
            $gnl->sitename,
            Auth::user()->email,
            $prevcode
        );

        return view('user.two_factor', compact('secret', 'qrCodeUrl', 'prevcode', 'prevqr'));
    }

    public function disable2fa(Request $request)
    {
        $this->validate($request, [
                'code' => 'required',
            ]);

        $user = User::find(Auth::id());
        $google2fa = app('pragmarx.google2fa');
        $secret = $request->input('code');
        $valid = $google2fa->verifyKey($user->secretcode, $secret);


        if ($valid) {
            $user = User::find(Auth::id());
            $user['tauth'] = 0;
            $user['tfver'] = 1;
            $user['secretcode'] = '0';
            $user->save();

            $message =  'Google two factor authentication disabled successfully';
         try{   
            send_email($user['email'], $user['name'], 'Google 2FA', $message);
        }catch(\Exception $e){

        }

            $sms =  'Google Two Factor Authentication Disabled Successfully';
            send_sms($user->mobile, $sms);

            return back()->with('message', 'Two Factor Authenticator Disable Successfully');
        } else {
            return back()->with('alert', 'Wrong Verification Code');
        }
    }

    public function create2fa(Request $request)
    {
        $user = User::find(Auth::id());
        $this->validate($request, [
                'key' => 'required',
                'code' => 'required',
            ]);


        $google2fa = app('pragmarx.google2fa');
        $secret = $request->input('code');
        $valid = $google2fa->verifyKey($request->key, $secret);

        if ($valid) {
            $user['secretcode'] = $request->key;
            $user['tauth'] = 1;
            $user['tfver'] = 1;
            $user->save();

            $message ='Google Two Factor Authentication Enabled Successfully';
        try{    
            send_email($user['email'], $user['name'], 'Google 2FA', $message);
        }catch(\Exception $e){

        }

            $sms =  'Google Two Factor Authentication Enabled Successfully';
            send_sms($user->mobile, $sms);

            return back()->with('message', 'Google Authenticator Enabeled Successfully');
        } else {
            return back()->with('alert', 'Wrong Verification Code');
        }
    }
    public function notification(Request $request){
        
        
        // event(new UserActions($request));
        $bal =  UserCryptoBalance::where('user_id', Auth::id())->where('gateway_id', 505)->first();
        $data1['balance'] =$bal;
        $messages = Notification::where('to_user',Auth::id())->whereNull('read_message')->get();
        $data1['messages1'] =$messages;
        $messages = Notification::where('to_user',Auth::id())->orderBy('created_at','desc')->take(10)->get();
        foreach($messages as &$bbb){
            
            $bbb->times = \Carbon\Carbon::createFromTimeStamp(strtotime( $bbb->created_at))->diffForHumans();
        
        }
        $data1['messages'] =$messages;
        return response()->json($data1);
    }
    public function allNotification(Request $request){
        
        $messages = Notification::where('to_user',Auth::id())->orderBy('created_at','desc')->paginate(20);
        foreach($messages as &$bbb){
            
            $bbb->times = \Carbon\Carbon::createFromTimeStamp(strtotime( $bbb->created_at))->diffForHumans();
        
        }
        $data1['messages'] =$messages;
        $title='Notification History';
        return view('user.all_notifications', compact('title', 'messages'));
    }
    public function notificationRead($id){
        $noti =Notification::find($id);
        $other_noti = Notification::where('to_user', Auth::id())->where('url', $noti->url)->get();
        foreach($other_noti as $n){
            $n->read_message ='read';
            $n->update();
        }
        $noti->read_message ='read';
        $url =$noti->url;
        $noti->update();
        return redirect()->intended($url);

    }
    public function rating(Request $request){
        $deal = AdvertiseDeal::findOrFail($request->deal_id);
        $data=[];
        if($deal->from_user_id != Auth::user()->id){

            $to = $deal->from_user_id;
        }else{

            $to = $deal->to_user_id;
        }
        $data['from_user']=Auth::user()->id;
        $data['to_user']=$to;
        $data['remarks']=$request->remarks;
        $data['rating']=$request->rate;
        $data['add_type']=$deal->add_type;
        $data['deal_id']=$deal->trans_id;
        $data['advertisement_id']=$deal->advertisement_id;
        Rating::create($data);
        $deal->reviewed=1;
        $deal->save();
        $user= User::findOrFail($to);
        if($user->rating < 100  ){
            $records=Rating::where('to_user',$to);
            $count=$records->count();
            $sum =$records->sum('rating');
            $t= ($sum/$count) * 100;
            if($t > 100){
                $t=100;
            }
            if($t < 0){
                $t=0;
            }
            
            $user->rating=round($t);
            $user->save();
        }
        elseif($request->rate  < 0 && $user->rating == 100){
            $records=Rating::where('to_user',$to);
            $count=$records->count();
            $sum =$records->sum('rating');
            $t= ($sum/$count) * 100;
            if($t > 100){
                $t=100;
            }
            if($t < 0){
                $t=0;
            }
            $user->rating=round($t);
            $user->save();
        }
        return back()->with('message', 'Feedback submitted successfully');
        
    }
    public function ratingUpdated(Request $request,$id){
        $rating= Rating::findOrFail($id);
        $rating->remarks=$request->remarks;
        $rating->rating=$request->rate;
        $user=User::findOrFail($rating->to_user);
        $rating->save();
        if($user->rating < 100  ){
            $records=Rating::where('to_user',$user->id);
            $count=$records->count();
            $sum =$records->sum('rating');
            $t= ($sum/$count) * 100;
            if($t > 100){
                $t=100;
            }
            if($t < 0){
                $t=0;
            }
            $user->rating=round($t);
            $user->save();
        }
        elseif($request->rate  < 0 && $user->rating == 100){
            $records=Rating::where('to_user',$user->id);
            $count=$records->count();
            $sum =$records->sum('rating');
            $t= ($sum/$count) * 100;
            if($t > 100){
                $t=100;
            }
            if($t < 0){
                $t=0;
            }
            $user->rating=round($t);
            $user->save();
        }
        return back()->with('message', 'Review Updated Successfully');
       
    }
    public function readallNotification($id){
        $messages = Notification::where('to_user',$id)->get();
        foreach($messages as $data){
            $data->read_message ='read';
        
            $data->update();
        }
        return back()->with('message', 'Notification marked read successfully');
    }

    public function feeStructure(){
        $title = "Fee Structure";
        $fee = GeneralSettings::first();

        return view('user.fee_structure', compact('title', 'fee'));
    }

    public function cannedMessages(){
        $title = "Secret Notes - Canned Messages";
        $messages = CannedMessages::where('user_id', Auth::user()->id)->paginate(10);

        return view('user.canned_messages', compact('title', 'messages'));
    }

    public function cannedMessagesAdd(Request $request){
        if(isset($request->message) && $request->message != ""){
            CannedMessages::create(array(
                "user_id" => Auth::user()->id,
                "message" => $request->message
            ));
            return back()->with('success', 'Message Added Successfully');
        }
        else{
            return back()->with('alert', 'Message Field is Empty!');
        }
    }

    public function cannedMessagesDelete($username, $id){
        if(isset($id) && $id != ""){
            CannedMessages::where('id', $id)->delete();
            return back()->with('success', 'Message Deleted Successfully');
        }
        else{
            return back()->with('alert', 'Something Went Wrong!');
        }
    }

    public function cannedMessagesEdit(Request $request){
        if(isset($request->message) && $request->message != ""){
            CannedMessages::where('id', $request->id)->update([
                'message' => $request->message
             ]);
            return back()->with('success', 'Message Updated Successfully');
        }
        else{
            return back()->with('alert', 'Message Field is Empty!');
        }
    }
}
