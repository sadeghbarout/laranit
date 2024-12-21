<?php

namespace App\Exceptions;

use App\Extras\StatusCodes;

class MaintenanceException extends \Exception {

    public function __construct() {
        parent::__construct();
    }

    public function render() {
        return response(generateResponse(ERR_UNDER_MAINTENANCE), StatusCodes::HTTP_SERVICE_UNAVAILABLE);
    }
}
