@extends('front.layout.master2')
@section('style')
<style>
    #preloader {
        position: fixed;
        left: 0px;
        top: 0px;
        width: 100%;
        height: 100%;
        z-index: 99999;
        background: url('/images/pageLoader.gif') 50% 50% no-repeat rgb(249, 249, 249);
        opacity: .8;
    }

    a {
        color: black !important;
    }

    .wrap-text{
        white-space: normal;
        overflow-wrap: break-word;
    }

    .modal-content{
        min-width: fit-content;
    }

    .modal-content > img{
        width: 100%;
    }
</style>
@stop
@section('body')
<style>
    #preloader {
        position: fixed;
        left: 0px;
        top: 0px;
        width: 100%;
        height: 100%;
        z-index: 99999;
        background: url('/images/pageLoader.gif') 50% 50% no-repeat rgb(249, 249, 249);
        opacity: .8;
    }
</style>
<div id="preloader"></div>

@php
    $name =$add->to_user->username;

    $ff=\Auth::user()->username;
    if($name == $ff){
        $name1 = $add->from_user->username;
        $note_to = $add->from_user->id;
    }else{
        $name1 = $add->to_user->username;
        $note_to = $add->to_user->id;
    }
@endphp
<!--bitcoin blance Strat--->
<section class="bitcoin-blacnce">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 buyer">
                @if($add->add_type ==2)
                <p>
                    <span>
                        Deal <span class="bold-text">{{$add->trans_id}}</span> in response to AD <span class="bold-text">@if($add->advertiser_id ==Auth::id())<a href="{{route('sell_buy.edit', ['advertise' => $add->advertisement_id, 'username' => auth()->user()->username])}}">{{$add->advertisement_id}}</a></b> @else <a href="{{ route('view', ['id'=>$add->advertisement_id, 'payment'=> Replace($add->paymentMethod->name)]) }}">{{$add->advertisement_id}}</a></b> @endif</span> from Seller <span class="bold-text"><a href="{{route('user.profile.view', $add->from_user->username)}}">{{$add->from_user->username}}</a></span> to Buyer
                        <span class="bold-text"><a href="{{route('user.profile.view', $add->to_user->username)}}">{{$add->to_user->username}}</a></span>
                    </span>
                </p>
                @else
                <p>
                    <span>
                        Deal <span class="bold-text">{{$add->trans_id}}</span> in response to AD <span class="bold-text">@if($add->advertiser_id ==Auth::id())<a href="{{route('sell_buy.edit', ['advertise' => $add->advertisement_id, 'username' => auth()->user()->username])}}">{{$add->advertisement_id}}</a></b> @else <a href="{{ route('view', ['id'=>$add->advertisement_id, 'payment'=> Replace($add->paymentMethod->name)]) }}">{{$add->advertisement_id}}</a></b> @endif</span> from BUYER <span class="bold-text"><a href="{{route('user.profile.view', $add->from_user->username)}}">{{$add->from_user->username}}</a></span> to SELLER
                        <span class="bold-text"><a href="{{route('user.profile.view', $add->to_user->username)}}">{{$add->to_user->username}}</a></span>
                    </span>
                </p>
                @endif
            </div>
        </div>
    </div>
</section>

<!--bitcoin blance Strat--->

@if (count($errors) > 0)
<div class="col-md-12">
    <div class="alert alert-danger alert-dismissible">
        <!-- <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> -->
        <strong class="col-md-12"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Alert!</strong>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </div>
</div>
@endif

<!--Bitcoin Create Add Form-->
<section class="pt-60 create-add">
    <div class="container">
        <div class="row">
            <form id="uploadDetail" enctype="multipart/form-data">
                @csrf
                <div class="form-div pb-30 message-send">
                    <div class="form-group col-md-12">
                        <span for="inputState" class="Select-control">Send Message to <span class="fitness">{{$name1}} :</span></span>
                        <textarea class="form-control input-control1" rows="5" placeholder="" name="message" id="message">{!! old('detail') !!}</textarea>
                    </div>
                    <div class="row pt-30">
                        <div class="col-lg-9 choose-file-invoice">
                            <label for="getFile" id="getFileName" class="custom-file-upload name-file">
                                Choose File
                            </label>
                        
                            <input id="getFile" type="file" name="image" accept="image/*" style="display:none;" />
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

