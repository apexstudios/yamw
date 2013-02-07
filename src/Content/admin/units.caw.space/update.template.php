<?php
if($this->result) {
    print_c('Victory! Unit '.Request::get('post-name').' been successully updated!', COLOR_SUCCESS);
} else {
    print_c('Error at updating!', COLOR_ERROR);
}