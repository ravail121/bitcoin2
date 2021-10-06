@extends('front.layout.master2')

@section('body')
    @php
        $bal = \App\Models\UserCryptoBalance::where('user_id', $coin->user->id)
        ->where('gateway_id',$coin->gateway_id)->first();
      $userdef = $coin->max_amount;
      $actual = $coin->price*$bal->balance;
      if ($coin->add_type == 1){
      $max = $userdef>$actual?$actual:$userdef;
      }else{
      $max = $coin->max_amount;
      }

      $max = round($max);

    @endphp

     <!--bitcoin blance Strat--->
     <section class="bitcoin-blacnce">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 balane">
                    <p><span>{{$coin->add_type == 2 ? 'Sell':'Buy'}} {{$coin->gateway->name}} using {{$coin->paymentMethod->name}}</span></p>
                </div>
            </div>
        </div>
    </section>

    <section class="pt-30">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 bitcoin-wishes">
                    <span><b>{{$coin->user->username}}</b>  wishes to {{$coin->add_type == 1 ? 'sell':'buy'}} {{$coin->gateway->name}} {{$coin->add_type == 1 ? 'to':'from'}}  you.</span>
                </div>
            </div>
        </div>
    </section>

    <section class="pt-30">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 price">
                    <ul>
                        <li>Price  : <i class="fas fa-money-bill-alt" aria-hidden="true"></i> <span class="green-color">{{$coin->price}} {{$coin->currency->name}} / {{$coin->gateway->currency}}</span></li>
                        <li>User : <span class="price">{{$coin->user->username}}</span></li>
                        <li>Payment Method : <span class="price">{{$coin->paymentMethod->name}}</span></li>
                        <li>Trade Limit : <span class="price">{{$coin->min_amount}} - {{$max}} {{$coin->currency->name}}</span></li>
                    </ul>
                </div>
                <div class="col-md-6 toadms">
                    <div class="card margin-top-pranto">
                        <div class="card-body">
                            <button type="button" class="btn btn-dark btn-lg btn-block" data-bs-toggle="modal" data-bs-target="#termModal">{{$coin->user->username}}'s Terms</button>

                                <button type="button" class="btn btn-secondary btn-lg btn-block" data-bs-toggle="modal" data-bs-target="#paymentModal">{{$coin->user->username}}'s Payment detail</button>
                            <br />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!--Bitcoin How Much Want To Buy Form-->
    <section class="pt-60 create-add">
        <div class="container">
            <div class="row">
            @guest

                <div class="col-md-8 offset-md-2 col-sm-12 padding-pranto-bottom">
                    <div class="login-admin login-admin1">
                        <div class="login-form">
                            <a href="{{url('/register')}}" class="btn btn-primary" style="display: block;text-decoration:none">
                            Sign Up Now
                            </a>
                        </div>

                    </div>
                </div>

            @else
                @if(Auth::user()->verified ==1)

                <form action="{{route('store.deal', ['advertise' => $coin->id])}}" id="submitforms" method="POST">
                    @csrf
                    <span class="buy">How Much Want To {{$coin->add_type == 2 ? 'Sell':'Buy'}} ?</span>
                    <div class="form-div2">
                        <div class="form-div pb-30">
                            <div class="form-row Category-span-1">
                                <div class="form-group col-md-6">
                                    <span for="amount" class="Select-control">How Much Want To Buy</span>
                                    <input type="number" step="any" id="amount" name="amount"  placeholder="How Much Want To {{$coin->add_type == 2 ? 'Sell':'Buy'}}" class="form-control input-control" aria-describedby="emailHelp" />
                                    <a>{{$coin->currency->name}}</a>
                                </div>
                                <div class="form-group col-md-6 max">
                                    <span for="inCoin" class="Select-control">In Bitcoin</span>
                                    <input type="number" step="any" id="inCoin" placeholder="In {{$coin->gateway->name}}" class="form-control input-control" aria-describedby="emailHelp" />
                                    <a>{{$coin->gateway->currency}}</a>
                                </div>
                            </div>
                            <div class="form-row Category-span-1">
                                <div class="form-group col-md-12">
                                    <span for="inputState" class="Select-control"> Terms of Trade</span>
                                    <textarea class="form-control input-control1" name="detail" rows="5" placeholder="Contact Message And Others Information..."></textarea>
                                </div>
                            </div>
                            @if($coin->allow_email == 1 || $coin->allow_phone == 1 || $coin->allow_id == 1)
                            <div class="form-row Category-span1">
                                <div class="form-group col-lg-6 col-sm-12">
                                    <span class="Select-control">This advertiser requires your permission to see the following (check it to give permission)</span>

                                    <ul class="unstyled centered">
                                        @if($coin->allow_email == 1)
                                        <li>
                                            <input class="styled-checkbox permission" name="allow_email" type="checkbox" value="1" id="allow_email" @if(old('allow_email') == 1) checked @endif>
                                            <label class="form-check-label" for="allow_email">
                                                Your are allowed to see my Email Address.
                                            </label>
                                        </li>
                                        @endif
                                        @if($coin->allow_phone == 1)
                                        <li class="form-check">
                                            <input class="styled-checkbox permission" name="allow_phone" type="checkbox" value="1" id="allow_phone" @if(old('allow_phone') == 1) checked @endif>
                                            <label class="form-check-label" for="allow_phone">
                                                Your are allowed to see my phone number.
                                            </label>
                                        </li>
                                        @endif
                                        @if($coin->allow_id == 1)
                                        <li class="form-check">
                                            <input class="styled-checkbox permission" name="allow_id" type="checkbox" value="1" id="allow_id" @if(old('allow_id') == 1) checked @endif>
                                            <label class="form-check-label" for="allow_id">
                                                Your are allowed to see my uploaded ID.
                                            </label>
                                        </li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                            @endif
                            <button type="submit" class="button-send" style="display: none" id="submit" >Send Request</button>
                            
                        </div>
                    </div>
                </form>
                @else
                <div class="col-md-8 offset-md-2 col-sm-12 padding-pranto-bottom">
                    <div class="login-admin login-admin1">
                        <div class="login-form">
                            <a href="/user/{{Auth::user()->username}}/edit-profile" class="btn btn-primary" style="display: block;text-decoration:none">
                            Verification Pending
                            </a>
                        </div>

                    </div>
                </div>
                @endif

            @endguest
                
            </div>
        </div>
    </section>

    <!--Bitcoin Create Add Form-->






