<?php
use Yamw\Lib\Request;

hasToBeAdmin();

Request::populateFromPost(array('name', 'desc', 'layer', 'aff'));

$this->result = new SpaceUnit(
    array(
        'name' => Request::get('post-name'),
        'description' => Request::get('post-desc'),
        'layer' => Request::get('post-layer'),
        'affiliation' => Request::get('post-aff'),
        'draft' => 0
    )
);
$this->result = $this->result->createEntry('description');
