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
                <p><span>How to buy bitcoin with {{$active->name}}</span></p>
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
                        <li class="tablinks @if($active->id == $data->id) active @endif">
                            <i class="caret_right_icon">&#8250;</i>
                            <a href="/methods/active/{{$data->id}}"> {{$data->name}}</a>
                        </li>
                        @endforeach
                        
                    </ul>
                </div>
            </div>
            <div class="col-lg-10">
                <div id="cash" class="tabcontent">
                <div id="faqs" class="faqs">


                    <div id="faqs-list" class="fancy-title title-bottom-border">
                        <h3> <b>{{$active->name}}</b>  community tips</h3>
                    </div>

                    <ul class="iconlist faqlist">
                        <li><svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-caret-right-fill"
                                fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M12.14 8.753l-5.482 4.796c-.646.566-1.658.106-1.658-.753V3.204a1 1 0 0 1 1.659-.753l5.48 4.796a1 1 0 0 1 0 1.506z" />
                            </svg><strong><a href="#faq-1" data-scrollto="#faq-1">{{$active->question_one}} ?
                                </a></strong></li>
                        <li><svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-caret-right-fill"
                                fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M12.14 8.753l-5.482 4.796c-.646.566-1.658.106-1.658-.753V3.204a1 1 0 0 1 1.659-.753l5.48 4.796a1 1 0 0 1 0 1.506z" />
                            </svg><strong><a href="#faq-99" data-scrollto="#faq-99">{{$active->question_two}}
                                    ?</a></strong></li>
                    </ul>
                    <div class="divider"><i class="fa fa-circle"></i></div>

                    <p>Now you can also use <b>{{$active->name}}</b> to buy bitcoins on Bitcoin.ngo.
                        Bitcoin.ngo makes the process
                        of purchasing BTC with <b>{{$active->name}}</b> whole lot simpler. Pay with
                        <b>{{$active->name}}</b> to
                        have bitcoin in your Bitcoin.ngo wallet as soon as the card is verified.<br />
                        <br />
                        You can either buy from one of many offers listed by vendors for selling their BTC using
                        <b>{{$active->name}}</b> or create your own offer to sell your bitcoin in
                        <b>{{$active->name}}</b>
                        balance. At Bitcoin.ngo, vendors can set their own rates and decide their
                        margins. Select the
                        offer that suits you the most while buying and create offers that have the best chance
                        of converting.<br />
                        <br />
                    </p>

                    <div class="divider"></i></div>

                    <h3 id="faq-1"><svg width="1em" height="1em" viewBox="0 0 16 16"
                            class="bi bi-caret-right-fill" fill="currentColor"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M12.14 8.753l-5.482 4.796c-.646.566-1.658.106-1.658-.753V3.204a1 1 0 0 1 1.659-.753l5.48 4.796a1 1 0 0 1 0 1.506z" />
                        </svg><strong>Q.</strong> {{$active->question_one}} ? </h3>
                    <p>
                        <em>{!!$active->answer_one!!}</em>
                    </p>

                    <div class="divider"><i class="fa fa-circle"></i></div>


                    <h3 id="faq-99"><svg width="1em" height="1em" viewBox="0 0 16 16"
                            class="bi bi-caret-right-fill" fill="currentColor"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M12.14 8.753l-5.482 4.796c-.646.566-1.658.106-1.658-.753V3.204a1 1 0 0 1 1.659-.753l5.48 4.796a1 1 0 0 1 0 1.506z" />
                        </svg><strong>Q.</strong> {{$active->question_two}} ? </h3>
                    <p>
                        <em>{!!$active->answer_two!!}</em>
                    </p>


                    <div class="clear"></div>

                    </div>
                    <div class="divider"><i class="fa fa-circle"></i></div>

                    <!---Form Sction-->
                    <div class="card">
                        <form method="POST" action="/submit-advice"
                            accept-charset="UTF-8" class="nobottommargin" id="adviceform" name="adviceform">
                            @csrf
                            <div class="form-row Category-span1">
                                <div class="form-group col-md-12">
                                    <input type="hidden" name="method_id" value="{{$active->id}}">
                                    <span for="inputState" class="Select-control">Name </span>
                                    <input type="text" name="username" class="form-control input-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Email" required/>
                                </div>
                            </div>
                                <div class="form-group col-md-12">
                                    <span for="inputState" class="Select-control">Advice</span>
                                    <textarea class="form-control input-control-textarea" id="advice" name="advice" rows="5" placeholder="Message" required></textarea>
                                </div>
                            <button type="submit" class="btn btn-primary publish">Submit Advice</button>
                        </form>
                    </div>
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

