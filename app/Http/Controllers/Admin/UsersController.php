<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Advertisement;
use App\Models\Ticket;
use App\Models\TicketComment;
use App\Models\Admin;
use App\Models\Trx;
use App\Models\Rating;
use App\Models\UserCryptoBalance;
use App\Models\Transaction;
use App\Models\WithdrawRequest;
use App\Models\GeneralSettings;
use App\Models\User;
use App\Models\Etemplate;
use App\Models\UserLogin;
use App\Models\Country;
use App\Models\Notification;
use App\Models\Deposit;
use App\Models\Gateway;
use App\Models\Cities;
use App\Models\AdvertiseDeal;
use App\Models\DealConvertion;
use App\Http\Requests\GeneralSetting\SendEmailFormRequest;
use App\Http\Requests\GeneralSetting\UpdateBalanceFormRequest;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Intervention\Image\Facades\Image;
use Exception;

Use Illuminate\Support\Facades\Storage;
use stdClass;

use function PHPSTORM_META\map;

class UsersController extends Controller
{
    
    public function index()
    {
        if(request()->getRequestUri() == '/adminio/users?deposit') {
            $page_title = "Pending Deposit";
            
            if (!request()->has('type') && !request()->has('address')) {
                $data=Transaction::where('status','pending')->paginate(10);
            } else {
                $data = Transaction::where(request()->all())->paginate(10);
            }
            return view('admin.deposit.pending_deposit', compact('data', 'page_title'));
        }
        $query = User::with(['country', 'cryptoBalances', 'cryptoAddvertises'])->latest('users.created_at');
        $page_title = "All User Manage";

        if(request()->active) {
            $page_title = "Active User";
            $query->where('status', 1);
        }

        if(request()->getRequestUri() == '/adminio/users?email=verified') {
            $page_title = "Email Unverified User";
            $query->where('email_verify', 0);
        }

        if(request()->getRequestUri() == '/adminio/users?phone=verified') {
            $page_title = "Phone Unverified User";
            $query->where('phone_verify', 0);
        }

        if(request()->getRequestUri() == '/adminio/users?banned') {
            $page_title = "Banned User";
            $query->where('status', '0');
        }
        if(request()->getRequestUri() == '/adminio/users?unverified') {
            $page_title = "Document Unverified";
            $query->where('document_uploaded', '1');
        }
        if(request()->getRequestUri() == '/adminio/users?autoverified') {
            $page_title = "Document Auto verified";
            $query->where('auto_verified', '1');
        }
        $query->select('users.*');
        $temp_query = $query;
        $query_for_balance = $query;
        $balance = $query_for_balance->join('user_crypto_balances', 'users.id', '=', 'user_crypto_balances.user_id')->sum('balance');
        $all = $temp_query->get();
        $total_users = count($all);
        $total_balance = $balance;
        // foreach($all as $one){
        //     $ee =UserCryptoBalance::where('user_id', $one->id)->first();
        //     if(!empty($ee)){
        //         $total_balance += $ee->balance;
        //     }else{
        //         $total_balance += 0;
        //     }
        // }
        $users = $query->get();
        foreach($users as $user){
            $user->adds =Advertisement::where('user_id', $user->id)->count();
            $user->opendeals = AdvertiseDeal::where('gateway_id', 505)->where('status','!=', 1)->where('status','!=', 21)->where('status','!=', 2)->where(function ($query) use ($user) {
                $query->where('to_user_id', $user->id);
                $query->orWhere('from_user_id', $user->id);
            })->count();
            $user->completedeals = AdvertiseDeal::where(function($query) use ($user){
                return $query
                ->where('to_user_id', $user->id)
                ->orWhere('from_user_id', $user->id);
                
            })->
            where(function($query){
                return $query
                ->where('status', 1);
                
            })->count();
            $user->disputedeals = AdvertiseDeal::where(function($query) use ($user){
                return $query
                ->where('to_user_id', $user->id)
                ->orWhere('from_user_id', $user->id);
                
            })->
            where(function($query){
                return $query
                ->where('status', 10);
                
            })->count();
            // $user->last_login = UserLogin::whereUser_id($user->id)->orderBy('id', 'desc')->first();
            $ee =UserCryptoBalance::where('user_id', $user->id)->first();
            if(!empty($ee)){
                $user->blnce =$ee->balance;
            }else{
                $user->blnce = 0;
            }
            
        }
        // return $users;
        $countries = Country::all();
        
        return view('admin.users.index', compact('page_title', 'users', 'total_users', 'total_balance', 'countries'));
    }

    public function filter(Request $request)
    {
        $query = User::with(['country', 'cryptoBalances', 'cryptoAddvertises'])->latest('users.created_at');
        $page_title = "Filtered Users";

        if(request()->document == 'verified') {
            $query->where('verified', 1);
        }else if(request()->document == 'unverified') {
            $query->where('verified', 0);
        }

        if(isset($request->country)){
            $country = $request->country;
            $query->where(function($q) use ($country){
                $i = 1;
                foreach($country as $c){
                    $i == 1 ? $q->where('country_id', $c) : $q->orWhere('country_id', $c);
                    $i++;
                }
            });
        }

        if(request()->email == 'verified') {
            $query->where('email_verify', 1);
        }else if(request()->email == 'unverified') {
            $query->where('email_verify', 0);
        }

        if(request()->user_type == 'real') {
            $query->where('email', 'not like', '%@tbe.email');
            $query->where('users.address', '!=', 'Testaddonebtc');
        }else if(request()->user_type == 'marketing') {
            $query->where('email', 'like', '%@tbe.email');
            $query->where('users.address', 'Testaddonebtc');
        }

        if(request()->phone == 'verified') {
            $query->where('phone_verify', 1);
        }else if(request()->phone == 'unverified') {
            $query->where('phone_verify', 0);
        }

        if(request()->status == 'active') {
            $query->where('status', 1);
        }else if(request()->status == 'inactive') {
            $query->where('status', 0);
        }
        $query->select('users.*');
        // dd($query->toSql());
        $temp_query = $query;
        $query_for_balance = $query;
        $balance = $query_for_balance->join('user_crypto_balances', 'users.id', '=', 'user_crypto_balances.user_id')->sum('balance');
        $all = $temp_query->get();
        $total_users = count($all);
        $total_balance = $balance;
        // foreach($all as $one){
        //     $ee =UserCryptoBalance::where('user_id', $one->id)->first();
        //     if(!empty($ee)){
        //         $total_balance += $ee->balance;
        //     }else{
        //         $total_balance += 0;
        //     }
        // }
        $users = $query->get();
        foreach($users as $user){
            $user->adds =Advertisement::where('user_id', $user->id)->count();
            $user->opendeals = AdvertiseDeal::where('gateway_id', 505)->where('status','!=', 1)->where('status','!=', 21)->where('status','!=', 2)->where(function ($query) use ($user) {
                $query->where('to_user_id', $user->id);
                $query->orWhere('from_user_id', $user->id);
            })->count();
            $user->completedeals = AdvertiseDeal::where(function($query) use ($user){
                return $query
                ->where('to_user_id', $user->id)
                ->orWhere('from_user_id', $user->id);
                
            })->
            where(function($query){
                return $query
                ->where('status', 1);
                
            })->count();
            $user->disputedeals = AdvertiseDeal::where(function($query) use ($user){
                return $query
                ->where('to_user_id', $user->id)
                ->orWhere('from_user_id', $user->id);
                
            })->
            where(function($query){
                return $query
                ->where('status', 10);
                
            })->count();
            $user->last_login = UserLogin::whereUser_id($user->id)->orderBy('id', 'desc')->first();
            $ee =UserCryptoBalance::where('user_id', $user->id)->first();
            if(!empty($ee)){
                $user->blnce =$ee->balance;
            }else{
                $user->blnce = 0;
            }
            
        }
        // return $users;
        $countries = Country::all();
        
        return view('admin.users.index', compact('page_title', 'users', 'total_users', 'total_balance', 'countries'));
    }

