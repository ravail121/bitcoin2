@extends('front.layout.master2')
@section('style')

@stop
@section('body')
<!--bitcoin blance Strat--->
<section class="bitcoin-blacnce">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 balane">
                <p><span>Two Factor Authenticator</span></p>
            </div>
        </div>
    </div>
</section>

<!--Bitcoin Create Add Form-->
<section class="pt-60 create-add">
    <div class="container">
        <div class="row">
            @if (count($errors) > 0)
                <div class="col-md-12">
                    <div class="alert alert-danger alert-dismissible">
                        <!-- <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> -->
                        <h4><i class="icon fa fa-ban"></i> Alert!</h4>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </div>
                </div>
            @endif
            @if(Auth::user()->tauth == '1')
            <div class="col-lg-6 setp1 two-factor">
                <div class="scan-code">
                    <p>Two Factor Authenticator</p>
                    @if(Auth::user()->tauth != '1')
                    <div class="form-row">
                        <div class="col-md-12">
                            <input type="text" value="{{$prevcode}}" class="form-control input-control" id="code" aria-describedby="emailHelp" placeholder="PGYFD5VYL4RUB63K" readonly/>
                            <span id="copybtn"><img src="{{asset('new-images/copy.png')}}" /></span>
                        </div>
                    </div>
                    <div class="qr-code-img">
                        <img src="{{$prevqr}}" />
                    </div>
                    @endif
                    <div class="ebble-btn">
                        <div class="ebble-btn pt-100">
                            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#disableModal">Disable Two Factor Authenticator</button>
                        </div>
                    </div>
                </div>
            </div>
            @else
            <div class="col-lg-6 setp1 two-factor">
                <div class="scan-code">
                    <p>Two Factor Authenticator</p>
                    <div class="form-row">
                        <div class="col-md-12">
                            <input type="text" name="key" value="{{$secret}}" class="form-control input-control" id="code" aria-describedby="emailHelp" placeholder="PGYFD5VYL4RUB63K" readonly/>
                            <span id="copybtn"><img src="{{asset('new-images/copy.png')}}" /></span>
                        </div>
                    </div>
                    <div class="qr-code-img">
                        <img src="{{$qrCodeUrl}}">
                    </div>
                    <div class="ebble-btn">
                        <div class="ebble-btn pt-100">
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#enableModal">Enable Two Factor Authenticator</button>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            <div class="col-lg-6 setp1 google-Authen">
                <div class="atuhscan">
                    <p class="google-sacan-auth">Google Authenticator</p>

                    <span class="use-google">Use Google Authenticator To Scan The QR Code Or Use The Code</span>
                    <p class="google-auth">
                        Google Authenticator is a multifactor app for mobile devices. It generates timed codes used during the 2-step verification process. To use Google Authenticator, install the Google Authenticator application on
                        your mobile device.
                    </p>

                    <div class="ebble-btn pt-100">
                        <a class="btn" href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2&hl=en" target="_blank">Download App</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--Bitcoin Create Add Form-->

    <div class="row padding-pranto-top padding-pranto-bottom">
        @if (count($errors) > 0)
            <div class="col-md-12">
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h4><i class="icon fa fa-ban"></i> Alert!</h4>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="col-md-6">
            @if(Auth::user()->tauth == '1')
                <div class="card">
                    <div class="card-header">
                        <h4 class="panel-title">Two Factor Authenticator</h4>
                    </div>
                    <div class="card-body text-center">
                        @if(Auth::user()->tauth != '1')
                        <div class="form-group">
                            <div class="input-group">
                                <input type="text" value="{{$prevcode}}" class="form-control input-lg" id="code" readonly>
                                <span class="input-group-addon btn btn-success" id="copybtn">Copy</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <img src="{{$prevqr}}">
                        </div>
                        @endif
                        <button type="button" class="btn btn-block btn-lg btn-danger" data-toggle="modal" data-target="#disableModal">Disable Two Factor Authenticator</button>
                    </div>
                </div>
            @else
                <div class="card">
                    <div class="card-header">
                        <h4 class="panel-title">Two Factor Authenticator</h4>
                    </div>
                    <div class="card-body text-center">
                        <div class="form-group">
                            <div class="input-group">
                                <input type="text" name="key" value="{{$secret}}" class="form-control input-lg" id="code" readonly>
                                <span class="input-group-addon btn btn-success" id="copybtn">Copy</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <img src="{{$qrCodeUrl}}">
                        </div>
                        <button type="button" class="btn btn-block btn-lg btn-primary small-font-size-in-mobile-device" data-toggle="modal" data-target="#enableModal">Enable Two Factor Authenticator</button>
                    </div>
                </div>
            @endif


        </div>

        <div class="col-md-6 margin-top-pranto">
            <div class="card">
                <div class="card-header">
                    <h4 class="panel-title">Google Authenticator</h4>
                </div>
                <div class="card-body text-center">
                    <h5 style="text-transform: capitalize;">Use Google Authenticator to Scan the QR code  or use the code</h5><hr/>
                    <p>Google Authenticator is a multifactor app for mobile devices. It generates timed codes used during the 2-step verification process. To use Google Authenticator, install the Google Authenticator application on your mobile device.</p>
                    <a class="btn btn-info" href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2&hl=en" target="_blank">DOWNLOAD APP</a>
                </div>
            </div>
        </div>

    </div>





    <!--Enable Modal -->
    <div id="enableModal" class="modal fade" role="dialog">

        <div class="modal-dialog">

            <div class="modal-content">
                <div class="modal-header">

                    <h4 class="modal-title">Verify Your OTP</h4>
                </div>

                <form method="post" action="{{route('go2fa.create')}}">
                    @csrf

                    <div class="modal-body">
                        {{csrf_field()}}

                        <div class="form-group">
                            <input type="hidden" name="key" value="{{$secret}}">
                            <input type="text" class="form-control" name="code" placeholder="Enter Google Authenticator Code">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary pull-right">Verify</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!--Disable Modal -->
    <div id="disableModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Verify Your OTP to Disable</h4>
                </div>
                <div class="modal-body">
                    <form action="{{route('disable.2fa')}}" method="POST">
                        {{csrf_field()}}
                        <div class="form-group">
                            <input type="text" class="form-control" name="code" placeholder="Enter Google Authenticator Code">
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-lg btn-success btn-block">Verify</button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

@stop

@section('script')
    <script type="text/javascript">
        document.getElementById("copybtn").onclick = function()
        {
            document.getElementById('code').select();
            document.execCommand('copy');
        }
    </script>
@stop