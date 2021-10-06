@extends('admin.layout.master')
@section('style')

@endsection
@section('body')

<div class="row">
    <div class="col-md-12">
        <div class="tile">
            <h3 class="tile-title pull-left">Filters</h3>
            <br>
            <div class="row">
                <form method="GET" class="" id="search-Form" action="">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Payment Method</label>
                                <select name="pm_id"  class="form-control select ">
                                    <option value="">Select</option>
                                    @foreach($pms as $key => $country)
                                   
                                    <option value="{{ $country->id }}"
                                        {{ $country->id == $pm_id ? 'selected' : '' }}>{{ $country->name }}
                                    </option>
                                    
                                    @endforeach
                                </select>

                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">AD ID</label>
                                <input type="text" name="add_id" id="add" value="{{$add_id ? $add_id:''}}" class="form-control input-lg" placeholder="Enter Id to search">

                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Country</label>
                                <select name="country_id" class="form-control select input-lg">
                                    <option value="">Select</option>
                                    @foreach($countries as $key => $country)

                                   
                                    <option value="{{ $country->id }}"
                                        {{ $country->id == $country_id ? 'selected' : '' }}>{{ $country->name }}
                                    </option>
                                   
                                    @endforeach
                                </select>

                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Currency</label>
                                <select name="currency" class="form-control select input-lg">
                                    <option value="">Select</option>
                                    @foreach($currencies as $key => $curren)

                                   
                                    <option value="{{ $curren->id }}"
                                        {{ $curren->id == $currency ? 'selected' : '' }}>{{ $curren->name }}
                                    </option>
                                   
                                    @endforeach
                                </select>

                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Username</label>
                                <!-- <select name="username" class="form-control select input-lg">
                                    <option value="">Select</option>
                                    @foreach($users as $key => $user)

                                   
                                    <option value="{{ $user->id }}"
                                        {{ $user->id == $username ? 'selected' : '' }}>{{ $user->username }}
                                    </option>
                                   
                                    @endforeach
                                </select> -->
                                <input type="text" name="username" value="{{$username ? $username:''}}" class="form-control " placeholder="Enter Username to search">

                            </div>
                        </div>
                        <div class="col-md-6">
                        <div class="form-group">
                        <button class="btn btn-outline btn-primary" style="margin: 31px;" type="button" id="submit">Search
                        <i class="fa fa-search"></i>
                    </button>
                        </div> </div>
                        
                    </div>
                </form>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">AD_ID</th>
                            <th scope="col">Username</th>
                            <th scope="col">Type</th>
                            <th scope="col">PM</th>
                            <th scope="col">Country</th>
                            <th scope="col">Currency</th>
                            <th scope="col">Limits</th>
                            <th scope="col">Margin</th>
                            <th scope="col">Status</th>
                            <th scope="col">AD Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($adds as $add)
                        @if($add->id < 396)
                            @continue
                        @endif
                        <tr>
                            <td data-label="ID">
                            <a class=""
                                                            href="{{route('view', ['id'=>$add->id, 'payment'=>Replace($add->paymentMethod->name)])}}">{{$add->id}}</a>
                            
                            </td>
                            <td data-label="Name">
                                <a href="@if(isset($add->user->username)) {{route('user.single', $add->user->username)}} @endif">
                                    {{$add->user->username}}
                                </a>
                            </td>
                            @if($add->add_type == 1)
                            <td data-label="Type">Sell</td>
                            @else
                            <td data-label="Type">Buy</td>
                            @endif
                            <td data-label="paymentMethod">{{$add->paymentMethod->name}} </td>
                            
                            @if(!empty($add->country))
                            <td data-label="currency">{{$add->country->name}} </td>
                            @else
                            <td data-label="currency">N/A </td>
                            @endif
                            <td data-label="currency">{{$add->currency->name}} </td>
                            <td data-label="limit">{{$add->min_amount.' '.$add->currency->name .'-'. $add->max_amount.' '.$add->currency->name}}
                                    </td>
                            <td data-label="margin">{{$add->margin}} </td>

                            <td data-label="status">
                                        <select name="status" id="status{{$add->id}}" class="status"
                                            data-id="{{$add->id}}" data-prev="{{$add->status}}">
                                            <option value="0" {{$add->status == '0'? 'selected':''  }}> Inactive
                                            </option>
                                            <option value="1" {{$add->status == '1'? 'selected':''  }}> Active
                                            </option>
                                            <option value="2" {{$add->status == '2'? 'selected':''  }}> On Vacation
                                            </option>
                                        </select>
                                    </td>
                                    <td data-sort="{{ date('Ymd', strtotime($add->created_at)) }}">{{ Timezone::convertToLocal($add->created_at ,'Y-m-d')   }}</td>
                            

                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="row">
                    <div class="col-md-12 text-center">
                        {{$adds->links()}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script>
  $(document).ready(function() {
    $('#submit').click(function() {
        // get all the inputs into an array.
        var $inputs = $('#search-Form :input');

        // not sure if you wanted this, but I thought I'd add it.
        // get an associative array of just the values.
        var values = {};
        $inputs.each(function() {
            values[this.name] = $(this).val() === undefined || $(this).val() == '' ? 'null' : $(this).val();
            console.log(this.name, $(this).val())
        });
        window.open('/adminio/ads-search'+ '/' +values['pm_id']+ '/' +values['username']+ '/' +values['add_id']+ '/' +values['country_id']+ '/' +values['currency']+ '/get', "_self")
        return false;

    });
    $('.myTable1').DataTable({
        'columnDefs': [{
                "orderable": false
            }],
            "aaSorting": [],
    });
    $(".status").change(function() {

var status = $(this).val();
var id = $(this).attr('data-id');
console.log(status, id);
var token = "{{csrf_token()}}";
$.ajax({
    type: "POST",
    url: "{{route('advertise.statusChange')}}",
    data: {
        'status': status,
        'id': id,
        '_token': token
    },
    success: function(data) {
        console.log("resposne", $data)
        if (data == 'false') {
            console.log($('#status' + id).attr('data-prev'))
            $('#status' + id).val($('#status' + id).attr('data-prev'));

            swal("You can’t change the status due to insufficient balance", "",
                "warning");
        } else {

            swal("Status Updated", "", "success");

        }


    }
});
});
  });


</script>
@endsection