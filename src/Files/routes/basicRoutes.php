private function basicRoutes() {

    Route::get('/telescope-clear', function() {
        \DB::table('telescope_entries_tags')->delete();
        \DB::table('telescope_monitoring')->delete();

        $date = Carbon::now()->subDays(10);
        \DB::table('telescope_entries')->whereDate('created_at', '<=', $date)->delete();

        return 'Telescope Cleared';
    });

    Route::get('/cc', function() {
        Artisan::call('cc');
        return "Cleared!";
    });

    \Artisan::command('cc', function () {
        \Artisan::call('cache:clear');
        $this->comment('clear cache!');
        \Artisan::call('config:clear');
        $this->comment('clear config!');
        \Artisan::call('config:cache');
        $this->comment('cache config!');
        $this->info('The job was done successfully');
    });


    Route::get('/dc/{count?}', function($count=10) {
        $result = \Artisan::call("dc $count");
        return "clear $result daily logs";
    });

    \Artisan::command('dc {count?}', function ($count=10) {
        if($count < 5){ // check counts
            return response('Unauthorized request',\StatusCodes::HTTP_UNAUTHORIZED);
        }

        $path = storage_path('logs/dailylogs/');
        $logs = array_diff(scandir($path), ['..', '.']);
        rsort($logs);

        $logs = array_slice($logs, $count);
        $count = 0;
        foreach ($logs as $log){
        $files = array_diff(scandir($path .$log), ['..', '.']);

        foreach ($files as $file){
            deleteFile($file, $path .$log .'/');
        }
        rmdir($path . $log);
            $count++;
        }
        return $count;
    });

}

