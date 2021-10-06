<?php

namespace App\Http\Controllers;
use Auth;
use App\Models\Transaction;
use App\Models\Trx;
use App\Models\WithdrawRequest;
use App\Services\Bitcoind;
use App\Models\Notification;
use App\Models\GeneralSettings;
use App\Http\Requests\WithdrawRequest\StoreFormRequest;
use App\Models\BitCoinPrice;
use App\Models\Currency;
use App\Models\InternalTransactions;
use App\Models\User;
use App\Models\UserCryptoBalance;
use Carbon\Carbon;
use Illuminate\Http\Request;

class WithdrawController extends Controller
{

    /**
     * @var App\Services\Bitcoind|null
     */
    private $bitcoind;

    /**
     * Create the event listener.
     *
     * @param App\Services\Bitcoind $bitcoind
     *
     * @return void
     */
    public function __construct(Bitcoind $bitcoind)
    {
        $this->bitcoind = $bitcoind;
    }

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(Request $request)
    {
        $data_receives = auth()->user()->withdrawRequests()->paginate(5,['*'],'q');
        $data_sends = InternalTransactions::where('user_id', auth()->user()->id)->paginate(5,['*'],'r');
        $balance =$request->user()->cryptoBalances->first();
        $currencyId = session()->get('currency_id');
        $general =GeneralSettings::first();
        $currency = Currency::get();
        $withdraw_fixed_fee = number_format((float)$general->withdraw_external_fixed_fee, 8, '.', '');
        $withdraw_percentage_fee = number_format((float)$general->withdraw_external_percentage_fee, 2, '.', '');
        $send_fixed_fee = number_format((float)$general->send_internal_fixed_fee, 8, '.', '');
        $send_percentage_fee = number_format((float)$general->send_internal_percentage_fee, 2, '.', '');

        // last 24 Hours send requests 
        $send_btc = 0;
        $max_send_limit = number_format((float)auth()->user()->max_send_limit, 8, '.', '');
        $last_trans = auth()->user()->withdrawRequests()->where('created_at', '>', Carbon::now()->subDay())->where('created_at', '<=', Carbon::now())->get();
        
        foreach($last_trans as $key => $item){
            $send_btc += $item->amount;
        }
        $last_trans = InternalTransactions::where('user_id', auth()->user()->id)->where('created_at', '>', Carbon::now()->subDay())->where('created_at', '<=', Carbon::now())->get();
        foreach($last_trans as $key => $item){
            $send_btc += $item->amount;
        }

        // withdraw max
        $withdraw_max = number_format((float)($balance->balance - $general->withdraw_external_fixed_fee)/(1+(($general->withdraw_external_percentage_fee)/100)), 8, '.', '');
        $withdraw_max -= $send_btc;
        $withdraw_max = $withdraw_max < 0 ? 0 : $withdraw_max;
        if($max_send_limit > 0 && $max_send_limit < $withdraw_max){
            $withdraw_max = $max_send_limit - $send_btc;
        } 

        // send max
        $send_max = number_format((float)($balance->balance - $general->send_internal_fixed_fee)/(1+(($general->send_internal_percentage_fee)/100)), 8, '.', '');
        $send_max -= $send_btc;
        $send_max = $send_max < 0 ? 0 : $send_max;
        if($max_send_limit > 0 && $max_send_limit < $send_max){
            $send_max = $max_send_limit - $send_btc; 
        } 


        $balance = number_format((float)$balance->balance, 8, '.', '');
        if($max_send_limit > 0){

        }
        else{
            $max_send_limit = $balance;
        }
        
        return view('user.withdraws', compact('max_send_limit', 'data_receives', 'data_sends','general','withdraw_fixed_fee','withdraw_percentage_fee','send_fixed_fee','send_percentage_fee','withdraw_max','send_max', 'balance', 'currency', 'currencyId'));
    }

