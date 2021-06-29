Route::middleware('api')
				->namespace('App\Http\Controllers')
				->group(base_path('routes/test.php'));

			Route::domain(SITE_URL_API)