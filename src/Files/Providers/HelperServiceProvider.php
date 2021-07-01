<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class HelperServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
	public function register()
	{

		$list=[
			app_path().'/Extras/consts.php',
			app_path().'/Extras/helpers.php',
			app_path().'/Extras/jdf.php',
			app_path().'/Extras/utilsCorrector.php',
		];
		foreach ($list as $filename){
			require_once($filename);
		}
	}

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
