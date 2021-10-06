<tbody>
  @foreach($trade_btc as $data)
    <tr>
      <td>{!! $data->user->link_to_page !!}</td>
      <td>
        {{$data->method->name}}
      </td>
      <td>{{$data->price}} {{$data->currency->name}}/{{$data->gateway->currency}}</td>
      <td>
        @if($data->add_type == 1)
          {{$data->min_amount.' '.$data->currency->name .'-'.$data->min_amount.' '.$data->currency->name}}
        @elseif($data->add_type == 2)
          {{$data->min_amount.' '.$data->currency->name .'-'.$data->max_amount.' '.$data->currency->name}}
        @endif
      </td>
      <td>
        <a class="btn btn-primary" href="{{ route('view', ['id'=>$data->id, 'payment'=> Replace($data->method->name)]) }}">
          @if(request()->getrequestUri() == '/trade/btc?sell')
            Sell
          @elseif(request()->getrequestUri() == '/trade/btc?buy')
            Buy
          @endif
        </a>
      </td>
    </tr>
  @endforeach
</tbody>
