<?php
namespace Yamw\Lib;

use Yamw\Lib\Templater\Templater;

class Core
{
    /**
     *
     * @var Yamw\Lib\Core
     */
    private static $instance;

    protected $action_string;
    protected $actionstring;
    protected $module;
    protected $action;
    protected $section;

    /**
     * @deprecated
     * @var bool
     */
    protected $render_template = true;

    /**
     * @deprecated
     * @var bool
     */
    protected $save_stats = true;

    /**
     * The current used template
     *
     * @deprecated
     * @var string
     */
    protected $current_template;
    protected $current_route;
    public $page;

    protected $ec_content;

    private $forward = false;
    private $canForward = true;

    /**
     * @return \Yamw\Lib\Core
     */
    final public static function getInstance()
    {
        if (!isset(self::$instance)) {
            $class = __CLASS__;
            self::$instance = new $class;
        }
        return self::$instance;
    }

    /**
     *
     * @return \Yamw\Lib\Core
     */
    final public static function justGetMeTheInstance()
    {
        return self::$instance;
    }

    /**
     * Processes the http request and chooses the route accordingly (which will
     * populate the Request class with data)
     */
    public function register()
    {
        global $Config, $Processer;
        $Config = Config::register();
        $Processer = $this;

        if (isCli()) {
            $this->cli();
        } else {
            $this->http();
        }

        return $this;
    }

    /**
     * Initiates Environment for HTTP Requests
     */
    public function http()
    {
        contenttype();
        nocache();
        header('X-Content-Type-Options: nosniff');
        header('X-XSS-Protection: 1; mode=block');
        header('X-Frame-Options: SAMEORIGIN');
        header('Strict-Transport-Security: max-age=31536000 ; includeSubDomains');

        // Content Security Policy
        // header("X-WebKit-CSP: default-src https:");


        global $Routes;

        Request::populateFromGet(array('page'));

        $page = '/'.Request::get('get-page');
        $opage = $page;

        preg_replace_callback('/^(.*?)(\.htm(l|)|\.php(3|4|5|s|)|\.jsp|\.asp(x)|)$/i',
        create_function('$matches', 'global $abc; $abc = $matches; return $matches[1];'), $page);

        $Routes = new Routes($page);

        $this->action_string = explode('/', $page);

        if (in_array('nt', $this->action_string)) {
            noTemplate();
        }

        global $abc;
        $this->extension = $abc[1];
        $this->actionstring = trim($page, '/');

        Request::populateFromServer(
            array(
                'QUERY_STRING' => '/',
                'REQUEST_URI' => '/',
                'HTTP_HOST' => 'localhost',
                'PHP_SELF' => 'index.php'
            )
        );

        $temp_page = str_replace(Request::get('server-query_string'), '', Request::get('server-request_uri'));
        $temp_page = str_replace(basename(Request::get('server-php_self')), '', $temp_page);
        $temp_page = str_replace($opage, '', $temp_page);
        $temp_page = str_replace($page, '', $temp_page);

        // If we pass additional url parameters, they shouldn't appear in the base url
        $temp_page = preg_replace("/(.*?)\?.*+/i", '$1', $temp_page);
        $temp_page = preg_replace("/(.*?)&.*+/i", '$1', $temp_page);

        $temp_page = str_replace("\n", '', $temp_page);

        if (!$temp_page) {
            $temp_page = '/';
        }
        if (!preg_match('/\/$/si', $temp_page)) {
            $temp_page .= '/';
        }
        if (!preg_match('/^\//si', $temp_page)) {
            $temp_page = '/'.$temp_page;
        }

        $host = Request::get('server-http_host');
        $this->page = "http://{$host}{$temp_page}";

        if (!preg_match('/\/$/si', $this->page)) {
            $this->page .= '/';
        }

        $this->section = Request::get('section');
        $this->module = Request::get('module');
        $this->action = Request::get('action');

        $this->init();
    }

    /**
     * Processes CLI requests
     */
    public function cli()
    {
        Templater::noTemplate();

        if (isset($_SERVER['argv'])) {
            array_shift($_SERVER['argv']);
            $this->args = $_SERVER['argv'];
            $this->args_count = $_SERVER['argc'] - 1;
        } else {
            $this->args = array();
            $this->args_count = 0;
        }

        try {
            $cliloader = new Loaders\CliLoader($this->args);
            $this->ec_content = $cliloader->load();
        } catch (\Exception $e) {
            println("Something pretty awesome happened that was not supposed to be.");
            echo $e->getMessage();
        }
    }

    /**
     * Initializes the page render by loading the module chosen by the http request.
     * Additionally, it loads the template into the Templater and, if applicable,
     * handles any forwards initiated by the forward.
     */
    public function init()
    {
        // Retrieve the content
        $this->ec_content = $this->getModule();

        // Forwarding code
        if ($this->forward) {
            $this->getForward($this->forward[0], $this->forward[1], $this->forward[2]);
        }
    }

