<?php
namespace Yamw\Lib\Exceptions;

class HttpErrorException extends SuperException {
    private $error_code = 000;
    private $error_type = 'None';

    public function __construct($message, $code) {
        parent::__construct($message, $code);
        $this->error_code = $code;

        if(!empty($type))
            $this->error_type = $type;
    }

    public function getError() {

    }
}
