@extends('front.layout.master2')

@section('body')
@if(auth()->user()->permission_send)
  <div class="row">
    <div class="col-md-6 offset-md-3 col-sm-12">
      <form class="form-horizontal" method="post" action="{{ route('user.withdraws.store') }}">
        @csrf
        <div class="jumbotron py-4">
          @if ($errors->any())
            <div class="alert alert-danger">
              <ul>
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

         
          <p>In Your Wallet: <b><span id="balance">{{ $balance }}</span> BTC</b></p>
          <p>Transaction Fee: <b><span id="fixed_fee">{{ $send_fixed_fee }}</span> BTC + <span id="percentage_fee">{{ $send_percentage_fee }}</span>%</b></p>
          <hr />
          <p>You can Send UpTo: <b><span id="max_amount">{{ $send_max }}</span> BTC</b></p>
            <!-- <label for="inputAmount">Amount in BTC  ( Max Amount = {{$send_max}} BTC ) </label> -->
            <div class="input-group">
            <input type="number" step="any" name="amount" min="0" max="{{$send_max}}" class="form-control" id="inputAmount" value="{{ old('amount') }}"> 
            
            <span class="input-group-btn">
              <a id="max" href="#" class="btn btn-primary" > Max</a>
            </span>
          </div>

          <div class="form-group">
            <label for="inputAddress">Address</label>
            <input type="text" class="form-control" name="address" id="inputAddress" value="{{ old('address') }}">
          </div>

          <hr />
          <h2>More Details</h2>
          <div class="form-group">
            <label for="desc">Description</label>
            <input type="text" class="form-control" name="description" id="desc" value="{{ old('description') }}">
          </div>

          <div class="row">
            <div class="form-group col-9">
              <label for="local_amount">Amount</label>
              <input type="text" class="form-control local_change" name="local_amount" id="local_amount" value="{{ old('local_amount') }}">
            </div>

            <div class="form-group col-3">
              <label for="local_amount">Type</label>
              <select class="form-control local_change" name="local_currency" id="local_currency" value="{{ old('local_currency') }}">
                @foreach($currency as $c)
                  <option value="{{$c->name}}" @if($c->id == $currencyId) selected @endif>{{$c->name}}</option>
                @endforeach
              </select>
            </div>
          </div>
          
          <div class="form-group">
            <input type="hidden" class="" name="type" id="inputType" value="{{ old('type') !== null ? old('type') : 1 }}">
          </div>
          <div class="form-group">
            <button class="btn btn-lg btn-primary btn-block" id="submit" type="submit">
              Send BTC
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
@endif
<div id="searchsell">
    <section class="cm_whitsc1 wow fadeInUp ">
        <div class="container">
            <div class="px-md-4 px-2 py-2">
                <div class="cm_head1">
          <h4>Send BTC Requests</h4>
        </div>
      <div class="dataTables_wrapper">
        <div class="row">
          <div class="col-md-12">
           <div class="cm_tabled1 table-responsive">
            <table class="table myTable" id="sellsearchtable">
              <thead >
                <tr>
                  <th>#</th>
                  <th>Amount</th>
                  <th>Address</th>
                  <th>Status</th>
                  <th>Created Date</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                
                  @foreach($data as $key => $item)
                    <tr>
                      <td>
                        {{ $key+1 }}
                      </td>
                      <td>{{ $item->amount }}</td>
                      <td><a href="https://www.blockchain.com/btc/address/{{ $item->address }}" target="_blank"> {{ $item->address }}</a></td>
                      <td>
                        <span >
                          {{ $item->status }}
                        </span>
                      </td>
                      <td>{{ Timezone::convertToLocal($item->created_at)  }}</td>
                      <td>
                        @if ($item->isPending)
                          <form method="POST" action="{{ route('user.withdraws.destroy', [ 'withdraw' => $item->id ]) }}">
                              @csrf
                              {{ method_field('DELETE') }}
                              <button type="submit" class="btn btn-sm btn-danger">
                                Cancel
                              </button>
                          </form>
                        @endif
                      </td>
                    </tr>
                  @endforeach
                
              </tbody>
            </table>
          </div>
          {{ $data->links() }}
        </div>
          </div>
                </div>
      </div>
    </div>
</section>
</div>
           
           
  
