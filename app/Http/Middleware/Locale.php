<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Summit\Core\Models\Locale as LocaleModel;

class Locale
{

    /**
     * @var Locale Model
     */
    protected $locale;

    public function __construct(LocaleModel $locale){

        $this->locale = $locale;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    { 

        $locale = request()->get('locale');
       // app()->setLocale($locale);
        if ($locale) {
            if ($this->locale->where('code', $locale)->first()) {
                app()->setLocale($locale); 
                session()->put('locale', $locale);
            }
        } else {
            if ($locale = session()->get('locale')) {
                app()->setLocale($locale);
            } else {
                app()->setLocale(config('app.locale'));
                //app()->setLocale(core()->getDefaultChannel()->default_locale->code);
                
            }
        }

        unset($request['locale']);


        return $next($request);
    }
}
