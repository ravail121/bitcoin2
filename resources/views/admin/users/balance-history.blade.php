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
                          <thead >
                          <tr>
                              <th>ID</th>
                              <th>Start Balance</th>
                              <th>Amount</th>
                              <th>Charge</th>
                              <th>End Balance</th>
                              <th>Details</th>
                              <th>Created At</th>
                          </tr>
                          </thead>
                          <tbody>
                          @foreach($datas as $data)

                            <tr class="@if($data->open) text-danger @endif">
                                <td>{{filter_var($data->trx, FILTER_SANITIZE_NUMBER_INT)}}</td>
                                <td>{{$data->pre_main_amo}}</td>
                                <td>{{$data->amount}}</td>
                                <td>{{$data->charge}}</td>
                                <td>{{$data->main_amo}}</td>
                                @if(strpos($data->deal_url, 'deal') !== false)
                                <td><a href="{{route('deal.view.admin', abs(filter_var($data->deal_url, FILTER_SANITIZE_NUMBER_INT)))}}">{{$data->title}}</a></td>
                                @else
                                <td>{{$data->title}}</td>
                                @endif
                                <td> {{ Timezone::convertToLocal($data->created_at ,'Y-m-d H:i:s') }}</td>
                            </tr>

                          @endforeach

                          </tbody>
                      </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
