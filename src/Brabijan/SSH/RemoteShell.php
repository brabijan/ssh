<?php

namespace Brabijan\SSH;

class RemoteShell
{

	/** @var \Brabijan\SSH\Connection */
	private $connection;



	public function __construct(Connection $connection)
	{
		$this->connection = $connection;
	}



	/**
	 * @param $cmd
	 * @param bool $pty
	 * @param null $env
	 * @return string
	 * @throws RemoteShellCommandException
	 */
	public function exec($cmd, $pty = FALSE, $env = NULL)
	{
		$result = ssh2_exec($this->connection->getConnection(), $cmd, $pty, $env);
		$errorStream = ssh2_fetch_stream($result, SSH2_STREAM_STDERR);
		stream_set_blocking($errorStream, TRUE);
		stream_set_blocking($result, TRUE);
		$error = stream_get_contents($errorStream);
		if (!empty($error)) {
			throw new RemoteShellCommandException($error);
		}

		return stream_get_contents($result);
	}

}



class RemoteShellCommandException extends \RuntimeException
{

}