<!--Bitcoin Create Add Form-->
<!--Bitcoin Create Add Form-->
<section class="table pt-30">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                
                <span class="buy"> Message</span>
                <div class="message-left oww" id="oww">
                    @foreach($add->conversation->reverse() as $data)
                        @if($data->type == 0)
                            <div class="col-md-12 message-inner  admin">
                                <div class="content-message">
                                    <p>Admin :</p>
                                    <p><a href="{{asset('storage/images/attach/'.$data->image)}}" download="">@if(isset($data->image)) <img style="width: 180px" src="{{asset('storage/images/attach/'.$data->image)}}"> @endif</a></p>
                                    <p class="message-text">{!! str_replace("\n","<br/>",$data->deal_detail) !!}</p>
                                </div>
                                <br/>
                                <span>{{ Timezone::convertToLocal($data->created_at ,'Y-m-d H:i:s')   }}</span>
                            </div>
                        @else
                            <div class="col-md-12 message-inner  {{$data->type  != \Auth::user()->id? 'reciver':'sender' }}">
                                <div class="content-message">
                                    <p>@if($data->type != \Auth::user()->id){{$name1}} @else {{Auth::user()->username}} @endif :</p>
                                    <p><a href="{{asset('storage/images/attach/'.$data->image)}}" download="">@if(isset($data->image)) <img style="width: 180px" src="{{asset('storage/images/attach/'.$data->image)}}"> @endif</a></p>
                                    <p class="message-text">{!! str_replace("\n","<br/>",$data->deal_detail) !!}</p>
                                </div>
                                <br/>
                                <span>{{ Timezone::convertToLocal($data->created_at ,'Y-m-d H:i:s')   }}</span>
                            </div>
                        @endif
                    @endforeach
                
                </div>

                <div class="personal-detail">
                    <div class="personal-detail-inner col-md-12">
                        <p>Personal Details</p>
                        <div class="personal-detail-main">
                            <div class="personal-detail-conent">
                                <ul>
                                    <li>
                                        <a><i class="fa fa-user"></i><span>@php echo $user->getLinkToPageAttribute() @endphp</span></a>
                                    </li>
                                    <li>
                                        <a><i class="fa fa-star"></i><span>{{ $user->rating }} %</span></a>
                                    </li>
                                    <li>
                                        <a><i class="fa fa-map-marker" aria-hidden="true"></i><span>{{ $user->city }}</span></a>
                                    </li>
                                    <li>
                                        <a><i class="fa fa-flag"></i><span>@if($user->country){{ $user->country->name }}@endif</span></a>
                                    </li>
                                </ul>
                            </div>
                            @if($add->advertise->add_type == 2 && $add->advertise->allow_id == 1)
                                <div class="user-buttn">
                                    <button data-toggle="modal" data-target="#userID" class="btn userid">View User ID</button>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="personal-detail">
                    <div class="personal-detail-inner col-md-12">
                        <p>Information Of <span class="finn">{{$user->name}}</span></p>
                        <div class="infromation">
                            <div class="row">
                                <div class="col-lg-4">
                                    <span class="bold-text">Trade Volume of BTC</span>
                                    <span>{{round($trade_btc,8)}} BTC</span>
                                </div>
                                <div class="col-lg-4">
                                    <span class="bold-text">First Purchase</span>
                                    <span>@if(!empty($first_buy))
                                    {{\Carbon\Carbon::createFromTimeStamp(strtotime($first_buy->created_at))->diffForHumans()}}
                                    @else NA @endif</span>
                                </div>
                                <div class="col-lg-4">
                                    <span class="bold-text">Total Bitcoin Buy</span>
                                    <span>{{ $buyCount }}</span>
                                </div>
                            </div>
                            <div class="row pt-20">
                                <div class="col-lg-6 totl-bit">
                                    <span class="bold-text">Total Bitcoin Sell</span>
                                    <span>{{ $sellCount }}</span>
                                </div>

                                <div class="col-lg-6 totl-bit">
                                    <span class="bold-text">Total Number Of Reviews</span>
                                    <span>{{ \App\Models\Rating::where('to_user', $user->id)->count() }}</span>
                                </div>
                            </div>
                        </div>
                        <p class="pt-30">Other Information</p>
                        <div class="infromation">
                            <div class="row">
                                <div class="col-lg-4 totl-bit">
                                    <span class="bold-text">Account Created</span>
                                    <span>@if(!empty($user->created_at))
                                    {{\Carbon\Carbon::createFromTimeStamp(strtotime($user->created_at))->diffForHumans()}}
                                    @else NA @endif</span>
                                </div>
                                <div class="col-lg-4 totl-bit">
                                    <span class="bold-text">Last Seen</span>
                                    <span>@if(!empty($last_login->created_at))
                                    {{\Carbon\Carbon::createFromTimeStamp(strtotime($last_login->created_at))->diffForHumans()}}@else
                                    NA @endif</span>
                                </div>
                                <div class="col-lg-4 totl-bit">
                                    <span class="bold-text">User Status @if($user->verified == 1) <img src="{{ asset('new-images/check.png') }}" /> @else <img src="{{ asset('new-images/unverifiy.png') }}" /> @endif </span>
                                    <span>@if($user->verified == 1) Verified @else Unverified @endif </span>
                                </div>
                            </div>
                            <div class="row pt-20">
                                <div class="col-lg-6 totl-bit">
                                    <span class="bold-text">Email @if($user->email_verify == 1) <img src="{{ asset('new-images/check.png') }}" /> @else <img src="{{ asset('new-images/unverifiy.png') }}" /> @endif </span>
                                    <span>@if($add->advertise->add_type == 2 && $add->advertise->allow_email == 1) {{$user->email}} @else Verified @endif</span>
                                </div>

                                <div class="col-lg-6 totl-bit">
                                    <span class="bold-text">Phone Number @if($user->phone_verify == 1) <img src="{{ asset('new-images/check.png') }}" /> @else <img src="{{ asset('new-images/unverifiy.png') }}" /> @endif </span>
                                    <span>@if($add->advertise->add_type == 2 && $add->advertise->allow_phone == 1) {{$user->phone}} @else Verified @endif</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <!--Date time Strat-->
                <div class="clock text-center timer">
                    <h2 id="timer"></h2>
                    <!-- <ul>
                        <li id="hours"></li>
                        <li id="point">:</li>
                        <li id="min"></li>
                        <li id="point">:</li>
                        <li id="sec"></li>
                    </ul> -->
                </div>

                <!---Date Timer Div ENd-->

                <div class="message-right">
                    <!-- <span>finnarvehalle@hotmail.com wants to Buy 0.01772153 BTC with 4450 NOK from you at the price of 251107 NOK.</span>
                    <div class="form-group">
                        <textarea class="form-control input-control1" rows="5" id="comment" placeholder="finnarvehalle@hotmail.com :*eller"></textarea>
                    </div> -->

                    <div class="accordion">
                        <div class="accordion__header is-active">
                            <h2>Transaction Status</h2>
                            <span class="pr-active" id="statusP">
                                @if($add->status == 0) Processing
                                @elseif($add->status == 1) Paid Complete
                                @elseif($add->status == 9) Paid
                                @elseif($add->status == 10)  Dispute
                                @elseif($add->status == 2) Cancelled
                                @elseif($add->status ==21) Automatically cancelled
                                @elseif($add->status == 11) On Hold
                                @endif
                            </span>
                                <!-- <span class="pr-panding">Processing</span>
                                <span class="pr-success">success</span>
                                <span class="pr-decline">decline</span> -->
                            <span class="accordion__toggle"></span>
                        </div>
                        <div class="accordion__body is-active">
                            @if($name1 == $name)
                            <p>You are {{$add->add_type == 2 ? 'selling':'buying' }} {{ number_format((float)$add->coin_amount , 8, '.', '')  }}
                                {{$add->gateway->currency}} with {{$add->amount_to}} {{$add->currency->name}}
                                {{$add->add_type == 2? 'to':'from' }} {{$name1}} at the price of {{$add->price}} {{$add->currency->name}}.</p>
                            @else
                            <p>You are {{$add->add_type == 1 ? 'selling':'buying' }} {{ number_format((float)$add->coin_amount , 8, '.', '')  }}
                                {{$add->gateway->currency}} for {{$add->amount_to}} {{$add->currency->name}}
                                {{$add->add_type == 1? 'to':'from' }} {{$name1}} at the price of {{$add->price}} {{$add->currency->name}}.</p>
                            
                            @endif
                        </div>
                        @if($add->advertiser_id != \Auth::user()->id)
                        <div class="accordion__header">
                            <h2>Terms of trade with {{$name1}}</h2>
                            <span class="accordion__toggle"></span>
                        </div>
                        <div class="accordion__body">
                            <p>{!! str_replace("\n","<br/>", $add->term_detail) !!}</p>
                        </div>
                        <div class="accordion__header">
                            <h2>{{$name1}} Payment Detail</h2>
                            <span class="accordion__toggle"></span>
                        </div>
                        <div class="accordion__body">
                            <p>{!! str_replace("\n","<br/>", $add->payment_detail) !!}</p>
                        </div>
                        @endif
                        <div class="accordion__header">
                            <h2>Secret Notepad - Canned Message</h2>
                            <span class="accordion__toggle"></span>
                        </div>
                        <div class="accordion__body">
                            <div class="text-area pb-10">
                        
                                <span>To use a message,Just Click on It</span>
                            </div>
                            @foreach($canned_messages as $msg)
                                <span class="area-bg message">{{$msg->message}}</span>
                            @endforeach
                            <div class="button-area pt-30"> 
                            @if(count($canned_messages)> 0)
                            <a href="{{route('canned.messages.get', [Auth::user()->username])}}"  class="manage-btn"> Manage Secret Notes</a>
                            @else
                            <a href="{{route('canned.messages.get', [Auth::user()->username])}}"  class="manage-btn"> Add Secret Notes</a>
                            @endif
                            </div>
                        </div>
                    </div>
                </div>

                @if($add->status == 0 )
                    <div class="you-paid">
                        <div class="content text-center">
                            <p class="pb-10">
                            <span>Make sure you paid,then Click Button "Mark As Paid" </span>
                        </p>

                        <div class="button-area"> <a class="paid-btn" type="button" data-bs-toggle="modal" data-bs-target="#paidModal"> Mark As Paid </a>
                        </div>
                        </div>
                    </div>
                @endif
                @if($add->status == 0 )
                    <div class="you-paid">
                        <div class="content text-center">
                            <p class="pb-10">
                            <span>If you do no want to make any deal with <span>{{$name1}}</span>,then CLick "Cancel Request" </span>
                        </p>

                        <div class="button-area"> <a class="manage-btn" type="button" data-bs-toggle="modal" data-bs-target="#cancelModal1"> Cancel Request</a>
                        </div>
                        </div>
                    </div>
                @endif
                @if( $add->status == 9 || $add->status == 11)
                    <div class="you-paid">
                        <div class="content text-center">
                            <p class="pb-10">
                            <span>If you have a problem with this trade, or the seller is refusing to release your bitcoins after you have paid, you can dispute the deal. Dispute will be allowed only after TIMER minutes.</span>
                        </p>

                        <div class="button-area"> <a class="manage-btn" type="button" data-bs-toggle="modal" data-bs-target="#cancelModal"> Dispute Request</a>
                        </div>
                        </div>
                    </div>
                @endif

                @php
                    if($add->advertiser_id == \Auth::user()->id ){
                    if($add->status == 2 ||$add->status == 21 ||$add->status == 1){
                    $check =true;
                    }else{
                    $check =false;
                    }
                    }else{
                    if($add->status == 1){
                    $check =true;
                    }else{
                    $check =false;
                    }

                    }
                @endphp

                <div class="you-paid feedback ReviewButton">
                @if(isset($rating))
                    <p class="my-feedback">Update feedback about {{$name1}}</p>
                    <form action="{{route('rating.updated',$rating->id)}}" method="post">

                    @else
                    <p class="my-feedback">Give feedback about {{$name1}}</p>
                    <form action="{{route('rating.post')}}" method="post">

                @endif
                        @csrf
                        <input type="hidden" name="deal_id" value="{{$add->id}}">
                            
                            
                        <div class="form-group fedd">
                            <input class="styled-checkbox-3" name="rate" value="2" @if(isset($rating) && $rating->rating =='2') checked @endif   id="styled-checkbox-1" type="radio">
                            <label for="styled-checkbox-1" class="trust">Trustworthy</label>
                                    
                            <p>Give your trading partner trustworthy feedback to increase his reputation and mark him as a trusted user. </p>
                        </div>
                        <div class="form-group fedd">
                            <input class="styled-checkbox-3"  name="rate" id="styled-checkbox-2" type="radio" value="1" @if(isset($rating) && $rating->rating =='1') checked @endif >
                            <label for="styled-checkbox-2"  class="trust">Positive</label>
                                    
                            <p>Give your trading partner positive feedback to increase his reputation. </p>
                        </div>
                        <div class="form-group fedd">
                            <input class="styled-checkbox-3" name="rate"  id="styled-checkbox-3" type="radio" value="0" @if(isset($rating) && $rating->rating =='0') checked @endif >
                            <label for="styled-checkbox-3" class="trust">Neutral</label>
                                    
                            <p>Give your trading partner neutral feedback that does not affect his reputation. </p>
                        </div>
                        <div class="form-group fedd">
                        <input  class="styled-checkbox-3" name="rate"  type="radio" id="styled-checkbox-4" value="-2" @if(isset($rating) && $rating->rating == '-2') checked @endif >
                            <label for="styled-checkbox-4" class="block">Distrust and Block</label>
                                    
                            <p>Give your trading partner negative feedback that decreases his reputation and block his account, this prevents him from trading with you again.</p>
                        </div>
                            <div class="form-group fedd">
                            <input  class="styled-checkbox-3" name="rate"  type="radio" id="styled-checkbox-5" value="-2" @if(isset($rating) && $rating->rating == '-2') checked @endif >
                            <label for="styled-checkbox-5" class="block">Block without feedback</label>
                                    
                            <p>Block your trading partner from trading with you, but don't give him any feedback</p>
                        </div>

                        <div class="form-group">
                            <div class="remarks pt-30 pb-10">
                                <span class="re-marks">Remarks</span>
                            </div>
                            <textarea cols="70" rows="5" class="text-remarks" name="remarks">@if(isset($rating) && $rating->remarks ) {{ $rating->remarks}} @endif</textarea>

                            @if(isset($rating))
                            <div class="button"><button class="feedback-btn" type="submit">Update feedback {{$name1}}</button></div>
                            @else
                            <div class="button"><button class="feedback-btn" type="submit">Submit feedback</button></div>
                            @endif

                        </div>
                    </form>
                </div>

                <form role="form" method="POST" action="{{route('note.submit')}}">
                    {{ csrf_field() }}
                    <div class="you-paid">
                        <p class="my-feedback">My Private Note About {{$name1}}</p>
                        <div class="form-group">
                            <div class="col-md-12">
                                <textarea id="area-top" class="form-control" rows="15" name="note" placeholder="Write a private note about {{$name1}} here. Only you will be able to see the note, not {{$name1}}. It will display here for you every time {{$name1}} communicates or trades with you.">@if(isset($note) && $note != "") {{ $note }} @endif</textarea>
                            </div>
                        </div>
                        <input type="hidden" name="to_user_id" value="{{$note_to}}" />
                        <div class="button"><button type="submit" class="update-btn">Update</button></div>
                    </div>
                </form>
                <!--tab div end-->
                <div class="you-paid">
                    <p class="text-center myrating pt-10 pb-10">My Rating to {{$name1}}</p>
                    <div class="table-rting next table-responsive rating1">
                        <table class="table Rating">
                            <thead>
                                <tr>
                                    <th>Sr</th>
                                    <th>Rating</th>
                                    <th>Review</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $i=1;
                                @endphp
                                @foreach($dealer_reviews as $review)
                                <tr>
                                    <td>{{$i++}}</td>
                                    <td>
                                    @if($review->rating == '2')
                                    Trustworthy
                                    @elseif($review->rating == '1')
                                    Positive
                                    @elseif($review->rating == '-2')
                                    Distrusts
                                    @elseif($review->rating == '-1')
                                    Block
                                    @elseif($review->rating == '0')
                                    Neutral
                                    @endif
                                    </td>
                                    <td>{{$review->remarks}}
                                    </td>
                                    <td>
                                        {{\Carbon\Carbon::createFromTimeStamp(strtotime($review->updated_at))->diffForHumans()}}

                                    </td>
                                </tr>
                                <!-- Cart Tr End -->
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{$dealer_reviews->links()}}
                </div>
            </div>
        </div>
    </div>
