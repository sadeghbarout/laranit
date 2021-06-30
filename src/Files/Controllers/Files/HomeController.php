<?php

namespace App\Http\Controllers\App;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Service\CarOrder;
use App\Models\Service\Consultation;
use App\Models\User\Customer;
use App\Models\Transaction\Exchanger;
use App\Models\Transaction\ExchangeRate;
use App\Models\Service\ProductOrder;
use App\Models\Service\ServiceOrder;
use App\Models\Transaction\Transaction;
use App\Models\Service\Translation;
use Illuminate\Support\Carbon;

class HomeController extends Controller{


	public function index(){
		if(auth()->check()){
			return view('dashboard.admin.home');
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
