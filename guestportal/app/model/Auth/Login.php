<?php
namespace GuestPortal\Auth;

use \GuestPortal\General\GeneralBackend;



/**
 * Login
 *
 * Class for checking login to admin-portal
 *
 * @author Robert Andresen <ra@fosenikt.no>
 */

class Login extends GeneralBackend {

	function __construct()
	{
		parent::__construct();

	}



	public function do_login($username, $password, $autologin=false)
	{
		$r = array();
		$success = true;

		$ad = new \Adldap\Adldap($this->ldap_config_intern);
		

		if (!$autologin) // Dont do AD-auth if autologin
		{
			$auth = $ad->authenticate($username, $password);

			if (!$auth) {
				$success = false;

				$r['status'] = 'error';
				$r['message'] = 'Wrong username and/or password';
				$r['msg_id'] = 01;
				return $r;
			}
		}


		// Get user AD-groups
		$access_groups = $this->get_login_ad_groups();
		$select = ['cn','memberof'];
		$user = $ad->users()->find($username, $select);

		$user_groups = $this->rebuild_user_ad_groups($user->memberof);
		$_SESSION['login_user_ad_groups'] = $user_groups;

		// Check if usergroup and access_groups intersect
		$array_intersect = array_intersect($access_groups, $user_groups);

		if (count($array_intersect) == 0) {
			$success = false;

			$r['status'] = 'error';
			$r['message'] = 'Group access missing';
			$r['msg_id'] = 02;
			return $r;
		}



		if ($success) {
			$r['status'] = 'success';

			$_SESSION['GUEST_PORTAL_AUTH'] = $username;
		}

		return $r;
	}


	public function do_logout()
	{
		unset($_SESSION['GUEST_PORTAL_AUTH']);
	}




	public function get_login_ad_groups()
	{
		$r = array();

		$query = "SELECT * FROM login_ad_groups";
		$result = $this->mysqli->query($query);
		$numRows = $result->num_rows;
		
		while ($row = $result->fetch_array()) {
			$r[] = $row['group_name'];
		}

		return $r;
	}


	private function rebuild_user_ad_groups($groups_arr)
	{
		$user_groups = array();

		if (count($groups_arr) > 0) {
			foreach ($groups_arr as $key => $group_cn_path) {
				$path_arr = explode(',', $group_cn_path);
				$group = $path_arr[0];
				$group = str_replace('CN=', '', $group);

				if (!empty($group)) {
					$user_groups[] = $group;
				}
			}
		}

		return $user_groups;
	}

}