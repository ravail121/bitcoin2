@extends('admin.layout.master')
@section('style')
<style>
.active1 {
    box-shadow: -4px 2px 4px 0px black !important;
}
</style>
@endsection
@section('body')
<div class="row">
    <div class="col-md-3">
        <div class="tile">
            <h4 class="tile-title">
                <i class="fa fa-user"></i> Profile </h4>
            <div class="tile-body">
                @if( $user->id_photo_id != null || file_exists($user->image))
                <img src="{{asset('storage/images/attach/'.$user->id_photo_id)}}" class="img-responsive propic"
                    alt="Profile Pic">
                @else
                <img src=" {{asset('images/user-default.png')}} " class="img-responsive propic" alt="Profile Pic">
                @endif
                <hr>
                <h5 class="bold">User Name : {{ $user->username }}</h5>
                <h5 class="bold">Name : {{ $user->name }}</h5>
                <hr>
                <p>
                @if($last_login != null)
                    <strong>Last Login : {{ Carbon\Carbon::parse($last_login->created_at)->diffForHumans() }}</strong>
                @else 
                    <strong>Last Login : N/A</strong>
                @endif
                    <br>
                </p>
                <p>
                    <strong>Created At : {{ Carbon\Carbon::parse($user->created_at)->diffForHumans() }}</strong>                
                </p>
                <hr>
                @if($last_login != null)
                <strong>Last Login From</strong> <br> {{ $last_login->user_ip }}
                <br> {{ $last_login->details }} <br>
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-9">
        <div class="row">
            <div class="col-md-6 col-lg-6">
                <a href="{{ url('adminio/transactions?user_id=' . $user->id) }}" class="text-decoration">
                    <div class="widget-small primary coloured-icon"><i class="icon fa fa-th fa-3x"></i>
                        <div class="info">
                            <h6>TRANSACTED</h6>
                            <p><b>{{ $trxes }}</b></p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-6 col-lg-6">
                <a href="{{url('adminio/external-transactions?address='.$user->cryptoBalances->first()->address) }}"
                    class="text-decoration">
                    <div class="widget-small info coloured-icon">
                        <i class="icon fa fa-download fa-3x"></i>
                        <div class="info">
                            <h6>DEPOSITS</h6>
                            <p><b>
                                    {{ $completed_depo }}
                                    
                                </b></p>
                        </div>
                    </div>
                </a>
            </div>
            @foreach($balance as $data)
            <div class="col-md-6 col-lg-6">
                <div class="widget-small m-0 warning coloured-icon">
                    <i class="icon fa fa-contao fa-3x"></i>
                    <div class="info">
                        <h6>{{$data->gateway->name}} Balance:</h6>
                        <p><b>{{number_format((float)$data->balance, 8, '.', '')}} {{$data->gateway->currency}}</b></p>
                    </div>
                </div>
            </div>
            @endforeach
            <div class="col-md-6 col-lg-6">
                <a href="#"
                    class="text-decoration">
                    <div class="widget-small m-0 info coloured-icon">
                        <i class="icon fa fa-exchange fa-3x"></i>
                        <div class="info">
                            <h6>WITHDRAWS</h6>
                            <p><b>
                                    {{ $user->transactions()->completed()->withdraws()->count() }}
                                </b></p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-6 my-3 text-center">
                <a href="{{ route('user.balance.history', ['user' => $user->username]) }}">
                    View Balance History <i class="fa fa-arrow-right"></i>
                </a>
            </div>
            <div class="col-6 my-3 text-center">
                <a href="{{ route('user.access.history', ['user' => $user->username]) }}">
                    View Access History <i class="fa fa-arrow-right"></i>
                </a>
            </div>
        </div>
        <div class="col-md-12">
            <div class="tile">
                <h3 class="tile-title"><i class="fa fa-cogs"></i> Operations</h3>
                <div class="tile-body">
                    <div class="row">
                        <div class="col-md-6">
                            <a href="{{route('user.balance',$user->id)}}" class="btn btn-lg btn-block btn-primary"><i
                                    class="fa fa-money"></i>
                                Add/Substract Balance</a><br>
                        </div>
                        <!-- <div class="col-md-6">
                            <a href="{{route('user.login.history',$user->id)}}"
                                class="btn btn-lg btn-block btn-primary"><i class="fa fa-sign-out"></i> Login
                                History</a>
                            <br>
                        </div> -->
                        <div class="col-md-6">
                            <a href="{{route('user.balance.nullify',$user->id)}}"
                                class="btn btn-lg btn-block btn-danger"><i class="fa fa-btc"></i> Nullify Balance</a>
                            <br>
                        </div>
                        <div class="col-md-6">
                            <a href="{{route('user.email',$user->id)}}" class="btn btn-lg btn-block btn-primary"> <i
                                    class="fa fa-envelope"></i> Send Email</a>
                            <br>
                        </div>
                        <div class="col-md-6">
                            <button type="button" class="btn btn-primary btn-lg btn-block" data-toggle="modal"
                                data-target="#changepass"><i class="fa fa-lock"></i>
                                Change Password
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<hr>
<div class="col-md-12  col-sm-12">

    <div class="tile">

        <div class="row">
            <div class="col-md-12">
                <div class=" text-center" style="background-color:#66a3ac ;padding:10px; margin-bottom:10px;">
                    <h2 class="text-center" style="background-color:#66a3ac;">Users Feedback and Ratings</h2>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 margin-top-pranto">
                <div class="card text-uppercase">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"> Trustworthy
                            <strong>{{ \App\Models\Rating::where('to_user', $user->id)->where('rating', '2')->count() }}</strong>
                        </li>
                        <li class="list-group-item"> Positive
                            <strong>{{ \App\Models\Rating::where('to_user', $user->id)->where('rating', '1')->count() }}</strong>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-md-4 margin-top-pranto">
                <div class="card text-uppercase">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"> Neutral
                            <strong>{{ \App\Models\Rating::where('to_user', $user->id)->where('rating', '0')->count() }}</strong>
                        </li>

                    </ul>
                </div>
            </div>
            <div class="col-md-4 margin-top-pranto">
                <div class="card text-uppercase">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"> Blocks
                            <strong>{{ \App\Models\Rating::where('to_user', $user->id)->where('rating', '-2')->count() }}</strong>
                        </li>
                        <li class="list-group-item"> Distrusts
                            <strong>{{ \App\Models\Rating::where('to_user', $user->id)->where('rating', '-1')->count() }}</strong>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <br>
        <div id="searchsell">



            <div class="dataTables_wrapper">
                <div class="row">
                    <div class="col-md-12">
                        <div class="cm_tabled1 table-responsive">


                            <table class="table myTable" id="sellsearchtable">
                                <thead>
                                    <tr>
                                        <th>Sr</th>
                                        <th>Review</th>
                                        <th>Rating</th>
                                        <th>Deal</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                    $i=1;
                                    @endphp
                                    @foreach($reviews as $review)
                                    <tr>
                                        <td>{{$i++}}</td>

                                        <td>{{$review->remarks}}
                                        </td>
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
                                        <td>
                                            <a class="btn btn-primary"
                                                href="{{route('deal.view.admin', $review->deal_id)}}"
                                                style="color: white">Detail</a>

                                        </td>
                                        <td>
                                            <a href="{{route('admin.review.delete', $review->id)}}"
                                                class="btn btn-danger btn-block">Delete</a>
                                            <a href="{{route('admin.review.edit', $review->id)}}"
                                                class="btn btn-info btn-block">Edit</a>


                                        </td>
                                    </tr>
                                    <!-- Cart Tr End -->
                                    @endforeach

                                </tbody>
                            </table>
                        </div>


                    </div>

                </div>
            </div>
        </div>


    </div>
    <hr>

    <div class="row">

    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <h3 class="tile-title">
                    <i class="fa fa-user"></i> Advertisements </h3>
                <div class="tile-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover order-column" id="">
                            <thead>
                                <tr>
                                    <th>Advertise Type</th>
                                    <th>GateWay Name</th>
                                    <th>Payment Method Name</th>
                                    <th>Min-Max</th>
                                    <th>Status</th>
                                    <th>Raised Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($addvertise as $data)
                                <!-- Cart Tr Start -->
                                <tr>
                                    <td>
                                        @if($data->add_type == 1)
                                        <span class="label label-primary">Want To Sell</span>
                                        @else
                                        <span class="label label-success">Want To Buy</span>
                                        @endif
                                    </td>
                                    <td>{{$data->gateway->name}}</td>
                                    <td>{{$data->paymentMethod->name}}</td>
                                    <td>{{$data->min_amount.' '.$data->currency->name .'-'. $data->max_amount.' '.$data->currency->name}}
                                    </td>
                                    <td>
                                        <select name="status" id="status{{$data->id}}" class="status"
                                            data-id="{{$data->id}}" data-prev="{{$data->status}}">
                                            <option value="0" {{$data->status == '0'? 'selected':''  }}> Inactive
                                            </option>
                                            <option value="1" {{$data->status == '1'? 'selected':''  }}> Active
                                            </option>
                                            <option value="2" {{$data->status == '2'? 'selected':''  }}> On Vacation
                                            </option>
                                        </select>
                                    </td>
                                    <td>{{  Timezone::convertToLocal($data->created_at,'g:ia \o\n l jS F Y')  }}</td>
                                </tr>
                                <!-- Cart Tr End -->
                                @endforeach
                            <tbody>
                        </table>
                        {!! $addvertise->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <h3 class="tile-title">
                    <i class="fa fa-user"></i> Open Deals </h3>
                <div class="tile-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover order-column" id="">
                            <thead>
                                <tr>
                                    <th>Deal ID</th>
                                    <th>Seller</th>
                                    <th>Buyer</th>
                                    <th>AD</th>
                                    <th>Amount BTC</th>
                                    <th>Deal Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($open_deals as $data)
                                <!-- Cart Tr Start -->
                                <tr>
                                    <td><a href="{{route('deal.view.admin', $data->trans_id)}}">{{$data->trans_id}}</a></td>
                                    <td><a href="{{route('user.single', $data->from_user->username)}}">{{$data->from_user->username}}</a></td>
                                    <td><a href="{{route('user.single', $data->to_user->username)}}">{{$data->to_user->username}}</a></td>
                                    <td><a href="{{route('search.ads') . '?add_id=' . $data->advertisement_id}}">{{$data->advertisement_id}}</a></td>
                                    <td>{{$data->coin_amount}}</td>
                                    <td>{{  Timezone::convertToLocal($data->created_at,'g:ia \o\n l jS F Y')  }}</td>
                                </tr>
                                <!-- Cart Tr End -->
                                @endforeach
                            <tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <h3 class="tile-title">
                    <i class="fa fa-user"></i> Update Profile</h3>
                <div class="tile-body">
                    <form id="form" method="POST" action="{{route('user.status', $user->id)}}"
                        enctype="multipart/form-data" name="editForm">
                        @csrf
                        @method('put')
                        <div class="row">
                        <div class="col-md-12 margin-top-pranto ">
    <div class="card border-dark">
      <div class="card-body text-light bg-info"> <span class="h6">Deposit address:</span>
                            <div class="float-right">
                               
                                <span id="depositAddress">{{ $user->cryptoBalances->first()->address }}</span>
                                <a title="Click to copy address" onclick="copy('depositAddress')" href="javascript:void(0)"><i class="fa fa-copy"></i></a>
                            </div>
                        </div></div>
                        </div></div>
                        <div class="row">
                            <div class="form-group col-md-4 {{ $errors->has('name') ? ' has-error' : '' }}">
                                <label> <strong>Name</strong></label>
                                <input type="text" name="name" class="form-control input-lg" value="{{ $user->name }}">
                                @if ($errors->has('name'))
                                <span class="help-block bg-red">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class="form-group col-md-4 {{ $errors->has('phone') ? ' has-error' : '' }}">
                                <label><strong>Phone</strong></label>
                                <input type="text" name="phone" class="form-control input-lg"
                                    value="{{ $user->phone }}">
                                @if ($errors->has('phone'))
                                <span class="help-block bg-red">
                                    <strong>{{ $errors->first('phone') }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class="form-group col-md-4 {{ $errors->has('email') ? ' has-error' : '' }}">
                                <label><strong>Email</strong></label>
                                <input type="email" name="email" class="form-control input-lg"
                                    value="{{ $user->email }}">
                                @if ($errors->has('email'))
                                <span class="help-block bg-red">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6 ">
                                <label> <strong>City</strong></label>
                                <input type="text" name="city" id="city" value="{{$user->city ? $user->city:''}}" class="form-control input-lg" placeholder="Type to search">

                            
                            </div>
                            <div class="form-group col-md-6">
                                <label><strong>Zip Code</strong></label>
                                <input type="text" name="zip_code" class="form-control input-lg"
                                    value="{{ $user->zip_code }}">
                            </div>
                            <div class="form-group col-md-12">
                                <label><strong>Address</strong></label>
                                <input type="text" name="address" class="form-control input-lg"
                                    value="{{ $user->address }}">
                            </div>
                            <div class="form-group col-md-6 ">
                                <label><strong>Country</strong></label>
                                <select name="country_id" class="form-control select input-lg">
                                    <option value="">Select Country</option>
                                    @foreach($countries as $key => $country)
                                    @if($user->country_id)
                                    <option value="{{ $country->id }}"
                                        {{ $country->id == $user->country_id ? 'selected' : '' }}>
                                        {{ $country->name }}</option>
                                    @else
                                    <option value="{{ $country->id }}">{{ $country->name }}</option>
                                    @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label><strong>Max Send Limit</strong></label>
                                <input type="number" step="any" min="0" name="max_send_limit" class="form-control input-lg"
                                    value="{{ $user->max_send_limit }}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label><strong>User Status</strong></label>
                                <input class="form-control" data-toggle="toggle" data-onstyle="success"
                                    data-offstyle="danger" data-width="100%" data-on="Active" data-off="Deactive"
                                    type="checkbox" value="1" name="status" {{ $user->status == "1" ? 'checked' : '' }}>
                            </div>

                            <div class="form-group col-md-4">
                                <label><strong>Email Verification</strong></label>
                                <input class="form-control" data-toggle="toggle" data-onstyle="success"
                                    data-offstyle="danger" data-width="100%" data-on="Yes" data-off="No" type="checkbox"
                                    value="1" name="email_verify" {{ $user->email_verify == "1" ? 'checked' : '' }}>
                            </div>
                            <div class="form-group col-md-4">
                                <label><strong>Phone Verification</strong></label>
                                <input class="form-control" data-toggle="toggle" data-onstyle="success"
                                    data-offstyle="danger" data-width="100%" data-on="Yes" data-off="No" type="checkbox"
                                    value="1" name="phone_verify" {{ $user->phone_verify == "1" ? 'checked' : '' }}>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label><strong>Auto Verified</strong></label>
                                <input class="form-control" data-toggle="toggle" data-onstyle="success"
                                    data-offstyle="danger" data-width="100%" data-on="Yes" data-off="No" type="checkbox"
                                    value="1" name="auto_verified" {{ $user->auto_verified == "1" ? 'checked' : '' }}>
                            </div>
                        </div>
                        <hr class="mb-3"/>
                        <div class="col-md-12">
                            <div class="">
                                <h3 class="tile-title"><i class="fa fa-cogs"></i> Permissions</h3>
                                <div class="tile-body">
                                    <div class="row">
                                        <div class="form-group col-md-6 mb-3">
                                            <input class="form-control" data-toggle="toggle" data-onstyle="success"
                                                    data-offstyle="danger" data-width="100%" data-on="Withdraw Allowed" data-off="Withdraw Blocked"
                                                    type="checkbox" value="1" name="permission_withdraw" {{ $user->permission_withdraw == "1" ? 'checked' : '' }}><br>
                                        </div>
                                        <div class="form-group col-md-6 mb-3">
                                            <input class="form-control" data-toggle="toggle" data-onstyle="success"
                                                    data-offstyle="danger" data-width="100%" data-on="Send Allowed" data-off="Send Blocked"
                                                    type="checkbox" value="1" name="permission_send" {{ $user->permission_send == "1" ? 'checked' : '' }}>
                                            <br>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <input class="form-control" data-toggle="toggle" data-onstyle="success"
                                                    data-offstyle="danger" data-width="100%" data-on="Buy Allowed" data-off="Buy Blocked"
                                                    type="checkbox" value="1" name="permission_buy" {{ $user->permission_buy == "1" ? 'checked' : '' }}>
                                            <br>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <input class="form-control" data-toggle="toggle" data-onstyle="success"
                                                    data-offstyle="danger" data-width="100%" data-on="Sell Allowed" data-off="Sell Blocked"
                                                    type="checkbox" value="1" name="permission_sell" {{ $user->permission_sell == "1" ? 'checked' : '' }}>
                                                    <br />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr />
                        <div class="col-md-12">
                            <div class=" text-center"
                                style="background-color:#66a3ac ;padding:10px; margin-bottom:10px;">
                                <h2 class="text-center" style="background-color:#66a3ac;">Verifications </h2>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="row">
                                <div class=" col-md-3">
                                    <h3 style="margin-top: 57px;">ID image</h3>
                                </div>
                                <div class=" col-md-6">
                                <a href="{{asset('storage/images/attach/'.$user->id_photo)}}"
                                    download=""><img id="address_photo_Preview" style=" margin-top:10px;" width="350" 
                                        src="{{asset('storage/images/attach/'.$user->id_photo)}}" alt="" /></a>

                                </div>
                                <div class=" col-md-3">
                                    <select style="margin-top: 57px;" name="id_photo_status"
                                        class="form-control select input-lg">
                                        <option class="bg-warning" value="0" @if($user->id_photo_status ==0) selected
                                            @endif>Unverified</option>
                                        <option class="bg-success" value="1" @if($user->id_photo_status ==1) selected
                                            @endif>Verified</option>
                                        <option class="bg-danger" value="2" @if($user->id_photo_status ==2) selected
                                            @endif>Rejected</option>
                                    </select>
                                    <br />
                                    <input type="file" name="id_photo" id="id_photo">
                                </div>
                            </div>
                            <div class="row">
                                <div class=" col-md-3">
                                    <h3 style="margin-top: 57px;">Proof of address</h3>
                                </div>
                                <div class=" col-md-6">
                                <a href="{{asset('storage/images/attach/'.$user->address_photo)}}"
                                    download=""><img id="address_photo_Preview" style=" margin-top:10px;" width="350" 
                                        src="{{asset('storage/images/attach/'.$user->address_photo)}}" alt="" /></a>
                                </div>

                                <div class=" col-md-3">
                                    <select style="margin-top: 57px;" name="address_photo_status"
                                        class="form-control select input-lg">
                                        <option class="bg-warning" value="0" @if($user->address_photo_status ==0)
                                            selected @endif>Unverified</option>
                                        <option class="bg-success" value="1" @if($user->address_photo_status ==1)
                                            selected @endif>Verified</option>
                                        <option class="bg-danger" value="2" @if($user->address_photo_status ==2)
                                            selected @endif>Rejected</option>
                                    </select>
                                    <br />
                                    <input type="file" name="address_photo" id="address_photo">
                                </div>
                            </div>
                            <div class="row">
                                <div class=" col-md-3">
                                    <h3 style="margin-top: 57px;">Personal image with ID </h3>
                                </div>
                                <div class=" col-md-6">
                                <a href="{{asset('storage/images/attach/'.$user->id_photo_id)}}"
                                    download=""> <img id="id_photo_id_Preview" style=" margin-top:10px;"
                                        width="350" 
                                        src="{{asset('storage/images/attach/'.$user->id_photo_id)}}" alt="" /></a>

                                </div>
                                <div class=" col-md-3">
                                    <select style="margin-top: 57px;" name="id_photo_id_status"
                                        class="form-control select input-lg">
                                        <option class="bg-warning" value="0" @if($user->id_photo_id_status ==0) selected
                                            @endif>Unverified</option>
                                        <option class="bg-success" value="1" @if($user->id_photo_id_status ==1) selected
                                            @endif>Verified</option>
                                        <option class="bg-danger" value="2" @if($user->id_photo_id_status ==2) selected
                                            @endif>Rejected</option>
                                    </select>
                                    <br />
                                    <input type="file" name="id_photo_id" id="id_photo_id">
                                </div>
                            </div>
                            <div class="row">
                                <div class=" col-md-4">
                                    <h3 style="margin-top: 57px;">User</h3>
                                </div>
                                <div class=" col-md-4">
                                </div>
                                <div class=" col-md-4">
                                    <select style="margin-top: 57px;" name="verified"
                                        class="form-control select input-lg">
                                        <option class="bg-warning" value="0" @if($user->verified ==0) selected
                                            @endif>Unverified</option>
                                        <option class="bg-success" value="1" @if($user->verified ==1) selected
                                            @endif>Verified</option>
                                    </select>

                                </div>
                            </div>

                        </div>

                        <hr />
                        <div class="col-md-12">
                            <div class=" text-center"
                                style="background-color:#66a3ac ;padding:10px; margin-bottom:10px;">
                                <h2 class="text-center" style="background-color:#66a3ac;">Personal Note</h2>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <!-- <label class="col-md-12"><strong style="text-transform: uppercase;">My Private Note About </strong></label> -->
                                <div class="col-md-12">
                                    <textarea id="area-top" class="form-control" rows="15" name="admin_note" placeholder="Enter you private note here...">@if(isset($user->admin_note) && $user->admin_note != "") {{ $user->admin_note }} @endif</textarea>
                                </div>
                            </div>
                        </div>
                        <br>
                        <hr>
                        <button type="submit" class="btn btn-lg btn-primary btn-block">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal for Edit button -->
    <div id="changepass" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel"><strong>Change Password</strong></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body">
                    <form role="form" method="POST" action="{{route('user.passchange', $user->id)}}"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="control-label"><strong>Password</strong></label>
                            <input id="password" type="password" class="form-control" name="password"
                                placeholder="Passowrd" required>
                            @if ($errors->has('password'))
                            <span class="help-block">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                            @endif
                        </div>
                        <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                            <label for="password-confirm" class="control-label"><strong>Confirm
                                    Password</strong></label>
                            <input id="password-confirm" type="password" class="form-control"
                                placeholder="Confirm Passowrd" name="password_confirmation" required>
                            @if ($errors->has('password_confirmation'))
                            <span class="help-block">
                                <strong>{{ $errors->first('password_confirmation') }}</strong>
                            </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-block">
                                Change Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>



    @endsection
    @section('script')
    <script type="text/javascript">
  function copy(containerid) {
    if (document.selection) {
      let range = document.body.createTextRange();
      range.moveToElementText(document.getElementById(containerid));
      range.select().createTextRange();
      document.execCommand('copy');

    } else if (window.getSelection) {
      let range = document.createRange();
      range.selectNode(document.getElementById(containerid));
      window.getSelection().addRange(range);
      document.execCommand('copy');
    }
  }
</script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
    </script>

    <script>
    $(document).ready(function() {
        $(".status").change(function() {

            var status = $(this).val();
            var id = $(this).attr('data-id');
            console.log(status, id);
            var token = "{{csrf_token()}}";
            $.ajax({
                type: "POST",
                url: "{{route('advertise.statusChange')}}",
                data: {
                    'status': status,
                    'id': id,
                    '_token': token
                },
                success: function(data) {
                    if (data == 'false') {
                        console.log($('#status' + id).attr('data-prev'))
                        $('#status' + id).val($('#status' + id).attr('data-prev'));

                        swal("You can’t change the status due to insufficient balance", "",
                            "warning");
                    } else {

                        swal("Status Updated", "", "success");

                    }


                }
            });
        });


       
    });
    $(document).ready(function() {
    $("#city").keyup(function (event) {
        var val = $('[name=city]').val();


        if (/\s/.test(val)) {
            // It has any kind of whitespace
            console.log('yes');
            var arr = val.split(' ');
            var val = arr[arr.length - 1];
        }
        if (val == '') {
            return false;
        }

        $.ajax({
            url: '/city/' + val,
            type: 'GET',
            cache: false,
            async: false,

            success: function (data) {
                console.log(data);
                var availableWords = data;
                autocomplete(document.getElementById("city"), availableWords);
            },
            error: function (data) {
                console.log(data);

            }

        });

    });
  });
  function autocomplete(inp, arr) {
    /*the autocomplete function takes two arguments,
    the text field element and an array of possible autocompleted values:*/
    var currentFocus;
    console.log(arr)
    console.log(arr.length)
    /*execute a function when someone writes in the text field:*/
    // inp.addEventListener("input", function(e) {
    // console.log(this.value)
    var a, b, i, val;
    /*close any already open lists of autocompleted values*/
    closeAllLists();
    // if (!val) { return false;}
    currentFocus = -1;
    /*create a DIV element that will contain the items (values):*/
    a = document.createElement("DIV");
    a.setAttribute("id", this.id + "autocomplete-list");
    a.setAttribute("class", "autocomplete-items");
    /*append the DIV element as a child of the autocomplete container:*/
    inp.parentNode.appendChild(a);
    /*for each item in the array...*/
    for (i = 0; i < arr.length; i++) {
        console.log(arr[i]);
        /*check if the item starts with the same letters as the text field value:*/
        //   if (arr[i].substr(0, val.length).toUpperCase() == val.toUpperCase()) {
        /*create a DIV element for each matching element:*/
        b = document.createElement("DIV");
        b.setAttribute("class", "high");
        /*make the matching letters bold:*/
        // b.innerHTML = "<strong>" + arr[i].substr(0, val.length) + "</strong>";
        b.innerHTML += arr[i];
        /*insert a input field that will hold the current array item's value:*/
        b.innerHTML += '<input type="hidden" value="' + arr[i] + '">';
        /*execute a function when someone clicks on the item value (DIV element):*/
        b.addEventListener("click", function (e) {
            /*insert the value for the autocomplete text field:*/
            if (/\s/.test(inp.value)) {
                // It has any kind of whitespace
                console.log('yes2');
                var str = inp.value;
                var arr1 = inp.value.split(' ');
                // var val1 = arr1[arr1.length-1];
                arr1.pop();
                var str1 = arr1.join(' ');
                inp.value = str1 + ' ' + this.getElementsByTagName("input")[0].value;
                // console.log(inp.value);
                closeAllLists();
            } else {
                // console.log(this.getElementsByTagName("input"));
                inp.value = this.getElementsByTagName("input")[0].value;
                // console.log(inp.value);
                /*close the list of autocompleted values,
                (or any other open lists of autocompleted values:*/
                closeAllLists();
            }


        });
        a.appendChild(b);
        //   }
    }
    // });
    //     /*execute a function presses a key on the keyboard:*/
    inp.addEventListener("keydown", function (e) {
        var x = document.getElementById(this.id + "autocomplete-list");
        if (x) x = x.getElementsByTagName("div");
        if (e.keyCode == 40) {
            /*If the arrow DOWN key is pressed,
            increase the currentFocus variable:*/
            currentFocus++;
            /*and and make the current item more visible:*/
            addActive(x);
        } else if (e.keyCode == 38) { //up
            /*If the arrow UP key is pressed,
            decrease the currentFocus variable:*/
            currentFocus--;
            /*and and make the current item more visible:*/
            addActive(x);
        } else if (e.keyCode == 13) {
            /*If the ENTER key is pressed, prevent the form from being submitted,*/
            e.preventDefault();
            if (currentFocus > -1) {
                /*and simulate a click on the "active" item:*/
                if (x) x[currentFocus].click();
            }
        }
    });
    function addActive(x) {
        /*a function to classify an item as "active":*/
        if (!x) return false;
        /*start by removing the "active" class on all items:*/
        removeActive(x);
        if (currentFocus >= x.length) currentFocus = 0;
        if (currentFocus < 0) currentFocus = (x.length - 1);
        /*add class "autocomplete-active":*/
        x[currentFocus].addClass("autocomplete-active");
    }
    function removeActive(x) {
        /*a function to remove the "active" class from all autocomplete items:*/
        for (var i = 0; i < x.length; i++) {
            x[i].classList.removeClass("autocomplete-active");
        }
    }
    function closeAllLists(elmnt) {
        /*close all autocomplete lists in the document,
        except the one passed as an argument:*/
        var x = document.getElementsByClassName("autocomplete-items");
        for (var i = 0; i < x.length; i++) {
            if (elmnt != x[i] && elmnt != inp) {
                x[i].parentNode.removeChild(x[i]);
            }
        }
    }
    //   /*execute a function when someone clicks in the document:*/
    document.addEventListener("click", function (e) {
        closeAllLists(e.target);
    });
}
    function full1(id) {
            document.getElementById(id).setAttribute('style',
                'position:absolute;top:0;left:0;width:100%;height:100%;');

            function toggleFullScreen() {
                if ((document.fullScreenElement && document.fullScreenElement !== null) ||
                    (!document.mozFullScreen && !document.webkitIsFullScreen)) {
                    if (document.documentElement.requestFullScreen) {
                        document.documentElement.requestFullScreen();
                    } else if (document.documentElement.mozRequestFullScreen) {
                        document.documentElement.mozRequestFullScreen();
                    } else if (document.documentElement.webkitRequestFullScreen) {
                        document.documentElement.webkitRequestFullScreen(Element.ALLOW_KEYBOARD_INPUT);
                    }
                } else {
                    if (document.cancelFullScreen) {
                        document.cancelFullScreen();
                    } else if (document.mozCancelFullScreen) {
                        document.mozCancelFullScreen();
                    } else if (document.webkitCancelFullScreen) {
                        document.webkitCancelFullScreen();
                    }
                }
            }
        }
    
    
    
    
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