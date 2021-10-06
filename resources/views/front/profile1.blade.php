@extends('front.layout.master2' )
@section('style')

@stop
@section('body')
<!--bitcoin blance Strat--->
<section class="bitcoin-blacnce">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 balane">
                <p><span>{{ $user->username }}'s Profile </span></p>
            </div>
            @if (\Auth::user() && \Auth::user()->id === $user->id)
            <div class="col-lg-4 balane">
                <a class="btn market" href="/user/{{$user->username}}/edit-profile"> Update Profile </a>
            </div>
            @endif
        </div>
    </div>
</section>

<!--Bitcoin Create Add Form-->
<section class="pt-60 create-add">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 setp1">
                <div class="heder">
                    <span>Personal Details</span>
                </div>
                <div class="content-ingo">
                    <ul class="how-personl">
                        <a href="">
                            <li><i class="fa fa-user"></i> {{ $user->username }}</li>
                        </a>
                        @if($user->rating >= 50)
                            <a href="">
                                <li class="active"><i class="fa fa-star"></i> {{ $user->rating }} %</li>
                            </a>
                        @elseif($user->rating < 50)
                            <a href="">
                                <li class="bg-danger"><i class="fa fa-star"></i> {{ $user->rating }} %</li>
                            </a>
                        @endif
                        <a href="">
                            <li><i class="fa fa-map-marker"></i> {{ $user->city }}</li>
                        </a>
                        <a href="">
                            <li><i class="fa fa-globe"></i>@if( $user->country) {{ $user->country->name }} @endif</li>
                        </a>
                    </ul>
                </div>
            </div>
            <div class="col-lg-4 setp1">
                <div class="heder">
                    <span>Information Of {{$user->username}}</span>
                </div>
                <div class="content-ingo">
                    <ul class="how-personl">
                        <a href="">
                            <li><span class="trade-btc">Trade Volume Of BTC</span>: {{round($trade_btc,8)}} BTC</li>
                        </a>
                        <a href="">
                            <li><span class="trade-btc">First Purchase</span>: @if(!empty($first_buy))
                                {{\Carbon\Carbon::createFromTimeStamp(strtotime($first_buy->created_at))->diffForHumans()}}
                                @else NA @endif</li>
                        </a>
                        <a href="">
                            <li><span class="trade-btc">Total Bitcoin Buy</span>: {{ $buyCount }}</li>
                        </a>
                        <a href="">
                            <li><span class="trade-btc">Total Bitcoin Sell</span>: {{ $sellCount }}</li>
                        </a>
                        <a href="">
                            <li><span class="trade-btc">Total Number Of Reviews</span>: {{ \App\Models\Rating::where('to_user', $user->id)->count() }}</li>
                        </a>
                    </ul>
                </div>
            </div>
            <div class="col-lg-4 setp1">
                <div class="heder">
                    <span>Other Information</span>
                </div>
                <div class="content-ingo">
                    <ul class="how-personl">
                        <a href="">
                            <li><span class="trade-btc">Account Created</span>: @if(!empty($user->created_at))
                                {{\Carbon\Carbon::createFromTimeStamp(strtotime($user->created_at))->diffForHumans()}}
                                @else NA @endif</li>
                        </a>
                        <a href="">
                            <li><span class="trade-btc">Last Seen</span>: @if(!empty($last_login->created_at))
                                {{\Carbon\Carbon::createFromTimeStamp(strtotime($last_login->created_at))->diffForHumans()}}@else
                                NA @endif</li>
                        </a>
                        <a href="">
                            <li><span class="trade-btc">Email</span>: <span class="Verified">@if($user->email_verify == 1) <i
                                    style="color: green;" class="fa fa-check"></i> Verified @else <i style="color: red;"
                                    class="fa fa-times"></i> Unverified @endif</span></li>
                        </a>
                        <a href="">
                            <li><span class="trade-btc">Phone Number</span>: <span class="Verified">@if($user->phone_verify == 1) <i
                                    style="color: green;" class="fa fa-check"></i> Verified @else <i style="color: red;"
                                    class="fa fa-times"></i> Unverified @endif</span></li>
                        </a>
                        <a href="">
                            <li><span class="trade-btc">User Status</span>: <span class="Verified">@if($user->verified == 1) <i
                                    style="color: green;" class="fa fa-check"></i> Verified @else <i style="color: red;"
                                    class="fa fa-times"></i> Unverified @endif</span></li>
                        </a>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
