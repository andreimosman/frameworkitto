<?php
/**
 * Very basic routing.
 * 
 * Written by Andrei Mosman <andrei.mosman@gmail.com>
 */

namespace Frameworkitto;

class Router {

    private static $instance;

    protected $baseURL = null;
    protected $appPath = null;
    protected $modules = ['admin'];
    protected $defaultController = 'home';

    protected $routeCache = [];

    private function __construct($baseURL=null) {
        $this->baseURL = $baseURL ??  self::getProtocolAndHost() . dirname($_SERVER['SCRIPT_NAME']);
        $this->appPath = dirname($_SERVER["SCRIPT_FILENAME"]);
    }

    public static function getInstance($baseURL = null) {
        if( self::$instance === null ) self::$instance = new self($baseURL);
        return self::$instance;
    }

    public function setDefaultController($defaultController) {
        $this->defaultController = strtolower($defaultController);
    }

    public function getDefaultController() {
        return $this->defaultController;
    }

    public function getAppPath() {
        return $this->appPath;
    }

    public function getBaseURL() {
        return $this->baseURL;
    }

    public static function getProtocolAndHost() {
        if( !isset($_SERVER['SERVER_NAME']) ) return ''; // CLI
        return strtolower( (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['SERVER_NAME'] );
    }

    public function setAction($action) {
        $this->getRoute(); // update cache if so
        $this->routeCache['action'] = $action;
    }

    public function getRoute() {

        if( $this->routeCache ) return $this->routeCache;
        
        $basePath = strtolower( dirname($_SERVER['SCRIPT_NAME']) ) . "/";
        @list($routePath,$query_string) = explode('?',str_replace($basePath, "", strtolower($_SERVER['REQUEST_URI'])),2);
        $routePath = str_replace("index.php/","",$routePath);

        $routeStack = explode('/', $routePath);

        $routeInfo = [
            'request_method' => strtolower($_SERVER["REQUEST_METHOD"]),
            'module' => null,
            'controller' => null,
            'action' => null,
            'params' => [],
        ];

        if(in_array($routeStack[0],$this->modules) || !$routeStack[0]) {
            $routeInfo['module'] = array_shift($routeStack);
        }

        $routeInfo['controller'] = array_shift($routeStack);
        if( !$routeInfo['controller'] ) $routeInfo['controller'] = $this->defaultController;


        $routeInfo['action'] = @array_shift($routeStack);
        if( !$routeInfo['action'] ) $routeInfo['action'] = 'index';

        if( $routeStack ) $routeInfo['params'] = $routeStack;


        $this->routeCache = $routeInfo;

        return($routeInfo);
    }

}
