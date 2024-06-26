<?php

namespace App\Http\Middleware;

use Closure;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;

use Symfony\Component\HttpFoundation\Response;

class LanguageSwitcher
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */


    public function handle(Request $request, Closure $next): Response
    {
        $defaultLocale = Config::get('app.locale');
        if (!Session::has('lang')) {
            Session::put('lang', $defaultLocale);
        }
        App::setLocale(Session::get('lang'));
        return $next($request);
    }
}
