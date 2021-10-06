<!DOCTYPE html>
<html lang="zxx">
@php
    $keywords = \App\Models\SEOKeywords::all()->random(5);
@endphp
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="All about {{ $keywords[0]->keyword }}, {{ $keywords[1]->keyword }}, {{ $keywords[2]->keyword }}, {{ $keywords[3]->keyword }} and also {{ $keywords[4]->keyword }}">
    <meta name="keywords" content="{{ $keywords[0]->keyword }}, {{ $keywords[1]->keyword }}, {{ $keywords[2]->keyword }}, {{ $keywords[3]->keyword }}, {{ $keywords[4]->keyword }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
    
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-2G3ZY2JKVH"></script>
    <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'G-2G3ZY2JKVH');
    </script>

    
    <title>
        @if(!empty($page_title)) {{$general->sitename}}: {{$page_title}} @endif
    </title>

    <!-- New Theme -->
    <link href="{{ URL::asset('/new-css/style.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('/new-css/navbar-responisve.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('/new-css/bootstrap.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('/new-css/responsive.css') }}" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('/new-fonts/fontawesome.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('/new-fonts/fontawesome.css') }}" />
    <link href="{{ URL::asset('/new-fonts/all.css') }}" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('/new-css/datatable.css') }}" />
    <!-- end new theme -->

    <!--bootstrap Css-->

    <!-- <link rel="stylesheet" href="{{ URL::asset('/css/animate.css') }}"> -->

    <!--Favicon add-->
    <link rel="icon" type="image/png" href="{{ asset('storage/logo/favicon.png') }}" />
    <!--bootstrap Css-->
    <!-- <link rel="stylesheet" href="{{ mix('css/front.css') }}">
    <link href="{{url('/')}}/assets/front/color.php?color={{$general->color}}" rel="stylesheet">
    <link rel="stylesheet" href="{{ URL::asset('/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('/css/bootstrap-grid.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css"> -->

    <!--    <link rel="stylesheet" href="{{ URL::asset('/css/font-awesome.min.css') }}">-->
    <!-- <link rel="stylesheet" href="{{ URL::asset('/css/jquery.selectBoxIt.css') }}"> -->
    <!--    <link rel="stylesheet" href="{{ URL::asset('/css/owl_style.css') }}">-->
    <!-- <link rel="stylesheet" href="{{ URL::asset('/css/style2.css') }}"> -->
    <!--    <link rel="stylesheet" href="{{ URL::asset('/css/style_dashbard.css') }}">-->
    <!-- <link rel="stylesheet" href="{{ URL::asset('/css/style1.css') }}"> -->
    <!-- <link rel="stylesheet" href="{{ URL::asset('/css/responsive.css') }}"> -->
    <!--Favicon add-->
    <!-- <link rel="icon" type="image/png" href="{{ asset('images/logo/favicon.png') }}" /> -->
    <!-- @yield('style') -->
    <!--############################### banner Section  css###############################-->
    <style>
        .indShow {
        position: relative;
        display: inline-block;
        cursor:pointer;
        
        }

        .indShow .indShowtext {
        visibility: hidden;
        width: 260px;
        background-color: #66a3ac;
        color: #ffffff;
        text-align: left;
        box-shadow: 6px 5px 2px 1px #888888;
        padding: 5px 0;
        /* Position the indShow */
        position: absolute;
        top: 5px;
        left: 105%;
        padding: 6px;
        }
        .indShowtext {
            z-index:3333333;
        }

        .indShow:hover .indShowtext {
        visibility: visible;
        }
        .indShow:focus .indShowtext {
        visibility: visible;
        }

    </style>
<!-- <link href="https://fonts.googleapis.com/css2?family=Inter:wght@500&display=swap" rel="stylesheet"> -->

</head>

