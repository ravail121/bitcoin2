@extends('front.layout.master2')
@section('body')
	<div class="row">
		<div class="col-md-8 offset-md-2 col-sm-12">
			<div class="jumbotron">
				<div class="card border-dark text-center" >
					<div class="card-header">
						<h5>Deposit via {{$page_title}}</h5>
					</div>
					<div class="card-body text-dark">
						<h6> PLEASE SEND EXACTLY <span style="color: green"> {{ $bcoin }}</span> LTC</h6>
						<h5>TO <span style="color: green"> {{ $wallet}}</span></h5>
						{!! $qrurl !!}
						<h4 style="font-weight:bold;">SCAN TO SEND</h4>
					</div>
					<div class="card-footer">
						<h6 class="text-center" style="color: red;">One Wallet Address Can Be Used Once. </h6>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection