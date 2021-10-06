<?php

namespace App\Http\Controllers;

use App\Models\UserCryptoBalance;
use App\Models\Transaction;
use App\Models\Trx;
use App\Models\User;
use App\Models\Notification;
use App\Models\SEOKeywords;
use App\Models\BitCoinPrice;
use App\Models\Currency;
use App\Models\FakeFeedbacks;
use App\Models\GeneralSettings;
use App\Models\Advertisement;
use App\Models\AdvertiseDeal;
use App\Models\UserLogin;
use App\Models\Country;
use App\Models\WalletAddresses;
use Log;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
Use Illuminate\Support\Facades\Storage;

class IpnController extends Controller
{


    /**
     * Handle webhook events
     *
     * @param Illuminate\Http\Request $request
     *
     * @return
     */
    public function index(Request $request)
    {
        $general_settings = GeneralSettings::first();
        // $txId = $request->get('txn_id');
        $data1 =$request->all();
        // Log::info(print_r($request->all(), true));
        $txId =$data1['txid'];
        $address =$data1['detail']['address'];
        $blockhash= isset($data1['blockhash'])?$data1['blockhash']:'';
        $confirmations= $data1['confirmations'];
        $amount =$data1['detail']['amount'];
        
        $transaction = Transaction::where('txid', $txId)->first();
        $userCryptoBalance = UserCryptoBalance::query()
            ->whereAddress($address)
            ->first();
        if(empty($userCryptoBalance))  {
            return response()->json([
                'message' => 'user address not found on system',
            ]);
        }  
        if (empty($transaction)) {
            

            if (!$userCryptoBalance) {
                abort(404, 'User crypto balance by address not found');
            }

            $transaction = new Transaction;
            $data['txid'] = $txId;
            $data['address'] = $address;
            $data['user_id'] = $userCryptoBalance->user->id;
            $data['main_amo'] = $userCryptoBalance->balance;
            $data['confirmations'] = $confirmations;
            $data['amount'] = $amount;
            $data['blockhash']=$blockhash;
            
        }
        if($confirmations == 0){
            $data['status'] ='pending';

            $subject ='Bitcoins are on the way to your wallet.+'.$amount.' BTC - '.$confirmations.'/1 confirmations';
            $message ='<p>Once the network has 1 confirmations your bitcoin will be available for use in your wallet. confirmations may take anywhere from 20 minutes to 90 minutes. We will notify you via email once bitcoins will be available in your wallet.</p>';
            
            send_email($userCryptoBalance->user->email, $userCryptoBalance->user->username, $subject, $message);
            $notification=[];
            $sbjct='Bitcoins are on the way to your wallet.+'.$amount.' BTC';

            $notification['from_user'] = 1;
            $notification['to_user'] =$userCryptoBalance->user->id;
            $notification['noti_type'] ='deposit';
            $notification['action_id'] =$transaction->id;
            $notification['message']= $sbjct;
            $notification['url'] ='/user'.'/'.$userCryptoBalance->user->username.'/deposits';
            
            
            Notification::create($notification);
        }
        if($confirmations == 1){
            $charge = round((float)$general_settings->deposit_external_fixed_fee  + ($general_settings->deposit_external_percentage_fee / 100 * $amount), 8);
        
            $old_balance = $userCryptoBalance->balance;
            $userCryptoBalance->balance += $amount;
            if($charge <= $userCryptoBalance->balance) $userCryptoBalance->balance -= $charge;
            else $charge = 0;

            $data['status'] ='complete';
            $data['fee'] = number_format((float)$charge  , 8, '.', '');

            $userCryptoBalance->balance=number_format((float)$userCryptoBalance->balance , 8, '.', '');
            $userCryptoBalance->save();

            try{
                Trx::create([
                    'user_id' => $userCryptoBalance->user->id,
                    'pre_main_amo' => number_format((float) $old_balance, 8, '.', '').' BTC',
                    'amount' =>number_format((float) $amount , 8, '.', '').' BTC',
                    'main_amo' =>number_format((float)$userCryptoBalance->balance  , 8, '.', '').' BTC',
                    'charge' => number_format((float)$charge  , 8, '.', '').' BTC',
                    'type' => '+',
                    'title' => 'Deposit ' . 'BTC' . ' Completed',
                    'trx' => 'Deposit' . 'BTC' . time(),
                    'deal_url' =>'/user'.'/'.$userCryptoBalance->user->username.'/deposits'
                ]);

                
                $subject ='Your bitcoins are here!  +'.$amount.' BTC added to your Bitcoin.ngo wallet.';
                $message ='<p>Congratulations! The Bitcoin network has cleared your transaction and '.$amount.' BTC is now available in your the bitcoin exchange wallet.</p><p>Thank you for trading on the bitcoin exchange and we look forward to seeing you again.
                </p>';

                send_email($userCryptoBalance->user->email, $userCryptoBalance->user->username, $subject, $message);

                $notification=[];
                $sbjct='Your bitcoins are here!  +'.$amount.' BTC added to your wallet.';

                $notification['from_user'] = 1;
                $notification['to_user'] =$userCryptoBalance->user->id;
                $notification['noti_type'] ='deposit';
                $notification['action_id'] =$transaction->id;
                $notification['message']= $sbjct;
                $notification['url'] ='/user'.'/'.$userCryptoBalance->user->username.'/deposits';
                
                
                Notification::create($notification);


            }catch(\Exception $e){

            }
            
            
           
        }
        if(isset($data)){
            $transaction->fill($data);
            $transaction->save();
        }
        
         

        return response()->json([
            'message' => true,
        ]);
    }
    public function block(Request $request){
        return response()->json([
            'message' => true,
        ]);
        $data1 =$request->all();
        $hash =$data1['hash'];
        $transaction = Transaction::where('blockhash', $hash)->first();
        if (!empty($transaction)) {
            $userCryptoBalance = UserCryptoBalance::query()
            ->whereAddress($transaction->address)
            ->first();
            if(empty($userCryptoBalance))  {
                return response()->json([
                    'message' => 'user address not found on system',
                ]);
            }  
            $data['status'] ='complete';
            $data['confirmations'] = 3;
            $userCryptoBalance->balance += $transaction->amount;
            $userCryptoBalance->balance=number_format((float)$userCryptoBalance->balance , 8, '.', '');
            $userCryptoBalance->save();

            Trx::create([
                'user_id' => $userCryptoBalance->user->id,
                'amount' =>number_format((float) $transaction->amount , 8, '.', '').' BTC',
                'main_amo' =>number_format((float)$userCryptoBalance->balance , 8, '.', '').' BTC',
                'charge' => 0,
                'type' => '+',
                'title' => 'Deposit ' . 'BTC' . ' Completed',
                'trx' => 'Deposit' . 'BTC' . time(),
                'deal_url' =>'/user'.'/'.$userCryptoBalance->user->username.'/deposits'
            ]);
            $transaction->fill($data);
            $transaction->save();
            $subject ='Your bitcoins are here!  +'.$transaction->amount.' BTC added to your Bitcoin.ngo wallet.';
            $message ='<p>Congratulations! The Bitcoin network has cleared your transaction and '.$transaction->amount.' BTC is now available in your the bitcoin exchange wallet.</p><p>Thank you for trading on the bitcoin exchange and we look forward to seeing you again.
            </p>';

            send_email($userCryptoBalance->user->email, $userCryptoBalance->user->username, $subject, $message);

            return response()->json([
                'amount' => $transaction->amount,
            ]);
        }
        return response()->json([
            'message' => true,
        ]);


    }

