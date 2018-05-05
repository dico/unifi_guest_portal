<?php
namespace GuestPortal\Auth;

use \GuestPortal\General\GeneralBackend;
use \GuestPortal\Sms\Sms;
use \GuestPortal\Unifi\Unifi;
use \GuestPortal\Connectors\UnifiApi\UnifiApi;



/**
 * Auth
 * 
 * Manage login for guest access.
 * If login granted, the function will authorize user in Unifi controller.
 *
 * @author Robert Andresen <ra@fosenikt.no>
 */

class Auth extends GeneralBackend {

	function __construct()
	{
		parent::__construct();

		$this->Sms = new Sms(SMS_GATEWAY, SMS_PORT, SMS_USER, SMS_PASSWORD, SMS_TYPE, SMS_API_KEY, SMS_URL);
		$this->Unifi = new Unifi;
		$this->UnifiApi = new UnifiApi(UNIFI_USER, UNIFI_PASSWORD, UNIFI_SERVER, UNIFI_SITE, UNIFI_VERSION, UNIFI_COOKIE_FILE);
		//$this->Ldap_school = new Ldap_school;
		//$this->Ldap_intern = new Ldap_intern;
	}





	/**
	* Log login attempt
	* 
  	* @author Robert Andresen <ra@fosen-utvikling.no>
  	*
  	* @param    String 	  	$client_mac  		Device MAC
  	* @param    Int 	  	$status  			Status ID
  	* @return   Int   		$r   				Response array with data
	*/
	public function set_login_attempt($client_mac, $status)
	{
		$r = array();

		$status = ($status) ? 1 : 0;

		// Log attempt
		$query = "INSERT INTO login_attempt SET 
					client_mac='".$client_mac."', 
					time_attempt='". date('Y-m-d H:i:s') ."',
					status='".$status."'";
		$result = $this->mysqli->query($query);

		if ($result) {
			$r['status'] = 'success';
		} else {
			$r['status'] = 'error';
		}

		return $r;
	}





	/**
	* Fetch login attempts for the last 5 minutes
	* Used to prevent hack attempts / bruteforce
	* 
  	* @author Robert Andresen <ra@fosen-utvikling.no>
  	*
  	* @param    String 	  	$client_mac  		Device MAC
  	* @return   Int   		  					Num attempts
	*/
	public function get_login_attempts($client_mac)
	{
		// Return attempt count
		$query = "SELECT count(client_mac) FROM login_attempt WHERE time_attempt > date_sub(now(), interval 5 minute) AND status='0'";
		$result = $this->mysqli->query($query);
		$row = $result->fetch_array();

		return $row['count(client_mac)'];
	}





	/**
	* Send one-time code to user
	* 
  	* @author Robert Andresen <ra@fosen-utvikling.no>
  	*
  	* @param    Array 	  	$p  		$p['auth_id'], $p['firstname'], $p['lastname'], $p['mobile'], 
  	* @return   Int   		$r   		Response array with data
	*/
	public function mobile_auth_send_code($p)
	{
		$r = array();
		$r['params'] = $p;

		// Generate a random code
		// and sends code by SMS
		$code = rand(11111,99999);
		$message = "$code";
		$r['sms_status'] = $this->Sms->sendSMS($p['mobile'], $message);

		// Log parameters
		$logParams['user'] = $p['mobile'];


		// Uncomment return to validate SMS response for continue
		// Can be commented out for e.g. debugging purposes
		if ($r['sms_status']['status'] != 'success') {
			//return $r;
		}

		// Log attempt
		if (!isset($p['auth_id']) && empty($p['auth_id'])) {
			$r['type'] = 'New code';

			$query = "INSERT INTO mobile_auth SET 
						time_created='". date('Y-m-d H:i:s') ."', 
						firstname='".$p['firstname']."', 
						lastname='".$p['lastname']."', 
						number='".$p['mobile']."', 
						code='".$code."', 
						status='". 0 ."', 
						client_ip='". $_SERVER['REMOTE_ADDR'] ."', 
						client_mac='". $_SESSION['controller']['id'] ."', 
						unifi_ap='". $_SESSION['controller']['ap'] ."'";
			$result = $this->mysqli->query($query);

			$r['auth_id'] = $this->mysqli->insert_id;

			$logParams['title'] = 'SMS code sent';
		}

		// If re-send code
		else {
			$r['type'] = 'Resend code';

			$query = "UPDATE mobile_auth SET 
						time_created='". date('Y-m-d H:i:s') ."',
						code='".$code."', 
						WHERE id='".$p['auth_id']."'";
			$result = $this->mysqli->query($query);

			$r['auth_id'] = $p['auth_id'];

			$logParams['title'] = 'SMS code sent (Resend)';
		}


		// Feedback reponse
		if ($result) {
			$r['status'] = 'success';

			$logParams['description'] = 'SMS code wrote successfully to database.';
		} else {
			$r['status'] = 'error';
			$r['message'] = 'Could not write to DB';

			$logParams['title'] = 'ERROR: Could not write SMS code to database';
		}


		// Log event
		$logParams['raw_data'] = $r;
		$r['log_event'] = $this->log_event($logParams);

		// Return feedback response
		return $r;
	}





