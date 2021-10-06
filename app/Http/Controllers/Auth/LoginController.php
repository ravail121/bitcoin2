<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\UserLogin;
use App\Events\UserActions;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Auth;
use App\Models\User;



class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
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
        $this->middleware('guest')->except('logout');
    }
    public function username()
    {
        return 'username';
    }
    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateLogin(Request $request)
    {
        $request->validate([
            $this->username() => 'required|string',
            'password' => 'required|string',
            'g-recaptcha-response' => 'required|captcha'
        ]);
    }


    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */

    public function authenticated(Request $request, $user)
    {

        event(new UserActions($request));
        if($user->status == 0){
            $this->guard()->logout();
            $notification =  array('alert' => 'Your account is deactivated.!','alert-type' => 'warning');
            return redirect('/login')->with($notification);
        }
        

        
        // $data=[];
        // $data['user_id']=$user->id;
        // $data['user_ip']=$ip;
        // $data['location']=$request->session()->get('country_id');
        // $data['details']=$request->session()->get('country');
        // UserLogin::create($data);
        // event(new UserActions($request));
        if($user->verified ==0){
            $notification =  array('alert' => 'Your Account and document verifcation is pending!','alert-type' => 'danger');
            return redirect('/user'.'/'.$user->username.'/edit-profile')->with($notification);
        }
        

    }



    public function logout(Request $request)
    {
        $user = User::findOrFail(Auth::id());

        if(Auth::user()->tauth == 1)
        {
            $user['tfver'] = 0;
        }else{
            $user['tfver'] = 1;

        }
        $user->save();

        Auth::guard()->logout();
        session()->flash('message', 'Just Logged Out!');
        return redirect('/login');
    }
}