    public function users_country($ids){
        $ids = explode(',', $ids);
        $page_title = "Selected Countries Users";
        $userss = array_map(function($id){
            $query = User::with(['country', 'cryptoBalances', 'cryptoAddvertises'])->latest();
            $query->where('country_id', $id);
        
            return $query->paginate(1000000);
        },$ids);
        $temp = array();
        $total_balance = 0;
        foreach($userss as $users){
            foreach($users as $user){
                $user->adds =Advertisement::where('user_id', $user->id)->count();
                $user->opendeals = AdvertiseDeal::where('gateway_id', 505)->where('status','!=', 1)->where('status','!=', 21)->where('status','!=', 2)->where(function ($query) use ($user) {
                    $query->where('to_user_id', $user->id);
                    $query->orWhere('from_user_id', $user->id);
                })->count();
                $user->completedeals = AdvertiseDeal::where(function($query) use ($user){
                    return $query
                    ->where('to_user_id', $user->id)
                    ->orWhere('from_user_id', $user->id);
                    
                })->
                where(function($query){
                    return $query
                    ->where('status', 1);
                    
                })->count();
                $user->disputedeals = AdvertiseDeal::where(function($query) use ($user){
                    return $query
                    ->where('to_user_id', $user->id)
                    ->orWhere('from_user_id', $user->id);
                    
                })->
                where(function($query){
                    return $query
                    ->where('status', 10);
                    
                })->count();
                $ee =UserCryptoBalance::where('user_id', $user->id)->first();
                if(!empty($ee)){
                    $user->blnce =$ee->balance;
                }else{
                    $user->blnce = 0;
                }
                $user->last_login = UserLogin::whereUser_id($user->id)->orderBy('id', 'desc')->first();
                $total_balance += $user->blnce;
                array_push($temp, $user);
            }
        }
        // print_r($temp);
        $users = $temp;
        $total_users = count($users);
        $countries = Country::all();

        
        return view('admin.users.index', compact('page_title', 'users', 'countries', 'ids', 'total_users', 'total_balance'));
    }

    public function marketing_users()
    {
        $query = User::with(['country', 'cryptoBalances', 'cryptoAddvertises'])->latest();
        $page_title = "All Marketing Users";

        $query->where('email', 'like', '%@tbe.email');
        $query->where('address', 'Testaddonebtc');

        $users = $query->paginate(100000);
        $total_users = count($users);
        $total_balance = 0;
        foreach($users as $user){
            $user->adds =Advertisement::where('user_id', $user->id)->count();
            $user->opendeals = AdvertiseDeal::where('gateway_id', 505)->where('status','!=', 1)->where('status','!=', 21)->where('status','!=', 2)->where(function ($query) use ($user) {
                $query->where('to_user_id', $user->id);
                $query->orWhere('from_user_id', $user->id);
            })->count();
            $user->completedeals = AdvertiseDeal::where(function($query) use ($user){
                return $query
                ->where('to_user_id', $user->id)
                ->orWhere('from_user_id', $user->id);
                
            })->
            where(function($query){
                return $query
                ->where('status', 1);
                
            })->count();
            $user->disputedeals = AdvertiseDeal::where(function($query) use ($user){
                return $query
                ->where('to_user_id', $user->id)
                ->orWhere('from_user_id', $user->id);
                
            })->
            where(function($query){
                return $query
                ->where('status', 10);
                
            })->count();
            $ee =UserCryptoBalance::where('user_id', $user->id)->first();
            if(!empty($ee)){
                $user->blnce =$ee->balance;
            }else{
                $user->blnce = 0;
            }
            $user->last_login = UserLogin::whereUser_id($user->id)->orderBy('id', 'desc')->first();
            $total_balance += $user->blnce;
            
        }
        // return $users;
        $countries = Country::all();
        $total_balance = round($total_balance, 8);

        $id = -1;
        return view('admin.users.marketing_users', compact('page_title', 'users', 'countries', 'id', 'total_users', 'total_balance'));
    }

    public function marketing_users_country($ids)
    {
        
        $ids = explode(',', $ids);
        $page_title = "All Marketing Users";
        $userss = array_map(function($id){
            $query = User::with(['country', 'cryptoBalances', 'cryptoAddvertises'])->latest();
    
            $query->where('address', 'Testaddonebtc');
            $query->where('email', 'like', '%@tbe.email');
            $query->where('country_id', $id);
        
            return $query->paginate(1000000);
        },$ids);
        $temp = array();
        $total_balance = 0;
        foreach($userss as $users){
            foreach($users as $user){
                $user->adds =Advertisement::where('user_id', $user->id)->count();
                $user->opendeals = AdvertiseDeal::where('gateway_id', 505)->where('status','!=', 1)->where('status','!=', 21)->where('status','!=', 2)->where(function ($query) use ($user) {
                    $query->where('to_user_id', $user->id);
                    $query->orWhere('from_user_id', $user->id);
                })->count();
                $user->completedeals = AdvertiseDeal::where(function($query) use ($user){
                    return $query
                    ->where('to_user_id', $user->id)
                    ->orWhere('from_user_id', $user->id);
                    
                })->
                where(function($query){
                    return $query
                    ->where('status', 1);
                    
                })->count();
                $user->disputedeals = AdvertiseDeal::where(function($query) use ($user){
                    return $query
                    ->where('to_user_id', $user->id)
                    ->orWhere('from_user_id', $user->id);
                    
                })->
                where(function($query){
                    return $query
                    ->where('status', 10);
                    
                })->count();
                $ee =UserCryptoBalance::where('user_id', $user->id)->first();
                if(!empty($ee)){
                    $user->blnce =$ee->balance;
                }else{
                    $user->blnce = 0;
                }
                $user->last_login = UserLogin::whereUser_id($user->id)->orderBy('id', 'desc')->first();
                $total_balance += $user->blnce;
                array_push($temp, $user);
            }
        }
        // print_r($temp);
        $users = $temp;
        $total_users = count($users);
        $countries = Country::all();

        
        return view('admin.users.marketing_users', compact('page_title', 'users', 'countries', 'ids', 'total_users', 'total_balance'));
    }