    public function getDashboardStats(){
        $general_settings = \App\Models\GeneralSettings::first();
        // event(new \App\Events\DashboardCountersEvent(array(
        //     'channel' => 'admin_dashboard_stats',
        //     'event' => 'updates_sindhu',
        //     'message' => array(
        //         'id' => "general_settings",
        //         'value' => $general_settings
        //     )
        // )));

        $open_deals = \App\Models\AdvertiseDeal::where('status', 0)->orWhere('status', 9)->count();
        event(new \App\Events\DashboardCountersEvent(array(
            'channel' => 'admin_dashboard_stats',
            'event' => 'updates_sindhu',
            'message' => array(
                'id' => "open_deals",
                'value' => $open_deals
            )
        )));

        $deals_under_dispute = \App\Models\AdvertiseDeal::where('status', 10)->count();
        event(new \App\Events\DashboardCountersEvent(array(
            'channel' => 'admin_dashboard_stats',
            'event' => 'updates_sindhu',
            'message' => array(
                'id' => "deals_under_dispute",
                'value' => $deals_under_dispute
            )
        )));

        $deals_on_hold = \App\Models\AdvertiseDeal::where('status', 11)->count();
        event(new \App\Events\DashboardCountersEvent(array(
            'channel' => 'admin_dashboard_stats',
            'event' => 'updates_sindhu',
            'message' => array(
                'id' => "deals_on_hold",
                'value' => $deals_on_hold
            )
        )));

        $open_support_tickets = \App\Models\Ticket::where('status', 1)->orWhere('status',2)->orWhere('status',3)->count();
        event(new \App\Events\DashboardCountersEvent(array(
            'channel' => 'admin_dashboard_stats',
            'event' => 'updates_sindhu',
            'message' => array(
                'id' => "open_support_tickets",
                'value' => $open_support_tickets
            )
        )));

        $unread_support_tickets = \App\Models\Ticket::where('status', 1)->orWhere('status',3)->count();
        event(new \App\Events\DashboardCountersEvent(array(
            'channel' => 'admin_dashboard_stats',
            'event' => 'updates_sindhu',
            'message' => array(
                'id' => "unread_support_tickets",
                'value' => $unread_support_tickets
            )
        )));

        $auto_verified_users = \App\Models\User::where('auto_verified', 1)->count();
        event(new \App\Events\DashboardCountersEvent(array(
            'channel' => 'admin_dashboard_stats',
            'event' => 'updates_sindhu',
            'message' => array(
                'id' => "auto_verified_users",
                'value' => $auto_verified_users
            )
        )));

        $p_unverified_users = \App\Models\User::where('phone_verify', 0)->count();
        event(new \App\Events\DashboardCountersEvent(array(
            'channel' => 'admin_dashboard_stats',
            'event' => 'updates_sindhu',
            'message' => array(
                'id' => "p_unverified_users",
                'value' => $p_unverified_users
            )
        )));

        $e_unverified_users = \App\Models\User::where('email_verify', 0)->count();
        event(new \App\Events\DashboardCountersEvent(array(
            'channel' => 'admin_dashboard_stats',
            'event' => 'updates_sindhu',
            'message' => array(
                'id' => "e_unverified_users",
                'value' => $e_unverified_users
            )
        )));

        $pending_withdrawals = \App\Models\WithdrawRequest::where('status', 'pending')->count();
        event(new \App\Events\DashboardCountersEvent(array(
            'channel' => 'admin_dashboard_stats',
            'event' => 'updates_sindhu',
            'message' => array(
                'id' => "pending_withdrawals",
                'value' => $pending_withdrawals
            )
        )));

        $pending_sends = \App\Models\InternalTransactions::where('status', 'pending')->count();
        event(new \App\Events\DashboardCountersEvent(array(
            'channel' => 'admin_dashboard_stats',
            'event' => 'updates_sindhu',
            'message' => array(
                'id' => "pending_sends",
                'value' => $pending_sends
            )
        )));

        $completed_deals = \App\Models\AdvertiseDeal::where('status', 1)->count();
        event(new \App\Events\DashboardCountersEvent(array(
            'channel' => 'admin_dashboard_stats',
            'event' => 'updates_sindhu',
            'message' => array(
                'id' => "completed_deals",
                'value' => $completed_deals
            )
        )));

        $total_methods = \App\Models\PaymentMethod::where('status', 1)->count();
        event(new \App\Events\DashboardCountersEvent(array(
            'channel' => 'admin_dashboard_stats',
            'event' => 'updates_sindhu',
            'message' => array(
                'id' => "total_methods",
                'value' => $total_methods
            )
        )));

        $total_currency = \App\Models\Currency::where('status', 1)->count();
        event(new \App\Events\DashboardCountersEvent(array(
            'channel' => 'admin_dashboard_stats',
            'event' => 'updates_sindhu',
            'message' => array(
                'id' => "total_currency",
                'value' => $total_currency
            )
        )));

        $total_active_ads = \App\Models\Advertisement::where('status', 1)->count();
        event(new \App\Events\DashboardCountersEvent(array(
            'channel' => 'admin_dashboard_stats',
            'event' => 'updates_sindhu',
            'message' => array(
                'id' => "total_active_ads",
                'value' => $total_active_ads
            )
        )));

        $total_inactive_ads = \App\Models\Advertisement::where('status', 0)->count();
        event(new \App\Events\DashboardCountersEvent(array(
            'channel' => 'admin_dashboard_stats',
            'event' => 'updates_sindhu',
            'message' => array(
                'id' => "total_inactive_ads",
                'value' => $total_inactive_ads
            )
        )));

        $total_users = \App\Models\User::count();
        event(new \App\Events\DashboardCountersEvent(array(
            'channel' => 'admin_dashboard_stats',
            'event' => 'updates_sindhu',
            'message' => array(
                'id' => "total_users",
                'value' => $total_users
            )
        )));

        $total_marketing_users = \App\Models\User::where('email', 'like', '%@tbe.email')->where('address', 'Testaddonebtc')->count();
        event(new \App\Events\DashboardCountersEvent(array(
            'channel' => 'admin_dashboard_stats',
            'event' => 'updates_sindhu',
            'message' => array(
                'id' => "total_marketing_users",
                'value' => $total_marketing_users
            )
        )));

        $total_real_users = \App\Models\User::where('email', 'not like', '%@tbe.email')->orWhere('address', '!=', 'Testaddonebtc')->orWhere('address', null)->count();
        event(new \App\Events\DashboardCountersEvent(array(
            'channel' => 'admin_dashboard_stats',
            'event' => 'updates_sindhu',
            'message' => array(
                'id' => "total_real_users",
                'value' => $total_real_users
            )
        )));

        $real_users_balance = round(\App\Models\User::where('users.email', 'not like', '%@tbe.email')->orWhere('users.address', '!=', 'Testaddonebtc')->orWhere('users.address', null)->rightJoin('user_crypto_balances', 'users.id', '=', 'user_crypto_balances.user_id')->sum('balance'), 8);
        event(new \App\Events\DashboardCountersEvent(array(
            'channel' => 'admin_dashboard_stats',
            'event' => 'updates_sindhu',
            'message' => array(
                'id' => "real_users_balance",
                'value' => $real_users_balance
            )
        )));

        $total_active_users_online = \App\Models\UserLogin::where('created_at', '>', Carbon::now()->subMinutes($general_settings->dashboard_refresh_time))->where('created_at', '<=', Carbon::now())->where('user_id', '!=', 1)->distinct('user_id')->count();
        event(new \App\Events\DashboardCountersEvent(array(
            'channel' => 'admin_dashboard_stats',
            'event' => 'updates_sindhu',
            'message' => array(
                'id' => "total_active_users_online",
                'value' => $total_active_users_online
            )
        )));

        $active_users = \App\Models\User::where('status', 1)->count();
        event(new \App\Events\DashboardCountersEvent(array(
            'channel' => 'admin_dashboard_stats',
            'event' => 'updates_sindhu',
            'message' => array(
                'id' => "active_users",
                'value' => $active_users
            )
        )));

        $users_pending_verifying = \App\Models\User::where('verified', 0)->where('document_uploaded', 1)->count();
        event(new \App\Events\DashboardCountersEvent(array(
            'channel' => 'admin_dashboard_stats',
            'event' => 'updates_sindhu',
            'message' => array(
                'id' => "users_pending_verifying",
                'value' => $users_pending_verifying
            )
        )));

        $deactivated_users = \App\Models\User::where('status', 0)->count();
        event(new \App\Events\DashboardCountersEvent(array(
            'channel' => 'admin_dashboard_stats',
            'event' => 'updates_sindhu',
            'message' => array(
                'id' => "deactivated_users",
                'value' => $deactivated_users
            )
        )));

        $document_unverified_users = \App\Models\User::where('verified', 0)->where('document_uploaded', 1)->count();
        event(new \App\Events\DashboardCountersEvent(array(
            'channel' => 'admin_dashboard_stats',
            'event' => 'updates_sindhu',
            'message' => array(
                'id' => "document_unverified_users",
                'value' => $document_unverified_users
            )
        )));

        $cancelled_deals = \App\Models\AdvertiseDeal::where('status', 2)->count();
        event(new \App\Events\DashboardCountersEvent(array(
            'channel' => 'admin_dashboard_stats',
            'event' => 'updates_sindhu',
            'message' => array(
                'id' => "cancelled_deals",
                'value' => $cancelled_deals
            )
        )));

        $expired_deals = \App\Models\AdvertiseDeal::where('status', 21)->count();
        event(new \App\Events\DashboardCountersEvent(array(
            'channel' => 'admin_dashboard_stats',
            'event' => 'updates_sindhu',
            'message' => array(
                'id' => "expired_deals",
                'value' => $expired_deals
            )
        )));

        $new_signups = \App\Models\User::where('created_at', '>', Carbon::now()->subDay())->where('created_at', '<=', Carbon::now())->count();
        event(new \App\Events\DashboardCountersEvent(array(
            'channel' => 'admin_dashboard_stats',
            'event' => 'updates_sindhu',
            'message' => array(
                'id' => "24_hrs_new_signups",
                'value' => $new_signups
            )
        )));

        $new_deals = \App\Models\AdvertiseDeal::where('created_at', '>', Carbon::now()->subDay())->where('created_at', '<=', Carbon::now())->count();
        event(new \App\Events\DashboardCountersEvent(array(
            'channel' => 'admin_dashboard_stats',
            'event' => 'updates_sindhu',
            'message' => array(
                'id' => "24_hrs_number_of_deals",
                'value' => $new_deals
            )
        )));

        $new_ads = \App\Models\Advertisement::where('created_at', '>', Carbon::now()->subDay())->where('created_at', '<=', Carbon::now())->count();
        event(new \App\Events\DashboardCountersEvent(array(
            'channel' => 'admin_dashboard_stats',
            'event' => 'updates_sindhu',
            'message' => array(
                'id' => "24_hrs_number_of_new_ads",
                'value' => $new_ads
            )
        )));

        $all_time_deposits = \App\Models\Transaction::where('type', 'deposit')->where('status', 'complete')->sum('amount');
        event(new \App\Events\DashboardCountersEvent(array(
            'channel' => 'admin_dashboard_stats',
            'event' => 'updates_sindhu',
            'message' => array(
                'id' => "all_time_deposits",
                'value' => $all_time_deposits
            )
        )));

        $all_time_withdrawals = \App\Models\WithdrawRequest::where('status', 'completed')->sum('amount');
        event(new \App\Events\DashboardCountersEvent(array(
            'channel' => 'admin_dashboard_stats',
            'event' => 'updates_sindhu',
            'message' => array(
                'id' => "all_time_withdrawals",
                'value' => $all_time_withdrawals
            )
        )));

        $trade_volume_data = \App\Models\Trx::where('created_at', '>', Carbon::now()->subDay())->where('created_at', '<=', Carbon::now())->get();
        $trade_volume = 0;
        foreach($trade_volume_data as $t){
            $trade_volume += explode(' ', $t->amount)[0];
        }
        $trade_volume = round($trade_volume, 8);
        event(new \App\Events\DashboardCountersEvent(array(
            'channel' => 'admin_dashboard_stats',
            'event' => 'updates_sindhu',
            'message' => array(
                'id' => "24_hrs_trade_volume",
                'value' => $trade_volume
            )
        )));

        $total_system_balance = round(\App\Models\UserCryptoBalance::sum('balance'), 8);
        event(new \App\Events\DashboardCountersEvent(array(
            'channel' => 'admin_dashboard_stats',
            'event' => 'updates_sindhu',
            'message' => array(
                'id' => "total_system_balance",
                'value' => $total_system_balance
            )
        )));

        $total_users = \App\Models\User::count();
        event(new \App\Events\DashboardCountersEvent(array(
            'channel' => 'admin_dashboard_stats',
            'event' => 'updates_sindhu',
            'message' => array(
                'id' => "total_users",
                'value' => $total_users
            )
        )));


        event(new \App\Events\DashboardCountersEvent(array(
            'channel' => 'admin_dashboard_stats',
            'event' => 'updates_sindhu',
            'message' => array(
                'id' => "auto_verification",
                'value' => $general_settings->auto_verification == 1 ? "On" : "Off"
            )
        )));

        
        event(new \App\Events\DashboardCountersEvent(array(
            'channel' => 'admin_dashboard_stats',
            'event' => 'updates_sindhu',
            'message' => array(
                'id' => "base_btc_price_factor",
                'value' => $general_settings->btc_price_factor
            )
        )));

        $marketing_users_balance = round(\App\Models\User::where('users.email', 'like', '%@tbe.email')->where('users.address', 'Testaddonebtc')->rightJoin('user_crypto_balances', 'users.id', '=', 'user_crypto_balances.user_id')->sum('balance'), 8);
        event(new \App\Events\DashboardCountersEvent(array(
            'channel' => 'admin_dashboard_stats',
            'event' => 'updates_sindhu',
            'message' => array(
                'id' => "marketing_users_balance",
                'value' => $marketing_users_balance
            )
        )));

        $all_time_total_commission = 0;
        Trx::chunkById(10000, function ($trxs) use(&$all_time_total_commission){
            foreach ($trxs as $trx) {
                $all_time_total_commission += explode(' ', $trx->charge)[0];
            }
        });
        $all_time_total_commission = round($all_time_total_commission, 8);
        event(new \App\Events\DashboardCountersEvent(array(
            'channel' => 'admin_dashboard_stats',
            'event' => 'updates_sindhu',
            'message' => array(
                'id' => "all_time_total_commission",
                'value' => $all_time_total_commission
            )
        )));

        // $general_settings = \App\Models\GeneralSettings::first();
        // switch ($request->q) {
        //     case 'general_settings':
        //         $general_settings = \App\Models\GeneralSettings::first();
        //         echo json_encode(array("status" => true, "value" => $general_settings));
        //         break;
        //     case 'open_deals':
        //         $open_deals = \App\Models\AdvertiseDeal::where('status', 0)->orWhere('status', 9)->count();
        //         echo json_encode(array("status" => true, "value" => $open_deals));
        //         break;
        //     case 'deals_under_dispute':
        //         $deals_under_dispute = \App\Models\AdvertiseDeal::where('status', 10)->count();
        //         echo json_encode(array("status" => true, "value" => $deals_under_dispute));
        //         break;
        //     case 'deals_on_hold':
        //         $deals_on_hold = \App\Models\AdvertiseDeal::where('status', 11)->count();
        //         echo json_encode(array("status" => true, "value" => $deals_on_hold));
        //         break;
        //     case 'open_support_tickets':
        //         $open_support_tickets = \App\Models\Ticket::where('status', 1)->orWhere('status',2)->orWhere('status',3)->count();
        //         echo json_encode(array("status" => true, "value" => $open_support_tickets));
        //         break;
        //     case 'unread_support_tickets':
        //         $check_count = \App\Models\Ticket::where('status', 1)->orWhere('status',3)->count();
        //         echo json_encode(array("status" => true, "value" => $check_count));
        //         break;
        //     case 'auto_verified_users':
        //         $auto_verified_users = \App\Models\User::where('auto_verified', 1)->count();
        //         echo json_encode(array("status" => true, "value" => $auto_verified_users));
        //         break;
        //     case 'p_unverified_users':
        //         $p_unverified_users = \App\Models\User::where('phone_verify', 0)->count();
        //         echo json_encode(array("status" => true, "value" => $p_unverified_users));
        //         break;
        //     case 'e_unverified_users':
        //         $e_unverified_users = \App\Models\User::where('email_verify', 0)->count();
        //         echo json_encode(array("status" => true, "value" => $e_unverified_users));
        //         break;
        //     case 'pending_withdrawals':
        //         $pending_withdrawals = \App\Models\WithdrawRequest::where('status', 'pending')->count();
        //         echo json_encode(array("status" => true, "value" => $pending_withdrawals));
        //         break;
        //     case 'pending_sends':
        //         $pending_sends = \App\Models\InternalTransactions::where('status', 'pending')->count();
        //         echo json_encode(array("status" => true, "value" => $pending_sends));
        //         break;
        //     case 'completed_deals':
        //         $completed_deals = \App\Models\AdvertiseDeal::where('status', 1)->count();
        //         echo json_encode(array("status" => true, "value" => $completed_deals));
        //         break;
        //     case 'total_methods':
        //         $total_methods = \App\Models\PaymentMethod::where('status', 1)->count();
        //         echo json_encode(array("status" => true, "value" => $total_methods));
        //         break;
        //     case 'total_currency':
        //         $total_currency = \App\Models\Currency::where('status', 1)->count();
        //         echo json_encode(array("status" => true, "value" => $total_currency));
        //         break;
        //     case 'total_active_ads':
        //         $total_active_ads = \App\Models\Advertisement::where('status', 1)->count();
        //         echo json_encode(array("status" => true, "value" => $total_active_ads));
        //         break;
        //     case 'total_inactive_ads':
        //         $total_inactive_ads = \App\Models\Advertisement::where('status', 0)->count();
        //         echo json_encode(array("status" => true, "value" => $total_inactive_ads));
        //         break;
        //     case 'total_users':
        //         $total_users = \App\Models\User::count();
        //         echo json_encode(array("status" => true, "value" => $total_users));
        //         break;
        //     case 'total_marketing_users':
        //         $total_marketing_users = \App\Models\User::where('email', 'like', '%@tbe.email')->where('address', 'Testaddonebtc')->count();
        //         echo json_encode(array("status" => true, "value" => $total_marketing_users));
        //         break;
        //     case 'total_real_users':
        //         $total_real_users = \App\Models\User::where('email', 'not like', '%@tbe.email')->orWhere('address', '!=', 'Testaddonebtc')->orWhere('address', null)->count();
        //         echo json_encode(array("status" => true, "value" => $total_real_users));
        //         break;
        //     case 'real_users_balance':
        //         $real_users_balance = round(\App\Models\User::where('users.email', 'not like', '%@tbe.email')->orWhere('users.address', '!=', 'Testaddonebtc')->orWhere('users.address', null)->rightJoin('user_crypto_balances', 'users.id', '=', 'user_crypto_balances.user_id')->sum('balance'), 8);
        //         echo json_encode(array("status" => true, "value" => $real_users_balance));
        //         break;
        //     case 'total_active_users_online':
        //         $total_active_users_online = \App\Models\UserLogin::where('created_at', '>', Carbon::now()->subMinutes($general_settings->dashboard_refresh_time))->where('created_at', '<=', Carbon::now())->where('user_id', '!=', 1)->distinct('user_id')->count();
        //         echo json_encode(array("status" => true, "value" => $total_active_users_online));
        //         break;
        //     case 'active_users':
        //         $active_users = \App\Models\User::where('status', 1)->count();
        //         echo json_encode(array("status" => true, "value" => $active_users));
        //         break;
        //     case 'users_pending_verifying':
        //         $users_pending_verifying = \App\Models\User::where('verified', 0)->where('document_uploaded', 1)->count();
        //         echo json_encode(array("status" => true, "value" => $users_pending_verifying));
        //         break;
        //     case 'deactivated_users':
        //         $deactivated_users = \App\Models\User::where('status', 0)->count();
        //         echo json_encode(array("status" => true, "value" => $deactivated_users));
        //         break;
        //     case 'document_unverified_users':
        //         $document_unverified_users = \App\Models\User::where('verified', 0)->where('document_uploaded', 1)->count();
        //         echo json_encode(array("status" => true, "value" => $document_unverified_users));
        //         break;
        //     case 'cancelled_deals':
        //         $cancelled_deals = \App\Models\AdvertiseDeal::where('status', 2)->count();
        //         echo json_encode(array("status" => true, "value" => $cancelled_deals));
        //         break;
        //     case 'expired_deals':
        //         $expired_deals = \App\Models\AdvertiseDeal::where('status', 21)->count();
        //         echo json_encode(array("status" => true, "value" => $expired_deals));
        //         break;
        //     case '24_hrs_new_signups':
        //         $new_signups = \App\Models\User::where('created_at', '>', Carbon::now()->subDay())->where('created_at', '<=', Carbon::now())->count();
        //         echo json_encode(array("status" => true, "value" => $new_signups));
        //         break;
        //     case '24_hrs_number_of_deals':
        //         $new_deals = \App\Models\AdvertiseDeal::where('created_at', '>', Carbon::now()->subDay())->where('created_at', '<=', Carbon::now())->count();
        //         echo json_encode(array("status" => true, "value" => $new_deals));
        //         break;
        //     case '24_hrs_number_of_new_ads':
        //         $new_ads = \App\Models\Advertisement::where('created_at', '>', Carbon::now()->subDay())->where('created_at', '<=', Carbon::now())->count();
        //         echo json_encode(array("status" => true, "value" => $new_ads));
        //         break;
        //     case 'all_time_deposits':
        //         $total_deposit = \App\Models\Transaction::where('type', 'deposit')->where('status', 'complete')->sum('amount');
        //         // $total_deposit = \App\Models\Transaction::deposits()->where('status', 'complete')->sum('amount');
        //         echo json_encode(array("status" => true, "value" => $total_deposit));
        //         break;
        //     case 'all_time_withdrawals':
        //         $all_time_withdrawals = \App\Models\WithdrawRequest::where('status', 'completed')->sum('amount');
        //         echo json_encode(array("status" => true, "value" => $all_time_withdrawals));
        //         break;
        //     case 'all_time_total_commission':
        //         $total_commission_data = \App\Models\Trx::get();
        //         $all_time_total_commission = 0;
        //         foreach($total_commission_data as $t){
        //             $all_time_total_commission += explode(' ', $t->charge)[0];
        //         }
        //         $all_time_total_commission = round($all_time_total_commission, 8);
        //         echo json_encode(array("status" => true, "value" => $all_time_total_commission));
        //         break;
        //     case '24_hrs_trade_volume':
        //         $trade_volume_data = \App\Models\Trx::where('created_at', '>', Carbon::now()->subDay())->where('created_at', '<=', Carbon::now())->get();
        //         $trade_volume = 0;
        //         foreach($trade_volume_data as $t){
        //             $trade_volume += explode(' ', $t->amount)[0];
        //         }
        //         $trade_volume = round($trade_volume, 8);
        //         echo json_encode(array("status" => true, "value" => $trade_volume));
        //         break;
        //     case 'total_system_balance':
        //         $total_system_balance = round(\App\Models\UserCryptoBalance::sum('balance'), 8);
        //         echo json_encode(array("status" => true, "value" => $total_system_balance));
        //         break;
        //     case 'auto_verification':
        //         echo json_encode(array("status" => true, "value" => $general_settings->auto_verification == 1 ? "On" : "Off"));
        //         break;
        //     case 'base_btc_price_factor':
        //         echo json_encode(array("status" => true, "value" =>$general_settings->btc_price_factor));
        //         break;
        //     case 'marketing_users_balance':
        //         $marketing_users_balance = round(\App\Models\User::where('users.email', 'like', '%@tbe.email')->where('users.address', 'Testaddonebtc')->rightJoin('user_crypto_balances', 'users.id', '=', 'user_crypto_balances.user_id')->sum('balance'), 8);
        //         echo json_encode(array("status" => true, "value" => $marketing_users_balance));
        //         break;
        
        //     default:
        //         echo json_encode(array("status" => false, "value" => 'Something Went Wrong'));
        //         break;
        // }
        // $general_settings = \App\Models\GeneralSettings::first();
        // $open_deals = \App\Models\AdvertiseDeal::where('status', 0)->orWhere('status', 9)->count();
        // $deals_under_dispute = \App\Models\AdvertiseDeal::where('status', 10)->count();
        // $deals_on_hold = \App\Models\AdvertiseDeal::where('status', 11)->count();
        // $open_support_tickets = \App\Models\Ticket::where('status', 1)->orWhere('status',2)->orWhere('status',3)->count();
        // $auto_verified_users = \App\Models\User::where('auto_verified', 1)->count();
        // $p_unverified_users = \App\Models\User::where('phone_verify', 0)->count();
        // $e_unverified_users = \App\Models\User::where('email_verify', 0)->count();
        // $pending_withdrawals = \App\Models\WithdrawRequest::where('status', 'pending')->count();
        // $pending_sends = \App\Models\InternalTransactions::where('status', 'pending')->count();
        // $completed_deals = \App\Models\AdvertiseDeal::where('status', 1)->count();
        // $total_methods = \App\Models\PaymentMethod::where('status', 1)->count();
        // $total_currency = \App\Models\Currency::where('status', 1)->count();
        // $total_active_ads = \App\Models\Advertisement::where('status', 1)->count();
        // $total_inactive_ads = \App\Models\Advertisement::where('status', 0)->count();
        // // $total_countries = \App\Models\Country::count();
        // $total_users = \App\Models\User::count();
        // $total_marketing_users = \App\Models\User::where('email', 'like', '%@tbe.email')->where('address', 'Testaddonebtc')->count();
        // $marketing_users_balance = round(\App\Models\User::where('users.email', 'like', '%@tbe.email')->where('users.address', 'Testaddonebtc')->rightJoin('user_crypto_balances', 'users.id', '=', 'user_crypto_balances.user_id')->sum('balance'), 8);
        // $total_real_users = \App\Models\User::where('email', 'not like', '%@tbe.email')->where('address', '!=', 'Testaddonebtc')->count();
        // $real_users_balance = round(\App\Models\User::where('users.email', 'not like', '%@tbe.email')->where('users.address', '!=', 'Testaddonebtc')->rightJoin('user_crypto_balances', 'users.id', '=', 'user_crypto_balances.user_id')->sum('balance'), 8);
        // $online_users = \App\Models\UserLogin::where('created_at', '>', Carbon::now()->subMinutes($general_settings->dashboard_refresh_time))->where('created_at', '<=', Carbon::now())->where('user_id', '!=', 1)->distinct('user_id')->count();
        // $active_users = \App\Models\User::where('status', 1)->count();
        // $users_pending_verifying = \App\Models\User::where('verified', 0)->where('document_uploaded', 1)->count();
        // $deactivated_users = \App\Models\User::where('status', 0)->count();
        // $document_unverified_users = \App\Models\User::where('verified', 0)->where('document_uploaded', 1)->count();
        // $cancelled_deals = \App\Models\AdvertiseDeal::where('status', 2)->count();
        // $expired_deals = \App\Models\AdvertiseDeal::where('status', 21)->count();
        // $new_signups = \App\Models\User::where('created_at', '>', Carbon::now()->subDay())->where('created_at', '<=', Carbon::now())->count();
        // $new_deals = \App\Models\AdvertiseDeal::where('created_at', '>', Carbon::now()->subDay())->where('created_at', '<=', Carbon::now())->count();
        // $new_ads = \App\Models\Advertisement::where('created_at', '>', Carbon::now()->subDay())->where('created_at', '<=', Carbon::now())->count();
        // $total_deposit = \App\Models\Transaction::deposits()->where('status', 'complete')->sum('amount');
        // $total_withdraws = \App\Models\WithdrawRequest::where('status', 'completed')->sum('amount');
        // $total_commission_data = \App\Models\Trx::get();
        // $trade_volume_data = \App\Models\Trx::where('created_at', '>', Carbon::now()->subDay())->where('created_at', '<=', Carbon::now())->get();
        // $total_system_balance = round(\App\Models\UserCryptoBalance::sum('balance'), 8);
        // $total_commission = 0;
        // $trade_volume = 0;
        // foreach($total_commission_data as $t){
        //     $total_commission += explode(' ', $t->charge)[0];
        // }
        // $total_commission = round($total_commission, 8);
        // foreach($trade_volume_data as $t){
        //     $trade_volume += explode(' ', $t->amount)[0];
        // }
        // $trade_volume = round($trade_volume, 8);

        // // createing response object
        // $response = array(
        //     "open_deals" => $open_deals,
        //     "deals_under_dispute" => $deals_under_dispute,
        //     "deals_on_hold" => $deals_on_hold,
        //     "open_support_tickets" => $open_support_tickets,
        //     "users_pending_verifying" => $users_pending_verifying,
        //     "auto_verified_users" => $auto_verified_users,
        //     "p_unverified_users" => $p_unverified_users,
        //     "e_unverified_users" => $e_unverified_users,
        //     "pending_withdrawals" => $pending_withdrawals,
        //     "pending_sends" => $pending_sends,
        //     "completed_deals" => $completed_deals,
        //     "total_methods" => $total_methods,
        //     "total_currency" => $total_currency,
        //     "total_active_users_online" => $online_users,
        //     "expired_deals" => $expired_deals,
        //     "total_active_ads" => $total_active_ads,
        //     // "total_countries" => $total_countries,
        //     // "commission" => $general_settings->trx_charge,
        //     "cancelled_deals" => $cancelled_deals,
        //     "total_inactive_ads" => $total_inactive_ads,
        //     "auto_verification" => $general_settings->auto_verification == 1 ? "On" : "Off",
        //     "base_btc_price_factor" => $general_settings->btc_price_factor,
        //     "total_users" => $total_users,
        //     "total_marketing_users" => $total_marketing_users,
        //     "marketing_users_balance" => $marketing_users_balance,
        //     "total_real_users" => $total_real_users,
        //     "real_users_balance" => $real_users_balance,
        //     "active_users" => $active_users,
        //     "deactivated_users" => $deactivated_users,
        //     "document_unverified_users" => $document_unverified_users,
        //     "24_hrs_trade_volume" => $trade_volume,
        //     "24_hrs_new_signups" => $new_signups,
        //     "24_hrs_number_of_deals" => $new_deals,
        //     "24_hrs_number_of_new_ads" => $new_ads,
        //     "all_time_deposits" => $total_deposit,
        //     "all_time_withdrawals" => $total_withdraws,
        //     "total_system_balance" => $total_system_balance,
        //     "all_time_total_commission" => round($total_commission, 8)
        // );
        // echo json_encode($response);

        echo "ok";
    }

