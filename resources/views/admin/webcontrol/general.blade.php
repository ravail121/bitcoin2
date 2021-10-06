@extends('admin.layout.master')

@section('body')
    <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <h3 class="tile-title ">{{$page_title}}</h3>
                <div class="tile-body">
                    <form role="form" method="POST" action="">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-md-4">
                                <h6>Website Title</h6>
                                <div class="input-group">
                                    <input type="text" class="form-control input-lg" value="{{$general->sitename}}"
                                           name="sitename">
                                    <div class="input-group-append"><span class="input-group-text">
                                            <i class="fa fa-file-text-o"></i>
                                            </span>
                                    </div>
                                </div>

                            </div>

                            <div class="col-md-4">
                                <h6>BASE COLOR CODE</h6>
                                <div class="from-group">
                                    <input type="color" style="height: 35px; width: 100%;" value="#{{$general->color}}" name="color">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <h6>Decimal After Point</h6>
                                <div class="input-group">
                                    <input type="text" class="form-control input-lg" value="{{$general->decimal}}"
                                           name="decimal">
                                    <div class="input-group-append"><span class="input-group-text">
                                            Decimal
                                            </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br>

                        <div class="row">
                            <hr/>
                            <div class="col-md-4">
                                <h6>Basic BTC Price Factor</h6>
                                <div class="input-group">
                                    <input type="number" step="0.05" class="form-control input-lg" value="{{$general->btc_price_factor}}"
                                           name="btc_price_factor">
                                    <div class="input-group-append"><span class="input-group-text">
                                            %
                                            </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <h6>Dashboard Refresh Time</h6>
                                <div class="input-group">
                                    <input type="number" class="form-control input-lg" value="{{$general->dashboard_refresh_time}}"
                                           name="dashboard_refresh_time">
                                    <div class="input-group-append"><span class="input-group-text">
                                            Min
                                            </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <h6>Counter Blink Time</h6>
                                <div class="input-group">
                                    <input type="number" class="form-control input-lg" value="{{$general->counter_blinking_time}}"
                                           name="counter_blinking_time">
                                    <div class="input-group-append"><span class="input-group-text">
                                            Sec
                                            </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <br>
                        <div class="row">
                            <hr/>
                            <div class="col-md-4">
                                <h6>Transection Charge</h6>
                                <div class="input-group">
                                    <input type="text" class="form-control input-lg" value="{{$general->trx_charge}}"
                                           name="trx_charge">
                                    <div class="input-group-append"><span class="input-group-text">
                                            %
                                            </span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <h6>EMAIL VERIFICATION</h6>
                                <input data-toggle="toggle" data-onstyle="success" data-offstyle="danger"
                                       data-width="100%" type="checkbox"
                                       name="email_verification" {{ $general->email_verification == "1" ? 'checked' : '' }}>
                            </div>
                            <div class="col-md-4">
                                <h6>SMS VERIFICATION</h6>
                                <input data-toggle="toggle" data-onstyle="success" data-offstyle="danger"
                                       data-width="100%" type="checkbox"
                                       name="sms_verification" {{$general->sms_verification == "1" ? 'checked' : ''}}>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <hr/>
                            <div class="col-md-4">
                                <h6>Registration</h6>
                                <input data-toggle="toggle" data-onstyle="success" data-offstyle="danger"
                                       data-width="100%" type="checkbox"
                                       name="registration" {{$general->registration == "1" ? 'checked' : '' }}>
                            </div>
                            <div class="col-md-4">
                                <h6>EMAIL NOTIFICATION</h6>
                                <input data-toggle="toggle" data-onstyle="success" data-offstyle="danger"
                                       data-width="100%" type="checkbox"
                                       name="email_notification" {{ $general->email_notification == "1" ? 'checked' : '' }}>
                            </div>
                            <div class="col-md-4">
                                <h6>SMS NOTIFICATION</h6>
                                <input data-toggle="toggle" data-onstyle="success" data-offstyle="danger"
                                       data-width="100%" type="checkbox"
                                       name="sms_notification" {{ $general->sms_notification == "1" ? 'checked' : '' }}>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            
                            <div class="col-md-4">
                                <h6>USER AUTO VERIFICATION</h6>
                                <input data-toggle="toggle" data-onstyle="success" data-offstyle="danger"
                                       data-width="100%" type="checkbox"
                                       name="auto_verification" {{$general->auto_verification == "1" ? 'checked' : '' }}>
                            </div>
                            <div class="col-md-4">
                                <h6>Min Balance For Sell AD</h6>
                                <div class="input-group">
                                    <input type="number" step="0.00000001" min="0" class="form-control input-lg" value="{{$general->min_balance_for_sell_ad}}"
                                           name="min_balance_for_sell_ad">
                                    <div class="input-group-append"><span class="input-group-text">
                                            BTC
                                            </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-12">
                                <h6>Google Map</h6>
                                <div class="input-group">
                                    <input type="text" class="form-control input-lg" value="{{$general->google_map}}"
                                           name="google_map">
                                    <div class="input-group-append"><span class="input-group-text">
                                            <i class="fa fa-globe"></i>
                                            </span>
                                    </div>
                                </div>

                            </div>


                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-12">
                                <h6>Header Title</h6>
                                <div class="form-group">
                                    <input type="text" class="form-control input-lg" value="{{$general->banner_title}}" name="banner_title">
                                </div>

                            </div>

                            <div class="col-md-12">
                                <h6>Header Sub-Title</h6>
                                <div class="form-group">
                                    <input type="text" class="form-control input-lg" value="{{$general->banner_sub_title}}" name="banner_sub_title">
                                </div>

                            </div>


                        </div>
                        <br>
                        <div class="row">
                            <hr/>
                            <div class="col-md-12 ">
                                <button class="btn btn-primary btn-block btn-lg">Update</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <h3 class="tile-title">Gateway settings</h3>
                <div class="tile-body">
                    <form class="" action="{{ url('adminio/bitcoind') }}" method="post">
                        @csrf
                        {{ method_field('PUT') }}
                        <legend>Bitcoin Node</legend>
                        <div class="row">
                            <div class="col-6">
                              <div class="form-group">
                                   <label for="inputScheme">Scheme</label>
                                   <input type="text" class="form-control" id="inputScheme" name="keys[BITCOIND_SCHEME]" value="{{ config('bitcoind.default.scheme') }}">
                               </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="inputHost">Host</label>
                                    <input type="text" class="form-control" id="inputHost" name="keys[BITCOIND_HOST]" value="{{ config('bitcoind.default.host') }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                              <div class="form-group">
                                   <label for="inputPort">Port</label>
                                   <input type="text" class="form-control" id="inputPort" name="keys[BITCOIND_PORT]" value="{{ config('bitcoind.default.port') }}">
                               </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="inputFee">Network Fee</label>
                                    <input type="text" class="form-control" id="inputFee" name="keys[BITCOIND_NETWORK_FEE]" value="{{ config('bitcoind.fee') }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                              <div class="form-group">
                                   <label for="inputUser">RPC User</label>
                                   <input type="text" class="form-control" id="inputUser" name="keys[BITCOIND_USER]" value="{{ config('bitcoind.default.user') }}">
                               </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="inputPassword">RPC Password</label>
                                    <input type="password" class="form-control" id="inputPassword" name="keys[BITCOIND_PASSWORD]" value="{{ config('bitcoind.default.password') }}">
                                </div>
                            </div>
                        </div>
                        <legend>Cold Wallets</legend>
                        <div class="row">
                          <div class="col-6">
                            <div class="form-group">
                                 <label for="inputUser">Settlement Wallet Address</label>
                                 <input type="text" class="form-control" id="inputUser" name="keys[BITCOIND_MASTER_WALLET_ADDRESS]" value="{{ config('bitcoind.default.master.address') }}">
                             </div>
                          </div>
                          <div class="col-6">
                              <div class="form-group">
                                  <label for="inputPassword">Withdrawal Wallet Private Key</label>
                                  <input type="text" class="form-control" id="inputPassword" name="keys[BITCOIND_MASTER_WALLET_PRIVATE]" value="{{ config('bitcoind.default.master.private') }}">
                              </div>
                          </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 ">
                                <button type="submit" class="btn btn-primary btn-block btn-lg">
                                    Update
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
