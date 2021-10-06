@extends('admin.layout.master')
@section('style')
<style>
#preloader {
    position: fixed;
    left: 0px;
    top: 0px;
    width: 100%;
    height: 100%;
    z-index: 9999;
    background: url('/images/pageLoader.gif') 50% 50% no-repeat rgb(249, 249, 249);
    opacity: .8;
}
</style>
@stop
@section('body')
<div id="preloader"></div>
<div class="row">

    <div class="col-md-6">
        <div class="tile">
            <h4 class="tile-title">
                <i class="fa fa-user"></i> @if($trans->add_type == 1) Buy Request @else Sell Request @endif
                from <a href="{{route('user.single', $trans->from_user->username)}}"> {{$trans->from_user->username}} </a> to
                <a href="{{route('user.single', $trans->to_user->username)}}"> {{$trans->to_user->username}} </a> AD <a href="{{route('search.ads') . '?add_id=' . $trans->advertisement_id}}"> {{$trans->advertisement_id}} </a></h4>
            <div class="tile-body">
                <hr>
                <h5>Trans ID: {{$trans->trans_id}}</h5>
                <h5 class="bold" id="statusP">Transaction status:
                    @if($trans->status == 0)
                    <span class="badge  badge-warning"> Processing </span>
                    @elseif($trans->status == 1)
                    <label class="badge  badge-success"> Paid Complete </label>
                    @elseif($trans->status == 9)
                    <span class="badge  badge-info"> Paid </span>
                    @elseif($trans->status == 10)
                    <span class="badge  badge-info"> Dispute </span>

                    @elseif($trans->status == 2)
                    <span class="badge  badge-danger"> Cancelled </span>
                    @elseif($trans->status == 21)
                      <span class="badge  badge-danger"> Automatically Cancelled </span>
                                       
                    @elseif($trans->status == 11)
                    <span class="badge  badge-warning"> On Hold </span>
                    @endif
                </h5><div class="holdButton">
                @if($trans->status == 0 || $trans->status == 11)
                
                <input class="form-control" data-toggle="toggle" data-onstyle="success"
                                        data-offstyle="danger" data-width="100%" data-off="Put On Hold" data-on="On Hold"
                                        type="checkbox"  name="hold" id="hold"
                                        {{ $trans->status == "11" ? 'checked' : '' }}>
                @endif</div>                        
                <h5 class="bold">Requested At : {{ Timezone::convertToLocal($trans->created_at )  }}</h5>
                @if($trans->status == 10)
                <a class="btn btn-primary btn-block" href="{{route('adminconfirm.paid', $trans->id)}}"> Release the bitcoin to the buyer</a>
                <a class="btn btn-secondary btn-block" href="{{route('adminconfirm.cancel', $trans->id)}}"> Return the bitcoin to the seller</a>
                @elseif($trans->status == 0 || $trans->status == 1 || $trans->status == 9 || $trans->status == 11)
                <a class="btn btn-danger btn-block" href="{{route('adminconfirm.dispute', $trans->id)}}"> Mark as Disputed</a>
                @endif
                <hr>
                <p>
                    <strong>Updated At : {{ Timezone::convertToLocal($trans->updated_at)    }}</strong>
                    <br>
                </p>
            </div>
        </div>

    </div>



    <div class="col-md-6">
        <div class="tile">
            <h4 class="tile-title">Price: {{$trans->price}} {{$trans->currency->name}}/{{$trans->gateway->currency}}
            </h4>
            <div class="tile-body">
                <hr>
                <h5 class="bold">Amount In {{$trans->currency->name}} : {{ $trans->amount_to }} {{$trans->currency->name}}</h5>
                <h5 class="bold">Amount In USD : {{ $trans->usd_amount }} USD</h5>
                <h5 class="bold">Amount In {{$trans->gateway->currency}} : {{ round($trans->coin_amount,8) }}
                    {{$trans->gateway->currency}}</h5>
                <hr>
                <p>
                    <strong>Gateway : {{$trans->gateway->name }}</strong><br>
                    <strong>Payment Method : {{$trans->paymentMethod->name }}</strong><br>
                    <strong>Currency Type : {{$trans->currency->name }}</strong><br>

                </p>
            </div>
        </div>

    </div>


    <div class="col-md-12">
        <div class="tile">
            <h4 class="tile-title">Terms & Payment Details</h4>
            <div class="tile-body">
                <hr>
                <h5 class="bold">Terms detail :</h5>
                {!! $trans->term_detail !!}
                <hr>
                <h5 class="bold">Payment detail :</h5>
                {!! $trans->payment_detail !!}
            </div>
        </div>

    </div>
    <div class="col-md-12">
        <div class="card">
        <div class="card">

<div class="card-header">
    Advertisement <span>#{{$trans->trans_id}}</span>
