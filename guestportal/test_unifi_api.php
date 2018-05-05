<?php
require_once('../core.php');





echo '<h1>Create object</h1>';
$unifiApi = new GuestPortal\Connectors\UnifiApi\UnifiApi(UNIFI_USER, UNIFI_PASSWORD, UNIFI_SERVER, UNIFI_SITE, UNIFI_VERSION, UNIFI_COOKIE_FILE);

echo '<pre>';
	print_r($unifiApi);
echo '</pre><hr />';







echo '<h1>Login</h1>';
$login = $unifiApi->login();

echo '<pre>';
	print_r($login);
echo '</pre><hr />';





echo '<h1>Authorize guest</h1>';

$mac_address = 'a0:d7:95:b5:9d:14';
$minutes = 10;

$auth_client = $unifiApi->authorize_guest($mac_address, $minutes);

echo '<pre>';
	print_r($auth_client);
echo '</pre><hr />';