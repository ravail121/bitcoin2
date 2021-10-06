@extends('admin.layout.master')

@section('body')
<link rel="stylesheet" href="https://cdn.datatables.net/select/1.3.1/css/select.dataTables.min.css">
<style>
  #users_wrapper > .row:first-child {
    display: none;
  }

  /* table.dataTable tr th.select-checkbox.selected::after {
    content: "✔";
    margin-top: -11px;
    margin-left: -4px;
    text-align: center;
    text-shadow: rgb(176, 190, 217) 1px 1px, rgb(176, 190, 217) -1px -1px, rgb(176, 190, 217) 1px -1px, rgb(176, 190, 217) -1px 1px;
  } */
</style>
<form method="POST" class="form-inline" action="{{route('marketing.users.action')}}">
{{csrf_field()}}
  <div class="row w-100 mb-3">
    <div class="col-2">
      <select name="country[]" id="country" class="form-control w-100 country"  multiple="multiple">
        <!-- <option value="" disabled selected>Select Country</option> -->
        @foreach($countries as $c)
          <option value="{{$c->id}}" @if(isset($ids)) {{ (in_array($c->id, $ids)) ? 'selected' : '' }} @endif >{{$c->name}}</option>
        @endforeach
      </select>
    </div>
    <div class="col-10">
      <input type="submit" name="submit" class="btn btn-success" value="Update Login" />
      <input type="submit" name="submit" class="btn btn-success" value="Add Balance" />
      <input type="submit" name="submit" class="btn btn-warning" value="Subtract Balance" />
      <input type="submit" name="submit" class="btn btn-danger" value="Nullify Balance" />
      <input type="submit" name="submit" class="btn btn-success" value="Activate ADs" />
      <input type="submit" name="submit" class="btn btn-danger" value="Deactivate ADs" />
      <input type="submit" name="submit" class="btn btn-danger" value="Deactivate Users" />
      <input type="submit" name="submit" class="btn btn-warning" value="Update Rating" />
      <input type="submit" name="submit" class="btn btn-danger" value="Keep Fraction" />
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="tile">
        <h3 class="tile-title pull-left">Marketing Users</h3><small class="pull-right">Total Users: {{$total_users}} - Total Balance: {{$total_balance}} BTC</small>
        
        <div class="table-responsive">
          <table id="users" class="table table-striped table-bordered" style="width:100%">
            <thead>
              <tr>
                <th scope="col"><input type="checkbox" value="1" id="select-all" class="select-checkbox"></th>
                <th scope="col">Name</th>
                <th scope="col">Email</th>
                <th scope="col">Username</th>
                <th scope="col">Country</th>
                <th scope="col">Mobile</th>
                <th scope="col">Open Deals</th>
                <th scope="col">Complete Deals</th>
                <th scope="col">Balance</th>
                <th scope="col">No. of Ranks</th>
                <th scope="col">Number of Ads</th>
                <th scope="col">User Status</th>
                <th scope="col">User Created</th>
                <th scope="col">Last Login</th>
                <!-- <th scope="col">Details</th> -->
              </tr>
            </thead>
            <tbody>
              @foreach($users as $user)
                @if(!isset($user->id)) @continue; @endif
                <tr>
                    <td data-label="Select">
                      <input type="checkbox" name="selected_users[]" value="{{$user->id}}" class="form-control select-checkbox selected_users" />
                    </td>
                  <td data-label="Name">
                    <a href="{{route('user.single', $user->username)}}">
                      {{$user->name}}
                    </a>
                  </td>
                  <td data-label="Email">{{$user->email}}</td>
                  <td data-label="Username">
                  <a href="{{route('user.single', $user->username)}}">
                  {{$user->username}}
                  </a></td>
                  @if($user->country)
                    <td data-label="Country">{{$user->country->name}}</td>
                  @else
                    <td data-label="Country"> N/A</td>
                  @endif
                  @if($user->phone)
                  <td data-label="Mobile">{{$user->phone }}</td>
                  @else
                  <td data-label="Mobile"> N/A</td>
                  @endif
                  <td data-label="Open Deals">{{$user->opendeals}}</td>
                  <td data-label="Complete Deals">{{$user->completedeals}}</td>

                   
                  
                  @if(!$user->blnce)
                  <td data-label="Balance">0</td>
                  @else
                  <td data-label="Balance">{{round($user->blnce, 8)}}</td>
                  @endif
                  <td data-label="Dispute Deals">{{$user->disputedeals}}</td>
                  @if(!$user->adds)
                  <td data-label="Advertise">0</td>
                  @else
                  <td data-label="Advertise">{{$user->adds}}</td>
                  @endif
                  @if($user->verified == 1)
                  <td data-label="Advertise">Verified</td>
                  @else
                  <td data-label="Advertise">Unverified</td>
                  
                  @endif

                  <td>@if(!empty($user->created_at)) {{\Carbon\Carbon::createFromTimeStamp(strtotime($user->created_at))->diffForHumans()}}
                      @else N/A @endif
                  </td>
                  <td>@if(!empty($user->last_login->created_at)) {{\Carbon\Carbon::createFromTimeStamp(strtotime($user->last_login->created_at))->diffForHumans()}}
                      @else N/A @endif
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
          
        </div>
      </div>
    </div>
  </div>
  </form>
  @stop
  
  <!-- <link rel="stylesheet" href="https://cdn.datatables.net/rowgroup/1.1.2/css/rowGroup.dataTables.min.css"> -->
  <link rel="stylesheet" href="https://cdn.datatables.net/select/1.3.1/css/select.dataTables.min.css">

  @section('script')
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/moment.min.js"></script>
  <script src="https://cdn.datatables.net/select/1.3.1/js/dataTables.select.min.js"></script>
  <!-- <script src="https://cdn.datatables.net/rowgroup/1.1.2/js/dataTables.rowGroup.min.js"></script> -->
  
  <script>
  $(document).ready(function () {
    $("#select-all").change(function () {
        $("input:checkbox").prop('checked', $(this).prop("checked"));
    })
  });

  
  </script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.min.js"></script>
  <script>

  $(document).ready(function () {
      $('#country').on('change', function(){
        if($('#country').val() != "")
        window.location.href = "/adminio/marketing-users/"+ $('#country').val();
      });
  });

  function updateStatus(userId,status1) {
   
    let url = 'user/update-status/'+userId;
    let status;
console.log(status1)
    if(status1) {
      status = 0;
    } else {
      status = 1;
    }
    console.log(status1)
    let data = {
      "_token": "{{ csrf_token() }}",
      "status":status
    }

    $.ajax({
      headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
      type: 'PUT',
      url: url,
      data: data,
      success: function (responce) {
        console.log(responce);
      }
    });
  }

  $('.country')
    .multiselect({
      buttonWidth: '100%',
      allSelectedText: 'All',
      maxHeight: 200,
      includeSelectAllOption: true
    })
    // .multiselect('updateButtonText');
  </script>
@endsection
