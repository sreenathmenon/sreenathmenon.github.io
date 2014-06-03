<?php

$order_fields = array(
	'order_id' => 'id',
	'order_date' => 'order_date',
	'billing_first_name' => 'billing_first_name',
	'billing_last_name' => 'billing_last_name',
	'billing_company' => 'billing_company',
	'billing_address_1' => 'billing_address1',
	'billing_address_2' => 'billing_address2',
	'billing_city' => 'billing_city',
	'billing_state' => 'billing_state',
	'billing_postcode' => 'billing_postcode',
	'billing_country' => 'billing_country',
	'billing_phone' => 'billing_phone',

	'shipping_first_name' => 'shipping_first_name',
	'shipping_last_name' => 'shipping_last_name',
	'shipping_company' => 'shipping_company',
	'shipping_address_1' => 'shipping_address1',
	'shipping_address_2' => 'shipping_address2',
	'shipping_city' => 'shipping_city',
	'shipping_state' => 'shipping_state',
	'shipping_postcode' => 'shipping_postcode',
	'shipping_country' => 'shipping_country',
	'shipping_phone' => 'shipping_phone',	

	'order_tax' => 'order_tax_val',
	'order_subtotal' => 'order_subtotal_val',
	'order_shipping' => 'order_shipping_val',
	'order_shipping_tax' => 'order_shipping_tax_val',
	'order_discount' => 'order_discount_inc_tax_val',
	'order_total' => 'order_total_val',
	'order_email' => 'order_email',
	'order_payment_date' => 'order_paid_date',
	
	'order_number_of_items' => 'order_qty',
	'order_notes' => 'order_custom1',
  	'order_shipping_customer_account' => 'order_custom2',
   	'order_subscribe' => 'order_custom3',

	// will need to process this before insert
	'order_tax_rate_name' => 'tax_name',
	'order_payment_method' => 'payment_method',
	'shipping_carrier' => 'shipping_method'

);

$item_fields = array(
	'item_order_id' =>'order_id',
	'item_name' => 'title',
	'item_sku' => 'sku',
	'item_price' => 'price_val',
	'item_quantity' => 'item_qty'

);

$payment_methods = array(
	'PayPal_Pro' => 'Generic Card',
	'PayPal_Express' => 'Generic Card',
	'wiretransfer' => 'Generic Card'
);

$shipping_methods = array(
	'1' => 'Local Pickup', // local pickup
	'2' => 'Customer Shipping Account',// use your own
	'3' => 'Call for shipping.', // call
	'Store_fedex_ext:FEDEX_2_DAY' => 'FedEx 2nd Day',
	'Store_fedex_ext:FEDEX_EXPRESS_SAVER' => 'FedEx Express Saver',
	'Store_fedex_ext:FIRST_OVERNIGHT' => 'FedEx First Overnight',
	'Store_fedex_ext:FEDEX_GROUND' => 'FedEx Ground Service',
	'Store_fedex_ext:GROUND_HOME_DELIVERY' => 'FedEx Home Delivery',
	'Store_fedex_ext:PRIORITY_OVERNIGHT' => 'FedEx Priority',
	'Store_fedex_ext:STANDARD_OVERNIGHT' => 'FedEx Standard Overnight',
	
	'Store_ups_ext:02' => 'UPS 2nd Day Air', // ups second day air
	'Store_ups_ext:59' => 'UPS 2nd Day Air AM', // second day air am
	'Store_ups_ext:12' => 'UPS 3-Day Select', // 3 day select
	'Store_ups_ext:03' => 'UPS Ground', // ground
	'Store_ups_ext:01' => 'UPS Next Day Air', // next day air
	'Store_ups_ext:14' => 'UPS Next Day Air Early AM', // next day air am
	'Store_ups_ext:13' => 'UPS Next Day Air Saver', // next day air saver
	
	//'Store_usps_ext:FIRST CLASS' => '17' // usps first class
	//'Store_usps_ext:PARCEL' => '18' // usps parcel post
	'Store_usps_ext:1' => 'USPS Priority Mail' // usps priority
);

$shipping_carriers =  array(
	'FedEx 2nd Day' => 'fedex',
	'FedEx Express Saver' => 'fedex',
	'FedEx First Overnight' => 'fedex',
	'FedEx Ground Service' => 'fedex',
	'FedEx Home Delivery' => 'fedex',
	'FedEx Priority' => 'fedex',
	'FedEx Standard Overnight' => 'fedex',
	'FedEx 2nd Day AM' => 'fedex',
	'FedEx International Economy' => 'fedex',
	
	'UPS 2nd Day Air' => 'ups', // ups second day air
	'UPS 2nd Day Air AM' => 'ups', // second day air am
	'UPS 3-Day Select' => 'ups', // 3 day select
	'UPS Ground' => 'ups', // ground
	'UPS Next Day Air' => 'ups', // next day air
	'UPS Next Day Air Early AM' => 'ups', // next day air am
	'UPS Next Day Air Saver' => 'ups', // next day air saver
	
	'USPS Parcel Post' => 'usps', 
	'USPS First-class Mail' => 'usps', 
	'USPS Priority Mail' => 'usps', // usps priority
	'US Mail' => 'usps'
);