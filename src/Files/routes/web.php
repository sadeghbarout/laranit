<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\App\HomeController;
use App\Http\Controllers\App\RoleController;
use App\Http\Controllers\App\SettingController;
use App\Http\Controllers\User\AdminController;
use App\Http\Controllers\App\UploadFileController;


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

			Route::get("dashboard/info", [HomeController::class, "dashboardInfo"]);


			// setting
			Route::resource("setting", SettingController::class);
			Route::post("setting/update", [SettingController::class, "update"]);
			Route::post('setting/uploadApp', [SettingController::class, 'uploadApp']);

			// admin
			Route::get("admin/profile", [AdminController::class, 'profile']);
			Route::post("admin/uploadProfileImage", [AdminController::class, 'uploadProfileImage']);
			Route::post("admin/changePassword", [AdminController::class, 'doChangePassword']);
			Route::post("admin/newPassword", [AdminController::class, 'setNewPassword']);
			Route::resource("admin", AdminController::class);
			Route::post("admin/role", [AdminController::class, "adminRoleOperation"]);
			Route::resource("admin", AdminController::class);
			Route::post("admin/role", [AdminController::class, "roleToggle"]);
			Route::post("admin/columns", [AdminController::class, "columnToggle"]);

			Route::get("getUploadFiles", [UploadFileController::class, 'getUploadFiles']);

			// role
			Route::post("role/permission", [RoleController::class, "permissionToggle"]);
			Route::resource("role", RoleController::class);
			Route::get("permission", [RoleController::class, "permissions"]);
		}

    });

	Route::domain(SITE_URL_ADMIN)->middleware('rateLimiter:10,1')->group(function () {
		Route::post('admin/login', [AdminController::class,'doLogin'])->name("admin.login");
		Route::get('logout', [AdminController::class,'logout'])->name("admin.logout");
		Route::get('init-admin', [AdminController::class, 'initAdmin']);
	});



	Route::domain(SITE_URL_ADMIN)->group(function () {
		Route::get('/{any}', [HomeController::class,'index'])->where('any', '.*');
	});
