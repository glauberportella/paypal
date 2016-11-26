<?php

namespace Paypal\Common;

abstract class Request
{
	protected $sandbox;
	protected $sandboxBaseEndpoint = 'https://api-3t.sandbox.paypal.com';
	protected $productionBaseEndpoint = 'https://api-3t.paypal.com';

	public function __construct($sandbox = false)
	{
		$this->sandbox = $sandbox;
	}

	public function getBaseEndpoint()
	{
		return $this->sandbox ? $this->sandboxBaseEndpoint : $this->productionBaseEndpoint;
	}

	public function isSandbox()
	{
		return $this->sandbox;
	}

	abstract public function send(array $request);
}