<?php
use Yamw\Lib\Request;

hasToBeAdmin();
forward404Unless(Request::exists('id'));

Request::populateFromPost(array('name', 'img' => null, 'position', 'description'));

$this->result = new About(
    array(
        'id' => Request::get('id'),
        'Name' => Request::get('post-name'),
        'img' => Request::get('post-img'),
        'position' => Request::get('post-pos'),
        'description' => Request::get('post-description')
    )
);
$this->result = $this->obj->save('description');
