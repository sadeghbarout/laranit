<?php

namespace App\Exceptions;

use App\Extras\StatusCodes;

class PermissionException extends \Exception {

    public function __construct() {
        parent::__construct();
    }

    public function render() {
        return response(generateResponse(ERR_ERROR_MESSAGE, [RK_MESSAGE => "شما به این بخش دسترسی ندارید!"]), StatusCodes::HTTP_FORBIDDEN);
    }
}
