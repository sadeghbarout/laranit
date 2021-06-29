private function basicRoutes() {
	Route::get('/cc', function() {
		Artisan::call('cache:clear');
		Artisan::call('config:clear');
		Artisan::call('config:cache');
		return "Cleared!";
	});
}

