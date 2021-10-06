<?php

namespace App\Http\Controllers;

use App\Models\AdvertiseDeal;
use App\Models\Advertisement;
use App\Models\GeneralSettings;
use App\Models\Slider;
use App\Models\User;
use App\Models\Rating;
use App\Models\UserLogin;
use Illuminate\Http\Request;
use PragmaRX\Google2FA\Google2FA;
use App\Models\Menu;
use App\Models\Gateway;
use App\Models\PaymentMethod;
use App\Models\PaymentMethodsCategories;
use App\Models\Currency;
use App\Models\Faq;
use App\Models\Advertisment;
use App\Models\Trx;
use App\Models\UserCryptoBalance;
use App\Models\Country;
use App\Models\PaymentMethodAdvise;
use App\Models\PrivateNote;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class FrontendController extends Controller
{
    public function authCheck()
    {
        if (Auth::user()->status == '1' && Auth::user()->email_verify == 1 && Auth::user()->phone_verify == 1 && Auth::user()->tfver == 1) {
            return redirect("user/".Auth::user()->username."/home");
        } else {
            return view('auth.noauthor');
        }
    }

    public function sendemailver()
    {
        $user = User::find(Auth::id());

        $chktm = Carbon::parse($user->email_time)->addMinutes(1);

        if ($chktm > Carbon::now()) {
            $delay = Carbon::now()->diffInSeconds($chktm);
            return back()->with('alert', 'Please Try after '.$delay.' Seconds');
        } else {
            $code = substr(rand(), 0, 6);
            $message = 'Your Verification code is: '.$code;
            $user['verification_code'] = $code ;
            $user['email_time'] = Carbon::now();
            $user->save();
try{
            send_email($user->email, $user->username, 'Verification Code', $message);
        }catch(\Exception $e){

        }

            $sms = $message;
            send_sms($user['mobile'], $sms);

            return back()->with('success', 'Email verification code sent succesfully');
        }
    }

    public function sendsmsver()
    {
        $user = User::find(Auth::id());
        $chktm = Carbon::parse($user->email_time)->addMinutes(1);

        if ($chktm > Carbon::now()) {
            $delay = Carbon::now()->diffInSeconds($chktm);
            return back()->with('alert', 'Please Try after '.$delay.' Seconds');
        } else {
            $code = substr(rand(), 0, 6);
            $sms =  'Your Verification code is: '.$code;
            $user['sms_code'] = $code;
            $user['phone_time'] = Carbon::now();
            $user->save();

            send_sms($user->mobile, $sms);
            return back()->with('success', 'SMS verification code sent succesfully');
        }
    }

    public function emailverify(Request $request)
    {
        $this->validate($request, [
            'code' => 'required'
        ]);

        $user = User::find(Auth::id());

        $code = $request->code;


        if ($user->verification_code == $code) {
            $user['email_verify'] = 1;
            $user['verification_code'] = substr(rand(), 0, 6);
            $user['email_time'] = Carbon::now();
            $user->save();

            return redirect('user/'.Auth::user()->username.'/home')->with('success', 'Email Verified');
        } else {
            return back()->with('alert', 'Wrong Verification Code');
        }
    }

    public function smsverify(Request $request)
    {
        $this->validate($request, [
            'code' => 'required'
        ]);

        $user = User::find(Auth::id());

        $code = $request->code;
        if ($user->sms_code == $code) {
            $user['phone_verify'] = 1;
            $user['sms_code'] = substr(rand(), 0, 6);
            $user['phone_time'] = Carbon::now();
            $user->save();

            return redirect('user/'.Auth::user()->username.'/home')->with('success', 'SMS Verified');
        } else {
            return back()->with('alert', 'Wrong Verification Code');
        }
    }
 
    public function verify2fa(Request $request)
    {
        $user = User::find(Auth::id());

        $this->validate($request, [
                'code' => 'required',
            ]);

        $google2fa = new Google2FA();
        $secret = $request->code;
        $valid = $google2fa->verifyKey($user->secretcode, $secret);



        if ($valid) {
            $user['tfver'] = 1;
            $user->save();
            return redirect('user/'.Auth::user()->username.'/home');
        } else {
            return back()->with('alert', 'Wrong Verification Code');
        }
    }

    public function withdraw2faVerify(Request $request)
    {
        $user = User::find(Auth::id());

        $this->validate($request, [
                'code' => 'required',
            ]);

        $google2fa = new Google2FA();
        $secret = $request->code;
        $valid = $google2fa->verifyKey($user->secretcode, $secret);



        if ($valid) {
            $user['tfver'] = 1;
            $user->save();

            $amount = $request->session()->get('requestAmount');
            $address = $request->session()->get('requestAddress');
            $description = $request->session()->get('requestDescription');
            $type = $request->session()->get('requestType');
            if($request == "") return redirect()->route('user.withdraws', auth()->user()->username)
            ->with('alert', 'Something went wrong. Try again!');
            
            $basic = GeneralSettings::first();
            $cryptoBalance = $request->user()->cryptoBalances->first();
            if($type == 1){
                $receiver = UserCryptoBalance::where('address', $address)->first();
                if(!isset($receiver->user_id)) return redirect()->route('user.withdraws', auth()->user()->username)->with('alert', 'Wallet Address invalid!');
                $charge = $basic->send_internal_fixed_fee + $basic->send_internal_percentage_fee / 100 * abs($amount);
                if(round($charge + abs($amount), 8) <= round($cryptoBalance->balance,8)){
                    $old_balance = $cryptoBalance->balance;
                    $cryptoBalance->decrement('balance', abs($amount));
                    $cryptoBalance->decrement('balance', $charge);
                    $request->merge([
                        'fee' => number_format((float)$charge, '8', '.', ''),
                        'address' => $address,
                        'amount' => $amount
                    ]);
                    if(isset($description)){
                        $request->merge([
                            'description' => $description
                        ]);
                    }
    
                    $request->user()->sendRequests()
                    ->create($request->all());

                    Trx::create([
                        'user_id' => $cryptoBalance->user_id,
                        'pre_main_amo' => number_format((float)$old_balance, '8', '.', '').' BTC',
                        'amount' => number_format((float)abs($amount), '8', '.', '').' BTC',
                        'main_amo' => number_format((float)$cryptoBalance->balance, '8', '.', '').' BTC',
                        'charge' => number_format((float)$charge, '8', '.', '').' BTC',
                        'type' => '-',
                        'title' => 'Send ' . 'BTC',
                        'trx' => 'Send' . 'BTC' . time(),
                        'deal_url' => '/user'.'/'.$cryptoBalance->user->username.'/sends',
                    ]);
    
                    return redirect()->route('user.withdraws', auth()->user()->username)
                    ->with('success', 'Send request created successfully!');
                }
                else{
                    return back()->with('alert', 'Insufficient Balance in Your Account');
                }
            }
            else
            {
                $charge = $basic->withdraw_external_fixed_fee + $basic->withdraw_external_percentage_fee / 100 * abs($amount);
                if(round($charge + abs($amount), 8) <= round($cryptoBalance->balance,8)){
                    $old_balance = $cryptoBalance->balance;
                    $cryptoBalance->decrement('balance', abs($amount));
                    $cryptoBalance->decrement('balance', $charge);
                    $request->merge([
                        'main_amo' => number_format((float)$cryptoBalance->balance, '8', '.', ''),
                        'fee' => number_format((float)$charge, '8', '.', ''),
                        'address' => $address,
                        'amount' => $amount
                    ]);
                    if(isset($description)){
                        $request->merge([
                            'description' => $description
                        ]);
                    }
    
                    $request->user()->withdrawRequests()
                    ->create($request->all());

                    Trx::create([
                        'user_id' => $cryptoBalance->user_id,
                        'pre_main_amo' => number_format((float)$old_balance, '8', '.', '').' BTC',
                        'amount' => number_format((float)abs($amount), '8', '.', '').' BTC',
                        'main_amo' => number_format((float)$cryptoBalance->balance, '8', '.', '').' BTC',
                        'charge' => number_format((float)$charge, '8', '.', '').' BTC',
                        'type' => '-',
                        'title' => 'Send ' . 'BTC',
                        'trx' => 'Send' . 'BTC' . time(),
                        'deal_url' => '/user'.'/'.$cryptoBalance->user->username.'/withdraws',
                    ]);
    
                    return redirect()->route('user.withdraws', auth()->user()->username)
                    ->with('success', 'Send request created successfully!');
                }
                else{
                    return back()->with('alert', 'Insufficient Balance in Your Account');
                }
            }

        } else {
            return back()->with('alert', 'Wrong Verification Code');
        }
    }

    public function index(Request $request)
    {
        $general_settings = GeneralSettings::first();
        $slider = cache()->remember('slider', 3600, function () {
            return Slider::find(5);
        });
        $coin = cache()->remember('gateway', 3600, function () {
            return Gateway::all();
        });

        $country = Country::where('id', $request->session()->get('country_id'))->first();
        
        $methods = [];
        if(!is_null($country)) {
            $methods = $country->paymentMethods()->where('status', 1)->get();
        }
        $categories = PaymentMethodsCategories::all();
        
        $currency = cache()->remember('currency', 3600, function () {
            return Currency::where('status', 1)->get();
        });

        $countries = cache()->remember('countries', 3600, function () {
            return Country::where('active', true)->get();
        });
        //  echo $request->session()->get('currency_id');exit;
        
        $buyOffers = Advertisement::with('user')
        
          ->where('add_type', '2')
          ->where('currency_id', $request->session()->get('currency_id'))
          ->where('gateway_id', 505)
         
         
          ->where('status', '1')
          ->orderByRaw('price desc')
          ->paginate(6,['*'], 'p');
        foreach($buyOffers as $key => &$data){
            $user= $data->user;
            if(UserCryptoBalance::where('user_id', $user->id)->first()->balance < $general_settings->min_balance_for_sell_ad && $data->add_type == 1){
                unset($buyOffers[$key]);
                continue;
            }
            $trade_btc = AdvertiseDeal::where('gateway_id', 505)->where('status', 1)->where(function ($query) use ($user) {
                $query->where('to_user_id', $user->id);
                $query->orWhere('from_user_id', $user->id);
            });
            $data->trade_btc= $trade_btc->sum('coin_amount');
            $data->trades= $trade_btc->count();

            
        }

          
             
        $sellOffers = Advertisement::with('user')
        
          ->where('add_type', '1')
          ->where('currency_id', $request->session()->get('currency_id'))
          ->where('gateway_id', 505)
          ->where('status', '1')
         
          ->orderByRaw('price')
              ->paginate(6,['*'], 'q');
        foreach($sellOffers as $key => &$data){
                $user= $data->user;
                if(UserCryptoBalance::where('user_id', $user->id)->first()->balance < $general_settings->min_balance_for_sell_ad && $data->add_type == 1){
                    unset($sellOffers[$key]);
                    continue;
                }
                $trade_btc = AdvertiseDeal::where('gateway_id', 505)->where('status', 1)->where(function ($query) use ($user) {
                    $query->where('to_user_id', $user->id);
                    $query->orWhere('from_user_id', $user->id);
                });
                $data->trade_btc= $trade_btc->sum('coin_amount');
                $data->trades= $trade_btc->count();
    
                
        }     

          return view('front.index', compact('slider', 'coin','categories', 'methods', 'currency', 'buyOffers', 'sellOffers', 'countries'));
    }

    public function tradeBTC(Request $request)
    {
        $general_settings = GeneralSettings::first();
        $slider = cache()->remember('slider', 3600, function () {
            return Slider::find(5);
        });
        $coin = cache()->remember('gateway', 3600, function () {
            return Gateway::all();
        });

        $country = Country::where('id', $request->session()->get('country_id'))->first();
        $methods = [];
        if(!is_null($country)) {
            $methods = $country->paymentMethods()->where('status', 1)->get();
        }

        $currency = cache()->remember('currency', 3600, function () {
            return Currency::where('status', 1)->get();
        });

        $countries = cache()->remember('countries', 3600, function () {
            return Country::where('active', true)->get();
        });
        $type = $request->has('sell') ? 'sell' : 'buy';
        if ($request->has('sell')) {
            $offers = Advertisement::with('user')
            
            ->where('add_type', '2')
              ->where('currency_id', $request->session()->get('currency_id'))
              ->where('gateway_id', 505)
              ->where('status', '1')
             
              
              ->orderByRaw('price desc')
              ->paginate(10);
              foreach($offers as $key => &$data){
                $user= $data->user;
                if(UserCryptoBalance::where('user_id', $user->id)->first()->balance < $general_settings->min_balance_for_sell_ad && $data->add_type == 1){
                    unset($offers[$key]);
                    continue;
                }
                $trade_btc = AdvertiseDeal::where('gateway_id', 505)->where('status', 1)->where(function ($query) use ($user) {
                    $query->where('to_user_id', $user->id);
                    $query->orWhere('from_user_id', $user->id);
                });
                $data->trade_btc= $trade_btc->sum('coin_amount');
                $data->trades= $trade_btc->count();
    
                
            }

        } else {
            $offers = Advertisement::with('user')
          
              ->where('add_type', '1')
              ->where('status', '1')
              ->where('currency_id', $request->session()->get('currency_id'))
              ->where('gateway_id', 505)
             
              ->orderByRaw('price')
              ->paginate(10);
              foreach($offers as $key => &$data){
                $user= $data->user;
                if(UserCryptoBalance::where('user_id', $user->id)->first()->balance < $general_settings->min_balance_for_sell_ad && $data->add_type == 1){
                    unset($offers[$key]);
                    continue;
                }
                $trade_btc = AdvertiseDeal::where('gateway_id', 505)->where('status', 1)->where(function ($query) use ($user) {
                    $query->where('to_user_id', $user->id);
                    $query->orWhere('from_user_id', $user->id);
                });
                $data->trade_btc= $trade_btc->sum('coin_amount');
                $data->trades= $trade_btc->count();
    
                
            }
        }
        
        $user = User::find(Auth::id());
        if(empty($user) ){
            return view('front.trade', compact('slider', 'coin', 'methods', 'currency', 'countries','offers', 'type'));

        }else{
            return view('front.trade1', compact('slider', 'coin', 'methods', 'currency', 'countries','offers', 'type'));

        }

    }

    public function menu(Request $request, $slug)
    {
        $menu = $data['menu'] =  Menu::whereSlug($slug)->first();
        $page_title = $menu->name;
        $slider = cache()->remember('slider', 3600, function () {
            return Slider::find(5);
        });
        $coin = cache()->remember('gateway', 3600, function () {
            return Gateway::all();
        });

        $country = Country::where('id', $request->session()->get('country_id'))->first();
        $methods = [];
        if(!is_null($country)) {
            $methods = $country->paymentMethods()->where('status', 1)->get();
        }

        $currency = cache()->remember('currency', 3600, function () {
            return Currency::where('status', 1)->get();
        });

        $countries = cache()->remember('countries', 3600, function () {
            return Country::where('active', true)->get();
        });
        return view('layouts.menu', compact('slider', 'coin', 'methods', 'currency', 'countries', 'page_title','menu'));
    }

    public function contactUs(Request $request)
    {
        $data['page_title'] = "Contact Us";
        $slider = cache()->remember('slider', 3600, function () {
            return Slider::find(5);
        });
        $coin = cache()->remember('gateway', 3600, function () {
            return Gateway::all();
        });

        $country = Country::where('id', $request->session()->get('country_id'))->first();
        $methods = [];
        if(!is_null($country)) {
            $methods = $country->paymentMethods()->where('status', 1)->get();
        }

        $currency = cache()->remember('currency', 3600, function () {
            return Currency::where('status', 1)->get();
        });

        $countries = cache()->remember('countries', 3600, function () {
            return Country::where('active', true)->get();
        });
        return view('layouts.contact', compact('data','slider', 'coin', 'methods', 'currency', 'countries'));
    }

    public function termsView(Request $request)
    {
        $page_title = "Our Terms";
        $slider = cache()->remember('slider', 3600, function () {
            return Slider::find(5);
        });
        $coin = cache()->remember('gateway', 3600, function () {
            return Gateway::all();
        });

        $country = Country::where('id', $request->session()->get('country_id'))->first();
        $methods = [];
        if(!is_null($country)) {
            $methods = $country->paymentMethods()->where('status', 1)->get();
        }

        $currency = cache()->remember('currency', 3600, function () {
            return Currency::where('status', 1)->get();
        });

        $countries = cache()->remember('countries', 3600, function () {
            return Country::where('active', true)->get();
        });
        return view('layouts.our-terms', compact('page_title','slider', 'coin', 'methods', 'currency', 'countries'));
    }

    public function policyView()
    {
        $page_title = "Our Policy";
        return view('layouts.our-policy', compact('page_title'));
    }

    public function howToBuyBTCview(){
        $page_title = "How To Buy BTC";
        return view('layouts.how-to-buy-btc', compact('page_title'));
    }

    public function contactSubmit(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'message' => 'required'
        ]);

        $general = GeneralSettings::first();

        $subject = "Contact Us";
    try{
        send_email($general->email, 'I am'.$request->name, $subject, $request->message);
    }catch(\Exception $e){

    }
        $notification =  array('message' => 'Contact Message Send.', 'alert-type' => 'success');
        return back()->with($notification);
    }

    public function viewSlug($id)
    {
        if(!empty(Auth::user())){
            if(!empty(Auth::user())){
                $coin = Advertisement::with(['currency', 'gateway', 'user'])
                ->findOrFail($id);

                if($coin->add_type == 2 && auth()->user()->permission_sell)
                    return view('front.view', compact('coin'));
                else if($coin->add_type == 1 && auth()->user()->permission_buy)
                        return view('front.view', compact('coin'));
                else{
                    return back()->with('alert', 'You are Blocked By Admin');
                }
                
            }else{
                return redirect('/')->with('alert', 'Your account and documents are not verified.');
            }
        }else{
            $coin = Advertisement::with(['currency', 'gateway', 'user'])
                ->findOrFail($id);
    
                return view('front.view', compact('coin'));
        }
        
        
    }

    public function searchRe(Request $request)
    {
        //    return $request->all();
          if($request->has('add_type')){
            $type = $request->add_type== 2 ?   'sell':'buy';
            if($type == 'sell'){
                $order ='price desc';
            }else{
                $order ='price';
            }
            if($request->method_id == '' || $request->method_id=='all'){
               
            $offers = Advertisement::with('user')
            
              ->where('add_type', $request->add_type)
              ->where('gateway_id', 505)
             
            ->where('status', '1')
            ->where('currency_id', $request->currency_id)
           
            ->orderByRaw($order)
              ->paginate(20);
              foreach($offers as &$data){
                $user= $data->user;
                $trade_btc = AdvertiseDeal::where('gateway_id', 505)->where('status', 1)->where(function ($query) use ($user) {
                    $query->where('to_user_id', $user->id);
                    $query->orWhere('from_user_id', $user->id);
                });
                $data->trade_btc= $trade_btc->sum('coin_amount');
                $data->trades= $trade_btc->count();
    
                
            }
            return view('front.trade1', compact('offers', 'type'));

            }else{
                if($request->category_id =='' || $request->category_id=="all" ){
                    $type = $request->add_type== 2 ?   'sell':'buy';
                    $offers = Advertisement::with('user')
                   
                      ->where('add_type', $request->add_type)
                      ->where('gateway_id', 505)
                      ->where('category_id', $request->category_id)
                      
                      ->where('status', '1')
                      ->where('currency_id', $request->currency_id)
                      ->orderByRaw($order)
                      ->paginate(20);
                      foreach($offers as &$data){
                        $user= $data->user;
                        $trade_btc = AdvertiseDeal::where('gateway_id', 505)->where('status', 1)->where(function ($query) use ($user) {
                            $query->where('to_user_id', $user->id);
                            $query->orWhere('from_user_id', $user->id);
                        });
                        $data->trade_btc= $trade_btc->sum('coin_amount');
                        $data->trades= $trade_btc->count();
            
                        
                    }
                }else{
                    $type = $request->add_type== 2 ?   'sell':'buy';
                    $offers = Advertisement::with('user')
                   
                      ->where('add_type', $request->add_type)
                      ->where('gateway_id', 505)
                      ->where('category_id', $request->category_id)
                      ->where('method_id', $request->method_id)
                      ->where('status', '1')
                      ->where('currency_id', $request->currency_id)
                      ->orderByRaw($order)
                      ->paginate(20);
                      foreach($offers as &$data){
                        $user= $data->user;
                        $trade_btc = AdvertiseDeal::where('gateway_id', 505)->where('status', 1)->where(function ($query) use ($user) {
                            $query->where('to_user_id', $user->id);
                            $query->orWhere('from_user_id', $user->id);
                        });
                        $data->trade_btc= $trade_btc->sum('coin_amount');
                        $data->trades= $trade_btc->count();
            
                        
                    }
                }
                
            return view('front.trade1', compact('offers', 'type'));

            }
            
          }
        
        if(Auth::id()){
            if($request->method_id == 'all' ){
                $sellOffers = Advertisement::where('add_type', 2)->where('gateway_id', 505)
               
                ->where('status', '1')
                ->where('currency_id', $request->sellcurrency_id)
                ->orderByRaw('price desc')
                ->paginate(20);
                foreach($sellOffers as &$data){
                    $user= $data->user;
                    $trade_btc = AdvertiseDeal::where('gateway_id', 505)->where('status', 1)->where(function ($query) use ($user) {
                        $query->where('to_user_id', $user->id);
                        $query->orWhere('from_user_id', $user->id);
                    });
                    $data->trade_btc= $trade_btc->sum('coin_amount');
                    $data->trades= $trade_btc->count();
        
                    
                }
    
                $buyOffers = Advertisement::where('add_type', 1)->where('gateway_id', 505)
               
                ->where('status', '1')
                ->where('currency_id', $request->buycurrency_id)
                ->orderByRaw('price ')
                ->paginate(20);
                foreach($buyOffers as &$data){
                    $user= $data->user;
                    $trade_btc = AdvertiseDeal::where('gateway_id', 505)->where('status', 1)->where(function ($query) use ($user) {
                        $query->where('to_user_id', $user->id);
                        $query->orWhere('from_user_id', $user->id);
                    });
                    $data->trade_btc= $trade_btc->sum('coin_amount');
                    $data->trades= $trade_btc->count();
        
                    
                }
            }else{
                if($request->category_id =='' || $request->category_id=="all" ){
                    $sellOffers = Advertisement::where('add_type', 2)->where('gateway_id', 505)
                ->where('method_id', $request->method_id)
               
                ->where('status', '1')
                ->where('currency_id', $request->sellcurrency_id)
                ->orderByRaw('price desc')
                ->paginate(20);
                foreach($sellOffers as &$data){
                    $user= $data->user;
                    $trade_btc = AdvertiseDeal::where('gateway_id', 505)->where('status', 1)->where(function ($query) use ($user) {
                        $query->where('to_user_id', $user->id);
                        $query->orWhere('from_user_id', $user->id);
                    });
                    $data->trade_btc= $trade_btc->sum('coin_amount');
                    $data->trades= $trade_btc->count();
        
                    
                }
                $buyOffers = Advertisement::where('add_type', 1)->where('gateway_id', 505)
                ->where('method_id', $request->method_id)
                
                ->where('status', '1')
                ->where('currency_id', $request->buycurrency_id)
                ->orderByRaw('price ')
                ->paginate(20);
                foreach($buyOffers as &$data){
                    $user= $data->user;
                    $trade_btc = AdvertiseDeal::where('gateway_id', 505)->where('status', 1)->where(function ($query) use ($user) {
                        $query->where('to_user_id', $user->id);
                        $query->orWhere('from_user_id', $user->id);
                    });
                    $data->trade_btc= $trade_btc->sum('coin_amount');
                    $data->trades= $trade_btc->count();
        
                    
                }
                }else{
                    $sellOffers = Advertisement::where('add_type', 2)->where('gateway_id', 505)
                    ->where('method_id', $request->method_id)
                    ->where('category_id', $request->category_id)
                    ->where('status', '1')
                    ->where('currency_id', $request->sellcurrency_id)
                    ->orderByRaw('price desc')
                    ->paginate(20);
                    foreach($sellOffers as &$data){
                        $user= $data->user;
                        $trade_btc = AdvertiseDeal::where('gateway_id', 505)->where('status', 1)->where(function ($query) use ($user) {
                            $query->where('to_user_id', $user->id);
                            $query->orWhere('from_user_id', $user->id);
                        });
                        $data->trade_btc= $trade_btc->sum('coin_amount');
                        $data->trades= $trade_btc->count();
            
                        
                    }
                    $buyOffers = Advertisement::where('add_type', 1)->where('gateway_id', 505)
                    ->where('method_id', $request->method_id)
                    ->where('category_id', $request->category_id)
                    ->where('status', '1')
                    ->where('currency_id', $request->buycurrency_id)
                    ->orderByRaw('price ')
                    ->paginate(20);
                    foreach($buyOffers as &$data){
                        $user= $data->user;
                        $trade_btc = AdvertiseDeal::where('gateway_id', 505)->where('status', 1)->where(function ($query) use ($user) {
                            $query->where('to_user_id', $user->id);
                            $query->orWhere('from_user_id', $user->id);
                        });
                        $data->trade_btc= $trade_btc->sum('coin_amount');
                        $data->trades= $trade_btc->count();
            
                        
                    }
                }
                
            }
            

        }else{
            if($request->method_id == 'all'){
                $sellOffers = Advertisement::where('add_type', 2)->where('gateway_id', 505)
                
                ->where('status', '1')
                ->where('currency_id', $request->sellcurrency_id)
                ->orderByRaw('price desc')
                ->paginate(20);
                foreach($sellOffers as &$data){
                    $user= $data->user;
                    $trade_btc = AdvertiseDeal::where('gateway_id', 505)->where('status', 1)->where(function ($query) use ($user) {
                        $query->where('to_user_id', $user->id);
                        $query->orWhere('from_user_id', $user->id);
                    });
                    $data->trade_btc= $trade_btc->sum('coin_amount');
                    $data->trades= $trade_btc->count();
        
                    
                }
                $buyOffers = Advertisement::where('add_type', 1)->where('gateway_id', 505)
                
                ->where('status', '1')
                ->where('currency_id', $request->buycurrency_id)
                ->orderByRaw('price')
                ->paginate(20);
                foreach($buyOffers as &$data){
                    $user= $data->user;
                    $trade_btc = AdvertiseDeal::where('gateway_id', 505)->where('status', 1)->where(function ($query) use ($user) {
                        $query->where('to_user_id', $user->id);
                        $query->orWhere('from_user_id', $user->id);
                    });
                    $data->trade_btc= $trade_btc->sum('coin_amount');
                    $data->trades= $trade_btc->count();
        
                    
                }
            }else{
                if($request->category_id =='' || $request->category_id=="all" ){
                    $sellOffers = Advertisement::where('add_type', 2)->where('gateway_id', 505)
                    ->where('method_id', $request->method_id)
                   
                    ->where('status', '1')
                    ->where('currency_id', $request->sellcurrency_id)
                    ->orderByRaw('price desc')
                    ->paginate(20);
                    foreach($sellOffers as &$data){
                        $user= $data->user;
                        $trade_btc = AdvertiseDeal::where('gateway_id', 505)->where('status', 1)->where(function ($query) use ($user) {
                            $query->where('to_user_id', $user->id);
                            $query->orWhere('from_user_id', $user->id);
                        });
                        $data->trade_btc= $trade_btc->sum('coin_amount');
                        $data->trades= $trade_btc->count();
            
                        
                    }
                    $buyOffers = Advertisement::where('add_type', 1)->where('gateway_id', 505)
                    ->where('method_id', $request->method_id)
                    
                    ->where('status', '1')
                    ->where('currency_id', $request->buycurrency_id)
                    ->orderByRaw('price ')
                    ->paginate(20);
                    foreach($buyOffers as &$data){
                        $user= $data->user;
                        $trade_btc = AdvertiseDeal::where('gateway_id', 505)->where('status', 1)->where(function ($query) use ($user) {
                            $query->where('to_user_id', $user->id);
                            $query->orWhere('from_user_id', $user->id);
                        });
                        $data->trade_btc= $trade_btc->sum('coin_amount');
                        $data->trades= $trade_btc->count();
            
                        
                    }
                }else{
                    $sellOffers = Advertisement::where('add_type', 2)->where('gateway_id', 505)
                    ->where('method_id', $request->method_id)
                    ->where('category_id', $request->category_id)
                    ->where('status', '1')
                    ->where('currency_id', $request->sellcurrency_id)
                    ->orderByRaw('price desc')
                    ->paginate(20);
                    foreach($sellOffers as &$data){
                        $user= $data->user;
                        $trade_btc = AdvertiseDeal::where('gateway_id', 505)->where('status', 1)->where(function ($query) use ($user) {
                            $query->where('to_user_id', $user->id);
                            $query->orWhere('from_user_id', $user->id);
                        });
                        $data->trade_btc= $trade_btc->sum('coin_amount');
                        $data->trades= $trade_btc->count();
            
                        
                    }
                    $buyOffers = Advertisement::where('add_type', 1)->where('gateway_id', 505)
                    ->where('method_id', $request->method_id)
                    ->where('category_id', $request->category_id)
                    ->where('status', '1')
                    ->where('currency_id', $request->buycurrency_id)
                    ->orderByRaw('price ')
                    ->paginate(20);
                    foreach($buyOffers as &$data){
                        $user= $data->user;
                        $trade_btc = AdvertiseDeal::where('gateway_id', 505)->where('status', 1)->where(function ($query) use ($user) {
                            $query->where('to_user_id', $user->id);
                            $query->orWhere('from_user_id', $user->id);
                        });
                        $data->trade_btc= $trade_btc->sum('coin_amount');
                        $data->trades= $trade_btc->count();
            
                        
                    }
                }
                
            }
            

        }
        $buycurrency_id = $request->buycurrency_id;
        $sellcurrency_id = $request->sellcurrency_id;
        $method_id = $request->method_id;
        $category_id = $request->category_id;
        $country_id =$request->country;
        $country = Country::where('id', $request->session()->get('country_id'))->first();
        $methods = [];
        if(!is_null($country)) {
            $methods = $country->paymentMethods()->where('status', 1)->get();
        }
            return view('front.sell_buy', compact('sellOffers', 'buyOffers','category_id','methods','method_id','country_id','sellcurrency_id','buycurrency_id'));
        
    }

    public function profileView($username)
    {
        $user = User::where('username', $username)->first();

        if (empty($user)) {
            return redirect('/');
        }

        $id = intval($user->id);

        $trades = AdvertiseDeal::where('gateway_id', 505)->where('status', 1)->where(function ($query) use ($id) {
            $query->where('to_user_id', $id);
            $query->orWhere('from_user_id', $id);
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
        
        $user1 = User::find(Auth::id());
        if(empty($user1) || $user->id == Auth::id()){
            $coin = Advertisement::where('user_id', $user->id)->where('status','1')->paginate(5,['*'], 'q');
            return view('front.profile', compact(
                'user',
                'trade_btc',
                'first_buy',
                'last_login',
                'coin',
                'reviews',
                'sellCount',
                'buyCount'
            ));
        }else{
            $coin = Advertisement::where('user_id', $user->id)->where('status', '1')->paginate(5,['*'], 'q');
            $dealer_reviews= Rating::where('to_user', $user->id)->where('from_user', Auth::user()->id)->orderBy('id', 'desc')->paginate(5,['*'], 's');
            $note = PrivateNote::where('to_user_id', $user->id)->where('from_user_id', Auth::user()->id)->first();
            if(isset($note->id)) $note = $note->note;
            else $note = "";

            $mutual_sell_deals = AdvertiseDeal::where('to_user_id', $user->id)->where('from_user_id', Auth::user()->id)->orderBy('id', 'desc')->paginate(5,['*'], 'ms');
            $mutual_buy_deals = AdvertiseDeal::where('to_user_id', Auth::user()->id)->where('from_user_id', $user->id)->orderBy('id', 'desc')->paginate(5,['*'], 'mb');;
            return view('front.profile1', compact(
                'user',
                'trade_btc',
                'first_buy',
                'last_login',
                'coin',
                'reviews',
                'dealer_reviews',
                'sellCount',
                'buyCount',
                'note',
                'mutual_sell_deals',
                'mutual_buy_deals'
            ));
        }
        
    }
    public function MethodGuide(Request $request){
        $methods=PaymentMethod::all();
        return view('front.methods', compact('methods'));
    }
    public function ActiveMethode($id){
        $methods=PaymentMethod::all();
        $active=PaymentMethod::find($id);
        return view('front.methods_show', compact('methods','active'));
    }
    public function submitAdvice(Request $request){
        $data=$request->all();
        PaymentMethodAdvise::create($data);
        return back()->with('message', 'Advice submitted successfully');
    }
    public function countryChange(Request $request,$iso){
    //    return $request->all();
       
        $myValue = config('geoip.currency_code');
        if(isset($myValue[$iso])){
            $curr= $myValue[$iso];
            $currency = Currency::where('name',$curr)->first();
            $country = Country::where('iso',$iso)->first();
            if ($currency) {
              session()->put('currency_id', $currency->id);
              session()->put('country_id', $country->id);
              session()->put('country', $country->name);
              session()->put('currency', $currency->name);
            }
            return 'true';
        }else{
            return 'true';
        }
        // return $myValue;

    }
}