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
                            <a href="{{route('admins.create')}}" class="btn btn-primary pull-right bold">
                                <i class="fa fa-plus"></i> Add new admin
                            </a>
                        </div>
                        <br>
                        <br>
                        <br>

                        <table class="table table-hover myTable">
                            <thead>
                            <tr>
                                <th scope="col">Name</th>
                                <th scope="col">Email</th>
                                <th scope="col">Username</th>
                                
                                <th scope="col">Mobile</th>
                                <th scope="col">Actions</th>
                            </tr>
                            </thead>
                            <tbody id="products-list" name="products-list">
                            @foreach ($admins as $key => $data)
                                <tr>
                                <td data-label="Name">
                                   
                                    {{$data->name}}
                                    
                                </td>
                                <td data-label="Email">{{$data->email}}</td>
                                <td data-label="Username">
                                    
                                    {{$data->username}}
                                    
                                </td>
                                
                                @if($data->mobile)
                                <td data-label="Mobile">{{$data->mobile }}</td>
                                @else
                                <td data-label="Mobile"> N/A</td>
                                @endif
                                    
                                    <td >
                                        <a href="{{route('admins.edit', $data->id)}}" class="btn btn-primary bold uppercase"><i class="fa fa-edit"></i> EDIT</a>
                                        <a href="{{route('admins.delete', $data->id)}}" class="btn btn-danger bold uppercase"><i class="fa fa-remove"></i> DELETE</a>
                                    
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
