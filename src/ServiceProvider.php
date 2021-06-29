<?php

namespace Colbeh\Laranit;


use Illuminate\Support\Facades\Gate;

class ServiceProvider extends \Illuminate\Support\ServiceProvider {

	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot() {
		include __DIR__ . '/routes.php';

//		$this->publish();

		$this->defineGates();
	}


	private function publish() {
		$this->publishes([
			__DIR__ . '/Files/.env' => base_path('.env'),
			__DIR__ . '/Files/.env.local' => base_path('.env.local'),
			__DIR__ . '/Files/Extras' => app_path('Extras'),
			__DIR__ . '/Files/Controllers/Files' => app_path('Http/Controllers'),
			__DIR__ . '/Files/Models' => app_path('Models'),
			__DIR__ . '/Files/Middleware' => app_path('Http/Middleware'),
			__DIR__ . '/Files/Exceptions' => app_path('Exceptions'),
			__DIR__ . '/Files/routes/test.php' => base_path('routes/test.php'),
			__DIR__ . '/Files/AppServiceProvider.php' => app_path('Providers/AppServiceProvider.php'),
			__DIR__ . '/Files/Models' => app_path('Models'),
			__DIR__ . '/Files/utils.json' => resource_path('files/utils.json'),
			__DIR__ . '/Files/seeders' => database_path('seeders'),
		], 'config');



		$this->copyLoadEnvironmentsCode();

		$this->addHelpersToComposerJson();

		$this->copyBaseControllerFunctions();

		$this->addMiddlewareToKernel();

		$this->routesFunctions();

//
//
//		$this->publishes([
//			__DIR__ . '/seeders/PermissionsSeeder.php' => database_path('seeders/PermissionsSeeder.php'),
//			__DIR__ . '/migrations/2021_01_01_000000_create_admin_role_table.php' => database_path('migrations/2021_01_01_000000_create_admin_role_table.php'),
//			__DIR__ . '/migrations/2021_01_01_000000_create_permission_role_table.php' => database_path('migrations/2021_01_01_000000_create_permission_role_table.php'),
//			__DIR__ . '/migrations/2021_01_01_000000_create_permissions_table.php' => database_path('migrations/2021_01_01_000000_create_permissions_table.php'),
//			__DIR__ . '/migrations/2021_01_01_000000_create_roles_table.php' => database_path('migrations/2021_01_01_000000_create_roles_table.php'),
//		], 'database');
	}

	public function addMiddlewareToKernel() {
		$str = '
		\App\Http\Middleware\LogAfterRequest::class,
		\App\Http\Middleware\CleanStrings::class,
//      \App\Http\Middleware\ForceHttps::class,
';

		$needle='protected $middleware = [';

		$filePath = app_path('Http/Kernel.php');
		$kernelContent = file_get_contents($filePath);
		$pos = strpos($kernelContent, $needle);
		$kernelContent = substr_replace($kernelContent, $str, $pos+strlen($needle), 0);
		file_put_contents($filePath, $kernelContent);
	}

	public function test() {
		return $this->addMiddlewareToKernel();

	}

	public function copyLoadEnvironmentsCode() {
		$str = '/*
|--------------------------------------------------------------------------
| Load Environment variables
|--------------------------------------------------------------------------
|
*/

$app->loadEnvironmentFrom(".env." . env2("APP_ENV"));
';

		$filePath = base_path('bootstrap/app.php');
		$appContent = file_get_contents($filePath);
		$pos = strpos($appContent, 'return $app;');
		$appContent = substr_replace($appContent, $str, $pos, 0);
		file_put_contents($filePath, $appContent);

	}


	public function addHelpersToComposerJson() {
		$filePath = base_path('composer.json');
		$composerContent = file_get_contents($filePath);
		$composerArray=json_decode($composerContent,true);

		$files[]= "app/Extras/jdf.php";
		$files[]= "app/Extras/helpers.php";
		$files[]= "app/Extras/utilsCorrector.php";
		$files[]= "app/Extras/consts.php";
		$composerArray['autoload']['files']= $files;

		$composerContent=json_encode($composerArray,JSON_PRETTY_PRINT);
		$composerContent=str_replace("\/","/",$composerContent);
		file_put_contents($filePath, $composerContent);
	}

	public function copyBaseControllerFunctions(){
		$str = file_get_contents(__DIR__.'/Files/Controllers/doEditing.php');

		$filePath = app_path('Http/Controllers/Controller.php');
		$appContent = file_get_contents($filePath);
		$pos = strrpos($appContent, '}');
		$appContent = substr_replace($appContent, $str, $pos, 0);
		file_put_contents($filePath, $appContent);
	}

	public function routesFunctions() {
		$str = file_get_contents(__DIR__.'/Files/routes/basicRoutes.php');

		$filePath = app_path('Providers/RouteServiceProvider.php');
		$appContent = file_get_contents($filePath);
		$pos = strrpos($appContent, '}');
		$appContent = substr_replace($appContent, $str, $pos, 0);


		$str = file_get_contents(__DIR__.'/Files/routes/routes.php');
		$appContent=str_replace('Route::prefix(\'api\')',$str,$appContent);


		$needle='$this->configureRateLimiting();';
		$pos = strrpos($appContent, $needle);
		$appContent=substr_replace($appContent,'$this->basicRoutes();',$pos+strlen($needle),0);


		file_put_contents($filePath, $appContent);
	}

	private function defineGates() {
		Gate::define('permission', function ($user, $permissions) {


			$adminPermissions = Helper::getAdminPermissions($user);


			if (!is_array($permissions)) {
				$permissions = array($permissions);
			}
			$permissions[] = 'root';
			return null !== $adminPermissions->whereIn('name', $permissions)->first();
		});
	}


}