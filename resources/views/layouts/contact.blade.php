@extends('front.layout.master')
@section('style')
    <style>
        iframe{
            width:100% ;
            height: 100%
        }
    </style>
@stop
@section('body')
<!--bitcoin blance Strat--->
<section class="bitcoin-blacnce">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 balane">
                <p><span>CONTACT US</span></p>
            </div>
        </div>
    </div>
</section>

<!--Bitcoin Create Add Form-->
<section class="pt-60 create-add">
    <div class="container">
        <div class="row">
            <div class="col-lg-5 take-tour">
            <p>Take A Tour</p>

            <span>Take a full tour of our website and know how its work.</span>

            <div class="wrapper">
                <video class="video">
                <source src="{{ asset('new-images/bitcoin.mp4') }}" type="video/mp4" />
            </video>
                <div class="playpause1"></div>
                <div class="playpause"></div>
            </div>
        </div>
        <div class="col-lg-6 offset-lg-1">

            <form action="{{ route('contact-submit') }}" method="post">
                @csrf
                <div class="contact-us pb-30">
                    <div class="div-contact pb-20">
                    <h2 class="text-center">Contact Us</h2>
                    </div>
                    <div class="form-row Category-span1">
                        <div class="form-group col-md-12">
                            <span for="inputState" class="Select-control">Full Name </span>
                            <input type="text" class="form-control input-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="name" placeholder="Full Name" />
                            @if ($errors->has('name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-row Category-span1">
                        <div class="form-group col-md-12">
                            <span for="inputState" class="Select-control">Email </span>
                            <input type="text" class="form-control input-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="email" placeholder="Email" />
                            @if ($errors->has('email'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                        <div class="form-group col-md-12">
                            <span for="inputState" class="Select-control">Message</span>
                            <textarea class="form-control input-control-textarea" rows="5" id="comment" name="message" placeholder="Message"></textarea>
                            @if ($errors->has('message'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('message') }}</strong>
                                </span>
                            @endif
                        </div>
                        <button type="submit" class="btn btn-primary publish">Submit</button>
                    </div>
                </div>

                
            </form>
        </div>
    </div>
    </div>
</section>

<!--Bitcoin Create Add Form-->
@stop