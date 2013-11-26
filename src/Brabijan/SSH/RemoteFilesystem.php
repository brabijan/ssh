<?php

namespace Brabijan\SSH;

class RemoteFilesystem
{


	/** @var \Brabijan\SSH\Connection */
	private $connection;


	/** @var resource */
	private $sftp;



	public function __construct(Connection $connection)
	{
		$this->connection = $connection;
	}



	/**
	 * @param $localFile
	 * @param $remoteFile
	 * @param int $mode
	 * @return bool
	 */
	public function upload($localFile, $remoteFile, $mode = 0644)
	{
		return ssh2_scp_send($this->connection->getConnection(), $localFile, $remoteFile, $mode);
	}



	/**
	 * @param $remoteFile
	 * @param $localFile
	 * @return bool
	 */
	public function download($remoteFile, $localFile)
	{
		return ssh2_scp_recv($this->connection->getConnection(), $remoteFile, $localFile);
	}



	/**
	 * @param $target
	 * @param $link
	 * @return bool
	 */
	public function createSymlink($target, $link)
	{
		return ssh2_sftp_symlink($this->getSftpResource(), $target, $link);
	}



	/**
	 * @param $dirName
	 * @param int $mode
	 * @param bool $recursive
	 * @return bool
	 */
	public function mkdir($dirName, $mode = 0777, $recursive = FALSE)
	{
		return ssh2_sftp_mkdir($this->getSftpResource(), $dirName, $mode, $recursive);
	}



	/**
	 * @param $filename
	 * @param $content
	 * @return int
	 */
	public function write($filename, $content)
	{
		return file_put_contents($this->buildStreamName($filename), $content);
	}



	/**
	 * @param $filename
	 * @return string
	 */
	public function read($filename)
	{
		return file_get_contents($this->buildStreamName($filename));
	}



	/**
	 * @param $filename
	 */
	public function delete($filename)
	{
		if ($this->isDir($filename)) {
			ssh2_sftp_rmdir($this->getSftpResource(), $filename);
		} else {
			ssh2_sftp_unlink($this->getSftpResource(), $filename);
		}
	}



	/**
	 * @param $from
	 * @param $to
	 * @return bool
	 */
	public function move($from, $to)
	{
		return ssh2_sftp_rename($this->getSftpResource(), $from, $to);
	}



	/**
	 * @param $from
	 * @param $to
	 */
	public function copy($from, $to)
	{
		$this->write($to, $this->read($from));
	}



	/**
	 * @param $filename
	 * @return bool
	 */
	public function fileExists($filename)
	{
		return file_exists($this->buildStreamName($filename));
	}



	/**
	 * @param $dirName
	 * @return bool
	 */
	public function isDir($dirName)
	{
		return is_dir($this->buildStreamName($dirName));
	}



	/**
	 * @param $filename
	 * @param $mode
	 * @return mixed
	 */
	public function chmod($filename, $mode)
	{
		return ssh2_sftp_chmod($this->getSftpResource(), $filename, $mode);
	}



	/**
	 * @param $filename
	 * @return string
	 */
	protected function buildStreamName($filename)
	{
		$sftp = $this->getSftpResource();

		return "ssh2.sftp://$sftp/$filename"; //sprintf('ssh2.sftp://%s/%s', $this->sftp, $filename);
	}



	/**
	 * @return resource
	 */
	protected function getSftpResource()
	{
		if (!$this->sftp) {
			$this->sftp = ssh2_sftp($this->connection->getConnection());
		}

		return $this->sftp;
	}

}