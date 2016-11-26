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

use Paypal\Common\Request as BaseRequest;
use Paypal\Exception\RequestException;

class Request extends BaseRequest
{
	const SANDBOX_PAYPAL_URL = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
	const PAYPAL_URL = 'https://www.paypal.com/cgi-bin/webscr';

	public function getBaseEndpoint()
	{
		return parent::getBaseEndpoint().'/nvp';
	}

	public function send(array $request)
	{
		$apiEndpoint  = $this->getBaseEndpoint();

		$curl = curl_init();

		curl_setopt($curl, CURLOPT_URL, $apiEndpoint);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($request));

		$response = urldecode(curl_exec($curl));

		curl_close($curl);

		$responseNvp = array();

		if (preg_match_all('/(?<name>[^\=]+)\=(?<value>[^&]+)&?/', $response, $matches)) {
			foreach ($matches['name'] as $offset => $name) {
				$responseNvp[$name] = $matches['value'][$offset];
			}
		}

		if (isset($responseNvp['ACK']) && $responseNvp['ACK'] != 'Success') {
			$messages = [];
			for ($i = 0; isset($responseNvp['L_ERRORCODE' . $i]); ++$i) {
				$messages[] = sprintf("PayPal NVP %s[%d]: %s\n",
								   $responseNvp['L_SEVERITYCODE' . $i],
								   $responseNvp['L_ERRORCODE' . $i],
								   $responseNvp['L_LONGMESSAGE' . $i]);
			}
			if (count($messages)) {
				throw new RequestException($messages);
			}
		}

		return $responseNvp;
	}
}