@extends('front.layout.master2')

@section('style')

@stop
@section('body')
<!--bitcoin blance Strat--->
<section class="bitcoin-blacnce">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 balane">
                <div class="row">
                    <div class="col-8"><p><span>Support Ticket</span> </p></div>
                    <div class="col-4">
                        <a class="btn btn-primary pull-right ml-3" href="{{route('add.new.ticket')}}"> <i class="fa fa-plus"></i> New Ticket</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

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
<div class="alert alert-info text-center" role="alert">
    Dear user, do not open multiple tickets regarding the same issue, since it will increase our response time. Our current response time is 1-2 days
</div>
<!--bitcoin blance Strat--->
<!--Bitcoin Create Add Form-->
<section class="table pt-15">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 ml-auto">
            <!-- <div class="serach"><input type="email" class="form-control input-search" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Search"><img src="images/loupe.png"></div></div> -->
            <div class="table-div datatable next complete table-responsive">

                <table id="example" class="table table-striped bit-table responsive">
                    <thead>
                        <tr>
                            <th style="width:10% !important;"> Ticket Id </th>
                            <th> Subject </th>
                            <th> Raised Time </th>
                            <th> Status </th>
                            <th> Action </th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach( $all_ticket as $key=>$data)
                        <tr>
                            <td>{{$data->ticket}}</td>
                            <td><b>{{$data->subject}}</b></td>
                            <td>{{    Timezone::convertToLocal($data->created_at,'F dS, Y - h:i A')     }}</td>
                            <td>
                                @if($data->status == 1)
                                    <button class="btn btn-warning"> Opened</button>
                                @elseif($data->status == 2)
                                    <button type="button" class="btn btn-success">  Replied </button>
                                @elseif($data->status == 3)
                                    <button type="button" class="btn btn-info"> Customer Reply </button>
                                @elseif($data->status == 9)
                                    <button type="button" class="btn btn-danger">  Closed </button>
                                @endif
                            </td>
                            <td>
                                <a href="{{route('ticket.customer.reply', $data->ticket )}}" class="btn btn-secondary"><b>View</b></a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                
            </div>
            {{$all_ticket->links()}}
        </div>
    </div>
</section>

<!--Bitcoin Create Add Form-->
<style>
strong.support_heading{
    font-size: 24px;
}

@media screen and (max-width:320px)
{
    strong.support_heading{
    font-size: 18px;
}
}
</style>
@endsection

