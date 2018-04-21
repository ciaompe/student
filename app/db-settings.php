<?php
//all configurations goes here  

$GLOBALS['config'] = array(
	
	//database configuration
	'mysql' => array(
		'host' => 'localhost', //mysql hostname
		'username' => '',//mysql username
		'password' => '', //mysql password
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
