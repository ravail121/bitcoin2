@extends('front.layout.master')
@section('style')

@stop
@section('body')
<!--bitcoin blance Strat--->
<section class="bitcoin-blacnce">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 balane">
                <p><span>{{$title}}</span></p>
            </div>
        </div>
    </div>
</section>

<!--bitcoin blance Strat--->


<!--Bitcoin Create Add Form-->
<section class="table pt-15 pb-30">
    <div class="container">
    {!! $fee->fee_top_box !!}
        <div class="row">
          <div class="col-lg-12 ml-auto">
            <!-- <div class="serach"><input type="email" class="form-control input-search" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Search"><img src="images/loupe.png"></div></div> -->
            <div class="table-div datatable transtion-type table-responsive">

                <table id="example" class="table table-striped bit-table responsive ">
                    <thead>
                        <tr>
                            <th>Transaction Type</th>
                            <th>Fixed Fee</th>
                            <th>Percentage Fee</th>
                            <th>Total Fee</th>
                        </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>Deposit</td>
                        <td>{{ number_format((float)$fee->deposit_external_fixed_fee, 8, '.', '') }} BTC</td>
                        <td>{{ $fee->deposit_external_percentage_fee }} %</td>
                        <td>{{ number_format((float)$fee->deposit_external_fixed_fee, 8, '.', '') }} BTC + {{ $fee->deposit_external_percentage_fee }} %</td>
                    </tr>

                    <tr>
                        <td>Withdraw</td>
                        <td>{{ number_format((float)$fee->withdraw_external_fixed_fee, 8, '.', '') }} BTC</td>
                        <td>{{ $fee->withdraw_external_percentage_fee }} %</td>
                        <td>{{ number_format((float)$fee->withdraw_external_fixed_fee, 8, '.', '') }} BTC + {{ $fee->withdraw_external_percentage_fee }} %</td>
                    </tr>

                    <tr>
                        <td>Buy Advertiser</td>
                        <td>{{ number_format((float)$fee->buy_advertiser_fixed_fee, 8, '.', '') }} BTC</td>
                        <td>{{ $fee->buy_advertiser_percentage_fee }} %</td>
                        <td>{{ number_format((float)$fee->buy_advertiser_fixed_fee, 8, '.', '') }} BTC + {{ $fee->buy_advertiser_percentage_fee }} %</td>
                    </tr>

                    <tr>
                        <td>Buy User</td>
                        <td>{{ number_format((float)$fee->buy_user_fixed_fee, 8, '.', '') }} BTC</td>
                        <td>{{ $fee->buy_user_percentage_fee }} %</td>
                        <td>{{ number_format((float)$fee->buy_user_fixed_fee, 8, '.', '') }} BTC + {{ $fee->buy_user_percentage_fee }} %</td>
                    </tr>

                    <tr>
                        <td>Sell Advertiser</td>
                        <td>{{ number_format((float)$fee->sell_advertiser_fixed_fee, 8, '.', '') }} BTC</td>
                        <td>{{ $fee->sell_advertiser_percentage_fee }} %</td>
                        <td>{{ number_format((float)$fee->sell_advertiser_fixed_fee, 8, '.', '') }} BTC + {{ $fee->sell_advertiser_percentage_fee }} %</td>
                    </tr>

                    <tr>
                        <td>Sell User</td>
                        <td>{{ number_format((float)$fee->sell_user_fixed_fee, 8, '.', '') }} BTC</td>
                        <td>{{ $fee->sell_user_percentage_fee }} %</td>
                        <td>{{ number_format((float)$fee->sell_user_fixed_fee, 8, '.', '') }} BTC + {{ $fee->sell_user_percentage_fee }} %</td>
                    </tr>

                    <tr>
                        <td>Send Internal</td>
                        <td>{{ number_format((float)$fee->send_internal_fixed_fee, 8, '.', '') }} BTC</td>
                        <td>{{ $fee->send_internal_percentage_fee }} %</td>
                        <td>{{ number_format((float)$fee->send_internal_fixed_fee, 8, '.', '') }} BTC + {{ $fee->send_internal_percentage_fee }} %</td>
                    </tr>

                    <tr>
                        <td>Receive Internal</td>
                        <td>{{ number_format((float)$fee->receive_internal_fixed_fee, 8, '.', '')}} BTC</td>
                        <td>{{ $fee->receive_internal_percentage_fee }} %</td>
                        <td>{{ number_format((float)$fee->receive_internal_fixed_fee, 8, '.', '') }} BTC + {{ $fee->receive_internal_percentage_fee }} %</td>
                    </tr>
                  </tbody>
                </table>
                
            </div>
        </div>
    {!! $fee->fee_bottom_box !!}
    </div>
</section>

<!--Bitcoin Create Add Form-->

@stop
