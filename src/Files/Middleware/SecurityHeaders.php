<?php

namespace App\Http\Middleware;


class SecurityHeaders
{
	public function handle($request, \Closure $next) {

		// not send X-Frame-Options and Content-Security-Policy for iframe routes
		$frameRoutes =[
			'/^\/map(\/.+)*$/'
		];

		header("Server: "); //HTTP 1.0
		header("X-XSS-Protection: 1; mode=block");

		header("X-Content-Type-Options: nosniff");
		header("Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0");//Dont cache
		header("Pragma: no-cache");//Dont cache
		header("Expires: Thu, 19 Nov 1981 08:52:00 GMT");//Make sure it expired in the past (this can be overkill)

		$useFrame=false;
		foreach ($frameRoutes AS $r){
			if(preg_match($r,request()->getPathInfo()))
				$useFrame=true;

		}

		if(!$useFrame){
			header("X-Frame-Options: SAMEORIGIN"); //HTTP 1.0
			header("Content-Security-Policy: frame-ancestors 'self'"); //HTTP 1.0
		}

		return $next($request);
	}

}
