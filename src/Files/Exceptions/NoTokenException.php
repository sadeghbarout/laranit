<?php

namespace App\Exceptions;

use App\Extras\StatusCodes;

class NoTokenException extends \Exception {

    public function __construct() {
        parent::__construct();
    }

    public function render() {
        return response(generateResponse(ERR_NO_TOKEN), StatusCodes::HTTP_UNAUTHORIZED);
    }
}
