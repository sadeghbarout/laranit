<?php
/**
 * Created by PhpStorm.
 * User: sadegh
 * Date: 12/10/2020
 * Time: 02:26 PM
 */

namespace App\Http\Middleware;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
class LogAfterRequest {

	public function handle($request, \Closure $next) {
		return $next($request);
	}


    // this function stores requests (both incoming and response) in storage/logs/dailylogs
    // day by day
	public function terminate($request, $response) {
		$url =$request->method().'  '. $request->fullUrl();
		$ip = $request->ip();
		$reqTime=getServerTime();

		$pathPrefix="-admin";
		if(isWebserviceSubdomain()){
			$pathPrefix="-api";
			$token = json_encode($request->header(H_AUTHORIZATION));
		}else{
			$token=\Auth::user()!=null?\Auth::id():null;
		}

		$request = json_encode($request->all());

		$time=microtime(true)-LARAVEL_START;
//		(new Response())->
		$date=getServerDate();
		$hour=explode(":",getServerTime())[0];
		$res=$response->getContent();
		if(strpos($res,'Stack trace:')!=false || strpos($res,'ErrorException:')!=false ){
			$res=substr($res,0,2000);
		}
		$data="[$reqTime] \t  ip: $ip \t duration: $time \t url:$url,\n request: $request ,token: $token,\n response: ".$res."\n\n";

		$folder=storage_path("logs/dailylogs/$date");
		$path=$folder."/$hour".$pathPrefix.".log";
		if(file_exists($path)){
			file_put_contents($path,$data,FILE_APPEND|LOCK_EX);
		}else{
			try{
				mkdir($folder,0777,true);
			}catch (\Exception $e){

			}
			file_put_contents($path,$data);
		}
	}
}
