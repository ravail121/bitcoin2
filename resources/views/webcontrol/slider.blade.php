@extends('admin.layout.master')
@section('body')

<div class="row">
  <div class="col-md-12">
      <div class="tile">
        <h1>{{$page_title}}</h1>
          <div class="tile-body">
              <div class="table-">
                  <div class="caption font-dark" >
                      <i class="icon-settings font-dark"></i>
                      <a href="#myModal" data-toggle="modal" class="btn btn-primary pull-right bold"><i class="fa fa-plus"></i> Add Slider</a>
                  </div>
                  <br>
                  <br>
                  <br>

                  <table class="table table-bordered table-hover">
                      <thead>
                      <tr>
                          <th scope="col">ID</th>
                          <th scope="col">Title</th>
                          <th scope="col">Description</th>
                          <th scope="col">Action</th>
                          <!-- <th scope="col">Actions</th> -->
                      </tr>
                      </thead>
                      <tbody id="products-list" name="products-list">
                      @foreach ($sliders as $key => $data)
                          <tr>
                              <td>{{ $key+1 }}</td>
                              <td>{{ $data->title }}</td>
                              <td>{{ $data->sub_title }}</td>
                              <td>
                              <a href="#deleteModal" data-toggle="modal" data-id="{{$data->id}}" data-src="{{ asset('assets/images/slider') }}/{{$data->image}}" class="delete_button btn btn-danger bold"><i class="fa fa-minus"></i> Remove Slider</a>
                              </td>
                          </tr>

                          <!-- Modal for DELETE -->

                      @endforeach
                      </tbody>
                  </table>
              </div>

          </div>
      </div>
  </div>
</div>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
      <div class="modal-content">
          <form class="form-horizontal" method="post" action="{{route('slider-store')}}" enctype="multipart/form-data">
              @csrf
          <div class="modal-header">
              <h4 class="modal-title" id="myModalLabel"><i class="fa fa-share-square"></i> Add New Slider</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
          </div>
          <div class="modal-body">

            <div class="form-group">
              <label class="col-md-12"><strong style="text-transform: uppercase;">Slider Main Title</strong></label>
              <div class="col-md-12">
                <input name="title" type="text" class="form-control input-lg" placeholder="Slider  Title" required />
              </div>
            </div>

            <div class="form-group">
              <label class="col-md-12"><strong style="text-transform: uppercase;">Slider Sub Title</strong></label>
              <div class="col-md-12">
                <input name="sub_title" type="text" class="form-control input-lg" placeholder="Slider Sub-title" required />
              </div>
            </div>

            <div class="form-group">
              <label class="col-md-12"><strong style="text-transform: uppercase;">Slider Video</strong></label>
              <div class="col-md-12">
              <!-- src="{{ asset('assets/images/slider') }}/file_name" -->
                <input name="image" type="file" accept=".mp4" class="form-control input-lg" />
                <!-- <img class="center-block" alt="" style="margin-top: 20px;margin-bottom: 10px;width:100%;"> -->
                <code><b style="color:red; font-weight: bold;margin-top: 10px">ONE VIDEO ONLY | Video should be in .mp4 format </b></code>
              </div>
            </div>

          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-info" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
              <button type="submit" class="btn btn-primary bold uppercase"><i class="fa fa-send"></i> Add Slider</button>

          </div>
          </form>
      </div>
  </div>

</div>

<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog">
      <div class="modal-content">
          <form class="form-horizontal" method="post" action="{{route('slider-delete')}}" enctype="multipart/form-data">
              @csrf
          <div class="modal-header">
              <h4 class="modal-title" id="myModalLabel"><i class="fa fa-share-square"></i> Confirm Delete</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
          </div>
          <div class="modal-body">
          <div class="form-group">
              <div class="col-md-12">
                <input name="id" type="hidden" id="id" required />
              </div>
            </div>

            <div class="form-group">
              <label class="col-md-12"><strong style="text-transform: uppercase;">Are you sure that you wanna delete this Slider Video?</strong></label>
              <div class="col-md-12">
              <!-- src="{{ asset('assets/images/slider') }}/file_name" -->
                <!-- <img id="my_image" class="center-block" alt="" style="margin-top: 20px;margin-bottom: 10px;width:100%;"> -->
              </div>
            </div>

          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-info" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
              <button type="submit" class="btn btn-danger bold uppercase"><i class="fa fa-send"></i> Delete Slider</button>

          </div>
          </form>
      </div>
  </div>

</div>
@stop
@section('script')
<script>
  $(document).ready(function() {
    $(document).on("click", '.delete_button', function(e) {
      var id = $(this).data('id');
      $("#id").val(id);
      // $("#my_image").attr("src",$(this).data('src'));
    });
  });
</script>
@stop
