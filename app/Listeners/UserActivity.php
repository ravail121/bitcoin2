<?php

namespace App\Listeners;

use App\Events\UserActions;
use App\Models\UserLogin;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UserActivity
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
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

    /**
     * Handle the event.
     *
     * @param  UserActions  $event
     * @return void
     */
    public function handle(UserActions $event)
    {
        // need modifications
        //$message = $event->request->user()->name . ' just logged in to the application.';
        if(request()->path() != 'user/notification' && isset($event->request->user()->id) && $event->request->user()->id != 1){
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
            $prev_action = UserLogin::where('user_id', $event->request->user()->id)->orderBy('id', 'desc')->first();
            // isset($prev_action->action) && $prev_action->action != $url ? "true" : "false";
            // echo "<br>".$prev_action->action."--".$url;exit;
            if(isset($prev_action->action) && $prev_action->action != $url){
                $browser = $this->getBrowser();
                $country = session()->get('country');
    
                $ip = geoip()->getLocation();
                $changed = 0;
                $old_country = session()->get('country');
                // $currency = \App\Models\Currency::whereName($ip->currency)->first();
                $country = \App\Models\Country::whereName($ip->country)->first();
                if ($old_country != $ip->country) {
                    $changed = 1;
                }
                
                $data=[];
                $data['user_id']=$event->request->user()->id;
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
        }

    }
}
