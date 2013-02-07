<?php
use Yamw\Lib\Request;

if($this->result) {
    print_c('Successfully updated '.Request::get('post-name'), COLOR_SUCCESS);
} else {
    print_c('There was an error at updating!', COLOR_ERROR);
    println();
    println();
    dump_var($this->result);
}