<!--Users Feedback and Ratings-->
<section class="table pt-15">
    <div class="container">
        <div class="row">
            <div class="col-lg-6"><span class="user">Users Feedback and Ratings</span></div>
            <div class="col-lg-6 ml-auto">
                <!-- <div class="serach"><input type="email" class="form-control input-search" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Search" /><img src="images/loupe.png" /></div> -->
            </div>

            <div class="container pt-60">
                <div class="row">
                    <!--Tab section 1-->
                    <div class="col-lg-2 tab-div">
                        <div class="tab-section">
                            <span>TRUSTWORTHY <span>{{ \App\Models\Rating::where('to_user', $user->id)->where('rating', '2')->count() }}</span></span>
                        </div>
                    </div>
                    <!--Tba section div  end--->
                    <!--Tab section 2-->
                    <div class="col-lg-2 tab-div">
                        <div class="tab-section">
                            <span>POSITIVE <span>{{ \App\Models\Rating::where('to_user', $user->id)->where('rating', '1')->count() }}</span></span>
                        </div>
                    </div>
                    <!--Tba section div  end--->
                    <!--Tab section 3-->
                    <div class="col-lg-2 tab-div">
                        <div class="tab-section">
                            <span>NEUTRAL <span>{{ \App\Models\Rating::where('to_user', $user->id)->where('rating', '0')->count() }}</span></span>
                        </div>
                    </div>
                    <!--Tba section div  end--->
                    <!--Tab section 4-->
                    <div class="col-lg-2 tab-div">
                        <div class="tab-section">
                            <span>BLOCKS <span>{{ \App\Models\Rating::where('to_user', $user->id)->where('rating', '-1')->count() }}</span></span>
                        </div>
                    </div>
                    <!--Tba section div  end--->
                    <!--Tab section 4-->
                    <div class="col-lg-2 tab-div">
                        <div class="tab-section">
                            <span>BLOCKS <span>{{ \App\Models\Rating::where('to_user', $user->id)->where('rating', '-2')->count() }}</span></span>
                        </div>
                    </div>
                    <!--Tba section div  end--->
                </div>
            </div>
            <div class="table-div datatable ingo table-responsive">
                <table id="example" class="table table-striped bit-table">
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
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{$reviews->links()}}
        </div>
    </div>
</section>

<!--Users Feedback and Ratings end-->

<!--Users ING-Oslo's Active ADs-->
<section class="table pt-15">
    <div class="container">
        <div class="row">
            <div class="col-lg-6"><span class="user">{{ $user->username }}'s Active ADs</span></div>
            <div class="col-lg-6 ml-auto">
                <!-- <div class="serach"><input type="email" class="form-control input-search" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Search" /><img src="images/loupe.png" /></div> -->
            </div>
            <div class="table-div datatable ingo table-responsive">
                <table id="example" class="table table-striped bit-table responsive">
                    <thead>
                        <tr>
                            <th>Coin Name</th>
                            <th>Payment Method</th>
                            <th>Price</th>
                            <th>Limits</th>
                            <th>Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($coin as $data)
                        @php
                        $bal = \App\Models\UserCryptoBalance::where('user_id', $data->user->id)
                        ->where('gateway_id',$data->gateway_id)->first();
                        $userdef = $data->max_amount;
                        $actual = $data->price*$bal->balance;
                        $max = $userdef>$actual?$actual:$userdef;
                        @endphp

                        <tr>
                            <td>{{$data->gateway->name}}</td>
                            <td>
                                {{-- <img style="width: 30px;" src="{{Storage::url($data->paymentMethod->icon)}}">
                                --}}
                                {{$data->paymentMethod->name}}
                            </td>
                            <td>{{$data->price}}
                                {{$data->currency->name}}/{{$data->gateway->currency}}</td>
                            <td>@if($data->add_type == 1)
                                {{$data->min_amount.' '.$data->currency->name .'-'.round($max,2).' '.$data->currency->name}}
                                @else
                                {{$data->min_amount.' '.$data->currency->name .'-'.$data->max_amount.' '.$data->currency->name}}
                                @endif
                            </td>
                            <td><p>
                                <a
                                    href="{{route('view', ['id'=>$data->id, 'payment'=>Replace($data->paymentMethod->name)])}}">{{$data->add_type == 2 ? 'Sell':'Buy'}}</a>
                                </p>
                            </td>
                        </tr>
                        <!-- Cart Tr End -->
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{$coin->links()}}
        </div>
    </div>
</section>

<!--Users ING-Oslo's Active ADs-->

