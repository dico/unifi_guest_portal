<?php
	ob_start();
	session_start();

	// Explode URL into parts
	$filepath_parts = explode('/', $_SERVER['SCRIPT_FILENAME']);
	
	// Define the URL
	if ($_SERVER['SERVER_PORT']  == 443) {
		define('URL', 'https://'.$_SERVER['HTTP_HOST'].'/');
	} else {
		define('URL', 'http://'.$_SERVER['HTTP_HOST'].'/');
	}
	

	// Split and loop the URL parts to use in redirect
	$q = '';
	$c = 0;
	$n = count($_GET);

	foreach ($_GET as $key => $value) {
		$c++;

		if ($c == 1) $q .= '?';
		else $q .= '&';
		
		$q .= "$key=$value";
	}

	$q .= "&siteID={$filepath_parts[6]}";

	// Redirect user to correct login-site
	header('Location: '.URL.'guestportal/index.php'.$q);
	exit();
?>