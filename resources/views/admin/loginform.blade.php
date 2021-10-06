<!DOCTYPE html>
<html>
<head>

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-2G3ZY2JKVH"></script>
    <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'G-2G3ZY2JKVH');
    </script>
    
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="{{ asset('storage/logo/favicon.png') }}" />
    <link rel="stylesheet" href="{{ mix('css/admin.css') }}">

    <meta property="og:locale" content="en_US" />
	<meta property="og:type" content="website" />
	<meta property="og:title" content="Bitcoin.ngo | Buy Bitcoin Locally" />
	<meta property="og:description" content="The New Local Bitcoins | Cash & 360+ online payment methods | INSTANT VERIFICATION." />
	<meta property="og:url" content="{{route('homepage')}}" />
	<meta property="og:site_name" content="Bitcoin.ngo | Buy Bitcoin Locally" />
	<meta property="article:modified_time" content="2019-09-19T10:16:12+00:00" />
	<meta property="og:image" content="{{ asset('storage/logo/social_preview.png') }}" />
	<meta property="og:image:width" content="720" />
	<meta property="og:image:height" content="720" />
	<meta name="twitter:card" content="summary_large_image" />
	<meta name="twitter:label1" content="Written by">
	<meta name="twitter:data1" content="Bitcoin WorldWide">
    <meta name="facebook-domain-verification" content="i5hs1ljb4jvrg9e4xzmurblnftkqzc" />
    
    <!-- Main CSS-->
    {{-- <link rel="stylesheet" type="text/css" href="{{asset('assets/admin/css/main.css')}}">
    <!-- Font-icon css-->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/admin/css/font-awesome.min.css')}}"> --}}
    <title>{{$page_title}} | {{$basic->sitename}}</title>
    <style>
        #error{
            color: red;
        }
        .error
        {
            color: red;
        }
        .abir{
            display: fixed;
            z-index: 299;
            position: absolute;
            /*width: 85%;*/
            color: #FFF;
            background-color: #26a1ab;
            border-color: #26a1ab;
        }
        .slow-spin {
            -webkit-animation: fa-spin 2s infinite linear;
            animation: fa-spin 2s infinite linear;
        }
    </style>
</head>
<body>
<section class="material-half-bg">
    <div class="cover"></div>
</section>
<section class="login-content">
    <div class="logo">
        <h1>{{$basic->sitename}}</h1>
    </div>
    <div class="login-box">
        <form class="login-form" id="login-form" method="post">
          @csrf
            <h3 class="login-head"><i class="fa fa-lg fa-fw fa-user"></i>SIGN IN</h3>
            <div class="form-group">
                <label class="control-label">USERNAME</label>
                <input class="form-control" name="username" type="text" placeholder="Username" autofocus>
            </div>
            <div class="form-group">
                <label class="control-label">PASSWORD</label>
                <input class="form-control" name="password" type="password" placeholder="Password">
            </div>
            <div class="form-group">
                {!! app('captcha')->display() !!}
            </div>


            <div class="form-group btn-container" id="working">
                <button class="btn btn-primary btn-block" ><i class="fa fa-sign-in fa-lg fa-fw"></i>SIGN IN</button>

            </div>
            <br>



                <div id="error"></div>

        </form>

    </div>
</section>
<script src="{{ mix('js/app.js') }}"></script>
<!-- <script src="{{ asset('new-js/3.5.1-jquery.min.js')}}"></script> -->
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script> -->
<script>
    $('document').ready(function(){
        /* validation */
        $("#login-form").validate({
            rules:
                {
                    password: {
                        required: true,
                    },
                    username: {
                        required: true,
                    },
                },
            messages:
                {
                    password: "<span style='color: red'>Password is required.</span>",
                    username: "<span style='color: red'>Username is required.</span>",
                },
            submitHandler: submitForm
        });
        /* validation */

        /* login submit */
        function submitForm(){
            var data = $("#login-form").serialize();

            $.ajax({

                type : 'POST',
                url  : "{{route('admin.login')}}",
                data : data,
                beforeSend: function()
                {
                    $("#error").fadeOut();
                    $("#working").html('<button class="btn btn-primary btn-block" ><strong class="block" style="font-weight: bold;">  <i class = "fa fa-spinner slow-spin"></i>  Validating Your Data.... </strong></button>');
                },
                success :  function(response)
                {

                    if(response=="ok"){

                        $("#working").html('<button class="btn btn-primary btn-block"> <i class="fa fa-check"></i> Success! Waiting to Dashboard...</button>');
                        setTimeout(' window.location.href = "{{route('admin.dashboard')}}"; ',3000);
                    }
                    else{

                        $("#error").fadeIn(1000, function(){
                            $("#error").html('<div class="alert alert-dismissible alert-danger"><button class="close" type="button" data-dismiss="alert">×</button>'+response+'</div>');
                            $("#working").html('<button class="btn btn-primary btn-block" id="working"><i class="fa fa-sign-in fa-lg fa-fw"></i>SIGN IN</button>');
                        });
                    }
                },
                error :  function(response)
                {   
                    
                    // console.log(response)
                    $("#error").fadeIn(1000, function(){
                        $("#error").html('<div class="alert alert-dismissible alert-danger"><button class="close" type="button" data-dismiss="alert">×</button>Invalid data</div>');
                        $("#working").html('');
                    });

                }

            });
            return false;
        }
        /* login submit */
    });
</script>
{!! NoCaptcha::renderJs() !!}

</body>
</html>
