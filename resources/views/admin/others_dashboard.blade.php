@extends('admin.layout.master')

@section('css')
<style>
rect:nth-child(even) {
    fill: #17a2b8;
}

rect:nth-child(odd) {
    fill: #19b952;
}

.card-header {
    padding: 0.40rem 1.25rem;
    /*background: #8c7ae6;*/
    background: #2f353b;
    color: white;
    font-size: 20px;
}

.widget-small .info h4 {
    font-size: 15px;
}

.widget-small {
    margin-bottom: 0px;
}

.card {
    margin-bottom: 20px !important;
    border: 1px solid #2f353b;
}

@media (min-width:312px) and (max-width:480px) {
    .widget-small {
        margin-bottom: 20px !important;
    }
}

canvas {
    width: 1000px;
    height: 400px;
}
</style>
@stop
@section('body')

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <i class="icon fa fa-users"></i> User Panel Shortcut
            </div>
            <div class="card-body">

                <div class="row">

                    <div class="col-md-3" style="margin-top: 5px;">
                        <a href="/adminio/pending/ticket" class="text-decoration">
                            <div class="widget-small primary "><i class="icon fa fa-phone fa-3x"></i>
                                <div class="info">
                                    <h4>Users Open Tickets </h4>
                                    <p><b>{{\App\Models\Ticket::where('status', 1)->orWhere('status',2)->orWhere('status',3)->count()}}</b>
                                    </p>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3" style="margin-top: 5px;">
                        <a href="{{route('support.admin.index')}}" class="text-decoration">
                            <div class="widget-small primary "><i class="icon fa fa-phone fa-3x"></i>
                                <div class="info">
                                    <h4>Users Closed Tickets </h4>
                                    <p><b>{{\App\Models\Ticket::where('status', 9)->count()}}</b></p>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection