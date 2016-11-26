<?php

namespace Paypal\Tests\Unit\ExpressCheckout\Nvp;

class RequestTest extends \PHPUnit_Framework_TestCase
{
	public function testCanInstantiate()
	{
		$request1 = new \Paypal\ExpressCheckout\Nvp\Request(true);
		$this->assertInstanceOf('\Paypal\ExpressCheckout\Nvp\Request', $request1);
		$request2 = new \Paypal\ExpressCheckout\Nvp\Request(false);
		$this->assertInstanceOf('\Paypal\ExpressCheckout\Nvp\Request', $request2);
	}
}