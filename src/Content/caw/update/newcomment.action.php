<?php
$this->noTemplate();

$this->result = UpdateComment::createNew('updates_comments', array('text' => $_POST['uc_text'], 'title' => $_POST['uc_title'],
    'author' => $this->UAM->getCurUserId(), 'uid' => (int)$_POST['update_id']), 'text');