    public function isWalletAdressExitsInSystem(Request $request){
        if(isset($request->address)){
            $res = \App\Models\UserCryptoBalance::where('address', $request->address)->first();
            if(isset($res->user_id)) echo json_encode(array("error" => false, "isExist" => true));
            else echo json_encode(array("error" => false, "isExist" => false));
        }
        else{
            echo json_encode(array("error" => true, "isExist" => false));
        }
    }

    public function calculateRelativeAmount(Request $request){
        try{
            if($request->from == 'BTC'){
                $rate = Currency::where('name', $request->to)->first()->btc_rate;
                // $btc_value = BitCoinPrice::latest()->first()->btc_price;

                echo json_encode(array(
                    "error" => false,
                    "relative_amount" => number_format((float)$rate * $request->amount, 8, '.', '')
                ));
            }
            else{
                $rate = Currency::where('name', $request->from)->first()->btc_rate;
                // $btc_value = BitCoinPrice::latest()->first()->btc_price;

                echo json_encode(array(
                    "error" => false,
                    "relative_amount" => number_format((float)$request->amount / $rate , 8, '.', '')
                ));
            }
        }
        catch(Exception $e){
            echo json_encode(array(
                "error" => true,
                "relative_amount" => $e->getMessage()
            ));
        }
    }

