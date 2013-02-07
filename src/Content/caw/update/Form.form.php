<?php
class UpdateForm extends iForm {
    public function __construct($form_id, $date='', $desc='', $text='', $row_id=NULL) {
        parent::__construct($form_id);
        
        $this->addInput('', 'notice', array('label' => 'PLEASE USE THE DATE FORMAT 1st January 1970!!! THE WHOLE SYSTEM RELIES ON IT!!!'));
        // $this->addInput('', 'br');
        $this->addInput('date', 'text', array('label' => 'Date', 'value' => $date));
        $this->addInput('', 'br');
        $this->addInput('desc', 'rtf', array('label' => 'Description', 'value' => $desc));
        $this->addInput('', 'br');
        $this->addInput('', 'br');
        $this->addInput('', 'br');
        $this->addInput('text', 'rtf', array('label' => 'Text', 'value' => $text, 'height' => '500'));
        
        if($row_id) {
            $this->addInput('row_id', 'hidden', array('value' => $row_id));
        }
    }
}