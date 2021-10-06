@extends('front.layout.master2')

@section('body')
<div id="searchbuy">
    <section class="cm_whitsc1 wow fadeInUp ">
        <div class="container-fluid">
            <div class="px-md-4 px-2 py-2">
                <div class="cm_head1">
        <h2>{{ucfirst($type)}} Bitcoin</h2>
        
                </div>
                <div class="dataTables_wrapper">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="cm_tabled1 table-responsive">
                            @if(empty($offers) || count($offers) == 0 )
                                <p>No Advertisement Avalaible</p><b>Be the first to advertise in your country and be the leader in your market.</b>
                            @else
                                    @include('front.trade-table', [
                                    'type' => $type,
                                    'offers' => $offers,
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
<div>
      
</div>
@stop
