@extends('front.layout.master2')

@section('body')
@if(auth()->user()->permission_withdraw && auth()->user()->permission_send)
<!--bitcoin blance Strat--->
  <section class="bitcoin-blacnce">
    <div class="container">
      <div class="row">
        <div class="col-lg-4 balane">
          <span>Send BTC</span>
        </div>
      </div>
    </div>
  </section>
  <!--bitcoin blance Strat--->
  <section class="dashboard-wallet">
      <div class="container wallet-dashbord">
          <div class="row pt-30 text-center">
              <div class="wallet">
                  <p>Your Wallet Balance</p>
                  <span class="Btc-price">
                    {{ $balance }} BTC
                  </span>
                  <div class="transtion-wallet">
                      <span class="transtion">Transaction Fee</span>
                      <span class="transtion-btc"><span id="fixed_fee">{{ $withdraw_fixed_fee }}</span> BTC + <span id="percentage_fee">{{ $withdraw_percentage_fee }}</span>%</span>
                  </div>
                  <div class="transtion-wallet">
                      <span class="transtion">Daily Limit</span>
                      <span class="transtion-btc">{{ $max_send_limit }} BTC</span>
                  </div>
              </div>
          </div>
      </div>
  </section>
  <!--Bitcoin Create Add Form-->
  <section class="pt-60 create-add">
    <div class="container">
        <div class="row">
          <form method="post" action="{{ route('user.withdraws.store') }}">
            @csrf
              @if ($errors->any())
                <div class="alert alert-danger">
                  <ul>
                    @foreach ($errors->all() as $error)
                      <li>{{ $error }}</li>
                    @endforeach
                  </ul>
                </div>
              @endif
              <div class="form-div pb-30 btc-send">
                  <div class="form-row Category-span1">
                      <div class="form-group col-md-6">
                          <span for="inputState" class="Select-control">You can Send UpTo: <span class="btc-bitcoin"><span id="max_amount">{{ $withdraw_max }}</span> BTC </span></span>
                          <input type="number" step="any" name="amount" min="0" max="{{$withdraw_max}}" class="form-control input-control" id="inputAmount" value="{{ old('amount') }}"> 
                          <a id="max">Max</a>
                      </div>
                      <div class="form-group col-md-6">
                          <span for="inputState" class="Select-control">Address</span>
                          <input type="text" class="form-control input-control" name="address" id="inputAddress" value="{{ old('address') }}">
                      </div>
                  </div>
                  <div class="container">
                      <div class="row">
                          <p class="more-details">More Details</p>
                      </div>
                  </div>
                  <div class="form-row Category-span1">
                      <div class="form-group col-md-6">
                          <span for="inputState" class="Select-control">Amount</span>
                          <input type="text" class="form-control input-control local_change" name="local_amount" id="local_amount" value="{{ old('local_amount') }}">
                      </div>
                      <div class="form-group col-md-6 select-cat-btc">
                          <span for="inputState" class="Select-control">Type</span>
                          <select class="form-control input-control local_change" name="local_currency" id="local_currency" value="{{ old('local_currency') }}">
                            @foreach($currency as $c)
                              <option value="{{$c->name}}" @if($c->id == $currencyId) selected @endif>{{$c->name}}</option>
                            @endforeach
                          </select>
                      </div>
                  </div>
                  <div class="form-row Category-span1">
                  <div class="form-group col-md-12 Description">
                      <span for="inputState" class="Select-control">Description</span>
                      <textarea name="description" id="desc" value="{{ old('description') }}" class="form-control input-control1" rows="5" placeholder="Write Your Description"></textarea>
                  </div>
              </div>
                  <button type="submit" class="btn btn-primary publish">Send BTC</button>
              </div>
          </form>
      </div>
    </div>
  </section>

<!--Bitcoin Create Add Form-->
@endif
  <section class="table pt-30">
      <div class="container">
          <div class="row">
              <div class="col-lg-6"><span class="buy">External Send Requests</span></div>

              <div class="col-lg-6 ml-auto">
                  <!-- <div class="serach"><input type="email" class="form-control input-search" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Search" /><img src="images/loupe.png" /></div> -->
              </div>
              <div class="send-btc">
                <div class="table-div datatable next table-responsive">
                  <table id="example" class="table table-striped bit-table responsive">
                      <thead>
                          <tr>
                              <th>#</th>
                              <th>Amount</th>
                              <th>Address</th>
                              <th>Status</th>
                              <th>Created Date</th>
                          </tr>
                      </thead>
                      <tbody>
                      @foreach($data_receives as $key => $item)
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
                          @if(false)
                          <td>
                            @if ($item->isPending)
                              <form method="POST" action="{{ route('user.withdraws.destroy', [ 'withdraw' => $item->id ]) }}">
                                  @csrf
                                  <!-- {{ method_field('DELETE') }} -->
                                  <button type="submit" class="btn btn-sm btn-danger">
                                    Cancel
                                  </button>
                              </form>
                            @endif
                          </td>
                          @endif
                        </tr>
                      @endforeach
                      </tbody>
                  </table>
              </div>
              {{ $data_receives->links() }}
          </div>
          </div>
      </div>
  </section>

  <section class="table pt-30">
      <div class="container">
          <div class="row">
              <div class="col-lg-6"><span class="buy">Internal Send Requests</span></div>

              <div class="col-lg-6 ml-auto">
                  <!-- <div class="serach"><input type="email" class="form-control input-search" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Search" /><img src="images/loupe.png" /></div> -->
              </div>
              <div class="send-btc">
                <div class="table-div datatable next table-responsive">
                  <table id="example" class="table table-striped bit-table responsive">
                      <thead>
                          <tr>
                              <th>#</th>
                              <th>Amount</th>
                              <th>Address</th>
                              <th>Status</th>
                              <th>Created Date</th>
                          </tr>
                      </thead>
                      <tbody>
                      @foreach($data_sends as $key => $item)
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
                          @if(false)
                          <td>
                            @if ($item->isPending)
                              <form method="POST" action="{{ route('user.withdraws.destroy', [ 'withdraw' => $item->id ]) }}">
                                  @csrf
                                  <!-- {{ method_field('DELETE') }} -->
                                  <button type="submit" class="btn btn-sm btn-danger">
                                    Cancel
                                  </button>
                              </form>
                            @endif
                          </td>
                          @endif
                        </tr>
                      @endforeach
                      </tbody>
                  </table>
              </div>
              {{ $data_sends->links() }}
          </div>
          </div>
      </div>
  </section>
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
