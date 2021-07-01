<?php

namespace App\Http\Middleware;


use App\Extras\StatusCodes;
use Closure;
use Illuminate\Support\Facades\App;

class ForceHttps {

	public function handle($request, Closure $next)
	{
        // redirect all request to https
		if (!$request->secure() &&( config('app.env') === 'production' || config('app.env') === 'twise')) { //&& App::environment() === 'production'
			return redirect()->secure($request->getRequestUri(),307,$request->headers->all());
		}

		return $next($request);
	}
}
