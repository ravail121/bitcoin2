@extends('front.layout.master2')
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
<section class="table pt-15">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 ml-auto">
            <!-- <div class="serach"><input type="email" class="form-control input-search" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Search"><img src="images/loupe.png"></div></div> -->
            <div class="table-div datatable next complete table-responsive">

                <table id="example" class="table table-striped bit-table responsive">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Created At</th>
                            <th>Trade Type</th>
                            <th>Trading Partner</th>
                            <th>Transaction Status</th>
                            <th>Price</th>
                            <th>Trade Amount</th>
                            <th> Exchange Rate</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($addvertise as $data)
                        <tr >
                            @php
                                $deal_url = "";
                                if($data->add_type == 1){
                                    if($data->from_user_id == \Auth::id())
                                        $deal_url = "/user/deal/$data->trans_id";
                                    else
                                        $deal_url = "/user/deal-reply/$data->trans_id";
                                }
                                else{
                                    if($data->from_user_id == \Auth::id())
                                        $deal_url = "/user/deal-reply/$data->trans_id";
                                    else
                                        $deal_url = "/user/deal/$data->trans_id";
                                }
                            @endphp
                            <td><a href="{{$deal_url}}"> {{$data->trans_id}} </a> </td>
                            <td>{{  Timezone::convertToLocal($data->created_at)}}</td>
                            <td>{{$data->add_type==1 ? 'Buy':'Sell'}}</td>
                            @php 
                                $name =$data->from_user->name ;
    
                                $ff=\Auth::user()->name;
                                if($name == $ff){
                                    $name1 = $data->to_user->name;
                                    $username1 = $data->to_user->username;
                                    
                                }else{
                                    $name1 = $data->from_user->name;
                                    $username1 = $data->from_user->username;
                                }
                            @endphp
                            <td><a href="{{route('user.profile.view', $username1)}}" style="color: black"><strong>{{$username1}}</strong></a></td>
                            <!-- @if(request()->path() == 'user/close/trade' || request()->path() == 'user/open/trade')
                                <td><a href="{{route('user.profile.view', $data->to_user->username)}}" style="color: black"><strong>{{$data->to_user->username}}</strong></a></td>
                            @elseif(request()->path() == 'user/cancel/trade' ||request()->path() == 'user/complete/trade')
                                @php 
                                    $name =$data->from_user->name ;
        
                                    $ff=\Auth::user()->name;
                                    if($name == $ff){
                                        $name1 = $data->to_user->name;
                                        $username1 = $data->to_user->username;
                                        
                                    }else{
                                        $name1 = $data->from_user->name;
                                        $username1 = $data->from_user->username;
                                    }
                                @endphp
                                <td><a href="{{route('user.profile.view', $username1)}}" style="color: black"><strong>{{$username1}}</strong></a></td>
                                
                            @else
                            <td><a href="{{route('user.profile.view', $data->from_user->username)}}" style="color: black"><strong>{{$data->from_user->username}}</strong></a></td>

                            @endif -->
                            <td>
                                @if($data->status == 0)
                                    <span class="">Processing</span>
                                @elseif($data->status == 1)
                                    <span class="">Complete</span>
                                @elseif($data->status == 11)
                                    <span class="">On Hold</span>
                                @elseif($data->status == 2)
                                    <span class="">Cancelled</span>
                                @elseif($data->status == 21)
                                    <span class="">Automatically Cancelled</span>
                                @elseif($data->status == 9)
                                    <span class="">Paid</span>
                                @endif
                            </td>
                            <td>{{$data->price }} {{$data->currency->name}} / {{$data->gateway->currency}}</td>
                            <td>{{$data->amount_to }} {{$data->currency->name}} </td>
                            <td>{{round($data->coin_amount,8) }} {{$data->gateway->currency}} </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                
            </div>
            {{$addvertise->links()}}
        </div>
    </div>
</section>

<!--Bitcoin Create Add Form-->
    
@stop

@section('script')

@stop