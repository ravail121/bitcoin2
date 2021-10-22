@extends('admin.layout.master')

@section('css')
<style>
rect:nth-child(even) {
    fill: #17a2b8;
}

rect:nth-child(odd) {
    fill: #19b952;
}

.card-header {
    padding: 0.40rem 1.25rem;
    /*background: #8c7ae6;*/
    background: #2f353b;
    color: white;
    font-size: 20px;
}

.widget-small .info h4 {
    font-size: 15px;
}

.widget-small {
    margin-bottom: 0px;
}

.card {
    margin-bottom: 20px !important;
    border: 1px solid #2f353b;
}

@media (min-width:312px) and (max-width:480px) {
    .widget-small {
        margin-bottom: 20px !important;
    }
}

canvas {
    width: 1000px;
    height: 400px;
}

.blink_me {
  animation: blinker 1s linear {!! $Gset->counter_blinking_time !!};
}

@keyframes blinker {  
  50% { opacity: 0.4; }
}
</style>
@stop
@section('body')
@if($dashboard_type == 'Counter')
<!-- Counters -->

<style>
    /* .well{background-color: wheat;} */
    .colorbg{
        background-color: wheat;
        padding: 10px;
    }
    a {
        color: white;
    }
    .text-decoration{
        width: 100%;
    }
    .info{
        text-transform: uppercase;
    }
    a:hover {color: white;}
    
    .value{
        float: right;
    }
    .green{background-color: green;}
    .red{background-color: red;}
    .blue{background-color: blue;}
    
    .widget-small:hover {
    transform: scale(1.1); /* (150% zoom - Note: if the zoom is too large, it will go outside of the viewport) */
    }
</style>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <i class="icon fa fa-users"></i> Action Required
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-sm-2">
                                <div class="well">
                                    <a href="{{url('/adminio/dispute/deals')}}" class="text-decoration">
                                        <div id="deals_under_dispute_blink" class="widget-small red p-1">
                                            <div class="info">
                                                <h4>Disputed Deals</h4>
                                                <p><b class="counter" id="deals_under_dispute">{{$disputed_deals_count}}</b></p>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="well">
                                    <a href="{{url('/adminio/withdraw-pending-requests')}}" class="text-decoration">
                                        <div  id="pending_withdrawals_blink" class="widget-small blue p-1">
                                            <div class="info">
                                                <h4>Pending Withdraw</h4>
                                                <p><b class="counter" id="pending_withdrawals">0</b></p>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="well">
                                    <a href="{{url('/adminio/send-pending-requests')}}" class="text-decoration">
                                        <div  id="pending_sends_blink" class="widget-small blue p-1">
                                            <div class="info">
                                                <h4>Pending Send</h4>
                                                <p><b class="counter" id="pending_sends">0</b></p>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="well">
                                    <a href="{{url('/adminio/users?unverified')}}" class="text-decoration">
                                        <div  id="users_pending_verifying_blink" class="widget-small red p-1">
                                            <div class="info">
                                                <h4>Pending Verifying </h4>
                                                <p><b class="counter" id="users_pending_verifying">0</b></p>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="well">
                                    <a href="/adminio/pending/ticket" class="text-decoration">
                                        <div id="open_support_tickets_blink" class="widget-small grey p-1">
                                            <div class="info">
                                                <h4>Open Support Tickets </h4>
                                                <p><b class="counter" id="open_support_tickets">{{$ticket}}</b> (<span class="counter" id="unread_support_tickets">0</span>)</p>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <i class="fa fa-handshake-o" aria-hidden="true"></i> Deals
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-sm-2">
                                <div class="well">
                                    <a href="{{url('/adminio/openDeals')}}" class="text-decoration">
                                        <div id="open_deals_blink" class="widget-small green p-1">
                                            <div class="info">
                                                <h4>Open </h4>
                                                <p><b class="counter" id="open_deals">0</b></p>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="well">
                                    <a href="{{url('/adminio/hold/deals')}}" class="text-decoration">
                                        <div id="deals_on_hold_blink" class="widget-small blue p-1">
                                            <div class="info">
                                                <h4>On Hold</h4>
                                                <p><b class="counter" id="deals_on_hold">0</b></p>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="well">
                                    <a href="{{url('/adminio/cancelled-deals')}}" class="text-decoration">
                                        <div  id="cancelled_deals_blink" class="widget-small red p-1">
                                            <div class="info">
                                                <h4>Cancelled</h4>
                                                <p><b class="counter" id="cancelled_deals">0</b></p>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="well">
                                    <a href="{{url('/adminio/complete-deals')}}" class="text-decoration">
                                        <div  id="completed_deals_blink" class="widget-small green p-1">
                                            <div class="info">
                                                <h4>Completed</h4>
                                                <p><b class="counter" id="completed_deals">0</b></p>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="well">
                                    <a href="{{url('/adminio/expired-deals')}}" class="text-decoration">
                                        <div  id="expired_deals_blink" class="widget-small red p-1">
                                            <div class="info">
                                                <h4>Expired</h4>
                                                <p><b class="counter" id="expired_deals">0</b></p>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <i class="fa fa-clock-o" aria-hidden="true"></i> 24 Hrs Reports
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-sm-3">
                            <div class="well">
                                <a href="/adminio/24hours-deals" class="text-decoration">
                                    <div id="24_hrs_number_of_deals_blink"  class="widget-small green p-1">
                                        <div class="info">
                                            <h4>Deals</h4>
                                            <p><b class="counter" id="24_hrs_number_of_deals">0</b></p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            </div>
                            <div class="col-sm-3">
                            <div class="well">
                                <a href="{{url('/adminio/ads-24hours')}}" class="text-decoration">
                                    <div  id="24_hrs_number_of_new_ads_blink" class="widget-small blue p-1">
                                        <div class="info">
                                            <h4>New Ads</h4>
                                            <p><b class="counter" id="24_hrs_number_of_new_ads">{{$ads24}}</b></p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            </div>
                            <div class="col-sm-3">
                            <div class="well">
                                <a href="/adminio/trade24hoursHistory" class="text-decoration">
                                    <div  id="24_hrs_trade_volume_blink" class="widget-small grey p-1">
                                        <div class="info">
                                            <h4>Trade Volume</h4>
                                            <p><b class="counter" id="24_hrs_trade_volume">0</b> BTC</p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            </div>
                            <div class="col-sm-3">
                            <div class="well">
                                <a href="/adminio/users24signups" class="text-decoration">
                                    <div  id="24_hrs_new_signups_blink" class="widget-small blue p-1">
                                        <div class="info">
                                            <h4>New Signups </h4>
                                            <p><b class="counter" id="24_hrs_new_signups">{{$signups}}</b></p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <i class="icon fa fa-user"></i> Users
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-sm-4 ">
                                <div class="well ">
                                    <p>
                                        <a href="/adminio/users" class="text-decoration">
                                            <div  id="total_users_blink" class="widget-small blue p-1">
                                                <div class="info">
                                                    <span>Users</span>
                                                    <span class="value"><b class="counter" id="total_users"></b>{{$user}}</span>
                                                </div>
                                            </div>
                                        </a>
                                    </p> 
                                    <p>
                                        <a href="/adminio/marketing-users" class="text-decoration">
                                            <div  id="total_marketing_users_blink" class="widget-small grey p-1">
                                                <div class="info">
                                                    <span>Global Users</span>
                                                    <span class="value"><b class="counter" id="total_marketing_users">{{$global}}</b></span>
                                                </div>
                                            </div>
                                        </a>
                                    </p>
                                    <p>
                                        <a href="/adminio/users" class="text-decoration">
                                            <div  id="total_real_users_blink" class="widget-small grey p-1">
                                                <div class="info">
                                                    <span>Pro Users</span>
                                                    <span class="value"><b class="counter" id="total_real_users">{{$pro}}</b></span>
                                                </div>
                                            </div>
                                        </a>
                                    </p> 
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="well">
                                    <p>
                                        <a href="/adminio/users?phone=verified" class="text-decoration">
                                            <div  id="p_unverified_users_blink" class="widget-small red p-1">
                                                <div class="info">
                                                    <span>SMS UNVERIFIED</span>
                                                    <span class="value"><b class="counter" id="p_unverified_users">{{$phone_active}}</b></p>
                                                </div>
                                            </div>
                                        </a>
                                    </p> 
                                    <p>
                                        <a href="/adminio/users?email=verified" class="text-decoration">
                                            <div  id="e_unverified_users_blink" class="widget-small red p-1">
                                                <div class="info">
                                                    <span>EMAIL UNVERIFIED</span>
                                                    <span class="value"><b class="counter" id="e_unverified_users">{{$email_active}}</b></span>
                                                </div>
                                            </div>
                                        </a>
                                    </p> 
                                    <p>
                                        <a href="{{url('/adminio/users?unverified')}}" class="text-decoration">
                                            <div  id="document_unverified_users_blink" class="widget-small red p-1">
                                                <div class="info">
                                                    <span>Document Unverified Users</span>
                                                    <span class="value"><b class="counter" id="document_unverified_users">{{$unverified}}</b></span>
                                                </div>
                                            </div>
                                        </a>
                                    </p>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="well"> 
                                    <p>
                                        <a href="/adminio/users?autoverified" class="text-decoration">
                                            <div  id="auto_verified_users_blink" class="widget-small green p-1">
                                                <div class="info">
                                                    <span>Auto Verified User</span>
                                                    <span class="value"><b class="counter" id="auto_verified_users">{{$autoverified}}</b></span>
                                                </div>
                                            </div>
                                        </a>
                                    </p>
                                    <p>
                                        <a href="/adminio/users-active" class="text-decoration">
                                            <div  id="active_users_blink" class="widget-small green p-1">
                                                <div class="info">
                                                    <span>Active Users</span>
                                                    <span class="value"><b class="counter" id="active_users">{{$user_active}}</b></span>
                                                </div>
                                            </div>
                                        </a>
                                    </p>
                                    <p>
                                        <a href="/adminio/users-inactive" class="text-decoration">
                                            <div  id="deactivated_users_blink" class="widget-small red p-1">
                                                <div class="info">
                                                    <span>Deactivated Users</span>
                                                    <span class="value"><b class="counter" id="deactivated_users">{{$user_deactive}}</b></span>
                                                </div>
                                            </div>
                                        </a>
                                    </p> 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <i class="fa fa-server" aria-hidden="true"></i> System Settings
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="well">
                                    <a href="{{url('/adminio/usersonline')}}" class="text-decoration">
                                        <div id="total_active_users_online_blink"  class="widget-small green p-1">
                                            <div class="info">
                                                <h4>Active Users Online</h4>
                                                <p><b class="counter" id="total_active_users_online">0</b></p>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="well">
                                    <a href="{{url('/adminio/general-settings')}}" class="text-decoration">
                                        <div  id="base_btc_price_factor_blink" class="widget-small red p-1">
                                            <div class="info">
                                                <h4>Base BTC Price Factor</h4>
                                                <p><b class="counter" id="base_btc_price_factor">0</b>%</p>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="well">
                                    <a href="/adminio/general-settings" class="text-decoration">
                                        <div id="auto_verification_blink"  class="widget-small green p-1">
                                            <div class="info">
                                                <h4>Auto Verification</h4>
                                                <p><b class="counter" id="auto_verification">Off</b></p>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4 ">
                                <div class="well ">
                                    <p>
                                        <a href="/adminio/ads-active" class="text-decoration">
                                            <div id="total_active_ads_blink"  class="widget-small green p-1">
                                                <div class="info">
                                                    <span>Active ADs</span>
                                                    <span class="value"><b class="counter" id="total_active_ads">{{$ads_active}}</b></span>
                                                </div>
                                            </div>
                                        </a>
                                    </p> 
                                    <p>
                                        <a href="/adminio/ads-inactive" class="text-decoration">
                                            <div id="total_inactive_ads_blink"  class="widget-small red p-1">
                                                <div class="info">
                                                    <span>Inactive Ads</span>
                                                    <span class="value"><b class="counter" id="total_inactive_ads">{{$ads_inactive}}</b></span>
                                                </div>
                                            </div>
                                        </a>
                                    </p>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="well">
                                    <p>
                                        <a href="{{url('/adminio/payment-methods')}}" class="text-decoration">
                                            <div  id="total_methods_blink" class="widget-small grey p-1">
                                                <div class="info">
                                                    <span>Methods</span>
                                                    <span class="value"><b class="counter" id="total_methods">0</b></span>
                                                </div>
                                            </div>
                                        </a>
                                    </p> 
                                    <p>
                                        <a href="{{url('/adminio/currency')}}" class="text-decoration">
                                            <div  id="total_currency_blink" class="widget-small grey p-1">
                                                <div class="info">
                                                    <span>Currency</span>
                                                    <span class="value"><b class="counter" id="total_currency">{{$currency}}</b></span>
                                                </div>
                                            </div>
                                        </a>
                                    </p> 
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="well">
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <i class="icon fa fa-btc"></i> Balance
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-sm-4 ">
                                <div class="well ">
                                    <p>
                                        <a href="/adminio/withdraw-complete-requests" class="text-decoration">
                                            <div  id="all_time_withdrawals_blink" class="widget-small red p-1">
                                                <div class="info">
                                                    <span>All Time withdrawals</span>
                                                    <span class="value"><b class="counter" id="all_time_withdrawals">0</b> BTC</span>
                                                </div>
                                            </div>
                                        </a>
                                    </p> 
                                    <p>
                                        <a href="/adminio/external-transactions" class="text-decoration">
                                            <div id="all_time_deposits_blink"  class="widget-small red p-1">
                                                <div class="info">
                                                    <span>All Time Deposits </span>
                                                    <span class="value"><b class="counter" id="all_time_deposits">0</b> BTC</p>
                                                </div>
                                            </div>
                                        </a>
                                    </p>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="well">
                                    <p>
                                        <a href="/adminio/users" class="text-decoration">
                                            <div  id="total_system_balance_blink" class="widget-small green p-1">
                                                <div class="info">
                                                    <span>OverAll Balance</span>
                                                    <span class="value"><b class="counter" id="total_system_balance">0</b> BTC</span>
                                                </div>
                                            </div>
                                        </a>
                                    </p> 
                                    <p>
                                        <a href="{{url('/adminio/transactions')}}" class="text-decoration">
                                            <div  id="all_time_total_commission_blink" class="widget-small red p-1">
                                                <div class="info">
                                                    <span>All Time Commissions</span>
                                                    <span class="value"><b class="counter" id="all_time_total_commission">0</b> BTC</span>
                                                </div>
                                            </div>
                                        </a>
                                    </p>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="well"> 
                                    <p>
                                        <a href="/adminio/marketing-users" class="text-decoration">
                                            <div  id="marketing_users_balance_blink" class="widget-small blue p-1">
                                                <div class="info">
                                                    <span>Global Users Balance</span>
                                                    <span class="value"><b class="counter" id="marketing_users_balance">0</b> BTC</span>
                                                </div>
                                            </div>
                                        </a>
                                    </p> 
                                    <p>
                                        <a href="/adminio/users" class="text-decoration">
                                            <div  id="real_users_balance_blink" class="widget-small blue p-1">
                                                <div class="info">
                                                    <span>Pro Users Balance</span>
                                                    <span class="value"><b class="counter" id="real_users_balance">0</b> BTC</span>
                                                </div>
                                            </div>
                                        </a>
                                    </p> 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@elseif($dashboard_type == 'Chart')
