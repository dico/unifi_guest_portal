<?php
namespace GuestPortal\General;



/**
 * Connect to database and other services
 *
 * @author Robert Andresen <ra@fosenikt.no>
 */
class GeneralBackend
{

	function __construct()
	{
		global $mysqli;
		$this->mysqli = $mysqli;

		//global $ldap_config[0];
		//$this->ldap_config_admin = $ldap_config[0];

		//global $ldap_config[1];
		//$this->ldap_config_school = $ldap_config[1];
	}






	/**
	* Log events
	* 
  	* @author Robert Andresen <ra@fosen-utvikling.no>
  	*
  	* @param    Array 	  	$p  		Log parameters
  	* @return   Array   	$r			Status array
	*/
	public function log_event($p)
	{
		$r = array();

		if (isset($p['raw_data']) && !empty($p['raw_data'])) {
			if (is_array($p['raw_data'])) {
				$p['raw_data'] = serialize($p['raw_data']);
			}
		}

		$query = "INSERT INTO system_log SET 
					time_created='". date('Y-m-d H:i:s') ."', 
					user='".$p['user']."', 
					title='".$p['title']."', 
					description='".$p['description']."', 
					raw_data='".$p['raw_data']."', 
					ip_address='". $_SERVER['REMOTE_ADDR'] ."', 
					unifi_guest_mac='". $p['unifi_guest_mac'] ."', 
					unifi_ap_mac='". $p['unifi_ap_mac'] ."', 
					unifi_ssid='". $p['unifi_ssid'] ."'";
		$result = $this->mysqli->query($query);

		if ($result) {
			$r['status'] = 'success';
		} else {
			$r['status'] = 'error';
			$r['message'] = 'DB error';
		}

		return $r;
	}





	/**
	* Get log events
	* 
  	* @author Robert Andresen <ra@fosen-utvikling.no>
  	*
  	* @return   Array   	$r					Status array
	*/
	public function get_log_events()
	{
		$r = array();

		$query = "SELECT * FROM system_log ORDER BY time_created DESC LIMIT 1000";
		$result = $this->mysqli->query($query);
		

		while($row = $result->fetch_array())
		{
			$r[$row['id']]['id'] = $row['id'];
			$r[$row['id']]['time_created'] = $row['time_created'];
			$r[$row['id']]['user'] = $row['user'];
			$r[$row['id']]['title'] = $row['title'];
			$r[$row['id']]['description'] = $row['description'];
			$r[$row['id']]['raw_data'] = $row['raw_data'];
			$r[$row['id']]['ip_address'] = $row['ip_address'];
			$r[$row['id']]['unifi_guest_mac'] = $row['unifi_guest_mac'];
			$r[$row['id']]['unifi_ap_mac'] = $row['unifi_ap_mac'];
			$r[$row['id']]['unifi_ssid'] = $row['unifi_ssid'];
		}

		return $r;
	}





	/**
	* Get single log event
	* 
  	* @author Robert Andresen <ra@fosen-utvikling.no>
  	*
  	* @return   Array   	$r					Status array
	*/
	public function get_log_event($id)
	{
		$r = array();

		$query = "SELECT * FROM system_log WHERE id='$id'";
		$result = $this->mysqli->query($query);
		

		while($row = $result->fetch_array())
		{
			$r['id'] = $row['id'];
			$r['time_created'] = $row['time_created'];
			$r['user'] = $row['user'];
			$r['title'] = $row['title'];
			$r['description'] = $row['description'];
			$r['raw_data'] = $row['raw_data'];
			$r['ip_address'] = $row['ip_address'];
			$r['unifi_guest_mac'] = $row['unifi_guest_mac'];
			$r['unifi_ap_mac'] = $row['unifi_ap_mac'];
			$r['unifi_ssid'] = $row['unifi_ssid'];
		}

		return $r;
	}

}

?>