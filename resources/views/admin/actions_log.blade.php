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
                                <th>ID</th>
                                <th>User IP</th>
                                <th>Country</th>
                                <th>Browser</th>
                                <th>Platform</th>
                                <th>Action</th>
                                <th>Created At</th>                            
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($trans as $data)

                                <tr>
                                    <td>{{$data->id}}</td>
                                    <td>{{$data->user_ip}}</td>
                                    <td class="@if($data->is_country_changed == 1) text-danger @endif">{{$data->country_name}}</td>
                                    <td>{{$data->browser}}</td>
                                    <td>{{$data->platform}}</td>
                                    <td>{{$data->action}}</td>
                                    <td> {{ Timezone::convertToLocal($data->created_at ,'Y-m-d H:i:s') }}</td>
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