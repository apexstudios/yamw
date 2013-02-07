<?php
if($this->result) {
    print_c('Successfully updated the chat entry by'.$author, COLOR_SUCCESS);
} else {
    print_c('There was a error at updating!', COLOR_ERROR);
}
