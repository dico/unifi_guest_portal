<?php
namespace GuestPortal\Sms;

use \GuestPortal\General\GeneralBackend;



/**
 * Send SMS
 *
 * @author Robert Andresen <ra@fosenikt.no>
 */

class Sms extends GeneralBackend {

	function __construct($gw, $port, $username, $password, $type, $api_key, $url)
	{
		parent::__construct();

		$this->gateway = $gw;
		$this->port = $port;
		$this->username = $username;
		$this->password = $password;
		$this->sms_type = $type;
		$this->api_key = $api_key;


		// Build URL
		// Replace wildcard parameters
		$url = str_replace('%%gateway%%', $this->gateway, $url);
		$url = str_replace('%%port%%', $this->port, $url);
		$url = str_replace('%%user%%', $this->username, $url);
		$url = str_replace('%%password%%', $this->password, $url);
		$url = str_replace('%%type%%', $this->sms_type, $url);
		$url = str_replace('%%apikey%%', $this->api_key, $url);

		$this->url = $url;

	}





	/**
	* Send SMS
	* 
  	* @author Robert Andresen <ra@fosen-utvikling.no>
  	*
  	* @param    Int 	  	$to  			Mobilenumber
  	* @param    String 	  	$message  		SMS message
  	* @return   Array   	$r				Status array
	*/
	public function sendSMS($to, $message)
	{
		$r = array();

		if (empty($to) || empty($message)) {
			$r['status'] = 'error';
			$r['message'] = 'Number or message is empty';
			return $r;
		}


		$url = $this->url;
		$url = str_replace('%%number%%', $to, $url);
		$url = str_replace('%%message%%', $message, $url);

		//$url = "http://".$this->gateway.":".$this->port."/http/send-message?username=".$this->username."&password=".$this->password."&to=$to&message-type=".$this->sms_type."&message=$message";
		

		//error_log("URL: " . $url);

		//$response = file_get_contents($url);
		$response = rpc($url);

		$response = trim($response);
		$r['response'] = $response;
		$r['url'] = $url;
		

		if (substr($response, 0,2) == 'OK') {
			$r['status'] = 'success';
		} else {
			$r['status'] = 'error';
			$r['message'] = 'SMS Gateway returned an empty or error response while sending the message';
		}

		return $r;
	}

}

?>