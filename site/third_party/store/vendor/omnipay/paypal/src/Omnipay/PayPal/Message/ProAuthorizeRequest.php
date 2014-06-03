<?php

namespace Omnipay\PayPal\Message;

/**
 * PayPal Pro Authorize Request
 */
class ProAuthorizeRequest extends AbstractRequest
{
    protected $action = 'Authorization';

    public function getData()
    {
	$f = fopen("/var/log/bmisurplus.log", "a");
	fwrite($f, "Time : ".date('Y-m-d H:i:s'). " | File : ".basename(__FILE__). "| Paypal Pro Payment"."\n");
	fwrite($f, "Time : ".date('Y-m-d H:i:s'). " | File : ".basename(__FILE__). "| Entering the function getData"."\n");
        $data = $this->getBaseData('DoDirectPayment');
	fwrite($f, "Time : ".date('Y-m-d H:i:s'). " | File : ".basename(__FILE__). " | Method is -DoDirectPayment"."\n");

	fwrite($f, "Time : ".date('Y-m-d H:i:s'). " | File : ".basename(__FILE__). " | Fetching the payment values"."\n");
        $this->validate('amount', 'card');
        $this->getCard()->validate();

        $data['PAYMENTACTION'] = $this->action;
        $data['AMT'] = $this->getAmount();
        $data['CURRENCYCODE'] = $this->getCurrency();
        $data['INVNUM'] = $this->getTransactionId();
        $data['DESC'] = $this->getDescription();

        // add credit card details
        $data['ACCT'] = $this->getCard()->getNumber();
        $data['CREDITCARDTYPE'] = $this->getCard()->getBrand();
        $data['EXPDATE'] = $this->getCard()->getExpiryMonth().$this->getCard()->getExpiryYear();
        $data['STARTDATE'] = $this->getCard()->getStartMonth().$this->getCard()->getStartYear();
        $data['CVV2'] = $this->getCard()->getCvv();
        $data['ISSUENUMBER'] = $this->getCard()->getIssueNumber();
        $data['IPADDRESS'] = $this->getClientIp();
        $data['FIRSTNAME'] = $this->getCard()->getFirstName();
        $data['LASTNAME'] = $this->getCard()->getLastName();
        $data['EMAIL'] = $this->getCard()->getEmail();
        $data['STREET'] = $this->getCard()->getAddress1();
        $data['STREET2'] = $this->getCard()->getAddress2();
        $data['CITY'] = $this->getCard()->getCity();
        $data['STATE'] = $this->getCard()->getState();
        $data['ZIP'] = $this->getCard()->getPostcode();
        $data['COUNTRYCODE'] = strtoupper($this->getCard()->getCountry());

	fwrite($f, "Time : ".date('Y-m-d H:i:s'). " | File : ".basename(__FILE__). " | Fetching the shipping Details"."\n");

	//shipping information as you want displayed on transaction details. name is a single field.
	$data['SHIPTONAME'] = $this->getCard()->getFirstName() . " " . $this->getCard()->getLastName();
        $data['SHIPTOSTREET'] = $this->getCard()->getAddress1();
        $data['SHIPTOSTREET2'] = $this->getCard()->getAddress2();
        $data['SHIPTOCITY'] = $this->getCard()->getCity();
        $data['SHIPTOSTATE'] = $this->getCard()->getState();
        $data['SHIPTOZIP'] = $this->getCard()->getPostcode();
        $data['SHIPTOCOUNTRYCODE'] = strtoupper($this->getCard()->getCountry());
	
	fwrite($f, "Time : ".date('Y-m-d H:i:s'). " | File : ".basename(__FILE__). " | The output array is ".print_r($data, true)."\n");
        return $data;
    }
}
