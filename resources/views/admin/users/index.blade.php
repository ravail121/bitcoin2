@extends('admin.layout.master')

@section('body')
<!-- <link rel="stylesheet" href="https://cdn.datatables.net/select/1.3.1/css/select.dataTables.min.css"> -->
<style>
  .dataTables_wrapper{
    zoom: 0.7 !important;
  }
  @media screen and (min-width: 1500px) {
    #actions {
      min-width: 2000px !important;
    }
  }

  .app-content{
    min-width: fit-content;
  }
</style>
  <!-- Modal -->
  <div class="modal fade" id="filterModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Filter Users</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method="get" action="{{route('users.filter')}}">
          <div class="modal-body">
            <div class="form-group">
              <label for="country">Users Country</label>
              <select class="form-control" name="country[]" id="country" multiple="multiple">
                @foreach($countries as $c)
                  <option value="{{$c->id}}">{{$c->name}}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group">
              <label for="user_type">Marketing</label>
              <select class="form-control" name="user_type" id="user_type">
                <option value="both" selected>Both</option>
                <option value="real">Real Users</option>
                <option value="marketing">Marketing Users</option>
              </select>
            </div>
            <div class="form-group">
              <label for="document">Users Documents</label>
              <select class="form-control" name="document" id="document">
                <option value="both" selected>Both</option>
                <option value="verified">Verified</option>
                <option value="unverified">Un-Verified</option>
              </select>
            </div>
            <div class="form-group">
              <label for="email">Users Email</label>
              <select class="form-control" name="email" id="email">
                <option value="both" selected>Both</option>
                <option value="verified">Verified</option>
                <option value="unverified">Un-Verified</option>
              </select>
            </div>
            <div class="form-group">
              <label for="phone">Users Phone</label>
              <select class="form-control" name="phone" id="phone">
                <option value="both" selected>Both</option>
                <option value="verified">Verified</option>
                <option value="unverified">Un-Verified</option>
              </select>
            </div>
            <div class="form-group">
              <label for="status">Users Status</label>
              <select class="form-control" name="status" id="status">
                <option value="both" selected>Both</option>
                <option value="active">Active</option>
                <option value="inactive">In-Active</option>
              </select>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <input type="submit" class="btn btn-primary" value="Filter" />
          </div>
        </form>
      </div>
    </div>
  </div>

<form method="POST" class="form-inline" action="{{route('marketing.users.action')}}">
  {{csrf_field()}}
  <div class="row" id="actions">
    <div class="col-xl-2 col-lg-2 col-md-2 col-sm-6 pb-3">
        <!-- <select name="country[]" id="country" class="form-control w-100 country"  multiple="multiple">
          @foreach($countries as $c)
            <option value="{{$c->id}}" @if(isset($ids)) {{ (in_array($c->id, $ids)) ? 'selected' : '' }} @endif >{{$c->name}}</option>
          @endforeach
        </select> -->
      <button type="button" class="btn btn-outline-secondary m-1"  data-toggle="modal" data-target="#filterModal">Filter Users</button>
      <a href="{{route('users')}}" class="btn btn-outline-danger m-1">Reset Filter</a>
    </div>
    <div class="col-xl-10 col-lg-10 col-md-10 col-sm-6">
      <input type="submit" name="submit" class="m-1 btn btn-success" value="Update Login" />
      <input type="submit" name="submit" class="m-1 btn btn-success" value="Add Balance" />
      <input type="submit" name="submit" class="m-1 btn btn-warning" value="Subtract Balance" />
      <input type="submit" name="submit" class="m-1 btn btn-danger" value="Nullify Balance" />
      <input type="submit" name="submit" class="m-1 btn btn-success" value="Activate ADs" />
      <input type="submit" name="submit" class="m-1 btn btn-danger" value="Deactivate ADs" />
      <input type="submit" name="submit" class="m-1 btn btn-danger" value="Deactivate Users" />
      <input type="submit" name="submit" class="m-1 btn btn-warning" value="Update Rating" />
      <input type="submit" name="submit" class="m-1 btn btn-danger" value="Keep Fraction" />  
      <input type="submit" name="submit" class="m-1 btn btn-success" value="Activate Users" />
      <input type="submit" name="submit" class="m-1 btn btn-danger" value="Delete Users" />      
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="tile">
        <div class="pull-right icon-btn">
          <small class="mr-3">Total User: {{$total_users}} - Total Balance: {{$total_balance}}</small>
          <input type="text" name="search" id="search" class="form-control" placeholder="Search">
          <button class="btn btn-outline btn-circle  green" onclick="searchUser()" type="button">
            <i class="fa fa-search"></i>
          </button>
        </div>
        <div class="table-responsive">
          <table class="table" id="users-table" style="overflow:auto;">
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
                <th scope="col">Disputes</th>
                <th scope="col">ADs</th>
                <th scope="col">User Status</th>
                <th scope="col">User Created</th>
                <th scope="col">Last Login</th>
                <!-- <th scope="col">Details</th> -->
              </tr>
            </thead>
            <tbody>
              @foreach($users as $user)
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
                  </a> <i class="fa fa-circle @if(strpos($user->email, '@tbe.email') !== false && $user->status == 1) text-warning @elseif(strpos($user->email, '@tbe.email') === false && $user->status == 1) text-success @else text-danger @endif}}" aria-hidden="true"></i></td>
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
                  <td data-label="Balance">{{number_format((float)abs($user->blnce) , 8, '.', '')}}</td>
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
                  <!-- <td data-label="Details" class="form-group">
                    <form class="" action="index.html" method="post">
                    
                      @csrf
                      <input type="hidden" value="{{ $user->id }}" id="activationUser">
                      <input type="hidden" value="{{ csrf_token() }}" id="csrf_token">
                      <style>
                        .toggle-off.btn {
                            padding-left: 0px;}
                        .btn {   
                        padding: 0.375rem 0.45rem;}
                      </style>

                      <a data-width="49%" href="{{route('user.delete', $user->id)}}" class="btn btn-danger mr-1">Delete</a>
                      <input class="form-control float-right" id="activation" onchange="updateStatus({{ $user->id }} ,{{$user->status}})" data-toggle="toggle" data-onstyle="success" data-offstyle="danger" data-width="49%" data-on="Active" data-off="Inactive" type="checkbox" name="status" {{$user->status ==1?'checked':''}}>
                    </form>
                  </td> -->
                </tr>
              @endforeach
            </tbody>
          </table>
          @if(false && !isset($ids)) {!! $users->render()!!} @endif
        </div>
      </div>
    </div>
  </div>
</form>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.23/css/jquery.dataTables.min.css">
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.min.js"></script>
<script>
  $('#country')
    .multiselect({
      buttonWidth: '100%',
      allSelectedText: 'All',
      maxHeight: 200,
      includeSelectAllOption: true
    })
    //.multiselect('updateButtonText');
</script>

<script>
  $(document).ready(function () {
    $("#select-all").change(function () {
        $("input:checkbox").prop('checked', $(this).prop("checked"));
    })
  });

  
</script>

<script>
  $(document).ready(function() {
    $('#users-table').DataTable( {
        "paging":   false,
        // "ordering": false,
        "info":     false,
        "searching":   false
    } );
  } );
</script>

<script>

  function searchUser(){
    window.location.href = "{{route('search.users.get')}}?search="+ $('#search').val();
  }
  // $('#country').on('change', function(){ 
  //   if($('#country').val() != "") window.location.href = "/adminio/users-country/"+ $('#country').val();
  // });
</script>

  <script>
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
  </script>

@endsection
