@extends('admin.layout.master')
 
@section('body')
    <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <div class="tile-body">
                  <div class="row">
                    <div class="col-md-12 order-md-2 mb-4">
                      <h4 class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted">{{ $page_title }}</span>
                        <div class="float-right">
                          <a href="#myModal" data-toggle="modal" class="btn btn-primary"><i class="fa fa-wrench"></i> Update Wallet Address</a>
                          <form class="d-inline" method="POST" action="{{ route('admin.withdraw.requests.reject', [ 'withdraw' => $withdraw->id ]) }}">
                              @csrf
                              <button type="submit" class="btn btn-sm btn-danger">
                                Reject
                              </button>
                          </form>
                          <form class="d-inline" method="POST" action="{{ route('admin.withdraw.requests.confirm', [ 'withdraw' => $withdraw->id ]) }}">
                              @csrf
                              <button type="submit" class="btn btn-sm btn-info">
                                Confirm
                              </button>
                          </form>
                        </div>
                      </h4>
                      <ul class="list-group mb-3">
                        <li class="list-group-item d-flex justify-content-between lh-condensed">
                          <div>
                            <h6 class="my-0">User</h6>
                          </div>
                          <span class="text-muted">
                            <a href="{{ route('user.single', [ 'user' => $withdraw->user->username ]) }}">
                              {{ $withdraw->user->username }}
                            </a>
                          </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between lh-condensed">
                          <div>
                            <h6 class="my-0">Amount</h6>
                          </div>
                          <span class="text-muted">
                            {{ $withdraw->amount }}
                          </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between lh-condensed">
                          <div>
                            <h6 class="my-0">Address</h6>
                          </div>
                          <span class="text-muted">
                            {{ $withdraw->address }}
                          </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between lh-condensed">
                          <div>
                            <h6 class="my-0">Status</h6>
                          </div>
                          <span class="text-info">
                            {{ $withdraw->status }}
                          </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between lh-condensed">
                          <div>
                            <h6 class="my-0">Created At</h6>
                          </div>
                          <span class="text-muted">
                            {{  Timezone::convertToLocal($withdraw->created_at)  }}
                          </span>
                        </li>
                      </ul>
                    </div>
                  </div>
                </div>
            </div>
        </div>
    </div>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
      <div class="modal-content">
          <form class="form-horizontal" method="post" action="{{route('admin-withdraw-address-update', $withdraw->id)}}" enctype="multipart/form-data">
              @csrf
          <div class="modal-header">
              <h4 class="modal-title" id="myModalLabel"><i class="fa fa-share-square"></i> Update Wallet Address</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
          </div>
          <div class="modal-body">

            <div class="form-group">
              <label class="col-md-12"><strong style="text-transform: uppercase;">Wallet Address</strong></label>
              <div class="col-md-12">
                <input name="address" type="text" class="form-control input-lg" value="{{$withdraw->address}}" placeholder="Wallet Address" required />
              </div>
            </div>
            <input name="id" type="hidden" value="{{$withdraw->id}}" required />
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-info" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
              <button type="submit" class="btn btn-primary bold uppercase"><i class="fa fa-send"></i> Update</button>

          </div>
          </form>
      </div>
  </div>

</div>
@endsection
