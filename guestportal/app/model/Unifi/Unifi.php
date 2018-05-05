<?php
namespace GuestPortal\Unifi;

use \GuestPortal\General\GeneralBackend;



/**
 * Get log and other framework-related content for the Unifi-usage and access
 *
 * @author Robert Andresen <ra@fosenikt.no>
 */

class Unifi extends GeneralBackend {

	function __construct()
	{
		parent::__construct();

	}


	public function get_connection_log($p=array())
	{
		$r = array();

		if (!isset($p['date_from']) || empty($p['date_from'])) {
			$p['date_from'] = date('Y-m-d').' 00:00:00';
		} else {
			list($day, $month, $year) = explode('-', $p['date_from']);
			$p['date_from'] = date('Y-m-d H:i:s', mktime(0,0,0,$month,$day,$year));
		}

		if (!isset($p['date_to']) || empty($p['date_to'])) {
			$p['date_to'] = date('Y-m-d').' 23:59:59';
		} else {
			list($day, $month, $year) = explode('-', $p['date_to']);
			$p['date_to'] = date('Y-m-d H:i:s', mktime(23,59,59,$month,$day,$year));
		}


		$q[] = "(time_created BETWEEN '{$p['date_from']}' AND '{$p['date_to']}')";


		if (isset($p['q']) && !empty($p['q'])) {
			$q[] = "(guest_mac LIKE '%{$p['q']}%' OR username LIKE '%{$p['q']}%')";
		}


		$qString = '';
		$nQ = count($q);
		$c = 0;

		if ($nQ > 0) {
			$qString = 'WHERE ';
			foreach ($q as $key => $value) {
				$qString .= $value;

				$c++;
				if ($c < $nQ) $qString .= ' AND ';
			}
		}

		$query = "SELECT * FROM connections $qString ORDER BY time_created DESC LIMIT 5000";
		$result = $this->mysqli->query($query);
		$numRows = $result->num_rows;
		
		while ($row = $result->fetch_array()) {
			$r[$row['id']]['id'] = $row['id'];
			$r[$row['id']]['time_created'] = $row['time_created'];
			$r[$row['id']]['auth_method'] = $row['auth_method'];
			$r[$row['id']]['username'] = $row['username'];
			$r[$row['id']]['ip_address'] = $row['ip_address'];
			$r[$row['id']]['guest_mac'] = $row['guest_mac'];
			$r[$row['id']]['ap_mac'] = $row['ap_mac'];
			$r[$row['id']]['ssid'] = $row['ssid'];
			$r[$row['id']]['time'] = $row['time'];
			$r[$row['id']]['url'] = $row['url'];

			if ($row['auth_method'] == 'sms') {
				$r[$row['id']]['sms_user'] = $this->get_sms_user($row['username']);
			}
		}

		return $r;
	}


	public function get_sms_user($number)
	{
		$query = "SELECT * FROM mobile_auth AS M WHERE M.number LIKE '$number' LIMIT 1";
		$result = $this->mysqli->query($query);
		$numRows = $result->num_rows;
		
		while ($row = $result->fetch_array()) {
			$r['id'] = $row['id'];
			$r['firstname'] = $row['firstname'];
			$r['lastname'] = $row['lastname'];
		}

		return $r;
	}

}

?>