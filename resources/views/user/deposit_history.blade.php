@extends('front.layout.master2')

@section('body')
<!--bitcoin blance Strat--->
<section class="bitcoin-blacnce">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 balane">
                <p><span>{{ $title }}</span></p>
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
            <div class="table-div datatable next table-responsive">

                <table id="example" class="table table-striped bit-table responsive">
                    <thead>
                        <tr>
                            <th>TxID</th>
                            <th>Status</th>
                            <th>Confirmations</th>
                            <th>Amount</th>
                            <th>Created At</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                      @foreach($data as $item)
                        <tr >
                          <td>
                            {{ $item->txid }}
                          </td>
                          <td>
                            <span >
                              {{ $item->status }}
                            </span>
                          </td>
                          <td>{{ $item->confirmations }}/1</td>
                          <td>{{ $item->amount }}</td>
                          <td>{{  Timezone::convertToLocal($item->created_at) }}</td>
                        </tr>
                      @endforeach
                    </tbody>
                </table>
                
            </div>
            {{ $data->links() }}
        </div>
    </div>
</section>

<!--Bitcoin Create Add Form-->

@stop
