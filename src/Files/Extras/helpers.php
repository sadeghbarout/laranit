<?php
/**
 * Created by PhpStorm.
 * User: sadegh
 * Date: 12/04/2018
 * Time: 04:24 PM
 */
// some date and time function
function setTimezone() {
	date_default_timezone_set('Asia/tehran');
}

/**
 * return server time (hour:minute:second)
 *
 * @return bool|string
 */
function getServerTime() {
	setTimezone();
	return date("H:i:s");
}

/**
 * return server time (year-mounth-day)
 *
 * @return bool|string
 */
function getServerDate() {
	setTimezone();
	return date("Y-m-d");
}

/**
 * return server date and time  (year-mounth-day hour:minute:second)
 *
 * @return bool|string
 */
function getServerDateTime() {
	setTimezone();
	return date("Y-m-d H:i:s");
}


function getYear($hejri = false) {
	setTimezone();

    if($hejri)
        $year = jdate('Y',null,null,null,'en');
    else
        $year = date('Y');

	return $year;
}


function getMonth($hejri = false, $text=false){
    setTimezone();

    if($hejri)
        $month = jdate($text ? 'F' : 'm');
    else
        $month = date($text ?'M':'m');

	return $month;
}


function getDay($hejri = false, $text=false){
    setTimezone();

    if($hejri)
        $month = jdate($text ? 'l' : 'd',null,null,null,'en');
    else
        $month = date($text ?'D':'d');

	return $month;
}

