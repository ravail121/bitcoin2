@extends('admin.layout.master')

@section('body')
    <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <h3 class="tile-title">
                  {{ $page_title }}
                </h3>
                <div class="tile-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                              <tr>
                                  <th scope="col">Id</th>
                                  <th scope="col">User</th>
                                  <th scope="col">Amount</th>
                                  <th scope="col">Address</th>
                                  <th scope="col">Created At</th>
                                  <th scope="col">Status</th>
                                  <th scope="col">Process</th>
                                  <th scope="col">Action</th>
                              </tr>
                            </thead>
                            <tbody>
                              @foreach($data as $key => $item)
                                  <tr>
                                      <td>
                                        {{ $item->id }}
                                      </td>
                                      <td data-label="User">
                                        <a href="{{ route('user.single', [ 'user' => $item->user->username ]) }}">
                                          {{ $item->user->username }}
                                        </a>
                                      </td>
                                      <td>
                                        {{ $item->amount }}
                                      </td>
                                      <td>
                                      <a href="https://www.blockchain.com/btc/address/{{ $item->address }}" target="_blank"> {{ $item->address }} </a>
                                      </td>
                                      <td>
                                        {{  Timezone::convertToLocal($item->created_at) }}
                                      </td>
                                      <td>{{ $item->status }}</td>
                                      <td>
                                        @if ($item->isPending)
                                          <a href="{{ route('admin.withdraw.requests.show', [ 'withdraw' => $item->id ]) }}" class="btn btn-sm btn-primary">
                                            Process
                                          </a>
                                        @endif
                                      </td>
                                      <td><a href="#myModal_{{$item->id}}" data-toggle="modal" class="btn btn-primary"><i class="fa fa-wrench"></i>Update</a></td>
                                     
                                  </tr>
                              @endforeach
                            </tbody>
                        </table>

                        @foreach($data as $item)
                          <div class="modal fade" id="myModal_{{$item->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form class="form-horizontal" method="post" action="{{route('admin-withdraw-address-update', $item->id)}}" enctype="multipart/form-data">
                                        @csrf
                                    <div class="modal-header">
                                        <h4 class="modal-title" id="myModalLabel"><i class="fa fa-share-square"></i> Update Wallet Address</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                                    </div>
                                    <div class="modal-body">

                                      <div class="form-group">
                                        <label class="col-md-12"><strong style="text-transform: uppercase;">Wallet Address</strong></label>
                                        <div class="col-md-12">
                                          <input name="address" type="text" class="form-control input-lg" value="{{$item->address}}" placeholder="Wallet Address" required />
                                        </div>
                                      </div>
                                      <input name="id" type="hidden" value="{{$item->id}}" required />
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-info" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
                                        <button type="submit" class="btn btn-primary bold uppercase"><i class="fa fa-send"></i> Update</button>

                                    </div>
                                    </form>
                                </div>
                            </div>

                          </div>
                        @endforeach
                        {!! $data->links()!!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
