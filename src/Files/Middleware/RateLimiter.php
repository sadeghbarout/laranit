<?php

namespace App\Http\Middleware;


use Illuminate\Routing\Middleware\ThrottleRequests;

class RateLimiter extends ThrottleRequests {

	protected function resolveRequestSignature($request)
	{
//		$baseIdentifier=request()->getMethod() . '|' . request()->ajax() . '|' .request()->decodedPath() . '|' . request()->ip();
		$baseIdentifier=request()->getMethod() . '|' . request()->ajax() . '|' .request()->getPathInfo() . '|' . request()->ip();
//		dd($baseIdentifier);

		if ($user = request()->user()) {
			return sha1($user->getAuthIdentifier() . '|' . $baseIdentifier);
		}

		return sha1($baseIdentifier);
	}

}
