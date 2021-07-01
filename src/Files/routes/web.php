<?php

use Illuminate\Support\Facades\Route;
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

    Route::domain(SITE_URL_ADMIN)->middleware('auth')->group(function () {

        if (request()->ajax()) {


			// ---------------------------------------------------------------------------------------------------------
			Route::namespace('App')->group(function (){

				// dashboard info
				Route::get("dashboard/info","HomeController@dashboardInfo");


				// setting
				Route::resource("setting","SettingController");
				Route::post("setting/update","SettingController@update");
				Route::post('setting/uploadApp', 'SettingController@uploadApp');

			});

        }

    });


    Route::domain(SITE_URL_ADMIN)->middleware('throttle:20,1')->group(function () {

		Route::get('login', 'AdminController@login')->name("login");
		Route::post('admin/login', 'AdminController@doLogin')->name("admin.login");
		Route::get('logout', 'AdminController@logout')->name("admin.logout");

    });


    Route::domain(SITE_URL_ADMIN)->group(function () {
		Route::get('/{any}', 'HomeController@index')->where('any', '.*');
    });
