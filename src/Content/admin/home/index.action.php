<?php
use Yamw\Lib\UAM\UAM;

getLogger()->addNotice('Admin panel access!', array('uid' => UAM::getInstance()->getCurUserId()));
