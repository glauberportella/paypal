<?php

namespace Paypal\Security;

class Credential
{
	protected $user;
	protected $pwd;
	protected $signature;

	public function __construct($user, $pwd, $signature)
	{
		$this->user = $user;
		$this->pwd = $pwd;
		$this->signature = $signature;
	}

	public function getUser()
	{
		return $this->user;
	}

	public function getPwd()
	{
		return $this->pwd;
	}

	public function getSignature()
	{
		return $this->signature;
	}
}