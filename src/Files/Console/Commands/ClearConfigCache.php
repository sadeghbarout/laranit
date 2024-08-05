<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class ClearConfigCache extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cc';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'clear cache and config';

    /**
     * Execute the console command.
     */
    public function handle() {
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('config:cache');
    }
}