    // public function addWaterMark(){
    //     // Load the stamp and the photo to apply the watermark to
    //     $stamp = imagecreatefrompng(asset('images/user-ic.png'));
    //     $im = imagecreatefrompng(asset('images/user-default (copy).png'));
    //     // $im = imagecreatefromjpeg(asset('images/user-default.png'));

    //     // Set the margins for the stamp and get the height/width of the stamp image
    //     $marge_right = 10;
    //     $marge_bottom = 10;
    //     $sx = imagesx($stamp);
    //     $sy = imagesy($stamp);

    //     // Copy the stamp image onto our photo using the margin offsets and the photo 
    //     // width to calculate positioning of the stamp. 
    //     imagecopy($im, $stamp, imagesx($im) - $sx - $marge_right, imagesy($im) - $sy - $marge_bottom, 0, 0, imagesx($stamp), imagesy($stamp));

    //     // Output and free memory
    //     header('Content-type: image/png');
    //     imagepng($im);
    //     imagedestroy($im);
    // }

    public function trimUserLogin($start, $end){
        $total_commission_data = \App\Models\Trx::get();
        $total_commission = 0;
        foreach($total_commission_data as $t){
            // $total_commission += explode(' ', $t->charge)[0];
            // preg_match("!\d*.?\d+!", $t->charge, $m);
            echo floatval($t->charge)."<br>";
        }
        $total_commission = round($total_commission, 8);
        // echo $total_commission;
        exit;
        echo "$start - $end <br />";
        $users = User::where('status', 1)->where('id', '>', $start)->where('id', '<=', $end)->get();
        foreach ($users as $u){
            $count = UserLogin::where('user_id', $u->id)->latest()->count();
            $logins = UserLogin::where('user_id', $u->id)->latest()->take($count)->skip(10)->get();

            // $logins = UserLogin::where('user_id', $u->id)->latest()->get();
            echo "starting for $u->id - $u->name <br/>";
            foreach($logins as $l){
                UserLogin::where('id', $l->id)->delete();
            }
            echo "Done <br/>";
        }
    }

