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
                        <!-- <div class="caption font-dark" >
                            <i class="icon-settings font-dark"></i>
                            <a href="{{route('methods.categories')}}" class="btn btn-primary pull-right bold">
                              <i class="fa fa-plus"></i> Add Category
                            </a>
                        </div> -->
                        <br>
                        <br>
                        <br>

                        <table class="table table-hover myTable">
                            <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Name</th>
                                <th scope="col">Advice</th>
                                <th scope="col">Status</th>
                                <th scope="col">Actions</th>
                            </tr>
                            </thead>
                            <tbody id="products-list" name="products-list">
                            @foreach ($advises as $key => $data)
                                <tr>
                                    <td>{{ $data->id }}</td>
                                    <td>{{ $data->username }}</td>
                                    <td>{{  (strlen($data->advice) > 45) ? substr($data->advice,0,42).'...' : $data->advice }}</td>
                                    <td>@if($data->status == 2)
                                            <button class="btn btn-warning"> Rejected</button>
                                        @elseif($data->status == 1)
                                            <button type="button" class="btn btn-success">  Accepted </button>
                                        @elseif($data->status == 0)
                                            <button type="button" class="btn btn-info"> Pending </button>
                                        @endif
                                    </td>
                                    <td >
                                        <a href="{{route('advises.edit', $data->id)}}" class="btn btn-primary bold uppercase"><i class="fa fa-edit"></i> EDIT</a>
                                        <a href="{{route('advises.delete', $data->id)}}" class="btn btn-danger bold uppercase"><i class="fa fa-remove"></i> DELETE</a>
                                    
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
