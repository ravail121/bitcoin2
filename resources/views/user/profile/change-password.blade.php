@extends( 'front.layout.master2' )
@section('style')

@stop
@section('body')


<!--Section: Login Form-->
<section class="mb-5 change-form">
          <div class="logo-header text-center">
            
             <p>Edit Your Profile Password</p>
             
           </div>
           <div class="login-content pt-30">
            <form method="post" action="{{ route('user.update-password', Auth::user()->username) }}">
                @if (count($errors) > 0)
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-danger alert-dismissible">
                                <!-- <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> -->
                                <h12><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Alert!</h12>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
                @csrf
                @method('put')
                <div class="showing-span">
                  <div class="form-row login-span row">
                            <div class="form-group col-md-12">
                                <span for="inputState" class="Select-control">Old Password</span>
                                 <input type="Password" class="form-control change-control" id="exampleInputEmail1" aria-describedby="emailHelp"  name="passwordold" placeholder="Old Password" />
                            </div>
                            
                        </div>
                       
                         <div class="form-row login-span row">
                            
                            <div class="form-group col-lg-12">
                                <span for="inputState" class="Select-control">New Password</span>
                                 <input type="Password" class="form-control change-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="password" placeholder="New Password" />
                            </div>
                             
                        </div>
                       
                           
                             
                         <div class="form-row login-span row">
                            
                            <div class="form-group col-lg-12">
                                <span for="inputState" class="Select-control">Confirm Password</span>
                                 <input type="Password" class="form-control change-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="password_confirmation" placeholder="Confirm Password" />
                            </div>
                             
                              
                        </div>
                       
                            <button type="submit" class="btn btn-primary publish">Update</button>


                    </form>
                  </div>
      </section>
<!--Section: Login Form-->
@stop