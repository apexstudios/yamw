<?php
if($this->result) {
    print_c('Unit '.Request::get('post-name').' has been successfully inserted!', COLOR_SUCCESS);
} else {
    print_c('Error at inserting!', COLOR_ERROR);
}