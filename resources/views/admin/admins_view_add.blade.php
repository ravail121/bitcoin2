@extends('admin.layout.master')

@section('body')
    <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <div class="tile-body">
                    @if(isset($admin))
                        <form class="form-horizontal" role="form" action="{{route('admins.editsave',$admin->id)}}" method="post" enctype="multipart/form-data">
                    @else
                        <form class="form-horizontal" role="form" action="{{route('admins.save')}}" method="post" enctype="multipart/form-data">
                    @endif   
                        @csrf
                       
                        <div class="form-body">
                            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                <label class="col-md-2 offset-1 control-label"><b>Name</b></label>
                                <div class="col-md-9 offset-1">
                                    <div class="input-group">
                                        <input type="text" name="name" value="@if(isset($admin)){{$admin->name}}@endif" class="form-control input-lg"
                                               placeholder="Your Name">
                                        <div class="input-group-append"><span class="input-group-text"><i class="fa fa-user"></i></span></div>
                                    </div>
                                    @if ($errors->has('name'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
                                <label class="col-md-2 offset-1 control-label"><b>Email</b></label>
                                <div class="col-md-9 offset-1">
                                    <div class="input-group">
                                        <input type="email" name="email" value="@if(isset($admin)){{$admin->email}}@endif" class="form-control input-lg"
                                               placeholder="Your Email">
                                        <div class="input-group-append"><span class="input-group-text"><i class="fa fa-envelope"></i></span></div>
                                    </div>
                                    @if ($errors->has('email'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('mobile') ? ' has-error' : '' }}">
                                <label class="col-md-2 offset-1 control-label"><b>Mobile</b></label>
                                <div class="col-md-9 offset-1">
                                    <div class="input-group">
                                        <input type="text" name="mobile" value="@if(isset($admin)){{$admin->mobile}}@endif" class="form-control input-lg"
                                               placeholder="Your Mobile">
                                        <div class="input-group-append"><span class="input-group-text"><i class="fa fa-phone"></i></span></div>
                                    </div>
                                    @if ($errors->has('mobile'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('mobile') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 offset-1 control-label"><b>Profile</b></label>
                                <div class="col-md-9 offset-1">
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                    @if(isset($admin))
                                        @if( $admin->image == null)
                                            <div class="fileinput-new thumbnail" style="width: 215px; height: 215px;" data-trigger="fileinput">
                                                <img style="width: 215px" src="{{ asset('assets/images/user/user-default.jpg') }}/" alt="...">
                                            </div>
                                        @else
                                            <div class="fileinput-new thumbnail" style="width: 215px; height: 215px;" data-trigger="fileinput">
                                                <img style="width: 215px" src="{{ asset($admin->image) }}" alt="...">
                                            </div>
                                        @endif
                                        @endif
                                        <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 215px; max-height: 215px"></div>
                                        <div>
                                                <span class="btn btn-info btn-file">
                                                    <span class="fileinput-new bold uppercase"><i class="fa fa-file-image-o"></i> Select image</span>
                                                    <span class="fileinput-exists bold uppercase"><i class="fa fa-edit"></i> Change</span>
                                                    <input type="file" name="image" accept="image/*">
                                                </span>
                                            <a href="#" class="btn btn-danger fileinput-exists bold uppercase" data-dismiss="fileinput"><i class="fa fa-trash"></i> Remove</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                            <label class="col-md-2 offset-1 control-label"><b>Role</b></label>
                                <div class="col-md-9 offset-1">
                                    <div class="input-group">
                                       
                                        <select name="role" id="role" required class="form-control input-lg">
                                            <option value="">Select role for user</option>
                                            @foreach($roles as $role)
                                                <option value="{{$role->id}}" @if( isset($admin) && $admin->role == $role->id) selected @endif >{{$role->name}} </option>
                                            @endforeach
                                        </select>       
                                        <div class="input-group-append"><span class="input-group-text"><i class="fa fa-user"></i></span></div>
                                    </div>
                                    @if ($errors->has('role'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('mobile') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                            <label class="col-md-2 offset-1 control-label"><b>Password</b></label>
                                <div class="col-md-9 offset-1">
                                    <div class="input-group">
                                    @if(isset($admin))
                                        <input type="password" name="password" value="" class="form-control input-lg"
                                               placeholder=" password">
                                    @else
                                    <input type="password" required name="password" value="" class="form-control input-lg"
                                               placeholder=" password">
                                    @endif           

                                        <div class="input-group-append"><span class="input-group-text"><i class="fa fa-lock"></i></span></div>
                                    </div>
                                    @if ($errors->has('password'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                             <div class="col-md-offset-2 col-md-9 offset-1">
                                    <button type="submit" class="btn  btn-block btn-primary btn-lg">Submit</button>
                                </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

@stop
