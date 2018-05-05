<?php
namespace GuestPortal\Connectors\UnifiApi;

class UnifiApi {

	public $is_loggedin=false;
	public $debug=true;
	public $ssl_version=1;

	function __construct($user, $password, $server, $site, $controller, $cookie_file="") {
		$this->user = $user;
		$this->password = $password;
		$this->server = $server;
		$this->site = $site;
		$this->controller = $controller;
		if (!empty($cookie_file)) $this->cookie_file = $cookie_file;
		
		if (strpos($controller,".")) {
			$con_ver = explode(".",$controller);
			$controller = $con_ver[0];
		}

		$this->controller = $controller;
	}

	function __destruct() {
		if ($this->is_loggedin) {
			$this->logout();
		}
	}


	




	/**
	* Authorize client on Unifi controller
	* 
  	* @author Robert Andresen <ra@fosen-utvikling.no>
  	*
  	* @param    String 	  	$mac  			Client device MAC address
  	* @param    Int 	  	$minutes  		Num minutes to allow client to use Internet before needed to re-auth
  	* @return   Array   	$r				Status array
	*/
	public function authorize_guest($mac, $minutes=0)
	{
		$r = array();

		$r['mac'] = $mac;
		

		if (empty($mac)) {
			$r['status'] = 'error';
			$r['message'] = 'Cannot authorize empty MAC-address';
		}

		if (isset($_SESSION['controller']['siteid']) && !empty($_SESSION['controller']['siteid'])) {
			$this->site = $_SESSION['controller']['siteid'];
		}
		
		if ($minutes == 0) {
			$minutes = DEFAULT_AUTH_TIME;
		}
		$r['minutes'] = $minutes;



		// Login
		$r['unifi_api_login'] = $this->login();


		// Send user to authorize and the time allowed
		$json = array(
			'cmd'=>'authorize-guest',
			'mac'=>$mac,
			'minutes'=>$minutes);

		$url = $this->server.'/api/s/'.$this->site.'/cmd/stamgr';
		$result = $this->rpc_call($url, $json);
		$return_data = json_decode($result);


		$r['url'] = $url;
		$r['json_payload'] = $json;


		$r['site'] = $this->site;


		if (isset($return_data->meta->rc) && $return_data->meta->rc == 'ok') {
			$r['status'] = 'success';
			$r['message'] = $return_data->meta;
		} else {
			$r['status'] = 'error';
			$r['message'] = $return_data->meta->msg;
		}

		return $r;
	}





	/**
	* De-authorize client on Unifi controller
	* 
  	* @author Robert Andresen <ra@fosen-utvikling.no>
  	*
  	* @param    String 	  	$mac  			Client device MAC address
  	* @return   Array   	$r				Status array
	*/
	public function unauthorize_guest($mac)
	{
		$r = array();

		if (empty($mac)) {
			$r['status'] = 'error';
			$r['message'] = 'Cannot unauthorize empty MAC-address';
		}


		// Login
		$r['unifi_api_login'] = $this->login();


		// Send user to authorize and the time allowed
		$json = array(
			'cmd'=>'unauthorize-guest',
			'mac'=>$mac);

		$url = $this->server.'/api/s/default/cmd/stamgr';
		$result = $this->rpc_call($url, $json);
		$return_data = json_decode($result);

		if (isset($return_data->meta->rc) && $return_data->meta->rc == 'ok') {
			$r['status'] = 'success';
		} else {
			$r['status'] = 'error';
			$r['message'] = $return_data->meta->msg;
		}

		return $r;
	}





	/**
	* List AP's
	* 
  	* @author Robert Andresen <ra@fosen-utvikling.no>
  	*
  	* @return   Array   	$r				Status array
	*/
	public function list_aps()
	{
		$url = $this->server.'/api/s/'.$this->site.'/stat/device';
		$result = $this->rpc_call($url);

		return $this->output($result);
	}






	/**
	* List guests
	* 
  	* @author Robert Andresen <ra@fosen-utvikling.no>
  	*
  	* @return   Array   	$r				Status array
	*/
	public function list_guests()
	{
		$url = $this->server."/api/s/".$this->site."/stat/guest";
		$result = $this->rpc_call($url);
		return $this->output($result);
	}






