<?php
namespace Yamw\Lib\Exceptions;

class MySqlException extends SuperException {
    private $query;

    public function __construct($a, $query) {
        parent::__construct($a);
        $this->query = trim($query);
        $this->error_msg = trim($a);
    }

    public function getError() {
        if($this->error_msg) {
            echo parent::getError();
            echo 'MySql-Error: <pre color="ff0000">'.$this->error_msg.'</pre><br />
            Query: <pre color="3366ff">'.$this->query.'</pre></p>';
        } else {
            global $Processer;
            $Processer->f404();
        }
    }
}
