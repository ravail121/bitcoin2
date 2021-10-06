@extends('admin.layout.master')

@section('body')
    <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <h3 class="tile-title ">{{$page_title}}</h3>
                <div class="tile-body">
                    <form role="form" method="POST" action="{{route('send.email')}}" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <input type="hidden" name="id" value="{{$user->id}}" />
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><strong>To</strong></label>
                                        <input type="email" name="emailto" class="form-control input-lg" value="{{$user->email}}" readonly >
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><strong>Name</strong></label>
                                        <input type="text" name="reciver" class="form-control input-lg" value="{{$user->name}}"  readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label><strong>Subject</strong></label>
                                <input type="text" name="subject" class="form-control input-lg" value="">
                            </div>
                            <div class="form-group">
                                <label><strong>Email Message</strong></label>
                                <textarea class="form-control" name="emailMessage" rows="10">

                                                </textarea>
                            </div>
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="submit-btn btn btn-primary btn-lg btn-block login-button">Send Email</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection