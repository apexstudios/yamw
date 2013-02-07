<?php
use Yamw\Lib\Request;

if($this->result) {
    print_c('Successfully updated staff member '.Request::get('post-name'), COLOR_SUCCESS);
} else {
    print_c('Error at updating staff member '.Request::get('post-name'), COLOR_ERROR);
}