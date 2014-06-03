<?php

use Store\Model\Order;
use Store\Model\OrderShippingMethod;
use Store\Ups\ApiRequest;

class Store_ups_ext
{
    const VERSION = '1.0.3';

    public $name = 'Store UPS Shipping';
    public $version = self::VERSION;
    public $description = 'Provides UPS shipping calculation for Expresso Store';
    public $settings_exist = 'y';
    public $docs_url = 'https://exp-resso.com/store/docs';
    public $class = 'Store_ups_ext';
    public $settings = array();

    public $services = array(
        '03' => 'UPS Domestic Ground',
        '12' => 'UPS Domestic 3 Day Select',
        '01' => 'UPS Domestic Next Day Air',
        '14' => 'UPS Domestic Next Day Air Early AM',
        '13' => 'UPS Domestic Next Day Air Saver',
        '02' => 'UPS Domestic Second Day Air',
        '59' => 'UPS Domestic Second Day Air AM',
        '11' => 'UPS International Standard',
        '65' => 'UPS International Saver',
        '07' => 'UPS International Worldwide Express',
        '54' => 'UPS International Worldwide Express Plus',
        '08' => 'UPS International Worldwide Expedited',
    );

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
            'class'     => $this->class,
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

        ee()->db->where('class', $this->class)
            ->update('extensions', array('version' => $this->version));
    }

    public function settings()
    {
        $settings = array();
        $settings['access_key'] = '';
        $settings['user_id'] = '';
        $settings['password'] = '';
        $settings['pickup_type'] = array('s',
            array(
                '01' => 'Daily Pickup',
                '03' => 'Customer Counter',
                '06' => 'One Time Pickup',
                '07' => 'On Call Air',
                '19' => 'Letter Center',
                '20' => 'Air Service Center',
            ), '01');
        $settings['service'] = array('c', $this->services);
        $settings['packaging'] = array('s',
            array(
                '02' => 'Package',
                '01' => 'UPS Letter',
                '03' => 'Tube',
                '04' => 'Pak',
                '25' => '10KG Box',
                '24' => '25KG Box',
                '30' => 'Pallet',
                '21' => 'Express Box',
                '2a' => 'Small Express Box',
                '2b' => 'Medium Express Box',
                '2c' => 'Large Express Box',
                '00' => 'Unknown',
            ), '02');
        $settings['source_city'] = '';
        $settings['source_zip'] = '';
        $settings['source_country'] = '';
        $settings['insure_order'] = array('r', array('1' => 'Yes', '0' => 'No'), '0');
        $settings['test_mode'] = array('r', array('0' => 'Disabled', '1' => 'Enabled'), '0');

        return $settings;
    }

    public function shipping_methods(Order $order, array $methods)
    {
        if (ee()->extensions->last_call !== false) {
            $methods = ee()->extensions->last_call;
        }

        if ($order->shipping_country == '') {
            return $methods;
        }

        if ($order->shipping_postcode == '' && $order->shipping_city == '') {
            return $methods;
        }

        require_once __DIR__.'/ApiRequest.php';
        $request = new ApiRequest(ee()->store->cached_http);
        $request->setSettings($this->settings);
        $request->setOrder($order);

        $response = $request->send();

        if ('1' === (string) $response->Response->ResponseStatusCode) {
            foreach ($response->RatedShipment as $row) {
                $code = (string) $row->Service->Code;
                if (empty($this->settings['service']) || in_array($code, (array) $this->settings['service'])) {
                    $option = new OrderShippingMethod;
                    $option->id = $this->class.':'.$code;
                    $option->name = $this->services[$code];
                    $option->class = $this->class;
                    $option->days = (string) $row->GuaranteedDaysToDelivery;

                    // zero cost if all items in cart have free shipping
                    if ($order->order_shipping_qty < 1) {
                        $option->amount = 0.0;
                    } else {
                        $option->amount = (string) $row->TotalCharges->MonetaryValue;
                    }

                    $methods[$option->id] = $option;
                }
            }
        }

        return $methods;
    }
}
