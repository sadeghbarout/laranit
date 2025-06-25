<?php

namespace App\Http\Middleware;


class CleanStrings
{
	public function handle($request, \Closure $next) {
		$inputs = $request->all();

		foreach ($inputs AS &$input){
//			$input = str_replace("<", "&lt;", $input);
//			$input = str_replace(">", "&gt;", $input);

			try {
				$input = str_replace(array("ي", "ك"), array("ی", "ک"), $input);
				$input = str_replace(array("۰", "۱", "۲", "۳", "۴", "۵", "۶", "۷", "۸", "۹"), array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9"), $input);
			}catch (\Throwable $e){}
		}
		$request->replace($inputs);

		return $next($request);
	}

}