    /**
     * Retrieves the module requested
     *
     * @param string $modules
     * @param string $actions
     *
     * @return string The content.
     */
    public function getModule($modules = '', $actions = '', $section = '')
    {
        ob_start();
        ob_implicit_flush(0);

        global $UAM, $MySql, $Config, $Routes, $Request;

        if (!$modules) {
            $modules = $this->module;
        }

        if (!$actions) {
            $actions = $this->action;
        }

        if (!$section) {
            $section = $this->section;
        }

        try {
            $moduleLoader = new Loaders\ModuleLoader();
            $moduleLoader->load($modules, $actions, $section);
        } catch (Exceptions\HttpErrorException $e) {
            ob_clean();
            echo $e->getMessage();
            header('HTTP/1.0 '.$e->getCode());
        } catch (Exceptions\AuthenticationException $e) {
            header('HTTP/1.0 403');

            getLogger()->addNotice(
                'Unauthorized access!',
                array(
                    'current_section' => $section,
                    'current_module' => $modules,
                    'current_action' => $actions,
                    'current_route' => $this->current_route,
                    'error_msg' => $e->getMessage(),
                    'uid' => UAM\UAM::getInstance()->getCurUserId(),
                    'username' => UAM\UAM::getInstance()->getCurUserName()
                )
            );

            // Wipe the current output buffer to reduce the risk of compromission
            ob_clean();

            $this->forward(403, 'index', 'error');

            echo $e->getMessage();
        } catch (\Exception $e) {
            getLogger()->addError(
                'Unknown Exception!',
                array(
                    'current_section' => $section,
                    'current_module' => $modules,
                    'current_action' => $actions,
                    'current_route' => $this->current_route,
                    'exception_message' => $e->getMessage(),
                    'exception_type' => get_class($e)
            ));

            echo $e->getMessage();
        }
        return ob_get_clean();
    }

    /**
     * Initiates a 403 error
     *
     * @param string $msg
     */
    public function f403($msg = '')
    {
        $this->fError(403, $msg);
    }

    /**
     * Initiates a 404 error
     *
     * @param string $msg
     */
    public function f404($msg = '')
    {
        $this->fError(404, $msg);
    }

    /**
     * Initiates a 500 error
     *
     * @param string $msg
     */
    public function f500($msg = '')
    {
        $this->fError(500, $msg);
    }

    /**
     * Generates an error if forward capability is enabled.
     * @param int $error
     * @param str $action
     * @param str $msg
     */
    public function fError($error = 404, $msg = '')
    {
        if (!$this->canForward) {
            return;
        }
        $this->error = $error;
        if (!DEBUG) {
            header('Cache-Control: no-cache, must-revalidate', true, $this->error);
        }
        $this->error_message = $msg;

        TemplateHelper::errorCSS();
        $this->forward($error, 'index', 'error');

        throw new Exceptions\HttpErrorException($msg, $error);
    }

    /**
     * Forward the current page to the desired Module/Action
     * @param string $module an existing module
     * @param string $action an existing action
     * @return unknown_type
     */
    public function forward($module, $action, $section = null)
    {
        if ($this->canForward) {
            $this->module = $module;
            $this->action = $action;
            $this->section = $section;
            $this->Forward = array($module, $action, $section);
        }
    }

    /**
     * Retrieves the current forward into the content store
     * (and possibly overwriting it)
     *
     * @param string $module
     * @param string $action
     */
    public function getForward($module, $action)
    {
        $this->ec_content = $this->getModule($module, $action, 'error');
    }

    /**
     * Blocks the ability to forward the user to another page
     */
    public function blockForward()
    {
        $this->canForward = false;
    }

    /**
     * Activates the ability to forward the user to another page
     */
    public function enableForward()
    {
        $this->canForward = true;
    }

    /**
     * Returns the current content
     * @param boolean $escaped
     * @return string <p>
     * The current content
     * </p>
     */
    public function getContent($escaped = false)
    {
        if ($escaped) {
            // Escaped for safe HTML
            return escape($this->ec_content, true);
        } else {
            return $this->ec_content;
        }
    }

    /**
     * Forwards all users to an 'Access Restricted'-Message and block further forwarding
     */
    public function blockAccess()
    {
        $this->f403();
        $this->blockForward();
    }

    /**
     * Returns the action string
     *
     * @deprecated
     *
     * @return string
     */
    public function getActionString()
    {
        return $this->actionstring;
    }

    public function getCurrentSection()
    {
        return $this->section;
    }

    public function getCurrentModule()
    {
        return $this->module;
    }

    public function getCurrentAction()
    {
        return $this->action;
    }

    public function getPage()
    {
        return $this->page;
    }

    public function setRoute($r)
    {
        if (!$r) {
            return false;
        }
        $this->current_route = $r;
    }

    /**
     * Returns the current route
     *
     * @return boolean
     */
    public function getRoute()
    {
        return $this->current_route;
    }
}
