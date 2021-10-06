@extends('admin.layout.master')
@section('body')
<div class="row">
  <div class="col-md-12">
    <div class="tile">
      <div class="tile-body">
        {!! Form::model($basic,['route'=>['contact-setting-update',$basic->id],'method'=>'PUT','role'=>'form','class'=>'form-horizontal','files'=>true]) !!}


        <div class="form-group">
          <label class="col-10 offset-1"><strong style="text-transform: uppercase;">Contact
              Phone</strong></label>
          <div class="col-10 offset-1">
            <div class="input-group">
              <input type="text" name="phone" class="form-control bold input-lg" value="{{ $basic->phone }}" required>
              <div class="input-group-append"><span class="input-group-text"><i class="fa fa-phone"></i></span></div>
            </div>
          </div>
        </div>

        <div class="form-group">
          <label class="col-10 offset-1"><strong style="text-transform: uppercase;">Contact
              Email</strong></label>
          <div class="col-10 offset-1">
            <div class="input-group">
              <input type="text" name="email" class="form-control bold input-lg" value="{{ $basic->email }}" required>
              <div class="input-group-append"><span class="input-group-text"><i class="fa fa-envelope-open"></i></span></div>
            </div>
          </div>
        </div>

        <div class="form-group">
          <label class="col-10 offset-1"><strong style="text-transform: uppercase;">Contact
              Address</strong></label>
          <div class="col-md-10 col-sm-12 offset-1">
            <div class="input-group">
              <input type="text" name="address" class="form-control bold input-lg" value="{{ $basic->address }}" required>
              <div class="input-group-append"><span class="input-group-text"><i class="fa fa-map-marker"></i></span></div>
            </div>
          </div>
        </div>

        <div class="form-group">
          <div class="col-10 offset-1">
            <button type="submit" class="btn btn-primary btn-block btn-lg"><i class="fa fa-send"></i> UPDATE
            </button>
          </div>
        </div>
        {!! Form::close() !!}
      </div>
    </div>
  </div>
</div>
@stop
