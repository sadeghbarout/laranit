<?php

use App\Extras\Tools;
use App\Models\Transaction\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('rateLimiter:15,1')->group(function () {
	Route::get("app/start","AppApiController@appStart");
	Route::get("app/main","AppApiController@mainPage");
});


Route::middleware('rateLimiter:7,1')->group(function () {


	// place APIs here ...


});








Route::fallback(function(){
	if(request('debug_mode') == 1) {
		return response()->json(['result' => ERR_ERROR, 'message' => 'No route found!'], \App\Extras\StatusCodes::HTTP_NOT_FOUND);
	} else {
		return response()->json(['result' => ERR_ERROR_MESSAGE, 'message' => 'No route found!'], \App\Extras\StatusCodes::HTTP_NOT_FOUND);
	}

});