</section>

<!--Bitcoin Buy Deals Form-->
<section class="table pt-60">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 history"><span>My Buy Deals With {{$user->username}}</span></div>
            <div class="col-lg-6 ml-auto">
                <!-- <div class="serach"><input type="email" class="form-control input-search" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Search" /><img src="images/loupe.png" /></div> -->
            </div>
            <div class="table-div datatable next complete table-responsive rating">
                <table id="example" class="table table-striped bit-table responsive">
                    <thead>
                        <tr>
                            <th>Sr</th>
                            <th>Deal ID</th>
                            <th>Amount (USD)</th>
                            <th>Amount (BTC)</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $i=1;
                        @endphp
                        @foreach($mutual_buy_deals as $deal)
                        <tr>
                            <td>{{$i++}}</td>
                            <td>{{$deal->id}}</td>
                            <td>{{$deal->usd_amount}}</td>
                            <td>{{$deal->coin_amount}}</td>
                            <td>@if($deal->status == 0)
                                <span > Processing </span>
                                @elseif($deal->status == 1)
                                <span > Paid Complete </span>
                                @elseif($deal->status == 9)
                                <span > Paid </span>
                                @elseif($deal->status == 10)
                                <span > Dispute </span>

                                @elseif($deal->status == 2)
                                <span > Cancelled </span>
                                @elseif($deal->status ==21)
                                <span > Automatically cancelled </span>
                                @elseif($deal->status == 11)
                                <span > On Hold </span>

                                @endif
                            </td>
                            <td>
                                {{\Carbon\Carbon::createFromTimeStamp(strtotime($deal->updated_at))->diffForHumans()}}

                            </td>
                        </tr>
                        <!-- Cart Tr End -->
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{$mutual_buy_deals->links()}}
        </div>
    </div>
