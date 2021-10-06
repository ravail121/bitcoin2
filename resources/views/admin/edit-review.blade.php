@extends('admin.layout.master')
@section('css')
  <style>
  button#btn_add {
    margin-bottom: 10px;
  }
    .title{
      height:500px;
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
        <form method="post" action="{{route('admin.review.save')}}">
                    @csrf
                    <input type="hidden" name="id" value="{{$review->id}}">
                    <div class="form-group ">
                                <label for="inputName" class="col-sm-12 control-label bold uppercase"><strong>Rating :</strong> </label>
                                <div class="col-sm-12">
                                    <select name="rating" class="form-control has-error bold" id="rating">
                                        <option value="-2" @if($review->rating =='-2') selected @endif>Distrusts</option>
                                        <option value="-1" @if($review->rating =='-1') selected @endif>Block</option>
                                        <option value="0" @if($review->rating =='0') selected @endif>Neutral</option>
                                        <option value="2" @if($review->rating =='2') selected @endif>Trustworthy</option>
                                        <option value="1" @if($review->rating =='1') selected @endif>Positive</option>
                                        
                                                        
                                                         


                                    </select>

                                </div>
                    </div>
                    <div class="form-group ">
                                <label for="inputName" class="col-sm-12 control-label bold uppercase"><strong>Remarks :</strong> </label>
                                <div class="col-sm-12">
                                    <textarea name="remarks" class="form-control has-error bold" id="" cols="30" rows="10">{{$review->remarks}}</textarea>
                                </div>
                    </div>


            
                
                    <button type="submit" name="status" 
                        class="btn btn-primary pull-right">Update</button>
                   
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
