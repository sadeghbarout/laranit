<?php
use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'Colbeh\Laranit', 'prefix' => 'laranit'], function () {
//	Route::get('/', ['as' => 'bmi_path', 'uses' => 'ConstController@index']);

	Route::get('/ssss', "TestController@ssss");
});