    public function addWaterMark(){
        $all_time_total_commission = 0;
        Trx::chunkById(10000, function ($trxs) use(&$all_time_total_commission){
            foreach ($trxs as $trx) {
                $all_time_total_commission += explode(' ', $trx->charge)[0];
            }
        });
        $all_time_total_commission = round($all_time_total_commission, 8);
        event(new \App\Events\DashboardCountersEvent(array(
            'channel' => 'admin_dashboard_stats',
            'event' => 'updates_sindhu',
            'message' => array(
                'id' => "all_time_total_commission",
                'value' => $all_time_total_commission
            )
        )));
        echo $all_time_total_commission;
        // $curl = curl_init();

        // curl_setopt_array($curl, array(
        //     CURLOPT_URL => "http:/64.227.86.53:8332/wallet/bitcoinexchangewallet",
        //     CURLOPT_RETURNTRANSFER => true,
        //     CURLOPT_ENCODING => "",
        //     CURLOPT_MAXREDIRS => 10,
        //     CURLOPT_TIMEOUT => 0,
        //     CURLOPT_FOLLOWLOCATION => true,
        //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //     CURLOPT_CUSTOMREQUEST => "POST",
        //     CURLOPT_POSTFIELDS => "{\"jsonrpc\":\"1.0\",\"id\":\"curltext\",\"method\":\"walletpassphrase\",\"params\":[\"BitcoinTBE@2020\",600]}",
        //     CURLOPT_HTTPHEADER => array(
        //         "content-type: text/plain;",
        //         "Authorization: Basic Yml0Y29pbjE6Q3dqNThBTUE5M1praA==",
        //     ),
        // ));

        // $response = curl_exec($curl);
        // dd($response);exit;
        // if (curl_errno($curl)) {
        //     $error_msg = curl_error($curl);
        // }

        // $curl = curl_init();

        // curl_setopt_array($curl, array(
        //     CURLOPT_URL => "http:/64.227.86.53:8332/wallet/bitcoinexchangewallet",
        //     CURLOPT_RETURNTRANSFER => true,
        //     CURLOPT_ENCODING => "",
        //     CURLOPT_MAXREDIRS => 10,
        //     CURLOPT_TIMEOUT => 0,
        //     // CURLOPT_FOLLOWLOCATION => true,
        //     // CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //     CURLOPT_CUSTOMREQUEST => "POST",
        //     CURLOPT_POSTFIELDS => "{\"jsonrpc\":\"1.0\",\"id\":\"curltext\",\"method\":\"sendtoaddress\",\"params\":[\"3EV8zGWH2x9w4pCsbkMEnMNiScf75x4zt1\",\"0.04163087\",\"\",\"\",true]}",
        //     CURLOPT_HTTPHEADER => array(
        //         "content-type: text/plain;",
        //         "Authorization: Basic Yml0Y29pbjE6Q3dqNThBTUE5M1praA==",
        //     ),
        // ));

        // $response = curl_exec($curl);
        // dd($response); 
        // if (curl_errno($curl)) {
        //     $error_msg = curl_error($curl);
        // }

        // curl_close($curl);

    //     $curl = curl_init();

    //     curl_setopt_array($curl, array(
    //         CURLOPT_URL => "http:/64.227.86.53:8332/wallet/bitcoinexchangewallet",
    //         // CURLOPT_URL => "http:/64.227.86.53:8332/wallet/",
    //         CURLOPT_RETURNTRANSFER => true,
    //         CURLOPT_ENCODING => "",
    //         CURLOPT_MAXREDIRS => 10,
    //         CURLOPT_TIMEOUT => 0,
    //         CURLOPT_CUSTOMREQUEST => "POST",
    //         CURLOPT_POSTFIELDS => "{\"jsonrpc\":\"1.0\",\"id\":\"curltext\",\"method\":\"getwalletinfo\",\"params\":[]}",
    //         CURLOPT_HTTPHEADER => array(
    //             "content-type: text/plain;",
    //             "Authorization: Basic Yml0Y29pbjE6Q3dqNThBTUE5M1praA==",
    //         ),
    //     ));

    //     $response = curl_exec($curl);

    //     if (curl_errno($curl)) {
    //         $error_msg = curl_error($curl);
    //     }

    //     curl_close($curl);
    // dd($response); 
    //     $response = json_decode($response);
    // return $result = $response->result;

    }
}