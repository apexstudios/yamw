<?php
use Yamw\Lib\Request;
use Yamw\Models\MMeta;

hasToBeAdmin();
forward404Unless(Request::exists('id'));

Request::populateFromPost(array('name', 'title', 'description', 'content'));

$this->obj = new MMeta(
    array(
        'id' => Request::get('id'),
        'name' => Request::get('post-name'),
        'title' => Request::get('post-title'),
        'description' => Request::get('post-description'),
        'content' => Request::get('post-contet')
    )
);
$this->result = $this->obj->BBCodeCache('content');