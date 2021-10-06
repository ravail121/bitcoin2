@extends('front.layout.master2')

@section('body')
<style>
.card_height {
        height: 210px;
      }
  
.btn {
    white-space: revert;
  }

.card-link {
    text-decoration: none;
    color: inherit;
}

.card-link:hover {
    color: white;
}

#wrapper {  
  margin-left: 15% !important;
}
  
@media screen and (max-width:1024px){
      .card_height {
            height: 250px;
       }
      }
@media screen and (max-width:768px) {
       .card_height {
        height: 270px;
        margin-bottom: 10px;
      }
    }

@media screen and (max-width:768px) and (min-width: 426px)
{
  .card-header {
    padding: 0.75rem 0.25rem;}
  .btn {
    white-space: revert;
    font-size:14px;
    padding :0.375rem 0px;}  
  }

@media screen and (max-width:320px) 
{
  .float-right{
    float: none !important;}
  
  }

  .bg-danger{
background-color: #c75042;
  }

  .bg-secondary{
background-color: #85898c;
  }

  .bg-success{
background-color: #0b7d5e;
  }
  
</style>
<!--bitcoin blance Strat--->
<section class="bitcoin-blacnce">
    <div class="container">
      @foreach($balance as $gate)
        <div class="row">
            <div class="col-lg-6 balane">
                <p>
                    <span>Deposit address </span>: {{ $gate->address }}<span>&nbsp;<a title="Click to copy address" onclick="copy('depositAddress')" href="javascript:void(0)"><i class="fa fa-copy"></i></a></span>
                </p>
            </div>
            <div class="col-lg-2 balane">
                <a class="btn market" href="@if(isset(auth()->user()->username)) {{url('/'.auth()->user()->username.'/market')}} @else {{url('/')}} @endif">Go to Market</a>
            </div>
            <div class="col-lg-4 balane">
                <p><span>Bitcoin Balance</span>: {{number_format((float)round($gate->balance, 8), 8, '.', '')}} BTC</p>
                <a class="btn Withdraw" href="{{route('user.withdraws', auth()->user()->username)}}">Send BTC</a>
            </div>
        </div>
      @endforeach
    </div>
</section>

<!--bitcoin blance Strat--->
<section class="welcome-dashboard">
    <div class="container">
        <div class="row pt-30 text-center">
            <div class="welcome">
                <p>Welcome to <span>{{auth()->user()->name}}'s Dashboard</span></p>
            </div>
        </div>
    </div>
