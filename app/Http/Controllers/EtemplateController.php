<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Etemplate;
use App\Http\Requests\Etemplate\UpdateFormRequest;
use App\Http\Requests\Etemplate\UpdateSMSAPIFormRequest;

class EtemplateController extends Controller
{
    public function index()
    {
        $data['page_title'] =  "Email Settings";
        $temp = $data['temp'] = Etemplate::first();
        if (is_null($temp)) {
            $default = [
                'esender' => 'email@example.com',
                'emessage' => 'Email Message',
                'smsapi' => 'SMS Message',
                'mobile' => '88019xxxxxx'
            ];
            Etemplate::create($default);
            $temp = Etemplate::first();
        }

        return view('admin.mailsms.email', $data);
    }
    public function smsApi()
    {
        $data['page_title'] =  "SMS Settings";
        $temp = $data['temp'] = Etemplate::first();
        if (is_null($temp)) {
            $default = [
                'esender' => 'email@example.com',
                'emessage' => 'Email Message',
                'smsapi' => 'SMS Message',

            ];
            Etemplate::create($default);
            $data['temp'] = Etemplate::first();
        }
        return view('admin.mailsms.sms', $data);
    }

    public function update(UpdateFormRequest $request)
    {
        $temp = Etemplate::first();

        $temp['esender'] = $request->esender;
        $temp['emessage'] = $request->emessage;

        $temp->save();

        return back()->with('success', 'Email Settings Updated Successfully!');
    }
    public function smsUpdate(UpdateSMSAPIFormRequest $request)
    {
        $temp = Etemplate::first();

        $temp['smsapi'] = $request->smsapi;
        $temp->save();

        return back()->with('success', 'SMS Api Updated Successfully!');
    }
}
