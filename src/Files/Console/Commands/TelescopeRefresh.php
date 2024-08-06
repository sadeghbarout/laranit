<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class TelescopeRefresh extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telescope:refresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'refresh telescope database';

    /**
     * Execute the console command.
     */
    public function handle() {
        $time = \Illuminate\Support\Carbon::now()->timestamp;

        $telescopeConnection = config('telescope.storage.database.connection');

        Schema::connection($telescopeConnection)->rename('telescope_entries', "telescope_entries_$time");
        $this->comment("telescope_entries_$time");
        DB::connection($telescopeConnection)->statement("CREATE TABLE telescope_entries LIKE telescope_entries_$time");

        Schema::connection($telescopeConnection)->rename('telescope_entries_tags', "telescope_entries_tags_$time");
        $this->comment("telescope_entries_tags_$time");
        DB::connection($telescopeConnection)->statement("CREATE TABLE telescope_entries_tags LIKE telescope_entries_tags_$time");


        $this->info('mission accomplished');
    }
}
