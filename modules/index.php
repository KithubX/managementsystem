<?php
 
require 'vendor/autoload.php';

\Slim\Slim::registerAutoloader();

 $app = new \Slim\Slim();

define ("SPECIALCONSTANT",true);
 
require("app/libs/connect.php");
require("userscontroller.php");
require("usersmodel.php");
require("app/routes/api.php");
require("app/libs/_Consultar.php");

$app->run();

?>