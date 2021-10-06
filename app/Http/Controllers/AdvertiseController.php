<?php

namespace App\Http\Controllers;

use Auth;
use App\Events\UserActions;

use App\Models\PaymentMethod;
use App\Models\Currency;
use App\Models\Advertisement;
use App\Models\Gateway;
use App\Models\Country;
use App\Models\User;
use App\Models\UserCryptoBalance;
use App\Models\PaymentMethodsCategories;
use Illuminate\Http\Request;
use App\Http\Requests\Advertisement\StoreFormRequest;
use App\Http\Requests\Advertisement\UpdateFormRequest;;
use App\Http\Requests\Advertisement\StoreDealFormRequest;
use App\Http\Requests\Advertisement\DealSendMessageFormRequest;
use App\Models\GeneralSettings;

class AdvertiseController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Advertisement\StoreFormRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreFormRequest $request)
    {
        // event(new UserActions($request));
        $general = GeneralSettings::first();
        $type = ((int) $request->add_type) === 1 ? 'sell' : 'buy';
        // $all = file_get_contents("https://api.coinstats.app/public/v1/coins/bitcoin");
        // $ticker = json_decode($all, true);
        // $btc_usd = $ticker['coin']['price'];
        $bal =  UserCryptoBalance::where('user_id',Auth::id() )->where('gateway_id', 505)->first();
        if( (empty($bal) || $bal->balance < $general->min_balance_for_sell_ad) &&  $request->status =='1' && $type == 'sell'){
            $request->status='0';
        }

        // if ($request->gateway_id == 505) {
            // $price = $btc_usd;
        // }

        if ($request->agree == 1 && $type == "sell") {
            if(!Auth::user()->permission_sell){
                return redirect()->back()->withErrors('You have no sell permission');
            }
            $cur = Currency::find($request->currency_id);

            $method = PaymentMethod::find($request->crypto_id);

            // if ($request->margin == 0) {
            //     $after_margin = ($cur->usd_rate * $price * 1)/100;
            // } else {
            //     $after_margin = ($cur->usd_rate * $price * $request->margin)/100;
            // }
            if($request->max_amount != "" && !is_numeric($request->max_amount)){
                return redirect()->back()->withErrors('Enter Digits in Max Amount');
            }

            $total_price = $request->price;
            $balance = UserCryptoBalance::where('user_id', Auth::id())->first();
            $max_amount = number_format((float)($balance->balance - $general->sell_advertiser_fixed_fee)/(1+(($general->sell_advertiser_percentage_fee)/100)), 8, '.', '');
            $max_amount = $max_amount < 0 ? 0 : (float)$max_amount;

            $max_amount *= $total_price;
            $max_amount = round($max_amount);

            Advertisement::create([
                'user_id' => Auth::id(),
                'add_type' => 1,
                'gateway_id' => 505,
                'method_id' => $request->crypto_id,
                'currency_id' => $request->currency_id,
                'margin' => $request->margin,
                'price' => $total_price,
                'min_amount' => $request->min_amount == "" || $request->min_amount == null ? 1 : $request->min_amount,
                'max_amount' => $request->max_amount == "" || $request->max_amount == null ? $max_amount : $request->max_amount,
                'auto_max' => $request->max_amount == "" || $request->max_amount == null ? true : false,
                'allow_email' => isset($request->allow_email) && $request->allow_email == 1 ? true : false,
                'allow_phone' => isset($request->allow_phone) && $request->allow_phone == 1 ? true : false,
                'allow_id' => isset($request->allow_id) && $request->allow_id == 1 ? true : false,
                'init_message' => isset($request->init_message) && $request->init_message != "" ? $request->init_message : null,
                'term_detail' => $request->term_detail,
                'payment_detail' => $request->payment_detail,
                'description'=> $request->description,
                'status' => $request->status,
                'category_id' => $request->category_id,
                'country_id' => $request->country_id,
            ]);
              
            $message = "Your trade advertisement created. You want to ".$type. " (" .$request->min_amount."-".$request->max_amount.") ".$cur->name." . You choose ".$method->name." as payment method.";
            
            try{
            send_email(Auth::user()->email, Auth::user()->name, 'You created a trade advertisement', $message);
            send_sms(Auth::user()->phone, $message);
        }catch(\Exception $e){

        }

            return redirect('/'. Auth::user()->username .'/market')->with('message', 'Advertise For Selling Create Successful.');
        } elseif ($request->agree == 1 && $type == "buy") {
            if(!Auth::user()->permission_buy){
                return redirect()->back()->withErrors('You have no buy permission');
            }
            $method = PaymentMethod::find($request->crypto_id);
            $cur = Currency::find($request->currency_id);
            if($request->max_amount == "" || $request->max_amount == null){
                return redirect()->back()->withErrors('Auto Max limit can only be used for Sell AD');
            }
            if(!is_numeric($request->max_amount)){
                return redirect()->back()->withErrors('Enter Digits in Max Amount');
            }

            // if ($request->margin == 0) {
            //     $after_margin = ($cur->usd_rate * $price * 1)/100;
            // } else {
            //     $after_margin = ($cur->usd_rate * $price * $request->margin)/100;
            // }

            $total_price = $request->price;

            $c = Advertisement::create([
                'user_id' => Auth::id(),
                'add_type' => 2,
                'gateway_id' => 505,
                'method_id' => $request->crypto_id,
                'currency_id' => $request->currency_id,
                'margin' => $request->margin,
                'price' => $total_price,
                'min_amount' => $request->min_amount == "" || $request->min_amount == null ? 1 : $request->min_amount,
                'max_amount' => $request->max_amount == "" || $request->max_amount == null ? 0 : $request->max_amount,
                // 'auto_max' => isset($request->auto_max) && $request->auto_max == 1 ? true : false,
                'allow_email' => isset($request->allow_email) && $request->allow_email == 1 ? true : false,
                'allow_phone' => isset($request->allow_phone) && $request->allow_phone == 1 ? true : false,
                'allow_id' => isset($request->allow_id) && $request->allow_id == 1 ? true : false,
                'init_message' => isset($request->init_message) && $request->init_message != "" ? $request->init_message : null,
                'term_detail' => $request->term_detail,
                'payment_detail' => $request->payment_detail,
                'description'=> $request->description,
                'status' => $request->status,
                'category_id' => $request->category_id,
                'country_id' => $request->country_id,
            ]);

            $message = "Your trade advertisement was created. You want to ".$type. " (" .$request->min_amount."-".$request->max_amount.") ".$cur->name." . You choose ".$method->name." as payment method.";
            try{

                send_email(Auth::user()->email, Auth::user()->name, 'You created a trade advertisement', $message);
                send_sms(Auth::user()->phone, $message);
        }catch(\Exception $e){

        }
            return redirect('/'. Auth::user()->username .'/market')->with('message', 'Advertise For Buying Create Successful.');
        } else {
            return redirect()->back()->withErrors('Please Read Our Terms And Condition');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Advertisement $advertise
     * @return \Illuminate\Http\Response
     */
    public function edit($username, Advertisement $advertise)
    {
        if(!empty(Auth::user()) && Auth::user()->verified == 1){
        $coin = Gateway::all();
        $country = Country::where('id', session()->get('country_id'))->first();
        $methods = $country->paymentMethods()->where('status', 1)->get();
        $countries = Country::all();
        $categories = PaymentMethodsCategories::all();
        $currency = Currency::active()->get();
        $defaultCurrency = Currency::find($advertise->currency_id);

        // $all = file_get_contents("https://api.coinstats.app/public/v1/coins/bitcoin");
        // $ticker = json_decode($all, true);

        $btc_usd = 0; // $ticker['coin']['price'];

        return view('user.sell_buy_edit', compact(
            'advertise',
            'coin',
            'methods',
            'currency',
            'btc_usd',
            'categories',
            'defaultCurrency',
            'countries',
            'country'
        ));
        }else{
            return redirect('/')->with('alert', 'Your account and documents are not verified.');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Advertisement\UpdateFormRequest  $request
     * @param  \App\Models\Advertisement $advertise
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateFormRequest $request, $username, Advertisement $advertise)
    {
        //event(new UserActions($request));
        $general = GeneralSettings::first();
        // $all = file_get_contents("https://api.coinstats.app/public/v1/coins/bitcoin");
        // $ticker = json_decode($all, true);
        $bal =  UserCryptoBalance::where('user_id', $advertise->user_id)->where('gateway_id', $advertise->gateway_id)->first();
        if( (empty($bal) || $bal->balance < $general->min_balance_for_sell_ad) &&  $request->status =='1' && $request->add_type == "sell"){
            $request->status='0';
        }
        // $btc_usd = $ticker['coin']['price'];

        // if ($request->gateway_id == 505) {
            // $price = $btc_usd ;
        // }

        if ($request->add_type == "sell") {
            if(!Auth::user()->permission_sell){
                return redirect()->back()->withErrors('You have no sell permission');
            }
            $cur = Currency::find($request->currency);

            $method = PaymentMethod::find($request->crypto_id);
            // if ($request->margin == 0) {
            //     $after_margin = ($cur->usd_rate * $price * 1)/100;
            // } else {
            //     $after_margin = ($cur->usd_rate * $price * $request->margin)/100;
            // }
            if($request->max_amount != "" && !is_numeric($request->max_amount)){
                return redirect()->back()->withErrors('Enter Digits in Max Amount');
            }

            $total_price = $request->price;
            $balance = UserCryptoBalance::where('user_id', Auth::id())->first();
            $max_amount = number_format((float)($balance->balance - $general->sell_advertiser_fixed_fee)/(1+(($general->sell_advertiser_percentage_fee)/100)), 8, '.', '');
            $max_amount = $max_amount < 0 ? 0 : (float)$max_amount;

            $max_amount *= $total_price;
            $max_amount = round($max_amount);

            $advertise->update([
                'user_id' => Auth::id(),
                'add_type' => 1,
                'gateway_id' => 505,
                'method_id' => $request->crypto_id,
                'currency_id' => $request->currency,
                'margin' => $request->margin,
                'price' => $total_price,
                'min_amount' => $request->min_amount == "" || $request->min_amount == null ? 1 : $request->min_amount,
                'max_amount' => $request->max_amount == "" || $request->max_amount == null ? $max_amount : $request->max_amount,
                'auto_max' => $request->max_amount == "" || $request->max_amount == null ? true : false,
                'allow_email' => isset($request->allow_email) && $request->allow_email == 1 ? true : false,
                'allow_phone' => isset($request->allow_phone) && $request->allow_phone == 1 ? true : false,
                'allow_id' => isset($request->allow_id) && $request->allow_id == 1 ? true : false,
                'init_message' => isset($request->init_message) && $request->init_message != "" ? $request->init_message : null,
                'term_detail' => $request->term_detail,
                'payment_detail' => $request->payment_detail,
                'status' => $request->status,
                'description' => $request->description,
                'category_id' => $request->category_id,
                'country_id' => $request->country_id,
            ]);

            $message = "Your trade advertisement updated. You want to ".$request->add_type." (".$request->min_amount."-".$request->max_amount.") ".$cur->name." . You choose ".$method->name." as payment method.";
           
            try{
            send_email(Auth::user()->email, Auth::user()->name, 'You update trade advertisement', $message);
            send_sms(Auth::user()->phone, $message);
        }catch(\Exception $e){

        }

            return redirect('/'. Auth::user()->username .'/market')->with('message', 'Advertise For Selling Update Successful.');
        } elseif ($request->add_type == "buy") {
            if(!Auth::user()->permission_buy){
                return redirect()->back()->withErrors('You have no buy permission');
            }
            $method = PaymentMethod::find($request->crypto_id);
            $cur = Currency::find($request->currency);
            if($request->max_amount == "" || $request->max_amount == null){
                return redirect()->back()->withErrors('Auto Max limit can only be used for Sell AD');
            }
            if(!is_numeric($request->max_amount)){
                return redirect()->back()->withErrors('Enter Digits in Max Amount');
            }

            // if ($request->margin == 0) {
            //     $after_margin = ($cur->usd_rate * $price * 1)/100;
            // } else {
            //     $after_margin = ($cur->usd_rate * $price * $request->margin)/100;
            // }

            $total_price = $request->price;

            $advertise->update([
                'user_id' => Auth::id(),
                'add_type' => 2,
                'gateway_id' => 505,
                'method_id' => $request->crypto_id,
                'currency_id' => $request->currency,
                'margin' => $request->margin,
                'price' => $total_price,
                'min_amount' => $request->min_amount == "" || $request->min_amount == null ? 1 : $request->min_amount,
                'max_amount' => $request->max_amount == "" || $request->max_amount == null ? 0 : $request->max_amount,
                // 'auto_max' => isset($request->auto_max) && $request->auto_max == 1 ? true : false,
                'allow_email' => isset($request->allow_email) && $request->allow_email == 1 ? true : false,
                'allow_phone' => isset($request->allow_phone) && $request->allow_phone == 1 ? true : false,
                'allow_id' => isset($request->allow_id) && $request->allow_id == 1 ? true : false,
                'init_message' => isset($request->init_message) && $request->init_message != "" ? $request->init_message : null,
                'term_detail' => $request->term_detail,
                'payment_detail' => $request->payment_detail,
                'status' => $request->status,
                'description' => $request->description,
                'category_id' => $request->category_id,
                'country_id' => $request->country_id,
            ]);

            // $message = "You Trade Advertise update complete. You want to".$request->add_type.' '.$request->min_amount.'-'.$request->max_amount.' '.$cur->name." . You choose ".$method->name." for transaction. Wait and your advertise on live now.";
            $message = "Your trade advertisement updated. You want to ".$request->add_type." (".$request->min_amount."-".$request->max_amount.") ".$cur->name." . You choose ".$method->name." as payment method.";
        
            try{
            send_email(Auth::user()->email, Auth::user()->name, 'You update trade advertisement', $message);
            send_sms(Auth::user()->phone, $message);
        }catch(\Exception $e){

        }
            return redirect('/'. Auth::user()->username .'/market')->with('message', 'Advertise For Buying Update Successful.');
        } else {
            return redirect()->back()->withErrors('Please Read Our Terms And Condition');
        }
    }

    public function sellCoin(Request $request)
    {
        if(!empty(Auth::user()) && Auth::user()->verified == 1){
            
            //event(new UserActions($request));
            $coin = Gateway::all();

            $country = Country::where('id', $request->session()->get('country_id'))->first();
            $methods = $country->paymentMethods()->where('status', 1)->get();
            $countries = Country::all();
            $currency = Currency::active()->get();
            $defaultCurrencyId = $request->session()->get('currency_id');

            if ($defaultCurrencyId) {
                $defaultCurrency = Currency::find($defaultCurrencyId);
            } else {
                $defaultCurrency = Currency::first();
            }
            $categories =PaymentMethodsCategories::all();
            // $all = file_get_contents("https://api.coinstats.app/public/v1/coins/bitcoin");
            // $ticker = json_decode($all, true);
            $btc_usd = 0; // $ticker['coin']['price'];
            $user = User::find(Auth::id());
            if(empty($user) ){
                return view('user.sell_coin', compact(
                    'coin',
                    'methods',
                    'currency',
                    'defaultCurrency',
                    'btc_usd',
                    'categories',
                    'countries',
                    'country'
                ));
            }else{
                return view('user.sell_coin1', compact(
                    'coin',
                    'methods',
                    'currency',
                    'defaultCurrency',
                    'btc_usd',
                    'categories',
                    'countries',
                    'country',
                    'user'
                ));
            }
        }else{
            return redirect('/')->with('alert', 'Your account and documents are not verified.');

        }
        
        
    }

    public function showAdvertiseHistory()
    {
        $user = auth()->user()->load('cryptoAddvertises');
        $addvertise = $user->cryptoAddvertises()->latest()->paginate(5);
        $permission_sell = Auth::user()->permission_sell;
        $permission_buy = Auth::user()->permission_buy;
       
        //event(new UserActions($request));
        return view('user.sell_buy_history', compact('addvertise', 'permission_buy', 'permission_sell'));
    }

    public function showCurrency(Request $request)
    {
        $data = Currency::findOrFail($request->crypto);
        return response()->json($data);
    }
    public function statusChange(Request $request){
        $basic_settings = GeneralSettings::first();
        $addvertisement =Advertisement::find($request->id);
        $bal =  UserCryptoBalance::where('user_id', $addvertisement->user_id)->where('gateway_id', $addvertisement->gateway_id)->first();
        if( (empty($bal) || $bal->balance < $basic_settings->min_balance_for_sell_ad) &&  $request->status =='1' && $addvertisement->add_type =='1' ){
            return 'false';
        }
        

        $addvertisement->status=$request->status;
        if($addvertisement->save()){
            return 'success';
        }else{
            return 'false';
        }

    }

}
