<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class MigrateAlter extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'migrate:alter {number?}';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'run alter migrations';

	/**
	 * Execute the console command.
	 */
	public function handle() {
		$migrationNumber = $this->argument('number');

		$path = database_path('migrations/alters');

		if (!File::isDirectory($path)) {
			$this->error("Path $path does not exist!");
			return;
		}

		$files = File::files($path);

		if (empty($files)) {
			$this->warn("No files found in $path!");
			return;
		}

		$fileNames = [];
		$files = array_reverse($files);
		foreach ($files as $file) {
			$migration = DB::table('migrations')->where('migration', str_replace('.php', '', $file->getFilename()))->first();
			if($migration){
				break;
			}

			$fileNames[] = $file->getFilename();
		}

		if(count($fileNames) === 0){
			$this->warn("Nothing to migrate");
			return;
		}

		$fileNames = array_values(array_reverse($fileNames));

		$this->info("Files in $path:");
		foreach ($fileNames as $fileName) {
			$cleanFileName = explode('.', substr($fileName, 11))[0];
			if($cleanFileName == $migrationNumber){
				$this->runMigration($fileName);
				return;
			}
		}

		if($migrationNumber){
			$this->error("not found migration: $migrationNumber");
		}

		$fileName = $this->choice('Please select a file', $fileNames, 1);

		$this->runMigration($fileName);
	}

	private function runMigration($fileName) {
		$migrationPath = "database/migrations/alters/" . $fileName;

		$this->info("executing $fileName:");

		Artisan::call('migrate', [
			'--path' => $migrationPath
		]);

		$this->line(Artisan::output());
	}
}
