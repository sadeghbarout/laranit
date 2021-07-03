<?php

namespace Colbeh\Laranit;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class PublishCommand extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'laranit:publish';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Send a marketing email to a user';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	public function handle()
	{
///
		Artisan::call('vendor:publish',
			[
				'--provider'=>"Colbeh\Laranit\ServiceProvider",
				'--force'=>"1",
			]
		);
		Artisan::call('vendor:publish',
			[
				'--provider'=>"Colbeh\Access\ServiceProvider",
				'--tag'=>"database",
			]
		);
		$this->copyLoadEnvironmentsCode();

//		$this->addHelpersToComposerJson();

		$this->copyBaseControllerFunctions();

		$this->addMiddlewareToKernel();

		$this->routesFunctions();

		$this->modifyLangValidations();

		$this->modifyPackageJson();

		$this->modifyConfigAppFile();

		$this->addHelpersServiceProvider();

		Artisan::call('key:generate');

	}


	//------------------------------------------------



	public function addMiddlewareToKernel() {
		$str = '
		\App\Http\Middleware\LogAfterRequest::class,
		\App\Http\Middleware\CleanStrings::class,
        \App\Http\Middleware\CheckPermission::class,
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

$app->loadEnvironmentFrom(".env.".file_get_contents(__DIR__."/../.env"));
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

		$appContent=str_replace('// protected $','protected $',$appContent);

		file_put_contents($filePath, $appContent);
	}

	public function modifyLangValidations() {

		$filePath = resource_path('lang/en/validation.php');
		$appContent = file_get_contents($filePath);
		$appContent=str_replace('The :attribute must be an array.','پارامتر :attribute باید آرایه باشد.',$appContent);
		$appContent=str_replace('The :attribute must be between :min and :max.','پارامتر :attribute باید بین :min و :max باشد.',$appContent);
		$appContent=str_replace('The :attribute field must be true or false.','پارامتر :attribute باید 0 یا 1 باشد.',$appContent);
		$appContent=str_replace('The :attribute must be an array.','پارامتر :attribute باید آرایه باشد.',$appContent);
		$appContent=str_replace('The :attribute must be between :min and :max.','پارامتر :attribute باید بین :min و :max باشد.',$appContent);
		$appContent=str_replace('The :attribute field must be true or false.','پارامتر :attribute باید 0 یا 1 باشد.',$appContent);
		$appContent=str_replace('The :attribute must be :digits digits.',':attribute باید :digits رقم باشد.',$appContent);
		$appContent=str_replace('The :attribute must be between :min and :max digits.',':attribute باید بین :min و :max رقم باشد.',$appContent);
		$appContent=str_replace('The :attribute must be a valid email address.','پارامتر :attribute باید یک ایمیل صحیح باشد.',$appContent);
		$appContent=str_replace('The :attribute must be a file.',':attribute باید یک فایل باشد.',$appContent);
		$appContent=str_replace('The :attribute must be greater than :value.','پارامتر :attribute باید کمتر از :value باشد.',$appContent);
		$appContent=str_replace('The :attribute must be greater than or equal :value.','پارامتر :attribute باید کمتر یا مساوی :value باشد.',$appContent);
		$appContent=str_replace('The :attribute must be less than :value.','پارامتر :attribute نباید بیشتر از :max کاراکتر باشد.',$appContent);
		$appContent=str_replace('The :attribute must be less than :value characters.','پارامتر :attribute نباید بیشتر از :max کاراکتر باشد.',$appContent);
		$appContent=str_replace('The :attribute must be less than or equal :value.','پارامتر :attribute باید حداقل :min کاراکتر باشد.',$appContent);
		$appContent=str_replace('The :attribute must be less than or equal :value characters.','پارامتر :attribute باید حداقل :min کاراکتر باشد..',$appContent);
		$appContent=str_replace('The :attribute must not be greater than :max.','پارامتر :attribute باید یک عدد باشد.',$appContent);
		$appContent=str_replace('The :attribute field is required.','فیلد :attribute اجباری است.',$appContent);
		$appContent=str_replace('The :attribute must be a string.','پارامتر :attribute باید یک متن باشد.',$appContent);
		$appContent=str_replace('The :attribute has already been taken.','پارامتر :attribute قبلا استفاده شده است.',$appContent);
		$appContent=str_replace('\'attributes\' => []','\'attributes\' => [
        \'password\'=>\'پسورد\',
        ]',$appContent);

		file_put_contents($filePath, $appContent);


	}




	public function modifyPackageJson() {
		$filePath = base_path('package.json');
		$composerContent = file_get_contents($filePath);
		$composerArray=json_decode($composerContent,true);

		$composerArray['devDependencies']['vue']= "^3.1.4";
		$composerArray['devDependencies']['vue-loader']= "^16.1.0";
		$composerArray['devDependencies']['vue-router']= "^4.0.10";
		$composerArray['devDependencies']['vue-template-compiler']= "^2.6.14";
		$composerArray['devDependencies']['@vue/compiler-sfc']= "^3.0.5";
		$composerArray['devDependencies']['cross-env']= "^7.0";
		$composerArray['devDependencies']['bootstrap']= "^4.0.0";
		$composerArray['devDependencies']['popper.js']= "^1.12";
		$composerArray['devDependencies']['sweetalert2']= "^7.33.1";
		$composerArray['devDependencies']['jquery']= "^3.2";

		$composerContent=json_encode($composerArray,JSON_PRETTY_PRINT);
		$composerContent=str_replace("\/","/",$composerContent);
		file_put_contents($filePath, $composerContent);
	}


	public function modifyConfigAppFile() {
		$str = "
		
		
	'base_url'=>env('BASE_URL'),
	'jwt_secret'=>env('JWT_SECRET'),
	
	
	";

		$needle='return [';
		$filePath = config_path('app.php');
		$appContent = file_get_contents($filePath);
		$pos = strpos($appContent, $needle);
		$appContent = substr_replace($appContent, $str, $pos+strlen($needle), 0);
		file_put_contents($filePath, $appContent);

	}


	public function addHelpersServiceProvider() {
		$str = " App\Providers\HelperServiceProvider::class,
	";

		$needle='App\Providers\AppServiceProvider::class,';
		$filePath = config_path('app.php');
		$appContent = file_get_contents($filePath);
		$pos = strpos($appContent, $needle);
		$appContent = substr_replace($appContent, $str, $pos, 0);
		file_put_contents($filePath, $appContent);

	}

}