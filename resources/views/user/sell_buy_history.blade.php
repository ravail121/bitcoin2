@extends('front.layout.master2')
@section('style')

@stop
@section('body')
<!--bitcoin blance Strat--->
<section class="bitcoin-blacnce">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 balane">
                <p><span>Trade Advertisement History</span></p>
            </div>
        </div>
    </div>
</section>

<!--bitcoin blance Strat--->


<!--Bitcoin Create Add Form-->
<section class="table pt-15">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 ml-auto">
            <!-- <div class="serach"><input type="email" class="form-control input-search" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Search"><img src="images/loupe.png"></div></div> -->
            <div class="table-div datatable next">

                <table id="example" class="table table-striped bit-table responsive">
                    <thead>
                        <tr>
                            <th>AD Type</th>
                            <th>GateWay</th>
                            <th>Payment Method</th>
                            <th>Min-Max</th>
                            <th>Raised</th>
                            <th>Action</th>
                            <th>Status</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($addvertise as $data)
                        <tr>
                            <td>
                                @if($data->add_type == 1)
                                    Want To Sell
                                @else
                                    Want To Buy
                                @endif
                            </td>
                            <td>{{$data->gateway->name}}</td>
                            <td>{{$data->paymentMethod->name}}</td>

                            <td>{{$data->min_amount.' '.$data->currency->name .'-'. $data->max_amount.' '.$data->currency->name}}</td>
                            <td>{{ Timezone::convertToLocal($data->created_at) }}</td>
                            <td><a href="{{route('sell_buy.edit', ['advertise' => $data->id, 'username' => auth()->user()->username])}}"><img src="{{ asset('new-images/edit.png') }}"></a></td>
                            @if(($data->add_type == 1 && $permission_sell) || ($data->add_type == 2 && $permission_buy))
                            <td class="select-active">
                                <select name="status" id="status{{$data->id}}" class="status form-control yellowText" data-id="{{$data->id}}" data-prev="{{$data->status}}"> 
                                    <option class="pending" value="0" {{$data->status == '0'? 'selected':''  }} > Inactive   </option>
                                    <option class="active" value="1" {{$data->status == '1'? 'selected':''  }} > Active     </option>
                                    <option class="approved-s" value="2" {{$data->status == '2'? 'selected':''  }} > On Vacation</option>
                                </select>
                                
                            </td>
                            @else
                            <td>Restricted</td>
                            @endif
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                
            </div>
            {{$addvertise->links()}}
        </div>
    </div>
</section>

<!--Bitcoin Create Add Form-->

@stop

@section('script')
<script>
    $(document).ready(function () {
        $("#crypto_id").change(function () {

           var crypto = $(this).val();
           var token = "{{csrf_token()}}";

           $.ajax({
                type :"POST",
                url :"{{route('currency.check', auth()->user()->username)}}",
                data:{
                    'crypto' : crypto,
                    '_token' : token
               },
               success:function(data){
                    $("#currency").val(data.code);

               }
           });
        });
        $(".status").change(function () {

            var status = $(this).val();
            var id    = $(this).attr('data-id');
            console.log(status, id);
            $('#status'+id).css("background-color", $('#status'+id+" option:selected").css("color"));
            var token = "{{csrf_token()}}";
            var verify = "{{\Auth::user()->verified}}";
            if(verify != 1){
                swal("Your account and documents are not verified.", "", "warning");
                $('#status'+id).val($('#status'+id).attr('data-prev')) ;
                return;
            }
            $.ajax({
                type :"POST",
                url :"{{route('advertise.statusChange')}}",
                data:{
                    'status' : status,
                    'id' : id,
                    '_token' : token
                },
               success:function(data){
                   if(data == 'false'){
                        console.log($('#status'+id).attr('data-prev'))
                        $('#status'+id).val($('#status'+id).attr('data-prev')) ;
                        swal("You can’t change the status due to insufficient balance", "", "warning");

                   }else{

                        swal("Status Updated", "", "success");

                   }
                    

               }
           });
        });
});
</script>
@stop
