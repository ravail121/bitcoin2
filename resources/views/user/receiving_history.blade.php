@extends('front.layout.master2')

@section('body')

<div class="row">
  <div class="col-md-12 margin-top-pranto">
    <div class="card border-dark">
      <div class="card-body text-dark">
        <span class="h6">To receive bitcoin in your wallet, please copy the wallet address and give it to the sender</span>
        <div class="float-right">
          <span class="h6">Wallet Address:</span>
          <span id="depositAddress">{{ \App\Models\UserCryptoBalance::where('user_id', Auth::user()->id)->first()->address }}</span>
          <a title="Click to copy address" onclick="copy('depositAddress')" href="javascript:void(0)"><i class="fa fa-copy"></i></a>
        </div>
      </div>
    </div>
  </div>
</div>

<div id="searchsell">
    <section class="cm_whitsc1 wow fadeInUp ">
        <div class="container">
            <div class="px-md-4 px-2 py-2">
                <div class="cm_head1">
                    <h4>{{ $title }}</h4>
                </div>
                 <div class="dataTables_wrapper">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="cm_tabled1 table-responsive">
                        <table class="table myTable" id="sellsearchtable">
                            <thead >
                            <tr>
                                <th>TxID</th>
                                <th>Sender Name</th>
                                <th>Status</th>
                                <th>Amount</th>
                                <th>Created At</th>
                            </tr>
                            </thead>
                            <tbody>
                              @foreach($data as $item)
                                <tr >
                                  <td>
                                    {{ $item->id }}
                                  </td>
                                  <td>
                                    {{ \App\Models\User::where('id', $item->user_id)->first()->name }}
                                  </td>
                                  <td>
                                    <span >
                                      {{ $item->status }}
                                    </span>
                                  </td>
                                  <td>{{ $item->amount }}</td>
                                  <td>{{  Timezone::convertToLocal($item->created_at) }}</td>
                                </tr>
                              @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                     </div>
                </div>
            </div>
        </div>
    </div>
                    </section>
            </div>

<script type="text/javascript">
  function copy(containerid) {
    if (document.selection) {
      let range = document.body.createTextRange();
      range.moveToElementText(document.getElementById(containerid));
      range.select().createTextRange();
      document.execCommand('copy');

    } else if (window.getSelection) {
      let range = document.createRange();
      range.selectNode(document.getElementById(containerid));
      window.getSelection().addRange(range);
      document.execCommand('copy');
    }
  }
</script>
@stop
