<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Console\Command;

class TelescopeClear extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telescope-clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'clear telescope';

    /**
     * Execute the console command.
     */
    public function handle() {
        DB::table('telescope_entries_tags')->delete();
        DB::table('telescope_monitoring')->delete();

        $date = Carbon::now()->subDays(10);
        DB::table('telescope_entries')->whereDate('created_at', '<=', $date)->delete();

        return 'Telescope Cleared';
    }
}
