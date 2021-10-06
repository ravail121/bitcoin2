<?php

namespace App\Console\Commands;

use App\Models\AdvertiseDeal;
use App\Models\Advertisement;
use App\Models\Gateway;
use App\Models\UserCryptoBalance;
use Illuminate\Console\Command;

class CoinClearCompiled extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'coin:clear-compiled';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove coins from the compiled list';

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
        $this->getCoinsExceptBTC()->each(function ($item) {
            AdvertiseDeal::where('gateway_id', $item->id)->delete();
            Advertisement::where('gateway_id', $item->id)->delete();
            UserCryptoBalance::where('gateway_id', $item->id)->delete();
            $item->deposit()->delete();
            $item->delete();
        });
    }

    /**
     * Get coins list except bitcoin(BTC)
     *
     * @return mixed
     */
    public function getCoinsExceptBTC()
    {
        return Gateway::with('deposit')->whereNotIn('name', ['BitCoin'])->get();
    }
}
