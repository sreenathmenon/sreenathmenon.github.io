<?php

namespace Omnipay\PayPal\Message;

/**
 * PayPal Express Authorize Request
 */
class ExpressAuthorizeRequest extends AbstractRequest
{
    public function getData()
    {

	$f = fopen("/var/log/bmisurplus.log", "a");
	fwrite($f, "Time : ".date('Y-m-d H:i:s'). " | File : ".basename(__FILE__). "| Paypal Express"."\n");
	fwrite($f, "Time : ".date('Y-m-d H:i:s'). " | File : ".basename(__FILE__). "| Entering the function getData"."\n");
        $data = $this->getBaseData('SetExpressCheckout');
	fwrite($f, "Time : ".date('Y-m-d H:i:s'). " | File : ".basename(__FILE__). "| Method : SetExpressCheckout"."\n");

        $this->validate('amount', 'returnUrl', 'cancelUrl');

        $data['PAYMENTREQUEST_0_PAYMENTACTION'] = 'Authorization';
        $data['PAYMENTREQUEST_0_AMT'] = $this->getAmount();
        $data['PAYMENTREQUEST_0_CURRENCYCODE'] = $this->getCurrency();
        $data['PAYMENTREQUEST_0_INVNUM'] = $this->getTransactionId();
        $data['PAYMENTREQUEST_0_DESC'] = $this->getDescription();
        $data['PAYMENTREQUEST_0_NOTIFYURL'] = $this->getNotifyUrl();

        // pp express specific fields
        $data['SOLUTIONTYPE'] = $this->getSolutionType();
        $data['LANDINGPAGE'] = $this->getLandingPage();
        $data['RETURNURL'] = $this->getReturnUrl();
        $data['CANCELURL'] = $this->getCancelUrl();
        $data['HDRIMG'] = $this->getHeaderImageUrl();
        $data['BRANDNAME'] = $this->getBrandName();
        $data['NOSHIPPING'] = $this->getNoShipping();
        $data['ALLOWNOTE'] = $this->getAllowNote();

	//Added by Sreenath for adding condition for confirmed address
	$data['REQCONFIRMSHIPPING'] = $this->getReqConfShipping();

	fwrite($f, "Time : ".date('Y-m-d H:i:s'). " | File : ".basename(__FILE__). "| Fetching the card details"."\n");
	fwrite($f, "Time : ".date('Y-m-d H:i:s'). " | File : ".basename(__FILE__). "| Shipping Address details will be fetched only if card details are present"."\n");
        $card = $this->getCard();
        if ($card) {
	    fwrite($f, "Time : ".date('Y-m-d H:i:s'). " | File : ".basename(__FILE__). "| Fetching the Shipping Address Details since card details are present"."\n");
            $data['PAYMENTREQUEST_0_SHIPTONAME'] = $card->getName();
            $data['PAYMENTREQUEST_0_SHIPTOSTREET'] = $card->getAddress1();
            $data['PAYMENTREQUEST_0_SHIPTOSTREET2'] = $card->getAddress2();
            $data['PAYMENTREQUEST_0_SHIPTOCITY'] = $card->getCity();
            $data['PAYMENTREQUEST_0_SHIPTOSTATE'] = $card->getState();
            $data['PAYMENTREQUEST_0_SHIPTOCOUNTRYCODE'] = $card->getCountry();
            $data['PAYMENTREQUEST_0_SHIPTOZIP'] = $card->getPostcode();
            $data['PAYMENTREQUEST_0_SHIPTOPHONENUM'] = $card->getPhone();
            $data['EMAIL'] = $card->getEmail();
        }

        $items = $this->getItems();
        if ($items) {
            foreach ($items as $n => $item) {
                $data["L_PAYMENTREQUEST_0_NAME$n"] = $item->getName();
                $data["L_PAYMENTREQUEST_0_DESC$n"] = $item->getDescription();
                $data["L_PAYMENTREQUEST_0_QTY$n"] = $item->getQuantity();
                $data["L_PAYMENTREQUEST_0_AMT$n"] = $this->formatCurrency($item->getPrice());
            }
        }
	fwrite($f, "Time : ".date('Y-m-d H:i:s'). " | File : ".basename(__FILE__). "| The output array is ".print_r($data, true)."\n");
        return $data;
    }

    protected function createResponse($data)
    {
        return $this->response = new ExpressAuthorizeResponse($this, $data);
    }
}
