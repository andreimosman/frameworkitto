<?php
/**
 * Very basic controller.
 * 
 * Written by Andrei Mosman <andrei.mosman@gmail.com>
 */

namespace Frameworkitto;

Class Controller {
    protected $view;

    protected $headerTemplate = "header";
    protected $footerTemplate = "footer";
    
    protected $includeHeader = true;
    protected $includeFooter = true;

    public function __construct() {
        if( !isset($_SESSION) ) session_start();
        $this->view = View::getInstance();
        $this->view->assign("ASSETS_CACHE_ID", 
            @ENVIRONMENT == "production" ? date('YMD') : uniqid()
        );
        $this->init();
        
    }

    public function init() {

    }

    public function loadFilesContent($files) {
        $content = '';
        foreach($files as $file) {
            $content .= file_get_contents($file);
        }
        return($content);
    }

    public function setIncludeHeader($includeHeader) {
        $this->includeHeader = $includeHeader;
    }

    public function setIncludeFooter($includeFooter) {
        $this->includeFooter = $includeFooter;
    }

    public function setHeaderTemplate($headerTemplate) {
        $this->headerTemplate = $headerTemplate;
    }

    public function setFooterTemplate($footerTemplate) {
        $this->footerTemplate = $footerTemplate;
    }

    public function addAsset($type, $asset, $target) {
        $assetPath = Router::getInstance()->getAppPath() . "/assets/" . $type . "/" . $asset . "." . $type;
        $assetURL  = Router::getInstance()->getBaseUrl() . "/assets/" . $type . "/" . $asset . "." . $type;

        if( file_exists( $assetPath ) ) {            
            if( $type == "js" ) $this->view->addCustomScript($target,$assetURL);
            if( $type == "css" ) $this->view->addCustomStyle($target,$assetURL);
        }
    }

    public function configureScriptsAndStylesBasedOnRoute() {
        $routeInfo = Router::getInstance()->getRoute();
        
        $controllerPath = $routeInfo['controller'];
        if( $routeInfo['module'] ) $controllerPath = $routeInfo['module'] . "/" . $view;
        $actionPath = $controllerPath . "/" . $routeInfo['action'];

        $this->addAsset("js", "common", "footer");

        $this->addAsset("css", $controllerPath, "header");
        $this->addAsset("css", $actionPath, "header");

        $this->addAsset("js", $controllerPath, "footer");
        $this->addAsset("js", $actionPath, "footer");
        
    }

    public function showView($view) {
        $this->configureScriptsAndStylesBasedOnRoute();
        if($this->includeHeader && $this->headerTemplate) $this->view->show($this->headerTemplate);
        $this->view->show($view);
        if($this->includeFooter && $this->footerTemplate) $this->view->show($this->footerTemplate);

    }

    public function showViewBasedOnRoute() {
        $routeInfo = Router::getInstance()->getRoute();
        $view = $routeInfo['controller'] . "/" . $routeInfo['action'];
        if( $routeInfo['module'] ) $view = $routeInfo['module'] . "/" . $view;
        return($this->showView($view));
    }

    public function execute() {
        $routeInfo = Router::getInstance()->getRoute();

        $methods = [
            $routeInfo['request_method'] . $routeInfo['action'], // More specific first
            $routeInfo['action'],
        ];

        $foundMethod = false;
        foreach($methods as $method) {
            if( method_exists($this, $method) ) {
                $foundMethod = true;
                break;
            }
        }

        $params = count($routeInfo['params']) == 1 ? $routeInfo['params'][0] : $routeInfo['params'];
        if( !$params ) $params = null;

        if($foundMethod) {
            return $this->$method($params); // Method will get one or more paramethers    
        } else {
            $method = $routeInfo["request_method"]; // Try to call get(), post(), put(), delete() on controller. Only get is implemented.
            return $this->$method($routeInfo['action'], $params);
        }

    }

    // Default get method (other http methods are not implemented by default)
    public function get($action, $params) {
        $this->showViewBasedOnRoute();
    }

    public static function redirectToRoute($controllerName, $action = null, $module = null) {
        $url = Router::getInstance()->getBaseURL() . ($module ? $module . "/" : '') . "/" . $controllerName;
        if( $action ) $url .= "/" . $action;
        self::redirectToURL($url);
    }

    public static function redirectToURL($url) {
        header("location: " . $url);
    }

    protected function setAuthenticationData($authenticationData) {
        $_SESSION['_AUTH_USER_'] = $authenticationData;
    }

    protected function getAuthenticationData(){ 
        return($_SESSION['_AUTH_USER_']);
    }

    protected function clearAuthenticationData() {
        unset($_SESSION['_AUTH_USER_']);
    }

    protected function logout() {
        $this->clearAuthenticationData();
        return $this->redirectToRoute('authenticated','route');
    }

    public function setJSONResponse($response) {
        header("Content-type: application/json");
        echo json_encode($response);
    }


}
