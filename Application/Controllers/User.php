<?php

namespace Application\Controllers;

use Frameworkitto\Router;
use Frameworkitto\Controller;

class User extends Controller {

    /** SAMPLE:
    protected $model;

    public function init() {
        $this->model = new UsersModel();
    }
    */


    /**
     * You can create function to handle http methods (ex: post($action), get($action), delete($action), put($action) off all requisitions)
     * 
     * or you can create a function that handles entire action. ex: postLogin() on controller user handles POST to /user/login
     */

    public function postLogin() {
        $email = @$_REQUEST['email'];
        $password = @$_REQUEST['password'];

        if( $email == 'test@test.com' && $password == '1234' ) {
            $this->setAuthenticationData([
                "user" => ["email" => $email]
            ]);

            return $this->setJSONResponse([
                'status' => 'success',
                'url' => Router::getInstance()->getBaseURL() . "/authenticated/route/" . uniqid()
            ]);            
        }

        return http_response_code(401); // Failed authentication;
    }

    public function postSignup() {
        $httpResponseCode = 400;

        $response = [
            "status" => "error",
            "message" => "This is just a sample code, please use 'test@test.com' password '1234' to login.",
        ];

        http_response_code($httpResponseCode);
        $this->setJSONResponse($response);

    }

    function postForgotPassword() {
        $email = @$_REQUEST['email'];
        return $this->setJSONResponse([
            "status" => "success",
            "message" => "This is just a message pretending to be ok. You submitted ".$email.". If you have not received it from us within 5 minutes, please check your spam folder.",
        ]);
    }

    /**
     * /user/logout is handled by \Frameworkitto\Logout
     */

    /**
     * The example bellow handle all actions
     */
    public function post($action) {
        echo "ACTION: $action";
    }


}