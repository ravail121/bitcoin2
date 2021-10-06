<?php

namespace App\Services;

use Denpa\Bitcoin\Client as BitcoinClient;

class Bitcoind
{

    private $bitcoind;

    public function __construct()
    {
        $this->bitcoind = new BitcoinClient(config('bitcoind.default'));
    }

    public function generateAddress()
    {
        try {
            $response = $this->bitcoind->getnewaddress("");
            if (!empty($response) && is_null($response->error())) {
                return [
                    'error' => 'ok',
                    'result' => [
                        'address' => $response->result(),
                    ],
                ];
            }

            return ['error' => 'Failed to generate address.'];
        }
        catch (\Exception $exception) {
            return [
                'error' => $exception->getMessage(),
            ];
        }
    }

    public function getTxnInfoByTxnId($txid)
    {
        try {
            $response = $this->bitcoind->gettransaction($txid);

            if (!empty($response) && is_null($response->error())) {
                $result = $response->result();
                return [
                    'error' => 'ok',
                    'result' => [
                        'confirmations' => $result['confirmations'],
                        'ipn_type' => $result['details'][0]['category'] == 'send' ? 'withdraw' : 'deposit',
                        'address' => $result['details'][0]['address'],
                        'txn_id' => $result['txid'],
                        'id' => $result['txid'],
                        'amount' => $result['details'][0]['category'] == 'send' ? bcmul($result['amount'], "-1") : $result['amount'],
                        'fee' => $result['details'][0]['category'] == 'send' ? bcmul($result['fee'], "-1") : 0,
                        'vout' => $result['details'][0]['vout'],
                    ],
                ];
            }

            return [
                'error' => 'No transaction found.',
            ];
        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage(),
            ];
        }
    }

    public function getTxnList($limit = 25)
    {
        //
    }

    public function sendToAddress($address, $amount)
    {
        $estimatedFee = $this->getEstimatedFee();
        $spendableAmount = $amount - $estimatedFee;
        if($this->getBalance() > $spendableAmount) {
            logs()->info('start withdrawal');
            logs()->info('logs: amount: ' . $amount);
            logs()->info('logs: spendable amount: ' . $amount);
            logs()->info('logs: fee: ' . $estimatedFee);
            logs()->info('logs: server balance: ' . $this->getBalance());
            logs()->info('end withdrawal');

            return ['error' => 'Insufficient balance to send.'];
        }
        $response = $this->bitcoind->sendtoaddress($address, $amount);

        if (!empty($response) && is_null($response->error())) {
            return [
                'error' => 'ok',
                'result' => [
                    'txn_id' => $response->result()
                ],
            ];
        }

        return ['error' => 'Failed to send.'];
    }

    public function getEstimatedFee($block = 6)
    {
        try {
            $response = $this->bitcoind->estimatesmartfee($block);
            if (!empty($response) && is_null($response->error())) {
                return $response->result()['feerate'];
            }

            return false;
        } catch (\Exception $exception) {
            return false;
        }
    }

    public function getBalance()
    {
        try {
            $response = $this->bitcoind->getbalance();
            if(!empty($response) && is_null($response->error())) {
                return $response->result();
            }

            return 0;
        } catch (\Exception $exception) {
            return 0;
        }
    }

    public function validateIPN($post_data, $server_data)
    {
        //
    }
}
