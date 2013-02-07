<?php
if($this->result) {
    print_c('Successfully commented on the article, '.$this->UAM->getCurUserName(), COLOR_SUCCESS);
    println('<script type="text/javascript">setTimeout(function(){document.location.href="'.$_POST['previous_page'].'"}, 3000);</script>', false);
} else {
    print_c('Error at commenting on the article', COLOR_ERROR);
}