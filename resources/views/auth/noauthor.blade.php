@extends('front.layout.master')
@section('style')
 
@stop
@section('body')
   <div class="row padding-pranto-top padding-pranto-bottom">
       @if (Auth::user()->status != '1')
       <div class="col-md-12">
           <h3 style="color: #cc0000; text-align:center" >Your account is Deactivated</h3>
       </div>

       @elseif(Auth::user()->email_verify != '1')

           <div class="col-md-6">
               <div class="card">
                   <form action="{{route('sendemailver')}}" method="POST">
                       @csrf
                   <div class="card-body">
                       <div class="form-group">
                            <input type="email" class="form-control" readonly value="{{Auth::user()->email}}" >
                       </div>
                   </div>
                   <div class="card-footer">
                       <button type="submit" class="btn btn-primary btn-block">Send Verification Code</button>
                   </div>
                   </form>
               </div>
           </div>
           <div class="col-md-6 margin-top-pranto">
               <div class="card">
                   <form action="{{route('emailverify') }}" method="post">
                       @csrf
                   <div class="card-body">
                       <div class="form-group">
                           <input type="text" class="form-control" name="code" placeholder="Enter Verification Code" required >
                       </div>
                   </div>
                   <div class="card-footer">
                       <button type="submit" class="btn btn-primary btn-block">Verify</button>
                   </div>
                    </form>
                </div>
           </div>


       @elseif(Auth::user()->phone_verify != '1')

           <div class="col-md-6">

               <div class="card">
                   <form action="{{route('sendsmsver')}}" method="POST">
                       @csrf
                   <div class="card-body">
                       <div class="form-group">
                           <input type="text" readonly class="form-control" value="{{Auth::user()->phone}}" >
                       </div>

                   </div>

                   <div class="card-footer">
                       <button type="submit" class="btn btn-primary btn-block">Send Verification Code</button>
                   </div>
                   </form>
               </div>
           </div>
           <div class="col-md-6 margin-top-pranto">
               <div class="card">
                   <form action="{{route('smsverify')}}" method="POST">
                       @csrf
                       <div class="card-body">
                           <div class="form-group">
                               <input class="form-control" type="text" name="code" placeholder="Enter Verification Code" required >
                           </div>

                       </div>

                       <div class="card-footer">
                           <button type="submit" class="btn btn-primary btn-block">Verify</button>
                       </div>
                   </form>
               </div>
           </div>

       @elseif(Auth::user()->tfver != '1')

           <div class="col-md-8 offset-md-2">
              <div class="jumbotron">
                  <div class="card">
                      <form action="{{route('go2fa.verify') }}" method="POST">
                          @csrf
                          <div class="card-body">
                              <div class="form-group">
                                  <input type="number" class="form-control" name="code" required placeholder="Enter Google Authenticator Code">
                              </div>
                          </div>
                          <div class="card-footer">
                              <button type="submit" class="btn btn-primary btn-block">Verify</button>
                          </div>

                      </form>
                  </div>
              </div>
           </div>
       @endif

   </div>
@endsection
