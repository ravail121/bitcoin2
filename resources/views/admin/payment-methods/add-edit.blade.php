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
          @if(isset($paymentMethod))
            <form class="form-horizontal" method="post" action="{{ route('payment-methods.update', $paymentMethod->id) }}" enctype="multipart/form-data">
              {{method_field('put')}}

          @else
            <form class="form-horizontal" method="post" action="{{route('payment-methods.store')}}" enctype="multipart/form-data">
          @endif
              @csrf
              <div class="form-group error">
                <label for="name" class="col-sm-12 control-label bold uppercase">
                  <strong>Name :</strong>
                </label>
                <div class="col-sm-12">
                  <input type="text" class="form-control has-error bold" id="name" name="name" value="{{ isset($paymentMethod) ? $paymentMethod->name : '' }}" placeholder="Payment Method Name">
                </div>
              </div>
              <div class="form-group error">
                <label for="description" class="col-sm-12 control-label bold uppercase">
                  <strong>Description :</strong>
                </label>
                <div class="col-sm-12">
                  <input type="text" class="form-control has-error bold" id="description" name="description" value="{{ isset($paymentMethod) ? $paymentMethod->description : '' }}" placeholder="Payment Method Description">
                </div>
              </div>
              <div class="form-group error">
                <div class="col-sm-12">
                  <strong>Counrty :</strong>
                  @php
                  if(isset($paymentMethod)) {
                    $selected = [];
                    foreach ($paymentMethod->countries as $key => $value) {
                      $selected[$key] = $value->id;
                    }
                  }
                  @endphp
                  <select name="country_ids[]" id="myMultiSelect" class="form-control myMultiSelect"  multiple="multiple">
                    @foreach($countries as $key => $country)
                      @if(isset($selected))
                        <option value="{{ $country->id }}" {{ (in_array($country->id, $selected)) ? 'selected' : '' }}>{{ $country->name}}</option>
                      @else
                        <option value="{{ $country->id }}">{{ $country->name }}</option>
                      @endif
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="form-group error">
                <div class="col-sm-12">
                  <strong>Categories :</strong>
                  @php
                  if(isset($paymentMethod)) {
                    
                    $selected1 = explode(',', $paymentMethod->category_ids);
                  
                  }
                  @endphp
                  <select name="category_ids[]" id="myMultiSelect1" class="form-control myMultiSelect"  multiple="multiple">
                    @foreach($categories as $key => $category)
                      @if(isset($selected1))
                        <option value="{{ $category->id }}" {{ (in_array($category->id, $selected1)) ? 'selected' : '' }}>{{ $category->name }}</option>
                      @else
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                      @endif
                    @endforeach
                  </select>
                </div>
              </div>

              <div class="form-group error">
                <label for="question_one" class="col-sm-12 control-label bold uppercase">
                  <strong>Question One :</strong>
                </label>
                <div class="col-sm-12">
                  <input type="text" class="form-control has-error bold" id="question_one" name="question_one" value="{{ isset($paymentMethod) ? $paymentMethod->question_one : '' }}" placeholder="Payment Method Question">
                </div>
              </div>

              <div class="form-group error">
                <label for="answer_one" class="col-sm-12 control-label bold uppercase">
                  <strong>Answer  :</strong>
                </label>
                <div class="col-sm-12">
                <textarea class="form-control has-error bold" id="answer_one" name="answer_one" value="" placeholder="Payment Method Answer" cols="30" rows="4">{{ isset($paymentMethod) ? $paymentMethod->answer_one : '' }}</textarea>
                 
                </div>
              </div>




              <div class="form-group error">
                <label for="question_two" class="col-sm-12 control-label bold uppercase">
                  <strong>Question Two :</strong>
                </label>
                <div class="col-sm-12">
                  <input type="text" class="form-control has-error bold" id="question_two" name="question_two" value="{{ isset($paymentMethod) ? $paymentMethod->question_two : '' }}" placeholder="Payment Method Question">
                </div>
              </div>

              <div class="form-group error">
                <label for="answer_two" class="col-sm-12 control-label bold uppercase">
                  <strong>Answer  :</strong>
                </label>
                <div class="col-sm-12">
                <textarea class="form-control has-error bold" id="answer_two" name="answer_two" value="" placeholder="Payment Method Answer" cols="30" rows="4">{{ isset($paymentMethod) ? $paymentMethod->answer_two : '' }}</textarea>
                 
                </div>
              </div>


              <div class="form-group error">
                <label for="status" class="col-sm-12 control-label bold uppercase">
                  <strong>Status</strong>
                </label>
                <div class="col-sm-12">
                  @if(isset($paymentMethod))
                    <input data-toggle="toggle" id="status" data-onstyle="success" data-offstyle="danger" data-width="100%" type="checkbox" {{ $paymentMethod->status == 1 ? 'checked': '' }} data-on="Active" data-off="Deactive"
                    name="status">
                  @else
                    <input data-toggle="toggle" id="status" data-onstyle="success" data-offstyle="danger" data-width="100%" type="checkbox" data-on="Active" data-off="Deactive" name="status">
                  @endif
                </div>
              </div>
              <div class="modal-footer">
                <button type="submit" class="btn btn-primary bold uppercase btn-block">
                  <i class="fa fa-send"></i>
                  @if(isset($paymentMethod))
                    Update Payment Method
                  @else
                    Create Payment Method
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

  $('.myMultiSelect')
    .multiselect({
      buttonWidth: '500px',
      allSelectedText: 'All',
      maxHeight: 200,
      includeSelectAllOption: true
    })
    .multiselect('updateButtonText');
    </script>
  @endsection
