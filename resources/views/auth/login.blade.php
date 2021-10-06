
{!! NoCaptcha::renderJs() !!}
<style>
    @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;700;900&display=swap");
</style>
<!-- <link href="{{ URL::asset('/new-css/style.css') }}" rel="stylesheet" /> -->
<!-- <link href="{{ URL::asset('/new-css/navbar-responisve.css') }}" rel="stylesheet" /> -->
<link href="{{ URL::asset('/new-css/bootstrap.min.css') }}" rel="stylesheet" />
<link href="{{ URL::asset('/new-css/responsive.css') }}" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="{{ URL::asset('/new-fonts/fontawesome.min.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ URL::asset('/new-fonts/fontawesome.css') }}" />
<link href="{{ URL::asset('/new-fonts/all.css') }}" rel="stylesheet" />
<!-- <link rel="stylesheet" type="text/css" href="{{ URL::asset('/new-css/datatable.css') }}" /> -->
<link href="{{ URL::asset('/new-css/login.css') }}" rel="stylesheet" />
<!--Section: Login Form-->
<section class="mb-5 login-form">
    <div class="logo-header text-center">
        <a class="logo-heder-img" href="{{url('/')}}"><img src="{{ asset('new-images/logo-login.png') }}"></a>
        <p>Login</p>
        <span class="">Please login to access Bitcoin.ngo area</span>
    </div>
    <div class="login-content pt-20">
        <form method="post" action="{{ route('login') }}">
            @csrf
            <div class="showing-span">
                <div class="form-row login-span">
                    <div class="form-group col-md-12">
                        <span for="inputState" class="Select-control">User Name or Email</span>
                        <input type="text" class="form-control login-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="User Name / Email" name="username" value="{{old('username')}}" />
                        @if ($errors->has('username'))
                            <span class="alert1">{{ $errors->first('username') }}</span>
                        @endif
                    </div>           
                </div>
                <div class="form-row login-span">
                    <div class="form-group col-md-12">
                        <span for="inputState" class="Select-control">Password</span>
                        <input type="Password" class="form-control login-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Password" name="password" />
                        @if ($errors->has('password'))
                            <span class="alert1">{{ $errors->first('password') }}</span>
                        @endif
                    </div>
                </div>
                 
                <div class="row align-items-center">
                    <div class="col-lg-6 col-md-6 col-sm-6 remember-me-wrap">
                        <div class="form-group">
                            <input class="styled-checkbox" id="styled-checkbox-1" name="remember" type="checkbox" value="1">
                            <label class="form-check-label" for="styled-checkbox-1">Remember me</label>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 lost-your-password-wrap">
                        <a class="lost-your-password" href="{{ route('password.request') }}">Forgot Password?</a>
                    </div>
                </div>
                    {!! app('captcha')->display() !!}
                    @if ($errors->has('g-recaptcha-response'))
                        <span class="alert1">The captcha field is required.</span>
                    @endif
                <button type="submit" class="btn btn-primary publish">Login</button>
                <div class="footer-login text-center pt-10"><span>New to Bitcoin.ngo?</span><span> <a class="" href="{{ route('register') }}">Register</a> </span>
                </div>
            </div>
        </form>
    </div>
</section>
<!--Section: Login Form-->

