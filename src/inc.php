<?php
/**
 * $HeadURL$
 *
 * @author  Anh Nhan <anhnhan@outlook.com>
 * @version SVN: $Revision$
 */

use Monolog\Logger;
use Monolog\Handler\MongoDBHandler;
use Monolog\Handler\FirePHPHandler;
use Monolog\Handler\ChromePHPHandler;
use Monolog\Processor\MemoryUsageProcessor;
use Monolog\Processor\WebProcessor;
use Monolog\Processor\IntrospectionProcessor;
use Yamw\Lib\Config;
use Yamw\Lib\MySql\AdvMySql_Conn;
use Yamw\Lib\Mongo\AdvMongo;

function getLogger($name = 'def', $col = 'yamw_monolog', $level = null)
{
    static $logger, $handler;

    if ($level === null) {
        $level = Logger::NOTICE;
    }

    if (!is_array($logger)) {
        $logger = array();
    }

    if (!isset($logger[$name])) {
        $logger[$name] = new Logger($name);
        if (!isset($handler)) {
            $handler = new MongoDBHandler(AdvMongo::getConn(false), Config::get('mongo.dbname'), 'yamw_monolog', $level, true);
        }
        $logger[$name]->pushHandler($handler);
        if (@DEBUG) {
            $logger[$name]->pushHandler(new FirePHPHandler());
            $logger[$name]->pushHandler(new ChromePHPHandler());
        }
        $logger[$name]->pushProcessor(new MemoryUsageProcessor(true));
        $logger[$name]->pushProcessor(new WebProcessor($_SERVER));
        $logger[$name]->pushProcessor(new IntrospectionProcessor(true));
    }

    return $logger[$name];
}

function slugify($text)
{
    // replace all non letters or digits by -
    $text = preg_replace('/\W+/', '-', $text);
    // trim and lowercase
    $text = strtolower(trim($text, '- '));
    return $text;
}

function functionify($text)
{
    $text = trim(preg_replace('/\W+/', ' ', $text), ' ');
    return preg_replace('/\s+/', '', ucwords($text));
}

/**
 * Whether the current process is a CLI interface or not
 *
 * @return boolean
 */
function isCli()
{
    return php_sapi_name() == 'cli';
}

/**
 * Returns the url without the base domain
 *
 * @return string
 */
function getPage()
{
    static $actionstring;
    if (!isset($actionstring)) {
        global $Processer;
        $actionstring = $Processer->getActionString();
    }
    return $actionstring;
}

/**
 * Deletes a Cookie
 *
 * @param string $name The name of the cookie to be deleted
 */
function deleteCookie($name)
{
    setcookie($name, '', time()-3600, '/');
}

/**
 * Sets a cookie
 *
 * @param string $name <p>The name of the cookie</p>
 * @param string $val  <p>The value the cookie will be set to</p>
 */
function cookie($name, $val = '')
{
    setcookie(
        $name,
        $val,
        time()+60*60*24*7,
        Config::get('cookie.path'),
        (Config::get('cookie.domain')) ? Config::get('cookie.domain') :
        (($_SERVER['HTTP_HOST']=='localhost') ? '' : "{$_SERVER['HTTP_HOST']}")
    );
}

/**
 * Creates a Javascript redirect to the specified $location after a certain $time has passed
 *
 * @param string $location
 * @param string $time
 */
function redirectAJAX($location = null, $time = 3000)
{
    if (!isset($location)) {
        $location = getAbsPath();
    } else {
        $location = preg_replace('/^\/(.*?)$/si', '$1', $location);
    }

    $string = '<script type="text/javascript">';
    $string .= 'setTimeout(function(){document.location.href="';
    $string .= $location;
    $string .= '"}, ';
    $string .= $time;
    $string .= ');</script>';

    println($string, false);
}

/**
 * This function returns the time taken for generating this page in seconds.
 * Sorry for the misleading name...
 *
 * @return float
 */
function getTime()
{
    global $pagegentime;
    if (!$pagegentime) {
        global $start_time;
        $end_time = microtime(true);
        $pagegentime = $end_time - $start_time;
    }
    return $pagegentime;
}

