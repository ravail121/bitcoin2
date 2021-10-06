@extends('admin.layout.master')
@section('body')
<div class="row">
  <div class="col-md-12">
    <div class="tile">
      <div class="tile-title">
        <div class="pull-right">
          <a href="{{ route('menu-create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Create New Menu</a>
        </div>
      </div>
      <br><br>

      <div class="tile-body">
        <div class="row">
          @foreach($menus as $m)
          <div class="col-md-6">
            <div class="text-center"><b>{{ $m->name }}</b></div>
            <br>
            <p class="text-center">
              {!! $m->description !!}
            </p>
            <div class="col-md-12">
              <a href="{{ route('menu-edit',$m->id) }}" class="btn btn-info"><i class="fa fa-edit"></i> Edit Menu </a>
              <button type="button" class="btn btn-danger delete_button" data-toggle="modal" data-target="#DelModal" data-id="{{ $m->id }}">
                <i class='fa fa-trash'></i> Delete Menu
              </button>
            </div>

          </div>
          @endforeach
        </div>
      </div>
      {!! $menus->links() !!}
    </div>
  </div>
</div>



<div class="modal fade" id="DelModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="myModalLabel"> <i class='fa fa-trash'></i> Delete !</h4>
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      </div>

      <div class="modal-body">
        <strong>Are you sure you want to Delete ?</strong>
      </div>

      <div class="modal-footer">
        <form id="deleteMenuForm" method="post" action="">
          {!! csrf_field() !!}
          {{ method_field('DELETE') }}
          <button type="button" class="btn btn-info" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-danger">DELETE</button>
        </form>
      </div>

    </div>
  </div>
</div>


@stop

@section('script')
<script>
  $(document).ready(function() {
    $(document).on("click", '.delete_button', function(e) {
      let id = $(this).data('id');
      $('#deleteMenuForm').attr('action', `/adminio/menu-delete/${id}`);
    });
  });
</script>
@stop