</section>

<!--Bitcoin Create Add Form-->
<!--Bitcoin Buy Deals Form-->
<section class="table pt-60">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 history"><span>My Sell Deals With {{$user->username}}</span></div>
            <div class="col-lg-6 ml-auto">
                <!-- <div class="serach"><input type="email" class="form-control input-search" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Search" /><img src="images/loupe.png" /></div> -->
            </div>
            <div class="table-div datatable next complete table-responsive rating">
                <table id="example1" class="table table-striped bit-table responsive">
                    <thead>
                        <tr>
                            <th>Sr</th>
                            <th>Deal ID</th>
                            <th>Amount (USD)</th>
                            <th>Amount (BTC)</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $i=1;
                        @endphp
                        @foreach($mutual_sell_deals as $deal)
                        <tr>
                            <td>{{$i++}}</td>
                            <td>{{$deal->id}}</td>
                            <td>{{$deal->usd_amount}}</td>
                            <td>{{$deal->coin_amount}}</td>
                            <td>@if($deal->status == 0)
                                <span > Processing </span>
                                @elseif($deal->status == 1)
                                <span > Paid Complete </span>
                                @elseif($deal->status == 9)
                                <span > Paid </span>
                                @elseif($deal->status == 10)
                                <span > Dispute </span>

                                @elseif($deal->status == 2)
                                <span > Cancelled </span>
                                @elseif($deal->status ==21)
                                <span > Automatically cancelled </span>
                                @elseif($deal->status == 11)
                                <span > On Hold </span>

                                @endif
                            </td>
                            <td>
                                {{\Carbon\Carbon::createFromTimeStamp(strtotime($deal->updated_at))->diffForHumans()}}

                            </td>
                        </tr>
                        <!-- Cart Tr End -->
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{$mutual_sell_deals->links()}}
        </div>
    </div>
