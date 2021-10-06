<?php

namespace App\Console\Commands;
use App\Models\AdvertiseDeal;
use App\Models\Advertisement;
use App\Models\GeneralSettings;
use App\Models\UserCryptoBalance;
use App\Models\Notification;
use App\Models\User;
use App\Models\Admin;
use App\Models\Currency;
use App\Models\BitCoinPrice;
use Illuminate\Console\Command;

class addPrices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'add:prices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        // $curl = curl_init();

        // curl_setopt_array($curl, array(
        //   CURLOPT_URL => "https://apiv2.bitcoinaverage.com/constants/exchangerates/global",
        //   CURLOPT_RETURNTRANSFER => true,
        //   CURLOPT_ENCODING => "",
        //   CURLOPT_MAXREDIRS => 10,
        //   CURLOPT_TIMEOUT => 30,
        //   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //   CURLOPT_CUSTOMREQUEST => "GET",
        //   CURLOPT_HTTPHEADER => array(
        //     "cache-control: no-cache",
        //     "postman-token: b749b3d8-43ae-1e74-f13c-2ff95536085c",
        //     "x-ba-key: MGI2NWRmZDg1NjhhNGQyNThkYjRmZTFlODNhYTU2NTI"
        //   ),
        // ));
        
        // $response = curl_exec($curl);
        // $err = curl_error($curl);
        
        // curl_close($curl);
        
        // if ($err) {
        //   echo "cURL Error #:" . $err;
        // } else {
        //   $response = json_decode($response);
        //   $response = $response->rates;
        //   foreach($response as $key => $value){
        //       $rate = number_format((float)round($value->rate, 8), 8, '.', '');
        //       Currency::where('name', $key)->update(['usd_rate' => $rate]);
        //   }
        // }

        if(date("i")%2 == 0){

            $curl = curl_init();

            curl_setopt_array($curl, array(
            CURLOPT_URL => "https://apiv2.bitcoinaverage.com/indices/global/ticker/short?crypto=BTC&fiat=SSP,USD,EUR,GBP,NOK,SEK,DKK,AED,AFN,ALL,AMD,ANG,AOA,ARS,AUD,AWG,AZN,BAM,BBD,BDT,BGN,BHD,BIF,BMD,BND,BOB,BRL,BSD,BTN,BWP,BYN,BYR,BZD,CAD,CDF,CHF,CLF,CLP,CNY,COP,CRC,CUC,CUP,CVE,CZK,DJF,DOP,DZD,EGP,ERN,ETB,FJD,FKP,GEL,GGP,GHS,GIP,GMD,GNF,GTQ,GYD,HKD,HNL,HRK,HTG,HUF,IDR,ILS,IMP,INR,IQD,IRR,ISK,JEP,JMD,JOD,JPY,KES,KGS,KHR,KMF,KPW,KRW,KWD,KYD,KZT,LAK,LBP,LKR,LRD,LSL,LTL,LVL,LYD,MAD,MDL,MGA,MKD,MMK,MNT,MOP,MRO,MUR,MVR,MWK,MXN,MYR,MZN,NAD,NGN,NIO,NPR,NZD,OMR,PAB,PEN,PGK,PHP,PKR,PLN,PYG,QAR,RON,RSD,RUB,RWF,SAR,SBD,SCR,SDG,SGD,SHP,SLL,SOS,SRD,STD,SVC,SYP,SZL,THB,TJS,TMT,TND,TOP,TRY,TTD,TWD,TZS,UAH,UGX,UYU,UZS,VEF,VND,VUV,WST,XAF,XAG,XAU,XCD,XDR,XOF,XPF,YER,ZAR,ZMK,ZMW,ZWL",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
                "postman-token: b749b3d8-43ae-1e74-f13c-2ff95536085c",
                "x-ba-key: MGI2NWRmZDg1NjhhNGQyNThkYjRmZTFlODNhYTU2NTI"
            ),
            ));
            
            $response = curl_exec($curl);
            $err = curl_error($curl);
            
            curl_close($curl);
            
            if ($err) {
            echo "cURL Error #:" . $err;
            } else {
                if($response != "Developer plan can't access endpoint apiv2.bitcoinaverage.com/indices/global/ticker/all?crypto=BTC."){
                    $response = json_decode($response);
                    foreach($response as $key => $value){
                        Currency::where('name', substr($key, 3))->update(['btc_rate' => $value->ask]);
                    }
                }
                else{
                    echo "no";
                }
            }
        }

        // $current_usd = file_get_contents("https://api.coinstats.app/public/v1/coins/bitcoin");
        // $ticker = json_decode($current_usd, true);
        // $btc_usd = $ticker['coin']['price'];
        $general = GeneralSettings::first();
        $basic_factor = $general->btc_price_factor;
        $btc_usd = 0; // $btc_usd + ($basic_factor / 100 * $btc_usd);
        // replacing $curr->usd_rate * $btc_usd with $curr->btc_rate
        // echo "current price usd".$btc_usd."<br>";
        // BitCoinPrice::create(['btc_price' => $btc_usd]);
        Advertisement::where('status', 1)
        ->chunkById(100, function ($adds) use($btc_usd, $general) {
            foreach ($adds as $add) {
                echo "previous".$add->price."<br>";
                $curr = Currency::find($add->currency_id);
                // $user = User::find($add->user_id);
                // if($add->margin == 0){
                //     $margin =1;
                // }else{
                //     $margin =$add->margin;
                // }
                $margin =$add->margin;
                $afterMargin = ($curr->btc_rate * $margin) / 100;
                if($add->add_type == 1){
                    $price = ($curr->btc_rate) + $afterMargin;
                }else{                    
                    $price = ($curr->btc_rate) - $afterMargin;
                }

                $price = round($price, 2);
                if($add->auto_max == 1){
                    $balance = UserCryptoBalance::where('user_id', $add->user_id)->first();
                    $max_amount = number_format((float)($balance->balance - $general->sell_advertiser_fixed_fee)/(1+(($general->sell_advertiser_percentage_fee)/100)), 8, '.', '');
                    $max_amount = $max_amount < 0 ? 0 : (float)$max_amount;

                    $max_amount *= $price;
                    $max_amount = round($max_amount);
                    echo "updated".$price."<br>";
                    echo "updated".$max_amount."<br>";
                    Advertisement::where('id', $add->id)->update(['price' => $price, "max_amount" => $max_amount]);
                }
                else{
                    echo "updated".$price."<br>";
                    Advertisement::where('id', $add->id)->update(['price' => $price]);
                }
            }
        });
    }
}
