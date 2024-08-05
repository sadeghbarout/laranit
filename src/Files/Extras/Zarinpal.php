<?php

namespace App\Extras;

use App\Exceptions\ErrorMessageException;
use App\Http\Controllers\Api\FinanceApiController;
use App\Models\User\User;
use Carbon\Carbon;

/**
 * Created by PhpStorm.
 * User: sadegh
 * Date: 03/06/2018
 * Time: 01:42 PM
 */
class Zarinpal {

	protected $marchentId;
	protected $callbackUrl;

	function __construct($merchentId = null, $callBackUrl = null) {
		if (!$merchentId) {
			$this->marchentId = env2('ZARINPAL_MERCHENT_ID');
		}
		if (!$callBackUrl) {
			$this->callbackUrl = env2('ZARINPAL_CALL_BACK_URL');
		}
	}

	public function newPurchase($price, $mobile, $desc = "", $email = "") {

		$opts = array(
			'http' => array(
				'user_agent' => 'PHPSoapClient'
			)
		);
		$context = stream_context_create($opts);

		$soapClientOptions = array(
			'stream_context' => $context,
			'cache_wsdl' => WSDL_CACHE_NONE,
			'encoding' => 'UTF-8'
		);

		$client=(object)[];
		try{

	//		$client = new \SoapClient('https://sandbox.zarinpal.com/pg/services/WebGate/wsdl', $soapClientOptions);
			 $client = new \SoapClient('https://www.zarinpal.com/pg/services/WebGate/wsdl', array('encoding' => 'UTF-8'));
	//		$client = new \SoapClient('https://ir.zarinpal.com/pg/services/WebGate/wsdl', array('encoding' => 'UTF-8'));
		}catch( \Exception $e){
			return generateResponse(ERR_ZARINPAL);
		}

		$result = $client->PaymentRequest(
			array(
				'MerchantID' => $this->marchentId,
				'Amount' => $price,
				'Description' => $desc,
				'Email' => $mobile,
				'Mobile' => $email,
				'CallbackURL' => $this->callbackUrl,
			)
		);

		//Redirect to URL You can do it also by creating a form
		if ($result->Status == 100) {
			return generateResponse(RES_SUCCESS, ['authority'=>  $result->Authority]);
		} else {
			echo 'ERR: ' . $result->Status;
			return generateResponse(ERR_ZARINPAL);
		}

	}

	public function createLink($authority) {
//		return "https://sandbox.zarinpal.com/pg/StartPay/" .$authority;
		return "https://www.zarinpal.com/pg/StartPay/" .$authority;
	}


	public function checkPurchase($authority, $price, $reCheck = false) {

//		$client = new \SoapClient('https://sandbox.zarinpal.com/pg/services/WebGate/wsdl', array('encoding' => 'UTF-8'));
		$client = new \SoapClient('https://ir.zarinpal.com/pg/services/WebGate/wsdl', array('encoding' => 'UTF-8'));

		$result = $client->PaymentVerification(
			array(
				'MerchantID' => $this->marchentId,
				'Authority' => $authority,
				'Amount' => $price
			)
		);

		if ($result->Status == 100 || $result->Status == 101) {
			if($reCheck)
				return ['status'=> $result->Status, 'refId'=> $result->RefID];

			return generateResponse(RES_SUCCESS, ['status'=> $result->Status, 'refId'=> $result->RefID]);
		}else {
			if($reCheck)
				return ['status'=> $result->Status];

			return generateResponse(ERR_ZARINPAL);
		}

	}

	public function reCheck($payment){
		$data = $this->checkPurchase($payment[COL_PAYMENT_AUTHORITY], $payment[COL_PAYMENT_AMOUNT], true);
		$user = User::findOrError($payment[COL_PAYMENT_USER_ID]);
		$status = $data['status'];

		if($status === 101 || $status === 100){ // success
			$refId = $data['refId'];
			(new FinanceApiController())->doCheckZarinpalPayment($payment,$user, $status, $refId);
		}else if($status === -51){ // fail
			$date = Carbon::parse($payment[COL_PAYMENT_CREATED_AT])->addMinutes(30);
			if($date < now()){
				$payment[COL_PAYMENT_STATUS] =  ENUM_PAYMENT_STATUS_CANCEL;
				$payment->save();
			}
		}else{
			return false;
		}

		return true;
	}
}