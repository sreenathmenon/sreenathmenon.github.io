<?php

if (defined('PATH_THIRD')) {
    require PATH_THIRD.'store/autoload.php';
}

use Store\Exception\ShippingException;
use Store\Model\Order;
use Store\Model\OrderShippingMethod;

class Store_usps_ext
{
    const VERSION = '1.0.2';

    public $name = 'Store USPS Shipping';
    public $version = self::VERSION;
    public $description = 'Provides USPS shipping calculation for Expresso Store';
    public $settings_exist = 'y';
    public $docs_url = 'https://exp-resso.com/store/docs';
    public $settings = array();
    public $endpoint = 'http://production.shippingapis.com/ShippingAPI.dll';

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
        $settings['username'] = '';
        $settings['source_zip'] = '';
        $settings['service'] = array('s', array(
            'FIRST CLASS' => 'USPS First Class',
            'FIRST CLASS COMMERCIAL' => 'USPS First Class Commercial',
            'FIRST CLASS HFP COMMERCIAL' => 'USPS First Class Hold For Pickup Commercial',
            'PRIORITY' => 'USPS Priority',
            'PRIORITY COMMERCIAL' => 'USPS Priority Commercial',
            'PRIORITY HFP COMMERCIAL' => 'USPS Priority Hold For Pickup Commercial',
            'EXPRESS' => 'USPS Express',
            'EXPRESS COMMERCIAL' => 'USPS Express Commercial',
            'EXPRESS SH' => 'USPS Express SH',
            'EXPRESS SH COMMERCIAL' => 'USPS Express SH Commercial',
            'EXPRESS HFP' => 'USPS Express Hold For Pickup',
            'EXPRESS HFP COMMERCIAL' => 'USPS Express Hold For Pickup Commercial',
            'PARCEL' => 'USPS Parcel',
            'MEDIA' => 'USPS Media',
            'LIBRARY' => 'USPS Library',
            'ALL' => 'All',
            'ONLINE' => 'Online',
        ));
        $settings['first_class_mail_type'] = array('s', array(
            'LETTER' => 'Letter',
            'FLAT' => 'Flat',
            'PARCEL' => 'Parcel',
            'POSTCARD' => 'Postcard',
            'PACKAGE SERVICE' => 'Package Service',
        ));
        $settings['container'] = array('s', array(
            'VARIABLE' => 'Variable',
            'FLAT RATE ENVELOPE' => 'Flat Rate Envelope',
            'PADDED FLAT RATE ENVELOPE' => 'Padded Flat Rate Envelope',
            'LEGAL FLAT RATE ENVELOPE' => 'Legal Flat Rate Envelope',
            'SM FLAT RATE ENVELOPE' => 'Sm Flat Rate Envelope',
            'WINDOW FLAT RATE ENVELOPE' => 'Window Flat Rate Envelope',
            'GIFT CARD FLAT RATE ENVELOPE' => 'Gift Card Flat Rate Envelope',
            'FLAT RATE BOX' => 'Flat Rate Box',
            'SM FLAT RATE BOX' => 'Sm Flat Rate Box',
            'MD FLAT RATE BOX' => 'Md Flat Rate Box',
            'LG FLAT RATE BOX' => 'Lg Flat Rate Box',
            'REGIONALRATEBOXA' => 'Regional Rate Box A',
            'REGIONALRATEBOXB' => 'Regional Rate Box B',
            'REGIONALRATEBOXC' => 'Regional Rate Box C',
            'RECTANGULAR' => 'Rectangular',
            'NONRECTANGULAR' => 'Nonrectangular',
        ));
        $settings['size'] = array('s', array(
            'REGULAR' => 'Regular',
            'LARGE' => 'Large',
        ));
        $settings['machinable'] = array('r', array('1' => 'Yes', '0' => 'No'), '1');

        return $settings;
    }

    public function shipping_methods(Order $order, array $methods)
    {
        if (ee()->extensions->last_call !== false) {
            $methods = ee()->extensions->last_call;
        }

        // don't bother unless we have an account configured
        if (!$this->settings['username']) {
            return $methods;
        }

        // don't bother unless we at least have a country and ZIP
        if ($order->shipping_country != 'US' || $order->shipping_postcode == '') {
            return $methods;
        }

        $request = $this->build_request($order);
        $response = $this->send($request);

        if ($response->getName() == 'Error') {
            throw new ShippingException((string) $response->Description);
        }

        if (isset($response->Package->Error)) {
            throw new ShippingException((string) $response->Package->Error->Description);
        }

        foreach ($response->Package->Postage as $row) {
            $code = (string) $row['CLASSID'];
            $option = new OrderShippingMethod;
            $option->id = __CLASS__.':'.$code;
            $option->name = $this->clean_str($row->MailService);
            $option->class = __CLASS__;

            // zero cost if all items in cart have free shipping
            if ($order->order_shipping_qty < 1) {
                $option->amount = 0.0;
            } else {
                $option->amount = (string) $row->Rate;
            }

            $methods[$option->id] = $option;
        }

        return $methods;
    }

    public function build_request(Order $order)
    {
        $request = new SimpleXMLElement('<RateV4Request/>');
        $request['USERID'] = $this->settings['username'];
        //$request->Revision = 2;
        $request->Package['ID'] = '0';
        $request->Package->Service = $this->settings['service']; //'ALL';
        $request->Package->FirstClassMailType = $this->settings['first_class_mail_type'];
        $request->Package->ZipOrigination = $this->settings['source_zip'];
        $request->Package->ZipDestination = $order->shipping_postcode;
        $request->Package->Pounds = 0;
        $request->Package->Ounces = max(1, round($order->order_shipping_weight_lb * 16));
        $request->Package->Container = $this->settings['container'];
        $request->Package->Size = $this->settings['size'];
        $request->Package->Width = max(1, $order->order_shipping_width_in);
        $request->Package->Length = max(1, $order->order_shipping_length_in);
        $request->Package->Height = max(1, $order->order_shipping_height_in);
        $request->Package->Machinable = $this->settings['machinable'] ? 'true' : 'false';

        return $request;
    }

    public function send($xml)
    {
        $data = http_build_query(array(
            'API' => 'RateV4',
            'XML' => $xml->asXML(),
        ));
        $response = ee()->store->cached_http->get($this->endpoint.'?'.$data)->send();

        return $response->xml();
    }

    protected function clean_str($str)
    {
        return strip_tags(str_replace('&reg;', '', html_entity_decode($str)));
    }
}