</div>
<form id="uploadDetail" class="form-horizontal" enctype="multipart/form-data">
    @csrf
    <div class="card-body">

        <div class="form-group">
            <strong><i class="fa fa-comment"></i> Send Messages to : <a style="color: blue"
                    href="{{route('user.profile.view', $trans->from_user->username)}}">{{$trans->from_user->username}}
                    </a>,
                    <a style="color: blue"
                    href="{{route('user.profile.view', $trans->to_user->username)}}">{{$trans->to_user->username}}
                    </a>
                    </strong>
            <textarea name="message" class="form-control" id="message"
                rows="3">{!! old('detail') !!}</textarea>
        </div>

        <div class="form-group" id="pranto">
            <input type="file" class="form-control-file" name="image">
            <small class="col-md-12"><i class="fa fa-picture-o"></i> Attach document (PNG , JPG and JPEG
                files only, take a screenshot if necessary):</small>
        </div>
    </div>
    <div class="card-footer">
        <button type="button" id="submit" class="btn btn-secondary"><i
                class="fa fa-paper-plane-o"></i>Send</button>
    </div>
</form>

</div>
            <div class="card-header">
                <strong class="col-md-12">Messages </strong>
            </div>

            <div class="card-body">
                <div id="oww" class="oww">
                    @foreach($trans->conversation->reverse() as $data)
                    <div class="col-md-12">
                       
                            @if($data->type == 0)
                            <div class="alert alert-danger">
                            <strong>Admin :</strong>
                            <p><a href="{{asset('storage/images/attach/'.$data->image)}}"
                                    download="">@if(isset($data->image)) <img style="width: 180px"
                                        src="{{asset('storage/images/attach/'.$data->image)}}"> @endif</a></p>
                            <p>{!! str_replace("\n","<br/>",str_replace(" ","&nbsp",$data->deal_detail)) !!}</p>
                            <p class="pull-right" style="margin: -10px;; margin-right:10px"> @if($data->read_message == 'read')<i class="fa fa-envelope-open-o" aria-hidden="true"></i> @else <i class="fa fa-envelope" aria-hidden="true"></i> @endif {{   Timezone::convertToLocal($data->created_at,'Y-m-d H:i:s' )     }}</p>


                            @elseif($data->type == $trans->from_user_id)
                            <div class="alert alert-info">
                            <strong>{{$trans->from_user->username }} :</strong>
                            <p><a href="{{asset('storage/images/attach/'.$data->image)}}"
                                    download="">@if(isset($data->image)) <img style="width: 180px"
                                        src="{{asset('storage/images/attach/'.$data->image)}}"> @endif</a></p>
                            <p>{!! str_replace("\n","<br/>",str_replace(" ","&nbsp",$data->deal_detail)) !!}</p>
                            <p class="pull-right" style="margin: -10px;">  {{  Timezone::convertToLocal($data->created_at,'Y-m-d H:i:s' )    }}</p>


                            @elseif($data->type == $trans->to_user_id)
                            <div class="alert alert-success">
                            <strong>{{$trans->to_user->username }} :</strong>
                            <p><a href="{{asset('storage/images/attach/'.$data->image)}}"
                                    download="">@if(isset($data->image)) <img style="width: 180px"
                                        src="{{asset('storage/images/attach/'.$data->image)}}"> @endif</a></p>
                            <p>{!! str_replace("\n","<br/>",str_replace(" ","&nbsp",$data->deal_detail)) !!}</p>
                            <p class="pull-right" style="margin: -10px;">  {{  Timezone::convertToLocal($data->created_at,'Y-m-d H:i:s' )    }}</p>



                            @endif

                            
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>



</div>

@endsection
@section('script')
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/moment.min.js"></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.20.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.14/moment-timezone.min.js"></script>

