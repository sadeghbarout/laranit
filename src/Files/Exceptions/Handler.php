<?php

namespace App\Exceptions;

use App\Extras\StatusCodes;
use App\Extras\Tools;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        //
    }

//
    public function render($request, Throwable $exception) {

		// handle special Exceptions

		if($exception instanceof ValidationException){
			if($request->get(P_OS)!='PWA' &&  Tools::detectAgent() == 'web'){
				return response(generateResponse(ERR_ERROR_MESSAGE,[RK_MESSAGE=> $exception->getMessage()]));
			}else{
				return response(generateResponse(ERR_ERROR_MESSAGE,[RK_MESSAGE=> $exception->getMessage()]), StatusCodes::HTTP_BAD_REQUEST);
			}

		}elseif($exception instanceof ErrorMessageException){
			$statusCode=200;
			if($exception->getCode()!=0)
				$statusCode=$exception->getCode();

			return response(generateResponse(ERR_ERROR_MESSAGE,[RK_MESSAGE=> $exception->getMessage()]),$statusCode);

		}elseif($exception instanceof MaintenanceException){
			return response(generateResponse(ERR_UNDER_MAINTENANCE),StatusCodes::HTTP_SERVICE_UNAVAILABLE);

		}elseif($exception instanceof NoTokenException){
			return response(generateResponse(ERR_NO_TOKEN),StatusCodes::HTTP_UNAUTHORIZED);

		}elseif($exception instanceof PermissionException){
			return response(generateResponse(ERR_ERROR_MESSAGE, [RK_MESSAGE => "شما به این بخش دسترسی ندارید!"]));
		}

		return parent::render($request, $exception);
	}

}
