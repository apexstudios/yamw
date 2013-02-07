<?php
use Yamw\Lib\Request;
use Yamw\Models\Chat;

noTemplate();
hasToBeAdmin();

Request::populateFromPost(array('chat_id' => false));

forwardUnless(Request::get('post-chat_id'), 500);

// Because of security purposes we use POST, which is slower but less manipulateable by average user
$this->result = Chat::retrieve(Request::get('post-chat_id'))->remove();
