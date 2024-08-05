private function basicRoutes() {
    Route::get('/telescope-clear', function() {
        Artisan::call('telescope-clear');
    });
    Route::get('/rr', function() {
    //		if(getUserIp()=="193.105.234.144"){
                return \Artisan::call('rr');
    //		}
        abort(403);
    });
    Route::get('/cc', function() {
        \Artisan::call('cc');
        return "Cleared!";
    });
    Route::get('/ccc', function() {
        \Artisan::call('ccc');
        return "Cleared!";
    });
    Route::get('/ccr', function() {
        \Artisan::call('ccc');
        return "Cleared And ".\Artisan::call('rr');
    });
    Route::get('/dc/{count?}', function($count=10) {
        $result = \Artisan::call("dc $count");
        return generateResponse(RES_SUCCESS, ["data" => "clear $result daily logs"]);
    });
}

