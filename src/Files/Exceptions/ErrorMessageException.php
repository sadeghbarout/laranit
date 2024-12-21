<?php

namespace App\Exceptions;

class ErrorMessageException extends \Exception {

    public function __construct(
        public $message,
        public $status = 200
    ) {
        parent::__construct($message, $status);
    }

    public function render() {
        return response(generateResponse(ERR_ERROR_MESSAGE, [RK_MESSAGE => $this->getMessage()]), $this->status);
    }
}
