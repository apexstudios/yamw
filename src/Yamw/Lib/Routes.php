<?php
namespace Yamw\Lib;

class Routes
{
    private $uri;
    private $routes = array();

    /**
     *
     * @var Request
     */
    private $request;

    public function __construct($url = '')
    {
        if (!$url) {
            $url = '/';
        }

        $this->uri = $url;
        $this->initRoutes();
    }

    public function setRequest(Request $request)
    {
        $this->request = $request;
        return $this;
    }

    public function getRequest()
    {
        return $this->request;
    }

    public function buildRequestFromURIs()
    {
        $this->checkURIs();

        if (!$this->request->valueExists('module')) {
            trigger_error('Seems like there was no matching route for ' . $this->uri);
        }

        return $this;
    }

    private function initRoutes()
    {
        $this->routes = simplexml_load_file(path('/config/routes.xml'));
    }

    private function checkURIs()
    {
        foreach ($this->routes as $route_name => $route) {
            if ($this->uri == $route->url) {
                $this->request->populate((array)$route->params);
                return;
            }

            $t = $this->checkURI($this->uri, $route);
            if ($t && is_array($t)) {
                $this->request->populate($t);

                $this->request->setValue('current-route', (string)$route->name);

                // Soon deprecated
                global $Processer;
                $Processer->setRoute((string)$route->name);

                return;
            }
        }
    }

    private function checkURI($url, $route)
    {
        $routeUri = (string)$route->url;
        $pattern = (preg_match('/\/$/i', $routeUri)) ? $routeUri : $routeUri.'/';

        // Strip off trailing slashes
        $url = (preg_match('/\/$/i', $url)) ? $url : $url.'/';
        // Strip off '/nt' from the end
        $url = preg_replace('/\/nt\/$/i', '/', $url);

        $patternParts = explode('/', $pattern);
        $uriParts = explode('/', $url);

        // Not same amount of elements, thus never could match
        // We now hereby save a lot of resources by skipping this loop sequence
        if (count($patternParts) != count($uriParts)) {
            return false;
        }

        $ret = array();

        foreach ($patternParts as $partIndex => $partPattern) {
            if (preg_match('/^:.*?/', $partPattern)) { // It's a variable
                $ret[ltrim($partPattern, ':')] = $uriParts[$partIndex];
            } else { // It's not a variable
                if ($partPattern != $uriParts[$partIndex]) {
                    // String does not match
                    return false;
                } else {
                    $ret[$partIndex] = $partPattern;
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
