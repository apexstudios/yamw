<?php
use Yamw\Forms\IForm;

class StaffForm extends IForm {
    public function __construct($form_id, $name='', $pos='', $img='', $desc='', $row_id=NULL) {
        parent::__construct($form_id);

        $this->addInput('name', 'text', array('label' => 'Name', 'value' => $name));
        $this->addInput('pos', 'text', array('label' => 'Position', 'value' => $pos));
        $this->addInput('img', 'text', array('label' => 'Path to image file', 'value' => $img));
        $this->addInput('', 'br');
        $this->addInput('desc', 'rtf', array('label' => 'Description', 'value' => $desc));

        if($row_id) {
            $this->addInput('row_id', 'hidden', array('value' => $row_id));
        }
    }
}