//-----------------------------------------------------------------------------------------------------------------------------------------------------------------
// clears input value
function clear($value){
	$value = str_replace(array("ي", "ك"), array("ی", "ک"), $value);
	$value = str_replace(array("۰", "۱", "۲", "۳", "۴", "۵", "۶", "۷", "۸", "۹"), array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9"), $value);
	$value=trim($value);
//	if($value==null){
//		return '';
//	}
	return $value;
}//-----------------------------------------------------------------------------------------------------------------------------------------------------------------
function min3Digit($value){
	if($value<10){
		return '10'.$value;
	}elseif($value<100){
		return '1'.$value;
	}
	return $value;
}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------
// env2 is used to read contants from .env files in situations where env does not  work
function env2($key) {

	global $BASE_DIR_LARAVEL;
	if($key=='APP_ENV'){
		$input = file_get_contents($BASE_DIR_LARAVEL . '.env');
	}else{
		$input = file_get_contents($BASE_DIR_LARAVEL . '.env.'.env2('APP_ENV'));
	}


	$p1 = strpos($input, $key);
	$p2 = strpos($input, "=", $p1); // pos of start value
	$p3 = strpos($input, "\n", $p2); // pos of end of line
	$p3_2 = strpos($input, "#", $p2); // pos of comment

	// ignore comment
	if ($p3 > $p3_2 && $p3_2 != "") { // if has comment
		$p3 = $p3_2 - 1;
	}
	$text=substr($input, $p2 + 1, $p3 - $p2 - 1);
	$text=trim(str_replace("\r",'',$text));
	return $text ;
}

//-----------------------------------------------------------------------------------------------------------------------------------------------------------------
// gets the subdomain name : admin, api
function getSubDomain() {
	if(isset($_SERVER) && isset($_SERVER['SERVER_NAME'])){
		$serverName = explode('.', $_SERVER['SERVER_NAME']);
		if (sizeof($serverName) > 2) {
			return $serverName[0];
		}
	}
	return "";
}

//-----------------------------------------------------------------------------------------------------------------------------------------------------------------
// duh
function isAdminSubDomain() {
	if (getSubDomain() == PREFIX_ADMIN) {
		return true;
	}
	return false;
}

//-----------------------------------------------------------------------------------------------------------------------------------------------------------------
// duh
function isAPISubDomain() {
	if (getSubDomain() == PREFIX_API) {
		return true;
	}
	return false;
}

//-----------------------------------------------------------------------------------------------------------------------------------------------------------------

/**
 * generate a json object for responses
 *
 * @return bool
 */
function generateResponse($result,$data=array()) {

	$response = array();
	$response[RK_RESULT] = $result;

	$response=array_merge($response,$data);

	return json_encode($response);
}


// returns today date in persian (hejri)
function getToday($text = true){
    if($text)
	    return UC(getServerDate(),U_MILADI_TO_HEJRI_TEXT);

    return UC(getServerDate(),U_MILADI_TO_HEJRI);
}


// returns error response (mostly used in APIs)
function errBack($message, $status = null) {
	if ($status == null)
		return generateResponse(ERR_ERROR_MESSAGE, [RK_MESSAGE => $message]);

	return response(generateResponse(ERR_ERROR_MESSAGE, [RK_MESSAGE => $message]), $status);
}

// returns success response (mostly used in APIs)
function resSuccess($message=null) {
	if ($message == null)
		return generateResponse(RES_SUCCESS);

	return generateResponse(RES_SUCCESS, [RK_MESSAGE => $message]);
}


//-----------------------------------------------------------------------------------------------------------------------------------------------------------------

function result($response) {
	return extJson($response,RK_RESULT,'');
}

//-----------------------------------------------------------------------------------------------------------------------------------------------------------------

/*
 * extract from json
 */
function extJson($response, $key, $default = "") {
	$response = json_decode($response, true);
	if (isset($response[$key])) {
		return $response[$key];
	} else {
		return $default;
	}
}

//-----------------------------------------------------------------------------------------------------------------------------------------------------------------
// returns error response (mostly used in web)
function errorBack($message,$redirect=''){
	if($redirect != ''){
		return generateResponse(ERR_ERROR_MESSAGE,[RK_MESSAGE => $message, RK_REDIRECT => $redirect]);
	}
	return generateResponse(ERR_ERROR_MESSAGE,[RK_MESSAGE => $message]);
}

// returns success response (mostly used in web)
function sucBack($message,$redirect=''){
	if($redirect != ''){
		return generateResponse(RES_SUCCESS,[RK_MESSAGE => $message, RK_REDIRECT => $redirect]);
	}
	return generateResponse(RES_SUCCESS,[RK_MESSAGE => $message]);
}



// ---------------------------------------------------------------------------------------------------------------------------------------------------------------
// unlinks the previous file if it exists
function deleteFile($fileAddress, $path){
    $file = pathinfo($fileAddress);
    $filename = $file['basename'];


    if($filename != "" && $filename != "default.png" && file_exists($path.$filename))
        \unlink($path.$filename);

    return;
}




//-----------------------------------------------------------------------------------------------------------------------------------------------------------------
function isWebserviceSubdomain() {
    if (getSubdomain() == PREFIX_API) {
        return true;
    }
    return false;
}



//-----------------------------------------------------------------------------------------------------------------------------------------------------------------
function getUserIp() {
    return request() -> ip();
}



// ----------------------------------------------------------------------------------------------------------------------------------------------------------------
// gets the day of week number in iran (default starting number is 0)
function getIranDayOfWeek($date = null, $startingNumber = 0){
    if($date == null)
        $date = getServerDateTime();

    setTimezone();
    $dayOfWeekNumber = date('N',strtotime($date)); // monday is the first day of week

    switch($dayOfWeekNumber){
        case 1: // monday => 2 shanbe
            return $startingNumber + 2;
            break;

        case 2: // tuesday => 3 shanbe
            return $startingNumber + 3;
            break;

        case 3: // wednesday => 4 shanbe
            return $startingNumber + 4;
            break;

        case 4: // thursday => 5 shanbe
            return $startingNumber + 5;
            break;

        case 5: // friday => jome
            return $startingNumber + 6;
            break;

        case 6: // saturday => shanbe
            return $startingNumber;
            break;

        case 7: // sunday => 1 shanbe
            return $startingNumber + 1;
            break;
    }
}



// ----------------------------------------------------------------------------------------------------------------------------------------------------------------
// calculates the radial distance between two points of lat and long
function radialDistance($lat1,$long1, $lat2,$long2){
	$lat1=(float)$lat1;
	$long1=(float)$long1;
	$lat2=(float)$lat2;
	$long2=(float)$long2;

    return round(( 6371 * acos( cos( deg2rad($lat1) ) * cos( deg2rad( $lat2 ) ) * cos( deg2rad( $long2 ) - deg2rad($long1) ) + sin( deg2rad($lat1) ) * sin( deg2rad( $lat2 ) ) ) ) );
}



//-----------------------------------------------------------------------------------------------------------------------------------------------------------------
// this function adds a middleware to the input controller , this middleware checks for admin permissions ( authorizePermissions() function is in Admin.php  )
function checkPermissionMiddleWare($controller,$role,$excepts=[]){
    if(Auth::check()){
        $controller ->middleware(function ($request, $next) use($role) {
            auth()->user()->authorizePermissions($role);
            return $next($request);
        }) -> except($excepts);
    }

}

// -----------------------------------------------------------------------------------------------------------------------------------------------
function formatNumber($number,$decimalPoints=2){
    return number_format((float)$number, $decimalPoints, '.', '');
}


