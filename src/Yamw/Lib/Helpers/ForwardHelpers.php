<?php
function forward404()
{
    global $Processer;
    $Processer->f404();
}

function forward($module = '404', $action = 'index', $section = 'error')
{
    global $Processer;
    $Processer->forward($module, $action, $section);
}

function forwardUnless($cond, $mod = '404', $act = 'index')
{
    if(!$cond) {
        forward($mod, $act);
    }
}

function forward404Unless($cond)
{
    if(!$cond) {
        forward404();
    }
}
