<?php
namespace Yamw\Lib\Exceptions;

abstract class SuperException extends \Exception {
    public function getError() {
        dump_var($this);

        echo '<p>Error-Type: '. $this->message .'<br />
        File: '. $this->file .':'. $this->line .' calling '.$this->trace[0]['class'].$this->trace[0]['type'].$this->trace[0]['function'].'</p>';
    }
}