    public function marketing_users_action(Request $request){
        if(!isset($request->selected_users)) return back()->with('message', 'Please select at least one user');
        // else return back()->with('message', count($request->selected_users));
        $users = $request->selected_users;
        
        if($request->submit == "Update Login"){
            foreach($users as $user_id){
                $user = UserLogin::where('user_id', $user_id)->orderBy('id', 'desc')->first();
                if(isset($user->id)){
                    $date = date("Y-m-d H:i:s");
                    $time = strtotime($date);
                    $time = $time - (rand(15,150) * 60);
                    $date = date("Y-m-d H:i:s", $time);
    
                    $data = array(
                        "user_id" => $user->user_id,
                        "location" => $user->location,
                        "user_ip" => $user->user_ip,
                        "country_name" => $user->country_name,
                        "details" => $user->details,
                        "browser" => $user->browser,
                        "platform" => $user->platform,
                        "action" => $user->action,
                        "is_country_changed" => 0,
                        // "created_at" => $date,
                        // "updated_at" => $date
                    );
    
                    $record = UserLogin::create($data);
                    $user = UserLogin::find($record->id);
                    $user->created_at = $date;
                    $user->updated_at = $date;
                    $user->save();
                }
                else{
                    $date = date("Y-m-d H:i:s");
                    $time = strtotime($date);
                    $time = $time - (rand(15,150) * 60);
                    $date = date("Y-m-d H:i:s", $time);
    
                    $data = array(
                        "user_id" => $user_id,
                        // "location" => $user->location,
                        "user_ip" => "127.0.0.1",
                        // "country_name" => $user->country_name,
                        "details" => "this user has no pervios login history",
                        // "browser" => $user->browser,
                        // "platform" => $user->platform,
                        // "action" => $user->action,
                        "is_country_changed" => 0,
                        // "created_at" => $date,
                        // "updated_at" => $date
                    );
    
                    $record = UserLogin::create($data);
                    $user = UserLogin::find($record->id);
                    $user->created_at = $date;
                    $user->updated_at = $date;
                    $user->save();
                }
            }
            return back()->with('message', 'Users Login Updated Successfuly!');
        }
        elseif($request->submit == "Add Balance"){
		
            $basic = GeneralSettings::first();
            $amount = 0.1;
            foreach($users as $user_id){
                $user = User::find($user_id);

                $balance = UserCryptoBalance::where('user_id',$user_id)->first();
                $old_balance = $balance->balance;
                $balance->balance += abs($amount);
                $balance->balance =number_format((float)$balance->balance, 8, '.', '');
                $balance->save();

                Trx::create([
                    'user_id' =>  $user_id,
                    'pre_main_amo' => number_format((float)$old_balance, 8, '.', '').' BTC',
                    'amount' =>number_format((float) abs($amount) , 8, '.', '').' BTC',
                    'main_amo' =>number_format((float)$balance->balance  , 8, '.', '').' BTC',
                    'charge' => number_format((float)0, 8, '.', '').' BTC',
                    'type' => '+',
                    'title' => 'Added By Admin',
                    'trx' => 'Adjustment' . 'BTC' . time(),
                    'deal_url' =>'/user/adjustments'
                ]);

                $txt = abs($amount) . ' ' . $basic->currency . ' credited to your account.' .'<br>';
                notify($user, abs($amount) . ' ' . $basic->currency .' credited to your account', $txt);
            
                $user->transactions()->create([
                    'txid' => '',
                    'status' => 'add',
                    'amount' => number_format((float)   abs($amount) , 8, '.', ''),
                    'main_amo' =>number_format((float)  $balance->balance , 8, '.', ''),
                    'address' => $balance->address,
                    'type' => Transaction::TYPE_MANUAL,
                ]);
            }
            
            $ee= 'added';
            $msg ='Money '.$ee.' Successful!';


            return back()->with('success',$msg );
        }
        elseif($request->submit == "Subtract Balance"){
            $basic = GeneralSettings::first();
            $amount = 0.1;
            foreach($users as $user_id){
                $user = User::find($user_id);

                $balance = UserCryptoBalance::where('user_id',$user_id)->first();
                $old_balance = $balance->balance;
                if($old_balance - abs($amount) >= 0){
                    $balance->balance -= abs($amount);
                    $balance->balance =number_format((float)$balance->balance, 8, '.', '');
                    $balance->save();

                    Trx::create([
                        'user_id' =>  $user_id,
                        'pre_main_amo' => number_format((float)$old_balance, 8, '.', '').' BTC',
                        'amount' => number_format((float) abs($amount) , 8, '.', '').' BTC',
                        'main_amo' => number_format((float)$balance->balance  , 8, '.', '').' BTC',
                        'charge' => number_format((float)0, 8, '.', '').' BTC',
                        'type' => '-',
                        'title' => 'Subtracted By Admin',
                        'trx' => 'Adjustment' . 'BTC' . time(),
                        'deal_url' =>'/user/adjustments'
                    ]);

                    $txt = abs($amount) . ' ' . $basic->currency . ' debited from your account.' .'<br>';
                    notify($user, abs($amount) . ' ' . $basic->currency .' debited from your account', $txt);
                
                    $user->transactions()->create([
                        'txid' => '',
                        'status' => 'subtract',
                        'amount' => number_format((float)   abs($amount) , 8, '.', ''),
                        'main_amo' =>number_format((float)  $balance->balance , 8, '.', ''),
                        'address' => $balance->address,
                        'type' => Transaction::TYPE_MANUAL,
                    ]);
                }
            }
            
            $ee= 'subtract';
            $msg ='Money '.$ee.' Successful!';


            return back()->with('success',$msg );
        }
        elseif($request->submit == "Keep Fraction"){
            $basic = GeneralSettings::first();
            foreach($users as $user_id){
                $user = User::find($user_id);

                $balance = UserCryptoBalance::where('user_id',$user_id)->first();
                $old_balance = $balance->balance;
                $amount = (int) $old_balance;
                if($old_balance - abs($amount) >= 0){
                    $balance->balance -= abs($amount);
                    $balance->balance =number_format((float)$balance->balance, 8, '.', '');
                    $balance->save();

                    Trx::create([
                        'user_id' =>  $user_id,
                        'pre_main_amo' => number_format((float)$old_balance, 8, '.', '').' BTC',
                        'amount' => number_format((float) abs($amount) , 8, '.', '').' BTC',
                        'main_amo' => number_format((float)$balance->balance  , 8, '.', '').' BTC',
                        'charge' => number_format((float)0, 8, '.', '').' BTC',
                        'type' => '-',
                        'title' => 'Subtracted By Admin',
                        'trx' => 'Adjustment' . 'BTC' . time(),
                        'deal_url' =>'/user/adjustments'
                    ]);

                    $txt = abs($amount) . ' ' . $basic->currency . ' debited from your account.' .'<br>';
                    notify($user, abs($amount) . ' ' . $basic->currency .' debited from your account', $txt);
                
                    $user->transactions()->create([
                        'txid' => '',
                        'status' => 'subtract',
                        'amount' => number_format((float)   abs($amount) , 8, '.', ''),
                        'main_amo' =>number_format((float)  $balance->balance , 8, '.', ''),
                        'address' => $balance->address,
                        'type' => Transaction::TYPE_MANUAL,
                    ]);
                }
            }
            
            $ee= 'subtract';
            $msg ='Money '.$ee.' Successful!';


            return back()->with('success',$msg );
        }
        elseif($request->submit == "Nullify Balance"){
            $basic = GeneralSettings::first();
            $amount = 0;
            foreach($users as $user_id){
                $user = User::find($user_id);

                $balance = UserCryptoBalance::where('user_id',$user_id)->first();
                $old_balance = $balance->balance;
                $amount = $old_balance;
                $balance->balance -= abs($amount);
                $balance->balance =number_format((float)$balance->balance, 8, '.', '');
                $balance->save();

                Trx::create([
                    'user_id' =>  $user_id,
                    'pre_main_amo' => number_format((float)$old_balance, 8, '.', '').' BTC',
                    'amount' => number_format((float) abs($amount) , 8, '.', '').' BTC',
                    'main_amo' => number_format((float)$balance->balance  , 8, '.', '').' BTC',
                    'charge' => number_format((float)0, 8, '.', '').' BTC',
                    'type' => '-',
                    'title' => 'Subtracted By Admin',
                    'trx' => 'Adjustment' . 'BTC' . time(),
                    'deal_url' =>'/user/adjustments'
                ]);

                $txt = abs($amount) . ' ' . $basic->currency . ' debited from your account.' .'<br>';
                notify($user, abs($amount) . ' ' . $basic->currency .' debited from your account', $txt);
            
                $user->transactions()->create([
                    'txid' => '',
                    'status' => 'subtract',
                    'amount' => number_format((float)   abs($amount) , 8, '.', ''),
                    'main_amo' =>number_format((float)  $balance->balance , 8, '.', ''),
                    'address' => $balance->address,
                    'type' => Transaction::TYPE_MANUAL,
                ]);
            }
            
            $ee= 'nullify';
            $msg ='Money '.$ee.' Successful!';


            return back()->with('success',$msg );
        }
        elseif($request->submit == "Activate ADs"){
            foreach($users as $user){
                Advertisement::where('user_id', $user)->update(['status' => 1]);
            }
            return back()->with('message', 'ADs Activated Successfuly!');
        }
        elseif($request->submit == "Deactivate ADs"){
            foreach($users as $user){
                Advertisement::where('user_id', $user)->update(['status' => 0]);
            }
            return back()->with('message', 'ADs Deactivated Successfuly!');
        }
        elseif($request->submit == "Deactivate Users"){
            foreach($users as $user){
                User::where('id', $user)->update(['status' => 0]);
            }
            return back()->with('message', 'Users Deactivated Successfuly!');
        }
        elseif($request->submit == "Activate Users"){
            foreach($users as $user){
                User::where('id', $user)->update(['status' => 1]);
            }
            return back()->with('message', 'Users Activated Successfuly!');
        }
        elseif($request->submit == "Delete Users"){
            foreach($users as $user){
                UserCryptoBalance::where('user_id', $user)->delete();
                User::where('id', $user)->delete();
            }
            return back()->with('message', 'Users Deleted Successfuly!');
        }
        elseif($request->submit == "Update Rating"){
            foreach($users as $user){
                $diff = 0;

                $date = date("Y-m-d H:i:s");
                $time = strtotime($date);
                $time = $time - (rand(120, 6*60) * 60);
                $date = date("Y-m-d H:i:s", $time);

                $all = Rating::where('to_user', $user)->orderBy('id','desc')->get();
                $first = true;
                $var = 1;
                foreach ($all as $one) {
                    if($first){
                        $first = false;
                        $old_date = strtotime($one->updated_at);
                        // $diff = $time - $old_date;
                        // echo "($one->id) $one->updated_at ==> $date <br />";
                        Rating::where('id', $one->id)->update(['updated_at' => $date]);
                    }
                    else{
                        // $time = strtotime($one->updated_at);
                        // $time = $time + $diff;
                        $var = rand($var, $var + 120);
                        $date = date("Y-m-d H:i:s", $time - $var * 60 * 60);
                        // echo "($one->id) $one->updated_at ==> $date <br />";
                        Rating::where('id', $one->id)->update(['updated_at' => $date]);
                    }
                }

                // Rating::where('to_user', $user)->orderBy('id','desc')->take(1)->update(['updated_at' => $date]);
                
            }
            return back()->with('message', 'Users Rating Updated Successfuly!');
        }
        elseif($request->submit == "Create Deals"){
            for($i = 0; $i < 100; $i++){
                $country_id = mt_rand(1, 246);
                $marketing_users = User::where('country_id', $country_id)->where('status', 1)->where('email', 'like', '%@tbe.email')->where('address', 'Testaddonebtc')->get();
            
                foreach($marketing_users as $original_user){
                    $user = UserLogin::where('user_id', $original_user->id)->orderBy('id', 'desc')->first();
                    if(isset($user->id)){
                        $date = date("Y-m-d H:i:s");
                        $time = strtotime($date);
                        $time = $time - (rand(15,150) * 60);
                        $date = date("Y-m-d H:i:s", $time);
        
                        $data = array(
                            "user_id" => $user->user_id,
                            "location" => $user->location,
                            "user_ip" => $user->user_ip,
                            "country_name" => $user->country_name,
                            "details" => $user->details,
                            "browser" => $user->browser,
                            "platform" => $user->platform,
                            "action" => $user->action,
                            "is_country_changed" => 0,
                            // "created_at" => $date,
                            // "updated_at" => $date
                        );
        
                        $record = UserLogin::create($data);
                        $user = UserLogin::find($record->id);
                        $user->created_at = $date;
                        $user->updated_at = $date;
                        $user->save();
                    }
                    else{
                        $date = date("Y-m-d H:i:s");
                        $time = strtotime($date);
                        $time = $time - (rand(15,150) * 60);
                        $date = date("Y-m-d H:i:s", $time);
        
                        $data = array(
                            "user_id" => $original_user->id,
                            // "location" => $user->location,
                            "user_ip" => "127.0.0.1",
                            // "country_name" => $user->country_name,
                            "details" => "this user has no pervios login history",
                            // "browser" => $user->browser,
                            // "platform" => $user->platform,
                            // "action" => $user->action,
                            "is_country_changed" => 0,
                            // "created_at" => $date,
                            // "updated_at" => $date
                        );
        
                        $record = UserLogin::create($data);
                        $user = UserLogin::find($record->id);
                        $user->created_at = $date;
                        $user->updated_at = $date;
                        $user->save();
                    }

                    $date = date("Y-m-d H:i:s");
                    $time = strtotime($date);
                    $time = $time - (rand(1*60, 3*60) * 60);
                    $date = date("Y-m-d H:i:s", $time);

                    $all = Rating::where('to_user', $original_user->id)->orderBy('id','desc')->get();
                    $first = true;
                    $var = 1;
                    foreach ($all as $one) {
                        if($first){
                            $first = false;
                            $old_date = strtotime($one->updated_at);
                            // $diff = $time - $old_date;
                            // echo "($one->id) $one->updated_at ==> $date <br />";
                            Rating::where('id', $one->id)->update(['updated_at' => $date]);
                        }
                        else{
                            // $time = strtotime($one->updated_at);
                            // $time = $time + $diff;
                            $var = rand($var, $var + 5);
                            $date = date("Y-m-d H:i:s", $time - $var * 60 * 60);
                            // echo "($one->id) $one->updated_at ==> $date <br />";
                            Rating::where('id', $one->id)->update(['updated_at' => $date]);
                        }
                    }
                }
            }

            $country_id = 100; // rand(1,246);
            $marketing_users = User::where('country_id', $country_id)->where('status', 1)->where('email', 'like', '%@tbe.email')->where('address', 'Testaddonebtc')->get();
            $no_of_users = sizeof($marketing_users);
            
            //checking users total
            if($no_of_users >= 2){
                $temp = array();
                foreach($marketing_users as $mu){
                    $temp[] = $mu;
                }
                $marketing_users = $temp;
                if(shuffle($marketing_users)){
                    $first_user = $marketing_users[0];
                    $second_user = $marketing_users[1];
                    
                    if(Advertisement::where('user_id', $first_user->id)->where('status', 1)->count() > 0){
                        $ad = Advertisement::where('user_id', $first_user->id)->where('status', 1)->first();
                        $deal_id = $this->createDeal($second_user, $ad); 
                    }else if(Advertisement::where('user_id', $second_user->id)->where('status', 1)->count() > 0){
                        $ad = Advertisement::where('user_id', $second_user->id)->where('status', 1)->first();
                        $deal_id = $this->createDeal($first_user, $ad); 
                    }

                    if($deal_id != "-1"){
                        $this->completeDeal($deal_id);
                    }
                }
                return back()->with('message', 'Deals Created Successfuly!');
            }
            return back()->with('message', 'No Deal Created!');
        }
    }

