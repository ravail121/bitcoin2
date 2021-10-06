<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use App\Models\Country;
use Illuminate\Http\Request;
use App\Http\Requests\Currency\StoreFormRequest;

class CurrencyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $crypto = Currency::orderBy('id', 'desc')->paginate(15);
        $page_title = 'Manage Currency';
        return view('admin.currency.index', compact('crypto', 'page_title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Currency\StoreFormRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreFormRequest $request)
    {
        if ($request->status != null) {
            $status = 1;
        } else {
            $status = 0;
        }

        Currency::create([
           'name' => $request->name,
           'usd_rate' => $request->usd_rate,
           'status' => $status,
        ]);

        return back()->with('success', 'Create Successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Currency  $currency
     * @return \Illuminate\Http\Response
     */
    public function show(Currency $currency)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Currency  $currency
     * @return \Illuminate\Http\Response
     */
    public function edit(Currency $currency)
    {
        $page_title = 'Update Currency';
        $crypto = Currency::findOrFail($currency->id);
        return view('admin.currency.edit', compact('crypto', 'page_title'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Currency\StoreFormRequest  $request
     * @param  \App\Models\Currency  $currency
     * @return \Illuminate\Http\Response
     */
    public function update(StoreFormRequest $request, Currency $currency)
    {
        if ($request->status != null) {
            $status = 1;
        } else {
            $status = 0;
        }

        Currency::where('id', $currency->id)->update([
           'name' => $request->name,
           'usd_rate' => $request->usd_rate,
           'status' => $status,
        ]);
        return redirect('adminio/currency')->with('success', 'Update Successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Currency  $currency
     * @return \Illuminate\Http\Response
     */
    public function destroy(Currency $currency)
    {
    }
}
