<?php

namespace Application\Controllers;

/**
 * All the controllers that depends on user authentication will extends this one.
 */
class Authenticated extends \Frameworkitto\AuthenticatedController {
    
    public function init() {
        $this->setLoginRoute('user','login'); // It will redirect to this route when user is not logged id
        $this->setAuthenticatedHomeRoute('home','dashboard'); // When user logs in, it will be redirected to this
    }

}
