<?php

namespace App\Http\Controllers;
use App\Models\Admin;

class HomeController extends Controller{


	public function index(){
		if(auth()->check()){
			return view('dashboard.home');
		}else{
			return redirect('login');
		}
	}


	// -----------------------------------------------------------------------------------------------------------------------
	public function dashboardInfo(){

		$items['adminsCount']=Admin::allCount();


		return generateResponse(RES_SUCCESS,[RK_ITEMS => $items]);
    }

}