<!--My Feedback and Ratings-->
<section class="table pt-15">
    <div class="container">
        <div class="row">
            <div class="col-lg-6"><span class="user">My Ratings and Feedbacks to {{$user->username}}</span></div>
            <div class="col-lg-6 ml-auto">
                <!-- <div class="serach"><input type="email" class="form-control input-search" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Search" /><img src="images/loupe.png" /></div> -->
            </div>

            <div class="container pt-60">
                <div class="row">
                    <!--Tab section 1-->
                    <div class="col-lg-2 tab-div">
                        <div class="tab-section">
                            <span>TRUSTWORTHY <span>{{ \App\Models\Rating::where('to_user', $user->id)->where('from_user', Auth::user()->id)->where('rating', '2')->count() }}</span></span>
                        </div>
                    </div>
                    <!--Tba section div  end--->
                    <!--Tab section 2-->
                    <div class="col-lg-2 tab-div">
                        <div class="tab-section">
                            <span>POSITIVE <span>{{ \App\Models\Rating::where('to_user', $user->id)->where('from_user', Auth::user()->id)->where('rating', '1')->count() }}</span></span>
                        </div>
                    </div>
                    <!--Tba section div  end--->
                    <!--Tab section 3-->
                    <div class="col-lg-2 tab-div">
                        <div class="tab-section">
                            <span>NEUTRAL <span>{{ \App\Models\Rating::where('to_user', $user->id)->where('from_user', Auth::user()->id)->where('rating', '0')->count() }}</span></span>
                        </div>
                    </div>
                    <!--Tba section div  end--->
                    <!--Tab section 4-->
                    <div class="col-lg-2 tab-div">
                        <div class="tab-section">
                            <span>BLOCKS <span>{{ \App\Models\Rating::where('to_user', $user->id)->where('from_user', Auth::user()->id)->where('rating', '-1')->count() }}</span></span>
                        </div>
                    </div>
                    <!--Tba section div  end--->
                    <!--Tab section 4-->
                    <div class="col-lg-2 tab-div">
                        <div class="tab-section">
                            <span>DISTRUSTS <span>{{ \App\Models\Rating::where('to_user', $user->id)->where('from_user', Auth::user()->id)->where('rating', '-2')->count() }}</span></span>
                        </div>
                    </div>
                    <!--Tba section div  end--->
                </div>
            </div>
            <div class="table-div datatable ingo table-responsive">
                <table id="example" class="table table-striped bit-table">
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
</section>

<!--My Feedback and Ratings end-->

<!--Users ING-Oslo's My Sell ADs-->
<section class="table pt-15">
    <div class="container">
        <div class="row">
            <div class="col-lg-6"><span class="user">My Sell Deals With {{$user->username}}</span></div>
            <div class="col-lg-6 ml-auto">
                <!-- <div class="serach"><input type="email" class="form-control input-search" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Search" /><img src="images/loupe.png" /></div> -->
            </div>
            <div class="table-div datatable ingo table-responsive">
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
                        @foreach($mutual_sell_deals as $deal)
                        <tr>
                            <td>{{$i++}}</td>
                            <td>{{$deal->id}}</td>
                            <td>{{$deal->usd_amount}}</td>
                            <td>{{$deal->coin_amount}}</td>
                            <td>@if($deal->status == 0)
                                <span class=""> Processing </span>
                                @elseif($deal->status == 1)
                                <span class=""> Paid Complete </span>
                                @elseif($deal->status == 9)
                                <span class=""> Paid </span>
                                @elseif($deal->status == 10)
                                <span class=""> Dispute </span>

                                @elseif($deal->status == 2)
                                <span class=""> Cancelled </span>
                                @elseif($deal->status ==21)
                                <span class=""> Automatically cancelled </span>
                                @elseif($deal->status == 11)
                                <span class=""> On Hold </span>

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

<!--Users ING-Oslo's My Sell ADs-->

<!--Users ING-Oslo's My Buy ADs-->
<section class="table pt-15">
    <div class="container">
        <div class="row">
            <div class="col-lg-6"><span class="user">My Buy Deals With {{$user->username}}</span></div>
            <div class="col-lg-6 ml-auto">
                <!-- <div class="serach"><input type="email" class="form-control input-search" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Search" /><img src="images/loupe.png" /></div> -->
            </div>
            <div class="table-div datatable ingo table-responsive">
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
                                <span class=""> Processing </span>
                                @elseif($deal->status == 1)
                                <span class=""> Paid Complete </span>
                                @elseif($deal->status == 9)
                                <span class=""> Paid </span>
                                @elseif($deal->status == 10)
                                <span class=""> Dispute </span>

                                @elseif($deal->status == 2)
                                <span class=""> Cancelled </span>
                                @elseif($deal->status ==21)
                                <span class=""> Automatically cancelled </span>
                                @elseif($deal->status == 11)
                                <span class=""> On Hold </span>

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

<!--Users ING-Oslo's My Buy ADs-->

<form role="form" method="POST" action="{{route('note.submit')}}">
    {{ csrf_field() }}
    <div class="you-paid">
        <p class="my-feedback">My Private Note About {{$user->username}}</p>
        <div class="form-group">
            <div class="col-md-12">
                <textarea id="area-top" class="form-control" rows="15" name="note" placeholder="Write a private note about {{$user->username}} here. Only you will be able to see the note, not {{$user->username}}. It will display here for you every time {{$user->username}} communicates or trades with you.">@if(isset($note) && $note != "") {{ $note }} @endif</textarea>
            </div>
        </div>
        <input type="hidden" name="to_user_id" value="{{$user->id}}" />
        <div class="button"><button type="submit" class="update-btn">Update</button></div>
    </div>
</form>

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

@section('script')

@stop