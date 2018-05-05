<?php
	/*

		Do what you want here...
		
		Default is redirect to default site, that will then redirect to /guestportal
		with URL parameters.

	*/

	require_once('core.php');

	header('Location: '.URL_UNIFI_SITES.'default/index.php');
	exit();
?>