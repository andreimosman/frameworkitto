<?php

require("vendor/autoload.php");
require("autoload.php"); // Probably there's a better way to do it, but it got virtually no attention

use Frameworkitto\Model;
use Frameworkitto\ControllerFactory;

/**
 * If your app do access database you must to create a PDO instance then call static Model::setPDO()
 */
// $pdo = new PDO("mysql:dbname=DBNAMEHERE;host=localhost", "username", "password");
//$pdo = new PDO("mysql:dbname=frameworkitto;host=localhost","frameworkitto",""); // <-- Sample
//Model::setPDO($pdo);

/**
 * Instantiate the controler
 */
$controller = ControllerFactory::instantiateControllerByRoute(); // Choose which controller to instantiate
$controller->execute(); // Do the magic
