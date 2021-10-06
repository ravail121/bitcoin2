@extends('front.layout.master3')
@section('style')
<!-- //styling will be there -->


<!-- <link rel="stylesheet" property="stylesheet" href="{{ URL::asset('/css/paymentstyle/paymentmethodstyle-1.css') }}" type="text/css">
<link rel="stylesheet" property="stylesheet" href="{{ URL::asset('/css/paymentstyle/paymentmethodstyle-3.css') }}" type="text/css">
<link rel="stylesheet" property="stylesheet" href="{{ URL::asset('/css/paymentstyle/paymentmethodstyle-4.css') }}" type="text/css"> -->

@stop
@section('body')
<!--bitcoin blance Strat--->
<section class="bitcoin-blacnce">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 balane">
                <p><span>HOW TO BUY BITCOIN WITH</span></p>
            </div>
        </div>
    </div>
</section>

<!--Bitcoin Create Add Form-->
<section class="pt-60 create-add">
    <div class="container">
        <div class="row">
            <div class="col-lg-2">
                <div class="heading"><span>Buy Bitcoin With</span></div>
                <div class="tab">
                    <ul>
                        @foreach($methods as $data)
                        <li class="tablinks">
                            <i class="caret_right_icon">&#8250;</i>
                            <a href="/methods/active/{{$data->id}}"> {{$data->name}}</a>
                        </li>
                        @endforeach
                        
                    </ul>
                </div>
            </div>
            <div class="col-lg-10">
                <div id="cash" class="tabcontent">
                    <h3>Q. What are payment methods ?</h3>
                    <p>
                        Buying bitcoin can be done in many ways. We call these "payment methods". Whatever anyone will accept in return for bitcoin is a payment method. We allow members from all over the world to create their own
                        payment methods with optionally asking for more Payment details in streamlined approach. The combination is infinite!.
                    </p>
                    <p>Click on a payment method for details and instructions.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!--Bitcoin Create Add Form-->


@endsection
@section('script')
<!-- java script will be here -->
@endsection