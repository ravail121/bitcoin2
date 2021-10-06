<?php

namespace App\Providers;

use App\Models\Menu;
use App\Models\Social;
use App\Models\Country;
use App\Models\Currency;
use App\Models\GeneralSettings;
use Illuminate\Support\Facades\URL;
use App\Models\PaymentMethodsCategories;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {   
        URL::forceScheme('https');
        Schema::defaultStringLength(191);
        $data = [];

        $data['general'] = GeneralSettings::first();
        $data['basic'] = GeneralSettings::first();
        $data['menus'] =  Menu::all();
        $data['social'] =  Social::all();
        $country = Country::where('id', session()->get('country_id'))->first();
        $data['methods'] = [];
        if(!is_null($country)) {
            $data['methods'] = $country->paymentMethods()->where('status', 1)->get();
        }
        $data['categories']=PaymentMethodsCategories::all();

        $data['currency'] = cache()->remember('currency', 3600, function () {
            return Currency::where('status', 1)->get();
        });

        $data['countries'] = cache()->remember('countries', 3600, function () {
            return Country::where('active', true)->get();
        });
        View::share($data);

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
