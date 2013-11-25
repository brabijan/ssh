<?php

namespace Brabijan\SSH\Authenticator;

use Nette;

class PublicKey extends Nette\Object implements IAuthenticator
{

	/** @var string */
	private $username;

	/** @var string path to public key file */
	private $publicKeyFile;

	/** @var string path to private key file */
	private $privateKeyFile;

	/** @var string|null */
	private $passPhrase;



	public function __construct($username, $publicKeyFile, $privateKeyFile, $passPhrase = NULL)
	{
		$this->username = $username;
		$this->publicKeyFile = $publicKeyFile;
		$this->privateKeyFile = $privateKeyFile;
		$this->passPhrase = $passPhrase;
	}



	/**
	 * @param resource $resource
	 */
	public function authenticate($resource)
	{
		return ssh2_auth_pubkey_file(
			$resource,
			$this->username,
			$this->publicKeyFile,
			$this->privateKeyFile,
			$this->passPhrase
		);
	}

}