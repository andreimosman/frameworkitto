<?php

namespace Application\Controllers;

use Frameworkitto\Router;

Class Home extends Authenticated {
    /**
     * When user logs in the example routes user to /home/dashboard.
     * 
     * the get method on superclass calls showViewBasedOnRoute() that open /Application/Views/{CrontrollerName}/{ActionName}
     * 
     * If no actionhame is supplied the default action name is index.
     * 
     * As you can see, if the content only show a view based on route, there is no need to have code inside the controller
     * 
     */
    
}
