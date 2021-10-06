<style>
    ul.app-menu>li{
        line-height: 0.5;
    }
</style>
<aside class="app-sidebar">
    @php
    $admin=\Auth::guard('admin')->user();
    @endphp
    <div class="app-sidebar__user"><img class="app-sidebar__user-avatar" class="img-circle"
            src="{{ asset($admin->image) }}" alt="User Image">
        <div>
            <p class="app-sidebar__user-name">{{ Auth::guard('admin')->user()->name }} </p>
            <!-- <p class="app-sidebar__user-designation">{{ Auth::guard('admin')->user()->username }}</p> -->
        </div>
    </div>
    <ul class="app-menu">
        <li><a class="app-menu__item @if(request()->path() == 'adminio/dashboard') active @endif"
                href="{{route('admin.dashboard')}}"><i class="app-menu__icon fa fa-dashboard"></i><span
                    class="app-menu__label">Dashboard</span></a></li>

        <li><a class="app-menu__item @if(request()->path() == 'adminio/dashboard-table') active @endif"
                href="{{route('admin.dashboard-table')}}"><i class="app-menu__icon fa fa-table"></i><span
                    class="app-menu__label">Tables</span></a></li>
        <li><a class="app-menu__item @if(request()->path() == 'adminio/dashboard-chart') active @endif"
                href="{{route('admin.dashboard-chart')}}"><i class="app-menu__icon fa fa-bar-chart"></i><span
                    class="app-menu__label">Charts</span></a></li>

        <li><a class="app-menu__item @if(request()->path() == 'adminio/feeSetup') active @endif"
                href="{{route('admin.feesetup')}}"><i class="app-menu__icon fa fa-money"></i><span
                    class="app-menu__label">Fee SetUp</span></a></li>

        
        @php $withdrawsCount= \App\Models\WithdrawRequest::where('status', 'pending')->count()
        @endphp
        <li class="treeview @if(request()->path() == 'adminio/withdraw-requests')  is-expanded 
                    @elseif(request()->path() == 'adminio/withdraw-pending-requests')  is-expanded  @endif">
            <a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-exchange"></i><span
                    class="app-menu__label">Withdrawals &nbsp @if($withdrawsCount > 0)<span
                                    class="badge badge-danger"> {{$withdrawsCount}}</span> @else @endif</span><i class="treeview-indicator fa fa-angle-right"></i></a>
            <ul class="treeview-menu">
                <li>
                    <a class="app-menu__item @if(request()->path() == 'adminio/withdraw-pending-requests') active @endif"
                        href="{{ route('admin.withdraw.requests.pending') }}">
                        <i class="app-menu__icon fa fa-plus"></i>
                        <span class="app-menu__label">Pending &nbsp @if($withdrawsCount > 0)<span
                                    class="badge badge-danger"> {{$withdrawsCount}}</span> @else @endif  </span>
                    </a>
                </li>
                <li>
                    <a class="app-menu__item @if(request()->path() == 'adminio/withdraw-requests') active @endif"
                        href="{{ route('admin.withdraw.requests') }}">
                        <i class="app-menu__icon fa fa-history"></i>
                        <span class="app-menu__label">Withdrawals History</span>
                    </a>
                </li>
            </ul>
        </li>


        @if($admin->is_pro ==1 || $admin->role ==1 )
        @php 
            $check_count = \App\Models\Ticket::where('status', 1)->orWhere('status',2)->orWhere('status',3)->count();
            $unread_check_count = \App\Models\Ticket::where('status', 1)->orWhere('status',3)->count();
        @endphp
        <li class="treeview @if(request()->path() == 'adminio/supports') is-expanded
        @elseif(request()->path() == 'adminio/pending/ticket') is-expanded @endif">
            <a class="app-menu__item" href="#" data-toggle="treeview"><i
                    class="app-menu__icon fa fa-ambulance"></i><span class="app-menu__label">Support @if($check_count >
                    0)<span class="badge badge-danger"> {{$check_count}} - {{$unread_check_count}} @else @endif </span> </span><i
                    class="treeview-indicator fa fa-angle-right"></i>
            </a>
            <ul class="treeview-menu">
                <li>
                    <a class="treeview-item @if(request()->path() == 'adminio/pending/ticket') active @endif"
                        href="{{route('pending.support.ticket')}}">
                        <i class="icon fa fa-spinner"></i> Pending &nbsp @if($check_count > 0)<span
                            class="badge badge-danger"> {{$check_count}} - {{$unread_check_count}} @else @endif</span>
                    </a>
                </li>
                <li>
                    <a class="treeview-item @if(request()->path() == 'adminio/supports') active @endif"
                        href="{{route('support.admin.index')}}">
                        <i class="icon fa fa-ticket"></i> Closed Tickets
                    </a>
                </li>
            </ul>
        </li>
        @endif

        <li class="treeview  @if(request()->path() == 'adminio/users')  is-expanded
        @elseif(request()->path() == 'adminio/user-banned')  is-expanded
        @elseif(request()->path() == 'adminio/user/{user}')  is-expanded
        @elseif(request()->path() == 'adminio/active/user')  is-expanded
        @elseif(request()->path() == 'adminio/email/unverified/user')  is-expanded
        @elseif(request()->path() == 'adminio/phone/unverified/user')  is-expanded
        @elseif(request()->path() == 'adminio/marketing-users')  is-expanded 
        
        @endif">
            <a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-users"></i><span
                    class="app-menu__label">Users</span><i class="treeview-indicator fa fa-angle-right"></i></a>
            <ul class="treeview-menu">
                <li>
                    <a class="treeview-item @if(request()->getRequestUri() == '/adminio/users') active @endif"
                        href="{{ route('users') }}">
                        <i class="icon fa fa-user"></i> Users
                    </a>
                </li>

                <li>
                    <a class="treeview-item @if(request()->getRequestUri() == '/adminio/users?unverified') active @endif"
                        href="/adminio/users?unverified">
                        <i class="icon fa fa-times-circle"></i> Document Unverified
                    </a>
                </li>
                <li>
                    <a class="treeview-item @if(request()->getRequestUri() == '/adminio/users?autoverified') active @endif"
                        href="/adminio/users?autoverified">
                        <i class="icon fa fa-times-circle"></i> Document Auto verified
                    </a>
                </li>
                <li>
                    <a class="treeview-item @if(request()->getRequestUri() == '/adminio/users?deposit') active @endif"
                        href="/adminio/users?deposit">
                        <i class="icon fa fa-money"></i> Pending Deposit
                    </a>
                </li>
                <li>
                    <a class="treeview-item @if(request()->getRequestUri() == '/adminio/users?active=true') active @endif"
                        href="/adminio/users?active=true">
                        <i class="icon fa fa-check"></i>Active Users
                    </a>
                </li>
                <li>
                    <a class="treeview-item @if(request()->getRequestUri() == '/adminio/users?email=verified') active @endif"
                        href="/adminio/users?email=verified">
                        <i class="icon fa fa-envelope"></i>Email Unverified
                    </a>
                </li>
                <li>
                    <a class="treeview-item @if(request()->getRequestUri() == '/adminio/users?phone=verified') active @endif"
                        href="/adminio/users?phone=verified">
                        <i class="icon fa fa-phone"></i>Phone Unverified
                    </a>
                </li>
                <li>
                    <a class="treeview-item @if(request()->getRequestUri() == '/adminio/users?banned') active @endif"
                        href="/adminio/users?banned" rel="noopener">
                        <i class="icon fa fa-ban"></i> Banned User
                    </a>
                </li>
                <li>
                    <a class="treeview-item @if(request()->getRequestUri() == '/adminio/marketing-users') active @endif"
                        href="/adminio/marketing-users" rel="noopener">
                        <i class="icon fa fa-ban"></i> Marketing Users
                    </a>
                </li>
            </ul>
        </li>

        <li class="treeview @if(request()->path() == 'adminio/deals')  is-expanded 
                    @elseif(request()->path() == 'adminio/dispute/deals')  is-expanded  @endif">
            <a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-file"></i><span
                    class="app-menu__label">Deals</span><i class="treeview-indicator fa fa-angle-right"></i></a>
            <ul class="treeview-menu">
                <li>
                    <a class="treeview-item @if(request()->path() == 'adminio/deals') active @endif"
                        href="{{route('deal.log')}}">
                        <i class="icon fa fa-list"></i>Deals List
                    </a>
                </li>
                <li>
                    <a class="treeview-item @if(request()->path() == 'adminio/dispute/deals') active @endif"
                        href="{{route('deal.dispute')}}">
                        <i class="icon fa fa-list"></i>Dispute deals
                    </a>
                </li>

            </ul>
        </li>

        <li><a class="app-menu__item @if(request()->path() == 'adminio/ads') active @endif"
                href="{{route('admin.ads')}}"><i class="app-menu__icon fa fa-plus"></i><span
                    class="app-menu__label">Ads</span></a></li>

        <li class="treeview  @if(request()->path() == 'adminio/users?deposit')  is-expanded 
                    @elseif(request()->path() == 'adminio/external-transactions')  is-expanded  @endif">
            <a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-money"></i><span
                    class="app-menu__label">Deposits</span><i class="treeview-indicator fa fa-angle-right"></i></a>
            <ul class="treeview-menu">

                <li>
                    <a class="treeview-item @if(request()->getRequestUri() == '/adminio/users?deposit') active @endif"
                        href="/adminio/users?deposit">
                        <i class="icon fa fa-list"></i> Pending deposit
                    </a>
                </li>

                <li>
                    <a class="treeview-item @if(request()->path() == 'adminio/external-transactions') active @endif"
                        href="{{route('deposits')}}">
                        <i class="icon fa fa-list"></i> Confirmed deposits
                    </a>
                </li>
            </ul>
        </li>

        @php $sendsCount= \App\Models\InternalTransactions::where('status', 'pending')->count()
        @endphp
        <li class="treeview @if(request()->path() == 'adminio/send-requests')  is-expanded 
                    @elseif(request()->path() == 'adminio/send-pending-requests')  is-expanded  @endif">
            <a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-exchange"></i><span
                    class="app-menu__label">Send &nbsp @if($sendsCount > 0)<span
                                    class="badge badge-danger"> {{$sendsCount}}</span> @else @endif</span><i class="treeview-indicator fa fa-angle-right"></i></a>
            <ul class="treeview-menu">
                <li>
                    <a class="app-menu__item @if(request()->path() == 'adminio/send-pending-requests') active @endif"
                        href="{{ route('admin.send.requests.pending') }}">
                        <i class="app-menu__icon fa fa-plus"></i>
                        <span class="app-menu__label">Pending &nbsp @if($sendsCount > 0)<span
                                    class="badge badge-danger"> {{$sendsCount}}</span> @else @endif  </span>
                    </a>
                </li>
                <li>
                    <a class="app-menu__item @if(request()->path() == 'adminio/send-requests') active @endif"
                        href="{{ route('admin.send.requests') }}">
                        <i class="app-menu__icon fa fa-history"></i>
                        <span class="app-menu__label">Sends History</span>
                    </a>
                </li>
            </ul>
        </li>

        <li class="treeview @if(request()->path() == 'adminio/transactions')  is-expanded 
                    @elseif(request()->path() == 'adminio/actions')  is-expanded  @endif">
            <a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-file"></i><span
                    class="app-menu__label">Logs</span><i class="treeview-indicator fa fa-angle-right"></i></a>
            <ul class="treeview-menu">
                <li>
                    <a class="treeview-item @if(request()->path() == 'adminio/transactions') active @endif"
                        href="{{route('trans.log')}}">
                        <i class="icon fa fa-money"></i>Transaction Log
                    </a>
                </li>
                <li>
                    <a class="treeview-item @if(request()->path() == 'adminio/actions') active @endif"
                        href="{{route('actions.log')}}">
                        <i class="icon fa fa-globe"></i>Actions
                    </a>
                </li>

                <!-- <li>
                    <a class="treeview-item @if(request()->path() == 'adminio/external-transactions') active @endif"
                        href="{{route('deposits')}}">
                        <i class="icon fa fa-file"></i> External Transaction Log
                    </a>
                </li> -->
            </ul>
        </li>

        <!-- <li><a class="app-menu__item @if(request()->path() == 'adminio/payment-methods') active @endif"
                href="{{route('payment-methods.index')}}"><i class="app-menu__icon fa fa-money"></i><span
                    class="app-menu__label">Payment Methods</span></a></li> -->

        @if($admin->is_pro ==1)

        <li class="treeview @if(request()->path() == 'adminio/reports/overview') is-expanded
        @elseif(request()->path() == 'adminio/reports/overview-search') is-expanded
         @endif">
            <a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-file"></i><span
                    class="app-menu__label">Reports</span><i class="treeview-indicator fa fa-angle-right"></i></a>
            <ul class="treeview-menu">

                <li>
                    <a class="treeview-item @if(request()->getRequestUri() == '/adminio/reports/overview') active @endif"
                        href="/adminio/reports/overview">
                        <i class="icon fa fa-list"></i> Overview
                    </a>
                </li>
            </ul>
        </li>
        <li class="treeview @if(request()->path() == 'adminio/payment-methods')  is-expanded 
                    @elseif(request()->path() == 'adminio/methods/category')  is-expanded 
                    @elseif(request()->path() == 'adminio/methods/advises')  is-expanded
                      @endif">
            <a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-money"></i><span
                    class="app-menu__label">Payment Methods</span><i
                    class="treeview-indicator fa fa-angle-right"></i></a>
            <ul class="treeview-menu">
                <li>
                    <a class="treeview-item @if(request()->path() == 'adminio/payment-methods') active @endif"
                        href="{{route('payment-methods.index')}}">
                        <i class="icon fa fa-list"></i>Payment Methods
                    </a>
                </li>
                <li>
                    <a class="treeview-item @if(request()->path() == 'adminio/methods/category') active @endif"
                        href="{{route('methods.viewcategories')}}">
                        <i class="icon fa fa-list"></i>Categories
                    </a>
                </li>
                <li>
                    <a class="treeview-item @if(request()->path() == 'adminio/methods/advises') active @endif"
                        href="{{route('methods.viewadvises')}}">
                        <i class="icon fa fa-list"></i>User Advises
                    </a>
                </li>

            </ul>
        </li>








        <li><a class="app-menu__item @if(request()->path() == 'adminio/cron-jobs') active @endif"
                href="{{route('cron-jobs.index')}}"><i class="app-menu__icon fa fa-clock-o"></i><span
                    class="app-menu__label">Cron Job</span></a></li>

        <li><a class="app-menu__item @if(request()->path() == 'adminio/currency') active @endif"
                href="{{route('currency.index')}}"><i class="app-menu__icon fa fa-eur"></i><span
                    class="app-menu__label">Currency</span></a></li>
        <li><a class="app-menu__item @if(request()->path() == 'adminio/addresses') active @endif"
                href="{{route('addresses.show')}}"><i class="app-menu__icon fa fa-address-card-o"></i><span
                    class="app-menu__label">Wallet Addresses</span></a></li>
        <li class="treeview @if(request()->path() == 'adminio/admins')  is-expanded 
                    @elseif(request()->path() == 'adminio/admins')  is-expanded  @endif">
            <a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-users"></i><span
                    class="app-menu__label">Admin Users</span><i
                    class="treeview-indicator fa fa-angle-right"></i></a>
            <ul class="treeview-menu">
                <li>
                    <a class="treeview-item @if(request()->path() == 'adminio/admins') active @endif"
                        href="{{route('admins.list')}}">
                        <i class="icon fa fa-list"></i>Users List
                    </a>
                </li>
            </ul>
        </li>





        

        

        

        @endif
        @if($admin->is_pro ==1)
        <li class="treeview @if(request()->path() == 'adminio/general-settings') is-expanded
        @elseif(request()->path() == 'adminio/template') is-expanded
        @elseif(request()->path() == 'adminio/sms-api') is-expanded
        @elseif(request()->path() == 'adminio/terms/policy') is-expanded
        @elseif(request()->path() == 'adminio/contact-setting') is-expanded
        @endif">
            <a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-cogs"></i><span
                    class="app-menu__label">Website Control</span><i class="treeview-indicator fa fa-angle-right"></i>
            </a>
            <ul class="treeview-menu">
                <li>
                    <a class="treeview-item @if(request()->path() == 'adminio/general-settings') active @endif"
                        href="{{route('admin.GenSetting')}}">
                        <i class="icon fa fa-cogs"></i> General Setting
                    </a>
                </li>
                <li>
                    <a class="treeview-item @if(request()->path() == 'adminio/template') active @endif"
                        href="{{route('email.template')}}">
                        <i class="icon fa fa-envelope"></i> Email Setting
                    </a>
                </li>
                <li>
                    <a class="treeview-item @if(request()->path() == 'adminio/sms-api') active @endif"
                        href="{{route('sms.api')}}">
                        <i class="icon fa fa-mobile"></i> SMS Setting
                    </a>
                </li>
                <li>
                    <a class="treeview-item @if(request()->path() == 'adminio/contact-setting') active @endif"
                        href="{{route('contact-setting')}}">
                        <i class="icon fa fa-phone"></i> Contact Setting
                    </a>
                </li>
                <li>
                    <a class="treeview-item @if(request()->path() == 'adminio/terms/policy') active @endif"
                        href="{{route('terms.policy')}}"><i class="icon fa fa-file"></i> Terms & Condition
                    </a>
                </li>
            </ul>
        </li>



        <li class="treeview     @if(request()->path() == 'adminio/manage-logo') is-expanded
      @elseif(request()->path() == 'adminio/manage-footer') is-expanded
      @elseif(request()->path() == 'adminio/manage-social') is-expanded
      @elseif(request()->path() == 'adminio/menu-control') is-expanded
      @elseif(request()->path() == 'adminio/menu-create') is-expanded
      @elseif(request()->path() == 'adminio/manage-breadcrumb') is-expanded
      @elseif(request()->path() == 'adminio/manage-about') is-expanded
      @elseif(request()->path() == 'adminio/advertisement') is-expanded
      @elseif(request()->path() == 'adminio/faqs-create') is-expanded
      @elseif(request()->path() == 'adminio/slider-index') is-expanded

      @elseif(request()->path() == 'adminio/faqs-all') is-expanded
      @elseif(request()->path() == 'adminio/advertisement/create') is-expanded
      @endif">
            <a class="app-menu__item" href="#" data-toggle="treeview">
                <i class="app-menu__icon fa fa-desktop"></i>
                <span class="app-menu__label">Interface Control</span>
                <i class="treeview-indicator fa fa-angle-right"></i>
            </a>
            <ul class="treeview-menu">
                <li>
                    <a class="treeview-item @if(request()->path() == 'adminio/manage-logo') active @endif"
                        href="{{route('manage-logo')}}">
                        <i class="icon fa fa-photo"></i> Logo & favicon
                    </a>
                </li>
                <li>
                    <a class="treeview-item @if(request()->path() == 'adminio/slider-index') active @endif"
                        href="{{route('slider-index')}}">
                        <i class="icon fa fa-file-text"></i> Manage Slider
                    </a>
                </li>
                <li>
                    <a class="treeview-item @if(request()->path() == 'adminio/manage-footer') active @endif"
                        href="{{route('manage-footer')}}">
                        <i class="icon fa fa-file-text"></i> Manage Footer
                    </a>
                </li>
                <li>
                    <a class="treeview-item @if(request()->path() == 'adminio/manage-social') active @endif"
                        href="{{route('manage-social')}}">
                        <i class="icon fa fa-share-alt-square"></i> Manage Social
                    </a>
                </li>
                <li>
                    <a class="treeview-item @if(request()->path() == 'adminio/menu-control'|| request()->path() == 'adminio/menu-create') active @endif"
                        href="{{route('menu-control')}}">
                        <i class="icon fa fa-desktop"></i> Menu Control
                    </a>
                </li>
            </ul>
        </li>
        @endif
    </ul>
</aside>