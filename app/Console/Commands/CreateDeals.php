<?php

namespace App\Console\Commands;
use App\Models\AdvertiseDeal;
use App\Models\Rating;
use App\Models\UserLogin;
use App\Models\Advertisement;
use App\Models\GeneralSettings;
use App\Models\UserCryptoBalance;
use App\Models\Notification;
use App\Models\User;
use App\Models\Admin;
use App\Models\DealConvertion;
use App\Models\FakeFeedbacks;
use App\Models\Trx;

use Illuminate\Console\Command;

class CreateDeals extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:deals';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'it automaticaly deals between markting users';

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

        $country_id = mt_rand(1,246);
        $marketing_users = User::where('country_id', $country_id)->where('status', 1)->where('email', 'like', '%@tbe.email')->where('address', 'Testaddonebtc')->get();
        $no_of_users = sizeof($marketing_users);

        //checking users total
        if($no_of_users >= 2){
            $temp = array();
            foreach($marketing_users as $mu){
                $temp[] = $mu;
            }
            $marketing_users = $temp;
            if(shuffle($marketing_users) && shuffle($marketing_users) && shuffle($marketing_users)){
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

            // rating

            $data=[];
            $data['from_user']= $actual_user->id;
            $data['to_user']= $advertise->user_id;
            $data['remarks']= FakeFeedbacks::where('id',rand(1,500))->first()->feedback;
            $data['rating']= 2;
            $data['add_type']= $deal->add_type;
            $data['deal_id']= $deal->trans_id;
            $data['advertisement_id']= $deal->advertisement_id;
            Rating::create($data);
            $deal->reviewed=1;
            $deal->save();
            $user= User::findOrFail($advertise->user_id);
            if($user->rating < 100  ){
                $records=Rating::where('to_user',$advertise->user_id);
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
            elseif(2  < 0 && $user->rating == 100){
                $records=Rating::where('to_user',$advertise->user_id);
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

            // rating end

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

            // rating

            $data=[];
            $data['from_user']= $actual_user->id;
            $data['to_user']= $advertise->user_id;
            $data['remarks']= FakeFeedbacks::where('id',rand(1,500))->first()->feedback;
            $data['rating']= 2;
            $data['add_type']= $deal->add_type;
            $data['deal_id']= $deal->trans_id;
            $data['advertisement_id']= $deal->advertisement_id;
            Rating::create($data);
            $deal->reviewed=1;
            $deal->save();
            $user= User::findOrFail($advertise->user_id);
            if($user->rating < 100  ){
                $records=Rating::where('to_user',$advertise->user_id);
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
            elseif(2  < 0 && $user->rating == 100){
                $records=Rating::where('to_user',$advertise->user_id);
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

            // rating end

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
}