<body>

    <div class="topbar">
        <div class="container">
            <div class="top-ul-social">
                <!-- <div class="topbar-social ml-auto">
                    @foreach($social as $data)
                    <a target="_blank" href="{{$data->link}}">{!! $data->code !!}</a>
                    @endforeach
                </div> -->
                @guest
                <div class="das-board">
                    <ul>
                        <li>
                            <a class="" href="{{url('register')}}">Register </a>
                        </li>
                        <li>
                            <a class="" href="{{url('login')}}">Login </a>
                        </li>
                    </ul>
                </div>
                @else
                    @php
                    try{
                    $user_bal =\App\Models\UserCryptoBalance::where('user_id', Auth::id())
                    ->where('gateway_id', 505)->first()->balance;
                    }catch(Exception $exception){
                    $user_bal =0;
                    }

                    @endphp
                <div class="das-board">
                    <a href="{{url('user/'.Auth::user()->username.'/home')}}" class="card-link mr-3"> <img src="{{ asset('new-images/dashboard/dashboard.png') }}" /></a>

                    <div class="navbar-nav justify-content-end balance badge badge-info">{{number_format((float)round($user_bal, 8), 8, '.', '')}} BTC</div>
                    
                    <ul class="navbar-nav justify-content-end cart_dropdown">
                        <li class="nav-item dropdown notification cart_dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbardrop" role="button" data-bs-toggle="dropdown" data-target="#user_menu" data-disabled="true" aria-expanded="false" data-toggle="dropdown">
                                Hi, {{ Auth::user()->username }}
                            </a>
                            <div id="user_menu" aria-labelledby="user_menu" class="cart_box1 dropdown-menu dropdown-menu-right">
                                
                                <ul class="cart_list">
                                    <li><a class="dropdown-item drop-downn-list" href="{{url('user/'.Auth::user()->username.'/home')}}"> Dashboard </a></li>
                                    <li><a class="dropdown-item drop-downn-list" href="/profile/{{Auth::user()->username}}"> My Profile </a></li>
                                    <li><a class="dropdown-item drop-downn-list" href="/user/{{ Auth::user()->username }}/change-password"> Change Password </a></li>
                                    <li><a class="dropdown-item drop-downn-list" href="{{route('support.index.customer', auth()->user()->username)}}"> Support Ticket </a></li>
                                    <li><a class="dropdown-item drop-downn-list" href="{{url('user/'.Auth::user()->username.'/security/two/step')}}"> Security </a></li>

                                    <li><a
                                        class="dropdown-item drop-downn-list"
                                        href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                                        document.getElementById('logout-form').submit();"
                                    >
                                        Logout
                                    </a></li>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        {{ csrf_field() }}
                                    </form>
                                </ul>
                            </div>
                        </li>

                    </ul>
                </div>
                @endguest
            </div>
        </div>
    </div>
    <header class="main-header fixed header_wrap  header_with_topbar">
        <div class="sticky-area-wrap">
            <div class="sticky-area">
                <div class="container">
                    <nav class="navbar navbar-expand-lg navbar-light ">
                        <div class="container-fluid">
                            <a class="navbar-brand" href="@if(isset(auth()->user()->username)) {{url('/'.auth()->user()->username.'/market')}} @else {{url('/')}} @endif">
                                <img src="{{ asset('storage/logo/logo.png') }}" alt="Learn about {{ $keywords[0]->keyword }}, {{ $keywords[1]->keyword }}, {{ $keywords[2]->keyword }} and know more about {{ $keywords[3]->keyword }} and {{ $keywords[4]->keyword }}">
                            </a>
                            <ul class="navbar-nav attr-nav align-items-center">
                                @if(\Auth::user())
                                    @php
                                    $messages = \App\Models\Notification::where('to_user',Auth::id())->orderBy('created_at','desc');
                                    $messages1 =
                                    \App\Models\Notification::where('to_user',Auth::id())->whereNull('read_message')->orderBy('created_at','desc');

                                    @endphp

                                    @php
                                    if($messages1->count() >0){
                                    $badge="danger";
                                    }else{
                                    $badge="secondary";
                                    }
                                    @endphp

                                    <li class="nav-item dropdown notification mobile">
                                        <span class="noti">    
                                            <div class="notification">
                                                <a href="#">
                                                    <div class="notBtn" href="#">
                                                        

                                                        <svg class="svg-inline--fa fa-bell fa-w-14" aria-hidden="true" focusable="false" data-prefix="far" data-icon="bell" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg="">
                                                            <path
                                                                fill="currentColor"
                                                                d="M439.39 362.29c-19.32-20.76-55.47-51.99-55.47-154.29 0-77.7-54.48-139.9-127.94-155.16V32c0-17.67-14.32-32-31.98-32s-31.98 14.33-31.98 32v20.84C118.56 68.1 64.08 130.3 64.08 208c0 102.3-36.15 133.53-55.47 154.29-6 6.45-8.66 14.16-8.61 21.71.11 16.4 12.98 32 32.1 32h383.8c19.12 0 32-15.6 32.1-32 .05-7.55-2.61-15.27-8.61-21.71zM67.53 368c21.22-27.97 44.42-74.33 44.53-159.42 0-.2-.06-.38-.06-.58 0-61.86 50.14-112 112-112s112 50.14 112 112c0 .2-.06.38-.06.58.11 85.1 23.31 131.46 44.53 159.42H67.53zM224 512c35.32 0 63.97-28.65 63.97-64H160.03c0 35.35 28.65 64 63.97 64z"
                                                            ></path>
                                                        </svg>
                                                        <span>{{$messages1->count()}}</span>
                                                        <div class="box">
                                                            <div class="display">
                                                                <div class="header">
                                                                    <!-- <p class="not">Notification</p> -->
                                                                    <p class="not"><a  href="/user/allNotification/{{Auth::user()->username}}" >All Notifications</a></p>
                                                                    <p class="not1"><a  href="/user/readallNotification/{{Auth::id()}}" >Mark As Read</a></p>
                                                                </div>
                                                            
                                                                @if($messages->count()>0)
                                                                    <div class="cont">
                                                                        @foreach($messages->get() as $data)
                                                                        <a class="" href="/notification-read/{{$data->id}}/{{Auth::user()->username}}" style="text-decoration: none;  @if($data->read_message == null) color: #000 !important; @else color: #666 !important; @endif">
                                                                            <div class="sec new">
                                                                                <div class="txt">{{$data->message}}</div>
                                                                                <div class="txt sub">{{\Carbon\Carbon::createFromTimeStamp(strtotime($data->created_at))->diffForHumans()}}</div>
                                                                            </div>
                                                                        </a>
                                                                        @endforeach
                                                                        <div class="sub-ding">
                                                                            <a href="/user/allNotification/{{Auth::user()->username}}" class="btn btn-primary notfication">See All Notifications</a>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                        </span>
                                    </li>
                                @endif
                            </ul>
                            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                            </button>
                            <div class="top-navbar collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
                                <ul class="navbar-nav">
                                    <li class="nav-item dropdown @if(request()->path() == 'trade/btc') active @endif">
                                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">BUY & SELL BTC
                                        </a>
                                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                            <li><a class="dropdown-item" href="/trade/btc?buy">Buy BTC</a></li>
                                            <li><a class="dropdown-item" href="/trade/btc?sell">Sell BTC</a></li>
                                            <!-- <li><hr class="dropdown-divider"></li>
                                            <li><a class="dropdown-item" href="#">Something else here</a></li> -->
                                        </ul>
                                    </li>
                                    @guest
                                    <li class="nav-item @if (request()->getrequestUri() == '/methods/guide') active @endif">
                                        <a class="nav-link" href="/methods/guide">
                                            Payment Methods
                                        </a>
                                    </li>
                                    @if(false)
                                        @foreach($menus as $data)
                                        <li class="nav-item @if(request()->path() == 'menu/'.$data->slug ) active @endif">
                                            <a class="nav-link" href="{{route('menu.view', $data->slug)}}">{{$data->name}} </a>
                                        </li>
                                        @endforeach
                                    @endif
                                    <li class="nav-item @if(request()->path() == 'how-to-buy-btc') active @endif">
                                        <a class="nav-link" href="{{route('how-to-buy-btc.index')}}">How To Buy BTC</a>
                                    </li>
                                    <li class="nav-item @if(request()->path() == 'contact' ) active @endif">
                                        <a class="nav-link" href="{{route('contact.index')}}">Contact </a>
                                    </li>
                                    @else
                                    <li
                                        class="nav-item dropdown @if(request()->path() == 'user/{{Auth::user()->username}}/advertise/coin'|| request()->path() == 'user/{{Auth::user()->username}}/advertise/history' ) active @endif">
                                        <a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-target="#1navbardrop"
                                            data-disabled="true" aria-expanded="false" data-toggle="dropdown">
                                            Post A Trade
                                        </a>
                                        <div class="dropdown-menu" id="1navbardrop">
                                            <a class="dropdown-item drop-downn-list"
                                                href="{{  route('advertise.sell.coin', auth()->user()->username)}}"> Create AD</a>
                                            <a class="dropdown-item drop-downn-list"
                                                href="{{ route('advertise.history', auth()->user()->username) }}"> My AD History </a>
                                        </div>
                                    </li>
                                    <li
                                        class="nav-item dropdown @if(request()->path() == 'user/{{Auth::user()->username}}/deposits'|| request()->path() == 'user/{{Auth::user()->username}}/transactions' )active @endif">
                                        <a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-target="#4navbardrop"
                                            data-disabled="true" aria-expanded="false" data-toggle="dropdown">
                                            Transactions
                                        </a>
                                        <div class="dropdown-menu" id="4navbardrop">
                                            <a class="dropdown-item drop-downn-list"
                                                href="{{route('user.withdraws', auth()->user()->username)}}"> Send History</a>
                                            <a class="dropdown-item drop-downn-list"
                                                href="{{route('deposit.history', auth()->user()->username)}}"> Deposit History</a>
                                            <a class="dropdown-item drop-downn-list"
                                                href="{{route('trans.history', auth()->user()->username)}}"> Transactions History </a>
                                        </div>
                                    </li>
                                    <li
                                        class="nav-item dropdown @if(request()->path() == 'user/{{Auth::user()->username}}/deposit' || request()->path() == route('user.withdraws', auth()->user()->username) || request()->path() == route('receives.history', auth()->user()->username)) active @endif">
                                        <a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-target="#1navbardrop"
                                            data-disabled="true" aria-expanded="false" data-toggle="dropdown">
                                            Send/Receive
                                        </a>
                                        <div class="dropdown-menu" id="1navbardrop">
                                            <a class="dropdown-item drop-downn-list"
                                                href="{{route('deposit.guide', auth()->user()->username)}}"> Deposit</a>
                                            @if(auth()->user()->permission_withdraw)<a class="dropdown-item drop-downn-list"
                                                href="{{route('user.withdraws', auth()->user()->username)}}"> Send </a>@endif
                                            <!-- @if(auth()->user()->permission_send)<a class="dropdown-item drop-downn-list"
                                                href="{{route('user.sends', auth()->user()->username)}}"> Send Internal</a>@endif -->
                                            <!-- <a class="dropdown-item drop-downn-list"
                                                href="{{route('receives.history', auth()->user()->username)}}"> Receive Internal </a> -->
                                        </div>
                                    </li>
                                    <li
                                        class="nav-item dropdown @if(request()->path() == 'contact' || request()->path() == 'menu/how-to-buy-btc' || request()->path() == 'methods/guide')active @endif">
                                        <a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-target="#1navbardrop"
                                            data-disabled="true" aria-expanded="false" data-toggle="dropdown">
                                            Help
                                        </a>
                                        <div class="dropdown-menu" id="1navbardrop">
                                            @if(false)
                                                @foreach($menus as $data)
                                                <a class="dropdown-item drop-downn-list" href="{{route('menu.view', $data->slug)}}">
                                                    {{$data->name}}</a>
                                                @endforeach
                                            @endif
                                            <a class="dropdown-item drop-downn-list" href="{{route('how-to-buy-btc.index')}}">How To Buy BTC</a>
                                            <a class="dropdown-item drop-downn-list" href="{{route('user.fee-structure')}}"> Fee
                                                Structure</a>
                                            <a class="dropdown-item drop-downn-list" href="{{route('payment.guide')}}"> Payment
                                                Methods</a>
                                            <a class="dropdown-item drop-downn-list"
                                                href="{{route('contact.index.username', auth()->user()->username)}}"> Contact</a>
                                        </div>
                                    </li>
                                    @endguest

                                    @if(\Auth::user())
                                        @php
                                        $messages = \App\Models\Notification::where('to_user',Auth::id())->orderBy('created_at','desc');
                                        $messages1 =
                                        \App\Models\Notification::where('to_user',Auth::id())->whereNull('read_message')->orderBy('created_at','desc');

                                        @endphp

                                        @php
                                        if($messages1->count() >0){
                                        $badge="danger";
                                        }else{
                                        $badge="secondary";
                                        }
                                        @endphp

                                        <li class="nav-item dropdown notification desktop">
                                            <div class="notification">
                                                <a href="#">
                                                    <div class="notBtn" href="#">
                                                        

                                                        <svg class="svg-inline--fa fa-bell fa-w-14" aria-hidden="true" focusable="false" data-prefix="far" data-icon="bell" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg="">
                                                            <path
                                                                fill="currentColor"
                                                                d="M439.39 362.29c-19.32-20.76-55.47-51.99-55.47-154.29 0-77.7-54.48-139.9-127.94-155.16V32c0-17.67-14.32-32-31.98-32s-31.98 14.33-31.98 32v20.84C118.56 68.1 64.08 130.3 64.08 208c0 102.3-36.15 133.53-55.47 154.29-6 6.45-8.66 14.16-8.61 21.71.11 16.4 12.98 32 32.1 32h383.8c19.12 0 32-15.6 32.1-32 .05-7.55-2.61-15.27-8.61-21.71zM67.53 368c21.22-27.97 44.42-74.33 44.53-159.42 0-.2-.06-.38-.06-.58 0-61.86 50.14-112 112-112s112 50.14 112 112c0 .2-.06.38-.06.58.11 85.1 23.31 131.46 44.53 159.42H67.53zM224 512c35.32 0 63.97-28.65 63.97-64H160.03c0 35.35 28.65 64 63.97 64z"
                                                            ></path>
                                                        </svg>
                                                        <span>{{$messages1->count()}}</span>
                                                        <div class="box">
                                                            <div class="display">
                                                                <div class="header">
                                                                    <!-- <p class="not">Notification</p> -->
                                                                    <p class="not"><a  href="/user/allNotification/{{Auth::user()->username}}" >All Notifications</a></p>
                                                                    <p class="not1"><a  href="/user/readallNotification/{{Auth::id()}}" >Mark As Read</a></p>
                                                                </div>
                                                            
                                                                @if($messages->count()>0)
                                                                    <div class="cont">
                                                                        @foreach($messages->get() as $data)
                                                                        <a class="" href="/notification-read/{{$data->id}}/{{Auth::user()->username}}" style="text-decoration: none; @if($data->read_message == null) color: #000 !important; @else color: #666 !important; @endif">
                                                                            <div class="sec new">
                                                                                <div class="txt">{{$data->message}}</div>
                                                                                <div class="txt sub">{{\Carbon\Carbon::createFromTimeStamp(strtotime($data->created_at))->diffForHumans()}}</div>
                                                                            </div>
                                                                        </a>
                                                                        @endforeach
                                                                        <div class="sub-ding">
                                                                            <a href="/user/allNotification/{{Auth::user()->username}}" class="btn btn-primary notfication">See All Notifications</a>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                        </li>
                                    @endif
                                </ul> 
                            </div>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </header>
    <div class="" style="margin-bottom: 70px;">
    @if( !empty(Auth::user()) && Auth::user()->verified ==0)
    <div class="alert alert-danger " style="text-align: center;">Your account and documents are not verified yet please update your account for activating your account <span style="padding: 13px;"> <a class="btn btn-primary" href="/user/{{ Auth::user()->username }}/edit-profile">Verify</a> </span></div>
    @endif
        <div id="justify-height">
            @yield('body')
        </div>
    </div>

    <!-- <div id="back-to-top" class="scroll-top back-to-top active" data-original-title="" title="" style="display: none">
        <i class="fa fa-angle-up"></i>
    </div> -->

    <!--New Theme footer Strat--->
    <footer class="blog-footer">
        <div class="container-fluid">
            <div class="text-center">
                <a href="{{url('/')}}"><img class="footer_logo_style rounded" src="{{ asset('new-images/logo-footer.png') }}" alt="logo" /></a>
            </div>

            <div class="row pt-60 footer-link">
                <div class="col-md-4">{{$general->address}}</div>
                <div class="col-md-4">{{$general->phone}}</div>
                <div class="col-md-4">{{$general->email}}</div>
            </div>
        </div>
    </footer>
    <div class="footer-bottom">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <p>
                        <a href="#"><span>{{$general->copyright}}</span></a>
                        <a href="{{route('terms.index')}}"><span>Terms</span></a> &amp; <a href="{{route('policy.index')}}"><span>Policy</span></a>
                    </p>
                </div>
                <div class="col-lg-2 offset-lg-4">
                    <div class="topbar-social ml-auto">
                        @foreach($social as $data)
                        <a target="_blank" href="{{$data->link}}">{!! $data->code !!}</a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!---New Theme Footer end-->

    <!-- New Theme -->
    <script src="{{ asset('new-js/all.js')}}"></script>
    <script src="{{ asset('new-js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{ asset('new-js/3.5.1-jquery.min.js')}}"></script>
    <script src="{{ asset('new-js/jquery.dataTables.min.js')}}"></script>
    <script src="{{ asset('new-js/dataTables.bootstrap5.min.js')}}"></script>
    <script src="{{ mix('js/app.js') }}"></script>
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> -->
    <!-- end new theme -->

