<?php

namespace Paypal\Tests\Unit\ExpressCheckout\Nvp;

class ExpressCheckoutTest extends \PHPUnit_Framework_TestCase
{
	public function testCanInstantiate()
	{
		$credential = new \Paypal\Security\Credential('glauberportella@gmail.com', 'teste', 'teste123');
		$this->assertInstanceOf('\Paypal\Security\Credential', $credential);

		$expchkt = new \Paypal\ExpressCheckout\Nvp\ExpressCheckout($credential);
		$this->assertInstanceOf('\Paypal\ExpressCheckout\Nvp\ExpressCheckout', $expchkt);
	}
}