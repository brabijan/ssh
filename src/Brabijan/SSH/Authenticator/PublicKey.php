<?php

namespace Brabijan\SSH\Authenticator;

class PublicKey implements IAuthenticator
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
	 * @throws \Exception
	 */
	public function authenticate($resource)
	{
		if (!file_exists($this->publicKeyFile) || !is_readable($this->publicKeyFile)) {
			throw new \Exception("Public key file stored in '{$this->publicKeyFile}' was not found or is not readable");
		}
		if (!file_exists($this->privateKeyFile) || !is_readable($this->privateKeyFile)) {
			throw new \Exception("Private key file stored in '{$this->privateKeyFile}' was not found or is not readable");
		}

		if (!@ssh2_auth_pubkey_file($resource, $this->username, $this->publicKeyFile, $this->privateKeyFile, $this->passPhrase)) { // @ prevent warning, on invalid authentication throws exception
			throw new \Exception("Authentication failed for user '{$this->username}' using public key: Username/PublicKey combination invalid");
		}
	}

}