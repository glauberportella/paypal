<?php
// Copyright (c) 2016 Glauber Portella <glauberportella@gmail.com>

// Permission is hereby granted, free of charge, to any person obtaining a
// copy of this software and associated documentation files (the "Software"),
// to deal in the Software without restriction, including without limitation
// the rights to use, copy, modify, merge, publish, distribute, sublicense,
// and/or sell copies of the Software, and to permit persons to whom the
// Software is furnished to do so, subject to the following conditions:

// The above copyright notice and this permission notice shall be included in
// all copies or substantial portions of the Software.

// THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
// IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
// FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
// AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
// LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
// FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
// DEALINGS IN THE SOFTWARE.

namespace Paypal\ExpressCheckout\Nvp;

use Paypal\ExpressCheckout\Nvp\Request;
use Paypal\Security\Credential;

class ExpressCheckout
{
	const VERSION = '108.0';
	const EXPRESS_CHECKOUT_CMD = '_express-checkout';

	protected $credential;

	public function __construct(Credential $credential)
	{
		$this->credential = $credential;
	}

	/**
	 * Paypal NVP SetExpressCheckout method
	 * 
	 * @param array   $requestData Paypal ExpressCheckout NVP request data for SetExpressCheckout
	 *                             data without API credential info and method, the sdk will 
	 *                             append it to the request data
	 * @param Request $request     The sdk NVP request instance
	 * 
	 * @return string The Redirect URL to redirect the user
	 * 
	 * @throws Paypal\Exception\RequestException On request failure will throw RequestException
	 */
	public function setExpressCheckout(array $requestData, Request $request)
	{
		$this->prepareRequestData($requestData, 'SetExpressCheckout');

		// if something go wrong in request it will throw a RequestException
		$responseNvp = $request->send($requestData);
		$query = array(
	        'cmd' => self::EXPRESS_CHECKOUT_CMD,
	        'token' => $responseNvp['TOKEN'],
	    );
	 
	    $redirectURL = sprintf('%s?%s', $paypalURL, http_build_query($query));
	 
	    return $redirectURL;
	}

	/**
	 * Paypal NVP GetExpressCheckoutDetails method
	 * 
	 * @param array   $requestData Paypal ExpressCheckout NVP request data 
	 *                             for GetExpressCheckoutDetails data without API credential 
	 *                             info and method, the sdk will append it to the request data
	 * @param Request $request     The sdk NVP request instance
	 * 
	 * @return array The Response data
	 * 
	 * @throws Paypal\Exception\RequestException On request failure will throw RequestException
	 */
	public function getExpressCheckoutDetails(array $requestData, Request $request)
	{
		$this->prepareRequestData($requestData, 'GetExpressCheckoutDetails');

		$responseNvp = $request->send($requestData);

		return $responseNvp;
	}

	/**
	 * Paypal NVP DoExpressCheckoutPayment method
	 * 
	 * @param array   $requestData Paypal ExpressCheckout NVP request data 
	 *                             for DoExpressCheckoutPayment data without API credential 
	 *                             info and method, the sdk will append it to the request data
	 * @param Request $request     The sdk NVP request instance
	 * 
	 * @return array The Response data
	 * 
	 * @throws Paypal\Exception\RequestException On request failure will throw RequestException
	 */
	public function doExpressCheckoutPayment(array $requestData, Request $request)
	{
		$this->prepareRequestData($requestData, 'DoExpressCheckoutPayment');

		$responseNvp = $request->send($requestData);

		return $responseNvp;
	}

	/**
	 * Paypal NVP RefundTransaction method
	 * 
	 * @param array   $requestData Paypal ExpressCheckout NVP request data 
	 *                             for RefundTransaction data without API credential 
	 *                             info and method, the sdk will append it to the request data
	 * @param Request $request     The sdk NVP request instance
	 * 
	 * @return array The Response data
	 * 
	 * @throws Paypal\Exception\RequestException On request failure will throw RequestException
	 */

	public function refundTransaction(array $requestData, Request $request)
	{
		$this->prepareRequestData($requestData, 'RefundTransaction');

		$responseNvp = $request->send($requestData);

		return $responseNvp;
	}

	/**
	 * Common prepare data for the request
	 * 
	 * @param  array  &$requestData The request data array to prepare
	 * @param  string $method       The ExpressCheckout NVP method name
	 * @return void
	 */
	private function prepareRequestData(array &$requestData, $method)
	{
		$paypalUrl = $request->isSandbox() ? Request::SANDBOX_PAYPAL_URL : Request::PAYPAL_URL;
		$user = $this->credential->getUser();
		$pwd = $this->credential->getPwd();
		$signature = $this->credential->getSignature();

		$requestData = array_merge(array(
				'USER' => $user,
				'PWD' => $pwd,
				'SIGNATURE' => $signature,
				'VERSION' => self::VERSION,
				'METHOD' => $method,
			), $requestData);
	}
}