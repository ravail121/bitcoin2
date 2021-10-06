<?php

namespace App\Http\Controllers;

use App\Models\AdvertiseDeal;
use App\Models\Trx;
use App\Models\Transaction;
use foo\bar;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Deposit;
use App\Models\UserLogin;

class DepositController extends Controller
{
    public function index()
    {
        $page_title = "Confirmed Deposit";
        if (!request()->has('type') && !request()->has('address')) {
            $data = Transaction::where('status','complete')->paginate(10);
        } else {
            $data = Transaction::where('status','complete')->where(request()->all())->paginate(10);
        }

        return view('admin.deposit.deposits', compact('data', 'page_title'));
    }
    
    public function transLog()
    {
        $trans = Trx::orderBy('id', 'desc')->newQuery();

        if (!request()->has('user_id')) {
            $trans = $trans->paginate(10);
        } else {
            $trans = $trans->where(request()->all())->paginate(10);
        }

        $page_title = "Transaction  Log";
        return view('admin.trans_log', compact('trans', 'page_title'));
    }
    public function actionsLog()
    {
        $trans = UserLogin::where('user_id', '!=', 1)->orderBy('id', 'desc')->newQuery();

        if (!request()->has('user_id')) {
            $trans = $trans->paginate(10);
        } else {
            $trans = $trans->where(request()->all())->paginate(10);
        }

        $page_title = "Action  Log";
        return view('admin.actions_log', compact('trans', 'page_title'));
    }

    public function trade24hoursHistory()
    {
        $trans = Trx::where('created_at', '>', Carbon::now()->subDay())->where('created_at', '<=', Carbon::now())->orderBy('id', 'desc')->newQuery();

        if (!request()->has('user_id')) {
            $trans = $trans->paginate(10);
        } else {
            $trans = $trans->where(request()->all())->paginate(10);
        }

        $page_title = "Trade 24 Hours History";
        return view('admin.trans_log', compact('trans', 'page_title'));
    }
    public function dealLog()
    {
        $trans = AdvertiseDeal::orderBy('id', 'desc')->paginate(10);
        $page_title = "Deal  Log";
        return view('admin.deal_log', compact('trans', 'page_title'));
    }

    public function dealCompleteLog()
    {
        $trans = AdvertiseDeal::where('status', 1)->orderBy('id', 'desc')->paginate(10);
        $page_title = "Complete Deal Logs";
        return view('admin.deal_log', compact('trans', 'page_title'));
    }

    public function dealExpiredLog()
    {
        $trans = AdvertiseDeal::where('status', 21)->orderBy('id', 'desc')->paginate(10);
        $page_title = "Expired Deal Logs";
        return view('admin.deal_log', compact('trans', 'page_title'));
    }

    public function deal24hoursLog()
    {
        $trans = AdvertiseDeal::where('created_at', '>', Carbon::now()->subDay())->where('created_at', '<=', Carbon::now())->orderBy('id', 'desc')->paginate(10);
        $page_title = "Expired Deal Logs";
        return view('admin.deal_log', compact('trans', 'page_title'));
    }

    public function dealCancelledLog()
    {
        $trans = AdvertiseDeal::where('status', 2)->orderBy('id', 'desc')->paginate(10);
        $page_title = "Cancelled Deal Logs";
        return view('admin.deal_log', compact('trans', 'page_title'));
    }

    public function disputedealLog(){
        $trans = AdvertiseDeal::where('status',10)->orderBy('id', 'desc')->paginate(10);
        $page_title = "Disputed Deals";
        return view('admin.deal_log', compact('trans', 'page_title'));
    }
    public function holddealLog(){
        $trans = AdvertiseDeal::where('status',11)->orderBy('id', 'desc')->paginate(10);
        $page_title = "Disputed Deals";
        return view('admin.deal_log', compact('trans', 'page_title'));
    }
    public function openDealLog(){
        $trans = AdvertiseDeal::where('status',0)->orWhere('status',9)->orderBy('id', 'desc')->paginate(10);
        $page_title = "Open Deals";
        return view('admin.deal_log', compact('trans', 'page_title'));
    }

    public function dealView($trans_id)
    {
        try{
            
            $trans = AdvertiseDeal::where('trans_id', $trans_id)->first();
            $page_title = "Deal View";
    
            if ($trans == '') {
                return back();
            }
        }catch(\Exception $e){
            return back();
        }
        

        return view('admin.deal_view', compact('trans', 'page_title'));
    }

    public function dealSearch(Request $request)
    {
        $trans = AdvertiseDeal::where('trans_id', $request->trans_id)->first();
        $page_title = "Deal View";

        if ($trans == '') {
            return back()->with('alert', 'Not Found');
        }

        return view('admin.deal_view', compact('trans', 'page_title'));
    }
}
