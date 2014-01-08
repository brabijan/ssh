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
	 * @param resource $resource
	 * @throws \Exception
	 */
	public function authenticate($resource)
	{
		if (!@ssh2_auth_password($resource, $this->username, $this->password)) { // @ prevent warning, on invalid authentication throws exception
			throw new \Exception("Authentication failed for user '{$this->username}' using public key: Username/Password combination invalid");
		}
	}

}