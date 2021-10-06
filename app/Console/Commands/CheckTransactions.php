<?php

namespace App\Console\Commands;

// use Log;
use App\Models\Transaction;
use App\Services\Bitcoind;
use Illuminate\Console\Command;

class CheckTransactions extends Command
{
    /**
     * @var App\Services\Bitcoind|null
     */
    private $bitcoind;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transactions:check-pending';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check and update all PENDING transaction, fetch confirmations count from bitcoin node';

    /**
     * Create a new command instance.
     *
     * @param App\Services\Bitcoind $bitcoind
     *
     * @return void
     */
    public function __construct(Bitcoind $bitcoind)
    {
        parent::__construct();

        $this->bitcoind = $bitcoind;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $transactions = Transaction::pending()->get();
        if (!$transactions->count()) {
          return;
        }
        $transactions->each(function ($item, $key) {
            $bitcoindTransaction = $this->bitcoind->getTxnInfoByTxnId($item->txid);
            if (empty($bitcoindTransaction)) {
              // Log::error($bitcoindTransaction['error']);
              return;
            }
            if ($bitcoindTransaction['error'] !== 'ok') {
              // Log::error($bitcoindTransaction['error']);
              return;
            }

            $addressBalance = $item->user->cryptoBalances()
              ->whereAddress($item->address)->first();

            if (!$addressBalance) {
                return;
            }

            $item->main_amo = $addressBalance->balance;
            $item->confirmations = $bitcoindTransaction['result']['confirmations'];
            $item->status = $item->confirmations === 6 ? 'completed' : 'pending';
            $item->save();

            if ($item->confirmations === 6 && $item->type === 'deposit') {
                $addressBalance->balance += $item->amount * 100;
                $addressBalance->save();
            }
        });
    }
}
