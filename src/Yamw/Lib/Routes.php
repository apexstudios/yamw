<?php
namespace Yamw\Lib;

class Routes
{
    private $url;
    private $routes = array();

    public function __construct($url = '')
    {
        if (!$url) {
            $url = '/';
        }

        $this->url = $url;
        $this->initRoutes();
        $this->checkURLs();

        if (!Request::exists('module')) {
            trigger_error('Seems like there was no matching route for '.$url);
        }
    }

    private function initRoutes()
    {
        $this->routes = simplexml_load_file(path('/config/routes.xml'));
    }

    private function checkURLs()
    {
        foreach ($this->routes as $route_name => $route) {
            if ($this->url == $route->url) {
                Request::init((array)$route->params->params);
                return;
            }

            $t = $this->checkURL($this->url, $route);
            if ($t && is_array($t)) {
                $Request = Request::init($t);
                global $Processer;
                $Processer->setRoute($route->name);
                return;
            }
        }
    }

    private function checkURL($url, $route)
    {
        $pattern = (preg_match('/\/$/i', (string)$route->url)) ? (string)$route->url : (string)$route->url.'/';
        $url = (preg_match('/\/$/i', $url)) ? $url : $url.'/';
        $url = preg_replace('/\/nt\/$/i', '/', $url);

        $t1 = explode('/', $pattern);
        $t2 = explode('/', $url);

        // Not same amount of elements, thus never could match
        // We now hereby save a lot of resources by skipping this loop sequence
        if (count($t1) != count($t2)) {
            return false;
        }

        $ret = array();

        foreach ($t1 as $k => $t) {
            if (preg_match('/^:.*?/', $t)) {
                // It's a variable
                $ret[ltrim($t, ':')] = $t2[$k];
            } else {
                // It's not a variable
                if ($t != $t2[$k]) {
                    // String does not match
                    return false;
                } else {
                    $ret[$k] = $t;
                }
            }
        }

        // Now check for some given params in the routes scheme file
        // Overwrites any values obtained from URL
        if ($route->params) {
            foreach ($route->params->params as $param_name => $param_value) {
                $ret[$param_name] = $param_value;
            }
        }

        // Now check if there are requirements
        if ($route->requirements) {
            foreach ($route->requirements->requirements as $name => $req) {
                foreach ($req as $req_type => $req_value) {
                    switch (mb_strtolower($req)) {
                        case 'type':
                            if (!gettype($ret[$name]) == $req_value) {
                                return false;
                            }
                            break;
                        default:
                            trigger_error('Requirement type '.$req.' does not exist');
                            break;
                    }
                }
            }
        }

        return $ret;
    }
}
