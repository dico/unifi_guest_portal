<?php
	
// ** PHP settings ** //

/**
	Timezone
	List of supported timezones: http://php.net/manual/en/timezones.php
*/

date_default_timezone_set('Europe/Oslo');


/**
	Error reporting
	List of supported timezones: http://php.net/manual/en/timezones.php
*/

//error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT);
error_reporting(E_ALL & ~E_NOTICE);


/**
	Timeout
*/
set_time_limit(20);





/**
	Set URL

	App folder is defined from URL HOST root (eg. http://mysite/APP_FOLDER)
	Set APP_FOLDER without / on start or end (only between)
*/
define('URL', 'http://192.168.1.210/');
define('URL_GUESTPORTAL', URL.'unifi_guest_portal/guestportal/');
define('URL_UNIFI_SITES', URL.'guest/s/');

// Where to redirect user after guest logon
define('REDIR_URL', 'https://google.com');

// Company name
define('COMPANY_NAME', 'My Company');
define('COMPANY_URL', 'http://mydomain.no');





/**
	Database parameters
*/
define('DB_NAME', 'unifi_guest_portal');	// DB Name
define('DB_USER', 'unifi');					// DB User
define('DB_PASSWORD', 'Test123');			// DB pw
define('DB_HOST', 'localhost');				// DB host



/**
	Unifi controller
*/
define('UNIFI_USER', 'guestmanager');					// SMS Gateway User
define('UNIFI_PASSWORD', 'GuestPortal553');				// SMS Gateway Password
define('UNIFI_SERVER', 'https://192.168.1.100:8443');	// SMS Gateway Server
define('UNIFI_SITE', 'default');						// SMS Gateway Site
define('UNIFI_VERSION', '5.6.26');						// SMS Gateway Server version
define('UNIFI_COOKIE_FILE', '/var/www/html/unifi_guest_portal/tmp/unifi_cookie.txt');	// SMS Gateway Server
//define('UNIFI_DEBUG', true);							// SMS Gateway Debug (Need to be set in the class file)
//define('UNIFI_SSL_VERSION', 1);						// SMS Gateway SSL version (Need to be set in the class file)
define('DEFAULT_AUTH_TIME', 525600);



/**
	SMS Gateway
	The SMS solution is tested with Diafaan SMS Gateway
*/
define('SMS_GATEWAY', 'IP_ADDRESS');				// SMS Gateway Server
define('SMS_PORT', '9710');							// SMS Gateway Port
define('SMS_USER', 'USER');							// SMS Gateway User
define('SMS_PASSWORD', 'PASSWORD');					// SMS Gateway Password
define('SMS_TYPE', 'sms.automatic');				// SMS Gateway Type
define('SMS_API_KEY', 'API_KEY');	// SMS Gateway API key
//define('SMS_URL', 'http://%%gateway%%:%%port%%/http/send-message?username=%%user%%&password=%%password%%&to=%%number%%&message-type=%%type%%&message=%%message%%');	// Diafaan SMS API
define('SMS_URL', 'https://platform.clickatell.com/messages/http/send?apiKey=%%apikey%%&to=%%number%%&content=%%message%%'); // Clickatell SMS API






/**
	LDAP config
	See documentation: https://github.com/Adldap2/Adldap2
*/
$ldap_config[0] = [
	'account_suffix'        => '@acme.corp.org',
	'domain_controllers'    => ['corp-dc1.corp.acme.org'],
	'base_dn'               => 'dc=corp,dc=acme,dc=org',
	'admin_username'        => 'admin',
	'admin_password'        => 'password',
	'use_ssl'               => false
];


$ldap_config[1] = [
	'account_suffix'        => '@acme.corp.org',
	'domain_controllers'    => ['corp-dc1.corp.acme.org'],
	'base_dn'               => 'dc=corp,dc=acme,dc=org',
	'admin_username'        => 'admin',
	'admin_password'        => 'password%',
	'use_ssl'               => false
];






/**
	General config
*/

$config = array (
	'max_allowed_attempts' => 10,
	'blowfish_secret' => 'wernf92348hrtlksdf235u8ghb',
);