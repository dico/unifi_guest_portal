<?php
require_once('../../../../core.php');



if (isset($_POST['_csrf']) && $_POST['_csrf'] != $_SESSION['_csrf']) {
	header('Location: '.URL_GUESTPORTAL.'?start&msg=8');
	exit();
}





//if (isset($_SESSION['loggingin'])) // Check to see if the form has been posted to
if ($_GET['action'] == 'mobile_auth_send_code') // Check to see if the form has been posted to
{
	if (isset($_POST['authID'])) $p['auth_id'] = $_POST['authID'];
	elseif (isset($_GET['authID'])) $p['auth_id'] = $_GET['authID'];

	if (isset($_POST['firstname'])) $p['firstname'] = $_POST['firstname'];
	elseif (isset($_GET['firstname'])) $p['firstname'] = $_GET['firstname'];

	if (isset($_POST['lastname'])) $p['lastname'] = $_POST['lastname'];
	elseif (isset($_GET['lastname'])) $p['lastname'] = $_GET['lastname'];

	if (isset($_POST['mobile'])) $p['mobile'] = $_POST['mobile'];
	elseif (isset($_GET['mobile'])) $p['mobile'] = $_GET['mobile'];

	$objAuth = new \GuestPortal\Auth\Auth();
	$result = $objAuth->mobile_auth_send_code($p);

	if ($result['status'] == 'success') {
		$redir = URL_GUESTPORTAL . "?page=enter_sms_code&authID=".$result['auth_id'].'&firstname='.$p['firstname'].'&lastname='.$p['lastname'].'&mobile='.$p['mobile'];
		if (isset($p['auth_id'])) $redir = $redir.'&authID='.$p['auth_id'];
	} else {
		$redir = URL_GUESTPORTAL . "?msg=8";
	}

	output($result, $redir);
}




if ($_GET['action'] == 'confirm_sms_code')
{
	$p['auth_id'] = $_GET['authID'];
	$p['code'] = $_POST['code'];


	$objAuth = new \GuestPortal\Auth\Auth();
	$result = $objAuth->mobile_auth_confirm_code($p);

	if ($result['status'] == 'success') {
		$redir = URL_GUESTPORTAL . "?page=start&msg=1";
	} else {
		$redir = URL_GUESTPORTAL . '?page=enter_sms_code&authID='.$p['auth_id'].'&msg=9';
	}

	output($result, $redir);
}



if ($_GET['action'] == 'room_auth_user')
{
	$lastname = $_POST['lastname'];
	$pin = $_POST['pin'];

	$result = $objAuth->room_auth_user($lastname, $pin);

	if ($result['status'] == 'success') {
		$redir = URL_GUESTPORTAL . "?page=start&msg=1";
	} else {
		$redir = URL_GUESTPORTAL . "?page=start&msg=9";
	}

	output($result, $redir);
}



/*
if ($_GET['action'] == 'intern_auth_user')
{
	$username = $_POST['username'];
	$password = $_POST['password'];

	$result = $objAuth->intern_auth_user($username, $password);

	if ($result['status'] == 'success') {
		$redir = URL_GUESTPORTAL . "?page=start&msg=1";
	} else {
		$redir = URL_GUESTPORTAL . "?page=start&msg=9";
	}

	echo '<pre>';
		print_r($result);
	echo '</pre>';

	output($result, $redir);
}



if ($_GET['action'] == 'school_auth_user')
{
	$username = $_POST['username'];
	$password = $_POST['password'];

	$result = $objAuth->school_auth_user($username, $password);

	if ($result['status'] == 'success') {
		$redir = URL_GUESTPORTAL . "?page=start&msg=1";
	} else {
		$redir = URL_GUESTPORTAL . "?page=start&msg=9";
	}

	echo '<pre>';
		print_r($result);
	echo '</pre>';

	output($result, $redir);
}
*/