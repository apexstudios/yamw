<?php

// Now calculate additional metadata
function meta($k=NULL, $v='') {
    static $meta;
    if ($k === NULL) {
        return $meta;
    } else {
        if (!isset($meta)) {
            $meta = array('$set' => array());
        }
        $meta['$set']['metadata.'.$k] = $v;
    }
}