</section>

<!--Bitcoin Create Add Form-->
<!--Bitcoin Buy Deals Form-->
<section class="table pt-60">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 history"><span>Public rating of {{$name1}}</span></div>
            <div class="col-lg-6 ml-auto">
                <!-- <div class="serach"><input type="email" class="form-control input-search" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Search" /><img src="images/loupe.png" /></div> -->
            </div>
            <div class="table-div datatable next complete table-responsive rating ">
                <table id="example2" class="table table-striped bit-table responsive">
                    <thead>
                        <tr>
                            <th>Sr</th>
                            <th>Rating</th>
                            <th>Review</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $i=1;
                        @endphp
                        @foreach($reviews as $review)
                        <tr>
                            <td>{{$i++}}</td>
                            <td>
                            @if($review->rating == '2')
                            Trustworthy
                            @elseif($review->rating == '1')
                            Positive
                            @elseif($review->rating == '-2')
                            Distrusts
                            @elseif($review->rating == '-1')
                            Block
                            @elseif($review->rating == '0')
                            Neutral
                            @endif
                            </td>
                            <td>{{$review->remarks}}
                            </td>
                            <td>
                                {{\Carbon\Carbon::createFromTimeStamp(strtotime($review->updated_at))->diffForHumans()}}

                            </td>
                        </tr>
                        <!-- Cart Tr End -->
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{$reviews->links()}}
        </div>
    </div>
