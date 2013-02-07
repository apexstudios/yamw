<?php
require_once __DIR__.'/../src/inc.php';
require_once __DIR__.'/../vendor/autoload.php';

require_once __DIR__.'/../src/Yamw/Lib/Constants.php';
require_once __DIR__.'/../src/Yamw/Lib/Settings.php';

require_once __DIR__.'/BaseTestCase.php';
require_once __DIR__.'/SeleniumHelpers.php';

ob_start();
Yamw\Lib\Core::getInstance()->register();
ob_end_clean();

useHelper('Template');
useHelper('Forward');
useHelper('Security');
useHelper('BBCode');

PHPUnit_Extensions_SeleniumTestCase::shareSession(true);
