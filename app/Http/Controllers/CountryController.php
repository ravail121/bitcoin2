<?php

namespace App\Http\Controllers;
use \App\Models\Country;

use Illuminate\Http\Request;

class CountryController extends Controller
{
    
    public function countries(Request $request)
    {
        $countries = Country::orderBy('id', 'desc')->paginate(15);
        $page_title = 'All Countries';
        return view('admin.country.index', compact('countries', 'page_title'));
    }
}
