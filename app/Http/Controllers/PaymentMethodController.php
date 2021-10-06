<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use App\Models\Country;
use App\Models\PaymentMethodsCategories;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use App\Http\Requests\Crypto\StoreFormRequest;
use Illuminate\Support\Facades\Storage;

class PaymentMethodController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $page_title = 'Manage Payment Methods';
        $payment = PaymentMethod::orderBy('id', 'desc')->get();
        return view('admin.payment-methods.index', compact('payment', 'page_title'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PaymentMethod  $payment
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $page_title = 'Create  Payment Method';
        $countries = Country::all();
        $categories = PaymentMethodsCategories::all();
        return view('admin.payment-methods.add-edit', compact('page_title','countries','categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Crypto\StoreFormRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreFormRequest $request)
    {
        if(!empty($request->category_ids)){
            $category_ids = implode(',', $request->category_ids); 
        }else{
            $category_ids ='';
        }
        $data =array();
        $data['name'] = $request->name;
        $data['description'] = $request->description;
        $data['category_ids'] = $category_ids;
        $data['question_one'] = $request->question_one;
        $data['question_two'] = $request->question_two;
        $data['answer_one'] = $request->answer_one;
        $data['answer_two'] = $request->answer_two;
        $data['status'] = ($request->status == 'on')? 1:0;
        $payment = PaymentMethod::create($data);

        $payment->countries()->sync($request->country_ids);

        return back()->with('success', 'Create Successfully!');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PaymentMethod  $payment
     * @return \Illuminate\Http\Response
     */
    public function edit(PaymentMethod $paymentMethod)
    {
        $page_title = 'Update Payment Method';
        $countries = Country::all();
        $categories = PaymentMethodsCategories::all();
        return view('admin.payment-methods.add-edit', compact('paymentMethod','categories', 'page_title', 'countries'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Crypto\StoreFormRequest  $request
     * @param  \App\Models\PaymentMethod  $payment
     * @return \Illuminate\Http\Response
     */
    public function update(StoreFormRequest $request, PaymentMethod $paymentMethod)
    {   
        if(!empty($request->category_ids)){
            $category_ids = implode(',', $request->category_ids);
        }else{
            $category_ids ='';
        }
         
        $data =array();
        $data['name'] = $request->name;
        $data['description'] = $request->description;
        $data['category_ids'] = $category_ids;
        $data['question_one'] = $request->question_one;
        $data['question_two'] = $request->question_two;
        $data['answer_one'] = $request->answer_one;
        $data['answer_two'] = $request->answer_two;
        $data['status'] = ($request->status == 'on')? 1:0;
        $paymentMethod->update($data);
        $paymentMethod->countries()->sync($request->country_ids);

        return redirect('adminio/payment-methods')->with('success', 'Update Successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PaymentMethod  $payment
     * @return \Illuminate\Http\Response
     */
    public function destroy(PaymentMethod $payment)
    {
        @unlink(public_path() . '/assets/images/payment-methods/'. $payment->icon);
        $payment->delete();
        return back()->with('success', 'Delete Successfully!');
    }
   
}
