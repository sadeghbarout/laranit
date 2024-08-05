<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class ClearConfig extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ccc';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'clear config';

    /**
     * Execute the console command.
     */
    public function handle() {
        Artisan::call('config:clear');
        Artisan::call('config:cache');    }
}
