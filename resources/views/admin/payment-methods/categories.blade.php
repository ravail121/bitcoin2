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
                            <a href="{{route('methods.categories')}}" class="btn btn-primary pull-right bold">
                              <i class="fa fa-plus"></i> Add Category
                            </a>
                        </div>
                        <br>
                        <br>
                        <br>

                        <table class="table table-hover myTable">
                            <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Name</th>
                                <th scope="col">Actions</th>
                            </tr>
                            </thead>
                            <tbody id="products-list" name="products-list">
                            @foreach ($categories as $key => $data)
                                <tr>
                                    <td>{{ $data->id }}</td>
                                    <td>{{ $data->name }}</td>
                                    
                                    <td >
                                        <a href="{{route('categories.edit', $data->id)}}" class="btn btn-primary bold uppercase"><i class="fa fa-edit"></i> EDIT</a>
                                        <a href="{{route('categories.delete', $data->id)}}" class="btn btn-danger bold uppercase"><i class="fa fa-remove"></i> DELETE</a>
                                    
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