</section>

<!--Bitcoin Create Add Form-->



<div id="paidModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Confirm paid<strong> To {{$name1}} ?</strong></h4>
            </div>

            <div class="modal-footer">
                <form method="post" action="{{route('confirm.paid.reverse')}}" onsubmit="return confirmRelease()">
                    @csrf
                    <input type="hidden" name="status" value="{{$add->id}}" />
                    <button type="submit" id="releaseBTC" name="submit" value="{{$add->id}}" class="btn btn-primary pull-right" style="margin-left:10px;">Paid</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </form>
            </div>

        </div>

    </div>
</div>

<div id="userID" class="modal fade bd-example-modal-lg" role="dialog">
    <div class="modal-dialog modal-lg">

        <div class="modal-content" style="width: fit-content;">
            <div class="modal-header">
                <h4 class="modal-title">Upload ID by<strong> {{$user->name}} ?</strong></h4>
            </div>

            <div class="modal-body">
                @if($user->id_photo_id != null || file_exists($user->image))
                <img style="width: 100%;" src="{{asset('storage/images/attach/'.$user->id_photo_id)}}" class="img-responsive propic"
                    alt="Profile Pic">
                @else
                <img style="width: 100%;" src=" {{asset('images/user-default.png')}} " class="img-responsive propic" alt="Profile Pic">
                @endif
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>

        </div>

    </div>
</div>


<div id="cancelModal1" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" style="color: red">Confirm Cancel Request</h4>
            </div>

            <form method="post" action="{{route('confirm.cancel.reverse')}}">
                @csrf

                <div class="modal-footer">
                    <button type="submit" name="status" value="{{$add->id}}" class="btn btn-primary pull-right">Confirm
                        Cancel</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>

    </div>
</div>
<div id="cancelModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" style="color: red">Confirm Dispute Request<strong> from
                        {{$add->from_user->username}}</strong></h4>
            </div>

            <form method="post" action="{{route('confirm.cancel')}}">
                @csrf

                <div class="modal-body">
                    {{csrf_field()}}
                    <h6 style="color: #0b2e13">
                        {{$add->from_user->username}} want to {{$add->add_type == 2 ? 'sell':'buy' }}
                        {{ $add->coin_amount  }} {{$add->gateway->currency}} with {{$add->amount_to}}
                        {{$add->currency->name}} {{$add->add_type == 1? 'from':'to' }} you .
                        And confirm Dispute deal.
                    </h6>
                </div>
                <input type="hidden" name="dispute" id="dispute">
                <div class="modal-footer">
                    <button type="submit" name="status" value="{{$add->id}}" class="btn btn-primary pull-right dispute">Confirm Dispute</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>

    </div>
</div>
@stop

@section('script')
<script type="text/javascript">
    $(".accordion__header").click(function (e) {
        e.preventDefault();
        var currentIsActive = $(this).hasClass("is-active");
        $(this).parent(".accordion").find("> *").removeClass("is-active");
        if (currentIsActive != 1) {
            $(this).addClass("is-active");
            $(this).next(".accordion__body").addClass("is-active");
        }
    });
    $("#summernote").summernote({
        placeholder: "Write a private note about Finn Are halle.here.only you will be able to see the note, not Finn Are halle.It will display here for you every time Finn are Halle commmunicattees or trade with you.",
        tabsize: 2,
        height: 120,
        toolbar: [
            ["style", ["style"]],
            ["font", ["bold", "underline", "clear"]],
            ["color", ["color"]],
            ["para", ["ul", "ol", "paragraph"]],
            ["table", ["table"]],
            ["insert", ["link", "picture", "video"]],
            ["view", ["fullscreen", "codeview", "help"]],
        ],
    });
    function confirmRelease(){
        $('#releaseBTC').prop('disabled', true);
        return true;
    }