    public function sends(Request $request)
    {
        $data = InternalTransactions::where('user_id', auth()->user()->id)->paginate();
        $balance =$request->user()->cryptoBalances->first();
        $currencyId = session()->get('currency_id');
        $general =GeneralSettings::first();
        $currency = Currency::get();
        $withdraw_fixed_fee = number_format((float)$general->withdraw_external_fixed_fee, 8, '.', '');
        $withdraw_percentage_fee = number_format((float)$general->withdraw_external_percentage_fee, 2, '.', '');
        $send_fixed_fee = number_format((float)$general->send_internal_fixed_fee, 8, '.', '');
        $send_percentage_fee = number_format((float)$general->send_internal_percentage_fee, 2, '.', '');

        // withdraw max
        $withdraw_max = number_format((float)($balance->balance - $general->withdraw_external_fixed_fee)/(1+(($general->withdraw_external_percentage_fee)/100)), 8, '.', '');
        $withdraw_max = $withdraw_max < 0 ? 0 : $withdraw_max;

        // send max
        $send_max = number_format((float)($balance->balance - $general->send_internal_fixed_fee)/(1+(($general->send_internal_percentage_fee)/100)), 8, '.', '');
        $send_max = $send_max < 0 ? 0 : $send_max;

        $balance = number_format((float)$balance->balance, 8, '.', '');
        
        return view('user.withdraws', compact('data','general','withdraw_fixed_fee','withdraw_percentage_fee','send_fixed_fee','send_percentage_fee','withdraw_max','send_max', 'balance', 'currency', 'currencyId'));
    }

    /**
     * Show a resource in storage.
     *
     * @param  \App\Models\WithdrawRequest  $withdraw
     *
     * @return \Illuminate\Http\Response
     */
    public function show(WithdrawRequest  $withdraw)
    {
        $page_title = "Withdraw request #{$withdraw->id}";

        return view('admin.withdraw-requests.show', compact('page_title', 'withdraw'));
    }