<!-- Graphs -->
<div id="chartSection" class="row">
    <div class="col-md-12">
        <div class="tile">
            <h3 class="tile-title">
                Last 7 Days Active Transaction Time
            </h3>
            <canvas id="trxes" height="200"></canvas>
        </div>
    </div>
    <div class="col-md-12">
        <div class="tile">
            <h3 class="tile-title">
                Last 7 Days Active Withdraw Requests
            </h3>
            <canvas id="withdraws" height="200"></canvas>
        </div>
    </div>
    <div class="col-md-12">
        <div class="tile">
            <h3 class="tile-title">
                Last 7 Days Active Deposits
            </h3>
            <canvas id="deposits" height="200"></canvas>
        </div>
    </div>
</div>
@elseif($dashboard_type == 'Table')
<!-- Tables -->
<div class="row">

    <div class="col-md-12">
        <div class="tile">
            <h3 class="tile-title">
                Active Dispute Deals (Deals with status Dispute or On-Hold)
            </h3>
            <div class="tile-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover order-column" id="">
                        <thead>
                            <tr>
                                <th>Trans Id</th>
                                <th>Trans Title</th>
                                <th>Amount</th>
                                <th>Created At</th>
                                <th>Detail</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($disputed_deals as $data)
                            <tr>
                                <td>{{$data->trans_id}}</td>
                                <td>
                                    @if($data->add_type == 1) Buy Request @else Sell Request @endif
                                    from {{$data->from_user->username}} to {{$data->to_user->username}}
                                </td>
                                <td>{{$data->amount_to}} {{$data->currency->name}}</td>
                                <td>{{ Timezone::convertToLocal($data->created_at ) }}</td>
                                <td><a class="btn btn-primary" href="{{route('deal.view.admin', $data->trans_id)}}"
                                        style="color: white">Detail</a></td>
                            </tr>
                            @endforeach
                        <tbody>
                    </table>
                    {!! $disputed_deals->links() !!}
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="tile">
            <h3 class="tile-title">
                Last Active Withdrawals Requests
            </h3>
            <div class="tile-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Id</th>
                                <th scope="col">User</th>
                                <th scope="col">Amount</th>
                                <th scope="col">Fee</th>
                                <th scope="col">Status</th>
                                <th scope="col">Address</th>
                                <th scope="col">Created At</th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($active_withdraw as $key => $item)
                            <tr>
                                <td>
                                    {{ $item->id }}
                                </td>
                                <td data-label="User">
                                    <a href="{{ route('user.single', [ 'user' => $item->user->username ]) }}">
                                        {{ $item->user->username }}
                                    </a>
                                </td>
                                <td>
                                    {{ $item->amount }}
                                </td>
                                <td>
                                    {{ $item->fee }}
                                </td>
                                <td>
                                    <span class="badge @if($item->status == 'completed') badge-success @elseif($item->status == 'rejected') badge-danger @else badge-primary @endif">
                                        {{ $item->status }}
                                    </span>
                                    
                                </td>
                                <td>
                                    <a href="https://www.blockchain.com/btc/address/{{ $item->address }}" target="_blank"> {{ $item->address }} </a>
                                </td>
                                <td>
                                    {{  Timezone::convertToLocal($item->created_at ) }}
                                </td>
                                <td>
                                    @if ($item->isPending)
                                    <a href="{{ route('admin.withdraw.requests.show', [ 'withdraw' => $item->id ]) }}"
                                        class="btn btn-sm btn-primary">
                                        Process
                                    </a>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {!! $active_withdraw->links()!!}
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="tile">
            <h3 class="tile-title">
                Last Active Send Requests
            </h3>
            <div class="tile-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Id</th>
                                <th scope="col">User</th>
                                <th scope="col">Amount</th>
                                <th scope="col">Fee</th>
                                <th scope="col">Status</th>
                                <th scope="col">Address</th>
                                <th scope="col">Created At</th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($active_send as $key => $item)
                            <tr>
                                <td>
                                    {{ $item->id }}
                                </td>
                                <td data-label="User">
                                    <a href="{{ route('user.single', [ 'user' => $item->user->username ]) }}">
                                        {{ $item->user->username }}
                                    </a>
                                </td>
                                <td>
                                    {{ $item->amount }}
                                </td>
                                <td>
                                    {{ $item->fee }}
                                </td>
                                <td>
                                    <span class="badge @if($item->status == 'completed') badge-success @elseif($item->status == 'rejected') badge-danger @else badge-primary @endif">
                                        {{ $item->status }}
                                    </span>
                                    
                                </td>
                                <td>
                                    <a href="https://www.blockchain.com/btc/address/{{ $item->address }}" target="_blank"> {{ $item->address }} </a>
                                </td>
                                <td>
                                    {{  Timezone::convertToLocal($item->created_at ) }}
                                </td>
                                <td>
                                    @if ($item->isPending)
                                    <a href="{{ route('admin.withdraw.requests.show', [ 'withdraw' => $item->id ]) }}"
                                        class="btn btn-sm btn-primary">
                                        Process
                                    </a>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {!! $active_send->links()!!}
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="tile">
            <h3 class="tile-title">
                Last Active Deposits
            </h3>
            <div class="tile-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">TxID</th>
                                <th scope="col">User</th>
                                <th scope="col">Type</th>
                                <th scope="col">Address</th>
                                <th scope="col">Status</th>
                                <th scope="col">Confirmations</th>
                                <th scope="col">Amount</th>
                                <th scope="col">Fee</th>
                                <th scope="col">Created At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($active_deposits as $key => $item)
                            <tr>
                                <td>
                                    {{ $item->id }}
                                </td>
                                <td title="{{ $item->txid }}">
                                    {{ str_limit($item->txid, 10) }}
                                </td>
                                <td data-label="User">
                                    <a href="{{ route('user.single', [ 'user' => $item->user->username ]) }}">
                                        {{ $item->user->username }}
                                    </a>
                                </td>
                                <td>
                                    <span class="badge badge-info">
                                        {{ $item->type }}
                                    </span>
                                </td>
                                <td>
                                    <a href="https://www.blockchain.com/btc/address/{{ $item->address }}" target="_blank"> {{ $item->address }} </a>

                                </td>
                                <td>
                                    <span class="badge badge-{{ $item->isComplete ? 'success' : 'warning' }}">
                                        {{ $item->status }}
                                    </span>
                                </td>
                                <td>
                                    {{ $item->confirmations }}/1
                                </td>
                                <td>
                                    {{ $item->amount }}
                                </td>
                                <td>
                                    {{ $item->fee }}
                                </td>
                                <td>
                                    {{ Timezone::convertToLocal($item->created_at ) }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {!! $active_deposits->links()!!}
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="tile">
            <h3 class="tile-title">
                Last Completed Deals
            </h3>
            <div class="tile-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover order-column" id="">
                        <thead>
                            <tr>
                                <th>Trans Id</th>
                                <th>Trans Title</th>
                                <th>Amount</th>
                                <th>Created At</th>
                                <th>Detail</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($completed_deals as $data)
                            <tr>
                                <td>{{$data->trans_id}}</td>
                                <td>
                                    @if($data->add_type == 1) Buy Request @else Sell Request @endif
                                    from <a href="{{ route('user.single', [ 'user' => $data->from_user->username ]) }}">{{$data->from_user->username}}</a> to <a href="{{ route('user.single', [ 'user' => $data->to_user->username ]) }}">{{$data->to_user->username}}</a>
                                </td>
                                <td>{{$data->amount_to}} {{$data->currency->name}}</td>
                                <td>{{ Timezone::convertToLocal($data->created_at )  }}</td>
                                <td><a class="btn btn-primary" href="{{route('deal.view.admin', $data->trans_id)}}"
                                        style="color: white">Detail</a></td>
                            </tr>
                            @endforeach
                        <tbody>
                    </table>
                    {!! $completed_deals->links() !!}
                </div>
            </div>
        </div>
    </div>

<div class="col-md-12">
    <div class="tile">
        <h3 class="tile-title">
            Latest Support Tickets
        </h3>
        <div class="tile-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover order-column" id="">
                    <thead>
                        <tr>
                            <th> Ticket Id </th>
                            <th> Customer Name </th>
                            <th> Subject </th>
                            <th> Raised Time </th>
                            <th> Status </th>
                            <th> Action </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach( $active_ticket as $key=>$data)
                            @if(isset($data->user_member->username))
                            <tr>
                                <td>{{$data->ticket}}</td>
                                <td><a href="{{route('user.single', $data->user_member->username)}}"><b>{{$data->user_member->username}}</b></a></td>
                                <td><b>{{$data->subject}}</b></td>
                                <td>{{   Timezone::convertToLocal($data->created_at,'F dS, Y - h:i A') }}</td>
                                <td>
                                    @if($data->status == 1)
                                        <button class="btn btn-warning"> Opened</button>
                                    @elseif($data->status == 2)
                                        <button type="button" class="btn btn-success">  Replied </button>
                                    @elseif($data->status == 3)
                                        <button type="button" class="btn btn-info"> Customer Reply </button>
                                    @elseif($data->status == 9)
                                        <button type="button" class="btn btn-danger">  Closed </button>
                                    @endif
                                </td>
                                <td>
                                    <a class="btn btn-primary" href="{{route('ticket.admin.reply', $data->ticket )}}">View</a>
                                </td>
                            </tr>
                            @endif
                        @endforeach
                    <tbody>
                </table>
                {!! $active_ticket->links() !!}
            </div>
        </div>
    </div>
</div>
</div>
@endif

<p id="temp" style="display: none;"></p>
@endsection

@section('script')
<script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
{{-- <script type="text/javascript" src="{{ asset('assets/admin/js/Chart.min.js') }}"></script> --}}
<script>
$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    let current = new Date();
    let week = [];

    for (let i = 1; i < 8; i++) {
        current.setDate(current.getDate() - 1);
        week.push(current.toISOString().substr(0, 10));
    }

    $.ajax({
        type: 'POST',
        url: "{{ route('admin.dashboard.charts') }}",
    }).done((response) => {
        console.log(response)
        for (let key in response) {
            let days = [],
                labels = [],
                data = [];

            for (let i in week) {
                for (let asdasdasd in response[key]) {
                    if (week[i] === asdasdasd) {
                        data.push(response[key][asdasdasd].length);
                    } else {
                        data.push(0);
                    }
                }
            }

            new Chart(key, {
                type: 'bar',
                data: {
                    labels: week,
                    datasets: [{
                        label: 'Count',
                        data,
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    legend: {
                        display: false
                    },
                }
            });
        }
    }).fail((error) => {
        console.log(error);
    })
});
</script>
<script>
    // {{route('ipn.getDashboardStats')}}
    $( document ).ready(function() {
        $.ajax({url: "{{route('ipn.getDashboardStats')}}", async: true, success: function(result){
            console.log(result)
        }});
        // var old_data = {};
        // var all_requests = {};
        // var all = $(".counter").map(function() {
        //     var $item = $(this);
        //     var id = $item.attr('id');
        //     // console.log($item.attr('id'));
        //     all_requests[id] = $.ajax({url: "{{route('ipn.getDashboardStats')}}?q="+id, async: true, success: function(result){
        //         // parsing data
        //         result = JSON.parse(result);
        //         // console.log(id, result.value)
        //         if(result.status){
        //             $('#'+id).html(result.value);
        //             old_data[id] = result.value;
        //         } 
        //     }});
        //     return $item.attr('id');
        // }).get();
        // console.log(all.join());
        var audio = new Audio("{{asset('sounds/beyond-doubt.mp3')}}");
        // var isNew = true;
        // var old_data = null;
        // $.ajax({url: "{{route('ipn.getDashboardStats')}}", success: function(result){
        //     // parsing data
        //     result = JSON.parse(result);
        //     console.log(result)
        //     isNew = false;
        //     Object.keys(result).forEach(function(key) {
        //         // updating value and turning on blinker
        //         $('#'+key).html(result[key]);
        //     })
        //     old_data = result;
        // }});
        setInterval(function(){ 
            $.ajax({url: "{{route('ipn.getDashboardStats')}}", async: true, success: function(result){
                // console.log(result)
            }});
            // var all = $(".counter").map(function() {
            //     var $item = $(this);
            //     var id = $item.attr('id');
            //     // console.log($item.attr('id'));
            //     $.ajax({url: "{{route('ipn.getDashboardStats')}}?q="+id, success: function(result){
            //         // parsing data
            //         result = JSON.parse(result);
            //         // console.log(id, result.value)
            //         if(result.status){
            //             $('#'+id).html(result.value);
            //             if(old_data[id] != result.value){
            //                 if(id == "open_support_tickets" || id == "pending_withdrawals"){
            //                     $("#temp").append("Some appended text.");
            //                     audio.play(); 
            //                 }
            //                 // updating value and turning on blinker
            //                 $('#'+id+"_blink").addClass('blink_me');
            //                 //stop blinker after a certain time
            //                 setTimeout(function() {   
            //                     $('#'+id+"_blink").removeClass('blink_me');
            //                 }, {!! $Gset->counter_blinking_time !!}*1000);
            //             }
            //         } 
            //     }});
            //     return $item.attr('id');
            // }).get();
            // console.log(all.join());
            // $.ajax({url: "{{route('ipn.getDashboardStats')}}", success: function(result){
            //     // parsing data
            //     result = JSON.parse(result);

            //     // when page loads for the first time
            //     if(isNew){
            //         isNew = false;
            //         Object.keys(result).forEach(function(key) {
            //             // updating value and turning on blinker
            //             $('#'+key).html(result[key]);
            //         })
            //         old_data = result;                  
            //     }
            //     else{
            //         Object.keys(result).forEach(function(key) {
            //             if(old_data[key] != result[key]){
            //                 if(key == "open_support_tickets" || key == "pending_withdrawals"){
            //                     $("#temp").append("Some appended text.");
            //                     audio.play(); 
            //                 }
            //                 // updating value and turning on blinker
            //                 $('#'+key).html(result[key]);
            //                 $('#'+key+"_blink").addClass('blink_me');
            //                 //stop blinker after a certain time
            //                 setTimeout(function() {   
            //                     $('#'+key+"_blink").removeClass('blink_me');
            //                 }, {!! $Gset->counter_blinking_time !!}*1000);
            //             }
            //         })
            //         old_data = result;
            //     }
            // }});
         }, {!! $Gset->dashboard_refresh_time !!}*1000*60);
    });
    
</script>
<script type="text/javascript">
    var old_data = {};
    var audio = new Audio("{{asset('sounds/beyond-doubt.mp3')}}");
    var channel = Echo.channel('admin_dashboard_stats');
    console.log("channel response", channel)
    channel.listen('.updates_sindhu', function(data) {
        console.log("Listen response",data.channel.message)
        var id = data.channel.message.id;
        var value = data.channel.message.value;
        
        if($('#'+id).html() != value){
            $('#'+id).html(value);
            if(id == "open_support_tickets" || id == "pending_withdrawals"){
                $("#temp").append("Some appended text.");
                audio.play(); 
            }
            // updating value and turning on blinker
            $('#'+id+"_blink").addClass('blink_me');
            //stop blinker after a certain time
            setTimeout(function() {   
                $('#'+id+"_blink").removeClass('blink_me');
            }, {!! $Gset->counter_blinking_time !!}*1000);
        }
    
    });

</script>
@stop
