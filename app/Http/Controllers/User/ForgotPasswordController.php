<?php

namespace App\Http\Controllers\User;

use App\Models\GeneralSettings;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Password;
use App\Models\User;
use DB;

class ForgotPasswordController extends Controller
{
    use SendsPasswordResetEmails;
    public function __construct()
    {
        $basic= GeneralSettings::first();

    }

    public function showLinkRequestForm()
    {
        $data['page_title'] = "Send Link password";
        return view('auth.passwords.email',$data);
    }

    public function sendResetLinkEmail(Request $request)
    {

         $this->validate($request,[
                'email' => 'required',
            ]);

        $user = User::where('email', $request->email)->first();
        if ($user == null)
        {
            return back()->with('alert', 'Email Not Available');
        }
        else
        {
            $to =$user->email;
            $name = $user->username;
            $subject = 'Password Reset';
            $code = str_random(30);
            $message = 'Use This Link to Reset Password: '.url('/').'/reset/'.$code;

            DB::table('password_resets')->insert(
                ['email' => $to, 'token' => $code, 'status' => 0, 'created_at' => date("Y-m-d h:i:s")]
            );

try{
            send_email($to,$name ,$subject, $message);
        }catch(\Exception $e){

        }
            return back()->with('success', 'Password Reset Email Sent Succesfully');
        }

    }
}
