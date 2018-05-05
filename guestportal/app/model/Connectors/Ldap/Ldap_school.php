<?php
namespace GuestPortal\Connectors\Ldap;

require(ABSPATH.'core/packages/adLDAP_skole_4.0.4r2/adLDAP.php');

/**
 * Class description
 *
 * @author Robert Andresen <ra@fosenikt.no>
 */



class Ldap_school extends GeneralBackend {

	function __construct()
	{
		parent::__construct();

		$this->adldap = new \adLDAP();
	}



	function auth_user($username, $password)
	{
		$result = $this->adldap->authenticate($username, $password);
		return $result;
	}

}

?>