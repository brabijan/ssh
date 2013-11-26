<?php

namespace Brabijan\SSH;

use Brabijan\SSH\Authenticator\IAuthenticator;

class Connection
{

	/** @var string */
	private $host;

	/** @var string */
	private $port;

	/** @var resource */
	private $connection;

	/** @var IAuthenticator */
	private $authenticator;

	/** @var RemoteShell */
	private $remoteShell;

	/** @var RemoteFilesystem */
	private $remoteFilesystem;



	public function __construct($host = NULL, $port = 22)
	{
		$this->host = $host;
		$this->port = $port;
	}



	/**
	 * @param $host
	 * @param int $port
	 */
	public function setHost($host, $port = 22)
	{
		$this->closeConnection();
		$this->host = $host;
		$this->port = $port;
	}



	/**
	 * @param IAuthenticator $authenticator
	 */
	public function setAuthenticator(IAuthenticator $authenticator)
	{
		$this->authenticator = $authenticator;
	}



	/**
	 * @return resource
	 */
	public function getConnection()
	{
		if (!$this->connection) {
			$this->connection = $this->connect();
		}

		return $this->connection;
	}



	/**
	 * @return RemoteShell
	 */
	public function getRemoteShell()
	{
		if (!$this->remoteShell) {
			$this->remoteShell = new RemoteShell($this);
		}

		return $this->remoteShell;
	}



	/**
	 * @return RemoteFilesystem
	 */
	public function getRemoteFilesystem()
	{
		if (!$this->remoteFilesystem) {
			$this->remoteFilesystem = new RemoteFilesystem($this);
		}

		return $this->remoteFilesystem;
	}



	/**
	 * @return resource
	 */
	private function connect()
	{
		$connection = ssh2_connect($this->host, $this->port);

		// @todo overovat, jestli authenticator existuje
		$this->authenticator->authenticate($connection);

		return $connection;
	}



	private function closeConnection()
	{
		$this->connection = null;
		$this->remoteShell = null;
		$this->remoteFilesystem = null;
	}

}