	/**
	* Log connection of successful
	* 
  	* @author Robert Andresen <ra@fosen-utvikling.no>
  	*
  	* @param    String 	  	$auth_method  		Auth method (sms, room...)
  	* @param    String 	  	$username  			Username / Mobilenumber (not required)
  	* @param    String 	  	$result  			Unifi result feedback (not required)
  	* @return   Array   	$r					Status array
	*/
	public function log_connection($auth_method, $username='', $result='')
	{
		$r = array();

		$query = "INSERT INTO connections SET 
					time_created='". date('Y-m-d H:i:s') ."', 
					auth_method='".$auth_method."', 
					username='".$username."', 
					ip_address='".$_SERVER['REMOTE_ADDR']."', 
					guest_mac='".$_SESSION['controller']['id']."', 
					ap_mac='".$_SESSION['controller']['ap']."', 
					ssid='".$_SESSION['controller']['ssid']."', 
					time='". date('Y-m-d H:i:s', $_SESSION['controller']['time']) ."', 
					url='". $_SESSION['controller']['refURL'] ."', 
					controller_feedback='". serialize($result) ."'";
		$result = $this->mysqli->query($query);

		if ($result) {
			$r['status'] = 'success';
		} else {
			$r['status'] = 'error';
			$r['message'] = 'Could not write to DB';
		}

		return $r;
	}





	/**
	* Confirm SMS code
	* 
  	* @author Robert Andresen <ra@fosen-utvikling.no>
  	*
  	* @param    String 	  	$auth_method  		$p['auth_id'], $p['code']
  	* @return   Array   	$r					Status array
	*/
	public function mobile_auth_confirm_code($p)
	{

		// Check input parameters
		if (empty($p['auth_id']) || empty($p['code'])) {
			$r['sms_auth']['status'] = 'error';
			$r['sms_auth']['message'] = 'Auth and/or sms code missing';

			$logParams['raw_data'] = $r;
			$r['log_event'] = $this->log_event($logParams);

			return $r;
		}


		// Check code and auth ID
		$query = "SELECT * FROM mobile_auth WHERE id='{$p['auth_id']}' AND code='{$p['code']}' AND time_created >= DATE_SUB(NOW(),INTERVAL 1 HOUR);";
		$result = $this->mysqli->query($query);
		$numRows = $result->num_rows;
		$auth_data = $result->fetch_array();
		
		// Log params
		$logParams['user'] = $auth_data['number'];
		$logParams['unifi_guest_mac'] = $auth_data['client_mac'];
		$logParams['unifi_ap_mac'] = $auth_data['unifi_ap'];


		// If no SMS record found with number and code
		if ($numRows == 0) {
			$r['sms_auth']['status'] = 'error';
			$r['sms_auth']['message'] = 'Wrong or expired code';

			
			$logParams['title'] = 'Wrong or expired SMS-code';
			$logParams['raw_data'] = $r;
			$r['log_event'] = $this->log_event($logParams);

			$r['log_attempt'] = $this->set_login_attempt($_SESSION['controller']['id'], 0);

			return $r;
		}

		// If SMS auth => Success
		else {
			$r['sms_auth']['status'] = 'success';

			$query = "UPDATE mobile_auth SET status='1' WHERE id='".$p['auth_id']."'";
			$result = $this->mysqli->query($query);

			$r['log_attempt'] = $this->set_login_attempt($_SESSION['controller']['id'], 1);
		}



		// Login to unifi controller
		$r['unifi_api_login'] = $this->UnifiApi->login();
		
		// Authorize with Unifi controller
		$minutes = (60 * 24);
		//$r['unifi_authorize'] = $this->Unifi->authorize($_SESSION['controller']['id'], $minutes);
		$r['unifi_authorize'] = $this->UnifiApi->authorize_guest($_SESSION['controller']['id'], $minutes);
		

		// Set feedback
		if ($r['unifi_authorize']['status'] == 'success') {
			$r['status'] = 'success';
			$logParams['title'] = 'Guest authorize success (SMS)';

			$r['log_connection'] = $this->log_connection('sms', $auth_data['number'], $r['unifi_authorize']);
		} else {
			$r['status'] = 'error';
			$r['message'] = 'Controller did not accept request';

			$logParams['title'] = 'Guest authorize error (SMS)';
		}


		// Log event
		$logParams['raw_data'] = $r;
		$r['log_event'] = $this->log_event($logParams);

		return $r;
	}





