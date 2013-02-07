<?php
if($this->result) {
    print_c('Successfully updated the update of the '.$_POST['date'], COLOR_SUCCESS);
} else {
    print_c('There was an error at updating!', COLOR_ERROR);
}