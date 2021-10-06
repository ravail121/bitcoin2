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
        <a class="logo-heder-img"><img src="{{asset('new-images/logo-login.png')}}" /></a>
        <p>Forgot Password</p>
    </div>
    <div class="login-content pt-20">
        <form method="post" action="{{ route('user.password.email') }}">
            @csrf
            <div class="showing-span">
                <div class="form-row login-span">
                    <div class="form-group col-md-12">
                        <span for="inputState" class="Select-control"> Email Address</span>
                        <input type="email" name="email" placeholder="Email Address" value="{{ old('email') }}" required autofocus class="form-control login-control" aria-describedby="emailHelp" />
                        @if ($errors->has('email'))
                            <span class="text-danger">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <button type="submit" class="btn btn-primary publish">Forgot Password</button>
            </div>
        </form>
    </div>
</section>
<!--Section: Login Form-->
<script src="{{ mix('js/app.js') }}"></script>

