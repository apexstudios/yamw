<?php
#$this->noTemplate();
hasToBeAdmin();
forward404Unless($Request->Id);

$this->obj = new Update(array('id' => $Request->Id, 'text' => $_POST['text'],
        'date' => $_POST['date'],
        'desc' => $_POST['desc']));
$this->result = $this->obj->save('text');