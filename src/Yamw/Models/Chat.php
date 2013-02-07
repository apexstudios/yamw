<?php
namespace Yamw\Models;

use \Yamw\Lib\Modelizer\Modelizer_Base;
use \Yamw\Lib\Interfaces\ModelizerInterface;
use \Yamw\Lib\MySql\AdvMySql;
use \Yamw\Lib\Property;
use \Yamw\Lib\UAM\UAM;

class Chat extends Modelizer_Base implements ModelizerInterface
{
    protected static $table = 'chat';
    protected static $array_map = array(
        'Id' => 'id',
        'Uid' => 'uid',
        'Name' => 'name',
        'Text' => 'cached_text',
        'Time' => 'time',
        'RawText' => 'text'
    );

    public function __construct(array $_data)
    {
        $_data['uid'] = $_data['name'];

        // Resolve Uid to username
        $_data['name'] = UAM::getInstance()->Users()->getUserNameById($_data['name']);

        parent::__construct($_data);

        // Time
        $this->Time  = getTimeLabel($this->Time);

        // If required, cache the text
        if (!$this->Text) {
            $this->Text = BBCode2HTML($this->RawText);
        }
    }
}
