@extends('admin.layout.master')
@section('css')
    {{-- <link rel="stylesheet" href="{{asset('assets/admin/css/table.css')}}"> --}}
    <style>
        button#btn_add {
            margin-bottom: 10px;
        }
    </style>
@stop

@section('body')
    <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <div class="tile-body">
                    <div class="table-">
                        <div class="caption font-dark" >
                            <i class="icon-settings font-dark"></i>
                            <!-- <a href="#myModal" data-toggle="modal" class="btn btn-primary pull-right bold"><i class="fa fa-plus"></i> Add Currency</a> -->
                        </div>
                        <br>
                        <br>
                        <br>

                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Name</th>
                                <th scope="col">ISO</th>
                                <th scope="col">Status</th>
                                <!-- <th scope="col">Actions</th> -->
                            </tr>
                            </thead>
                            <tbody id="products-list" name="products-list">
                            @foreach ($countries as $key => $data)
                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ $data->name }}</td>
                                    <td>{{ $data->iso }}</td>
                                    <td>
                                        @if($data->active == 1)
                                        <span class="badge badge-success">Active</span>
                                            @else
                                            <span class="badge badge-danger">Deactive</span>
                                        @endif
                                    </td>
                                </tr>

                                <!-- Modal for DELETE -->

                            @endforeach
                            </tbody>
                        </table>
                        <div class="row">
                        <div class="col-md-12 text-center">
                            {{$countries->links()}}
                        </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form class="form-horizontal" method="post" action="{{route('currency.store')}}" enctype="multipart/form-data">
                    @csrf
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel"><i class="fa fa-share-square"></i> Create Currency</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">

                        <div class="form-group error">
                            <label for="inputName" class="col-sm-12 control-label bold uppercase"><strong>Name :</strong> </label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control has-error bold " id="name" name="name" placeholder="Crypto Name">
                            </div>
                        </div>
                        <div class="form-group error">
                            <label for="inputName" class="col-sm-12 control-label bold uppercase"><strong>USD Rate:</strong> </label>
                            <div class="col-sm-12">

                                <div class="input-group">
                                    <div class="input-group-append">
                                        <span class="input-group-text">1 USD = </span>
                                    </div>
                                    <input type="text" class="form-control has-error bold " name="usd_rate">
                                </div>

                            </div>
                        </div>



                    <div class="form-group error">
                            <label for="inputName" class="col-sm-12 control-label bold uppercase"><strong>Status</strong> </label>
                            <div class="col-sm-12">
                                <input data-toggle="toggle" data-onstyle="success" data-offstyle="danger"
                                       data-width="100%" type="checkbox" data-on="Active" data-off="Deactive"
                                       name="status">
                            </div>
                        </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-info" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
                    <button type="submit" class="btn btn-primary bold uppercase"><i class="fa fa-send"></i> Save Crypto</button>

                </div>
                </form>
            </div>
        </div>

    </div>


@stop
