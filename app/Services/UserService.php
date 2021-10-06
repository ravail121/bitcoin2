<?php

namespace App\Services;

// use Log;
use App\Models\Gateway;
use App\Models\User;
use App\Models\WalletAddresses;

class UserService
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
     * Check user has a wallet address
     *
     * @param App\Models\User $user
     *
     * @return boolean
     */
    public function hasWalletAddress(User $user)
    {
        return $user->cryptoBalances()->where('gateway_id', 505)->exists();
    }

    /**
     * Create a new wallet address resource.
     *
     * @param App\Models\User $user
     *
     * @return mixed
     */
    public function createWalletAddress(User $user)
    {
        $result = WalletAddresses::where('status',0)->first();

        if (!empty($result) ) {
            $gateway = Gateway::first();

            $user->cryptoBalances()->create([
                'gateway_id' => $gateway->id,
                'balance' => '0.00000000',
                'address' => $result->addresses,
            ]);
            $result->status=1;
            $result->save();
        }
    }

    /**
     * Generate new wallet address
     *
     * @return boolean
     */
    public function generateAddress()
    {
        $response = $this->bitcoind->generateAddress();

        if (!empty($response) && $response['error'] === 'ok') {
            // Log::info(print_r($response, true));
            return $response['result'];
        } else {
            // Log::error(print_r($response, true));
            return [];
        }
    }
}
