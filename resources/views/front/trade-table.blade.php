<table class="table myTable bit-table table-striped" id="example">
  <thead >
    <!-- <tr >
      <th scope="col" style="color:black">User</th>
      <th title="Average User Rating" scope="col" style="color:black">Rating</th>
      <th title="Total number of completed trades" scope="col" style="color:black">Trades</th>
      <th title="User BTC trading volume" scope="col" style="color:black">Volume</th>
      <th scope="col" style="color:black">Payment method</th>
      <th scope="col" style="color:black">Price/BTC</th>
      <th scope="col" style="color:black">Limits</th>
      <th scope="col" style="color:black">Detail</th>
    </tr> -->
    <tr>
      <th>User</th>
      <th class="trades">Rating</th>
      <th class="trades">Trades</th>
      <th class="trades">Volume</th>
      <th>Payment Method</th>
      <th>Price/BTC</th>
      <th class="trades">Limits</th>
      <th>Detail</th>
    </tr>
  </thead>
  <tbody>
    
  @foreach($offers as $data)
    <tr>
      <td>{!! $data->user->link_to_page !!}</td>
      <td class="trades" title="Average User Rating">{{$data->user->rating}}%</td>
      <td class="trades" title="Total number of completed trades">{{$data->trades}}</td>
      <td class="trades" title="User BTC trading volume">{{     number_format((float)$data->trade_btc, 2, '.', '')}} BTC</td>
      <td>
        {{ isset($data->paymentMethod) ? $data->paymentMethod->name : ''}}
        <div class="pull-right indShow"> <i class="fa fa-info-circle text-primary" aria-hidden="true"></i>
        <span class="indShowtext" style=" display:inline-block;word-wrap: break-word;">{!! isset($data->description) ? wordwrap( $data->description,39,"<br>\n") : ''  !!}</span>
</div>
      </td>
      
      <td>{{$data->price}}<br />{{$data->currency->name}}</td>
      @php
        $bal = \App\Models\UserCryptoBalance::where('user_id', $data->user_id)->first();
        $userdef = $data->max_amount;
        $actual = $data->price*$bal->balance;
        if ($data->add_type == 1){
        $max = $userdef>$actual?$actual:$userdef;
        }else{
        $max = $data->max_amount;
        }
        $max = round($max);

      @endphp
      <td class="trades">
        @if($data->add_type == 1)
          {{$data->min_amount.'-'.$max}}<br />{{$data->currency->name}}
        @elseif($data->add_type == 2)
          {{$data->min_amount.'-'.$max}}<br />{{$data->currency->name}}
        @endif
      </td>
      <td>
      @if($data->user_id == \Auth::id())
      <p><a href="{{route('sell_buy.edit', ['advertise' => $data->id, 'username' => auth()->user()->username])}}">Edit</a></p>

      @else

        @if($data->add_type == 1 && ($data->user_id != \Auth::id()))
        <p><a href="{{ route('view', ['id'=>$data->id, 'payment'=> Replace($data->paymentMethod->name)]) }}">
            {{ucfirst($type)}}  </a></p>
          @elseif($data->add_type == 2 &&($data->user_id != \Auth::id()))
          <p><a href="{{ route('view', ['id'=>$data->id, 'payment'=> Replace($data->paymentMethod->name)]) }}">
            {{ucfirst($type)}}</a></p>
        @endif
      @endif
      </td>
    </tr>
  @endforeach
 
  </tbody>
  
      
  
</table>
{{ $offers->links() }}


<script src="{{ asset('new-js/bootstrap.bundle.min.js')}}"></script>
<script src="{{ asset('new-js/3.5.1-jquery.min.js')}}"></script>
<script src="{{ asset('new-js/jquery.dataTables.min.js')}}"></script>
<script src="{{ asset('new-js/dataTables.bootstrap5.min.js')}}"></script>
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> -->
<script type="text/javascript">
    // $("#example").dataTable({
        // language: {
        //     paginate: {
        //         next: '<img src="images/next.png">', // or '→'
        //         previous: '<img src="images/pre.png">', // or '←'
        //     },
        // },
        // order: [7],
        // pageLength: [4],
        // columnDefs: [
        //     {
        //         targets: [7],

        //         orderable: false,
        //     },
        // ],
        // "paging": false
    // });
</script>