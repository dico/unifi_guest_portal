<?php
require_once('../../../../core.php');


if (isset($_POST['_csrf']) && $_POST['_csrf'] != $_SESSION['_csrf']) {
	header('Location: '.URL.'?start&msg=8');
	exit();
}





if ($_GET['action'] == 'add_room')
{
	$p['location_id'] = $_POST['selInstitution'];
	$p['lastname'] = $_POST['inputLastname'];
	$p['room'] = $_POST['inputRoom'];

	$result = $objPatient->add_room($p);
	output($result, $redir);
}


if ($_GET['action'] == 'edit_room')
{
	$p['id'] = $_GET['id'];
	$p['location_id'] = $_POST['selInstitution'];
	$p['room'] = $_POST['inputRoom'];

	$result = $objPatient->edit_room($p);
	output($result, $redir);
}




if ($_GET['action'] == 'delete_room')
{
	$result = $objPatient->delete_room($_GET['id']);
	output($result);
}









if ($_GET['action'] == 'add_institution')
{
	$p['name'] = $_POST['inputName'];

	$result = $objPatient->add_institution($p);
	output($result, $redir);
}


if ($_GET['action'] == 'edit_institution')
{
	$p['id'] = $_GET['id'];
	$p['name'] = $_POST['inputName'];

	$result = $objPatient->edit_institution($p);
	output($result, $redir);
}


if ($_GET['action'] == 'delete_institution')
{
	$result = $objPatient->delete_institution($_GET['id']);
	output($result, $redir);
}