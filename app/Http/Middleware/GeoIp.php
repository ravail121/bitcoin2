<?php

namespace App\Http\Middleware;

use Closure;

class GeoIp
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!session()->has('currency')) {
            $ip = geoip()->getLocation();
            session()->put('country', $ip->country);
            session()->put('currency', $ip->currency);
            $currency = \App\Models\Currency::whereName($ip->currency)->first();
            $country = \App\Models\Country::whereName($ip->country)->first();
            if ($currency) {
              session()->put('currency_id', $currency->id);
              session()->put('country_id', $country->id);
            }
        }
        return $next($request);
    }
}
