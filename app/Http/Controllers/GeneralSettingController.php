<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Storage;
use App\Models\GeneralSettings;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use File;
use App\Http\Requests\Admin\UpdateFormRequest;

use App\Http\Requests\Admin\UpdatePasswordFormRequest;

class GeneralSettingController extends Controller
{

    public function index()
    {
        $data['page_title'] = "Basic Settings";
        return view('admin.loginform', $data);
    }

    public function GenSetting()
    {
        $data['page_title'] = 'General Settings';
        $data['general'] = GeneralSettings::first();
        return view('admin.webcontrol.general', $data);
    }

    public function feeSetup()
    {
        $data['page_title'] = 'Fee Settings';
        $data['general'] = GeneralSettings::first();
        return view('admin.webcontrol.feesetup', $data);
    }

    public function UpdateGenSetting(Request $request)
    {
        $gs = GeneralSettings::first();
        $in = $request->except('_token');


        $in['color'] = ltrim($request->color, '#');
        $in['registration'] = $request->registration == 'on' ? '1' : '0';
        $in['email_verification'] = $request->email_verification == 'on' ? '1' : '0';
        $in['sms_verification'] = $request->sms_verification == 'on' ? '1' : '0';
        $in['email_notification'] = $request->email_notification == 'on' ? '1' : '0';
        $in['auto_verification'] = $request->auto_verification == 'on' ? '1' : '0';
        $in['sms_notification'] = $request->sms_notification == 'on' ? '1' : '0';
        $in['withdraw_status'] = $request->withdraw_status == 'on' ? '1' : '0';

        $res = $gs->fill($in)->save();

        if ($res) {
            return back()->with('success', 'Updated Successfully!');
        } else {
            return back()->with('alert', 'Problem With Updating');
        }
    }

    public function updateFeeSetup(Request $request)
    {
        $gs = GeneralSettings::first();
        $in = $request->except('_token');

        $res = $gs->fill($in)->save();

        if ($res) {
            return back()->with('success', 'Updated Successfully!');
        } else {
            return back()->with('alert', 'Problem With Updating');
        }
    }


    public function changePassword()
    {
        $data['page_title'] = "Change Password";
        return view('admin.webcontrol.change_password', $data);
    }

    public function updatePassword(UpdatePasswordFormRequest $request)
    {
        $user = Auth::guard('admin')->user();

        $oldPassword = $request->old_password;
        $password = $request->new_password;
        $passwordConf = $request->password_confirmation;

        if (!Hash::check($oldPassword, $user->password) || $password != $passwordConf) {
            $notification =  array('message' => 'Password Do not match !!', 'alert-type' => 'error');
            return back()->with($notification);
        } elseif (Hash::check($oldPassword, $user->password) && $password == $passwordConf) {
            $user->password = bcrypt($password);
            $user->save();
            $notification =  array('message' => 'Password Changed Successfully !!', 'alert-type' => 'success');
            return back()->with($notification);
        }
    }


    public function profile()
    {
        $data['admin'] = Auth::user();
        $data['page_title'] = "Profile Settings";
        return view('admin.webcontrol.profile', $data);
    }

    public function updateProfile(UpdateFormRequest $request)
    {
        $data = Admin::find($request->id);
     
        $in = $request->except('_method', '_token');
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $location = 'admin/';
            $filename=time().'.jpg';
          
            $file=file_get_contents($file);
          Storage::put($location.$filename, $file, 'public');

            $in['image'] = 'storage/admin/'.$filename;
            
        }


        
       
        $data->fill($in)->save();

        $notification =  array('message' => 'Profile Update Successfully', 'alert-type' => 'success');
        return back()->with($notification);
    }

    /**
     * Update bitcoind shceme
     *
     * @param Illuminate\Http\Request $request
     *
     * @return View
     */
    public function updateBitcoindScheme(Request $request)
    {
        $update = overwriteEnvFile($request->keys);

        \Artisan::call('config:cache');
        \Artisan::call('config:clear');

        $type = $update ? 'success' : 'alert';
        $message = $update ? 'Successfully Updated!' : 'Something get wrong!.';

        return back()->with($type, $message);
    }
}
