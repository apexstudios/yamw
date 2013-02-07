<?php
namespace Yamw\Lib;

date_default_timezone_set('Europe/London');

// Security URLs
@ini_set('allow_url_fopen', 'Off');
@ini_set('allow_url_include', 'Off');

// XDebug
@ini_set('xdebug.collect_params', '2');
@ini_set('xdebug.dump.SERVER', 'REQUEST_URI');
@ini_set('xdebug.show_local_vars', 'on');
@ini_set('xdebug.dump_globals', 'on');


// Security Errors
if (DEBUG) {
    @error_reporting(E_ALL);
    @ini_set('display_errors', '1');
} else {
    @error_reporting(E_ERROR);
    @ini_set('display_errors', '0');
}

if (function_exists('xdebug_break')) {
    @ini_set('error_prepend_string', '<font color="#000000">');
    @ini_set('error_append_string', '</font>');
} else {
    @ini_set('error_prepend_string', '<font color="#ff8888">');
    @ini_set('error_append_string', '</font>');
}
#@ini_set('error_prepend_string', '');
#@ini_set('error_append_string', '');

@ini_set('expose_php', 'Off');

// Tag specifics
@ini_set('asp_tags', 'Off');
@ini_set('short_open_tag', 'Off');

// Magic Quotes
@ini_set('magic_quotes_gpc', 'Off');
@ini_set('magic_quotes_runtime', 'Off');

// Register Globals
@ini_set('register_globals', 'Off');

// MultiByte-Strings
@ini_set('mbstring.func_overload', 7);
mb_internal_encoding('UTF-8');
@ini_set('mbstring.http_input', 'auto');
@ini_set('mbstring.http_output', 'UTF-8');

// Do we actually need it? I mean, we use our own system atm...
// Session
/** disabled
 * @ini_set('session.save_handler', 'files');
 * @ini_set('session.use_trans_sid', 0);
 * @ini_set('session.gc_maxlifetime', 60*60*24*2);
 * @ini_set('session.cookie_lifetime', 60*60*24*2);
 * @ini_set('session.name', 'hcaw_psessid');
 * @ini_set('session.cookie_httponly', 1);
 * @ini_set('session.use_cookies', 1);
 * @ini_set('session.use_only_cookies', 1);
 */
