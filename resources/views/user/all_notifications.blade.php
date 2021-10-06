@extends('front.layout.master2')
@section('style')
<style>
.bg-secondary {
    background-color: #6c757d2e !important;
}
</style>
@stop
@section('body')
@if (count($errors) > 0)
<div class="col-md-12">
    <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <strong class="col-md-12"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Alert!</strong>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </div>

</div>
@endif
<!--bitcoin blance Strat--->
<section class="bitcoin-blacnce">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 balane">
                <span>All Notifications</span>
            </div>
        </div>
    </div>
</section>


<!--bitcoin blance Strat--->


<!--Bitcoin Create Add Form-->
<section class="pt-60 create-support">
    <div class="container">
        <div class="row">
            <div class="form-div Notifications">
                <div class="row">
                    <div class="col-4"></div>
                    <div class="col-4">{{$messages->links()}}</div>
                    <div class="col-4"></div>
                </div>
                
                <div class="container">
                    @foreach($messages as $message)
                    @php
                    if($message->read_message == null){
                        $color="bg-secondary";
                    }else{
                        $color="";
                    }
                    @endphp
                    <div class="row Congratulation">
                        <div class="col-lg-6">
                            <a class="cong-accc" href="/notification-read/{{$message->id}}/{{Auth::user()->username}}">{{$message->message}}</a>
                        </div>
                        <div class="col-lg-2 offset-lg-4">
                            <span class="date-cong">{{\Carbon\Carbon::createFromTimeStamp(strtotime($message->created_at))->diffForHumans()}}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="row">
                    <div class="col-4"></div>
                    <div class="col-4">{{$messages->links()}}</div>
                    <div class="col-4"></div>
                </div>
            </div>
        </div>
    </div>
</section>


@stop

@section('script')

@stop