<script>
$(document).ready(function() {
    $('#hold').change(function(){
    if(this.checked) {
       
        $.ajax({
            type: "GET",
            url: "{{route('admin.deal_hold',[$trans->id,11])}}",
            success: function(data1) {
                swal("Status Updated", "", "success");
                location.reload();

            }
        })
    }
    else {
        $.ajax({
            type: "GET",
            url: "{{route('admin.deal_hold',[$trans->id,0])}}",
            success: function(data1) {
                swal("Status Updated", "", "success");
                location.reload();


            }
        })
    }
});


    $("#preloader").hide();
    $('#submit').click(function(e) {

$("#preloader").show();


e.preventDefault();
var id = "{{$trans->id}}";
var message = $('#message').val();

if (message == '' ||message == ' ') {
    alert('message field is required');
    return true;
}
var profileForm = $('#uploadDetail')[0];
var formData = new FormData(profileForm);

formData.append('id', id);
formData.append('message', message);
formData.append('_token', "{{csrf_token()}}");


$.ajax({
    type: "POST",
    url: "{{route('admin.send.message')}}",
    data: formData,
    cache: false,
    contentType: false,
    processData: false,
    success: function(data) {
        var user_id= "{{\Auth::user()->id}}";
        var user_name= "{{\Auth::user()->username}}";
        $("#pranto").load(location.href + " #pranto");
        $('#message').val(' ');
        console.log(data);
        var type = data.type;
        console.log(type)
        console.log(data.from_name)
        console.log(image)
        var details = data.deal_detail;
        var details = details.replace(/[\r\n]+/g,"<br>");
        if (data.image) {
            var image = data.image;
        } else {
            var image = null
        }
        var string = '<div class="col-md-12">';
        if (type == 0) {
            var mess = "danger";
            var name = user_name;
        
        }
        string += '<div class="alert alert-' + mess + '">';

        string += '<strong>' + name + '  :</strong> ';
        if (image) {
            var url = "{{ asset('storage/images/attach/') }}";
            string += '<p><a href="' + url + '/' + image +
                '" download=""> <img style="width: 180px" src="' + url + '/' +
                image + '"></a></p>';
        }
        
        string += '<p>' + details + '</p><p class="pull-right" style="margin: -10px;; margin-right:10px">';
                    if(type == 0){
                        if(data.read_message == 'read'){
                            string +=' <i class="fa fa-envelope-open-o" aria-hidden="true"></i>';

                        }
                        else{
                            string +=  ' <i class="fa fa-envelope" aria-hidden="true"></i> ';

                        }
                    }
                    // moment.utc("2018-01-22 04:09:31").local();
                    // var format ='YYYY-MM-DD hh:mm:ss a';
                    // var date = moment.utc(data.created_at).local();
                    string +=  data.created_at+'</p></div></div>';
                    
        $('#oww').prepend(string);
        $("#preloader").hide();
    }
})


});
})
$(window).ready(function() {
    setInterval(yourAjaxCall, 30000);

    function yourAjaxCall() {
        $.ajax({
            type: "GET",
            url: "{{route('admin.deal_messages.get',$trans->id)}}",
            success: function(data1) {


                if (data1.status == 0) {
                    $('#statusP').html(
                        'Transaction status:<span class="badge badge-warning"> Processing </span>'
                        );
                        $('.holdButton').show();
                    
                }

                if (data1.status == 1) {
                    $('#statusP').html(
                        'Transaction status:<span class="badge badge-success"> Paid Complete </span>'
                        );
                        $('.holdButton').hide();
                }
                if (data1.status == 9) {
                    $('#statusP').html(
                        'Transaction status:<span class="badge badge-info"> Paid </span>'
                        );
                        $('.holdButton').hide();
                }
                if (data1.status == 10) {
                    $('#statusP').html(
                        'Transaction status:<span class="badge badge-info"> Dispute </span>');
                        $('.holdButton').hide();
                }
                if (data1.status == 2) {
                    $('#statusP').html(
                        'Transaction status:<span class="badge badge-danger"> Cancelled </span>');
                        $('.holdButton').hide();
                }




                $('#oww').html('');
                var user_id= "{{\Auth::user()->id}}";
                var user_name= "{{\Auth::user()->username}}";
                $.each(data1.msgs, function(key, data) {
                     console.log(data);
                    var type = data.type;
                    var details = data.deal_detail;
                    var details = details.replace(/[\r\n]+/g,"<br>");
                    if (data.image) {
                        var image = data.image;
                    } else {
                        var image = null
                    }
                    var string = '<div class="col-md-12">';
                    if( type == 0){
                        var mess = "danger";
                        var name = 'Admin';
                    }else if(type == data.from_user_id){
                               
                        var mess = "info";
                        var name = data.from_name;

                                
                    } else {
                        var mess = "success";
                        var name = data.to_name;
                    }
                    

                    
                    string += '<div class="alert alert-' + mess + '">';

                    string += '<strong>' + name + '  :</strong> ';
                    if (image) {
                        var url = "{{ asset('storage/images/attach/') }}";
                        string += '<p><a href="' + url + '/' + image +
                            '" download=""> <img style="width: 180px" src="' + url + '/' +
                            image + '"></a></p>';
                    }
                    string += '<p>' + details + '</p><p class="pull-right" style="margin: -10px;; margin-right:10px">';
                    if(type == 0){
                        if(data.read_message == 'read'){
                            string +=' <i class="fa fa-envelope-open-o" aria-hidden="true"></i>';

                        }
                        else{
                            string +=  ' <i class="fa fa-envelope" aria-hidden="true"></i> ';

                        }
                    }
                    // var format ='YYYY-MM-DD hh:mm:ss a';
                    // var date = moment.utc(data.created_at).local();
                    string +=  data.created_at+'</p></div></div>';
                   
                    $('#oww').append(string);
                })
                return;

                // console.log(data);


            }


        });

    }
});
</script>


@endsection