	/**
	* List sites
	* 
  	* @author Robert Andresen <ra@fosen-utvikling.no>
  	*
  	* @return   Array   	$r				Status array
	*/
	public function list_sites()
	{
		$url = $this->server."/api/s/".$this->site."/api/self/sites";
		$result = $this->rpc_call($url);
		return $this->output($result);
	}






	/**
	* Login to controller
	* 
  	* @author Robert Andresen <ra@fosen-utvikling.no>
  	*
  	* @return   Array   	$r				Status array
	*/
	public function login()
	{
		$url = $this->server."/api/login";
		$json = array("username" => $this->user,"password" => $this->password);

		$result = $this->rpc_call($url, $json);
		$return_data = json_decode($result);

		if (isset($return_data->meta->rc) && $return_data->meta->rc == 'ok') {
			$login = true;
		} else {
			$login = false;
		}

		return $login;
	}






	/**
	* Logout of controller
	* 
  	* @author Robert Andresen <ra@fosen-utvikling.no>
  	*
  	* @return   Array   	$r				Status array
	*/
	private function logout()
	{
		$url = $this->server."/logout";
		$result = $this->rpc_call($url);
		return $this->output($result);
	}






	/**
	* Data output formating
	* 
  	* @author Robert Andresen <ra@fosen-utvikling.no>
  	*
  	* @param   	String   	$data			JSON data in
  	* @return   Array   	$r				Array out
	*/
	private function output($data)
	{
		$content_decoded = json_decode($data);

		if (isset($content_decoded->meta->rc)) {
			if ($content_decoded->meta->rc == "ok") {
				if (is_array($content_decoded->data)) {
					foreach ($content_decoded->data as $guest) {
						$return[]=$guest;
					}
				}
			}
		}

		return $return;
	}





	/**
	* Authorize a guest by mac address.
	*
	* @param string $site
	* @param string $mac the mac address of the guest to authorize.
	* @param int $minutes number of minutes to authorize guest.
	* @param array $data associative array with extra data, i.e. up (kbps), down (kbps), bytes (MB)
	*
	* @return ResponseInterface
	* @throws GuzzleException
	*/
	public function authorize_guest_by_mac($site, $mac, $minutes, array $data = [])
	{

		// Send user to authorize and the time allowed
		$json = array(
			'cmd'=>'authorize-guest',
			'mac'=>$mac,
			'minutes'=>$minutes);

		$url = $this->server.'/api/s/'.$site.'/cmd/stamgr';
		$result = $this->rpc_call($url, $json);
		$return_data = json_decode($result);


		$r['url'] = $url;
		$r['json_payload'] = $json;

		if (isset($return_data->meta->rc) && $return_data->meta->rc == 'ok') {
			$r['status'] = 'success';
			$r['message'] = $return_data->meta;
		} else {
			$r['status'] = 'error';
			$r['message'] = $return_data->meta->msg;
		}

		return $r;


	}






	/**
	* RPC call to controller
	* 
  	* @author Robert Andresen <ra@fosen-utvikling.no>
  	*
  	* @param   	String   	$data			URL to controller
  	* @param   	String   	$json			JSON data in
  	* @return   		   	$result			Result data from rpc call
	*/
	private function rpc_call($url, $json=array())
	{

		$url = str_replace('%%server%%', $this->server, $url);
		$url = str_replace('%%site%%', $this->site, $url);


		// Start Curl
		$ch = curl_init();

		// We are posting data
		curl_setopt($ch, CURLOPT_POST, TRUE);

		// Set up cookies
		//$cookie_file = "/tmp/unifi_cookie";
		curl_setopt($ch, CURLOPT_COOKIEJAR, $this->cookie_file); // Set cookie
		curl_setopt($ch, CURLOPT_COOKIEFILE, $this->cookie_file); // Read cookie


		// Allow Self Signed Certs
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_SSLVERSION, $this->ssl_version);

		curl_setopt($ch , CURLOPT_RETURNTRANSFER, true);


		// Send user to authorize and the time allowed
		$data = json_encode($json);


		// Make the API Call
		curl_setopt($ch, CURLOPT_URL, $url);
		//curl_setopt($ch, CURLOPT_POSTFIELDS, 'json='.$data);
		//curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		
		if (trim($data) != "") {
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		} else {
			curl_setopt($ch, CURLOPT_POST, FALSE);
		}

		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		$result = curl_exec($ch);

		curl_close($ch);

		return $result;
	}

}