<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use GuzzleHttp\Client;
use App\Models\Currency;

class UpdateFiatRates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fiats:update';


    /**
     * cryptocompare url
     *
     * @var string
     */
    protected $url;

    /**
     * @var mixed
     */
    protected $client;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->url = 'http://data.fixer.io/api';

        $this->client = new Client([
            'headers' => [
                'Accepts' => 'application/json',
            ],
        ]);
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
         $request = $this->client->get($this->url . '/latest', [
              'query' => [
                  'access_key' => 'ca096961ac62a0e4c986eb03babca151',
                  'format' => '1'
              ],
         ]);

         $result = json_decode($request->getBody()->getContents());

         $currencies = [];

         $usd = $result->rates->USD;

         foreach ($result->rates as $sym => $value) {
           $currency = Currency::where(['name' => $sym])->first();
           if (!$currency) {
               $currency = new Currency();
               $currency->name = $sym;
               $currency->status = 1;
           }
           $currency->usd_rate = $value / $usd;
           $currency->save();
         }
    }
}


