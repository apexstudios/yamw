<?php
#$this->noTemplate();
hasToBeAdmin();

$this->result = new Update(array('text' => $_POST['text'], 'date' => $_POST['date'], 'desc' => $_POST['desc']));
$this->result = $this->result->createEntry('text');