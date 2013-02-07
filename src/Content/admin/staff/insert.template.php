<?php
use Yamw\Lib\Request;

if($this->result) {
    print_c('Successfully created an entry for staff member '.Request::get('post-name'), COLOR_SUCCESS);
} else {
    print_c('Error at creating the entry for staff member '.Request::get('post-name'), COLOR_ERROR);
}
