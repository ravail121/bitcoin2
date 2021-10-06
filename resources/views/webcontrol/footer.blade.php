@extends('admin.layout.master')
@section('import-css')
<link href="{{ asset('assets/admin/css/bootstrap-fileinput.css') }}" rel="stylesheet">
@stop
@section('body')
<div class="row">
  <div class="col-md-12">
    <div class="tile">
      <div class="tile-body">

        {!! Form::model($basic,['route'=>['manage-footer-update'],'method'=>'PUT','role'=>'form','class'=>'form-horizontal','files'=>true]) !!}

        <div class="row">
          <div class="col-md-12">


            <div class="form-group{{ $errors->has('copyright') ? ' has-error' : '' }}">
              <label class="col-md-12"><strong style="text-transform: uppercase;">Copyright Text</strong></label>
              <div class="col-md-12">
                <textarea name="copyright" id="area1" class="form-control" required>{{ $basic->copyright }}</textarea>
                @if ($errors->has('email'))
                <span class="help-block">
                  <strong>{{ $errors->first('email') }}</strong>
                </span>
                @endif
              </div>
            </div>

            <div class="form-group">
              <div class="col-md-12">
                <button type="submit" class="btn btn-primary btn-block btn-lg"><i class="fa fa-send"></i> UPDATE</button>
              </div>
            </div>
          </div>
        </div><!-- row -->

        {!! Form::close() !!}
      </div>
    </div>
  </div>
</div>


@stop

@section('import-script')
<script src="{{ asset('assets/admin/js/bootstrap-fileinput.js') }}"></script>
@stop