    public function completeDeal($deal_id){
        $general = GeneralSettings::first();
        $add = AdvertiseDeal::findOrFail($deal_id);
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

    }

    public function createDeal($actual_user, $advertise){
        $amount = rand($advertise->min_amount, $advertise->max_amount);
        $bal =  UserCryptoBalance::where('user_id', $actual_user->id)->where('gateway_id', $advertise->gateway_id)->first();
        $trans_id = time() . rand(11111,99999);
        $general =GeneralSettings::first();

        if ($advertise->add_type == 1) {
            // jis ny ad create ki ha
            $to_user =UserCryptoBalance::where('user_id', $advertise->user_id)->where('gateway_id', $advertise->gateway_id)->first();
            $coin_amount = number_format((float)rand(1,3)/100 * $to_user->balance , 8, '.', '');
            // $amount = round((float)$coin_amount * $advertise->currency->usd_rate);
            $usd_rate = round($amount / $advertise->currency->usd_rate);

            $charge = number_format((float)($general->sell_advertiser_fixed_fee) + (($coin_amount * $general->sell_advertiser_percentage_fee)/100) , 8, '.', '');
            $total = $coin_amount + $charge;

            if ($to_user->balance <= $total) {            
                return "-1";
                // return $to_user->balance . " - ". $coin_amount . " - ". $total . " - ". $to_user->user_id . " - ". $amount . " - ". $advertise->id . " - ". $advertise->add_type;
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
                'from_user_id' => $actual_user->id,
                'trans_id' => $trans_id,
                'usd_amount' => $usd_rate,
                'coin_amount' => $coin_amount,
                'amount_to' => $amount,
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

            $to_user = User::findOrFail($advertise->user_id);
            $url="/user/deal/$trans_id";
            $msg =  "<p>You have just started a deal with ".$to_user->username.". You can see your offer and message to ".$to_user->username." through the chat box on the <a href=". config('app.url').$url.">deal page</a>.</p><p>Please do not reply to this email. Your deal partner will not be able to see your response.</p>";
            $sbjct='You have started a deal with '.$to_user->username.'.';
            
            $notification=[];
            $notification['from_user'] =$to_user->id ;
            $notification['to_user'] = $actual_user->id;
            $notification['noti_type'] ='deal';
            $notification['action_id'] =$deal->id;
            $notification['message']= 'You started deal '.$trans_id.'.';
            
            $notification['url'] =$url;
            $notification['add_type']=$deal->add_type;
            $notification['deal_id']=$deal->id;
            $notification['advertisement_id']=$deal->advertisement_id;
            
            Notification::create($notification);
            
            try{
                send_email($actual_user->email, $actual_user->username, $sbjct, $msg);
                send_sms($actual_user->phone, $msg);
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
                'title' => 'Sell to '.$actual_user->username,
                'trx' => 'SellBTC'.time(),
                'deal_url'=>$url
            ]);
            
            $message= "<p>".ucfirst($actual_user->username)." has just offered to start a deal with you. You can see the offer and respond to it on the  <a href=". config('app.url').$url.">deal page</a>.</p><p>Please do not reply to this email. Your deal partner will not be able to see your response.</p>";
            $sbjct=ucfirst($actual_user->username).' has started a deal '.$trans_id.' with you.';
                        
            try{
                send_email($from_user->email, $from_user->username,$sbjct, $message);
                send_sms($from_user->phone, $message);
            }catch(\Exception $ee){
                // return $ee;
            }

            $notification=[];
            $notification['from_user'] = $actual_user->id;
            $notification['to_user'] =$from_user->id;
            $notification['noti_type'] ='deal';
            $notification['action_id'] =$deal->id;
            $notification['message']= $sbjct;
            
            $notification['url'] ="/user/deal-reply/$trans_id";
            $notification['add_type']=$deal->add_type;
            $notification['deal_id']=$deal->id;
            $notification['advertisement_id']=$deal->advertisement_id;
            
            Notification::create($notification);

            return $deal->id;
        } else {
            $to_user =UserCryptoBalance::where('user_id', $actual_user->id)->where('gateway_id', $advertise->gateway_id)->first();
            $coin_amount = rand(1,3)/100 * $to_user->balance;
            // $amount = $coin_amount * $advertise->currency->usd_rate ;
            $usd_rate = $amount / $advertise->currency->usd_rate ;

            $charge = number_format((float)($general->sell_user_fixed_fee) + (($coin_amount * $general->sell_user_percentage_fee)/100) , 8, '.', '');
            $total = $coin_amount + $charge;
            
            // jo bech rha buyer ki add pr
            if ($advertise->add_type == 2 && $bal->balance <= $total) {                
                return "-1";
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
                'add_type' => '2',
                'to_user_id' =>$advertise->user_id ,
                'from_user_id' =>$actual_user->id ,
                'trans_id' => $trans_id,
                'usd_amount' => $usd_rate,
                'coin_amount' => $coin_amount,
                'amount_to' => $amount,
                'status' => 0,
                'dispute_timer' => time(),
                'advertiser_id' => $advertise->user_id,
                'advertisement_id' => $advertise->id,
            ]);

            $to_user = User::findOrFail($advertise->user_id);
            $url="/user/deal-reply/$trans_id";

            Trx::create([
                'user_id' => $actual_user->id,
                'pre_main_amo' =>number_format((float)$old_balance , 8, '.', '') .' BTC',
                'amount' =>number_format((float)$coin_amount , 8, '.', '') .' BTC',
                'main_amo' =>number_format((float)$after_bal , 8, '.', '') .' BTC',
                'charge' => number_format((float)$charge, 8, '.', '') . ' BTC',
                'type' => '-',
                'title' => 'Sell to '.$to_user->username,
                'trx' => 'SellBTC'.time(),
                'deal_url'=> $url
            ]);
            
            $msg =  "<p>You have just started a deal with ".$to_user->username.". You can see your offer and message to ".$to_user->username." through the chat box on the <a href=". config('app.url').$url.">deal page</a>.</p><p>Please do not reply to this email. Your deal partner will not be able to see your response.</p>";
            $sbjct='You have started a deal with '.$to_user->username.'.';
            
            try{
                send_email($actual_user->email, $actual_user->username, $sbjct, $msg);
                send_sms($actual_user->phone, $msg);
            }catch(\Exception $ee){

            }

            $notification=[];
            $notification['from_user'] =$to_user->id ;
            $notification['to_user'] = $actual_user->id;
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
            
            $message= "<p>".ucfirst($actual_user->username)." has just offered to start a deal with you. You can see the offer and respond to it on the  <a href=". config('app.url').$url.">deal page</a>.</p><p>Please do not reply to this email. Your deal partner will not be able to see your response.</p>";
            $sbjct=ucfirst($actual_user->username).' has started a deal '.$trans_id.' with you.';
                        
            try{
                send_email($from_user->email, $from_user->username, $sbjct, $message);
                send_sms($from_user->phone, $message);
            }catch(\Exception $ee){

            }

            $notification=[];
            $notification['from_user'] = $actual_user->id;
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

            return $deal->id;
        }
    }

    public function users24signups()
    {
        
        $query = User::with(['country', 'cryptoBalances', 'cryptoAddvertises'])->latest();
        $page_title = "New SignUps 24 Hours";

        $query->where('created_at', '>', Carbon::now()->subDay())->where('created_at', '<=', Carbon::now());
        $users = $query->paginate(20);
        foreach($users as $user){
            $user->adds =Advertisement::where('user_id', $user->id)->count();
            $user->opendeals = AdvertiseDeal::where('gateway_id', 505)->where('status','!=', 1)->where('status','!=', 21)->where('status','!=', 2)->where(function ($query) use ($user) {
                $query->where('to_user_id', $user->id);
                $query->orWhere('from_user_id', $user->id);
            })->count();
            $user->completedeals = AdvertiseDeal::where(function($query) use ($user){
                return $query
                ->where('to_user_id', $user->id)
                ->orWhere('from_user_id', $user->id);
                
            })->
            where(function($query){
                return $query
                ->where('status', 1);
                
            })->count();
            $user->disputedeals = AdvertiseDeal::where(function($query) use ($user){
                return $query
                ->where('to_user_id', $user->id)
                ->orWhere('from_user_id', $user->id);
                
            })->
            where(function($query){
                return $query
                ->where('status', 10);
                
            })->count();
            $ee =UserCryptoBalance::where('user_id', $user->id)->first();
            if(!empty($ee)){
                $user->blnce =$ee->balance;
            }else{
                $user->blnce = 0;
            }
            
        }
        // return $users;
        
        return view('admin.users.users', compact('page_title', 'users'));
    }

    public function usersonline()
    {
        $query = User::latest();
        $page_title = "Currently Online Users";
        // echo UserLogin::where('created_at', '>', Carbon::now()->subMinutes(GeneralSettings::first()->dashboard_refresh_time))->where('created_at', '<=', Carbon::now())->where('user_id', '!=', 1)->distinct('user_id')->get();exit;
        $online_users = UserLogin::where('created_at', '>', Carbon::now()->subMinutes(GeneralSettings::first()->dashboard_refresh_time))->where('created_at', '<=', Carbon::now())->distinct('user_id')->get();
        foreach ($online_users as $u) {
            $query->orWhere('id', $u->user_id);
        }
        
        $users = $query->paginate(10);
        foreach($users as $user){
            $user->adds =Advertisement::where('user_id', $user->id)->count();
            $user->opendeals = AdvertiseDeal::where('gateway_id', 505)->where('status','!=', 1)->where('status','!=', 21)->where('status','!=', 2)->where(function ($query) use ($user) {
                $query->where('to_user_id', $user->id);
                $query->orWhere('from_user_id', $user->id);
            })->count();
            $user->completedeals = AdvertiseDeal::where(function($query) use ($user){
                return $query
                ->where('to_user_id', $user->id)
                ->orWhere('from_user_id', $user->id);
                
            })->
            where(function($query){
                return $query
                ->where('status', 1);
                
            })->count();
            $user->disputedeals = AdvertiseDeal::where(function($query) use ($user){
                return $query
                ->where('to_user_id', $user->id)
                ->orWhere('from_user_id', $user->id);
                
            })->
            where(function($query){
                return $query
                ->where('status', 10);
                
            })->count();
            $ee =UserCryptoBalance::where('user_id', $user->id)->first();
            if(!empty($ee)){
                $user->blnce =$ee->balance;
            }else{
                $user->blnce = 0;
            }
            
        }
        // return $users;
        
        return view('admin.users.users', compact('page_title', 'users'));
    }

    public function activeUsers()
    {
        
        $query = User::with(['country', 'cryptoBalances', 'cryptoAddvertises'])->latest();
        $page_title = "All User Manage";

        $page_title = "Active User";
        $query->where('status', 1);
        
        $users = $query->paginate(20);
        foreach($users as $user){
            $user->adds =Advertisement::where('user_id', $user->id)->count();
            $user->opendeals = AdvertiseDeal::where('gateway_id', 505)->where('status','!=', 1)->where('status','!=', 21)->where('status','!=', 2)->where(function ($query) use ($user) {
                $query->where('to_user_id', $user->id);
                $query->orWhere('from_user_id', $user->id);
            })->count();
            $user->completedeals = AdvertiseDeal::where(function($query) use ($user){
                return $query
                ->where('to_user_id', $user->id)
                ->orWhere('from_user_id', $user->id);
                
            })->
            where(function($query){
                return $query
                ->where('status', 1);
                
            })->count();
            $user->disputedeals = AdvertiseDeal::where(function($query) use ($user){
                return $query
                ->where('to_user_id', $user->id)
                ->orWhere('from_user_id', $user->id);
                
            })->
            where(function($query){
                return $query
                ->where('status', 10);
                
            })->count();
            $ee =UserCryptoBalance::where('user_id', $user->id)->first();
            if(!empty($ee)){
                $user->blnce =$ee->balance;
            }else{
                $user->blnce = 0;
            }
            
        }
        // return $users;
        
        return view('admin.users.users', compact('page_title', 'users'));
    }

    public function inactiveUsers()
    {
        
        $query = User::with(['country', 'cryptoBalances', 'cryptoAddvertises'])->latest();
        $page_title = "All User Manage";

        $page_title = "In-Active User";
        $query->where('status', 0);
        
        $users = $query->paginate(20);
        foreach($users as $user){
            $user->adds =Advertisement::where('user_id', $user->id)->count();
            $user->opendeals = AdvertiseDeal::where('gateway_id', 505)->where('status','!=', 1)->where('status','!=', 21)->where('status','!=', 2)->where(function ($query) use ($user) {
                $query->where('to_user_id', $user->id);
                $query->orWhere('from_user_id', $user->id);
            })->count();
            $user->completedeals = AdvertiseDeal::where(function($query) use ($user){
                return $query
                ->where('to_user_id', $user->id)
                ->orWhere('from_user_id', $user->id);
                
            })->
            where(function($query){
                return $query
                ->where('status', 1);
                
            })->count();
            $user->disputedeals = AdvertiseDeal::where(function($query) use ($user){
                return $query
                ->where('to_user_id', $user->id)
                ->orWhere('from_user_id', $user->id);
                
            })->
            where(function($query){
                return $query
                ->where('status', 10);
                
            })->count();
            $ee =UserCryptoBalance::where('user_id', $user->id)->first();
            if(!empty($ee)){
                $user->blnce =$ee->balance;
            }else{
                $user->blnce = 0;
            }
            
        }
        // return $users;
        
        return view('admin.users.users', compact('page_title', 'users'));
    }

    public function userSearch(Request $request)
    {
        $this->validate($request, [
            'search' => 'required',
        ]);
        $data['users'] = User::where('username', 'like', '%' . $request->search . '%')->orWhere('email', 'like', '%' . $request->search . '%')->orWhere('name', 'like', '%' . $request->search . '%')->get();
        $data['page_title'] = "Search User";
        return view('admin.users.search', $data);
    }

    public function userSearchGet(Request $request)
    {
        $this->validate($request, [
            'search' => 'required',
        ]);
        $data['users'] = User::where('username', 'like', '%' . $request->search . '%')->orWhere('email', 'like', '%' . $request->search . '%')->orWhere('name', 'like', '%' . $request->search . '%')->get();
        $data['page_title'] = "Search User";
        return view('admin.users.search', $data);
    }

    public function balanceNullify($user_id){
        $basic = GeneralSettings::first();
        $amount = 0;
        $user = User::find($user_id);

        $balance = UserCryptoBalance::where('user_id',$user_id)->first();
        $old_balance = $balance->balance;
        $amount = $old_balance;
        $balance->balance -= abs($amount);
        $balance->balance =number_format((float)$balance->balance, 8, '.', '');
        $balance->save();

        Trx::create([
            'user_id' =>  $user_id,
            'pre_main_amo' => number_format((float)$old_balance, 8, '.', '').' BTC',
            'amount' => number_format((float) abs($amount) , 8, '.', '').' BTC',
            'main_amo' => number_format((float)$balance->balance  , 8, '.', '').' BTC',
            'charge' => number_format((float)0, 8, '.', '').' BTC',
            'type' => '-',
            'title' => 'Subtracted By Admin',
            'trx' => 'Adjustment' . 'BTC' . time(),
            'deal_url' =>'/user/adjustments'
        ]);

        $txt = abs($amount) . ' ' . $basic->currency . ' debited from your account.' .'<br>';
        notify($user, abs($amount) . ' ' . $basic->currency .' debited from your account', $txt);
    
        $user->transactions()->create([
            'txid' => '',
            'status' => 'subtract',
            'amount' => number_format((float)   abs($amount) , 8, '.', ''),
            'main_amo' =>number_format((float)  $balance->balance , 8, '.', ''),
            'address' => $balance->address,
            'type' => Transaction::TYPE_MANUAL,
        ]);

        $ee= 'nullify';
        $msg ='Money '.$ee.' Successful!';


        return back()->with('success',$msg );
    }
    
    public function singleUser($user)
    {
        $user = User::where('username', $user)->first();
        $data['page_title'] = "User Manage";
        $data['user'] = $user;
        $data['balance'] = UserCryptoBalance::where('user_id', $user->id)->get();
        $data['addvertise'] = Advertisement::where('user_id', $user->id)->latest()->paginate(5);
        $from_open_deals = AdvertiseDeal::where('from_user_id', $user->id)->where('status', 0)->get();
        $to_open_deals = AdvertiseDeal::where('to_user_id', $user->id)->where('status', 0)->get();
        $data['open_deals'] = collect()->merge($from_open_deals, $to_open_deals)->sortByDesc('created_at');
        $data['last_login'] = UserLogin::whereUser_id($user->id)->orderBy('id', 'desc')->first();
        $data['trxes'] = $user->trxes->count();
        $data['countries'] = Country::all();
        $data['cities'] = Cities::all();
        $data['reviews']= Rating::where('to_user', $user->id)->get();
        $data['completed_depo']=Transaction::where('user_id', $user->id)->where('type','deposit')->where('status','complete')->count();

        return view('admin.users.single', $data);
    }

    public function showBalanceHistory($user)
    {
        $user = User::where('username', $user)->first();
        $user = $user->load([
            // 'transactions',
            'trxes',
            // 'withdrawRequests',
        ]);
        $data = collect()->merge($user->trxes);
        // $res = array();
        foreach($data as &$d){
            $open = false;
            if (strpos($d->deal_url, 'deal') !== false) {
                $trans_id = abs((int) filter_var($d->deal_url, FILTER_SANITIZE_NUMBER_INT));
                $deal = AdvertiseDeal::where('trans_id', $trans_id)->first();
                $deal->status == 0 ? $open = true : $open = false;
            }
            $d->open = $open;
            // $res[] = $d;
        }
            // ->merge($user->transactions()->historyBalance()->get())
            // ->merge($user->withdrawRequests()->where('status', '!=', WithdrawRequest::STATUS_REJECTED)->get())
        

        return view('admin.users.balance-history', [
            'page_title' => 'Balance History',
            'datas' => $data->sortByDesc('created_at')
        ]);
    }

    public function showAccessHistory($user)
    {
        $user = User::where('username', $user)->first();
        
        $data = UserLogin::where('user_id', $user->id)->orderBy('id', 'DESC')->paginate(30);
        return view('admin.users.access-history', [
            'page_title' => 'Access History',
            'datas' => $data->sortByDesc('created_at')
        ]);
    }

    public function userPasschange(Request $request, $id)
    {
        $user = User::find($id);
        $this->validate(
            $request,
            [
                'password' => 'required|string|min:5|confirmed'
            ]
        );
        if ($request->password == $request->password_confirmation) {
            $user->password = bcrypt($request->password);
            $user->save();
            $msg = 'Password Changed By Admin. New Password is: ' . $request->password;
         try{   
            send_email($user->email, $user->username, 'Password Changed', $msg);
        }catch(\Exception $e){

        }
            $notification = array('message' => 'Password Changed', 'alert-type' => 'success');
            return back()->with($notification);
        } else {
            $notification = array('message' => 'Password Not Matched');
            return back()->with($notification);
        }
    }


    public function statusupdate(Request $request, User $user)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            // 'email' => 'required|string|max:255|unique:users,email,' . $user->id,
            // 'phone' => 'required|string|max:255|unique:users,phone,' . $user->id,
        ]);
        
        $id=$user->id;
        $user1 = User::find($id);

        $id_photo_verified =$request->id_photo_status;
        $id_photo_id_verified =$request->id_photo_id_status;
        $address_photo_verified =$request->address_photo_status;

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
            
            $user['id_photo'] = $filename_watermark;
            $user['document_uploaded']=1;
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

            $user['id_photo_id'] = $filename_watermark;
            $user['document_uploaded']=1;
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

            $user['address_photo'] = $filename_watermark;
            $user['document_uploaded']=1;
            $address_photo_verified =1;
        }

        $user['name'] = $request->name;
        $user['country_id'] = $request->country_id;
        $user['phone'] = $request->phone;
        $user['email'] = $request->email;
        $user['city'] = $request->city;
        $user['id_photo_status'] = $id_photo_verified;
        $user['id_photo_id_status'] = $id_photo_id_verified;
        $user['address_photo_status'] = $address_photo_verified;
        $user['verified'] = $request->verified;
        $user['zip_code'] = $request->zip_code;
        $user['address'] = $request->address;
        $user['admin_note'] = $request->admin_note;
        $user['auto_verified'] = $request->auto_verified;
        $user['max_send_limit'] = $request->max_send_limit;
        $user['status'] = $request->status == "1" ? 1 : 0;
        $user['email_verify'] = $request->email_verify == "1" ? 1 : 0;
        $user['phone_verify'] = $request->phone_verify == "1" ? 1 : 0;

        // permissions
        $user['permission_withdraw'] = $request->permission_withdraw == "1" ? 1 : 0;
        $user['permission_send'] = $request->permission_send == "1" ? 1 : 0;
        $user['permission_buy'] = $request->permission_buy == "1" ? 1 : 0;
        $user['permission_sell'] = $request->permission_sell == "1" ? 1 : 0;
        if($id_photo_verified ==1 && $id_photo_id_verified==1 && $address_photo_verified ==1 && $request->verified ==1){
            $user['document_uploaded']=0;
        }else{
            $user['document_uploaded']=1;
        }

        if(!$user['permission_buy']){
            Advertisement::where('user_id', $user->id)->where('add_type', 2)->update(['status' => 0]);
        }
        if(!$user['permission_sell']){
            Advertisement::where('user_id', $user->id)->where('add_type', 1)->update(['status' => 0]);
        }
        
        
        
        //rejected emails+
            $admin=Auth::guard('admin')->user();
            $notification=[];
            $notification['from_user'] = $admin->id;
            $notification['to_user'] =$user->id;
            $notification['noti_type'] ='verification';
            $notification['action_id'] =$user->id;
           
            $notification['url'] ='/user'.'/'.$user->username.'/edit-profile';
            
            
            
        if($request->id_photo_status == 2 && $user1->id_photo_status !=2){
            $message1 ="<p>We are unable to verify your ID. Make sure you submit genuine documents that are clear and legible.
             </p><p>Feel free to contact our support team for further assistance.</p>";
            $noti1="We are unable to verify your ID document.";
        }
        if($request->address_photo_status == 2 && $user1->address_photo_status!=2){
            $message2 ="<p>We are unable to verify your proof of address document. Make sure you submit genuine document that are clear and legible.
            </p><p>Feel free to contact our support team for further assistance.</p>";
            $noti2="We are unable to verify your proof of address document.";
            
        }
        if($request->id_photo_id_status == 2 && $user1->id_photo_id_status!=2){
            $message3 ="<p>We are unable to verify your personal image with id. Make sure you submit genuine document that are clear and legible.
            </p><p>Feel free to contact our support team for further assistance.</p>";
            $noti3="We are unable to verify your personal image with ID.";
            
        }
//accepted emails+
        if($request->id_photo_status == 1 && $user1->id_photo_status !=1){
            $message4 ="<p>Congratulations! Your ID document was approved and you’ve successfully completed the ID verification process.</p>";
            $noti4="Congratulation! Your ID document has been approved.";
        }
        if($request->address_photo_status == 1 && $user1->address_photo_status!=1){
            $message5 ="<p>Congratulations! Your proof of address was approved and you’ve successfully completed the address verification process.</p>";
            $noti5="Congratulation! Your proof of address document has been approved.";
        }
        if($request->id_photo_id_status == 1  && $user1->id_photo_id_status!=1){
            $message6 ="<p>Congratulations! Your personal image with ID was approved and you’ve successfully completed the personal image verification process.</p>";
            $noti6="Congratulation! Your personal image with ID has been approved.";
        }
       

        $msg = 'Your Profile Updated by Admin';
        try{
            send_email($user->email, $user->username, 'Profile Updated', $msg);
            if(isset($message1)){
                $notification['message']=$noti1;
                Notification::create($notification);
                send_email($user->email, $user->username, 'Unable to complete ID verification', $message1);

            }
            if(isset($message2)){
                $notification['message']=$noti2;
                Notification::create($notification);
                send_email($user->email, $user->username, 'Unable to complete proof of address verification', $message2);

            }
            if(isset($message3)){
                $notification['message']=$noti3;
                Notification::create($notification);
                send_email($user->email, $user->username, 'Unable to complete personal image verification', $message3);

            }
            if(isset($message4)){
                $notification['message']=$noti4;
                Notification::create($notification);
            send_email($user->email, $user->username, 'Your ID has been verified!', $message4);

            }
            if(isset($message5)){
                $notification['message']=$noti5;
                Notification::create($notification);
            send_email($user->email, $user->username, 'Your address has been verified!', $message5);

            }
            if(isset($message6)){
                $notification['message']=$noti6;
                Notification::create($notification);
            send_email($user->email, $user->username, 'Your image has been verified!', $message6);

            }
            if($request->verified == 1 && $user1->verified!=1){
                $noti7="Congratulation! Your account has been approved.";
                $notification['message']=$noti7;
                    Notification::create($notification);
            }
                
        }catch(\Exception $e){
            return $e;
        }
        $user->save();
        return back()->with('message', 'User Profile Updated Successfuly!');
    }

    public function updateStatus(Request $request, User $user)
    {
        $user['status'] = $request->status == "1" ? 1 : 0;
        $user->save();

        $msg = 'Your Profile Updated by Admin';
    try{
        send_email($user->email, $user->username, 'Profile Updated', $msg);
    }catch(\Exception $e){

    }
        return response()->json([
            'success' => 'User status is successfuly updated',
            'user' => $user
        ]);
    }

    public function userEmail($id)
    {
        $data['user'] = User::findorFail($id);
        $data['page_title'] = "Send  Email To User";
        return view('admin.users.email', $data);
    }

    public function sendemail(SendEmailFormRequest $request)
    { 
        $id = $request->id;
        $to = $request->emailto;
        $name = $request->reciver;
        $subject = $request->subject;
        $message = $request->emailMessage;
    try{
        send_email($to, $name, $subject, $message);

        $a = strtoupper(md5(uniqid(rand(), true)));
        $filename='';

        $ticket = Ticket::create([
           'subject' => $request->subject,
            'ticket' => substr($a, 0, 8),
            'customer_id' => $id,
            'status' => 1,
        ]);

        TicketComment::create([
           'ticket_id' => $ticket->ticket,
           'type' => 0,
           'comment' => $request->emailMessage,
           'issue' => "Email Support Ticket",
           'replyto' => '',
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
        $notification['to_user'] = $id;
        $notification['noti_type'] = 'support';
        $notification['action_id'] = $ticket->id;
        $notification['message']= 'Admin initiated support ticket-'.$ticket->id;
        
        $notification['url'] =$url11;
        
        
        Notification::create($notification);
    }catch(\Exception $e){
        return $e;
    }
        $notification = array('message' => 'Mail Sent Successfuly!', 'alert-type' => 'success');
        return back()->with($notification);
    }

    public function loginLogsByUsers(User $user)
    {
        $logs = UserLogin::where('user_id', $user)->orderBy('id', 'DESC')->paginate(30);
        $page_title = 'Login Information';
        return view('admin.users.login-logs-by-users', compact('logs', 'page_title', 'user'));
    }

    public function ManageBalanceByUsers(User $user,$id)
    {
        // echo $id;
        $user = User::find($id);
        $balance = UserCryptoBalance::where('user_id', $user->id)->get();
    //  foreach($balance as $data){
    //  echo   $data->gateway->currency;
    //  }
    //  return;
        $gateways = Gateway::all();
        $page_title = "ADD / SUBSTRUCT BALANCE";
        
        return view('admin.users.balance-manage', compact('user', 'gateways','page_title', 'balance'));
    }

    public function deleteUser($id){
        $deals = AdvertiseDeal::where('gateway_id', 505)->where(function ($query) use ($id) {
                    $query->where('to_user_id', $id);
                    $query->orWhere('from_user_id', $id);
                })->count();
        
        if($deals > 0){
            $notification = array('message' => 'User has deals!', 'alert-type' => 'warning');
            return back()->with($notification);
        }
        else{
            User::where('id', $id)->delete();
            $notification = array('message' => 'User Deleted Successfuly!', 'alert-type' => 'success');
            return back()->with($notification);
        }
    }

    public function saveBalanceByUsers(UpdateBalanceFormRequest $request)
    {
        $basic = GeneralSettings::first();

        $user = User::find($request->id);

        $balance = UserCryptoBalance::where('user_id',$request->id)->where('gateway_id',$request->gateway_id)->first();
        $old_balance = $balance->balance;
        if ($request->operation == "on") {
            $balance->balance += abs($request->amount);
            $balance->balance =number_format((float)$balance->balance, 8, '.', '');
            $balance->save();

            Trx::create([
                'user_id' =>  $request->id,
                'pre_main_amo' => number_format((float)$old_balance, 8, '.', '').' BTC',
                'amount' =>number_format((float) abs($request->amount) , 8, '.', '').' BTC',
                'main_amo' =>number_format((float)$balance->balance  , 8, '.', '').' BTC',
                'charge' => number_format((float)0, 8, '.', '').' BTC',
                'type' => '+',
                'title' => 'Added By Admin',
                'trx' => 'Adjustment' . 'BTC' . time(),
                'deal_url' =>'/user/adjustments'
            ]);

            $txt = abs($request->amount) . ' ' . $basic->currency . ' credited to your account.' .'<br> reason : '.  $request->message;
            notify($user, abs($request->amount) . ' ' . $basic->currency .' credited to your account', $txt);
        } else {
            if ($balance->balance > 0 && abs($request->amount) <= $balance->balance) {
                $balance->balance -= abs($request->amount);
                $balance->balance =number_format((float)$balance->balance, 8, '.', '');
                $balance->save();

                Trx::create([
                    'user_id' =>  $request->id,
                    'pre_main_amo' => number_format((float)$old_balance, 8, '.', '').' BTC',
                    'amount' =>number_format((float) abs($request->amount) , 8, '.', '').' BTC',
                    'main_amo' =>number_format((float)$balance->balance  , 8, '.', '').' BTC',
                    'charge' => number_format((float)0, 8, '.', '').' BTC',
                    'type' => '-',
                    'title' => 'Deducted By Admin',
                    'trx' => 'Adjustment' . 'BTC' . time(),
                    'deal_url' =>'/user/adjustments'
                ]);

                $txt = abs($request->amount) . ' ' . $basic->currency . ' debited from your account.' .'<br> reason : '. $request->message;
                notify($user, abs($request->amount) . ' ' . $basic->currency . ' debited from your account', $txt);
            } else {
                return back()->with('alert', 'Insufficent Balance To Substract!');
            }
        }
        $user->transactions()->create([
            'txid' => '',
            'status' => ($request->operation === 'on') ? 'add' : 'substract',
            'amount' => number_format((float)   abs($request->amount) , 8, '.', ''),
            'main_amo' =>number_format((float)  $balance->balance , 8, '.', ''),
            'address' => $balance->address,
            'type' => Transaction::TYPE_MANUAL,
        ]);
        $ee= ($request->operation === 'on') ? 'added' : 'substract';
        $msg ='Money '.$ee.' Successful!';

        return back()->with('success',$msg );
    }


    public function loginLogs($user = 0)
    {
        $user = User::find($user);
        if ($user) {
            $logs = UserLogin::where('user_id', $user->id)->orderBy('id', 'DESC')->paginate(20);
            $page_title = 'Login Logs Of ' . $user->username;
        } else {
            $logs = UserLogin::orderBy('id', 'DESC')->paginate(20);
            $page_title = 'User Login Logs';
        }
        return view('admin.users.login-logs', compact('logs', 'page_title'));
    }


    public function userTrans(User $user)
    {
        $page_title = "$user->username - All Transaction";
        $deposits = Trx::whereUser_id($id)->latest()->paginate(10);

        return view('admin.users.user-trans', compact('deposits', 'page_title'));
    }
    public function userDeposit(User $user)
    {
        $page_title = "$user->username - All Deposit";
        $deposits = Deposit::whereUser_id($user)->whereStatus(1)->paginate(30);
        return view('admin.users.user-trans', compact('deposits', 'page_title'));
    }

    public function viewTerms()
    {
        $page_title = "Terms & Policy";
        return view('admin.webcontrol.terms_policy', compact('page_title'));
    }

    public function updateTerms(Request $request)
    {
        GeneralSettings::whereId(1)->update([
            'policy' => $request->policy,
            'terms' => $request->terms,
        ]);
        return back()->with('success', 'Successfully Completed!');
    }
    public function EditReview($id){
        $review = Rating::find($id);
        $page_title = "Update Review";
        return view('admin.edit-review', compact('page_title','review'));

    }
    public function SaveReview(Request $request){
        $review = Rating::find($request->id);
        $review->rating=$request->rating;
        $review->remarks=$request->remarks;
        $id= $review->to_user;
        $review->save();
        return redirect("/adminio/user/$id")->with('success', 'Successfully Updated!');

    }
    public function DeleteReview($id){
        $review = Rating::find($id);
        $review->delete();
        return back()->with('success', 'Deleted Successfully!');
    }
}
