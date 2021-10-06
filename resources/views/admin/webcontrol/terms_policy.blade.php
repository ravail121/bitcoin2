@extends('admin.layout.master')

@section('body')
    <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <h3 class="tile-title ">{{$page_title}}</h3>
                <div class="tile-body">
                    <form role="form" method="POST" action="{{route('terms.policy.update')}}">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-md-12">
                                <h6>Terms</h6>
                                <div class="form-group">
                                  <textarea id="termsEditor" class="form-control" rows="10" name="terms">{!! $general->terms !!}</textarea>
                                </div>

                            </div>

                            <div class="col-md-12">
                                <h6>Policy</h6>
                                <div class="form-group">
                                  <textarea id="prvEditor" class="form-control" rows="10" name="policy">{!! $general->policy !!}</textarea>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <hr/>
                            <div class="col-md-12 ">
                                <button class="btn btn-primary btn-block btn-lg">Update</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script src="https://cdn.ckeditor.com/ckeditor5/16.0.0/classic/ckeditor.js"></script>
    <script>
      ClassicEditor
        .create( document.querySelector( '#termsEditor' ) )
        .then( editor => {
                console.log( editor );
        })
        .catch( error => {
                console.error( error );
        });
      ClassicEditor
        .create( document.querySelector( '#prvEditor' ) )
        .then( editor => {
                console.log( editor );
        })
        .catch( error => {
                console.error( error );
        });
    </script>
@stop
