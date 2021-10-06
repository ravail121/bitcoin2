
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
<!-- <link href="{{ URL::asset('/new-fonts/all.css') }}" rel="stylesheet" /> -->
<!-- <link rel="stylesheet" type="text/css" href="{{ URL::asset('/new-css/datatable.css') }}" /> -->
<link href="{{ URL::asset('/new-css/login.css') }}" rel="stylesheet" />
<!--Section: Login Form-->
<section class="mb-5 login-form">
    <div class="logo-header text-center">
        <a class="logo-heder-img" href="{{url('/')}}"><img src="{{asset('new-images/logo-login.png')}}"></a>
        <p>Register</p>
        <span class="">Register your account with {{$general->sitename}} is free, quick and easy</span>
    </div>
    <div class="login-content pt-30">
    <form method="post" action="{{url('/register')}}">
        @csrf
        @if (count($errors) > 0)
            <div class="row">
                <div class="col-md-010">
                    <div class="alert alert-danger alert-dismissible">
                        <!-- <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> -->
                        <h12><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Alert!</h12>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
        <div class="showing-span">
            <div class="form-row login-span row">
                    <div class="form-group col-md-12">
                        <span for="inputState" class="Select-control">Full Name</span>
                            <input type="text" name="name" value="{{old('name')}}" class="form-control login-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Full Name" />
                    </div>
                    
                </div>
                
                    <div class="form-row login-span row">
                    
                    <div class="form-group col-lg-12">
                        <span for="inputState" class="Select-control">User Name</span>
                            <input type="text" name="username" value="{{old('username')}}" class="form-control login-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="User Name" />
                    </div>
                        
                </div>
                <div class="form-row login-span row">
                    
                        <div class="form-group col-lg-12">
                        <span for="inputState" class="Select-control">Email</span>
                            <input type="email" name="email" value="{{old('email')}}" class="form-control login-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Email" />
                    </div>
                    
                
                </div>
                    <div class="form-row login-span row">
                    
                    <div class="form-group col-lg-12">
                        <span for="inputPassword" class="Select-control">Password</span>
                            <input type="password" id="inputPassword" name="password" value="{{old('password')}}" class="form-control login-control" aria-describedby="emailHelp" placeholder="Password" />
                    </div>
                        
                        
                </div>
                <div class="form-row login-span row">
                
                        <div class="form-group col-lg-12">
                        <span for="inputState" class="Select-control">Confirm Password</span>
                            <input type="password" name="password_confirmation" value="{{old('password_confirmation')}}" class="form-control login-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Confirm Password" />
                    </div>
                
                </div>

                <div class="form-row">
                    {!! app('captcha')->display() !!}
                </div>
                
                    <button type="submit" class="btn btn-primary publish">Register</button>


                    <div class="footer-login text-center pt-10"><span>Have An Account?</span><span> <a class="" href="{{route('login')}}"> Login</a> </span>
                    </div>
            </form>
            </div>
</section>
<!--Section: Login Form-->
    
