<?php

namespace Brabijan\SSH\Authenticator;

class Password implements IAuthenticator
{

	/** @var string */
	private $username;

	/** @var string */
	private $password;



	public function __construct($username, $password)
	{
		$this->username = $username;
		$this->password = $password;
	}



	/**
	 * @param $resource
	 */
	public function authenticate($resource)
	{
		return ssh2_auth_password($resource, $this->username, $this->password);
	}

}