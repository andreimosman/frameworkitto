<?php

namespace Application\Controllers;

use Frameworkitto\Router;

Class Home extends Authenticated {
    /**
     * When user logs in the example routes user to /home/dashboard.
     * 
     * the @GET method on main Controller class calls showViewBasedOnRoute() that opens /Application/Views/{CrontrollerName}/{ActionName}
     * 
     * If no action name is supplied, is index.
     * 
     * So for this homepage controller, the Controller class already handles a simple and basic call for a main index page. No extra coding needs to be done!
     * 
     */
    
}
