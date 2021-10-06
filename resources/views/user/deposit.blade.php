@extends('front.layout.master')

@section('body')
<div class="row padding-pranto-top">
  @foreach($user_address as $gate)
  <div class="col-md-3 text-center margin-top-pranto">
    <div class="card border-dark">
      <div class="card-header">{{$gate->gateway->name}} Balance :</div>
      <div class="card-body text-dark">
        <h6 class="card-title">@if($gate->balance == 0) 0.0000 {{$gate->gateway->currency}} @else {{round($gate->balance, 8)}} {{$gate->gateway->currency}}@endif</h6>
      </div>
    </div>
  </div>
  @endforeach
</div>

<div class="row padding-pranto-top">
  @if (count($errors) > 0)
  <div class="col-md-12">
    <div class="alert alert-danger alert-dismissible">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
      <strong class="col-md-12"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Alert!</strong>
      @foreach ($errors->all() as $error)
      <li>{{ $error }}</li>
      @endforeach
    </div>
  </div>
  @endif

  @foreach($gates as $gate)
  <div class="col-md-3 text-center margin-top-pranto padding-pranto-bottom">
    <div class="card">
      <h5 class="card-header">{{$gate->name}}</h5>
      <div class="card-body">
        <img src="{{asset('assets/images/gateway')}}/{{$gate->id}}.jpg" style="width:100%">
      </div>
      <div class="card-footer">
        <a class="btn btn-primary btn-block" style="color: white" data-toggle="modal" data-target="#depositModal{{$gate->id}}">Deposit Now</a>
      </div>
    </div>
  </div>

  <div id="depositModal{{$gate->id}}" class="modal fade" role="dialog">
    <div class="modal-dialog">

      <div class="modal-content">
        <div class="modal-header">

          <h4 class="modal-title">Deposit via <strong>{{$gate->name}}</strong></h4>
        </div>

        <form method="post" action="{{route('deposit.confirm')}}">

          <div class="modal-body">
            {{csrf_field()}}

            <input type="hidden" name="gateway" value="{{$gate->id}}">


            <div class="form-group">
              <div class="input-group">
                <input type="text" name="amount" class="form-control input-lg" id="amount" placeholder=" Enter Amount" required>
                <div class="input-group-prepend">
                  <div class="input-group-text">{{$gate->currency}}</div>
                </div>
              </div>


            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default " data-dismiss="modal">Close
            </button>
            <button type="submit" class="btn btn-primary pull-right">Submit</button>
          </div>
        </form>
      </div>

    </div>
  </div>
  @endforeach

</div>
@stop

@section('script')

<script>
  (function($) {
    $(window).on('resize', function() {
      var bodyHeight = $(window).height();
      $('#min-height-deposit').css('min-height', parseInt(bodyHeight) - 650);
      console.log(bodyHeight)
    });
    var bodyHeight = $(window).height();
    $('#min-height-deposit').css('min-height', parseInt(bodyHeight) - 650);
    console.log(bodyHeight)


  }(jQuery))
</script>
@stop
