<?php
require_once('../../../../core.php');




if ($_GET['action'] == 'add_device')
{

	if (!isset($_SESSION['auth_user']) || empty($_SESSION['auth_user'])) {
		unset($_SESSION['auth_user']);
		die('Unauthorized user');
	}


	$site_id = $_POST['selSite'];
	$mac = $_POST['inputMac'];
	$comment = $_POST['inputComment'];

	
	$_SESSION['site_id'] = $site_id;
	$_SESSION['mac'] = $mac;
	$_SESSION['comment'] = $comment;


	$r['inputs'] = array(
		'site_id' => $site_id,
		'mac' => $mac,
		'comment' => $comment,
	);

	$minutes = (60 * 24 * 365);
	$r['unifi_authorize'] = $objUnifiapi->authorize_guest_by_mac($site_id, $mac, $minutes);




	

	if ($r['unifi_authorize']['status'] == 'success') {
		$query = "INSERT INTO devices_static SET 
					time_added='". date('Y-m-d H:i:s') ."', 
					mac_adress='".$mac."', 
					site_id='".$site_id."', 
					comment='".$comment."', 
					user='".$_SESSION['auth_user']."', 
					raw_feedback='".$technicianFeedback."'";
		$result = $mysqli->query($query);

		if ($result) {
			$r['write2db'] = 'success';
		} else {
			$r['write2db'] = 'error';
		}



		$r['status'] = 'success';

		$logParams = array(
			'user' => $_SESSION['auth_user'],
			'title' => 'Added static MAC-device',
			'description' => '',
			'raw_data' => $r,
		);
	}

	else {
		$r['status'] = 'error';

		$logParams = array(
			'user' => $_SESSION['auth_user'],
			'title' => 'ERROR: Added static MAC-device',
			'description' => 'Bad response from UniFi-controller',
			'raw_data' => $r,
		);
	}

	$r['log_event'] = $objGeneralBackend->log_event($logParams);


	if ($r['status'] == 'success') {
		header('Location: '.URL.'?page=add_device_device_info&msg=1');
	} else {
		header('Location: '.URL.'?page=add_device_device_info&msg=2&controller_status='.$r['unifi_authorize']['status']);
	}

	exit();
}