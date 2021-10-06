<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\AdvertiseDeal;
use App\Models\Advertisement;
use App\Models\GeneralSettings;
use App\Models\UserCryptoBalance;
use App\Models\Notification;
use App\Models\User;
use App\Models\Admin;
use App\Models\Trx;
use Log;


class dealTimer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'deal:timer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checking deal times if timer is complete and change deal status';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $deals = AdvertiseDeal::where('status', '!=', '1')->where('status', '!=', '2')->where('status', '!=', '21')->where('status', '!=', '10')->where('status', '!=', '11')->get();
        foreach ($deals as $deal) {
            // Log::info($deal);
            $timer = $deal->dispute_timer;
            $current = time();
            //    echo $deal->trans_id."<br>";
            $diff = $current - $timer;
            $minute = $diff / 60;
            if ($minute >= 90) {
                echo "its high";
                if ($deal->status == 9) {
                    // echo "its high";

                    // if ($deal->advertiser_id == $deal->to_user_id) {
                    //     if ($deal->add_type  == 2) {
                    //         $url = "user/deal/$deal->trans_id";
                    //         $url1 = "user/deal-reply/$deal->trans_id";
                    //     } else {
                    //         $url = "user/deal-reply/$deal->trans_id";
                    //         $url1 = "user/deal/$deal->trans_id";
                    //     }
                    //     $ee = $deal->to_user_id;
                    //     $rr = $deal->from_user_id;
                    //     $notification = [];
                    //     $notification['from_user'] = $rr;
                    //     $notification['to_user'] = $ee;
                    //     $notification['noti_type'] = 'deal';
                    //     $notification['action_id'] = $deal->id;
                    //     $notification['message'] = 'System puts the deal on hold, for further information contact with support.';

                    //     $notification['url'] = $url;
                    //     $notification['add_type'] = $deal->add_type;
                    //     $notification['deal_id'] = $deal->id;
                    //     $notification['advertisement_id'] = $deal->advertisement_id;

                    //     Notification::create($notification);

                    //     $notification = [];
                    //     $notification['from_user'] = $ee;
                    //     $notification['to_user'] = $rr;
                    //     $notification['noti_type'] = 'deal';
                    //     $notification['action_id'] = $deal->id;
                    //     $notification['message'] = 'System puts the deal on hold, for further information contact with support.';

                    //     $notification['url'] = $url1;
                    //     $notification['add_type'] = $deal->add_type;
                    //     $notification['deal_id'] = $deal->id;
                    //     $notification['advertisement_id'] = $deal->advertisement_id;

                    //     Notification::create($notification);



                    //     $email_user1 = User::find($ee);
                    //     $email_user2 = User::find($rr);
                    //     $message1 = '<p>Your deal with ' . $email_user2->name . ' has on hold after agreed time as you were not active.</p><p>The support team will review the deal and inform you about the status of the deal as soon as possible.
                    //     </p><p>If you have any questions about anything, feel free to reach out to our support team for assistance.</p><p> The detail of the deal are as follows</p>';
                    //     $message1 .= '<br><b>BTC Rate:</b><br>';
                    //     $message1 .= '<p>' . $deal->price . ' ' . $deal->currency->name . '/' . $deal->gateway->currency . '</p><br>';

                    //     $message1 .= '<br><b>Deal:</b><br><br>';
                    //     $message1 .= '<p><a  href="' . config('app.url') . $url . '"  style="	background-color: #23373f;
                    //     padding: 10px ;
                    //     margin: 10px;
                        
                        
                    
                    //     text-decoration: none;
                    //     color: #ffff;
                    //     font-weight: 600;
                    //     border-radius: 4px;"> Click To See</a></p>';
                    //     $Advertisement = Advertisement::where('id', $deal->advertisement_id)->first();


                    //     $message1 .= '<br><b>Offer:</b><br><br>';
                    //     $mthod = $Advertisement->paymentMethod->name;
                    //     $url11 = "/ad/$Advertisement->id/$mthod";
                    //     $message1 .= '<p><a  href="' . config('app.url') . $url11 . '"  style="	background-color: #23373f;
                    //     padding: 10px;
                    //     margin: 10px;
                    
                    //     text-decoration: none;
                    //     color: #ffff;
                    //     font-weight: 600;
                    //     border-radius: 4px;"> Click To See</a></p>';

                    //     $message11 = '<p>Your deal with ' . $email_user1->name . ' has on Hold after agreed time as you were not active.</p><p>The support team will review the deal and inform you about the status of the deal as soon as possible.
                    //     </p><p>If you have any questions about anything, feel free to reach out to our support team for assistance.</p><p> The detail of the deal are as follows</p>';
                    //     $message11 .= '<br><b>BTC Rate:</b><br>';
                    //     $message11 .= '<p>' . $deal->price . ' ' . $deal->currency->name . '/' . $deal->gateway->currency . '</p><br>';

                    //     $message11 .= '<br><b>Deal:</b><br><br>';
                    //     $message11 .= '<p><a  href="' . config('app.url') . $url1 . '"  style="	background-color: #23373f;
                    //     padding: 10px ;
                    //     margin: 10px;
                        
                        
                    
                    //     text-decoration: none;
                    //     color: #ffff;
                    //     font-weight: 600;
                    //     border-radius: 4px;"> Click To See</a></p>';


                    //     $message11 .= '<b><br>Offer:</b><br><br>';
                    //     $mthod = $Advertisement->paymentMethod->name;
                    //     $url11 = "/ad/$Advertisement->id/$mthod";
                    //     $message11 .= '<p><a  href="' . config('app.url') . $url11 . '"  style="	background-color: #23373f;
                    //     padding: 10px;
                    //     margin: 10px;
                    
                    //     text-decoration: none;
                    //     color: #ffff;
                    //     font-weight: 600;
                    //     border-radius: 4px;"> Click To See</a></p>';
                    //     try {
                    //         send_email($email_user1->email, $email_user1->username, ucfirst('Deal on hold'), $message1);
                    //         send_email($email_user2->email, $email_user2->username, ucfirst('Deal on hold'), $message11);
                    //     } catch (\Exception $e) {
                    //     }
                    // }




                    // $deal->status = 11;
                    // $url = '/adminio/deals/' . $deal->trans_id;
                    // $message = '<p>System automatically put on hold deal ' . $deal->trans_id . ' please review with users</p> <br> <br>
                    // <a  href="' . config('app.url') . $url . '" style="	background-color: #23373f;
                    // padding: 10px ;
                    // margin: 10px;
                    
                    
                    
                    // text-decoration: none;
                    // color: #ffff;
                    // font-weight: 600;
                    // border-radius: 4px;"> Click To See</a>';

                    // $admin = Admin::first();
                    // try {
                    //     send_email($admin->email, $admin->username, ucfirst('Deal on hold'), $message);
                    // } catch (\Exception $e) {
                    // }
                } else {
                    $deal->status = 21;
                    if ($deal->advertiser_id == $deal->to_user_id) {
                        if ($deal->add_type == 2) {
                            $url = "/user/deal/$deal->trans_id";
                            $url1 = "/user/deal-reply/$deal->trans_id";
                        } else {
                            $url = "/user/deal-reply/$deal->trans_id";
                            $url1 = "/user/deal/$deal->trans_id";
                        }
                        $ee = $deal->to_user_id;
                        $rr = $deal->from_user_id;
                        $notification = [];
                        $notification['from_user'] = $rr;
                        $notification['to_user'] = $ee;
                        $notification['noti_type'] = 'deal';
                        $notification['action_id'] = $deal->id;
                        $notification['message'] = 'Deal ' . $deal->trans_id . ' expired ';

                        $notification['url'] = $url;
                        $notification['add_type'] = $deal->add_type;
                        $notification['deal_id'] = $deal->id;
                        $notification['advertisement_id'] = $deal->advertisement_id;

                        Notification::create($notification);

                        $notification = [];
                        $notification['from_user'] = $ee;
                        $notification['to_user'] = $rr;
                        $notification['noti_type'] = 'deal';
                        $notification['action_id'] = $deal->id;
                        $notification['message'] = 'Deal ' . $deal->trans_id . ' expired ';

                        $notification['url'] = $url1;
                        $notification['add_type'] = $deal->add_type;
                        $notification['deal_id'] = $deal->id;
                        $notification['advertisement_id'] = $deal->advertisement_id;

                        Notification::create($notification);
                        $email_user1 = User::find($ee);
                        $email_user2 = User::find($rr);
                        $message1 = '<p>Your deal with ' . $email_user2->name . ' has expired.</p><p> The detail of the deal are as follows</p>';
                        $message1 .= '<br><b>BTC Rate:</b><br>';
                        $message1 .= '<p>' . $deal->price . ' ' . $deal->currency->name . '/' . $deal->gateway->currency . '</p><br>';

                        $message1 .= '<br><b>Deal:</b><br><br>';
                        $message1 .= '<p><a  href="' . config('app.url') . $url . '"  style="	background-color: #23373f;
                    padding: 10px ;
                    margin: 10px;
                    
                    
                   
                    text-decoration: none;
                    color: #ffff;
                    font-weight: 600;
                    border-radius: 4px;"> Click To See</a></p>';
                        $Advertisement = Advertisement::where('id', $deal->advertisement_id)->first();


                        $message1 .= '<br><b>Offer:</b><br><br>';
                        $mthod = $Advertisement->paymentMethod->name;
                        $url11 = "/ad/$Advertisement->id/$mthod";
                        $message1 .= '<p><a  href="' . config('app.url') . $url11 . '"  style="	background-color: #23373f;
                    padding: 10px;
                    margin: 10px;
                
                    text-decoration: none;
                    color: #ffff;
                    font-weight: 600;
                    border-radius: 4px;"> Click To See</a></p>';

                        $message11 = '<p>Your deal with ' . $email_user1->name . ' has expired.</p><p> The detail of the deal are as follows</p>';
                        $message11 .= '<br><b>BTC Rate:</b><br>';
                        $message11 .= '<p>' . $deal->price . ' ' . $deal->currency->name . '/' . $deal->gateway->currency . '</p><br>';

                        $message11 .= '<br><b>Deal:</b><br><br>';
                        $message11 .= '<p><a  href="' . config('app.url') . $url1 . '"  style="	background-color: #23373f;
                    padding: 10px ;
                    margin: 10px;
                    
                    
                   
                    text-decoration: none;
                    color: #ffff;
                    font-weight: 600;
                    border-radius: 4px;"> Click To See</a></p>';


                        $message11 .= '<b><br>Offer:</b><br><br>';
                        $mthod = $Advertisement->paymentMethod->name;
                        $url11 = "/ad/$Advertisement->id/$mthod";
                        $message11 .= '<p><a  href="' . config('app.url') . $url11 . '"  style="	background-color: #23373f;
                    padding: 10px;
                    margin: 10px;
                
                    text-decoration: none;
                    color: #ffff;
                    font-weight: 600;
                    border-radius: 4px;"> Click To See</a></p>';
                        $message11 .= "<br><p>If any bitcoin was moved to escrow, it will be returned to your wallet shortly.</p>";
                        $message1 .= "<br><p>If any bitcoin was moved to escrow, it will be returned to your wallet shortly.</p>";

                        try {
                            send_email($email_user1->email, $email_user1->username, 'Deal expired', $message1);
                            send_email($email_user2->email, $email_user2->username, 'Deal expired', $message11);
                        } catch (\Exception $e) {
                        }
                    }
                    $type = $deal->add_type == 1 ?   'Sell' : 'Buy';

                    $trans = Trx::where("deal_url", "LIKE", '%/'. strtoupper($deal->trans_id))->first();

                    if(!isset($trans->id)) {$deal->update(); continue;}
                    $amount = explode('BTC', $trans->amount)[0];
                    $charge = explode('BTC', $trans->charge)[0];
                    $total = (float)$amount + (float)$charge;
                    $total = number_format((float)$total, 8, '.', '');

                    if ($deal->add_type == 1) {
                        $to_user = User::findOrFail($deal->to_user_id);
                        $to_user_adress = UserCryptoBalance::where('user_id', $to_user->id)
                            ->where('gateway_id', $deal->gateway_id)->first();
                        $old_balance = $to_user_adress->balance;
                        $main_bal = $to_user_adress->balance + (float)$amount + (float)$charge;
                        $to_user_adress->balance = $main_bal;
                        $to_user_adress->save();


                        Trx::create([
                            'user_id' => $to_user->id,
                            'pre_main_amo' => number_format((float)$old_balance, 8, '.', '') . ' ' . $deal->gateway->currency,
                            'amount' => number_format((float)$total, 8, '.', '') . ' ' . $deal->gateway->currency,
                            'main_amo' => number_format((float)$main_bal, 8, '.', '') . ' ' . $deal->gateway->currency,
                            'charge' => number_format((float)0, 8, '.', '') . ' ' . $deal->gateway->currency,
                            'type' => '+',
                            'title' => $type . ' Cancel',
                            'trx' => $type . '' . $deal->gateway->currency . time(),
                            'deal_url' => $url
                        ]);
                    } else {
                        $to_user = User::findOrFail($deal->from_user_id);
                        $to_user_adress = UserCryptoBalance::where('user_id', $to_user->id)
                            ->where('gateway_id', $deal->gateway_id)->first();
                        $old_balance = $to_user_adress->balance;
                        $main_bal = $to_user_adress->balance + (float)$amount + (float)$charge;
                        $to_user_adress->balance = $main_bal;
                        $to_user_adress->save();


                        Trx::create([
                            'user_id' => $to_user->id,
                            'pre_main_amo' => number_format((float)$old_balance, 8, '.', '') . ' ' . $deal->gateway->currency,
                            'amount' => number_format((float)$total, 8, '.', '') . ' ' . $deal->gateway->currency,
                            'main_amo' => number_format((float)$main_bal, 8, '.', '') . ' ' . $deal->gateway->currency,
                            'charge' => number_format((float)0, 8, '.', '') . ' ' . $deal->gateway->currency,
                            'type' => '+',
                            'title' => 'Sell Cancel',
                            'trx' => 'Sell' . $deal->gateway->currency . time(),
                            'deal_url' => $url1
                        ]);
                    }

                    $url = '/adminio/deals/' . $deal->trans_id;
                    $message = '<p>This ' . $deal->trans_id . ' has expired after agreed time as user was not active. The detail of the deal are as follows</p>';
                    $message .= '<br><b>BTC Rate:</b><br>';
                    $message .= '<p>' . $deal->price . ' ' . $deal->currency->name . '/' . $deal->gateway->currency . '</p><br>';

                    $message .= '<br><b>Deal:</b><br><br>';
                    $message .= '<p><a  href="' . config('app.url') . $url . '"  style="	background-color: #23373f;
                    padding: 10px ;
                    margin: 10px;
                    
                    
                   
                    text-decoration: none;
                    color: #ffff;
                    font-weight: 600;
                    border-radius: 4px;"> Click To See</a></p>';
                    $admin = Admin::first();
                    try {
                        send_email($admin->email, $admin->username, 'Deal expired', $message);
                    } catch (\Exception $e) {
                    }
                }
            }

            $deal->update();
        }
    }
}
