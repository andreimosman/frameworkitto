<?php
/**
 * Very basic authenticated controller.
 * 
 * Written by Andrei Mosman <andrei.mosman@gmail.com>
 */

namespace Frameworkitto;

Class AuthenticatedController extends Controller {

    protected $loginRoute = [];
    protected $homeRoute = [];

    public function __construct() {
        parent::__construct();
        $this->view->setNavigationBarView("authenticated/navigationbar");
    }

    public function setLoginRoute($controller,$action, $module = null) {
        $this->loginRoute = [
            'controller' => $controller,
            'action' => $action,
            'module' => $module,
        ];
    }

    public function setAuthenticatedHomeRoute($controller,$action, $module = null) {
        $this->homeRoute = [
            'controller' => $controller,
            'action' => $action,
            'module' => $module,
        ];
    }

    public function redirectToHome() {
        return $this->redirectToRoute($this->homeRoute['controller'],$this->homeRoute['action'], $this->homeRoute['module']);
    }

    protected function isAuthenticated() {
        if( !@$_SESSION['_AUTH_USER_'] ) return false;
        return $this->checkAuthentication();
    }

    protected function checkAuthentication() {
        return true;
    }

    public function redirectToLogin() {
        return $this->redirectToRoute($this->loginRoute['controller'],$this->loginRoute['action'], $this->loginRoute['module']);
    }

    public function showViewBasedOnRoute() {
        if( !$this->isAuthenticated() ) return $this->redirectToLogin();
        return parent::showViewBasedOnRoute();
    }

    public function route() {
        if( !$this->isAuthenticated() ) return $this->redirectToLogin();
        return $this->redirectToHome();
    }


}
