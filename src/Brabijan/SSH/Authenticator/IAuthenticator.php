<?php

namespace Brabijan\SSH\Authenticator;

interface IAuthenticator
{

	/**
	 * @param $resource
	 */
	public function authenticate($resource);

}