<?php

namespace Application\Controllers;

use Frameworkitto\Router;

Class Gettingstart extends Authenticated {

    /**
     * if you don't create any specific function, the controller will try to open a view /{controllername}/{actionname}
     * the default actionname is index.
     * 
     * but you can create the some functions:
     */

    /**
    // Handles all GET requests
    public function get($action,$params) {
        // You can set the view using:

        $viewPath = "foldername/viewname";
        $this->showView($viewPath); // <-- = it will display with headers and footers

        // or you can call view directly to process only the content of a view
        $this->view->show($viewPath);

        // if you want to store the output on a variable you can call:
        $var = $this->view->show($viewPath,true);
    }
    */

    /**
    // Handles all POST requests
    public function post($action,$params) {

    }
    */
    
    /**
    // Handles all PUT requests
    public function put($action,$params) {

    }
    */
        
    /**
    // Handles all PUT delete
    public function put($action,$params) {

    }
    */

    /**
    // Or you can create handlers to methods and actions. Ex:

    function getTest($params=null) {
        // The line bellow will show view /gettingstart/test
        $this->showViewBasedOnRoute();
    }

     */
        

    
}