@stop
@section('script')
<script>
$(document).ready(function() {
  $('#max').click(function(){
      var withdraw_max = "{{$withdraw_max}}";
      var send_max = "{{$send_max}}";
      if($('#inputType').val() == 1) {
        $('#inputAmount').val(send_max);
        var amount = send_max;
        var currency = $('#local_currency').find(":selected").text();
        $.ajax({
          url: "{{route('ipn.calculateRelativeAmount')}}",
          method: "POST",
          data: {amount: amount, from: 'BTC', to: currency},
          success: function(result){
            // parsing data
            result = JSON.parse(result);
            if(!result.error){
              $('#local_amount').val(result.relative_amount);
            }
        }});
      }
      else{ 
        $('#inputAmount').val(withdraw_max);
        var amount = withdraw_max;
        var currency = $('#local_currency').find(":selected").text();
        $.ajax({
          url: "{{route('ipn.calculateRelativeAmount')}}",
          method: "POST",
          data: {amount: amount, from: 'BTC', to: currency},
          success: function(result){
            // parsing data
            result = JSON.parse(result);
            if(!result.error){
              $('#local_amount').val(result.relative_amount);
            }
        }});
      }
    });
    
  });
</script>
<script>
    // {{route('ipn.isWalletAdressExitsInSystem')}}
    $( document ).ready(function() {
      var withdraw_fixed_fee = {!! $withdraw_fixed_fee !!};
      var withdraw_percentage_fee = {!! $withdraw_percentage_fee !!};
      var send_fixed_fee = {!! $send_fixed_fee !!};
      var send_percentage_fee = {!! $send_percentage_fee !!};
      var withdraw_max = {!! $withdraw_max !!};
      var send_max = {!! $send_max !!};

      $('#inputAddress').keyup(function(){
        var address = $('#inputAddress').val();
        console.log(address);
        $.ajax({
          url: "{{route('ipn.isWalletAdressExitsInSystem')}}",
          method: "POST",
          data: {address: address},
          success: function(result){
            // parsing data
            result = JSON.parse(result);
            if(!result.error && result.isExist){
              $('#inputType').val(1);
              $('#submit').html('Send BTC');
              $('#fixed_fee').html(send_fixed_fee);
              $('#percentage_fee').html(send_percentage_fee);
              $('#max_amount').html(send_max);
              $("#inputAmount").attr({
                "max" : send_max
              });
            }
            else{
              $('#inputType').val(0);
              $('#submit').html('Request Withdrawal');
              $('#fixed_fee').html(withdraw_fixed_fee);
              $('#percentage_fee').html(withdraw_percentage_fee);
              $('#max_amount').html(withdraw_max);
              $("#inputAmount").attr({
                "max" : withdraw_max
              });
            }
        }});
      });



      $('#inputAmount').keyup(function(){
        var amount = $('#inputAmount').val();
        var currency = $('#local_currency').find(":selected").text();
        $.ajax({
          url: "{{route('ipn.calculateRelativeAmount')}}",
          method: "POST",
          data: {amount: amount, from: 'BTC', to: currency},
          success: function(result){
            // parsing data
            result = JSON.parse(result);
            if(!result.error){
              $('#local_amount').val(result.relative_amount);
            }
        }});
      });

      $('#local_currency').change(function(){
        var amount = $('#local_amount').val();
        var currency = $('#local_currency').find(":selected").text();
        $.ajax({
          url: "{{route('ipn.calculateRelativeAmount')}}",
          method: "POST",
          data: {amount: amount, from: currency, to: 'BTC'},
          success: function(result){
            // parsing data
            result = JSON.parse(result);
            if(!result.error){
              $('#inputAmount').val(result.relative_amount);
            }
        }});
      });

      $('#local_amount').keyup(function(){
        var amount = $('#local_amount').val();
        var currency = $('#local_currency').find(":selected").text();
        $.ajax({
          url: "{{route('ipn.calculateRelativeAmount')}}",
          method: "POST",
          data: {amount: amount, from: currency, to: 'BTC'},
          success: function(result){
            // parsing data
            result = JSON.parse(result);
            if(!result.error){
              $('#inputAmount').val(result.relative_amount);
            }
        }});
      });
    });
    
</script>

@endsection
