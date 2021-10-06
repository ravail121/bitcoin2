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
          @if(isset($category))
            <form class="form-horizontal" method="post" action="{{ route('categories.update', $category->id) }}" enctype="multipart/form-data">
            {{method_field('put')}}


          @else
            <form class="form-horizontal" method="post" action="{{route('save.categories')}}" enctype="multipart/form-data">
          @endif
              @csrf
              <div class="form-group error">
                <label for="name" class="col-sm-12 control-label bold uppercase">
                  <strong>Name :</strong>
                </label>
                <div class="col-sm-12">
                  <input type="text" class="form-control has-error bold" id="name" name="name" value="{{ isset($category) ? $category->name : '' }}" placeholder="Category Name">
                </div>
              </div>
              <div class="form-group error">
                <label for="description" class="col-sm-12 control-label bold uppercase">
                  <strong>Description :</strong>
                </label>
                <div class="col-sm-12">
                  <input type="text" class="form-control has-error bold" id="description" name="description" value="{{ isset($category) ? $category->description : '' }}" placeholder="Category Description">
                </div>
              </div>
              
              <div class="modal-footer">
                <button type="submit" class="btn btn-primary bold uppercase btn-block">
                  <i class="fa fa-send"></i>
                  @if(isset($category))
                    Update Category 
                  @else
                    Create Category 
                  @endif
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

  $('#myMultiSelect')
    .multiselect({
      buttonWidth: '500px',
      allSelectedText: 'All',
      maxHeight: 200,
      includeSelectAllOption: true
    })
    .multiselect('updateButtonText');
    </script>
  @endsection
