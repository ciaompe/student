<?php
//all configurations goes here  

$GLOBALS['config'] = array(
	
	//database configuration
	'mysql' => array(
		'host' => 'localhost', //mysql hostname
		'username' => 'root',//mysql username
		'password' => 'e97adada80157a17c4b0c9b8b3707fd9d358f937d480a648', //mysql password
		'db' => 'mysara' //mysql database name
	),
	//cookie configuration
	'rember' => array(
	 	'cookie_name' => 'hash',
	 	'cookie_expiry' => 86400
	),
	//session configuration
	'session' => array(
		'session_name' => 'user',
		'token_name' => 'token'
	)
);

 ?>