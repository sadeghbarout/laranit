<?php

use App\Exceptions\ErrorMessageException;
use App\Extras\StatusCodes;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

$app = Application::configure(basePath: dirname(__DIR__))
	->withRouting(
		commands: __DIR__ . '/../routes/console.php',
		health: '/up',
		then: function () {
			Route::middleware('api')
				->group(base_path('routes/basic.php'));

			Route::middleware('api')
				->group(base_path('routes/test.php'));

			Route::middleware('api')
				->prefix('api')
				->group(base_path('routes/api.php'));

			Route::middleware('web')
				->group(base_path('routes/web.php'));
		}
	)
	->withMiddleware(function (Middleware $middleware) {
		$middleware->prepend([
			\App\Http\Middleware\SetRealIp::class,
			\App\Http\Middleware\SecurityHeaders::class,
			\App\Http\Middleware\LogAfterRequest::class,
			\App\Http\Middleware\CleanStrings::class,
			//      \App\Http\Middleware\ForceHttps::class,
		]);

		$middleware->alias([
			"rateLimiter" => \App\Http\Middleware\RateLimiter::class,
		]);
	})
	->withExceptions(function (Exceptions $exceptions) {
		$exceptions->renderable(function (\Throwable $e, $request) {
			if(!app()->hasDebugModeEnabled()){
				throw new ErrorMessageException('server error.', StatusCodes::HTTP_INTERNAL_SERVER_ERROR);
			}
		});
	})->create();

$app->loadEnvironmentFrom(".env." . trim(file_get_contents(__DIR__ . "/../.env")));
return $app;
