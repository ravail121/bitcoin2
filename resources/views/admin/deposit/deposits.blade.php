@extends('admin.layout.master')

@section('body')
    <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <div class="tile-header">
                  <h3 class="tile-title d-inline">
                    {{ $page_title }}
                  </h3>
                  <!-- <form class="form-inline float-right" method="get">
                    <div class="input-group mb-2 mr-sm-2">
                      <div class="input-group-prepend">
                        <div class="input-group-text">Filter By Type:</div>
                      </div>
                      <select class="form-control" name="type">
                        <option selected disabled>Choose here</option>
                        <option @if(request()->has('type') && request()->get('type') === 'deposit') selected @endif value="deposit">Deposit</option>
                        <option @if(request()->has('type') && request()->get('type') === 'withdraw') selected @endif value="withdraw">Withdraw</option>
                      </select>
                    </div>
                    <button type="submit" class="btn btn-primary mb-2">Filter</button>
                  </form> -->
                </div>
                <div class="tile-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                              <tr>
                                  <th scope="col">#</th>
                                  <th scope="col">TxID</th>
                                  <th scope="col">User</th>
                                  <th scope="col">Type</th>
                                  <th scope="col" title="Click address for filter">Address</th>
                                  <th scope="col">Status</th>
                                  <th scope="col">Confirmations</th>
                                  <th scope="col">Amount</th>
                                  <th scope="col">Fee</th>
                                  <th scope="col">Created At</th>
                              </tr>
                            </thead>
                            <tbody>
                              @foreach($data as $key => $item)
                                  <tr>
                                      <td>
                                        {{ $item->id }}
                                      </td>
                                      <td title="{{ $item->txid }}">
                                        {{ str_limit($item->txid, 10) }}
                                      </td>
                                      <td data-label="User">
                                        <a href="{{ route('user.single', [ 'user' => $item->user->username ]) }}">
                                          {{ $item->user->username }}
                                        </a>
                                      </td>
                                      <td>
                                        <span class="badge badge-info">
                                          {{ $item->type }}
                                        </span>
                                      </td>
                                      <td>
                                        <form>
                                          <input type="submit" class="btn btn-link p-0" name="address" value="{{ $item->address }}" title="Click for filter">
                                        </form>
                                      </td>
                                      <td>
                                        <span class="badge badge-{{ $item->isComplete ? 'success' : 'warning' }}">
                                          {{ $item->status }}
                                        </span>
                                      </td>
                                      <td>
                                        {{ $item->confirmations }}/1
                                      </td>
                                      <td>
                                        {{ $item->amount }}
                                      </td>
                                      <td>
                                        {{ $item->fee }}
                                      </td>
                                      <td>
                                        {{   Timezone::convertToLocal($item->created_at)  }}
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
