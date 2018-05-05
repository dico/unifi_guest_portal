<?php
	ob_start();
	session_start();


	// prevent XSS
	$_GET   = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);
	$_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);




	/* Get config file and set ABSPATH
	--------------------------------------------------------------------------- */
	define( 'ABSPATH', dirname(__FILE__) . '/' );

	if ( file_exists( ABSPATH . 'config.php') ) {
		require_once( ABSPATH . 'config.php' ); // The config file resides in ABSPATH
	}




	/* Create database instance
	--------------------------------------------------------------------------- */
	$mysqli = new Mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME); // Create DB-instance
	if ($mysqli->connect_errno) { die('Connect Error: ' . $mysqli->connect_errno); } // Check for connection errors
	
	mysqli_set_charset($mysqli, "utf8"); // Set DB charset




	/* Define variables
	--------------------------------------------------------------------------- */
	define('PACKAGES_URL', URL.'core/packages/');
	define('PACKAGES_PATH', ABSPATH.'core/packages/');






	/* Function and classes
	--------------------------------------------------------------------------- */
	require_once(ABSPATH . 'guestportal/app/autoload.php');
	//require_once(ABSPATH . 'guestportal/vendor/autoload.php'); // If needed for composer-packages

	require(ABSPATH.'guestportal/src/php_functions/core.php');

	


	/* Set unifi hotspot variables
	--------------------------------------------------------------------------- */
	
	if (isset($_GET['id']) && isset($_GET['ssid']) && isset($_GET['ap'])) {
		$_SESSION['controller']['id'] = $_GET['id'];
		$_SESSION['controller']['ap'] = $_GET['ap'];
		$_SESSION['controller']['time'] = $_GET['t'];
		$_SESSION['controller']['ssid'] = $_GET['ssid'];
		$_SESSION['controller']['refURL'] = $_GET['url'];
		$_SESSION['controller']['siteid'] = $_GET['siteID'];
	}


	elseif(!isset($_SESSION['controller']['id']) && !isset($_SESSION['controller']['ssid']) && !isset($_SESSION['controller']['ap'])) {
		$_SESSION['controller']['id'] = '';
		$_SESSION['controller']['ap'] = '';
		$_SESSION['controller']['time'] = '';
		$_SESSION['controller']['ssid'] = '';
		$_SESSION['controller']['refURL'] = '';
		$_SESSION['controller']['siteid'] = '';
	}


	if (empty($_SESSION['controller']['time'])) {
		$_SESSION['controller']['time'] = time();
	}

	if (empty($_SESSION['controller']['refURL'])) {
		$_SESSION['controller']['refURL'] = REDIR_URL;
	}

?>