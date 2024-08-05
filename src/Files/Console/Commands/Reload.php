<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Reload extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rr';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'reload app';

    /**
     * Execute the console command.
     */
    public function handle() {
        $result = exec('supervisorctl reload', $output, $exitCode);
        if ($exitCode === 0) {
            return "$result";
        } else {
            return "Error! $result";
            dd("Error!", $output, $exitCode, $result);
        }
    }
}