	/**
	* Auth room login
	* 
  	* @author Robert Andresen <ra@fosen-utvikling.no>
  	*
  	* @param    String 	  	$lastname  		Users lastname
  	* @param    Int 	  	$pin  			Pin-code (static from e.g. front desk)
  	* @return   Array   	$r				Status array
	*/
	public function room_auth_user($lastname, $pin)
	{


		$query = "SELECT * FROM rooms WHERE lastname LIKE '$lastname' AND pin='$pin'";
		$result = $this->mysqli->query($query);
		$numRows = $result->num_rows;


		// If AD-auth OK
		if ($numRows != 0) {
			$success = true;

			// Log into Unifi-controller
			$r['unifi_api_login'] = $this->UnifiApi->login();

			// Auth client
			$minutes = (60 * 24 * 365);
			//$r['unifi_api_authorize'] = $this->Unifi->authorize($_SESSION['controller']['id'], $minutes);
			$r['unifi_api_authorize'] = $this->UnifiApi->authorize_guest($_SESSION['controller']['id'], $minutes);


			// Check if unifi returned an error or not
			if ($r['unifi_api_authorize']['status'] == 'success') {
				$r['status'] = 'success';
				$r['log_connection'] = $this->log_connection('login', "PASIENT\\\\".maskName($username));

				$logParams['title'] = 'Guest authorize success (Pasient)';
			} else {
				$r['status'] = 'error';
				$r['message'] = 'Unifi-controller returned an error';

				$logParams['title'] = 'Guest authorize error (Pasient)';
			}

		}

		else {
			$r['status'] = 'error';
			$r['message'] = 'Wrong username and/or password';

			$logParams['title'] = 'Wrong username and/or password (Pasient)';
		}


		// Log attempt
		$r['log_attempt'] = $this->set_login_attempt($_SESSION['controller']['id'], $success);


		// Log event
		$logParams['raw_data'] = $r;
		$r['log_event'] = $this->log_event($logParams);

		return $r;
	}








	/*public function intern_auth_user($username, $password)
	{
		$r = array();
		$success = false;
		
		$ad = new \Adldap\Adldap($this->ldap_config_intern);
		$r['ad_auth'] = $ad->authenticate($username, $password);

		// Log parameters
		$logParams['user'] = "LOGIN\\\\$username";


		// If AD-auth OK
		if ($r['ad_auth']) {
			$success = true;

			// Log into Unifi-controller
			$r['unifi_api_login'] = $this->UnifiApi->login();

			// Auth client
			$minutes = (60 * 24 * 365);
			//$r['unifi_api_authorize'] = $this->Unifi->authorize($_SESSION['controller']['id'], $minutes);
			$r['unifi_api_authorize'] = $this->UnifiApi->authorize_guest($_SESSION['controller']['id'], $minutes);


			// Check if unifi returned an error or not
			if ($r['unifi_api_authorize']['status'] == 'success') {
				$r['status'] = 'success';
				$r['log_connection'] = $this->log_connection('login', "LOGIN\\\\$username");

				$logParams['title'] = 'Guest authorize success (Login AD)';
			} else {
				$r['status'] = 'error';
				$r['message'] = 'Unifi-controller returned an error';

				$logParams['title'] = 'Guest authorize error (Login AD)';
			}

		}

		else {
			$r['status'] = 'error';
			$r['message'] = 'Wrong username and/or password';

			$logParams['title'] = 'Wrong username and/or password (Login AD)';
		}


		// Log attempt
		$r['log_attempt'] = $this->set_login_attempt($_SESSION['controller']['id'], $success);


		// Log event
		$logParams['raw_data'] = $r;
		$r['log_event'] = $this->log_event($logParams);

		return $r;
	}*/



