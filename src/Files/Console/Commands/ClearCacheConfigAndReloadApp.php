<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class ClearCacheConfigAndReloadApp extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ccr';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'clear cache and config and reload app';

    /**
     * Execute the console command.
     */
    public function handle() {
        Artisan::call('ccc');
        return "Cleared And ". Artisan::call('rr');
    }
}
