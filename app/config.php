<?php 

defined('APP_PATH') || define('APP_PATH', dirname(__DIR__));

//for php error reporting
error_reporting(E_ALL | E_STRICT);  
ini_set('display_startup_errors',1);  
ini_set('display_errors',1);

//Custom error handling function
function customError($errno, $errstr) {
  echo "<b>Error:</b> [$errno] $errstr<br>";
  echo "Ending Script";
  die();
}

//set custom error handler to the php 
//set_error_handler("customError");


//starting php session
session_start();

//Loading all core files in the core folder
spl_autoload_register(
	function($class) {
		require_once APP_PATH.'/app/core/'.$class.'.php';
	}
);

//Loading helpers
require_once APP_PATH.'/app/helpers/sanitize.php';

//Loading pdf 
require_once APP_PATH.'/app/helpers/pdf/fpdf.php';

//loading database configuration
require_once APP_PATH.'/app/db-settings.php';

if (Config::get('mysql/db') == "") {
	include APP_PATH.'/app/errors/database_error.php';
	die();
}

//create new application instance
$app = new App;

?>