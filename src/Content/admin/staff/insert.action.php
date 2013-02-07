<?php
use Yamw\Lib\Request;

hasToBeAdmin();

Request::populateFromPost(array('name', 'img' => null, 'position', 'description'));

$this->result = new About(array('Name' => Request::get('post-name'),
    'img' => Request::get('post-img'),
    'position' => Request::get('post-pos'),
    'description' => Request::get('post-description')));
$this->result = $this->result->createEntry('description');
