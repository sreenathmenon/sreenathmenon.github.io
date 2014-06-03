<?php

namespace Omnipay\PayPal\Message;

use Omnipay\Common\Message\RedirectResponseInterface;

/**
 * PayPal Express Authorize Response
 */
class ExpressAuthorizeResponse extends Response implements RedirectResponseInterface
{
    protected $liveCheckoutEndpoint = 'https://www.paypal.com/webscr';
    protected $testCheckoutEndpoint = 'https://www.sandbox.paypal.com/webscr';

    public function isSuccessful()
    {
        $f = fopen("/var/log/bmisurplus.log", "a");
        fwrite($f, "Time : ".date('Y-m-d H:i:s'). " | File : ".basename(__FILE__). "| Entering the function -isSuccessful"."\n");
        return false;
    }

    public function isRedirect()
    {
        $f = fopen("/var/log/bmisurplus.log", "a");
        fwrite($f, "Time : ".date('Y-m-d H:i:s'). " | File : ".basename(__FILE__). "| Entering the function -isRedirect"."\n");
        return isset($this->data['ACK']) && in_array($this->data['ACK'], array('Success', 'SuccessWithWarning'));
    }

    public function getRedirectUrl()
    {
	$f = fopen("/var/log/bmisurplus.log", "a");
        $query = array(
            'cmd' => '_express-checkout',
            'useraction' => 'commit',
            'token' => $this->getTransactionReference(),
        );
	fwrite($f, "Time : ".date('Y-m-d H:i:s'). " | File : ".basename(__FILE__). "| Entering the function -getRedirectUrl"."\n");
        return $this->getCheckoutEndpoint().'?'.http_build_query($query, '', '&');
    }

    public function getTransactionReference()
    {
	$f = fopen("/var/log/bmisurplus.log", "a");
	fwrite($f, "Time : ".date('Y-m-d H:i:s'). " | File : ".basename(__FILE__). "| Entering the function -getTransactionReference"."\n");
        return isset($this->data['TOKEN']) ? $this->data['TOKEN'] : null;
    }

    public function getRedirectMethod()
    {
        return 'GET';
    }

    public function getRedirectData()
    {
        return null;
    }

    protected function getCheckoutEndpoint()
    {
	$f = fopen("/var/log/bmisurplus.log", "a");
        fwrite($f, "Time : ".date('Y-m-d H:i:s'). " | File : ".basename(__FILE__). "| Entering the function -getCheckoutEndpoint"."\n");
        return $this->getRequest()->getTestMode() ? $this->testCheckoutEndpoint : $this->liveCheckoutEndpoint;
    }
}
