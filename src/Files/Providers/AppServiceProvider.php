<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider {

	/**
	 * Register any application services.
	 */
	public function register(): void {
		//
	}

	/**
	 * Bootstrap any application services.
	 */
	public function boot(): void {
		// relating morph relations target_types to the respective models
		Relation::morphMap([
//			'customer' => Customer::class,
//          'admin' => Admin::class,
		]);


		// this function stores sql query logs inside storage/logs/sqlLogs.txt
		DB::enableQueryLog();
		DB::listen(function ($query) {
			$data = $query->sql . "\t [" . implode(',', $query->bindings) . "]  (time :$query->time)" . " \n \n";
			file_put_contents(storage_path('logs/sqlLogs.txt'), $data, FILE_APPEND);
		});

		Schema::defaultStringLength(191);
		View::share('prefixHtml', PREFIX_HTML);
	}
}
