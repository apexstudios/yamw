<?php
if($this->result) {
    print_c('Successfully inserted', COLOR_SUCCESS);
} else {
    println('Error at inserting!', COLOR_ERROR);
}