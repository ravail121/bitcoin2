@extends('front.layout.master')
@section('style')

@stop
@section('body')
  <div class="row  padding-pranto-top padding-pranto-bottom">
    <div class="col-md-12">

      <div class="card">

        <div class="card-header">
          <h4>Statistics</h4>
        </div>

        <div class="card-body">

          <div class="table-responsive">
            <table class="table">
              <thead class= "">
                <tr>
                  <th>#</th>
                  <th>Created at</th>
                  <th>Trade type</th>
                  <th>Trading partner</th>
                  <th>Transaction status</th>
                  <th>Price</th>
                  <th>Trade amount</th>
                  <th>Exchange rate</th>
                  <th>...</th>
                </tr>
              </thead>
              <tbody>
                @php
                $statistics=[];
                @endphp
                @foreach($statistics as $data)
                  <tr>
                    <td>{{$data->trans_id}}</td>
                    <td>{{  Timezone::convertToLocal($data->created_at)}}</td>
                    <td>{{$data->add_type==1 ? 'Buy':'Sell'}}</td>
                    @if(request()->path() == 'user/close/trade' || request()->path() == 'user/open/trade')
                      <td><a href="{{route('user.profile.view', $data->to_user->username)}}" style="color: black"><strong>{{$data->to_user->username}}</strong></a></td>
                    @else
                      <td><a href="{{route('user.profile.view', $data->from_user->username)}}" style="color: black"><strong>{{$data->from_user->username}}</strong></a></td>
                    @endif
                    <td>
                      @if($data->status == 0)
                        <span class="label label-warning">Processing</span>
                      @elseif($data->status == 1)
                        <span class="label label-success">Complete</span>
                      @elseif($data->status == 2)
                        <span class="label label-danger">Cancelled</span>
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
        </div>
      </div>
    </div>
  </div>
@stop