</section>
<section class="welcome-dashboard">
  <div class="container">
      <div class="row pt-30 text-center">
          @php
            try{
            $user_bal =\App\Models\UserCryptoBalance::where('user_id', Auth::id())
            ->where('gateway_id', 505)->first()->balance;
            $user_bal = number_format((float)$user_bal, 8, '.', '');
            }catch(Exception $exception){
            $user_bal =0;
            }
          @endphp
          <div class="col-lg-3 dashboard-bitcoin">
              <a href="{{route('trans.history', auth()->user()->username)}}">
                  <div class="bit">
                      <div class="bi-img">
                          <span><img src="{{ asset('new-images/dashboard/bitcoin-logo.png') }}" /></span>
                      </div>
                      <div class="Balance">
                          <span class="blance-bit">Balance in BTC</span>
                          <span class="amount-bit">{{ $user_bal }}</span>
                      </div>
                  </div>
              </a>
          </div>
          @php
              $currency = App\Models\Currency::
                  where(function($query){
                    return $query
                    ->where('id', 1);                
                })->first();
            @endphp
            @php
            $usd_balance = $user_bal * $currency->btc_rate;
            $usd_balance = number_format((float)$usd_balance, 2, '.', '');
          @endphp
          <div class="col-lg-3 dashboard-bitcoin">
              <a href="{{route('trans.history', auth()->user()->username)}}">
                  <div class="bit">
                      <div class="bi-img">
                          <span><img src="{{ asset('new-images/dashboard/dollar-symbol.png') }}" /></span>
                      </div>
                      <div class="Balance">
                          <span class="blance-bit">Balance in USD</span>
                          <span class="amount-bit">{{$usd_balance}}</span>
                      </div>
                  </div>
              </a>
          </div>
          @php
            $currency = App\Models\Currency::
                where(function($query){
                  return $query
                  ->where('id', session()->get('currency_id'));                
              })->first();
          @endphp
          <div class="col-lg-3 dashboard-bitcoin">
              <a href="{{route('trans.history', auth()->user()->username)}}">
                  <div class="bit">
                      <div class="bi-img">
                          <span><img src="{{ asset('new-images/dashboard/rupee-indian.png') }}" /></span>
                      </div>
                      <div class="Balance">
                          <span class="blance-bit">Balance in {{$currency->name}}</span>
                          <span class="amount-bit">{{number_format((float)$user_bal * $currency->btc_rate, 2, '.', '')}}</span>
                      </div>
                  </div>
              </a>
          </div>

          <div class="col-lg-3 dashboard-bitcoin">
              <a href="{{route('open.trade')}}">
                  <div class="bit">
                      <div class="bi-img">
                          <span><img src="{{ asset('new-images/dashboard/handshake.png') }}" /></span>
                      </div>
                      <div class="Balance">
                          <span class="blance-bit">Open Deals</span>
                          <span class="amount-bit">{{App\Models\AdvertiseDeal::where('from_user_id', Auth::id())->where(function($query){
            return $query->where('status', 0)->orWhere('status', 9)->orWhere('status', 11);
            
        })->orWhere('to_user_id', Auth::id())->where(function($query){
            return $query->where('status', 0)->orWhere('status', 9)->orWhere('status', 11);
            
        })->count()}}</span>
                      </div>
                  </div>
              </a>
          </div>
          <div class="col-lg-3 dashboard-bitcoin">
              <a href="{{route('complete.trade')}}">
                  <div class="bit">
                      <div class="bi-img">
                          <span><img src="{{ asset('new-images/dashboard/task-complete.png') }}" /></span>
                      </div>
                      <div class="Balance">
                          <span class="blance-bit">Completed Deals</span>
                          <span class="amount-bit">{{App\Models\AdvertiseDeal::
                            where(function($query){
                              return $query
                              ->where('to_user_id', Auth::id())
                              ->orWhere('from_user_id', Auth::id());
                              
                          })->
                          where(function($query){
                              return $query
                              ->where('status', 1);
                              
                          })->count()}}</span>
                      </div>
                  </div>
              </a>
          </div>
          <div class="col-lg-3 dashboard-bitcoin">
              <a href="{{route('close.trade')}}">
                  <div class="bit">
                      <div class="bi-img">
                          <span><img src="{{ asset('new-images/dashboard/close-deals.png') }}" /></span>
                      </div>
                      <div class="Balance">
                          <span class="blance-bit">Closed Deals</span>
                          <span class="amount-bit">{{App\Models\AdvertiseDeal::
                            where(function($query){
                                return $query
                                ->where('to_user_id', Auth::id())
                                ->orWhere('from_user_id', Auth::id());
                                
                            })->
                            where(function($query){
                                return $query
                                ->where('status', 1)
                                ->orWhere('status', 2)
                                ->orWhere('status', 21);
                            })->count()}}</span>
                      </div>
                  </div>
              </a>
          </div>

          <div class="col-lg-3 dashboard-bitcoin">
              <a href="{{route('cancel.trade')}}">
                  <div class="bit">
                      <div class="bi-img">
                          <span><img src="{{ asset('new-images/dashboard/signal.png') }}" /></span>
                      </div>
                      <div class="Balance">
                          <span class="blance-bit">Cancelled Deals</span>
                          <span class="amount-bit">{{App\Models\AdvertiseDeal::
                            where(function($query){
                                return $query
                                ->where('to_user_id', Auth::id())
                                ->orWhere('from_user_id', Auth::id());
                                
                            })->
                            where(function($query){
                                return $query
                                ->where('status', 2);
                            })->count()}}</span>
                      </div>
                  </div>
              </a>
          </div>

          <div class="col-lg-3 dashboard-bitcoin">
              <a href="{{route('expire.trade')}}">
                  <div class="bit">
                      <div class="bi-img">
                          <span><img src="{{ asset('new-images/dashboard/close-deals.png') }}" /></span>
                      </div>
                      <div class="Balance">
                          <span class="blance-bit">Expired Deals</span>
                          <span class="amount-bit">{{App\Models\AdvertiseDeal::
                            where(function($query){
                                return $query
                                ->where('to_user_id', Auth::id())
                                ->orWhere('from_user_id', Auth::id());
                                
                            })->
                            where(function($query){
                                return $query
                                ->where('status', 21);
                            })->count()}}</span>
                      </div>
                  </div>
              </a>
          </div>

          <div class="col-lg-3 dashboard-bitcoin">
              <a href="{{route('deposit.history', auth()->user()->username)}}">
                  <div class="bit">
                      <div class="bi-img">
                          <span><img src="{{ asset('new-images/dashboard/pending.png') }}" /></span>
                      </div>
                      <div class="Balance">
                          <span class="blance-bit">Pending Deposits</span>
                          <span class="amount-bit">{{ auth()->user()->transactions()->pending()->deposits()->count() }}</span>
                      </div>
                  </div>
              </a>
          </div>
          <div class="col-lg-3 dashboard-bitcoin">
              <a href="{{route('deposit.history', auth()->user()->username)}}">
                  <div class="bit">
                      <div class="bi-img">
                          <span><img src="{{ asset('new-images/dashboard/check-mark.png') }}" /></span>
                      </div>
                      <div class="Balance">
                          <span class="blance-bit">Completed Deposits</span>
                          <span class="amount-bit">{{ auth()->user()->transactions()->completed()->deposits()->count() }}</span>
                      </div>
                  </div>
              </a>
          </div>
          <div class="col-lg-3 dashboard-bitcoin">
              <a href="{{route('user.withdraws', auth()->user()->username)}}">
                  <div class="bit">
                      <div class="bi-img">
                          <span><img src="{{ asset('new-images/dashboard/p.png') }}" /></span>
                      </div>
                      <div class="Balance">
                          <span class="blance-bit">Pending Withdrawals</span>
                          <span class="amount-bit">{{ auth()->user()->transactions()->pending()->withdraws()->count() }}</span>
                      </div>
                  </div>
              </a>
          </div>
          <div class="col-lg-3 dashboard-bitcoin">
              <a href="{{route('user.withdraws', auth()->user()->username)}}">
                  <div class="bit">
                      <div class="bi-img">
                          <span><img src="{{ asset('new-images/dashboard/c.png') }}" /></span>
                      </div>
                      <div class="Balance">
                          <span class="blance-bit">Completed Withdrawals</span>
                          <span class="amount-bit">{{ auth()->user()->transactions()->completed()->withdraws()->count() }}</span>
                      </div>
                  </div>
              </a>
          </div>

          <div class="col-lg-3 dashboard-bitcoin">
              <a href="{{route('advertise.history', auth()->user()->username)}}">
                  <div class="bit">
                      <div class="bi-img">
                          <span><img src="{{ asset('new-images/dashboard/video.png') }}" /></span>
                      </div>
                      <div class="Balance">
                          <span class="blance-bit">All ADs</span>
                          <span class="amount-bit">{{ \App\Models\Advertisement::where('user_id',Auth::id())->where('status', '1')->count() }}</span>
                      </div>
                  </div>
              </a>
          </div>

          <div class="col-lg-3 dashboard-bitcoin">
              <a href="{{route('trans.history', auth()->user()->username)}}">
                  <div class="bit">
                      <div class="bi-img">
                          <span><img src="{{ asset('new-images/dashboard/transaction.png') }}" /></span>
                      </div>
                      <div class="Balance">
                          <span class="blance-bit">All Transactions</span>
                          <span class="amount-bit">{{ \App\Models\Trx::where('user_id',Auth::id())->count() }}</span>
                      </div>
                  </div>
              </a>
          </div>

          <div class="col-lg-3 dashboard-bitcoin">
              <a href="{{route('allNotification.get')}}">
                  <div class="bit">
                      <div class="bi-img">
                          <span><img src="{{ asset('new-images/dashboard/notification.png') }}" /></span>
                      </div>
                      <div class="Balance">
                          <span class="blance-bit">All Notifications</span>
                          <span class="amount-bit">{{ \App\Models\Notification::where('to_user',Auth::id())->whereNull('read_message')->orderBy('created_at','desc')->count() }}</span>
                      </div>
                  </div>
              </a>
          </div>

          <div class="col-lg-3 dashboard-bitcoin">
              <a href="{{route('support.index.customer', auth()->user()->username)}}">
                  <div class="bit">
                      <div class="bi-img">
                          <span><img src="{{ asset('new-images/dashboard/support-ticket.png') }}" /></span>
                      </div>
                      <div class="Balance">
                          <span class="blance-bit">Support Tickets</span>
                          <span class="amount-bit">{{ \App\Models\Ticket::where('status', '!=', '9')->where('customer_id', Auth::id())->count() }}</span>
                      </div>
                  </div>
              </a>
          </div>
      </div>
  </div>
</section>


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
