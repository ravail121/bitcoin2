@extends('front.layout.master2')
@section('style')

@stop
@section('body')
<!--bitcoin blance Strat--->
<section class="bitcoin-blacnce">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 balane">
                <p><span>Transaction History</span></p>
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
                <!-- <div class="serach"><input type="email" class="form-control input-search" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Search" /><img src="images/loupe.png" /></div> -->
            </div>
            <div class="table-div datatable next table-responsive">
                <table id="example" class="table table-striped bit-table responsive">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Start Balance</th>
                            <th>Amount</th>
                            <th>Charge</th>
                            <th>End Balance</th>
                            <th>Details</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($trans as $data)

                    <tr>
                        @if(strpos($data->deal_url, 'deal') !== false)
                            <td><a href="{{$data->deal_url}}" class="text-decoration-none">{{filter_var($data->trx, FILTER_SANITIZE_NUMBER_INT)}}</a></td>
                        @elseif(strpos($data->deal_url, 'deposit') !== false)
                            <td><a href="{{$data->deal_url}}" class="text-decoration-none">{{filter_var($data->trx, FILTER_SANITIZE_NUMBER_INT)}}</a></td>
                        @else
                            <td>{{filter_var($data->trx, FILTER_SANITIZE_NUMBER_INT)}}</td>
                        @endif
                        <td>{{$data->pre_main_amo}}</td>
                        <td>{{$data->amount}}</td>
                        <td>{{$data->charge}}</td>
                        <td>{{$data->main_amo}}</td>
                        @if(strpos($data->deal_url, 'deal') !== false)
                            <td><a href="{{$data->deal_url}}" class="text-decoration-none">{{$data->title}}</a></td>
                        @elseif(strpos($data->deal_url, 'deposit') !== false)
                            <td><a href="{{$data->deal_url}}" class="text-decoration-none">{{$data->title}}</a></td>
                        @else
                            <td>{{$data->title}}</td>
                        @endif
                        <td> {{ Timezone::convertToLocal($data->created_at ,'Y-m-d H:i:s') }}</td>
                    </tr>

                    @endforeach
                    </tbody>
                </table>
            </div>
            {{$trans->links()}}
        </div>
    </div>
</section>

<!--Bitcoin Create Add Form-->




@stop

@section('script')

@stop