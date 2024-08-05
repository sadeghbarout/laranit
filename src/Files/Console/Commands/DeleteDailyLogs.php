<?php

namespace App\Console\Commands;

use App\Exceptions\ErrorMessageException;
use Illuminate\Console\Command;

class DeleteDailyLogs extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dc {count?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'delete daily logs';

    /**
     * Execute the console command.
     *
     * @throws ErrorMessageException
     */
    public function handle() {
        $count = $this->argument('count');
        $count=$count??10;

        if ($count < 5) { // check counts
            throw new ErrorMessageException('Unauthorized request', 403);
        }

        $path = storage_path('logs/dailylogs/');
        $logs = array_diff(scandir($path), ['..', '.']);
        rsort($logs);

        $logs = array_slice($logs, $count);
        $count = 0;
        foreach ($logs as $log) {
            $files = array_diff(scandir($path . $log), ['..', '.']);

            foreach ($files as $file) {
                deleteFile($file, $path . $log . '/');
            }
            rmdir($path . $log);
            $count++;
        }
        return $count;
    }
}
