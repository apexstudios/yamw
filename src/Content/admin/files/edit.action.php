<?php
use Yamw\Lib\Exceptions\HttpErrorException;
use Yamw\Lib\Request;

if(!Request::exists('id')){
    throw new HttpErrorException('Did not find the file', 404);
}
