@extends('admin.layout.master')
@section('css')

@stop
@section('body')
    <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <div class="tile-body">
                    <form class="form-horizontal" action="{{ route('menu-update',$menu->id) }}" method="post"
                          role="form">

                        {!! csrf_field() !!}
                        <div class="form-body">

                            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                <label class="col-md-12"><strong style="text-transform: uppercase;">Menu
                                        Name</strong></label>
                                <div class="col-md-12">
                                    <input class="form-control input-lg" value="{{ $menu->name }}" name="name"
                                           placeholder="" type="text" required>
                                    @if ($errors->has('name'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-12"><strong
                                            style="text-transform: uppercase;">CONTENT</strong></label>
                                <div class="col-md-12">
                                    <textarea id="area1" class="form-control" rows="15"
                                              name="description">{{ $menu->description }}</textarea>
                                </div>
                            </div>
                            <br>
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary btn-block btn-lg"><i class="fa fa-send"></i>
                                    UPDATE MENU
                                </button>
                            </div>


                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>




@stop

@section('script')
    <script src="https://cdn.ckeditor.com/ckeditor5/16.0.0/classic/ckeditor.js"></script>
    <script>
      ClassicEditor
        .create( document.querySelector( '#area1' ) )
        .then( editor => {
                console.log( editor );
        })
        .catch( error => {
                console.error( error );
        });
    </script>
@stop
