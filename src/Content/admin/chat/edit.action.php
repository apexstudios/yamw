<?php
use Yamw\Lib\Request;
use Yamw\Models\Chat;

noTemplate();
hasToBeAdmin();
forward404Unless(Request::exists('id'));

$chat = Chat::retrieve(Request::get('id'));
forward404Unless($chat);
