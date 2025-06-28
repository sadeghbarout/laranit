<?php
namespace App\Extras;

use App\Models\User\Customer;
use App\Models\App\Notification;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Contracts\Encryption\EncryptException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use Jwt\Algorithm\HS256Algorithm;
use Jwt\Algorithm\RS256Algorithm;
use Jwt\Exception;
use Jwt\Jwt;
use App\Exceptions\ErrorMessageException;

use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelLow;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\PngWriter;

use OpenSpout\Common\Entity\Row;
use OpenSpout\Common\Entity\Style\Style;
use OpenSpout\Reader\Common\Creator\ReaderFactory;
use OpenSpout\Writer\XLSX\Entity\SheetView;
use OpenSpout\Writer\XLSX\Writer;

class Tools {

	public static function correctNumber($number){
		$number=trim($number);
		$number = str_replace(array(",","۰", "۱", "۲", "۳", "۴", "۵", "۶", "۷", "۸", "۹"), array("","0", "1", "2", "3", "4", "5", "6", "7", "8", "9"), $number);
		return (int)$number;
	}

//-----------------------------------------------------------------------------------------------------------------------------------------------------------------
	public static function isNumberic($input, $min, $max, $optional = false) {
		if ($optional && $input == '') {
			return true;
		}
		if (preg_match('/^[.0-9]{' . $min . ',' . $max . '}$/', $input) || preg_match('/^[0-9]{' . $min . ',' . $max . '}$/', $input)) {
			return true;
		} else {
			return false;
		}
	}


//-----------------------------------------------------------------------------------------------------------------------------------------------------------------

	/* @param \Illuminate\Http\UploadedFile $file */
	public static function generateUniqueFileName($file, $prefix = "",$withTime=false) {

		if($withTime){
			return $prefix . getServerDate().str_replace(':','-',getServerTime()).'-'.rand(1,100) . '.' . $file->getClientOriginalExtension();

		}else{
			return $prefix . md5($file->getClientOriginalName() . time()) . '.' . $file->getClientOriginalExtension();
		}
	}

//-----------------------------------------------------------------------------------------------------------------------------------------------------------------

