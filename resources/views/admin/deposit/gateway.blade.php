@extends('admin.layout.master')

@section('body')
    <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <div class="tile-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>SL</th>
                                <th>Icon</th>
                                <th>Coin Name</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($gateways as $k=>$gateway)
                                <tr>
                                    <td>{{ ++$k }}</td>
                                    <td><img style="height: 80px;" src="{{ asset('assets/images/gateway') }}/{{$gateway->id}}.jpg" alt=""/></td>
                                    <td>{!! $gateway->name !!}</td>
                                </tr>
                            @endforeach
                            <tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