	/*public function school_auth_user($username, $password)
	{
		$r = array();
		
		$ad = new \Adldap\Adldap($this->ldap_config_school);
		$r['auth'] = $ad->authenticate($username, $password);


		// Log parameters
		$logParams['user'] = "LOGIN\\\\$username";


		if ($r['auth']) {
			$r['status'] = 'success';
			$success = true;



			$minutes = (60 * 24 * 365);
			//$r['unifi_authorize'] = $this->Unifi->authorize($_SESSION['controller']['id'], $minutes);
			$r['unifi_api_authorize'] = $this->UnifiApi->authorize_guest($_SESSION['controller']['id'], $minutes);
			//$r['log_connection'] = $this->log_connection('skole', "SKOLE\\$username");
			//$r['log_attempt'] = $this->set_login_attempt($_SESSION['controller']['id'], 1);

			// Check if unifi returned an error or not
			if ($r['unifi_api_authorize']['status'] == 'success') {
				$r['status'] = 'success';
				$r['log_connection'] = $this->log_connection('skole', "SKOLE\\\\$username");

				$logParams['title'] = 'Guest authorize success (Skole AD)';
			} else {
				$r['status'] = 'error';
				$r['message'] = 'Unifi-controller returned an error';

				$logParams['title'] = 'Guest authorize error (Skole AD)';
			}
		}

		else {
			$r['status'] = 'error';
			$r['message'] = 'Wrong username and/or password';

			$r['log_attempt'] = $this->set_login_attempt($_SESSION['controller']['id'], 0);

			$logParams['title'] = 'Wrong username and/or password (Skole AD)';
		}


		// Log attempt
		$r['log_attempt'] = $this->set_login_attempt($_SESSION['controller']['id'], $success);

		// Log event
		$logParams['raw_data'] = $r;
		$r['log_event'] = $this->log_event($logParams);

		return $r;
	}*/


	/*public function intern_auth_user_only($username, $password)
	{
		$r = array();
		
		$ad = new \Adldap\Adldap($this->ldap_config_intern);
		$r['auth'] = $ad->authenticate($username, $password);

		if ($r['auth']) {
			$r['status'] = 'success';
		}
		
		else {
			$r['status'] = 'error';
			$r['message'] = 'Wrong username and/or password';

			$r['log_attempt'] = $this->set_login_attempt($_SESSION['controller']['id'], 0);

			$logParams['title'] = 'Wrong username and/or password (Skole AD)';
		}

		// Log attempt
		$r['log_attempt'] = $this->set_login_attempt($_SESSION['controller']['id'], $success);

		return $r;
	}*/


	/*public function school_auth_user_only($username, $password)
	{
		$r = array();
		
		$ad = new \Adldap\Adldap($this->ldap_config_school);
		$r['auth'] = $ad->authenticate($username, $password);

		if ($r['auth']) {
			$r['status'] = 'success';
		}

		else {
			$r['status'] = 'error';
			$r['message'] = 'Wrong username and/or password';

			$r['log_attempt'] = $this->set_login_attempt($_SESSION['controller']['id'], 0);

			$logParams['title'] = 'Wrong username and/or password (Skole AD)';
		}

		// Log attempt
		$r['log_attempt'] = $this->set_login_attempt($_SESSION['controller']['id'], $success);

		return $r;
	}*/

}

?>