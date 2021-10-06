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
      @if ($errors->any())
        <div class="alert alert-danger">
          <ul>
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif
      <div class="tile">
        <div class="tile-body">
         
            <form class="form-horizontal" method="post" action="{{route('advises.update',$category->id)}}" enctype="multipart/form-data">
          
              @csrf
              <div class="form-group error">
                <label for="name" class="col-sm-12 control-label bold uppercase">
                  <strong>Name :</strong>
                </label>
                <div class="col-sm-12">
                  <input type="text" class="form-control has-error bold" id="name" name="username" value="{{  $category->username  }}" placeholder=" Name">
                </div>
              </div>
              <div class="form-group error">
                <label for="description" class="col-sm-12 control-label bold uppercase">
                  <strong>Advice :</strong>
                </label>
                <div class="col-sm-12">
                  <input type="text" class="form-control has-error bold" id="description" name="advice" value="{{  $category->advice  }}" placeholder="Advice">
                </div>
              </div>
              <div class="form-group error">
                <label for="description" class="col-sm-12 control-label bold uppercase">
                  <strong>Status :</strong>
                </label>
                <div class="col-sm-12">
                <select name="status" id="status" class="form-control">
                    <option value="0" @if($category->status ==0 ) selected @endif>Pending</option>
                    <option value="1" @if($category->status ==1 ) selected @endif>Accept</option>
                    <option value="2" @if($category->status ==2 ) selected @endif>Reject</option>
                </select>
                </div>
              </div>
              
              <div class="modal-footer">
                <button type="submit" class="btn btn-primary bold uppercase btn-block">
                  <i class="fa fa-send"></i>
                 
                    Update Advice 
                  
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  @stop
  @section('script')
    <script>
    // $("#myMultiSelect").bsMultiSelect();
    </script>
  @endsection
