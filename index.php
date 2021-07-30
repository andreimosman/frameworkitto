<?php

require("vendor/autoload.php");
require("autoload.php"); // Probably there's a better way to do it, but it got virtually no attention

use Frameworkitto\Model;
use Frameworkitto\ControllerFactory;

/**
 * If your app do access database you must to create a PDO instance then call static Model::setPDO()
 */
/**
$pdo = new PDO("mysql:dbname=DBNAMEHERE;host=localhost", "username", "password");
$pdo = new PDO("mysql:dbname=frameworkitto;host=localhost","frameworkitto",""); // <-- Sample
Model::setPDO($pdo); // Tell all the models to use this PDO instance.
*/


/**
 * If your app will send email, you can must to set up the EmailSender:
 * It uses PHPMailer.
 */

/**
EmailSender::setConfig([
    "host" => "mail.mymailserver.com",
    "auth" => true,
    "username" => "me@mymailserver.com",
    "password" => "myverysecurepassword",
    "from_email" => "noreply@mymailserver.com",
    "from_name" => "My incredible app!",
    "secure" => "STARTTLS",
    "port" => 465,
]);
*/

/**
 * Instantiate the controler
 */
$controller = ControllerFactory::instantiateControllerByRoute(); // Choose which controller to instantiate
$controller->execute(); // Do the magic
