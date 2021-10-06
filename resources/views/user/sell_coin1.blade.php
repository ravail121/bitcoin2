@extends('front.layout.master2')
@section('style')

@stop
@section('body')
<!--bitcoin blance Strat--->
<section class="bitcoin-blacnce">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 balane">
                <p><span>Create a Coin Trade Advertisement</span></p>
            </div>
        </div>
    </div>
</section>

<!--bitcoin blance Strat--->
<section class="welcome-dashboard">
    <div class="container">
        <div class="row pt-30 text-center">
            <div class="dear-heding">
                <p>Dear {{$user->name}}</p>
                <span>
                    you may only use payment accounts that are registered in your own name. You must provide your payment details in the advertisement or in the deal chat. The deal is subject to fees,
                    <span class="check-page"><a href="{{route('user.fee-structure')}}">check the fees page</a></span> for full details.
                </span>
                <p class="kind-trade">What kind of trade advertisement do you wish to create? If you wish to sell coins make sure you have coins in your Local wallet.</p>
            </div>
        </div>
        <div class="col-md-12 text-center ">
            <h2 class="coinDesc ">

            </h2>
        </div>
    </div>
</section>

<!--Bitcoin Create Add Form-->
<section class="pt-60 create-add">
    <div class="container">
            
            <form action="{{route('sell.buy', auth()->user()->username)}}" method="post">
                @csrf
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
            <div class="form-div pb-30 ">
                
                    <div class="form-row Category-span1 create-add">
                        <div class="form-group col-md-6 select-cat1">
                            <span for="coin" class="Select-control">Select AD Type </span>
                            <select required name="add_type" id="coin" class="form-control input-control">
                                <option disabled value="" selected>Select Buy / Sell</option>
                                <option value="1" {{ old('add_type') == 1 ? 'selected':'' }}>Sell</option>
                                <option value="2" {{ old('add_type') == 2 ? 'selected':'' }}>Buy</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6 select-cat1">
                            <span for="status" class="Select-control">Status</span>
                            <select required name="status" id="status" class="form-control input-control">
                                <option value="0" {{ old('status') == "0" ? "selected" : "" }}> Inactive </option>
                                <option value="1" {{ old('status') == "1" ? "selected" : "selected" }}> Active </option>
                                <option value="2" {{ old('status') == "2" ? "selected" : "" }}> On Vacation</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-row Category-span1 create-add">
                        <div class="form-group col-md-6 select-cat1">
                            <span for="category_id" class="Select-control">Payment Method Category</span>
                            <select required name="category_id" id="category_id" class="form-control input-control">
                                <option disabled value="">Select Category</option>
                                @foreach($categories as $data)
                                <option value="{{$data->id}}" {{ old('category_id') == $data->id ? 'selected':'' }}>
                                    {{$data->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-6 select-cat1">
                            <span for="crypto_id" class="Select-control">Payment Method</span>
                            <select required name="crypto_id" id="crypto_id" class="form-control input-control">
                                <option disabled value="">Select Method </option>
                                @foreach($methods as $data)
                                <option value="{{$data->id}}" {{ old('crypto_id') == $data->id || $data->id == '19' ? 'selected':'' }}>
                                    {{$data->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-row Category-span1 create-add">
                        <div class="form-group col-md-6 select-cat1">
                            <span for="country_id" class="Select-control"> Select Country</span>
                            @php 
                            if((old('country_id'))){
                                $cot= old('country_id');
                            }else{
                                $cot= $country->id;
                            }
                            @endphp
                            <select required name="country_id" id="country_id" class="form-control input-control">
                                <option disabled value="">Select Country</option>
                                @foreach($countries as $country)
                                <option value="{{ $country->id }}" @if($country->id == $cot) selected @endif>
                                    {{$country->name}}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-6 select-cat1">
                            <span for="currency" class="Select-control"> Select Currency</span>
                            @php 
                            if((old('currency_id'))){
                                $cid= old('currency_id');
                            }else{
                                $cid= $defaultCurrency->id;
                            }
                            @endphp
                            <select required name="currency_id" id="currency" class="form-control input-control">
                                <option disabled value="">Select Currency</option>
                                @foreach($currency as $data)
                                <option value="{{ $data->id }}" @if($data->id == $cid) selected @endif>
                                    {{$data->name}}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                        <div class="form-row Category-span1 create-add">
                        <div class="form-group col-md-6">
                            <span for="margin" class="Select-control">Margin</span>
                            <input class="form-control input-control" type="number"  name="margin" step="any"
                                id="margin" placeholder="Margin" value="{{ old('margin')? old('margin') : 0 }}">
                           
                        </div>
                    
                        <div class="form-group col-md-6">
                            <span for="price" class="Select-control"> Price Equation</span>
                            <input class="form-control input-control" type="text" id="price" name="price1" value="{{old('price1')}}"  readonly>
                            <input type="hidden" name="price" value="{{old('price')}}" id="price1">
                        </div>
                    </div>
                    
                
            
                
                    </div>


                    <div class="form-div2">
            <div class="form-div pb-30">
                
                    <div class="form-row Category-span1 create-add">
                        <div class="form-group col-md-12">
                            <span for="description" class="Select-control">Description </span>
                            <input type="text" name="description" class="form-control input-control" value="{{old('description')}}" id="description" aria-describedby="emailHelp" maxlength="25" placeholder="Write Your Description (Maxx 25 Characters)">
                        </div>
                        
                    </div>
                    <div class="form-row Category-span1 create-add">
                        <div class="form-group col-md-6">
                            <span for="min_amount" class="Select-control">Min. Transaction Limit</span>
                            <input type="text" name="min_amount" value="{{ old('min_amount') }}" id="min_amount" class="form-control input-control" aria-describedby="emailHelp" placeholder="Min. Amount">
                            <a class="currency-lbl">{{ $general->currency }}</a>
                        </div>
                        <div class="form-group col-md-6 max">
                            <span for="max_amount" class="Select-control">Maxx. Transaction Limit</span>
                            <input type="text" name="max_amount" id="max_amount" value="{{old('max_amount')}}" class="form-control input-control" aria-describedby="emailHelp" placeholder="Maxx. Amount">
                            <a class="currency-lbl">{{ $general->currency }}</a>
                            <span class="leave-max"> Leave it empty if you want the system to calculate it from your balance</span>
                        </div>
                    </div>
                    <div class="form-row Category-span1 create-add">
                        <div class="form-group col-md-6">
                            <span for="term_detail" class="Select-control"> Terms of Trade</span>
                            <textarea class="form-control input-control1" name="term_detail"
                                rows="5">{!! old('term_detail') !!}</textarea>
                        </div>
                        <div class="form-group col-md-6">
                            <span for="payment_detail" class="Select-control">Payment Details</span>
                            <textarea class="form-control input-control1" name="payment_detail"
                                rows="5">{!! old('payment_detail') !!}</textarea>
                        </div>
                    </div>
                        <div class="form-row Category-span1 create-add">
                        <div class="form-group col-md-6">
                            <span  class="Select-control">Verification of New User (optional)</span>
                            <span>When a user is trading with you for the first time, you can require them to:</span>
                            <ul class="unstyled centered">
                                <li>
                                <input class="styled-checkbox" name="allow_email" type="checkbox" value="1" id="defaultCheck1" @if(old('allow_email') == 1) checked @endif>
                                <label for="defaultCheck1">Allow me to see your Email Address</label>
                                </li>
                                <li>
                                <input class="styled-checkbox" name="allow_phone" type="checkbox" value="1" id="defaultCheck2" @if(old('allow_phone') == 1) checked @endif>
                                <label for="defaultCheck2">Allow me to see your phone number</label>
                                </li>
                                
                                <li>
                                <input class="styled-checkbox" name="allow_id" type="checkbox" value="1" id="defaultCheck3" @if(old('allow_id') == 1) checked @endif>
                                <label for="defaultCheck3">Allow me to see your uploaded ID</label>
                                </li>
                            </ul>
                                                        
                        </div>
                    
                            <div class="form-group col-md-6">
                            <span for="inputState" class="Select-control">PayAutomatically send greeting message to people who start a deal with you (optional)</span>
                            <textarea class="form-control input-control1" name="init_message" placeholder="Type your optional welcome message/instructions here..."
                                rows="5">{!! old('init_message') !!}</textarea>
                        </div>
                    </div>
                    

                    
                
            
                
                    </div>
                        <div class="footer-terms-ul">
                        <ul class="unstyled centered">
                        <li>
                                <input class="styled-checkbox" id="styled-checkbox-5" type="checkbox" name="agree" value="1" required>
                                <label for="styled-checkbox-5"> <span href="" class="terms-poilcy">I Agree With All <a href="{{route('terms.index')}}">Terms</a> & <a
                                    href="{{route('policy.index')}}">Policy</a></span></label>
                                </li>
                                
                            </ul></div>

                            <button type="submit" class="btn btn-primary publish">Publish Advertise</button>
                    </div>
                </form>
            
            </div>
        
</section>

<!--Bitcoin Create Add Form-->

@stop

@section('script')


<script>
$(document).ready(function() {
    // category()
    $('.error').hide();
    $(document).on('change','#max_amount',function(){
        var max=parseInt($(this).val());
        var min=parseInt($('#min_amount').val());
        if(min != '' || min != null){
            if(max < min){
                $("#max_amount").css("background-color", "#F59898");
                $("#max_amount").val(' ');
                $('.error').show();
            }else{
                $("#max_amount").css("background-color", "#87E9B9");
                $('.error').hide();
            }
        }

    })
    $(document).on('change','#min_amount',function(){
        var min=parseInt($(this).val());
        var max=parseInt($('#max_amount').val());
        if(max != '' || max != null){
            if(max < min){
                $("#max_amount").css("background-color", "#F59898");
                $("#max_amount").val(' ');
                $('.error').show();
            }else{
                $("#max_amount").css("background-color", "#87E9B9");
                $('.error').hide();
            }
        }

    })
    $(document).on('change','#category_id',function(){
        category();
    })
    $(document).on('change', "#coin", function() {
        console.log($(this).val())
        if ($(this).val() == 1) {
            var sti =
                "Once created, Your offer will be visible at buy coin page for  potential buyers to see."
        } else if ($(this).val() == 2) {
            var sti =
                "Once created, Your offer will be visible at sell coin page for  potential sellers to see."

        } else {
            var sti = '';
            $('.coinDesc').removeClass('badge badge-success');
        }
        $('.coinDesc').addClass('badge badge-success');
        $('.coinDesc').html(sti);
        var price = '{{$btc_usd}}';
        var name = 'BTC';
        getPrice(price);
    })
    let defaultCurrency = `{{ $defaultCurrency->name }}`
    $(".currency-lbl").text(defaultCurrency);

    // $(document).on('change',"#coin", function () {

    var crypto = 505; //$('#coin').find(":selected").val();

    if (crypto == 505) {
        var price = '{{$btc_usd}}';
        var name = 'BTC';
        getPrice(price);
    }

    function getPrice(price) {

        var cur = $("#currency").val();
        var token = "{{csrf_token()}}";
        $.ajax({
            type: "POST",
            url: "{{route('currency.check', auth()->user()->username)}}",
            data: {
                'crypto': cur,
                '_token': token
            },

            success: function(data) {
                console.log(data)
                $("#sizing-addon1").text(data.name);
                $("#sizing-addon2").text(data.name);
                $(".currency-lbl").text(data.name);
                if ($("#margin").val() == 0) {
                    // updated data.usd_rate * price with data.btc_rate
                    $("#price").val(Number(data.btc_rate).toFixed(2) + ' ' + data.name +
                        ' to ' + name);
                    $("#price1").val(Number(data.btc_rate).toFixed(2));
                }

                $("#margin").bind('keyup mouseup', function() {

                    var margin = $(this).val();
                    var type = $('#coin').val();
                    if(type =='1'){
                        if (margin == 0) {
                        var afterMargin = (data.btc_rate * 1) / 100;
                        $("#price").val(Number(((data.btc_rate) + afterMargin))
                            .toFixed(2) + ' ' + data.name + ' to ' + name);
                        $("#price1").val(Number(((data.btc_rate) + afterMargin))
                            .toFixed(2));    
                        }
                        var afterMargin = (data.btc_rate * margin) / 100;
                        var prr = parseFloat(Number(data.btc_rate) + Number(afterMargin)).toFixed(2);
                        if(prr <= 0 ){
                            $("#margin").val('0');
                            var afterMargin = (data.btc_rate * 1) / 100;
                            $("#price").val(Number(((data.btc_rate) +
                                    afterMargin)).toFixed(2) + ' ' + data.name +
                                ' to ' + name);
                            $("#price1").val(Number(((data.btc_rate) +
                                    afterMargin)).toFixed(2) );    
                            alert('Bitcoin price can not be zero')
                            return
                        
                        }
                        $("#price").val( prr+ ' ' + data.name + ' to ' + name);
                        $("#price1").val( prr);
                    }else{
                        if (margin == 0) {
                            var afterMargin = (data.btc_rate * 1) / 100;
                            $("#price").val(Number(((data.btc_rate) - afterMargin))
                                .toFixed(2) + ' ' + data.name + ' to ' + name);
                            $("#price1").val(Number(((data.btc_rate) - afterMargin))
                                .toFixed(2));    
                        }
                        var afterMargin = (data.btc_rate * margin) / 100;
                        var prr = parseFloat(Number(data.btc_rate) - Number(afterMargin)).toFixed(2);
                        console.log(prr, "haaha")
                        if(prr <= 0 ){
                            $("#margin").val('0');
                            var afterMargin = (data.btc_rate * 1) / 100;
                            $("#price").val(Number(((data.btc_rate) -
                                    afterMargin)).toFixed(2) + ' ' + data.name +
                                ' to ' + name);
                            $("#price1").val(Number(((data.btc_rate) -
                                    afterMargin)).toFixed(2) );    
                            alert('Bitcoin price can not be zero')
                            return
                        
                        }
                        $("#price").val( prr+ ' ' + data.name + ' to ' + name);
                        $("#price1").val( prr);
                    }
                    

                });

            }
        });

    }
    function category(){
        
        
    
       var categories = {!!json_encode($categories) !!};
       var methods  = {!!json_encode($methods) !!};
       var current=$('#category_id').val();
       console.log('otttttttttt')
       console.log(current)
       if(current !=''){
        $('#crypto_id')
        .empty()
        console.log('inn')
        var string = '';
       for(var i in methods){
        //    if(methods[i].id == $(this).val()){
              
               var cat_ids = methods[i].category_ids;
               
               
               if(cat_ids){
                var cat_ids = cat_ids.split(',');
                console.log(cat_ids)
                    
                        if (cat_ids.indexOf(current.toString()) !== -1 ){

                            string +='<option value="'+methods[i].id+'">'+methods[i].name+' </option>';
                        
                        }
                    
               }
       }
       if(string == ''){
            string +='<option value=""> No Payment Method</option>';
       }
       $('#crypto_id').append(string)
       $('#crypto_id').attr("disabled", false);
       console.log(categories)
       }
       
    
    }

    $(document).on('change', "#currency", function() {

        $("#margin").val('0');

        var cur = $(this).find(":selected").val();
        var token = "{{csrf_token()}}";
        $.ajax({
            type: "POST",
            url: "{{route('currency.check', auth()->user()->username)}}",
            data: {
                'crypto': cur,
                '_token': token
            },

            success: function(data) {
                $(".currency-lbl").text(data.name);
                $("#sizing-addon1").text(data.name);
                $("#sizing-addon2").text(data.name);

                if ($("#margin").val() == 0) {
                    $("#price").val(Number(data.btc_rate).toFixed(2) + ' ' + data
                        .name + ' to ' + name);
                    $("#price1").val(Number(data.btc_rate).toFixed(2));
                }

                $("#margin").bind('keyup mouseup', function() {

                    var margin = $(this).val();
                    var type = $('#coin').val();
                    if(type =='1'){
                        if (margin == 0) {
                            var afterMargin = (data.btc_rate * 1) / 100;
                            $("#price").val(Number(((data.btc_rate) +
                                    afterMargin)).toFixed(2) + ' ' + data.name +
                                ' to ' + name);
                            $("#price1").val(Number(((data.btc_rate) +
                                    afterMargin)).toFixed(2));
                        }

                        // if (margin != 0){
                            
                        var afterMargin = (data.btc_rate * margin) / 100;
                        // console.log("after", afterMargin, "btc", data.btc_rate, "margin", margin);
                        var prr = parseFloat(Number(data.btc_rate) + Number(afterMargin)).toFixed(2);
                        if(prr <= 0 ){
                            $("#margin").val('0');
                            var afterMargin = (data.btc_rate * 1) / 100;
                            $("#price").val(Number(((data.btc_rate) +
                                    afterMargin)).toFixed(2) + ' ' + data.name +
                                ' to ' + name);
                            $("#price1").val(Number(((data.btc_rate) +
                                    afterMargin)).toFixed(2) );
                            alert('Bitcoin price can not be zero')
                            return
                        
                        }
                        // console.log("ppr", prr, "name", data.name, "to", name);
                        $("#price").val( prr+ ' ' + data.name + ' to ' + name);
                        $("#price1").val( prr);
                    }else{
                        if (margin == 0) {
                            var afterMargin = (data.btc_rate * 1) / 100;
                            $("#price").val(Number(((data.btc_rate) -
                                    afterMargin)).toFixed(2) + ' ' + data.name +
                                ' to ' + name);
                            $("#price1").val(Number(((data.btc_rate) -
                                    afterMargin)).toFixed(2));
                        }

                        // if (margin != 0){
                            
                        var afterMargin = (data.btc_rate * margin) / 100;
                        var prr = parseFloat(Number(data.btc_rate) - Number(afterMargin)).toFixed(2);
                        if(prr <= 0 ){
                            $("#margin").val('0');
                            var afterMargin = (data.btc_rate * 1) / 100;
                            $("#price").val(Number(((data.btc_rate) -
                                    afterMargin)).toFixed(2) + ' ' + data.name +
                                ' to ' + name);
                            $("#price1").val(Number(((data.btc_rate) -
                                    afterMargin)).toFixed(2) );
                            alert('Bitcoin price can not be zero')
                            return
                        
                        }
                        $("#price").val( prr+ ' ' + data.name + ' to ' + name);
                        $("#price1").val( prr);
                    }
                    
                    

                });

            }
        });
    });

    
    

    // });
});
</script>
@stop