<!--Start of Tawk.to Script-->
<script type="text/javascript">
var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
(function(){
var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
s1.async=true;
s1.src='https://embed.tawk.to/5f69942ef0e7167d00128ca6/default';
s1.charset='UTF-8';
s1.setAttribute('crossorigin','*');
s0.parentNode.insertBefore(s1,s0);
})();
</script>
<!--End of Tawk.to Script-->

    @yield('script')

    @if (Session::has('alert'))
    <script type="text/javascript">
    $(document).ready(function() {
        swal("{{ Session::get('alert') }}", "", "warning");
    });
    </script>
    
    @endif

    @if (Session::has('message'))
    <script type="text/javascript">
    $(document).ready(function() {
        swal("{{ Session::get('message') }}", "", "success");
    });
    </script>
    @endif

    @if (Session::has('success'))
    <script type="text/javascript">
    $(document).ready(function() {
        swal("{{ Session::get('success') }}", "", "success");
    });
    </script>
    @endif

    <script>
    $(document).ready(function() {

        $(document).on('change','#category_id1',function(){
          $('#method_id')
            .empty()
        
    
       var categories = {!!json_encode($categories) !!};
       var methods  = {!!json_encode($methods) !!};
       var current=$(this).val();
       var string = '';
       for(var i in methods){
        //    if(methods[i].id == $(this).val()){
              
               var cat_ids = methods[i].category_ids;
               
               
               if(cat_ids){
                var cat_ids = cat_ids.split(',');
                console.log(cat_ids)
                    
                        if (cat_ids.indexOf(current.toString()) !== -1 ){

                            string +='<option value="'+methods[i].id+'">'+methods[i].name+' </option>';
                        
                        }
                    
               }
       }
       if(string == ''){
            string +='<option value=""> No Payment Method</option>';
       }
       $('#method_id').append(string)
       $('#method_id').attr("disabled", false);
       console.log(categories)
    
    })

    function playSound() {
        const audio = new Audio("https://www.nasa.gov/mp3/581097main_STS-1_Dust-it-Off.mp3");
        audio.play();
    }

        setInterval(notirfresh, 30000);
        var last_notification = {!! isset($last_message->id) ? $last_message->id : 0 !!};
        function notirfresh() {
            $('div.noti').empty();
            $.ajax({
                type: "GET",
                url: "{{route('notification.get')}}",

                success: function(data) {
                    if (data.balance) {
                        var userBal = data.balance;
                        userBal =Number(userBal.balance);
                        var bal = userBal.toFixed(8) + ' BTC';
                        $('.balance').text(bal);
                    }
                    // console.log(data.deal)
                    // console.log(data.approval.length)
                    var messages = data.messages;
                    var count = data.messages1;
                    // console.log(messages)
                    var Tlength = count.length;
                    if (Tlength > 0) {
                        $('.Tlength').text(Tlength);
                        $('.Tlength').removeClass('badge-secondary');
                        $('.Tlength').addClass('badge-danger');

                    } else {
                        $('.Tlength').text(Tlength);
                        $('.Tlength').removeClass('badge-danger');
                        $('.Tlength').addClass('badge-secondary');
                    }

                    console.log(Tlength);
                          
                    var string = '<div class="notification_box">  <hr><div class="row "><div class="col-md-4"></div><div class="col-md-4"><h4>Notifications</h4></div><div class="col-md-4"  style="padding-top:3px;"><a  href="/user/readallNotification/{{Auth::id()}}">Mark all as read</a> </div></div>  <hr>';
                    if (messages.length > 0) {
                        if(last_notification != messages[0].id){
                            last_notification = messages[0].id;
                            /* Audio link for notification */ 
                            var audio = new Audio("{{asset('sounds/swiftly.mp3')}}"); 
                            
                            var options = {
                                body: messages[0].message,
                                icon: "{{asset('images/favicon.png')}}"
                            }
                            if (!("Notification" in window)) {
                                alert("This browser does not support desktop notification");
                            }

                            // Let's check whether notification permissions have already been granted
                            else if (Notification.permission === "granted") {
                                // If it's okay let's create a notification
                                // var notification = new Notification("New Notification!", options);
                                // audio.play(); 
                                // notification.show();
                            }

                            // Otherwise, we need to ask the user for permission
                            else if (Notification.permission !== "denied") {
                                Notification.requestPermission().then(function (permission) {
                                // If the user accepts, let's create a notification
                                if (permission === "granted") {
                                    // var notification = new Notification("New Notification!", options);
                                    // audio.play(); 
                                    // notification.show();
                                }
                                });
                            }
                        }
                            for(var i in messages){
                                // console.log(messages[i].message);
                                // console.log(value.trans_id);
                            var id = messages[i].id;
                            var message = messages[i].message;
                            var times = messages[i].times;
                            var read = messages[i].read_message;

                            var url = "/notification-read/" + id;

                            // console.log(url);
                            if (read == null) {
                                string +=
                                    '<div style="background-color:darkgrey;  white-space: normal;"> <a class="dropdown-item"  style="white-space: normal;" href="' +
                                    url + '"> ' + message + ' <span class="pull-right" style="margin-right:5px;"> ' +
                                    times + '</span> </a></div> <hr>'

                            } else {
                                string += '<div   white-space: normal;"> <a class="dropdown-item"  href="' + url +
                                    '"> ' + message + ' <span class="pull-right" style="margin-right:5px;"> ' +
                                    times + '</span> </a></div> <hr>'

                            }


                            }
                            string += '</div><a class="btn btn-dashboard btn-block" href="/user/allNotification/@if(isset(Auth::user()->username)) {{Auth::user()->username}} @endif"> <h4>See All Notification</h4> </a>';
                        

                    } else {
                        string += ' <a class="dropdown-item" > No Notification </a><hr>'
                    }
                    string += '';
                    // $('.noti').append(string)

                },
                error: function(data) {

                }
            })
        }



        var winheight = $(window).height() - 365;
        $('#justify-height').css('min-height', winheight + 'px');
        $('#buycurrency_id').change(function() {
            var value = $('#add_type1').val('1');
        });
        $('#sellcurrency_id').change(function() {
            var value = $('#add_type1').val('2');
        });


        // $('.myTable').DataTable({
           
        //     'columnDefs': [{
        //         "orderable": false
        //     }],
        //     "aaSorting": [],
   
        //     "dom": '<"top">r<"bottom"f><"clear">',

        // });
        






    });
    </script>

</body>

</html>