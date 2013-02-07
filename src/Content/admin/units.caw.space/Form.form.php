<?php
use Yamw\Forms\IForm;

class UnitForm extends IForm {
    public function __construct($form_id, $name='', $aff='', $lay='', $desc='', $row_id=NULL) {
        parent::__construct($form_id);

        $this->addInput('name', 'text', array('label' => 'Name', 'value' => $name));

        $this->addInput('', 'br');
        $this->addInput('', 'br');

        $this->addInput('aff', 'dropdown', array('label' => 'Affiliation', 'selected' => $aff));
        $this->addOption('aff', 'aff_cov', 'Covenant', 1);
        $this->addOption('aff', 'aff_unsc', 'UNSC', 2);
        $this->addOption('aff', 'aff_flood', 'Flood', 3);
        $this->addOption('aff', 'aff_forerunner', 'Foreruners', 4);
        $this->addOption('aff', 'aff_urf', 'United Rebel Front', 5);
        $this->addOption('aff', 'aff_other', 'Other', 6);

        $this->addInput('', 'br');
        $this->addInput('', 'br');

        $this->addInput('layer', 'dropdown', array('label' => 'Classification', 'selected' => $lay));
        $this->addOption('layer', 'layer_bomber', 'Fighter', 0);
        $this->addOption('layer', 'layer_bomber', 'Bomber', 1);
        $this->addOption('layer', 'layer_bomber', 'Corvette', 2);
        $this->addOption('layer', 'layer_bomber', 'Frigate', 3);
        $this->addOption('layer', 'layer_bomber', 'Destroyer', 4);
        $this->addOption('layer', 'layer_bomber', 'Cruiser', 5);
        $this->addOption('layer', 'layer_bomber', 'Heavy Cruiser', 6);
        $this->addOption('layer', 'layer_bomber', 'Carrier', 7);

        $this->addInput('', 'br');
        $this->addInput('', 'br');

        $this->addInput('desc', 'rtf', array('label' => 'Description', 'value' => $desc, 'height' => '500px'));

        if($row_id) {
            $this->addInput('row_id', 'hidden', array('value' => $row_id));
        }
    }
}
