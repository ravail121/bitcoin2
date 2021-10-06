@extends('admin.layout.master')

@section('body')
    <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <h3 class="tile-title ">{{$page_title}}</h3>
                <div class="tile-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover order-column" id="">
                            <thead>
                            <tr>
                                <th>Username</th>
                                <th>ID</th>
                                <th>Amount</th>
                                <th>End Balance</th>
                                <th>Charge</th>
                                <th>Detail</th>
                                <th>Created At</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($trans as $data)
                                <tr>
                                    <td>
                                        <a href="{{route('user.single', $data->user->username)}}">
                                            {{$data->user->username}} 
                                        </a>
                                    </td>

                                    @if(strpos($data->deal_url, 'deal') !== false)
                                    <td><a href="{{route('deal.view.admin', abs(filter_var($data->deal_url, FILTER_SANITIZE_NUMBER_INT)))}}">{{filter_var($data->trx, FILTER_SANITIZE_NUMBER_INT)}}</a></td>
                                    @else
                                    <td>{{filter_var($data->trx, FILTER_SANITIZE_NUMBER_INT)}}</td>
                                    @endif
                                    <td>{{$data->amount}}</td>
                                    <td>{{ number_format((float)$data->main_amo, 8, '.', '') }} BTC</td>
                                    <td>{{ number_format((float)$data->charge, 8, '.', '') }} BTC</td>
                                    @if(strpos($data->title, 'Sell to') !== false || strpos($data->title, 'Buy from') !== false)
                                    @php
                                    $title = explode(' ',$data->title);
                                    @endphp
                                    <td>{{$title[0]}} {{$title[1]}} <a href="{{route('user.single', $title[2])}}">{{$title[2]}}</a></td>
                                    @else
                                    <td>{{$data->title}}</td>
                                    @endif
                                    <td>{{  Timezone::convertToLocal($data->created_at)  }}</td>
                                </tr>
                            @endforeach
                            <tbody>
                        </table>
                        {{$trans->links()}}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection