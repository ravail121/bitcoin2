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
                                <th>After Balance</th>
                                <th>Charge</th>
                                <th>Detail</th>
                                <th>Created At</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($deposits as $data)
                                <tr>
                                    <td>
                                        <a href="{{route('user.single', $data->user->username)}}">
                                            {{$data->user->username}}
                                        </a>
                                    </td>

                                    <td>{{$data->trx}}</td>
                                    <td>{{$data->amount}}</td>
                                    <td>{{$data->main_amo}}</td>
                                    <td>{{$data->charge}}</td>
                                    <td>{{$data->title}}</td>
                                    <td>{{ Timezone::convertToLocal($data->created_at)    }}</td>
                                </tr>
                            @endforeach
                            <tbody>
                        </table>

                        {!! $deposits->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection