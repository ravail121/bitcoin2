@extends('front.layout.master')

@section('body')
<div id="searchbuy">
    <section class="cm_whitsc1 wow fadeInUp ">
        <div class="container-fluid">
            <div class="px-md-4 px-2 py-2">
                <div class="cm_head1">
                    <h4>Buy Bitcoin</h4>
                    
                </div>
                <div class="dataTables_wrapper">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="cm_tabled1 table-responsive">
                                @include('front.trade-table', [
                                'type' => $type,
                                'offers' => $offers,
                                ])
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
    

@stop
