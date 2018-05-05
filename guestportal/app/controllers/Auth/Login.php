<?php
require_once('../../../../core.php');


if (isset($_POST['_csrf']) && $_POST['_csrf'] != $_SESSION['_csrf']) {
	die('Session expired... Please go back and try again...');
}



if ($_GET['action'] == 'do_login')
{

	$username = $_POST['inputUsername'];
	$password = $_POST['inputPassword'];
	$result = $objLogin->do_login($username, $password);

	if ($result['status'] == 'success') {
		$redir = URL.'admin/';
	} else {
		$redir = URL.'admin/login/?errorMsg='.urlencode($result['message']).'&msgID='.$result['msg_id'].'&username='.$username;
	}

	output($result, $redir);
}


if ($_GET['action'] == 'do_auto_login')
{
	$username = $_GET['username'];
	$result = $objLogin->do_login($username, '', true);

	if ($result['status'] == 'success') {
		$redir = URL.'admin/';
	} else {
		$redir = URL.'admin/login/?errorMsg='.urlencode($result['message']).'&msgID='.$result['msg_id'].'&username='.$username;
	}

	output($result, $redir);
}



if ($_GET['action'] == 'do_logout')
{
	$result = $objLogin->do_logout();
	$redir = URL.'admin/login/';
	output($result, $redir);
}