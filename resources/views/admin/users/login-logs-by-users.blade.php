@extends('admin.layout.master')

@section('body')
    <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <h3 class="tile-title pull-left">{{$page_title}}</h3>

                <div class="table-responsive">


                <table class="table table-striped  table-hover">
                    <thead>
                    <tr>
                        <th scope="col">User</th>
                        <th scope="col">IP</th>
                        <th scope="col">Location</th>
                        <th scope="col">Details</th>
                        <th scope="col">Time</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($logs as $log)
                        <tr>
                            <td data-label="Username"><a href="{{route('user.single',$log->user->username)}}">{{$log->user->name}}</a></td>
                            <td data-label="User IP">{{$log->user_ip}}</td>
                            <td data-label="User Location">{{$log->location}}</td>
                            <td data-label="Details">{{$log->details}}</td>
                            <td data-label="Time">{{ $log->created_at->diffForHumans() }}</td>
                        </tr>
                    @endforeach
                    <tbody>
                </table>
                {{ $logs->links() }}
                    </div>
            </div>
        </div>
    </div>


@endsection