<!-- Modal -->
<div class="modal fade" id="termModal" tabindex="-1" aria-labelledby="termModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header terms-text">
                <h5 class="modal-title text-center" id="exampleModalLabel">Terms of {{$coin->user->username}}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>{!! str_replace("\n","<br/>", $coin->term_detail) !!}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary close" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!--load all styles -->
<!-- Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header terms-text">
                <h5 class="modal-title text-center" id="exampleModalLabel">Payment detail of {{$coin->user->username}}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>{!! str_replace("\n","<br/>", $coin->payment_detail) !!}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary close" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!--load all styles -->



@stop

@section('script')
<script>
    $(document).ready(function () {
        $('.error').hide();
        var min = "{{$coin->min_amount}}";
        var email = "{{$coin->allow_email}}";
        var phone = "{{$coin->allow_phone}}";
        var id = "{{$coin->allow_id}}";
        var max = "{{$max}}";
        var price = "{{$coin->price}}";
        var allow_email = false;
        var allow_phone = false;
        var allow_id = false;
        var amount = false;
        var permissions = email == false && phone == false && id == false ? true : false;
        // console.log(price)

        $(document).on('input', "#amount",function(){
            var val = $(this).val();
            var bitcoin = parseFloat(val)/price;
            bitcoin = bitcoin.toFixed(8);
            if(parseFloat(val) >= parseFloat(min) && parseFloat(val) <= parseFloat(max)){ 
                amount = true;
                $("#amount").css("background-color", "#87E9B9");
                $("#inCoin").val(bitcoin);
                $('.error').hide();
            }
            else{
                amount = false;
                $("#amount").css("background-color", "#F59898");
                $("#inCoin").val(' ');
                $('.error').show();
            }
            if(parseFloat(val) >= parseFloat(min) && parseFloat(val) <= parseFloat(max) && permissions){
                // $("#amount").css("background-color", "#87E9B9");
                $("#submit").css("display", "block");
                // $("#inCoin").val(bitcoin);
                // $('.error').hide();

            }else {
                // $("#amount").css("background-color", "#F59898");
                $("#submit").css("display", "none");
                // $("#inCoin").val(' ');
                // $('.error').show();
            }
        });
        $(document).on('input', "#inCoin",function(){
            var bitcoin = $(this).val();
            var coins = bitcoin * price ;
            coins =coins.toFixed(8);
            if(parseFloat(coins) >= parseFloat(min) && parseFloat(coins) <= parseFloat(max)){ 
                amount = true;
                $("#amount").css("background-color", "#87E9B9");
                $("#amount").val(parseFloat(coins));
                $('.error').hide();
            }
            else{
                amount = false;
                $("#amount").css("background-color", "#F59898");
                $("#amount").val(' ');
                $('.error').show();
            }
            if(parseFloat(coins) >= parseFloat(min) && parseFloat(coins) <= parseFloat(max) && permissions){
                // $("#amount").css("background-color", "#87E9B9");
                $("#submit").css("display", "block");
                // $("#amount").val(parseFloat(coins));
                // $('.error').hide();

            }else {
                // $("#amount").css("background-color", "#F59898");
                $("#submit").css("display", "none");
                // $("#amount").val(' ');
                // $('.error').show();
            }
        });
        $(document).on('change', ".permission",function(){
            var allow_email = $('#allow_email').is(":checked") ? $('#allow_email').val() : 0;
            var allow_phone = $('#allow_phone').is(":checked") ? $('#allow_phone').val() : 0;
            var allow_id = $('#allow_id').is(":checked") ? $('#allow_id').val() : 0;
            if(allow_email == email && allow_phone == phone && allow_id == id) permissions = true;
            else permissions = false;
            if(allow_email == email && allow_phone == phone && allow_id == id && amount){
                // $("#amount").css("background-color", "#87E9B9");
                $("#submit").css("display", "block");
                // $("#inCoin").val(bitcoin);
                // $('.error').hide();

            }else {
                // $("#amount").css("background-color", "#F59898");
                $("#submit").css("display", "none");
                // $("#inCoin").val(' ');
                // $('.error').show();
            }
        });
        $("#submitforms").submit(function(event) {
        
            var val = $('#amount').val();
            var bitcoin = parseFloat(val)/price;
            
            if(parseFloat(val) >= parseFloat(min) && parseFloat(val) <= parseFloat(max)){
                $('.error').hide();
            }else{
                event.preventDefault();  
                $('.error').show();
            }
            // var bitcoin = $('#inCoin').val();
            // if(bitcoin){
            //     var coins = bitcoin * price ;
            // coins =coins.toFixed(8);
            // if(parseFloat(coins) >= parseFloat(min) && parseFloat(coins) <= parseFloat(max)){
            //     $('.error').hide();
            // }else{
            //     event.preventDefault();  
            //     $('.error').show();
            // }
            // }
            

        });
    });
</script>
@stop
