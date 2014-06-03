<?php

namespace Store\Ups;

use SimpleXmlElement;
use Guzzle\Http\Client as HttpClient;
use Store\Model\Order;

class ApiRequest
{
    public $live_endpoint = 'https://onlinetools.ups.com/ups.app/xml/Rate';
    public $test_endpoint = 'https://wwwcie.ups.com/ups.app/xml/Rate';
    public $max_package_weight = 50; // lb
    public $imperial_countries = array('US');

    protected $order;
    protected $settings;

    /**
     * @var Guzzle HTTP client
     */
    protected $http;

    public function __construct(HttpClient $http)
    {
        $this->http = $http;
        $this->setSettings(array());
    }

    public function getOrder()
    {
        return $this->order;
    }

    public function setOrder(Order $order)
    {
        $this->order = $order;
    }

    public function getSettings()
    {
        return $this->settings;
    }

    public function setSettings(array $settings)
    {
        $settings = array_merge(array(
            'access_key' => '',
            'user_id' => '',
            'password' => '',
            'pickup_type' => '',
            'service' => '',
            'packaging' => '',
            'source_city' => '',
            'source_zip' => '',
            'source_country' => '',
            'insure_order' => '',
            'test_mode' => '',
        ), $settings);

        $this->settings = $settings;
    }

    public function buildAccessRequest()
    {
        $xml = new SimpleXmlElement('<AccessRequest />');
        $xml->AccessLicenseNumber = $this->settings['access_key'];
        $xml->UserId = $this->settings['user_id'];
        $xml->Password = $this->settings['password'];

        return $xml;
    }

    public function buildRatingRequest()
    {
        $xml = new SimpleXMLElement('<RatingServiceSelectionRequest />');
        $xml->Request->TransactionReference->CustomerContext = 'Rating and Service';
        $xml->Request->TransactionReference->XpciVersion = '1.0';
        $xml->Request->RequestAction = 'Rate';
        $xml->Request->RequestOption = 'Shop';
        $xml->PickupType->Code = $this->settings['pickup_type'];

        $xml->Shipment->Shipper->Address->City = $this->settings['source_city'];
        $xml->Shipment->Shipper->Address->PostalCode = $this->settings['source_zip'];
        $xml->Shipment->Shipper->Address->CountryCode = strtoupper($this->settings['source_country']);

        $xml->Shipment->ShipTo->PhoneNumber = $this->order->shipping_phone;
        $xml->Shipment->ShipTo->Address->AddressLine1 = $this->order->shipping_address1;
        $xml->Shipment->ShipTo->Address->AddressLine2 = $this->order->shipping_address2;
        $xml->Shipment->ShipTo->Address->City = $this->order->shipping_address3;
        $xml->Shipment->ShipTo->Address->StateProvinceCode = $this->order->shipping_region;
        $xml->Shipment->ShipTo->Address->PostalCode = $this->order->shipping_postcode;
        $xml->Shipment->ShipTo->Address->CountryCode = strtoupper($this->order->shipping_country);

        $weight_lb = max(0.1, $this->order->order_shipping_weight_lb);
        $num_packages = ceil($weight_lb / $this->max_package_weight);
        for ($i = 0; $i < $num_packages; $i++) {
            $xml->Shipment->Package[$i]->PackagingType->Code = $this->settings['packaging'];

            // units must match source address/country for some reason
            if (in_array(strtoupper($this->settings['source_country']), $this->imperial_countries)) {
                $xml->Shipment->Package[$i]->Dimensions->UnitOfMeasurement->Code = 'IN';
                $xml->Shipment->Package[$i]->Dimensions->Length = sprintf("%.1f", $this->order->order_shipping_length_in / $num_packages);
                $xml->Shipment->Package[$i]->Dimensions->Height = sprintf("%.1f", $this->order->order_shipping_height_in);
                $xml->Shipment->Package[$i]->Dimensions->Width = sprintf("%.1f", $this->order->order_shipping_width_in);
                $xml->Shipment->Package[$i]->PackageWeight->UnitOfMeasurement->Code = 'LBS';
                $xml->Shipment->Package[$i]->PackageWeight->Weight = sprintf("%.1f", $this->order->order_shipping_weight_lb / $num_packages);
            } else {
                $xml->Shipment->Package[$i]->Dimensions->UnitOfMeasurement->Code = 'CM';
                $xml->Shipment->Package[$i]->Dimensions->Length = sprintf("%.1f", $this->order->order_shipping_length_cm / $num_packages);
                $xml->Shipment->Package[$i]->Dimensions->Height = sprintf("%.1f", $this->order->order_shipping_height_cm);
                $xml->Shipment->Package[$i]->Dimensions->Width = sprintf("%.1f", $this->order->order_shipping_width_cm);
                $xml->Shipment->Package[$i]->PackageWeight->UnitOfMeasurement->Code = 'KGS';
                $xml->Shipment->Package[$i]->PackageWeight->Weight = sprintf("%.1f", $this->order->order_shipping_weight_kg / $num_packages);
            }

            // order weight must not be zero
            if ((float) $xml->Shipment->Package[$i]->PackageWeight->Weight <= 0) {
                $xml->Shipment->Package[$i]->PackageWeight->Weight = '0.1';
            }

            if ($this->settings['insure_order']) {
                $xml->Shipment->Package[$i]->PackageServiceOptions->InsuredValue->CurrencyCode = config_item('store_currency_code');
                $xml->Shipment->Package[$i]->PackageServiceOptions->InsuredValue->MonetaryValue = $this->order->order_total_val;
            }
        }

        return $xml;
    }

    public function toString()
    {
        return $this->buildAccessRequest()->asXML().$this->buildRatingRequest()->asXML();
    }

    /**
     * Send request to UPS
     */
    public function send()
    {
        $response = $this->http->post($this->getEndpoint(), null, $this->toString())->send();

        return $response->xml();
    }

    public function getEndpoint()
    {
        return $this->settings['test_mode'] ? $this->test_endpoint : $this->live_endpoint;
    }
}
