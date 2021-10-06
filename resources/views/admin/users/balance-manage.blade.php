@extends('admin.layout.master')

@section('body')
    <style>
        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
        }
    </style>


    <div class="row">
        <div class="col-md-8">
            <div class="tile">
                <h4 class="tile-title">
                    <i class="fa fa-cogs"></i> Update Profile
                </h4>
                <div class="tile-body">
                    <form id="form" method="POST" action="{{route('user.balance.update')}}"
                          enctype="multipart/form-data" name="editForm">
                        {{ csrf_field() }}
                        <input type="hidden" name="id" value="{{$user->id}}">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label> <strong>Name</strong></label>
                                <input data-toggle="toggle" checked data-onstyle="success" data-offstyle="danger" data-on=" <i class='fa fa-plus'></i> Add Money" data-off="<i class='fa fa-minus'></i> Substruct Money"  data-width="100%" data-height="20" type="checkbox" name="operation">

                            </div>
                            <div class="form-group col-md-6">
                                <label><strong>Select Gateway</strong></label>
                               <select class="form-control" name="gateway_id">
                                   @foreach($balance as $data)
                                <option  value="{{$data->gateway_id}}">
                                    {{$data->gateway->currency}}</option>
                                @endforeach
                               </select>
                            </div>

                        </div>

                        <div class="row">
                            <div class="form-group col-md-12">
                                <label><strong>Amount</strong></label>
                                <div class="form-group">
                                    <input type="text" name="amount" class="form-control input-lg">
                                </div>
                                @if ($errors->has('amount'))
                                    <span class="help-block">
                                                <strong>{{ $errors->first('amount') }}</strong>
                                            </span>
                                @endif
                            </div>

                        </div>

                        <div class="row">
                            <div class="form-group col-md-12 ">
                                <label> <strong>Message</strong></label>
                                <textarea name="message" id="" class="form-control"  rows="5" required></textarea>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-lg btn-primary btn-block">Update</button>

                    </form>
                </div>
            </div>

        </div>

        <div class="col-md-4">
            <div class="tile">
                <h4 class="tile-title">
                    <i class="fa fa-money"></i> Current Balance
                </h4>
                <div class="title-body">
                        @if( file_exists($user->image))
                            <img src=" {{url('assets/user/images/'.$user->image)}} " class="img-responsive propic"
                                 alt="Profile Pic">
                        @else

                            <img src=" {{url('assets/user/images/user-default.png')}} " class="img-responsive propic"
                                 alt="Profile Pic">
                        @endif

                        <br>
                    <h5>User Name : {{ $user->username }}</h5>
                    <h5>Name : {{ $user->name }}</h5>
                            @foreach($balance as $data)
                    <h6> {{ $data->gateway->name }} BALANCE : {{number_format(floatval($data->balance), $basic->decimal, '.', '')}} {{$data->gateway->currency}}</h6>
                            @endforeach
                    <hr>
                    <p><strong>Last Login : {{ Carbon\Carbon::parse($user->login_time)->diffForHumans() }}</strong> <br></p>
                </div>
            </div>

        </div>


    </div>


@endsection

