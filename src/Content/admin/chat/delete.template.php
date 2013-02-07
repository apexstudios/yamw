<?php
if($this->result) {
    print_c('Row successfully deleted', COLOR_SUCCESS);
} else {
    print_c('Error at deleting!', COLOR_ERROR);
}