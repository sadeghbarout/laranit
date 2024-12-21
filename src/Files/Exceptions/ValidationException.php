<?php

namespace App\Exceptions;

use App\Extras\StatusCodes;

class ValidationException extends \Exception {

    public function __construct(
        public $message,
    ) {
        parent::__construct($message);
    }

    public function render() {
        return response(generateResponse(ERR_ERROR_MESSAGE,[RK_MESSAGE=> $this->getMessage()]), StatusCodes::HTTP_BAD_REQUEST);
    }
}