/**
 * Returns the peak memory usage during the generation of this page
 *
 * @return string
 */
function getMemoryUsage()
{
    global $peakmem;
    if (!$peakmem) {
        $peakmem = memory_get_peak_usage()/1024/1024;
    }
    return $peakmem;
}

/**
 * Generates the path pointing to the specified resource
 *
 * @param string $val The path from the website root to the resource
 * @param bool $base If true, it will generate an absolute path, otherwise it will generate a relative one
 *
 * @return string <p>The path to the resource</p>
 */
function path($val = '', $base = true)
{
    if ($base) {
        return str_replace(
            array('//', '/', '\\', '\\\\'),
            array(DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR),
            dirname(__FILE__).DIRECTORY_SEPARATOR.$val
        );
    } else {
        return str_replace(
            array('//', '/', '\\', '\\\\'),
            array(DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR),
            $val
        );
    }
}

/**
 * Checks a string whether it contains a value that can be interpreted as the boolean <i>true</i>
 *
 * @param string $str The string to be interpreted
 *
 * @return boolean
 */
function checkForTrue($str)
{
    global $true;
    if (in_array(mb_strtolower($str), $true)) {
        return true;
    } else {
        return false;
    }
}

/**
 * Prints a single line (line break at the end)
 *
 * @param string $string The line to be print
 * @param bool   $html   Whether to use HTML line breaks (will still output text line breaks)
 */
function println($string = '', $html = true)
{
    if ($html) {
        print($string.(isCli() ? PHP_EOL : "<br />\n"));
    } else {
        print($string."\n");
    }
}

function escape($string, $html = false)
{
    if ($html) {
        return htmlspecialchars($string);
    } else {
        return AdvMySql_Conn::getConn()->real_escape_string($string);
    }
}

function sescape($string)
{
    return escape(escape($string, true));
}

/**
 * Validates an e-mail address
 *
 * @param unknown_type $email
 *
 * @return boolean Whether the given e-mail address is valid
 */
function validateEMail($email)
{
    $email = htmlspecialchars($email);

    if (
        !preg_match(
            '/^([A-Za-z0-9\.\!\#\$\%\&\'\*\+\-\/\=\?\^\_\`\{\|\}\~]){1,64}'.
            '\@{1}([A-Za-z0-9\.\!\#\$\%\&\'\*\+\-\/\=\?\^\_\`\{\|\}\~]){1,248}'.
            '\.{1}([a-z]){2,6}$/',
            $email
        )
        ) {
        return false;
    }

    list($localPart, $domainPart) = explode('@', $email, 2);

    if (@!fsockopen($domainPart, 80)) {
        return false;
    }

    return true;
}

function cache($time = 1)
{
    if (USE_CACHE) {
        $time = 60*60*24*$time;
        header('Pragma: public');
        header('Cache-control: max-age='.$time);
        header('Expires: '.date(DATE_ANH_NHAN, time() + $time));
    }
}

function nocache()
{
    header('Pragma: no-cache');
    header('Cache-control: no-cache');
    header('Expires: -1');
}

function lastModified($time, $etag, $length = 720)
{
    //get the HTTP_IF_MODIFIED_SINCE header if set
    $ifModifiedSince=(isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) ? $_SERVER['HTTP_IF_MODIFIED_SINCE'] : false);

    //get the HTTP_IF_NONE_MATCH header if set
    $etagHeader=(isset($_SERVER['HTTP_IF_NONE_MATCH']) ? trim($_SERVER['HTTP_IF_NONE_MATCH']) : false);

    header('Expires: '.date("D, d M Y H:i:s T", time() + 60*60*24*$length));

    //set last-modified header
    header("Last-Modified: ".gmdate("D, d M Y H:i:s T", $time));

    //set etag-header
    header("ETag: \"$etag\"");

    //make sure caching is turned on
    // header('Cache-Control: private');
    header('Pragma: public');

    //check if page has changed If not send 304 header and exit
    if (
        $etagHeader == $etag
        || ($ifModifiedSince
        && @strtotime($ifModifiedSince) < $time)
    ) {
        header("HTTP/1.1 304 Not Modified");
        exit;
    }
}

