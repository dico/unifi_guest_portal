<?php
namespace GuestPortal\Connectors\Ldap;

require(ABSPATH.'core/packages/adLDAP_intern_4.0.4r2/adLDAP.php');

/**
 * Class description
 *
 * @author Robert Andresen <ra@fosenikt.no>
 */



class Ldap_intern extends GeneralBackend {

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