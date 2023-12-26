<?php

namespace App\Http\Controllers\App;
use App\Http\Controllers\Controller;
use App\Models\User\Admin;

class HomeController extends Controller{


	public function index(){
		return view('dashboard.home');
	}


	// -----------------------------------------------------------------------------------------------------------------------
	public function dashboardInfo(){

		$items['adminsCount']=Admin::count();


		return generateResponse(RES_SUCCESS,[RK_ITEMS => $items]);
    }

}