	public static function checkNationalCode($code)
	{
		if(!preg_match('/^[0-9]{10}$/',$code))
			return false;
		for($i=0;$i<10;$i++)
			if(preg_match('/^'.$i.'{10}$/',$code))
				return false;
		for($i=0,$sum=0;$i<9;$i++)
			$sum+=((10-$i)*intval(substr($code, $i,1)));
		$ret=$sum%11;
		$parity=intval(substr($code, 9,1));
		if(($ret<2 && $ret==$parity) || ($ret>=2 && $ret==11-$parity))
			return true;
		return false;
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------


	const TY_EMAIL = 1;
	const TY_PHONE = 2;
	const TY_API_TOKEN_CODE = 3;
	const TY_PASS = 4;
	const TY_INVITE_CODE = 5;
	const TY_QR_CODE = 6;

	/**
	 * generate code
	 * this function generates some  codes like phone validation code  or email validation code and some more
	 *
	 * @return string
	 */
	public static function generateCode($TY_type) {
		switch ($TY_type) {
			case self::TY_EMAIL:
				$alphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYS123456789abcdefghijklmnopqrstuvwxyz"; //used chars
				$codeLenght = 30;
				break;
			case self::TY_PHONE:
				$alphabet = "123456789"; //used chars
				$codeLenght = 4;
				break;

			case self::TY_API_TOKEN_CODE:
				$alphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYS_-.123456789abcdefghijklmnopqrstuvwxyz"; //used chars
				$codeLenght = 100;
				break;
			case self::TY_PASS:
				$alphabet = "123456789abcdefghijklmnopqrstuvwxyz"; //used chars
				$codeLenght = 6;
				break;
			case self::TY_INVITE_CODE:
				$alphabet = "123456789ABCDEFGHIJKLMNOPQRSTUVWYZ"; //used chars
				$codeLenght = 5;
				break;
            case self::TY_QR_CODE:
				$alphabet = "123456789abcdefghijklmnopqrstuvwxyz"; //used chars
				$codeLenght = 10;
				break;
		}
		$string = array();
		$alphaLength = strlen($alphabet) - 1;
		for ($i = 0; $i < $codeLenght; $i++) {
			$n = rand(0, $alphaLength);
			$string[] = $alphabet[$n];
		}
		return implode($string);
	}


//---------------------------------------------------------------------------------------------
// as the names says, it creates JWT token to identify and authenticate users in APIs
	private static $iis="xxxxxxxxxxxx"; // project name
	static function createJWTToken($array, $expireAfter = 2 * 365 * 1 * 24 * 60 * 60) {

		$now_seconds = time();
		$exp = $now_seconds + $expireAfter;
		$payload = array(
			"iat" => $now_seconds,
			"exp" => $exp,
			"iis" => self::$iis,
			"OS"=>Tools::detectAgent(),
		);
		$array = array_merge($payload, $array);
//		die(json_encode($array));
		$secret = config('app.jwt_secret');
//		die($secret);

		return Jwt::encode($array, new HS256Algorithm($secret));
	}

//---------------------------------------------------------------------------------------------
	static function JWTDecode($jwt) {
		$secret = config('app.jwt_secret');
		try {
			$decodedData = JWT::decode($jwt, ['algorithm' => new HS256Algorithm($secret)]);
			if($decodedData['iis']!=self::$iis)
				return '';
			return $decodedData->getPayload()->toArray();
		} catch (Exception $e) {
//			echo $e->getMessage();
			return '';
		}

	}

	//---------------------------------------------------------------------------------------------
	static function encryptData($data) {
		try {
			return Crypt::encrypt($data);
		} catch (EncryptException $e) {
//			echo $e->getMessage();
			return 0;
		}

	}
	//---------------------------------------------------------------------------------------------
	static function decryptData($data) {
		try {
			return Crypt::decrypt($data);
		} catch (DecryptException $e) {
//			echo $e->getMessage();
			return 0;
		}
    }



    // -------------------------------------------------------------------------------------------------
    // almost every image uploading process in this project uses this function to upload and crop images in the defined dimensions and puts the file in the specified path
    // compressImage() function does the cropping
    public static function uploadAndCompressImage($file, $path, $width=null, $height=null){
		$imageName = self::uploadFile($file, $path);

		if ($width != null || $height != null) {
			self::compressImage($path . $imageName, $path . $imageName, $width, $height);
		}
		return $imageName;
    }




    // -------------------------------------------------------------------------------------------------
    // almost every image uploading process in this project uses this function to upload and crop images in the defined dimensions and puts the file in the specified path
    // compressImage() function does the cropping
    public static function uploadFile($file, $path,$allowedExtensions=['jpg','jpeg','png','gif','bmp']){
    	$ext=$file->getClientOriginalExtension();
    	if(in_array(strtolower($ext),$allowedExtensions)){

			$imageName = md5($file->getClientOriginalName() . time()) . '.' . $file->getClientOriginalExtension();
			$file -> move($path, $imageName);
			return $imageName;
		}else{
			$extensionsStr=implode(', ',$allowedExtensions);
			throw new ErrorMessageException('این پسوند مجاز نمی باشد. پسوند های مجاز:'.$extensionsStr,StatusCodes::HTTP_NOT_FOUND);
		}
    }



	//---------------------------------------------------------------------------------------------

	public static function compressImage($source_url, $destination_url, $width, $height, $cropx1 = null, $cropx2 = null, $cropy1 = null, $cropy2 = null) {
		// calculate new size
		$size = getimagesize($source_url);
		$ratioWH = $size[0] / $size[1]; // width/height

		if ($width != null && $height != null) {
			$new_width = $width;
			$new_height = $height;
		} elseif ($width == null && $height != null) {

			$new_height = $height;
			$new_width = $ratioWH * $height;
		} elseif ($width != null && $height == null) {
			$new_width = $width;
			$new_height = $width / $ratioWH;
		} else {
			throw new \Exception('no Dimension');
			return;
		}
		$new_height = (int)($new_height);
		$new_width = (int)($new_width);

		// resize image
		$ext = pathinfo($source_url, PATHINFO_EXTENSION);

		if ($ext == "jpg" || $ext == "jpeg" || $ext == "JPG" || $ext == "JPEG") {
			$img = imagecreatefromjpeg($source_url);
			$width = imagesx($img);
			$height = imagesy($img);
			$tmp_img = imagecreatetruecolor($new_width, $new_height);
			if ($cropy2 != null) {
				imagecopyresampled($tmp_img, $img, 0, 0, $cropx1, $cropy1, $new_width, $new_height, $cropx2 - $cropx1, $cropy2 - $cropy1);
			} else {
				imagecopyresized($tmp_img, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
			}
			imagejpeg($tmp_img, $destination_url);
		} else if ($ext == "PNG" || $ext == "png") {
			$img = imagecreatefrompng($source_url);
			$width = imagesx($img);
			$height = imagesy($img);
			$tmp_img = imagecreatetruecolor($new_width, $new_height);


			imagecolortransparent($tmp_img, imagecolorallocatealpha($tmp_img, 0, 0, 0, 127));
			imagealphablending($tmp_img, false);
			imagesavealpha($tmp_img, true);

			if ($cropy2 != null) {
				imagecopyresampled($tmp_img, $img, 0, 0, $cropx1, $cropy1, $new_width, $new_height, $cropx2 - $cropx1, $cropy2 - $cropy1);
			} else {
				imagecopyresized($tmp_img, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
			}
			imagepng($tmp_img, $destination_url);

		} else if ($ext == "GIF" || $ext == "gif") {
//		$img = imagecreatefromgif($source_url);
//		$width = imagesx($img);
//		$height = imagesy($img);
//		$tmp_img = imagecreatetruecolor($new_width, $new_height);
//
//		imagecolortransparent($tmp_img, imagecolorallocatealpha($tmp_img, 0, 0, 0, 127));
//		imagealphablending($tmp_img, false);
//		imagesavealpha($tmp_img, true);
//
//		imagecopyresized($tmp_img, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
//		imagegif($tmp_img, $destination_url);

		}

		return $destination_url;
	}

	public static function detectAgent(){
			if(isset($_SERVER['HTTP_USER_AGENT']) && Str::contains($_SERVER['HTTP_USER_AGENT'],'okhttp')){
				return 'android';
			}elseif(isset($_SERVER['HTTP_USER_AGENT']) && Str::contains($_SERVER['HTTP_USER_AGENT'],'Alamofire')){
				return 'ios';
			}elseif(isset($_SERVER['HTTP_USER_AGENT'])){
				return 'web';
			}else{
				return 'unknown';
			}
	}

	/* ------------------------------------- CURL POST TO HTTP OR HTTPS --------------------------------- */
// if fielda arr was empty, this send a GET request
	public static function post2http($fields_arr, $url) {
		$ch = curl_init();

		$fields_string = "";
		if (sizeof($fields_arr) > 0) {
			foreach ($fields_arr as $key => $value) {
				$fields_string .= $key . '=' . $value . '&';
			}
			$fields_string = substr($fields_string, 0, -1);

			curl_setopt($ch, CURLOPT_POST, count($fields_arr));
			curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
		}
		if (strpos($url, "https://") !== false)
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		//execute post
		$res = curl_exec($ch);

		//close connection
		curl_close($ch);
		return $res;
	}



//-------------------------------------------------------------------------------------------------
    // sending notifications
	public static function sendNotification($message, $title, $users, $url = "", $data = null,$store = true, $type = 'text') {
		throw new \Exception('Not implement yet'); // todo:

//		$ans=true;
//		if($users=="")
//			return false;
//
//
//		$forAll = false;
//		if($users == "all"){ // user == "all" means sending notifs to every custome who has a firebase token
//            $forAll = true;
//
//		    $users = Customer::where(COL_CUSTOMER_FIREBASE_TOKEN ,'!=',"") -> get([COL_CUSTOMER_ID, COL_CUSTOMER_FIREBASE_TOKEN, COL_CUSTOMER_OS]) -> all();
//        }
//
//        // checking if $users is a collection
//        if ($users instanceof Collection){
//            $users = $users->all();
//        }
//        else{
//            if(!is_array($users))
//                $users=array($users);
//        }
//
//        if($users!=null && $users!='' && is_array($users) && sizeof($users)>0){
//
//            $tokenListAndroid = [];
//            $tokenListIos = [];
//			$tokenListPWA = [];
//            $notifsArray = [];
//
//
//			if(is_array($data))
//				$strData=json_encode($data);
//			else
//				$strData=$data;
//
////		    storing notifs to database
//
//            foreach($users as $user){
//                if(strtolower($user[COL_CUSTOMER_OS]) == 'android'){
//                    $tokenListAndroid[] = $user[COL_CUSTOMER_FIREBASE_TOKEN];
//                }
//                elseif(strtolower($user[COL_CUSTOMER_OS]) == 'ios'){
//                    $tokenListIos[] = $user[COL_CUSTOMER_FIREBASE_TOKEN];
//                }
//                elseif(strtolower($user[COL_CUSTOMER_OS]) == 'pwa'){
//                    $tokenListPWA[] = $user[COL_CUSTOMER_FIREBASE_TOKEN];
//                }
//
//                if(!$forAll)
//                    $notifsArray[] = self::notifArray($user[COL_CUSTOMER_ID],$title,$message,$type,$strData,$url);
//
//            }
//
//            // if $forAll == true, customer_id = -1
//            if($forAll)
//				$notifsArray[] = self::notifArray(-1,$title,$message,$type,$strData,$url);
//
//
//		    Notification::insert($notifsArray);
//
////		    pushing notifs
//			if(sizeof($tokenListAndroid)>0)
//				$ans=self::sendPushNotificationAndroid($message, $title, $tokenListAndroid, $url, $data);
//			if(sizeof($tokenListIos)>0)
//				$ans=self::sendPushNotificationIos($message, $title, $tokenListIos, $url, $data);
//			if(sizeof($tokenListPWA)>0)
//				$ans=self::sendPushNotificationPWA($message, $title, $tokenListPWA, $url, $data);
//		}
//
//		return $ans;
	}


	private static function notifArray ($customerId,$title,$message,$type,$strData,$url) {
		throw new \Exception('Not implement yet'); // todo:

//		return [
//			COL_NOTIFICATION_CUSTOMER_ID=> $customerId,
//			COL_NOTIFICATION_TITLE=> $title,
//			COL_NOTIFICATION_MESSAGE=> $message,
//			COL_NOTIFICATION_DATE=> getServerDateTime(),
//			COL_NOTIFICATION_TYPE=> $type,
//			COL_NOTIFICATION_DATA=> $strData,
//			COL_NOTIFICATION_URL=> $url,
//		];

	}


    //-------------------------------------------------------------------------------------------------
	// data example : $data= ["message" => $notification, "moredata" => 'dd']
	private static function sendPushNotificationAndroid($message, $title, $tokenList, $url, $data) {
		if(!$data){
			$data['a']='a';
		}
		$notification = [
			'title' => $title,
			'body' => $message,
			'click_action'=>$url,
			'sound'=> 'default'
		];

        $data['notification'] =$notification;
		$fields = [
			'registration_ids' => $tokenList, //multple token array
			'data' => $data
		];

		return self::sendFirebase($fields);
    }



    //-------------------------------------------------------------------------------------------------
	// data example : $data= ["message" => $notification, "moredata" => 'dd']
	private static function sendPushNotificationIos($message, $title, $tokenList, $url, $data) {
		if(!$data){
			$data['a']='a';
		}
		$notification = [
			'title' => $title,
			'body' => $message,
			'click_action'=>$url,
			'sound'=> 'default'
		];

		$fields = [
			'registration_ids' => $tokenList, //multple token array
			'notification' => $notification,
			'data' => $data
		];

		return self::sendFirebase($fields);
	}

	//-------------------------------------------------------------------------------------------------
	// data example : $data= ["message" => $notification, "moredata" => 'dd']
	private static function sendPushNotificationPWA($message, $title, $tokenList, $url, $data) {

		if(!$data){
			$data['a']='a';
		}
		$notification = [
			'title' => $title,
			'body' => $message,
			'click_action'=>$url,
			'sound'=> 'default'
		];

//		$data['notification'] =$notification;
		$fields = [
			'registration_ids' => $tokenList, //multple token array
			'notification' => $notification,
			'data' => $data
		];

		return self::sendFirebase($fields);
	}


//-----------------------------------------------------------------------------------------------------------------------------------------------------------------
	private static function sendFirebase($fields) {

		$headers = array(
			'Authorization: key=xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx',
			'Content-Type: application/json'
		);

		#Send Reponse To FireBase Server
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
		$result = curl_exec($ch);
		curl_close($ch);

		return $result;

    }


//-----------------------------------------------------------------------------------------------------------------------------------------------------------------


	public static function langMessage($key, $userLang = null, $data = []) {
		if ($userLang == null) {
			$lang = request(P_LANG, 'fa');
		} else {
			$lang = $userLang;
		}
		App::setLocale($lang);

		return __('index.' . $key, $data);
	}

//-----------------------------------------------------------------------------------------------------------------------------------------------------------------
	/**
	 * @throws ErrorMessageException
	 * @throws ResponseException
	 */
	public static function readExcelFile($file, $validation, $dataStartFrom = 1, $justValidate = 0) {
		$reader = ReaderFactory::createFromFileByMimeType($file);
		$reader->open($file);

		$sheetData = [];
		foreach ($reader->getSheetIterator() as $sheet) {
			foreach ($sheet->getRowIterator() as $row) {
				$sheetData[] = $row->toArray();
			}
		}

		$sheetData = array_values($sheetData);
		$sheetData = array_slice($sheetData, $dataStartFrom);

		$data = [];
		$keys = array_keys($validation);
		$keysCount = count($keys);

		foreach ($sheetData as $sheetRow) {
			$rowValues = array_filter($sheetRow, function($value) {
				return $value !== null && trim($value) !== '';
			});

			if (empty($rowValues)) {
				break;
			}

			$newData = [];
			$counter = 0;

			foreach ($sheetRow as $item) {
				if ($counter >= $keysCount) {
					break;
				}

				$newData[$keys[$counter]] = $item;
				$counter++;
			}

			$data[] = $newData;
		}

		$reader->close();

		$error = "";
		foreach ($data as $index => $item) {
			try {
				Validator::arrayValidator($item, $validation, false);
			}catch (ValidationException $e){
				$error .= "ردیف " . $index + 1 . $e->getMessage() . "<br>";
			}
		}

		if($error !== ""){
			throw new ErrorMessageException($error, StatusCodes::HTTP_BAD_REQUEST);
		}

		if($justValidate == 1){
			throw new ResponseException(new Response(resSuccess('فایل صحیح می باشد')));
		}

		return $data;
	}

	public static function excelFile($builder, $colsValue, $excelFileName) {
		$filePath = PATH_UPLOAD . PATH_TMP . "$excelFileName.xlsx";

		$sheetView = new SheetView();
		$sheetView->setShowRowColHeaders(true);
		$sheetView->setRightToLeft(true);

		$style = new Style();
		$style->setBackgroundColor(\OpenSpout\Common\Entity\Style\Color::LIGHT_GREEN);

		$writer = new Writer();
		$writer->openToFile($filePath);

		$sheet = $writer->getCurrentSheet()->setSheetView($sheetView);
		foreach ($colsValue as $colIndex => $cv) {
			$width = $cv['width']??160;
			$sheet->setColumnWidth($width / 8, $colIndex + 1);
		}

		$headerLabels = array_map(fn($col) => $col['label'], $colsValue);
		$headerRow = Row::fromValues($headerLabels);
		$headerRow->setStyle($style);
		$writer->addRow($headerRow);

		foreach ($builder as $item) {
			$rowData = [];

			foreach ($colsValue as $col) {
				$cv = $col['value'];
				$value = '';

				if ($cv instanceof \Closure) {
					$value = call_user_func($cv, $item);
				} elseif (is_array($cv)) {
					foreach ($cv as $key => $cv1) {
						$value .= $item[$key][$cv1] ?? '';
					}
				} else {
					$value = $item[$cv] ?? '';
				}

				$rowData[] = $value;
			}

			$writer->addRow(Row::fromValues($rowData));
		}

		$writer->close();

		return Storage::url(PATH_TMP . "$excelFileName.xlsx");
	}

	public static function getExcelDataLazy($builder) {
		$items = collect();
		$builder->chunk(10000, function ($data) use (&$items) {
			$items->push(...$data);
		});

		return $items;
	}

	public static function getSheetData($sheet) {
		$data = [];
		foreach ($sheet->getRowIterator() as $sheetData) {
			$data[] = $sheetData->toArray();
		}

		return $data;
	}

}