function contenttype($val = 'text/html', $char = 'UTF-8')
{
    @header('Content-type: '.$val.($char ? '; charset='.$char : ''));
}

/**
 * Encodes a.k.a. reverses an e-mail address
 *
 * @param string $val The e-mail address to be reversed
 *
 * @return string
 */
function encodeMail($val)
{
    return strrev($val);
}

function encodeFilename($name, $dir = false)
{
    if ($dir) {
        $except = array('\\', ':', '*', '?', '"', '<', '>', '|', '.');
        return str_replace($except, '_', $name);
    } else {
        $except = array('\\', '/', ':', '*', '?', '"', '<', '>', '|', '.');
        $ret = explode('.', $name);
        $ext = $ret[substr_count($name, '.')];

        $name = str_replace('.'.$ext, '', $name);
        return str_replace($except, '_', $name).'.'.$ext;
    }
}

/**
 * Converts _name_ into a thumbnail path, optionally containing _extra_.
 *
 * @param string $name
 *         Path that is being converted into a thumbnail path
 * @param string $extra
 *         An extra that is being added to the file name
 *         of the thumbnail for further differentiation.
 * @param string $subfolder
 *         The subfolder where the thumbnail should reside in.
 */
function getThumbPath($name, $extra = '', $subfolder = 'thumbs')
{
    $except = array('\\', ':', '*', '?', '"', '<', '>', '|', '.', ' ');
    $ret = explode('.', $name);
    $ext = $ret[substr_count($name, '.')];

    if ($extra) {
        $extra = $extra.'.';
    }

    $name = str_replace('.'.$ext, '', $name);

    if ($subfolder) {
        $subfolder .= '/';
    }

    $name = explode('/', $name);
    $name[count($name)-1] = $subfolder.$name[count($name)-1];
    $name = implode('/', $name);

    return str_replace($except, '_', $name).'.thumb.'.$extra.$ext;
}

/**
 * Includes a Helper if found
 *
 * @param string $name The name of the Helper
 *
 * @return boolean
 */
function useHelper($name)
{
    $aname = dirname(__FILE__)."/Yamw/Lib/Helpers/{$name}Helpers.php";
    return (@include_once $aname) ? true : false;
}

function getScheme()
{
    global $scheme;
    if (!isset($scheme)) {
        $scheme = new SimpleXMLElement(file_get_contents(dirname(__FILE__).'/config/scheme.xml'));
    }
}

/**
 * Resolves the Model to be used for a specific table
 *
 * @param string $name The name of a table for which the Model should be resolved
 */
function resolveTable2Model($name)
{
    getScheme();
    global $scheme;

    foreach ($scheme->map as $map) {
        if ((string)$map['table_name'] == $name) {
            return (string)$map['model_name'];
        }
    }
    // If not in table, return None;
    return 'None';
}

function resolveTable2Column($table)
{
    getScheme();
    global $scheme;

    $return = array();
    foreach ($scheme->map as $map) {
        if (is_string($table)) {
            if ((string)$map['table_name'] == $table) {
                foreach ($map->column as $value) {
                    $return[] = trim($value['name']);
                }
            }
        } elseif (is_array($table)) {
            foreach ($table as $t) {
                if ((string)$map['table_name'] == $t) {
                    foreach ($map->column as $value) {
                        $return[] = $map['table_name'].'.'.trim($value['name']);
                    }
                }
            }
        }
    }
    return $return;
}

function tableIsInScheme($name)
{
    getScheme();
    global $scheme;

    foreach ($scheme->map as $map) {
        if ((string)$map['table_name'] == $name) {
            return true;
        }
    }
    return false;
}

function columnName($table, $column)
{
    return $table.DB_TB_DELIMITER.$column;
}

/**
 * Dumps a variable in a HTML-friendly manner
 *
 * @param unknown_type $var The variable to be dumped
 */
function dump_var($var)
{
    echo '<pre>';
    var_dump($var);
    echo '</pre>';
}

/**
 * Returns the month as an int
 *
 * @param string $name The name of the month
 *
 * @return int
 */
