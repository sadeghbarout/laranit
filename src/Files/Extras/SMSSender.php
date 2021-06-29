<?php
/*
 * this class will send SMS
 */
namespace App\Extras;

use Illuminate\Support\Facades\Log;
use Kavenegar\KavenegarApi;

class SMSSender {

	protected $_message, $_phoneNumber, $_message2, $_message3, $_message4;

	// these are the pre defined sms templates in kavehnegar website
	const TEMPLATE_NAME = 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxx';


	// constructor to recieve phoneNumber and message of sms
	public function __construct($phoneNumber = "", $message = "", $message2 = "", $message3 = "", $message4 = "") {

		if (substr($phoneNumber, 0, 1) != '+')
			$phoneNumber = '+' . $phoneNumber;

		if (substr($phoneNumber, 0, 3) != '+98')
			$phoneNumber = str_replace("+", "00", $phoneNumber);

		$this->_phoneNumber = $phoneNumber;
		$this->_message = $message;
		$this->_message2 = $message2;
		$this->_message3 = $message3;
		$this->_message4 = $message4;
	}


	public function sendMessage($template) {

		$phonenumber = $this->_phoneNumber;
		$token1 = $this->_message;
		$token2 = $this->_message2;
		$token3 = $this->_message3;
		$token4 = $this->_message4;

		if (substr($phonenumber, 0, 1) != "+")
			$phonenumber = '+' . $phonenumber;

		if (substr($phonenumber, 0, 3) == "+98") {
			return $this->sendKavehnegar($phonenumber, $token1, $template, $token2, $token3, $token4);
		} else {
			return $this->sendKavehnegar($phonenumber, $token1, $template, $token2, $token3, $token4);
		}
	}


	/**
	 *
	 * twilio sms service
	 *
	 * @return string
	 */
	private function sendTwilio($phonenumber, $text) {

		$id = "AC1faxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx";
		$token = "51fddxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx";
		$url = "https://api.twilio.com/2010-04-01/Accounts/$id/SMS/Messages";
		$from = "+15100000000";
		$to = $phonenumber; //"+989357083778"; // twilio trial verified number
		$body = $text;
		$data = array(
			'From' => $from,
			'To' => $to,
			'Body' => $body,
		);
		$post = http_build_query($data);
		$x = curl_init($url);
		curl_setopt($x, CURLOPT_POST, true);
		curl_setopt($x, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($x, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($x, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($x, CURLOPT_USERPWD, "$id:$token");
		curl_setopt($x, CURLOPT_POSTFIELDS, $post);
		$y = curl_exec($x);
		curl_close($x);
//		var_dump($post);
		return $y;
	}


	/**
	 *
	 * kavenegar sms service
	 *
	 * @return string
	 */
	private function sendKavehnegar($receptor, $token1, $template, $token2 = "", $token3 = "", $token10 = '') {
		try {
			$api = new KavenegarApi("xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx");
			$type = "sms"; //sms | call

			$result = $api->VerifyLookup($receptor, $token1, $token2, $token3, $template, $type, $token10);

			$res = $result[0];
			if ($res->status == 6) {
				$this->onSendFailed("kavehnegar code:6/ receptor:$receptor/ token1:$token1");
				return "unsuccess";
			} else {
				return "success";
			}
		} catch (\Kavenegar\Exceptions\ApiException $e) {
			$this->onSendFailed($e->errorMessage() . " / receptor:$receptor/ token1:$token1");
			Log::error("kavehnegar_error : " . $e->errorMessage());
		} catch (\Kavenegar\Exceptions\HttpException $e) {
			$this->onSendFailed($e->errorMessage() . " / receptor:$receptor/ token1:$token1");
			Log::error("kavehnegar_error : " . $e->errorMessage());
		}
	}


	public function onSendFailed($desc) {
		Tools::post2http(['token' => 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx',
				'section' => 'smsSender',
				'desc' => $desc]
			, "https://projects.colbeh.ir/bug/report", true);
	}
}


?>
