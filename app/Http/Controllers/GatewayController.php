<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gateway;
use Image;
use Carbon\Carbon;
use App\Models\GeneralSettings;
use App\Http\Requests\Gateway\UpdateFormRequest;

class GatewayController extends Controller
{
    public function show()
    {
        $gateways = Gateway::all();

        if (is_null($gateways)) {
            $default=[
                  'gateimg' => 'paypal.png',
                  'name' => 'PayPal',
                  'minamo' => '100',
                  'maxamo' => '100000',
                  'fixed_charge' => '10',
                  'percent_charge' => '11',
                  'rate' => '21',
                  'val1' => 'JHuiqejhkjq',
                  'val2' => '24897HHd',
                  'status' => '1'
              ];

            Gateway::create($default);
            $gateways = Gateway::all();
        }
        $page_title = "Payment Methods";

        return view('admin.deposit.gateway', compact('gateways', 'page_title'));
    }

    public function update(UpdateFormRequest $request)
    {
        $gateway = Gateway::all();

        foreach ($gateway as $data) {
            Gateway::whereId($data->id)
                    ->update([
                        'val1' => $request->val1,
                        'val2' => $request->val2,
                    ]);
        }

        return back()->with('success', 'Gateway Information Updated Successfully');
    }
}
