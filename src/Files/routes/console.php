<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
	$this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/**
 * Define the application's command schedule.
 *   /usr/local/bin/php /home/itodel/domains/itodel.com/laravel/artisan schedule:run
 **/

$opts = array('http' => array('header' => "User-Agent:MyAgent/1.0\r\n"));
$context = stream_context_create($opts);


Schedule::call(function () use ($context) {
	return file_get_contents('http://'.SITE_URL_API.'/dc/30', FALSE, $context);
})
	->daily()
	->description('clear daily Logs')
	->emailOutputOnFailure(["cronjob@colbeh.ir"]);;

//Schedule::command('sanctum:prune-expired --hours=24')->daily()
//    ->name('delete expire tokens');
//
//Schedule::call(function () {
//    Artisan::call("telescope:refresh");
//    return 'done';
//})
//    ->monthly()
//    ->name('telescopeRefresh');

