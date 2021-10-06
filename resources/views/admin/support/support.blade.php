@extends('admin.layout.master')

@section('body')

    <div class="row">
                <div class="col-md-12">
                    <div class="tile">
                        <h3 class="tile-title ">{{$page_title}}</h3>
                        <div class="tile-body">
                            <table class="table table-striped table-bordered table-hover order-column">
                                <thead>
                                <tr>
                                    <th> Ticket Id </th>
                                    <th> Customer Name </th>
                                    <th> Subject </th>
                                    <th> Raised Time </th>
                                    <th> Status </th>
                                    <th> Action </th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach( $all_ticket as $key=>$data)
                                    @if(isset($data->user_member->username))
                                    <tr>
                                        <td>{{$data->ticket}}</td>
                                        <td><a href="{{route('user.single', $data->user_member->username)}}"><b>{{$data->user_member->username}}</b></a></td>
                                        <td><b>{{$data->subject}}</b></td>
                                        <td>{{   Timezone::convertToLocal($data->created_at,'F dS, Y - h:i A') }}</td>
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
                                            <a class="btn btn-primary" href="{{route('ticket.admin.reply', $data->ticket )}}">View</a>
                                        </td>
                                    </tr>
                                    @endif
                                @endforeach
                                </tbody>
                            </table>
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    {{$all_ticket->links()}}
                                </div>
                            </div>

                        </div>
                    </div>
                    <!-- END EXAMPLE TABLE PORTLET-->
                </div>
            </div>

@endsection