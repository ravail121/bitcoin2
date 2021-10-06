@extends('admin.layout.master')
@section('css')
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
                    <div class="table-responsive">
                        <div class="caption font-dark" >
                            <i class="icon-settings font-dark"></i>
                            <a href="{{route('payment-methods.create')}}" class="btn btn-primary pull-right bold">
                              <i class="fa fa-plus"></i> Add Payment Method
                            </a>
                        </div>
                        <br>
                        <br>
                        <br>

                        <table class="table  table-hover myTable">
                            <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Name</th>
                                {{-- <th scope="col">Icon</th> --}}
                                <th scope="col">Status</th>
                                <th scope="col">Actions</th>
                            </tr>
                            </thead>
                            <tbody id="products-list" name="products-list">
                            @foreach ($payment as $key => $data)
                                <tr>
                                    <td>{{ $data->id }}</td>
                                    <td>{{ $data->name }}</td>
                                    {{-- <td><img style="width: 50px; height: 50px" src="{{ Storage::url($data->icon) }}"> </td> --}}
                                    <td>
                                        @if($data->status == 1)
                                        <span class="badge badge-success">Active</span>
                                            @else
                                            <span class="badge badge-danger">Deactive</span>
                                        @endif
                                    </td>
                                    <td >
                                        <a href="{{route('payment-methods.edit', $data->id)}}" class="btn btn-primary bold uppercase"><i class="fa fa-edit"></i> EDIT</a>
                                    </td>
                                </tr>

                                <!-- Modal for DELETE -->

                            @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
@stop
