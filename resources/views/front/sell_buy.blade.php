@extends('front.layout.master')
@section('style')
@stop
@section('body')
<div class="row  padding-pranto-top padding-pranto-bottom">
    <div class="col-md-12">

        <div class="card">

            <div class="card-header">
                <h4>Trade advertisements</h4>
            </div>

            <div id="searchbuy">
                <section class="cm_whitsc1 wow fadeInUp ">
                    <div class="container">
                        <div class="px-md-4 px-2 py-2">
                            <div class="cm_head1">
                                <h4>Buy Bitcoin</h4>
                            </div>
                            <div class="dataTables_wrapper">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="cm_tabled1 table-responsive">


                                            @if(empty($buyOffers) || count($buyOffers) == 0 )
                                            <p>No advertisement avalaible</p><b>Be the first to advertise in your country
                                                and be the leader in your market.</b>
                                            @else
                                            @include('front.trade-table', [
                                            'type' => 'buy',
                                            'offers' => $buyOffers,
                                            ])
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
            <br>
            <div id="searchsell">
                <section class="cm_whitsc1 wow fadeInUp ">
                    <div class="container">
                        <div class="px-md-4 px-2 py-2">
                            <div class="cm_head1">
                                <h4>Sell Bitcoin</h4>
                                <hr>
                            </div>
                            <div class="dataTables_wrapper">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="cm_tabled1 table-responsive">
                                            @if(empty($sellOffers) || count($sellOffers) == 0 )
                                            <p>No advertisement avalaible</p> <b> Be the first to advertise in your country
                                                and be the leader in your market.</b>
                                            @else
                                            @include('front.trade-table', [
                                            'type' => 'sell',
                                            'offers' => $sellOffers,
                                            ])
                                            @endif

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
            
           
           
        </div>
    </div>
</div>

@endsection