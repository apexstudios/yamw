<?php
use Yamw\Lib\Request;

use Yamw\Models\Chat;

noTemplate();
forward404Unless(Request::exists('id'));

Request::populateFromPost(array('text', 'author'));

$text = Request::get('post-text');
$author = Request::get('author');

if(!($text || $author)) {
    $this->f500('index', 'Missing parameters');
}

$this->obj = new Chat(array('id' => $Request->Id, 'text' => $text, 'name' => $author));
$this->result = $this->obj->save('text');