    public function sendShow(InternalTransactions  $withdraw)
    {
        $page_title = "Send BTC request #{$withdraw->id}";

        return view('admin.send-requests.show', compact('page_title', 'withdraw'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\WithdrawRequest\StoreFormRequest  $request
     * @param  \App\Models\WithdrawRequest  $withdrawRequest
     *
     * @return \Illuminate\Http\Response
     */
    public function afterStore(StoreFormRequest $request, WithdrawRequest $withdrawRequest)
    {
        if(!empty(Auth::user()) && Auth::user()->verified == 1){

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
                        'fee' => number_format((float)$charge, '8', '.', '')
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
                        'fee' => number_format((float)$charge, '8', '.', '')
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
        }else{
            return back()->with('alert', 'Your account and documents are not verified.');
        }
    }
 
    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\WithdrawRequest\StoreFormRequest  $request
     * @param  \App\Models\WithdrawRequest  $withdrawRequest
     *
     * @return \Illuminate\Http\Response
     */
    public function store(StoreFormRequest $request, WithdrawRequest $withdrawRequest)
    {
        if(!empty(Auth::user()) && Auth::user()->verified == 1){
            if(Auth::user()->tauth == 1){
                session()->put('requestAmount', abs($request->amount));
                session()->put('requestAddress', $request->address);
                session()->put('requestDescription', isset($request->description) ? $request->description : null);
                session()->put('requestType', isset($request->type) ? $request->type : 0);
                return redirect()->route('user.withdraws.withdraw2faUI');
            }
            else{
                $basic = GeneralSettings::first();
                $cryptoBalance = $request->user()->cryptoBalances->first();
                if($request->type == 1){
                    $receiver = UserCryptoBalance::where('address', $request->address)->first();
                    if($cryptoBalance->address == $request->address) return redirect()->route('user.withdraws', auth()->user()->username)->with('alert', "Wallet can not send btc to itself");
                    if(!isset($receiver->user_id)) return redirect()->route('user.withdraws', auth()->user()->username)->with('alert', 'Wallet Address invalid!');
                    $charge = $basic->send_internal_fixed_fee + $basic->send_internal_percentage_fee / 100 * abs($request->amount);
                    if(round($charge + abs($request->amount), 8) <= round($cryptoBalance->balance,8)){
                        $old_balance = $cryptoBalance->balance;
                        $cryptoBalance->decrement('balance', abs($request->amount));
                        $cryptoBalance->decrement('balance', $charge);
                        $request->merge([
                            'fee' => number_format((float)$charge, '8', '.', '')
                        ]);
                        if(isset($request->description)){
                            $request->merge([
                                'description' => $request->description
                            ]);
                        }
        
                        $request->user()->sendRequests()
                        ->create($request->all());
    
                        Trx::create([
                            'user_id' => $cryptoBalance->user_id,
                            'pre_main_amo' => number_format((float)$old_balance, '8', '.', '').' BTC',
                            'amount' => number_format((float)abs($request->amount), '8', '.', '').' BTC',
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
                else{
                    $charge = $basic->withdraw_external_fixed_fee + $basic->withdraw_external_percentage_fee / 100 * abs($request->amount);
                    if(round($charge + abs($request->amount), 8) <= round($cryptoBalance->balance,8)){
                        $old_balance = $cryptoBalance->balance;
                        $cryptoBalance->decrement('balance', abs($request->amount));
                        $cryptoBalance->decrement('balance', $charge);
                        $request->merge([
                            'main_amo' => number_format((float)$cryptoBalance->balance, '8', '.', ''),
                            'fee' => number_format((float)$charge, '8', '.', '')
                        ]);
                        if(isset($request->description)){
                            $request->merge([
                                'description' => $request->description
                            ]);
                        }
        
                        $request->user()->withdrawRequests()
                        ->create($request->all());
    
                        Trx::create([
                            'user_id' => $cryptoBalance->user_id,
                            'pre_main_amo' => number_format((float)$old_balance, '8', '.', '').' BTC',
                            'amount' => number_format((float)abs($request->amount), '8', '.', '').' BTC',
                            'main_amo' => number_format((float)$cryptoBalance->balance, '8', '.', '').' BTC',
                            'charge' => number_format((float)$charge, '8', '.', '').' BTC',
                            'type' => '-',
                            'title' => 'Send ' . 'BTC',
                            'trx' => 'Send' . 'BTC' . time(),
                            'deal_url' =>'/user'.'/'.$cryptoBalance->user->username.'/withdraws',
                        ]);
        
                        return redirect()->route('user.withdraws', auth()->user()->username)
                        ->with('success', 'Send request created successfully!');
                    }
                    else{
                        return back()->with('alert', 'Insufficient Balance in Your Account');
                    }
                }                
            }
        
        }else{
            return back()->with('alert', 'Your account and documents are not verified.');
        }
    }

    /**
     * Display a listing of the resource in admin.
     *
     * @return View
     */
    public function withdrawRequests()
    {
        $page_title = 'Withdraw requests';
        $data = WithdrawRequest::paginate();

        return view('admin.withdraw-requests.index', compact('page_title', 'data'));
    }

    public function sendRequests()
    {
        $page_title = 'Send BTC requests';
        $data = InternalTransactions::paginate();

        return view('admin.send-requests.index', compact('page_title', 'data'));
    }

    /**
     * Display a listing of the resource in admin.
     *
     * @return View
     */
    public function withdrawPendingRequests()
    {
        $page_title = 'Withdraw Pending Requests';
        $data = WithdrawRequest::where('status', 'pending')->paginate();

        return view('admin.withdraw-requests.index', compact('page_title', 'data'));
    }

    /**
     * Display a listing of the resource in admin.
     *
     * @return View
     */
    public function sendPendingRequests()
    {
        $page_title = 'Send BTC Pending Requests';
        $data = InternalTransactions::where('status', 'pending')->paginate();

        return view('admin.send-requests.index', compact('page_title', 'data'));
    }

    /**
     * Display a listing of the resource in admin.
     *
     * @return View
     */
    public function withdrawCompleteRequests()
    {
        $page_title = 'Withdraw Completed Requests';
        $data = WithdrawRequest::where('status', 'completed')->paginate();

        return view('admin.withdraw-requests.index', compact('page_title', 'data'));
    }

    /**
     * Display a listing of the resource in admin.
     *
     * @return View
     */
    public function withdraw2faUI()
    {
        $page_title = 'Withdraw requests Authentication';

        return view('auth.withdraw2fa', compact('page_title'));
    }

    /**
     * Reject a resource in storage.
     *
     * @param  \App\Models\WithdrawRequest $withdraw
     *
     * @return View
     */
    public function reject(WithdrawRequest $withdraw)
    {   
        if(!empty(Auth::user()) ){
        $withdraw->status = WithdrawRequest::STATUS_REJECTED;

        if ($withdraw->save()) {
            $cryptoBalance = $withdraw->user->cryptoBalances->first();
            $old_balance = $cryptoBalance->balance;
            $cryptoBalance->increment('balance', $withdraw->amount);
            $cryptoBalance->increment('balance', $withdraw->fee);

            Trx::create([
                'user_id' =>  $withdraw->user->id,
                'pre_main_amo' => number_format((float)$old_balance, 8, '.', '').' BTC',
                'amount' =>number_format((float) $withdraw->amount + $withdraw->fee , 8, '.', '').' BTC',
                'main_amo' =>number_format((float)$cryptoBalance->balance  , 8, '.', '').' BTC',
                'charge' => number_format((float)0  , 8, '.', '').' BTC',
                'type' => '+',
                'title' => 'Refund-' . 'BTC' . ' Completed',
                'trx' => 'Refund' . 'BTC' . time(),
                'deal_url' =>'/user/refunds'
            ]);

            $notification=[];
            $notification['from_user'] =Auth::user()->id ;
            $notification['to_user'] = $withdraw->user->id;
            $notification['noti_type'] ='withdraw';
            $notification['action_id'] =$withdraw->id;
            $notification['message']= 'Your Send request was rejected.';
            
            $notification['url'] ='/user'.'/'.$withdraw->user->username.'/withdraws';
            Notification::create($notification);
            $sbjct ='Your deposit was rejected.';
            $msg ='<p> The amount of '.$withdraw->amount.' BTC has been rejected. Your new balance is now: '.$cryptoBalance->balance.' BTC </p>';
            try{
                send_email($withdraw->user->email, $withdraw->user->username, $sbjct, $msg);
                
            }catch(\Exception $ee){
                // return $ee;
            }

        }

        return redirect()->route('admin.withdraw.requests');
    }else{
            return back()->with('alert', 'Your account and documents are not verified.');
        }
    }

    public function sendReject(InternalTransactions $withdraw)
    {   
        if(!empty(Auth::user()) ){
        $withdraw->status = 'rejected';

        if ($withdraw->save()) {
            $cryptoBalance = $withdraw->user->cryptoBalances->first();
            $old_balance = $cryptoBalance->balance;
            $cryptoBalance->increment('balance', $withdraw->amount);
            $cryptoBalance->increment('balance', $withdraw->fee);

            Trx::create([
                'user_id' =>  $withdraw->user->id,
                'pre_main_amo' => number_format((float) $old_balance, 8, '.', '') .' BTC',
                'amount' =>number_format((float) $withdraw->amount + $withdraw->fee , 8, '.', '').' BTC',
                'main_amo' =>number_format((float)$cryptoBalance->balance  , 8, '.', '').' BTC',
                'charge' => number_format((float)0  , 8, '.', '').' BTC',
                'type' => '+',
                'title' => 'Refund-' . 'BTC' . ' Completed',
                'trx' => 'Refund' . 'BTC' . time(),
                'deal_url' =>'/user/refunds'
            ]);

            $notification=[];
            $notification['from_user'] =Auth::user()->id ;
            $notification['to_user'] = $withdraw->user->id;
            $notification['noti_type'] ='withdraw';
            $notification['action_id'] =$withdraw->id;
            $notification['message']= 'Your Send BTC request was rejected.';
            
            $notification['url'] ='/user'.'/'.$withdraw->user->username.'/sends';
            Notification::create($notification);
            $sbjct ='Your deposit was rejected.';
            $msg ='<p> The amount of '.$withdraw->amount.' BTC has been rejected. Your new balance is now: '.$cryptoBalance->balance.' BTC </p>';
            try{
                send_email($withdraw->user->email, $withdraw->user->username, $sbjct, $msg);
                
            }catch(\Exception $ee){
                // return $ee;
            }

        }

        return redirect()->route('admin.send.requests');
    }else{
            return back()->with('alert', 'Your account and documents are not verified.');
        }
    }

    /**
     * Confirm a resource in storage.
     *
     * @param  \App\Models\WithdrawRequest $withdraw
     * @param  \App\Models\Transaction $transaction
     *
     * @return View
     */
    public function confirm(WithdrawRequest $withdraw, Transaction $transaction)
    {
        try {
            $cryptoBalance = $withdraw->user->cryptoBalances->first();
            

            $withdraw->status = WithdrawRequest::STATUS_COMPLETED;
            $withdraw->save();

            // $withdraw->user->transactions()->create([
            //     'txid' => rand(),
            //     'type' => Transaction::TYPE_WITHDRAW,
            //     'amount' => $withdraw->amount,
            //     'main_amo' => $withdraw->main_amo,
            //     'pre_main_amo' => $withdraw->pre_main_amo,
            //     'fee' => $withdraw->fee,
            //     'address' => $cryptoBalance->address,
            //     'confirmations' => 1,
            //     'status' => WithdrawRequest::STATUS_COMPLETED
            // ]);

            $notification=[];
            $notification['from_user'] =Auth::user()->id ;
            $notification['to_user'] = $withdraw->user->id;
            $notification['noti_type'] ='withdraw';
            $notification['action_id'] =$withdraw->id;
            $notification['message']= 'Your Send is completed.';
            
            $notification['url'] ='/user'.'/'.$withdraw->user->username.'/withdraws';
            Notification::create($notification);
            $sbjct ='Your deposit was successfully completed.';
            $msg ='<p> The amount of '.$withdraw->amount.' BTC has been successfully Sent from your wallet on your request. Your new balance is now: '.$cryptoBalance->balance.' BTC </p>';
            try{
                send_email($withdraw->user->email, $withdraw->user->username, $sbjct, $msg);
                
            }catch(\Exception $ee){
                // return $ee;
            }

            return redirect()->route('admin.withdraw.requests')
              ->with('success', 'Withdraw request has been confirmed!');
        } catch (\Exception $e) {
            return redirect()->route('admin.withdraw.requests')
              ->with('alert', $e->getMessage());
        }
    }

    public function sendConfirm(InternalTransactions $withdraw, Transaction $transaction)
    {
        try {
            $basic = GeneralSettings::first();
            $cryptoBalance = $withdraw->user->cryptoBalances->first();
            

            $withdraw->status = 'completed';
            $withdraw->save();

            $charge = number_format((float)$basic->receive_internal_fixed_fee , 8, '.', '') + number_format((float)$basic->receive_internal_percentage_fee / 100 * $withdraw->amount , 8, '.', '');
            $amount = $withdraw->amount - $charge;
            $receiver = UserCryptoBalance::where('address', $withdraw->address)->first();
            $receiver = User::where('id', $receiver->user_id)->first();
            $receiver_balance = $receiver->cryptoBalances->first();

            $old_balance = $receiver_balance->balance;
            $receiver_balance->increment('balance', $amount);

            Trx::create([
                'user_id' =>  $receiver->id,
                'pre_main_amo' => $old_balance.' BTC',
                'amount' =>number_format((float) $amount , 8, '.', '').' BTC',
                'main_amo' =>number_format((float)$receiver_balance->balance  , 8, '.', '').' BTC',
                'charge' => number_format((float)$charge  , 8, '.', '').' BTC',
                'type' => '+',
                'title' => 'Receive ' . 'BTC' . ' Completed',
                'trx' => 'Receive' . 'BTC' . time(),
                'deal_url' =>'/user'.'/'.$receiver->username.'/receives'
            ]);

            
            $subject ='Your bitcoins are here!  +'.$amount.' BTC added to your Bitcoin.ngo wallet.';
            $message ='<p>Congratulations! The Bitcoin network has cleared your transaction and '.$amount.' BTC is now available in your the bitcoin exchange wallet.</p><p>Thank you for trading on the bitcoin exchange and we look forward to seeing you again.
            </p>';

            send_email($receiver_balance->user->email, $receiver_balance->user->username, $subject, $message);

            $notification=[];
            $sbjct='Your bitcoins are here!  +'.$amount.' BTC added to your wallet.';

            $notification['from_user'] = 1;
            $notification['to_user'] =$receiver_balance->user->id;
            $notification['noti_type'] ='Receive';
            $notification['action_id'] =$transaction->id;
            $notification['message']= $sbjct;
            $notification['url'] ='/user'.'/'.$receiver_balance->user->username.'/receives';     
            Notification::create($notification);


            $notification=[];
            $notification['from_user'] =Auth::user()->id ;
            $notification['to_user'] = $withdraw->user->id;
            $notification['noti_type'] ='send';
            $notification['action_id'] =$withdraw->id;
            $notification['message']= 'Your send BTC request is completed.';
            
            $notification['url'] ='/user'.'/'.$withdraw->user->username.'/sends';
            Notification::create($notification);
            $sbjct ='Your deposit was successfully completed.';
            $msg ='<p> The amount of '.$withdraw->amount.' BTC has been successfully Sent from your wallet on your request. Your new balance is now: '.$cryptoBalance->balance.' BTC </p>';
            try{
                send_email($withdraw->user->email, $withdraw->user->username, $sbjct, $msg);
                
            }catch(\Exception $ee){
                // return $ee;
            }

            return redirect()->route('admin.send.requests')
              ->with('success', 'Send BTC request has been confirmed!');
        } catch (\Exception $e) {
            return redirect()->route('admin.send.requests')
              ->with('alert', $e->getMessage());
        }
    }

    /**
     * Destroy resource in storage.
     *
     * @param \App\Models\WithdrawRequest $withdraw
     *
     * @return View
     */
    public function destroy(WithdrawRequest $withdraw)
    {
        $cryptoBalance = $withdraw->user->cryptoBalances->first();
        $old_balance = $cryptoBalance->balance;
        $cryptoBalance->increment('balance', $withdraw->amount + $withdraw->fee);

        $withdraw->delete();

        Trx::create([
            'user_id' =>  $withdraw->user->id,
            'pre_main_amo' => number_format((float) $old_balance, 8, '.', '') . ' BTC',
            'amount' =>number_format((float) $withdraw->amount + $withdraw->fee , 8, '.', '').' BTC',
            'main_amo' =>number_format((float)$cryptoBalance->balance  , 8, '.', '').' BTC',
            'charge' => number_format((float)0  , 8, '.', '').' BTC',
            'type' => '+',
            'title' => 'Refund-Send-' . 'BTC' . ' Completed',
            'trx' => 'Refund' . 'BTC' . time(),
            'deal_url' =>'/user/refunds'
        ]);

        return redirect()->route('user.withdraws', auth()->user()->username)
          ->with('success', 'Send request canceled successfully!');
    }

    public function destroySend(WithdrawRequest $withdraw)
    {
        $cryptoBalance = $withdraw->user->cryptoBalances->first();
        $old_balance = $cryptoBalance->balance;
        $cryptoBalance->increment('balance', $withdraw->amount + $withdraw->fee);

        $withdraw->delete();

        Trx::create([
            'user_id' =>  $withdraw->user->id,
            'pre_main_amo' => number_format((float) $old_balance, 8, '.', '') . ' BTC',
            'amount' =>number_format((float) $withdraw->amount + $withdraw->fee , 8, '.', '').' BTC',
            'main_amo' =>number_format((float)$cryptoBalance->balance  , 8, '.', '').' BTC',
            'charge' => number_format((float)0  , 8, '.', '').' BTC',
            'type' => '+',
            'title' => 'Refund-Withdraw-' . 'BTC' . ' Completed',
            'trx' => 'Refund' . 'BTC' . time(),
            'deal_url' =>'/user/refunds'
        ]);

        return redirect()->route('user.withdraws', auth()->user()->username)
          ->with('success', 'Withdraw request canceled successfully!');
    }

    public function updateWithDrawWalletAddress(WithdrawRequest $withdraw, Request $request){
        $this->validate(
            $request,
            [
                'address' => 'required|string'
            ]
        );

        $withdraw->address = $request->address;
        $withdraw->save();
        return back()->with('success', 'Wallet address updated successfully!');
    }

    public function updateSendWalletAddress(InternalTransactions $withdraw, Request $request){
        $this->validate(
            $request,
            [
                'address' => 'required|string'
            ]
        );

        $withdraw->address = $request->address;
        $withdraw->save();
        return back()->with('success', 'Wallet address updated successfully!');
    }
}
