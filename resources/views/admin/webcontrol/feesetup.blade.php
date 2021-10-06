@extends('admin.layout.master')

@section('body')
    <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <h3 class="tile-title ">{{$page_title}}</h3>
                <div class="tile-body">
                    <form role="form" method="POST" action="">
                        {{ csrf_field() }}

                        <hr/>
                        <div class="form-group">
                            <label class="col-md-12"><strong style="text-transform: uppercase;">Top Box</strong></label>
                            <div class="col-md-12">
                                <textarea id="area-top" class="form-control" rows="15" name="fee_top_box">{{ $general->fee_top_box }}</textarea>
                            </div>
                        </div>
                        <hr/>
                        <div class="row">
                            
                            <div class="col-md-3">
                                <h6>Deposit Fixed Fee</h6>
                                <div class="input-group">
                                    <input type="number" step="0.00000001" class="form-control input-lg" value="{{number_format((float)$general->deposit_external_fixed_fee, '8', '.', '')}}"
                                           name="deposit_external_fixed_fee">
                                    <div class="input-group-append"><span class="input-group-text">
                                            BTC
                                            </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <h6>Deposit Percentage Fee</h6>
                                <div class="input-group">
                                    <input type="number" step="0.01" class="form-control input-lg" value="{{$general->deposit_external_percentage_fee}}"
                                           name="deposit_external_percentage_fee">
                                    <div class="input-group-append"><span class="input-group-text">
                                            %
                                            </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <h6>Withdraw Fixed Fee</h6>
                                <div class="input-group">
                                    <input type="number" step="0.00000001" class="form-control input-lg" value="{{number_format((float)$general->withdraw_external_fixed_fee, '8', '.', '')}}"
                                           name="withdraw_external_fixed_fee">
                                    <div class="input-group-append"><span class="input-group-text">
                                            BTC
                                            </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <h6>Withdraw Percentage Fee</h6>
                                <div class="input-group">
                                    <input type="number" step="0.01" class="form-control input-lg" value="{{$general->withdraw_external_percentage_fee}}"
                                           name="withdraw_external_percentage_fee">
                                    <div class="input-group-append"><span class="input-group-text">
                                            %
                                            </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br>

                        <div class="row">
                            <hr/>
                            <div class="col-md-3">
                                <h6>Buy Advertiser Fixed Fee</h6>
                                <div class="input-group">
                                    <input type="number" step="0.00000001" class="form-control input-lg" value="{{number_format((float)$general->buy_advertiser_fixed_fee, '8', '.', '')}}"
                                        name="buy_advertiser_fixed_fee">
                                    <div class="input-group-append"><span class="input-group-text">
                                            BTC
                                            </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <h6>Buy Advertiser Percentage Fee</h6>
                                <div class="input-group">
                                    <input type="number" step="0.01" class="form-control input-lg" value="{{$general->buy_advertiser_percentage_fee}}"
                                        name="buy_advertiser_percentage_fee">
                                    <div class="input-group-append"><span class="input-group-text">
                                            %
                                            </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <h6>Buy User Fixed Fee</h6>
                                <div class="input-group">
                                    <input type="number" step="0.00000001" class="form-control input-lg" value="{{number_format((float)$general->buy_user_fixed_fee, '8', '.', '')}}"
                                        name="buy_user_fixed_fee">
                                    <div class="input-group-append"><span class="input-group-text">
                                            BTC
                                            </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <h6>Buy User Percentage Fee</h6>
                                <div class="input-group">
                                    <input type="number" step="0.01" class="form-control input-lg" value="{{$general->buy_user_percentage_fee}}"
                                        name="buy_user_percentage_fee">
                                    <div class="input-group-append"><span class="input-group-text">
                                            %
                                            </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br>

                        <div class="row">
                            <hr/>
                            <div class="col-md-3">
                                <h6>Sell Advertiser Fixed Fee</h6>
                                <div class="input-group">
                                    <input type="number" step="0.00000001" class="form-control input-lg" value="{{number_format((float)$general->sell_advertiser_fixed_fee, '8', '.', '')}}"
                                        name="sell_advertiser_fixed_fee">
                                    <div class="input-group-append"><span class="input-group-text">
                                            BTC
                                            </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <h6>Sell Advertiser Percentage Fee</h6>
                                <div class="input-group">
                                    <input type="number" step="0.01" class="form-control input-lg" value="{{$general->sell_advertiser_percentage_fee}}"
                                        name="sell_advertiser_percentage_fee">
                                    <div class="input-group-append"><span class="input-group-text">
                                            %
                                            </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <h6>Sell User Fixed Fee</h6>
                                <div class="input-group">
                                    <input type="number" step="0.00000001" class="form-control input-lg" value="{{number_format((float)$general->sell_user_fixed_fee, '8', '.', '')}}"
                                        name="sell_user_fixed_fee">
                                    <div class="input-group-append"><span class="input-group-text">
                                            BTC
                                            </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <h6>Sell User Percentage Fee</h6>
                                <div class="input-group">
                                    <input type="number" step="0.01" class="form-control input-lg" value="{{$general->sell_user_percentage_fee}}"
                                        name="sell_user_percentage_fee">
                                    <div class="input-group-append"><span class="input-group-text">
                                            %
                                            </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br>

                        <div class="row">
                            <hr/>
                            <div class="col-md-3">
                                <h6>Send Fixed Fee</h6>
                                <div class="input-group">
                                    <input type="number" step="0.00000001" class="form-control input-lg" value="{{number_format((float)$general->send_internal_fixed_fee, '8', '.', '')}}"
                                        name="send_internal_fixed_fee">
                                    <div class="input-group-append"><span class="input-group-text">
                                            BTC
                                            </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <h6>Send Percentage Fee</h6>
                                <div class="input-group">
                                    <input type="number" step="0.01" class="form-control input-lg" value="{{$general->send_internal_percentage_fee}}"
                                        name="send_internal_percentage_fee">
                                    <div class="input-group-append"><span class="input-group-text">
                                            %
                                            </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <h6>Receive Fixed Fee</h6>
                                <div class="input-group">
                                    <input type="number" step="0.00000001" class="form-control input-lg" value="{{number_format((float)$general->receive_internal_fixed_fee, '8', '.', '')}}"
                                        name="receive_internal_fixed_fee">
                                    <div class="input-group-append"><span class="input-group-text">
                                            BTC
                                            </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <h6>Receive Percentage Fee</h6>
                                <div class="input-group">
                                    <input type="number" step="0.01" class="form-control input-lg" value="{{$general->receive_internal_percentage_fee}}"
                                        name="receive_internal_percentage_fee">
                                    <div class="input-group-append"><span class="input-group-text">
                                            %
                                            </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br>
                        <hr />
                        <div class="form-group">
                            <label class="col-md-12"><strong style="text-transform: uppercase;">Bottom Box</strong></label>
                            <div class="col-md-12">
                                <textarea id="area-bottom" class="form-control" rows="15" name="fee_bottom_box">{{ $general->fee_bottom_box }}</textarea>
                            </div>
                        </div>
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

@endsection

@section('script')
    <script src="https://cdn.ckeditor.com/ckeditor5/16.0.0/classic/ckeditor.js"></script>
    <script>
      ClassicEditor
        .create( document.querySelector( '#area-top' ) )
        .then( editor => {
                console.log( editor );
        })
        .catch( error => {
                console.error( error );
        });
    </script>
    <script>
      ClassicEditor
        .create( document.querySelector( '#area-bottom' ) )
        .then( editor => {
                console.log( editor );
        })
        .catch( error => {
                console.error( error );
        });
    </script>
@stop