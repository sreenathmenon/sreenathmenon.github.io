<?php

if (defined('PATH_THIRD')) {
    require PATH_THIRD.'store/autoload.php';
}

use Store\Exception\ShippingException;
use Store\Model\Order;
use Store\Model\OrderShippingMethod;

class Store_fedex_ext
{
    const VERSION = '1.0.3';

    public $name = 'Store FedEx Shipping';
    public $version = self::VERSION;
    public $description = 'Provides FedEx shipping calculations for Expresso Store';
    public $settings_exist = 'y';
    public $docs_url = 'https://exp-resso.com/docs';
    public $settings = array();
    public $live_endpoint = 'https://ws.fedex.com/xml/';
    public $test_endpoint = 'https://wsbeta.fedex.com/xml/';

    public $services = array(
        'EUROPE_FIRST_INTERNATIONAL_PRIORITY' => 'Fedex Europe First International Priority',
        'FEDEX_1_DAY_FREIGHT' => 'FedEx 1 Day Freight',
        'FEDEX_2_DAY' => 'FedEx 2 Day',
        'FEDEX_2_DAY_AM' => 'FedEx 2 Day AM',
        'FEDEX_2_DAY_FREIGHT' => 'FedEx 2 Day Freight',
        'FEDEX_3_DAY_FREIGHT' => 'FedEx 3 Day Freight',
        'FEDEX_EXPRESS_SAVER' => 'FedEx Express Saver',
        'FEDEX_FIRST_FREIGHT' => 'FedEx First Freight',
        'FEDEX_FREIGHT_ECONOMY' => 'FedEx Freight Economy',
        'FEDEX_FREIGHT_PRIORITY' => 'FedEx Freight Priority',
        'FEDEX_GROUND' => 'FedEx Ground',
        'FIRST_OVERNIGHT' => 'Fedex Overnight',
        'GROUND_HOME_DELIVERY' => 'Fedex Ground Home Delivery',
        'INTERNATIONAL_ECONOMY' => 'Fedex International Economy',
        'INTERNATIONAL_ECONOMY_FREIGHT' => 'Fedex International Economy Freight',
        'INTERNATIONAL_FIRST' => 'Fedex International First',
        'INTERNATIONAL_PRIORITY' => 'IFedex nternational Priority',
        'INTERNATIONAL_PRIORITY_FREIGHT' => 'Fedex International Priority Freight',
        'PRIORITY_OVERNIGHT' => 'Fedex Priority Overnight',
        'SMART_POST' => 'Fedex Smart Post',
        'STANDARD_OVERNIGHT' => 'Fedex Standard Overnight');

    const XML_NAMESPACE = 'http://fedex.com/ws/rate/v10';

    public function __construct($settings = array())
    {
        $defaults = array();
        foreach (array_keys($this->settings()) as $key) {
            $defaults[$key] = null;
        }
        $this->settings = array_merge($defaults, $settings);
    }

    public function activate_extension()
    {
        $data = array(
            'class'     => __CLASS__,
            'method'    => 'shipping_methods',
            'hook'      => 'store_order_shipping_methods',
            'priority'  => 10,
            'settings'  => serialize($this->settings),
            'version'   => $this->version,
            'enabled'   => 'y'
        );

        ee()->db->insert('extensions', $data);
    }

    public function update_extension($current = '')
    {
        if ($current == '' || $current == $this->version) {
            return false;
        }

        ee()->db->where('class', __CLASS__)
            ->update('extensions', array('version' => $this->version));
    }

    public function settings()
    {
        $settings = array();
        $settings['api_key'] = '';
        $settings['password'] = '';
        $settings['account_no'] = '';
        $settings['meter_no'] = '';
        $settings['dropoff'] = array('s', array(
            'BUSINESS_SERVICE_CENTER' => 'Business Service Center',
            'DROP_BOX' => 'Drop Box',
            'REGULAR_PICKUP' => 'Regular Pickup',
            'REQUEST_COURIER' => 'Request Courier',
            'STATION' => 'Station'));
        $settings['service'] = array('c', $this->services);
        $settings['packaging'] = array('s', array(
            'YOUR_PACKAGING' => 'Own Packaging',
            'FEDEX_BOX' => 'FedEx Box',
            'FEDEX_10KG_BOX' => 'FedEx 10kg Box',
            'FEDEX_25KG_BOX' => 'FedEx 25kg Box',
            'FEDEX_ENVELOPE' => 'FedEx Envelope',
            'FEDEX_PAK' => 'FedEx Pak',
            'FEDEX_TUBE' => 'FedEx Tube'));
        $settings['source_city'] = '';
        $settings['source_zip'] = '';
        $settings['source_country'] = array('s', array_map(function($country) {
            return $country['name'];
        }, ee()->store->shipping->get_countries()));
        $settings['residential_delivery'] = array('s', array(1 => 'Yes', 0 => 'No'));
        $settings['test_mode'] = array('s', array(0 => 'No', 1 => 'Yes'));

        return $settings;
    }

