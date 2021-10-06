<?php

namespace App\Http\Controllers;

use App\Models\Deposit;
use App\Models\Trx;
use App\Models\UserCryptoBalance;
use Carbon\Carbon;
use Illuminate\Http\Request;

use Auth;
use App\Models\User;
use App\Models\Gateway;
use App\Models\GeneralSettings;

use Illuminate\Support\Facades\Session;
use Stripe\Stripe;
use Stripe\Token;
use Stripe\Charge;
use App\Lib\coinPayments;
use CoinGate\CoinGate;
use App\Lib\BlockIo;
use App\Lib\CoinPaymentHosted;
use App\Http\Requests\Payment\ConfirmFormRequest;

class PaymentController extends Controller
{
    public function userDataUpdate($data)
    {
        if ($data->status == 0) {
            $data['status'] = 1;
            $data->update();

            $user = User::findOrFail($data->user_id);

            $address = UserCryptoBalance::where('user_id', $data->user_id)
                ->where('gateway_id', $data->gateway_id)->first();

            $new_balance = $address->balance + $data->amount;
            $address->balance = $new_balance;
            $address->save();

            Trx::create([
                'user_id' => $user->id,
                'amount' => $data->amount,
                'main_amo' => $new_balance,
                'charge' => 0,
                'type' => '+',
                'title' => 'Deposit Via' . $data->gateway->name,
                'trx' => $data->trx
            ]);

            $txt = $data->amount . ' ' . $data->gateway->currency .' Deposited Successfully Via '. $data->gateway->name;
            notify($user, 'Deposit Successfully Completed', $txt);
        }
    }

    public function depositConfirm(ConfirmFormRequest $request)
    {
        $gate = Gateway::findOrFail($request->gateway);

        $all = file_get_contents("https://api.coinstats.app/public/v1/coins/bitcoin");
        $ticker = json_decode($all, true);

        $btc_usd = $ticker['coin']['price'];

        $de['user_id'] = Auth::id();
        $de['gateway_id'] = $gate->id;
        $de['amount'] = floatval($request->amount);
        $de['charge'] = 0;
        $de['usd_amo'] = null;
        $de['btc_amo'] = 0;
        $de['status'] = 0;
        $de['trx'] = 'DP-'.rand();
        $data = Deposit::create($de);


        if (is_null($data)) {
            return redirect()->route('deposit', auth()->user()->username)->with('alert', 'Invalid Deposit Request');
        }
        if ($data->status != 0) {
            return redirect()->route('deposit', auth()->user()->username)->with('alert', 'Invalid Deposit Request');
        }


        if ($data->gateway_id == 505) {
            $method = Gateway::find(505);

            if ($data->btc_amo == 0 || $data->btc_wallet=="") {
                $cps = new CoinPaymentHosted();
                $cps->Setup($method->val2, $method->val1);
                $callbackUrl = route('ipn.coinPay.btc');

                $req = array(
                    'amount' => $btc_usd,
                    'currency1' => 'USD',
                    'currency2' => 'BTC',
                    'custom' => $data->trx,
                    'ipn_url' => $callbackUrl,
                );


                $result = $cps->CreateTransaction($req);

                if ($result['error'] == 'ok') {
                    $bcoin = $request->amount;
                    $sendadd = $result['result']['address'];

                    $data['btc_amo'] = $bcoin;
                    $data['btc_wallet'] = $sendadd;
                    $data->update();
                } else {
                    return back()->with('alert', 'Failed to Process');
                }
            }
            $wallet = $data['btc_wallet'];
            $bcoin = $data['btc_amo'];
            $page_title = $method->name;


            $qrurl =  "<img src=\"https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=bitcoin:$wallet&choe=UTF-8\" title='' style='width:300px;' />";
            return view('user.payment.coinpaybtc', compact('bcoin', 'wallet', 'qrurl', 'page_title'));
        }
    }


    //IPN Functions //////


    public function ipnCoinPayBtc(Request $request)
    {
        $track = $request->custom;
        $status = $request->status;
        $amount2 = floatval($request->amount2);
        $currency2 = $request->currency2;

        $data = Deposit::where('trx', $track)->orderBy('id', 'DESC')->first();

        if ($status>=100 || $status==2) {
            if ($currency2 == "BTC" && $data->status == '0' && $data->btc_amo <= $amount2) {
                $this->userDataUpdate($data);
            }
        }
    }

    public function ipnCoinPayEth(Request $request)
    {
        $track = $request->custom;
        $status = $request->status;
        $amount2 = floatval($request->amount2);
        $currency2 = $request->currency2;

        $data = Deposit::where('trx', $track)->orderBy('id', 'DESC')->first();
        $bcoin = $data->btc_amo;
        if ($status>=100 || $status==2) {
            if ($currency2 == "ETH" && $data->status == '0' && $data->btc_amo<=$amount2) {
                $this->userDataUpdate($data);
            }
        }
    }

    public function ipnCoinPayDoge(Request $request)
    {
        $track = $request->custom;
        $status = $request->status;
        $amount2 = floatval($request->amount2);
        $currency2 = $request->currency2;

        $data = Deposit::where('trx', $track)->orderBy('id', 'DESC')->first();
        $bcoin = $data->btc_amo;
        if ($status>=100 || $status==2) {
            if ($currency2 == "DOGE" && $data->status == '0' && $data->btc_amo<=$amount2) {
                $this->userDataUpdate($data);
            }
        }
    }
    public function ipnCoinPayLtc(Request $request)
    {
        $track = $request->custom;
        $status = $request->status;
        $amount2 = floatval($request->amount2);
        $currency2 = $request->currency2;

        $data = Deposit::where('trx', $track)->orderBy('id', 'DESC')->first();
        $bcoin = $data->btc_amo;
        if ($status>=100 || $status==2) {
            if ($currency2 == "LTC" && $data->status == '0') {
                $this->userDataUpdate($data);
            }
        }
    }
}
