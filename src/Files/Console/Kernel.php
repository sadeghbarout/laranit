<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Artisan;

class Kernel extends ConsoleKernel
{
	/**
	 * The Artisan commands provided by your application.
	 *
	 * @var array
	 */
	protected $commands = [
		//
	];

	/**
	 * Define the application's command schedule.
	 *   /usr/local/bin/php /home/itodel/domains/itodel.com/laravel/artisan schedule:run
	 *
	 * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
	 * @return void
	 */
	protected function schedule(Schedule $schedule)
	{
		$opts = array('http' => array('header' => "User-Agent:MyAgent/1.0\r\n"));
		$context = stream_context_create($opts);


		$schedule->call(function () use ($context) {
			return file_get_contents('http://'.SITE_URL_API.'/dc/30', FALSE, $context);
		})
			->daily()
			->description('clear daily Logs')
			->emailOutputOnFailure(["cronjob@colbeh.ir"]);;

//		$schedule->command('sanctum:prune-expired --hours=24')->daily()
//			->name('delete expire tokens');

//		$schedule->call(function () {
//			Artisan::call("telescope:refresh");
//			return 'done';
//		})
//			->monthly()
//			->name('telescopeRefresh');

	}

	/**
	 * Register the commands for the application.
	 *
	 * @return void
	 */
	protected function commands()
	{
		$this->load(__DIR__.'/Commands');

		require base_path('routes/console.php');
	}
}
