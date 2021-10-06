@extends('admin.layout.master')

@section('body')
<link rel="stylesheet" href="https://cdn.datatables.net/select/1.3.1/css/select.dataTables.min.css">
  <div class="row">
    <div class="col-md-12">
      <div class="tile">
        <h3 class="tile-title pull-left">User List</h3> 
        <div class="pull-right icon-btn">
          <form method="POST" class="form-inline" action="{{route('search.users')}}">
            @csrf
            <input type="text" name="search" class="form-control" placeholder="Search">
            <button class="btn btn-outline btn-circle  green" type="submit">
              <i class="fa fa-search"></i>
            </button>
          </form>
        </div>
        <div class="table-responsive">
          <table class="table">
            <thead>
              <tr>
                <th scope="col">Name</th>
                <th scope="col">Email</th>
                <th scope="col">Username</th>
                <th scope="col">Country</th>
                <th scope="col">Mobile</th>
                <th scope="col">Open Deals</th>
                <th scope="col"> Complete Deals</th>
                <th scope="col">Balance</th>
                <th scope="col">No. of Disputes</th>
                <th scope="col">Number of Ads</th>
                <th scope="col">User Status</th>
                <th scope="col">User Created</th>
                <th scope="col">Last Login</th>
                <th scope="col">Details</th>
              </tr>
            </thead>
            <tbody>
              @foreach($users as $user)
                <tr>
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
                  <td data-label="Details" class="form-group">
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


                      <input class="form-control" id="activation" onchange="updateStatus({{ $user->id }} ,{{$user->status}})" data-toggle="toggle" data-onstyle="success" data-offstyle="danger" data-width="100%" data-on="Active" data-off="Inactive" type="checkbox" name="status" {{$user->status ==1?'checked':''}}>
                      </td>
                </tr>
              @endforeach
            </tbody>
          </table>
          @if(!isset($ids)) {!! $users->render()!!} @endif
        </div>
      </div>
    </div>
  </div>

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
