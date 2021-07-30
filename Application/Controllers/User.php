<?php

namespace Application\Controllers;

use Frameworkitto\Router;
use Frameworkitto\Controller;

use Application\Models\Users;

class User extends Controller {

    /** SAMPLE:
    protected $model;

    public function init() {
        // In order for this to work you must to call Model::setPDO() on index.php
        $this->model = new UsersModel();
    }
    */


    /**
     * You can create functions to handle http methods (ex: post($action), get($action), delete($action), put($action) off all requisitions)
     * 
     * or you can create a function that handles entire action. ex: postLogin() on controller user handles POST to /user/login
     */

    /**
     * Sample login function
     * Route: @POST "/user/login"
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

    // Sample code that sends confirmation email using templates.
    public function sendConfirmationEmail($userData) {
        $sender = new EmailSender();
        $title = "My super app using Frameworkitto! Please confirm your email.";
        $template = "_mailtemplates/email_confirmation"; // <-- Application/Views/_mailtemplates/email_confirmation.php
        // $userData is passed as variables to the template
        return $sender->sendEmailUsingTemplate($data['email'],$title,$template,$userData);
    }

    /**
     * Sample signup function that receives data
     * Route: @POST "/user/signup"
     */
    public function postSignup() {
        $httpResponseCode = 400;

        $response = [
            "status" => "error",
            "message" => "This is just a sample code, please use 'test@test.com' password '1234' to login.",
        ];

        /**
        try {
            // 1. Do your magic to create the account here (very rudimentar example bellow)
            //$data = $this->model->create($_REQUEST);

            // 2. Send confirmation email
            //$this->sendConfirmationEmail($data);

            // 3. Everything happened ok.
            $httpResponseCode = 200;
            $response = [
                "status" => "success",
                "message" => "Your account has been created. Please check your e-mail to activate-it before log-in.",
                "url" => Router::getInstance()->getBaseURL() . "/user/login",
            ];

        } catch(\Exception $e) {
            $httpResponseCode = 500; // <-- some error code here
            $response = [
                "status" => "error",
                "message" => "Something happened... ",
            ];
        }
        */

        http_response_code($httpResponseCode);
        $this->setJSONResponse($response);

    }

    /**
     * Sample password recovery function
     * Route: @POST "/user/forgotpassword"
     */
    function postForgotPassword() {
        $email = @$_REQUEST['email'];
        return $this->setJSONResponse([
            "status" => "success",
            "message" => "This is just a message pretending to be ok. You submitted ".$email.". If you have not received it from us within 5 minutes, please check your spam folder.",
        ]);
    }

    /**
     * /user/logout is handled by \Frameworkitto\Controller::logout()
     */

    /**
     * The example below handles POST all actions
     */
    public function post($action) {
        echo "ACTION: $action";
    }


}
