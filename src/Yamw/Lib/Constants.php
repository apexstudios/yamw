<?php
namespace Yamw\Lib;

// Put all pre-defined constants here
global $true;
$true = array('1', 'true', 'yes', 'on');

define('DEBUG', checkForTrue(Config::get('debug')));

// Current YAMW Version
define('VERSION', 'Yamw v4.8');

// Current OS
define('CURRENT_OS', php_uname('s'));


// The directory separator for the current OS
if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}

// The root path of the application
define('BASEPATH', dirname(__FILE__).DS);

// define('DEBUG', checkForTrue($Config->debug));
define('SHOW_DBGMSG', true);

// Cache modus [ON] OFF
define('USE_CACHE', checkForTrue(Config::get('cache')));

// MySql
define('MYSQL_HOST',    Config::get('mysql.host'));
define('MYSQL_USER',    Config::get('mysql.username'));
define('MYSQL_PW',        Config::get('mysql.password'));
define('MYSQL_DB',        Config::get('mysql.dbname'));
define('MYSQL_PREF',    Config::get('mysql.table_prefix'));

define('DB_TB_DELIMITER', '___');

// Colors
define('COLOR_ERROR',    'AA1212');
define('COLOR_SUCCESS',    '228822');
define('COLOR_INFO',    '224499');
define('COLOR_YELLOW',    'ffff88');
define('COLOR_PINK',    'ff88ff');
define('COLOR_TURKIS',    '88ffff');
define('COLOR_TURQUOIS',    COLOR_TURKIS); // Alias
define('COLOR_WHITE',    'ffffff');
define('COLOR_BLACK',   '000000');

// User System
define('USER_COOKIE', 'mybb_sid');

// Time/Date
define('DATE_ANH_NHAN', 'D, d M Y H:i T');

// Rich Text Editing
define('CURRENT_RTE', 'TinyMCE');
define('RTF_CURRENT', CURRENT_RTE);
define('RTF_STD_HEIGHT', 220);
define('RTF_STD_WIDTH', '17em');

// Thumbnail Settings
define('TN_SMALL_WIDTH', 150);
define('TN_SMALL_HEIGHT', 92);
define('TN_DEFAULT_WIDTH', 300);
define('TN_DEFAULT_HEIGHT', 185);
define('TN_BIG_WIDTH', 900);
define('TN_BIG_HEIGHT', 556);

// MYSQL
define('id', 'id');
define('DESC', 'DESC');
define('ASC', 'ASC');

// MAGIC_QUOTES
define('MQ', get_magic_quotes_gpc());