</script>
<script type="text/javascript">
    function startTimer(duration, display) {
        var timer = duration,
            minutes, seconds;
        setInterval(function() {
            minutes = parseInt(timer / 60, 10);
            seconds = parseInt(timer % 60, 10);

            minutes = minutes < 10 ? "0" + minutes : minutes;
            seconds = seconds < 10 ? "0" + seconds : seconds;

            display.textContent = minutes + ":" + seconds;

            if (--timer < 0) {
                timer = 00;
                $('.timer').hide();
                $('#rrrrr').prop('disabled', false);
            }
        }, 1000);
    }


    $(window).ready(function() {
        $('.ReviewButton').hide();
        var check = "{{$check}}";
        console.log(check)
        if (check) {
            $('.ReviewButton').show();
        }
        setInterval(yourAjaxCall, 30000);

        function yourAjaxCall() {
            $.ajax({
                type: "GET",
                url: "{{route('deal_messages.get',$add->id)}}",
                success: function(data1) {
                    var check = false;
                    var user_id = "{{\Auth::user()->id}}";
                    if (data1.advertiser_id == user_id) {
                        if (data1.status == 2 || data1.status == 21 || data1.status == 1) {
                            check = true;
                        } else {
                            check = false;
                        }
                    } else {
                        if (data1.status == 1) {
                            check = true;
                        } else {
                            check = false;
                        }

                    }
                    console.log(check)

                    // $("#pranto").load(location.href + " #pranto");
                    // $('#message').val(' ');
                    // console.log(data1.msgs);
                    $('.PaidButton').hide();
                    $('.DisputeButton').hide();
                    $('.cancelButton').hide();
                    $('.ReviewButton').hide();
                    if (check) {
                        $('.ReviewButton').show();
                    }

                    if (data1.status == 0) {
                        $('#statusP').html(
                            'Processing'
                        );
                        $('.PaidButton').show();
                        $('.cancelButton').show();
                    }

                    if (data1.status == 1) {
                        $('#statusP').html(
                            'Paid Complete'
                        );
                        $('.timer').hide();
                        // location.reload();
                    }
                    if (data1.status == 9) {
                        $('#statusP').html(
                            'Paid'
                        );

                        $('.DisputeButton').show();
                    }
                    if (data1.status == 10) {
                        $('#statusP').html(
                            'Dispute');
                        // $('.cancelButton').show();
                    }
                    if (data1.status == 2) {
                        $('#statusP').html(
                            'Cancelled');
                        $('.timer').hide();
                        // location.reload();
                    }
                    if (data1.status == 21) {
                        $('#statusP').html(
                            'Automatically Cancelled');
                        $('.timer').hide();
                        // location.reload();
                    }
                    if (data1.status == 11) {
                        $('#statusP').html(
                            'On Hold');
                        $('.DisputeButton').show();
                        $('.timer').hide();
                    }




                    $('#oww').html('');

                    var user_name = "{{\Auth::user()->username}}";
                    $.each(data1.msgs, function(key, data) {
                        // console.log(data);
                        var type = data.type;
                        var details = data.deal_detail;
                        var details = details.replace(/[\r\n]+/g, "<br>");
                        details = details.replaceAll('&nbsp', ' ');
                        var created_at = data.created_at;
                        if (data.image) {
                            var image = data.image;
                        } else {
                            var image = null
                        }
                        var string = '';
                        if (type == 0) {
                            var mess = "admin";
                            var name = 'Admin';
                        } else {
                            if (type != user_id) {
                                var mess = "reciver";
                                if (data.to_name == user_name) {
                                    var name = data.from_name;
                                } else {
                                    var name = data.to_name;

                                }


                            } else {
                                var mess = "sender";
                                var name = user_name;
                            }
                        }
                        string += '<div class="col-md-12 message-inner ' + mess + '"><div class="content-message">';

                        string += '<p>' + name + '  :</p> ';
                        if (image) {
                            var url = "{{ asset('storage/images/attach/') }}";
                            string += '<p><a href="' + url + '/' + image +
                                '" download=""> <img style="width: 180px" src="' + url + '/' +
                                image + '"></a></p>';
                        }
                        string += '<p class="message-text">' + details.replaceAll('\n', '<br>') + '</p></div><br/><span>';
                        if (type == user_id) {
                            
                        }
                        // console.log(created_at);
                        // console.log(moment(new Date(created_at)).format("YYYY-MM-DD hh:mm:ss a"));
                        string += created_at + '</span></div>';

                        $('#oww').append(string);
                    })
                    return; 

                    // console.log(data);


                }


            });

        }
    });

    $(document).ready(function() {

        const actualBtn = document.getElementById('getFile');

        const fileChosen = document.getElementById('file_name');

        actualBtn.addEventListener('change', function(){
        fileChosen.textContent = this.files[0].name
        })

        $("#preloader").hide();
        $('#preloader').bind('ajaxStart', function() {
            $(this).show();
        }).bind('ajaxStop', function() {
            $(this).hide();
        });
        var dataDeal = {!! json_encode($add) !!};
        console.log(dataDeal.status)
        if (dataDeal.status == 2 || dataDeal.status == 21 || dataDeal.status == 1) {
            $('.timer').hide();

        }
        if (dataDeal.status != 1 && dataDeal.status != 2) {
            console.log(dataDeal.dispute_timer)
            var date = new Date(); // some mock date
            var milliseconds = Math.round(date.getTime() / 1000);
            var time = milliseconds - dataDeal.dispute_timer;

            var minutes = time / 60;
            console.log(minutes)
            if (minutes > 90) {
                $('#rrrrr').prop('disabled', false);
                $('.timer').hide();
                minutes = 0;
            } else {
                $('#rrrrr').prop('disabled', true);
                minutes = 90 - minutes;
            }

            display = document.querySelector('#timer');
            startTimer(minutes * 60, display);

        }


        $('.dispute').click(function(e) {
            $('#dispute').val(10)
        })
        $('#submit').click(function(e) {

            $("#preloader").show();


            e.preventDefault();
            var id = "{{$add->id}}";
            var message = $('#message').val();

            if (message == '' || message == ' ') {
                alert('message field is required');
                $("#preloader").hide();
                return true;
            }
            var profileForm = $('#uploadDetail')[0];
            var formData = new FormData(profileForm);

            formData.append('id', id);
            formData.append('message', message);
            formData.append('url', 'deal');
            formData.append('_token', "{{csrf_token()}}");

            $('#file_name').html('No file Choosen');


            $.ajax({
                type: "POST",
                url: "{{route('send.message.deal')}}",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data) {
                    if(data.error !== undefined && data.error){
                        $("#preloader").hide();
                        alert(data.message);
                    }
                    var user_id = "{{\Auth::user()->id}}";
                    var user_name = "{{\Auth::user()->username}}";
                    $("#pranto").load(location.href + " #pranto");
                    $('#message').val(' ');
                    console.log(data);
                    var type = data.type;
                    // console.log(user_name)
                    // console.log(data.from_name)
                    // console.log(data.to_name)
                    var from_name = data.from_name;
                    var to_name = data.to_name;
                    var created_at = data.created_at;
                    var details = data.deal_detail;
                    var details = details.replaceAll("\n", "<br>");
                    details = details.replaceAll('&nbsp', ' ');
                    if (data.image) {
                        var image = data.image;
                    } else {
                        var image = null
                    }
                    var string = '';
                    if (type == 0) {
                        var mess = "admin";
                        var name = 'Admin';
                    } else {
                        if (type != user_id) {
                            var mess = "reciver";
                            if (data.to_name == user_name) {
                                var name = data.from_name;
                            } else {
                                var name = data.to_name;

                            }


                        } else {
                            var mess = "sender";
                            var name = user_name;
                        }
                    }
                    string += '<div class="col-md-12 message-inner ' + mess + '"><div class="content-message">';

                    string += '<p>' + name + '  :</p> ';
                    if (image) {
                        var url = "{{ asset('storage/images/attach/') }}";
                        string += '<p><a href="' + url + '/' + image +
                            '" download=""> <img style="width: 180px" src="' + url + '/' +
                            image + '"></a></p>';
                    }
                    string += '<p class="message-text">' + details.replaceAll('\n', '<br>') + '</p></div><br/><span>';
                    if (type == user_id) {
                        
                    }
                    // console.log(created_at);
                    // console.log(moment(new Date(created_at)).format("YYYY-MM-DD hh:mm:ss a"));
                    string += created_at + '</span></div>';

                    $('#oww').prepend(string);
                    $("#preloader").hide();
                }
            })

            // setTimeout(function () {
            //     $("#oww").load(location.href + " #oww");
            // }, 3000)
        });

    });
</script>
<script>
    $( ".message" ).click(function() {
        $('#message').val($('#message').val() + ' ' + $(this).text());
    });
</script>
<script>
    $('#getFile').change(function() {
        var i = $(this).prev('label').clone();
        var file = $('#getFile')[0].files[0].name;
        $(this).prev('label').text(file);
        $('#getFileName').text(file);
    });
</script>
    <script src="https://cdn.ckeditor.com/ckeditor5/16.0.0/classic/ckeditor.js"></script>
    <script>
      ClassicEditor
        .create( document.querySelector( '#area-top' ) )
        .then( editor => {
                console.log( editor );
        })
        .catch( error => {
                console.error( error );
        });
    </script>
@stop