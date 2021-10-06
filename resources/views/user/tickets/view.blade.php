@extends('front.layout.master2')

@section('style')

@endsection
@section('body')
<section class="bitcoin-blacnce">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 balane">
                <div class="row">
                    <div class="col-8"><p><span>View Ticket</span> </p></div>
                    <div class="col-4">
                        Status: 
                        @if($ticket_object->status == 1)
                            <span > Opened</span>
                        @elseif($ticket_object->status == 2)
                            <span >  Replied </span>
                        @elseif($ticket_object->status == 3)
                            <span > Customer Reply </span>

                        @elseif($ticket_object->status == 9)
                            <span >  Closed </span>
                        @endif
                        @if($ticket_object->status != 9)
                            <a href="{{route('ticket.close', $ticket_object->ticket)}}" class="btn btn-danger pull-right" style="margin-left: 10px;" >Close it</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<div class="alert alert-info text-center" role="alert">
    #{{$ticket_object->ticket}} - {{$ticket_object->subject}}  
</div>
<section class="table pt-30">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                
                <span class="buy"> Message</span>
                <div class="message-left oww" id="oww">
                    @foreach($ticket_data as $data)
                        @if($data->type != 1)
                            <div class="col-md-12 message-inner  admin">
                                <div class="content-message">
                                    <p>Support Team :</p>
                                    @if (strpos($data->files1, '.pdf') !== false) 
                                        <embed src="{{asset('storage/images/attach/'.$data->files1)}}" type="application/pdf"   height="300px" width="100%">
                                    @else
                                        @if($data->files1 !='')
                                            <p> 
                                                <a href="{{asset('storage/images/attach/'.$data->files1)}}" download="">@if(isset($data->files1)) <img style="width: 180px" src="{{asset('storage/images/attach/'.$data->files1)}}"> @endif</a>
                                            </p>
                                        @endif
                                    @endif
                                    <p class="message-text">{!! str_replace("\n","<br/>",str_replace(" ","&nbsp",$data->comment)) !!}</p>
                                </div>
                                <span>{{ Timezone::convertToLocal($data->updated_at ,'Y-m-d H:i:s')   }}</span>
                            </div>
                        @else
                            <div class="col-md-12 message-inner reciver">
                                <div class="content-message">
                                    <p>{{Auth::user()->username}} :</p>
                                    @if (strpos($data->files1, '.pdf') !== false) 
                                        <embed src="{{asset('storage/images/attach/'.$data->files1)}}" type="application/pdf"   height="300px" width="100%">
                                    @else
                                        @if($data->files1 !='')
                                            <p> 
                                                <a href="{{asset('storage/images/attach/'.$data->files1)}}" download="">@if(isset($data->files1)) <img style="width: 180px" src="{{asset('storage/images/attach/'.$data->files1)}}"> @endif</a>
                                            </p>
                                        @endif
                                    @endif
                                    <p class="message-text">{!! str_replace("\n","<br/>",str_replace(" ","&nbsp",$data->comment)) !!}</p>
                                </div>
                                <span>{{ Timezone::convertToLocal($data->updated_at ,'Y-m-d H:i:s')   }}</span>
                            </div>
                        @endif
                    @endforeach
                
                </div>
            </div>
        </div>
    </div>
</section>
<section class="pt-60 create-add">
    <div class="container">
        <div class="row">
            <form method="POST" action="{{route('store.customer.reply', $ticket_object->ticket)}}" accept-charset="UTF-8" enctype="multipart/form-data">
                @csrf
                <div class="form-div pb-30 message-send">
                    <div class="form-group col-md-12">
                        <span for="inputState" class="Select-control">Send Message to <span class="fitness">Support Team :</span></span>
                        <textarea class="form-control input-control1" rows="5" placeholder="" name="comment" id="message">{!! old('comment') !!}</textarea>
                    </div>
                    <div class="row pt-30">
                        <div class="col-lg-9 choose-file-invoice">
                            <label for="getFile" id="getFileName" class="custom-file-upload name-file">
                                Choose File
                            </label>
                        
                            <input id="getFile" type="file" name="files" accept="image/*" style="display:none;" />
                            <span class="uplod-doc">   <span class="upload">Upload Document </span>(PNG , JPG and JPEG files only, take a screenshot if necessary)</span>
                        </div>
                        <div class="col-lg-2 pt-20">
                            <button type="submit" id="submit" class="btn btn-primary send">Send</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
<script src="{{ asset('new-js/3.5.1-jquery.min.js')}}"></script>
<script>
    $('#getFile').change(function() {
        var i = $(this).prev('label').clone();
        var file = $('#getFile')[0].files[0].name;
        $(this).prev('label').text(file);
        $('#getFileName').text(file);
    });
</script>
@endsection