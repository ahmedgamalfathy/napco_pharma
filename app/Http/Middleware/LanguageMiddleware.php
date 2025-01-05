<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class LanguageMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $lang= $request->route('lang','en');
        if(!in_array($lang,['en','ar']))
           {
               abort(404,'language not supported.');
           }
        App::setLocale($lang);
        Session::put('locale',$lang);
        $locale = $request->get('lang', config('app.locale'));
        App::setLocale($locale);

        return $next($request);
    }
}
