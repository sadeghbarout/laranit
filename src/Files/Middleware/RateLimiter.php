<?php

namespace App\Http\Middleware;


use Illuminate\Routing\Middleware\ThrottleRequests;

class RateLimiter extends ThrottleRequests {

	protected function resolveRequestSignature($request)
	{
//		$baseIdentifier=request()->getMethod() . '|' . request()->ajax() . '|' .request()->decodedPath() . '|' . getUserIp();
		$baseIdentifier=request()->getMethod() . '|' . request()->ajax() . '|' .request()->getPathInfo() . '|' . getUserIp();
//		dd($baseIdentifier);

		if ($user = request()->user()) {
			return sha1($user->getAuthIdentifier() . '|' . $baseIdentifier);
		}

		return sha1($baseIdentifier);
	}

}
