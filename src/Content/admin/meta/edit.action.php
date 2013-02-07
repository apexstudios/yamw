<?php
use Yamw\Lib\Request;
use Yamw\Lib\MySql\AdvMySql;
use Yamw\Forms\MMetaForm;

noTemplate();
forward404Unless(Request::exists('id'));

$this->meta = AdvMySql::getTable('meta')->setModel('mMeta')->where('id', Request::get('id'))->limit(1)->execute();
forward404Unless($this->meta);

foreach($this->meta as $obj) {
    $this->form = new MMetaForm('EditForm', $obj->getName(), $obj->getDescription(), $obj->getTitle(), $obj->getRawContent());
    $this->form->setURL('admin/meta/update', $obj->getId());
}
