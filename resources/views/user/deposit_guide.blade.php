@extends('front.layout.master2')

@section('body')
<style>
.card_height {
        height: 210px;
      }
  
.btn {
    white-space: revert;
  }
  
@media screen and (max-width:1024px){
      .card_height {
            height: 250px;
       }
      }
@media screen and (max-width:768px) {
       .card_height {
        height: 270px;
        margin-bottom: 10px;
      }
    }

@media screen and (max-width:768px) and (min-width: 426px)
{
  .card-header {
    padding: 0.75rem 0.25rem;}
  .btn {
    white-space: revert;
    font-size:14px;
    padding :0.375rem 0px;}  
  }

@media screen and (max-width:320px) 
{
  .float-right{
    float: none !important;}
  
  }
  
</style>
<div class="row">
  @foreach($balance as $gate)
  <div class="col-md-12 margin-top-pranto">
    <div class="card border-dark">
      <div class="card-body text-dark">
        <span class="h6">{{ $gate->gateway->name }} Balance:</span>
        @if($gate->balance == 0)
        0.0000 {{$gate->gateway->currency}}
        @else
        {{round($gate->balance, 8)}} {{$gate->gateway->currency}}
        @endif
        <a class="mx-1" title="Withdraw Request" href="{{ route('user.withdraws', auth()->user()->username) }}">
          Send BTC
        </a>
        <div class="float-right">
          <span class="h6">Deposit address:</span>
          <span id="depositAddress">{{ $gate->address }}</span>
          <a title="Click to copy address" onclick="copy('depositAddress')" href="javascript:void(0)"><i class="fa fa-copy"></i></a>
        </div>
      </div>
    </div>
  </div>
  
  @endforeach
</div>

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
@stop