    public function shipping_methods(Order $order, array $methods)
    {
        if (ee()->extensions->last_call !== false) {
            $methods = ee()->extensions->last_call;
        }

        // don't bother unless we at least have a country and ZIP
        if ($order->shipping_country == '' || $order->shipping_postcode == '') {
            return $methods;
        }

        $request = $this->build_request($order);
        $response = $this->send($request);
        $fedex_methods = $this->parse_response($response);

        // zero cost if all items in cart have free shipping
        if ($order->order_shipping_qty < 1) {
            foreach ($fedex_methods as $method) {
                $method->amount = 0.0;
            }
        }

        return $methods + $fedex_methods;
    }

    public function build_request(Order $order)
    {
        $xml = new SimpleXMLElement('<RateRequest xmlns="'.self::XML_NAMESPACE.'" />');
        $xml->WebAuthenticationDetail->UserCredential->Key = $this->settings['api_key'];
        $xml->WebAuthenticationDetail->UserCredential->Password = $this->settings['password'];
        $xml->ClientDetail->AccountNumber = $this->settings['account_no'];
        $xml->ClientDetail->MeterNumber = $this->settings['meter_no'];
        $xml->Version->ServiceId = 'crs';
        $xml->Version->Major = 10;
        $xml->Version->Intermediate = 0;
        $xml->Version->Minor = 0;
        $xml->RequestedShipment->DropoffType = $this->settings['dropoff'];
        $xml->RequestedShipment->PackagingType = $this->settings['packaging'];
        $xml->RequestedShipment->PreferredCurrency = config_item('store_currency_code');
        $xml->RequestedShipment->Shipper->Address->City = $this->settings['source_city'];;
        $xml->RequestedShipment->Shipper->Address->PostalCode = $this->settings['source_zip'];
        $xml->RequestedShipment->Shipper->Address->CountryCode = strtoupper($this->settings['source_country']);
        $xml->RequestedShipment->Recipient->Address->StreetLines[] = $order->shipping_address1;
        $xml->RequestedShipment->Recipient->Address->StreetLines[] = $order->shipping_address2;
        $xml->RequestedShipment->Recipient->Address->City = $order->shipping_address3;
        if (in_array($order->shipping_country, array('US', 'CA'))) {
            $xml->RequestedShipment->Recipient->Address->StateOrProvinceCode = $order->shipping_state;
        }
        $xml->RequestedShipment->Recipient->Address->PostalCode = $order->shipping_postcode;
        $xml->RequestedShipment->Recipient->Address->CountryCode = $order->shipping_country;

        if ($this->settings['residential_delivery']) {
            $xml->RequestedShipment->Recipient->Address->Residential = 1;
        }

        $xml->RequestedShipment->PackageCount = 1;
        $xml->RequestedShipment->RequestedPackageLineItems->SequenceNumber = 1;
        $xml->RequestedShipment->RequestedPackageLineItems->GroupPackageCount = 1;
        $xml->RequestedShipment->RequestedPackageLineItems->Weight->Units = 'LB';
        $xml->RequestedShipment->RequestedPackageLineItems->Weight->Value = max(0.1, $order->order_shipping_weight_lb);
        $xml->RequestedShipment->RequestedPackageLineItems->Dimensions->Length = round($order->order_shipping_length_in);
        $xml->RequestedShipment->RequestedPackageLineItems->Dimensions->Width = round($order->order_shipping_width_in);
        $xml->RequestedShipment->RequestedPackageLineItems->Dimensions->Height = round($order->order_shipping_height_in);
        $xml->RequestedShipment->RequestedPackageLineItems->Dimensions->Units = 'IN';

        return $xml;
    }

    public function send($data)
    {
        $request = ee()->store->cached_http->post($this->endpoint(), array(), $data->asXML());

        return $request->send();
    }

    public function parse_response($response)
    {
        $xml = simplexml_load_string($response->getBody());
        $rate = $xml->children(self::XML_NAMESPACE);
        if (empty($rate)) {
            throw new ShippingException(lang('store.shipping_communication_error'));
        }

        if ((string) $rate->HighestSeverity == 'ERROR') {
            if (isset($rate->Notifications->LocalizedMessage)) {
                throw new ShippingException((string) $rate->Notifications->LocalizedMessage);
            }

            throw new ShippingException((string) $rate->Notifications->Message);
        }

        $methods = array();

        foreach ($rate->RateReplyDetails as $row) {
            $code = (string) $row->ServiceType;
            if (empty($this->settings['service']) || in_array($code, (array) $this->settings['service'])) {
                $option = new OrderShippingMethod;
                $option->id = __CLASS__.':'.$code;
                $option->name = $this->services[$code];
                $option->amount = (string) $row->RatedShipmentDetails->ShipmentRateDetail->TotalNetCharge->Amount;
                $option->class = __CLASS__;

                $methods[$option->id] = $option;
            }
        }

        return $methods;
    }

    public function endpoint()
    {
        return $this->settings['test_mode'] ? $this->test_endpoint : $this->live_endpoint;
    }
}
