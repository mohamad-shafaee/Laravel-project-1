<?php

namespace Summit\Core;

use Summit\Core\Models\Locale;  


class Core
{
	protected $locale;

	public function __construct(Locale $locale)
	{
		$this->locale = $locale;


	}

	public function getAllLocales()
	{
		static $locales;

		if($locales){
			return $locales;
		}

		return $locales = $this->locale::all();

	}

	public function getCurrentLocale(){

		static $locale;
		if($locale){
			return $locale;
		}

		$locale = $this->locale->where('code', app()->getLocale())->first();

		if (! $locale) {
            $locale = $this->locale->where('code', config('app.fallback_locale'))->first();
        }

        return $locale;
	}

	public function test1(){

		return "JJJJJ";
	}


}
