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
                                  <th scope="col"></th>
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
                                        @if ($item->status == 'pending')
                                          <a href="{{ route('admin.send.requests.show', [ 'withdraw' => $item->id ]) }}" class="btn btn-sm btn-primary">
                                            Process
                                          </a>
                                        @endif
                                      </td>
                                     
                                  </tr>
                              @endforeach
                            </tbody>
                        </table>
                        {!! $data->links()!!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
