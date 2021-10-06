@extends('admin.layout.master')

@section('css')
<style>
    fieldset
    {
        border: 1px solid #ddd !important;
        margin: 0;
        xmin-width: 0;
        padding: 10px;
        position: relative;
        border-radius:4px;
        background-color:#f5f5f5;
        padding-left:10px!important;
    }

    legend
    {
        font-size:14px;
        font-weight:bold;
        margin-bottom: 0px;
        width: 35%;
        border: 1px solid #ddd;
        border-radius: 4px;
        padding: 5px 5px 5px 10px;
        background-color: #ffffff;
    }
</style>
@endsection
@section('body')
    <div class="row">
                <div class="col-md-12">

                    <div class="tile">
                        <div class="tile-body">

                            <h3 class="tile-title">#{{$ticket_object->ticket}} - {{$ticket_object->subject}}</h3>

                            <div class="tools" style="margin-bottom: 10px;">
                                @if($ticket_object->status == 1)
                                    <button class="btn btn-warning"> Opened</button>
                                @elseif($ticket_object->status == 2)
                                    <button type="button" class="btn btn-success">  Replied </button>
                                @elseif($ticket_object->status == 3)
                                    <button type="button" class="btn btn-info"> Customer Reply </button>
                                @elseif($ticket_object->status == 9)
                                    <button type="button" class="btn btn-danger">  Closed </button>
                                @endif
                                @if($ticket_object->status != 9)
                                <a href="{{route('ticket.close', $ticket_object->ticket)}}" class="btn btn-danger pull-right" style="margin-right: 10px;" >Click To Make Close</a>
                            @endif
                            </div>
                           
                        </div>

                        <div class="tile">
                            <div class="tile-body">

                            <form method="POST" action="{{route('store.admin.reply', $ticket_object->ticket)}}" accept-charset="UTF-8" class="form-horizontal form-bordered">
                                {{csrf_field()}}
                                <div class="form-group">
                                    <div class="col-md-12">
                                        @foreach($ticket_data as $data)
                                        <div class="panel-body">
                                            <fieldset class="col-md-12">
                                                @if($data->type == 1)
                                                    <legend class="bg-info" style="color: white"><span style="color: white">    {{$ticket_object->user_member->username}}</span> , <small>{{   Timezone::convertToLocal($data->updated_at,'F dS, Y - h:i A')     }}</small></legend>
                                                 @else
                                                    <legend class="bg-danger" style="color: white"><span style="color: white">{{Auth::guard('admin')->user()->name}}</span> , <small>{{ Timezone::convertToLocal($data->updated_at,'F dS, Y - h:i A') }}</small></legend>
                                                @endif
                                                <div class="panel panel-danger">
                                                    <div class="panel-body">
                                                    <p>{!! str_replace("\n","<br/>", $data->comment) !!}</p>
                                                        @if (strpos($data->files1, '.pdf') !== false) 
                                        <embed src="{{asset('storage/images/attach/'.$data->files1)}}" type="application/pdf"   height="300px" width="100%">
                                    @else
                                    @if($data->files1 !='')
                                    <p><a href="{{asset('storage/images/attach/'.$data->files1)}}"
                                    download="">@if(isset($data->files1)) <img style="width: 180px"
                                        src="{{asset('storage/images/attach/'.$data->files1)}}"> @endif</a></p>
                            
                                        @endif
                                    @endif
                                                    </div>
                                                </div>
+
                                            </fieldset>

                                            <div class="clearfix"></div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                    <div class="form-group">
                                        <label class="col-md-12 bold">Reply: </label>
                                        <div class="col-md-12">
                                            <textarea id="area1" class="form-control" name="comment" rows="10"></textarea>
                                        </div>
                                    </div>
                                <div class="form-actions">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <button type="submit" class="btn purple col-md-12"><i class="fa fa-check"></i> Post</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            </div>

                        </div>
                    </div>
            </div>
            <!-- END PAGE CONTENT-->
        </div>
@endsection
@section('script')
    <script src="https://cdn.ckeditor.com/ckeditor5/16.0.0/classic/ckeditor.js"></script>
    <script>
    //   ClassicEditor
    //     .create( document.querySelector( '#area1' ) )
    //     .then( editor => {
    //             console.log( editor );
    //     })
    //     .catch( error => {
    //             console.error( error );
    //     });
    </script>
@stop
