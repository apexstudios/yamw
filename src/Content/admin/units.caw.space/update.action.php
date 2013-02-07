<?php
#$this->noTemplate();
hasToBeAdmin();
forward404Unless($Request->Id);

Request::populateFromPost(array('name', 'desc', 'layer', 'aff'));

$this->result = new SpaceUnit(
    array(
        'name' => Request::get('post-name'),
        'description' => Request::get('post-desc'),
        'layer' => Request::get('post-layer'),
        'affiliation' => Request::get('post-aff')
    )
);
$this->result = $this->obj->save('description');
