<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\App\HomeController;
use App\Http\Controllers\App\SettingController;
use App\Http\Controllers\User\AdminController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
	Route::get('/',function (){
	return view('welcome');
	});

    Route::domain(SITE_URL_ADMIN)->middleware(['auth','rateLimiter:10,1'])->group(function () {

        if (request()->ajax()) {


			// ---------------------------------------------------------------------------------------------------------

			// dashboard info
			Route::get("dashboard/info", [HomeController::class, "dashboardInfo"]);


			// setting
			Route::resource("setting", SettingController::class);
			Route::post("setting/update", [SettingController::class, "update"]);
			Route::post('setting/uploadApp', [SettingController::class, 'uploadApp']);


		}

    });


    Route::domain(SITE_URL_ADMIN)->middleware('rateLimiter:10,1')->group(function () {

		Route::get('login', [AdminController::class,'login'])->name("login");

		Route::middleware('rateLimiter:5,1')->group(function () {
			Route::post('admin/login', [AdminController::class,'doLogin'])->name("admin.login");
			Route::get('logout', [AdminController::class,'logout'])->name("admin.logout");
		});
    });


	Route::domain(SITE_URL_ADMIN)->group(function () {
		if (request()->ajax()) {
			Route::any('/{any}',function () {
				return response()->json(['result' => ERR_ERROR_MESSAGE, 'message' => 'آدرس مورد نظر یافت نشد!'], \App\Extras\StatusCodes::HTTP_NOT_FOUND);
			})->where('any', '.*');;

		}else{

			Route::get('/{any}', [HomeController::class, 'index'])->where('any', '.*');
		}
	});
