<?php
/**
 * Very basic controller factory.
 * 
 * Written by Andrei Mosman <andrei.mosman@gmail.com>
 */

namespace Frameworkitto;

Class ControllerFactory {

    public static function instantiateControllerByRoute(): Controller {
        $routeInfo = Router::getInstance()->getRoute();

        $routeStack = ['Application','Controllers'];
        if( $routeInfo["module"] ) $routeStack[] = ucfirst($routeInfo["module"]);
        $routeStack[] = ucfirst($routeInfo["controller"]);

        $className = implode('\\',$routeStack);

        $controller = new $className();

        return $controller;
        
    }


}