function month2Int($name)
{
    static $months;

    if (!isset($months)) {
        $months = array();
        $months[] = array(1 => 'January');
        $months[] = array(1 => 'Jan');
        $months[] = array(2 => 'February');
        $months[] = array(2 => 'Feb');
        $months[] = array(3 => 'March');
        $months[] = array(3 => 'Mar');
        $months[] = array(4 => 'April');
        $months[] = array(4 => 'Apr');
        $months[] = array(5 => 'May');
        $months[] = array(6 => 'June');
        $months[] = array(6 => 'Jun');
        $months[] = array(7 => 'July');
        $months[] = array(7 => 'Jul');
        $months[] = array(8 => 'August');
        $months[] = array(8 => 'Aug');
        $months[] = array(9 => 'September');
        $months[] = array(9 => 'Sep');
        $months[] = array(10 => 'October');
        $months[] = array(10 => 'Oct');
        $months[] = array(11 => 'November');
        $months[] = array(11 => 'Nov');
        $months[] = array(12 => 'December');
        $months[] = array(12 => 'Dec');
    }

    foreach ($months as $month) {
        $ret = array_search($name, $month);
        if ($ret) {
            return $ret;
        }
    }
}

function arrayReversed($array)
{
    $array = array_reverse($array);
    ksort($array);
    return $array;
}

function resolveContentType($ext)
{
    $mime = array(
        // Image
        'png' => 'image/png',
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'gif' => 'image/gif',

        // Video
        'mov' => 'video/quicktime',
        'qt' => 'video/quicktime',
        'avi' => 'video/avi',
        'mpg' => 'video/mpeg',
        'flv' => 'video/x-flv',
        'mp4' => 'video/mp4',
        'mkv' => 'video/x-matroska',
        'wmv' => 'video/x-ms-wmv',
        'webm' => 'video/webm',
    );

    return array_key_exists($ext, $mime) ? $mime[$ext] : 'application/octet-stream';
}

function getTimeLabel($time, $now = null)
{
    if ($now===null) {
        $now = time();
    }

    $diff = $now - $time;

    if ($diff < 60*2) { // Under two minutes
        return 'Just right now';
    } elseif ($diff < 3600) { // Under a hour
        return ($diff/60%60).' minutes before';
    } elseif ($diff < 3600*4) { // Under four hours
        return ($diff/60/60%60).' hours and '.(($diff-($diff/60/60%60)*3600)/60%60).
            ' minutes before';
    } elseif ($diff < 3600*24*2) { // Under two days
        return ($diff/60/60%60).' hours before';
    } elseif ($diff < 3600*24*7) { // Under a week
        return ($diff/60/60/24%60).' days before';
    } elseif ($diff < 3600*24*7*5) { // Under 5 weeks
        return ($diff/60/60/24/7%60).' weeks before';
    } else {
        return date(DATE_ANH_NHAN, $time);
    }
}

function getAjaxErrorHandling($return = true)
{
    if ($return) {
        ob_start();
    }
    echo 'function (x, e) {
                    if(x.status == 0)
                        $.noticeAdd({text: "You are offline! Please check your network!"'.
                        ', type: "error"});
                    else if (x.status == 404)
                        $.noticeAdd({text: "Requested URL not found!", type: "error"});
                    else if (x.status == 403)
                        $.noticeAdd({text: "You did not have sufficient permission'.
                        ' to do what you wanted...", type: "error"});
                    else if (x.status == 500)
                        $.noticeAdd({text: "Server-side error!", type: "error"});
                    else if (x.status == "parseerror")
                        $.noticeAdd({text: "Error parsing response from server!", type: "error"});
                    else if(x.status == "timeout")
                        $.noticeAdd({text: "Network timeout!", type: "error"});
                    else
                        $.noticeAdd({text: "Unknown Error! "+x.responseText, type: "error"});
                }';

    if ($return) {
        return ob_get_clean();
    }
}

function rand_string($length = 30)
{
    $tmp = array_merge(range('a', 'z'), range('A', 'Z'));

    $string = '';
    for ($i = 0; $i < $length; $i++) {
        $rand = rand(0, 51);
        $string .= $tmp[$rand];
    }

    return $string;
}

function escapeSingleQuotes($text)
{
    return preg_replace('/([\\|\'])/', '\\$1', $text);
}
