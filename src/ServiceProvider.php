<?php

namespace Colbeh\Laranit;



use Illuminate\Support\Facades\Artisan;

class ServiceProvider extends \Illuminate\Support\ServiceProvider {

	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot() {
		include __DIR__ . '/routes.php';


		if ($this->app->runningInConsole()) {
			$this->commands([
				PublishCommand::class,
			]);
		}


		$this->publish();
	}


	private function publish() {
		$this->publishes([
			__DIR__ . '/Files/bootstrap/app.php' => base_path('bootstrap/app.php'),
			__DIR__ . '/Files/bootstrap/providers.php' => base_path('bootstrap/providers.php'),
			__DIR__ . '/Files/.env' => base_path('.env'),
			__DIR__ . '/Files/.env.local' => base_path('.env.local'),
			__DIR__ . '/Files/Extras' => app_path('Extras'),
			__DIR__ . '/Files/Console/Commands' => app_path('Console/Commands'),
			__DIR__ . '/Files/Controllers/Files' => app_path('Http/Controllers'),
			__DIR__ . '/Files/Models' => app_path('Models'),
			__DIR__ . '/Files/lang' => base_path('lang/fa'),
			__DIR__ . '/Files/Middleware' => app_path('Http/Middleware'),
			__DIR__ . '/Files/Exceptions' => app_path('Exceptions'),
			__DIR__ . '/Files/routes/test.php' => base_path('routes/test.php'),
			__DIR__ . '/Files/routes/basic.php' => base_path('routes/basic.php'),
			__DIR__ . '/Files/routes/console.php' => base_path('routes/console.php'),
			__DIR__ . '/Files/routes/api.php' => base_path('routes/api.php'),
			__DIR__ . '/Files/routes/web.php' => base_path('routes/web.php'),
			__DIR__ . '/Files/routes/site.php' => base_path('routes/site.php'),
			__DIR__ . '/Files/providers/AppServiceProvider.php' => app_path('Providers/AppServiceProvider.php'),
			__DIR__ . '/Files/providers/HelperServiceProvider.php' => app_path('Providers/HelperServiceProvider.php'),
			__DIR__ . '/Files/utils.json' => resource_path('files/utils.json'),
			__DIR__ . '/Files/list-columns.json' => resource_path('files/list-columns.json'),
			__DIR__ . '/Files/seeders' => database_path('seeders'),
			__DIR__ . '/Files/config/auth.php' => base_path('config/auth.php'),
			__DIR__ . '/Files/resources/assets/libs' => resource_path('assets/libs'),
			__DIR__ . '/Files/resources/font' => resource_path('font'),
			__DIR__ . '/Files/resources/images' => public_path('images'),
			__DIR__ . '/Files/vite.config.js' => base_path('vite.config.js'),
			__DIR__ . '/Files/webpack.mix.js' => base_path('webpack.mix.js'),
			__DIR__ . '/Files/resources/views' => resource_path('views'),
			__DIR__ . '/Files/resources/js' => resource_path('js'),
			__DIR__ . '/Files/resources/css' => resource_path('css'),
			__DIR__ . '/Files/resources/vuexy-assets' => resource_path('vuexy-assets'),
			__DIR__ . '/Files/migrations/2014_10_12_000000_create_admins_table.php' => database_path('migrations/2014_10_12_000000_create_admins_table.php'),
			__DIR__ . '/Files/migrations/2021_01_01_000000_create_settings_table.php' => database_path('migrations/2021_01_01_000000_create_settings_table.php'),
			__DIR__ . '/Files/migrations/alters/2025_06_24_1.php' => database_path('migrations/alters/2025_06_24_1.php'),
			__DIR__ . '/Files/migrations/2025_01_02_091055_create_upload_files_table.php' => database_path('migrations/2025_01_02_091055_create_upload_files_table.php'),
			__DIR__ . '/Files/Rules' => app_path('Rules'),
		], 'config');

	}

}
