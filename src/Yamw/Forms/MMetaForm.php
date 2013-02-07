<?php
namespace Yamw\Forms;

class MMMetaForm extends iForm
{
    public function __construct($form_id, $name = '', $description = '', $title = '', $content = '', $row_id = null)
    {
        parent::__construct($form_id);
        
        $this->addInput('name', 'text', array('label' => 'Name', 'value' => $name));
        $this->addInput('title', 'text', array('label' => 'Title', 'value' => $title));
        $this->addInput('description', 'text', array('label' => 'Description', 'value' => $description));
        $this->addInput('', 'br');
        $this->addInput('content', 'rtf', array('label' => 'Content', 'value' => $content));
        
        if($row_id) {
            $this->addInput('row_id', 'hidden', array('value' => $row_id));
        }
    }
}