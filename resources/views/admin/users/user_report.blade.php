@extends('admin.layout.master')

@section('body')

    <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <h3 class="tile-title pull-left">User List</h3>
                <div class="pull-right icon-btn">
                    <form method="POST" class="form-inline" action="{{route('search.users')}}">
                        {{csrf_field()}}
                        <input type="text" name="search" class="form-control" placeholder="Search">
                        <button class="btn btn-outline btn-circle  green" type="submit"><i
                                    class="fa fa-search"></i></button>

                    </form>
                </div>

                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col">Name</th>
                            <th scope="col">Email</th>
                            <th scope="col">Username</th>
                            <th scope="col">Mobile</th>
                            <th scope="col">Details</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td data-label="Name">{{$user->name}}</td>
                                <td data-label="Email">{{$user->email}}</td>
                                <td data-label="Username">{{$user->username}}</td>
                                <td data-label="Mobile">{{$user->phone or 'N/A'}}</td>
                                <td  data-label="Details">
                                    <a href="{{route('user.single', $user->username)}}"
                                       class="btn btn-outline-primary ">
                                        <i class="fa fa-eye"></i> View </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    {!! $users->render()!!}
                </div>
            </div>
        </div>
    </div>



@endsection