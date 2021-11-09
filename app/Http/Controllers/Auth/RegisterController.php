<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Events\UserActions;
use App\Models\User;
use App\Models\GeneralSettings;
use App\Models\Gateway;
use App\Models\Currency;
use App\Models\Slider;
use App\Models\Country;
use App\Models\UserLogin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use Carbon\Carbon;


class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/user/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showRegistrationForm(Request $request)
    {
        $basic = GeneralSettings::first();

        if ($basic->registration != 1)
        {
            return back()->with('alert', 'Registration Disable Now');
        }
        $slider = cache()->remember('slider', 3600, function () {
            return Slider::find(5);
        });
        $coin = cache()->remember('gateway', 3600, function () {
            return Gateway::all();
        });

        $country = Country::where('id', $request->session()->get('country_id'))->first();
        $methods = [];
        if(!is_null($country)) {
            $methods = $country->paymentMethods()->where('status', 1)->get();
        }

        $currency = cache()->remember('currency', 3600, function () {
            return Currency::where('status', 1)->get();
        });

        $countries = cache()->remember('countries', 3600, function () {
            return Country::where('active', true)->get();
        });
        $page_title = "Sign Up";
        return view('auth.register', compact('page_title','basic','slider', 'coin', 'methods', 'currency', 'countries'));
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'username' => 'required|min:5|unique:users|regex:/^\S*$/u',
            'password' => 'required|string|min:4|confirmed',
            'g-recaptcha-response' => 'required|captcha'
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $basic = GeneralSettings::first();

        if ($basic->email_verification == 1) {
            $email_verify = 0;
        } else {
            $email_verify = 1;
        }

        if ($basic->sms_verification == 1) {
            $phone_verify = 0;
        } else {
            $phone_verify = 1;
        }

        $verification_code  = rand (1000,9999);
        $sms_code  = rand (1000 , 9999);
        $email_time = Carbon::parse()->addMinutes(1);
        $phone_time = Carbon::parse()->addMinutes(1);

        $ip = geoip()->getLocation();
        $country = \App\Models\Country::whereName($ip->country)->first();


        $email=explode("@", $data['email']);
        if($email[1]!='tbe.email'){
            return User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'username' => $data['username'],
                'country_id' => $country->id,
                'email_verify' => $email_verify,
                'verification_code' => $verification_code,
                'sms_code' => $sms_code,
                'email_time' => $email_time,
                'phone_verify' => $phone_verify,
                'phone_time' => $phone_time,
                'tauth' => 0,
                'tfver' => 1,
                'password' => Hash::make($data['password']),
            ]);
        }
        else{
            return User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'username' => $data['username'],
                'email_verify' => $email_verify,
                'verification_code' => $verification_code,
                'sms_code' => $sms_code,
                'email_time' => $email_time,
                'phone_verify' => $phone_verify,
                'phone_time' => $phone_time,
                'tauth' => 0,
                'tfver' => 1,
                'password' => Hash::make($data['password']),
            ]);
        }
        

        
        // session()->flash('message', 'Sign Up Successfull!');
        // return redirect('/login');
    }



    function getBrowser()
    {
        $u_agent = $_SERVER['HTTP_USER_AGENT'];
        $bname = 'Unknown';
        $platform = 'Unknown';
        $version= "";

        //First get the platform?
        if (preg_match('/linux/i', $u_agent)) {
            $platform = 'linux';
        }
        elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
            $platform = 'mac';
        }
        elseif (preg_match('/windows|win32/i', $u_agent)) {
            $platform = 'windows';
        }
    
        // Next get the name of the useragent yes seperately and for good reason
        if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent))
        {
            $bname = 'Internet Explorer';
            $ub = "MSIE";
        }
        elseif(preg_match('/Firefox/i',$u_agent))
        {
            $bname = 'Mozilla Firefox';
            $ub = "Firefox";
        }
        elseif(preg_match('/Chrome/i',$u_agent))
        {
            $bname = 'Google Chrome';
            $ub = "Chrome";
        }
        elseif(preg_match('/Safari/i',$u_agent))
        {
            $bname = 'Apple Safari';
            $ub = "Safari";
        }
        elseif(preg_match('/Opera/i',$u_agent))
        {
            $bname = 'Opera';
            $ub = "Opera";
        }
        elseif(preg_match('/Netscape/i',$u_agent))
        {
            $bname = 'Netscape';
            $ub = "Netscape";
        }
    
        // finally get the correct version number
        $known = array('Version', $ub, 'other');
        $pattern = '#(?<browser>' . join('|', $known) .
        ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
        if (!preg_match_all($pattern, $u_agent, $matches)) {
            // we have no matching number just continue
        }
    
        // see how many we have
        $i = count($matches['browser']);
        if ($i != 1) {
            //we will have two since we are not using 'other' argument yet
            //see if version is before or after the name
            if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
                $version= $matches['version'][0];
            }
            else {
                $version= $matches['version'][1];
            }
        }
        else {
            $version= $matches['version'][0];
        }
    
        // check if we have a number
        if ($version==null || $version=="") {$version="?";}
    
        return array(
            'userAgent' => $u_agent,
            'name'      => $bname,
            'version'   => $version,
            'platform'  => $platform,
            'pattern'    => $pattern
        );
    }

    function url(){
        return sprintf(
          "%s://%s%s",
          isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
          $_SERVER['SERVER_NAME'],
          $_SERVER['REQUEST_URI']
        );
    }

    protected function registered(Request $request, $user)
    {   
        try{
            $ip = NULL; $deep_detect = TRUE;

            if (filter_var($ip, FILTER_VALIDATE_IP) === FALSE) {
                $ip = $_SERVER["REMOTE_ADDR"];
                if ($deep_detect) {
                    if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP))
                        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
                    if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP))
                        $ip = $_SERVER['HTTP_CLIENT_IP'];
                }
            }
            $data=[];
            $data['user_id']=$user->id;
            $data['user_ip']=$ip;
            $data['location']=$request->session()->get('country_id');
            $data['details']=$request->session()->get('country');
            // UserLogin::create($data);
            if(request()->path() != 'user/notification'){
                $user_ip = NULL; $deep_detect = TRUE;
    
                if (filter_var($user_ip, FILTER_VALIDATE_IP) === FALSE) {
                    $user_ip = $_SERVER["REMOTE_ADDR"];
                    if ($deep_detect) {
                        if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP))
                            $user_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
                        if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP))
                            $user_ip = $_SERVER['HTTP_CLIENT_IP'];
                    }
                }
                $url = $this->url();
                $browser = $this->getBrowser();    
                $ip = geoip()->getLocation();
                $changed = 0;
                // $currency = \App\Models\Currency::whereName($ip->currency)->first();
                $country = \App\Models\Country::whereName($ip->country)->first();
                
                $data=[];
                $data['user_id']=$user->id;
                $data['user_ip']=$user_ip;
                $data['location']=$country->id;
                $data['country_name']= $country->name;
                $data['is_country_changed'] = $changed;
                $data['browser']= $browser['name'];
                $data['platform']= $browser['platform'];
                $data['action']= $url;
                $data['details']= "left for testing"; //$browser['name']. '---' .$this->url(). '---' .$country;
                UserLogin::create($data);
                            
            }

            event(new UserActions($request));
        }catch(\Exception $e){

        }
        
        $basic = GeneralSettings::first();

        if ($basic->email_verify == 1) {
            $email_code = rand (1000 , 9999);
            $text = "Your Verification Code Is: <b>$email_code</b>";
            $this->sendMail($user->email, $user->username, 'Email verification', $text);
            $user->verification_code = $email_code;
            $user->email_time = Carbon::parse()->addMinutes(5);
            $user->save();
        }
        if ($basic->phone_verify == 1) {
            $sms_code = rand (1000 , 9999);
            $txt = "Your phone verification code is: $sms_code";
            $to = $user->phone;
            $this->sendSms($to, $txt);
            $user->sms_code = $sms_code;
            $user->phone_time = Carbon::parse()->addMinutes(5);
            $user->save();
        }
    }

    public function sendSms($to, $text)
    {
        $basic = GeneralSettings::first();
        $appi = $basic->smsapi;
        $text = urlencode($text);
        $appi = str_replace("{{number}}", $to, $appi);
        $appi = str_replace("{{message}}", $text, $appi);
        $result = file_get_contents($appi);
    }


}
