<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * ExpressionEngine - by EllisLab
 *
 * @package		ExpressionEngine
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2003 - 2011, EllisLab, Inc.
 * @license		http://expressionengine.com/user_guide/license.html
 * @link		http://expressionengine.com
 * @since		Version 2.0
 * @filesource
 */
 
// ------------------------------------------------------------------------

/**
 * BMI Custom Module Front End File
 *
 * @package		ExpressionEngine
 * @subpackage	Addons
 * @category	Module
 * @author		Conflux Group (Jeremy Gimbel)
 * @link		http://confluxgroup.com
 */

// composer autoload
require dirname(__FILE__).'/vendor/autoload.php';

// constant contact classes
use Ctct\ConstantContact;
use Ctct\Components\Contacts\Contact;
use Ctct\Components\Contacts\ContactList;
use Ctct\Components\Contacts\EmailAddress;
use Ctct\Exceptions\CtctException;

class Bmi_custom {
	
	public $return_data;
	
	/**
	 * Constructor
	 */
	public function __construct()
	{
		
	}
	
	// ----------------------------------------------------------------

	/**
	 * cron_action()
	 *
	 * the action id hit by cron runs this method, which will
	 * call all the necessary batches to be processed
	 */
	public function cron_action()
	{

		// process any recently updated inventory
		$this->inventory_batch();
		
		// process any recently shipped orders
		$this->shipping_batch();

		// process any recently processing orders
		$this->processing_batch();

		// process any product updates
		$this->product_batch();   

		// clean up any old records from the queues
		$this->cleanup();
		exit('done');

	} // end cron_action


	/**
	 * product_action()
	 *
	 * grabs a batch of product updates from the products table and formats them into
	 * xml to be used by DataGrab for updating the product catalog
	 * 
	 * after generating xml, the included records are marked as processed, so they aren't
	 * run again
	 * 
	 */
	public function product_action()
	{
		// shut up php
		error_reporting(0);
		ee()->load->helper('xml');

		$output = new Oxymel;

		// create our structure
		$output->xml->products->contains;

		ee()->sync_db = ee()->load->database('sync_db', true);

		$query = ee()->sync_db
			->where('processed', 0)
			->get('products', 500);

		$ids_processed = array();

		foreach($query->result() as $row)
		{

			if($row->tax_exempt == 0)
			{
				$tax_exempt = "Taxable";
			}	
			else
			{
				$tax_exempt = "";
			}

			if(!empty($row->product_category_3))
			{
				$product_category = $row->product_category_3;
			}	
			elseif(!empty($row->product_category_2))
			{
				$product_category = $row->product_category_2;
			}
			else
			{
				$product_category = $row->product_category_1;
			}

			// append a product
			$output
				->product->contains
				  	->title->contains->cdata($row->sku .' - ' . $row->name)->end
				  	->sku($row->sku)
				  	->name->contains->cdata($row->name)->end
				  	->price($row->price)
				  	->weight($row->weight)
				  	->length($row->length)
				  	->width($row->width)
				  	->height($row->height)
				  	->handling($row->handling)
				  	->free_shipping($row->free_shipping)
				  	->tax_exempt($tax_exempt)
				  	->category->contains->cdata($product_category)->end
				  	->product_number($row->product_number)
				  	->manufacturer->contains->cdata($row->product_manufacturer)->end
				  	->container($row->product_container)
				  	->condition($row->product_condition)
				  	->listed($row->product_listed)
				  	->location($row->product_location)
				  	->tested($row->product_tested)
				  	->cosmetic_condition->contains->cdata($row->product_cosmetic_condition)->end
				  	->keywords->contains->cdata($row->product_keywords)->end
				  	->natural_search->contains->cdata($row->product_natural_search)->end
				  	->description->contains->cdata($row->product_description)->end
				  	->image($row->product_image_filename)
				  	->stock_level($row->product_stock_level)
				  	->sell_online($row->product_sell_online)
				  	->timestamp($row->timestamp)
				->end;	

			$ids_processed[] = $row->id;

		}	

		// close our structure
		$output->end;	
			  	
		// update processed flag on records	
		if(count($ids_processed) > 0)
		{
			ee()->sync_db->where_in('id', $ids_processed)->set('processed', 1)->update('products');
		}

		header('Content-Type: text/xml');
		exit($output->to_string());

	} // end product_action()

	/**
	 * shipping_batch()
	 *
	 * reads shipping updates (carrier and tracking numbers) from the shipping table 
	 * (added by fb) and updates the order status with the shipping info
	 */
	private function shipping_batch()
	{
		// load the sync db
		ee()->sync_db = ee()->load->database('sync_db', true);

		// find the most recent unprocessed shipments
		$query = ee()->sync_db->where('processed !=', '1')->order_by('id', 'asc')->get('orders_shipping', 200);

		// create arrays for shipment ids and tracking numbers
		$ids_processed = array();

		// find our shipped status
		$status = Store\Model\Status::where('name', 'Shipped')->first();

		// load the order_map config file
		include_once dirname(dirname(__FILE__)).'/bmi_custom/config/order_map.php';

		// loop through each shipment
		foreach($query->result() as $shipment)
		{
			// add id to the processed array
			$ids_processed[] = $shipment->id;

			if(!empty($shipping_carriers[$shipment->carrier]))
			{
				$tracking = $shipping_carriers[$shipment->carrier] .'|'. $shipment->tracking_number;
			}
			else
			{
				$tracking = $shipment->tracking_number;
			}	

			// update the status of each order with the tracking numbers
			$order_object = Store\Model\Order::find($shipment->order_id);
			$order_object->updateStatus($status, 0, $tracking);

		}

		// update the shipment records to indicate they've been processed
		if(count($ids_processed) > 0)
		{
			ee()->sync_db->where_in('id', $ids_processed)->set('processed', 1)->update('orders_shipping');
		}	
		
		
	} // end of shipping_batch()

	/**
	 * processing_batch()
	 *
	 * reads status updates from the processing table 
	 * and updates the appropriate fields in the ee order data
	 */
	private function processing_batch()
	{
		// load the sync db
		ee()->sync_db = ee()->load->database('sync_db', true);

		// find the most recent unprocessed shipments
		$query = ee()->sync_db->where('processed !=', '1')->order_by('id', 'asc')->get('orders_processed', 50);

		// create arrays for shipment ids and tracking numbers
		$ids_processed = array();

		// find our shipped status
		$status = Store\Model\Status::where('name', 'Processing')->first();

		// loop through each shipment
		foreach($query->result() as $processed)
		{
			// update the status of each order with the tracking numbers
			$order_object = Store\Model\Order::find($processed->order_id);
			if(!empty($order_object))
			{
				$order_object->updateStatus($status, 0, '');
				// add id to the processed array
				$ids_processed[] = $processed->id;
			}
		}

		// update the shipment records to indicate they've been processed
		if(count($ids_processed) > 0)
		{
			ee()->sync_db->where_in('id', $ids_processed)->set('processed', 1)->update('orders_processed');
		}	
		
	} // end of processing_batch()

	/**
	 * inventory_batch()
	 *
	 * reads inventory updates from the products_inventory table 
	 * and updates the appropriate fields in ee 
	 */
	private function inventory_batch()
	{
		// load the sync db
		ee()->sync_db = ee()->load->database('sync_db', true);

		// find the most recent unprocessed shipments
		$query = ee()->sync_db->where('processed !=', '1')->order_by('id', 'asc')->get('products_inventory', 45000);

		// create arrays for processed inventory updates
		$ids_processed = array();

		// create array for the update query
		$inventory_update = array();


		// loop through each shipment
		foreach($query->result() as $inventory)
		{
			// add id to the processed array
			$ids_processed[] = $inventory->id;

			// add this update to the query
			$inventory_update[] = array(
				'sku' => $inventory->sku,
				'stock_level' => $inventory->quantity
			);
			
		} // end foreach

		// if we have records to process, update the stock levels and mark the records as processed
		if(count($ids_processed) > 0)
		{
			// run the update batch query
			ee()->db->update_batch('store_stock', $inventory_update, 'sku'); 

			ee()->sync_db->where_in('id', $ids_processed)->set('processed', 1)->update('products_inventory');
		}			
		
	} // end of inventory_batch()

	/**
	 * product_batch()
	 *
	 * 
	 * 
	 */
	private function product_batch()
	{
		// add call to trigger datagrab here
		//file_get_contents(ee()->config->item('base_url') . '?ACT=25&id=9');

		ee()->sync_db = ee()->load->database('sync_db', true);
		$update_count = ee()->sync_db->count_all_results('products');

		if($update_count > 0)
		{	
			// create curl resource 
	        $ch = curl_init(); 

	        // set url 
	        curl_setopt($ch, CURLOPT_URL, ee()->config->item('base_url') . '?ACT=25&id=' . ee()->config->item('integration_product_import_id')); 
	        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 0); 
	        curl_setopt($ch, CURLOPT_TIMEOUT, 300); 
	        curl_exec($ch); 
	        curl_close($ch);
    	}
	}

	/**
	 * cleanup()
	 *
	 * removes any processed records older than a week
	 * 
	 */
	private function cleanup()
	{
		ee()->sync_db = ee()->load->database('sync_db', true);

		// delete 14 day old processed product records, always leaving at least 5
		ee()->sync_db->query("DELETE FROM products WHERE processed = 1 AND timestamp < DATE_SUB(NOW(), INTERVAL 14 DAY) AND id NOT IN (SELECT id FROM ( SELECT id FROM `products` WHERE processed = 1 ORDER BY id DESC LIMIT 5 ) keepers)");
		
		// delete old inventory records	
		ee()->sync_db->query("DELETE FROM products_inventory WHERE processed = 1 AND timestamp < DATE_SUB(NOW(), INTERVAL 2 DAY)");

		// delete old shipping records
		ee()->sync_db->query("DELETE FROM orders_shipping WHERE processed = 1 AND timestamp < DATE_SUB(NOW(), INTERVAL 14 DAY)");

		// delete old processed records
		ee()->sync_db->query("DELETE FROM orders_processed WHERE processed = 1 AND timestamp < DATE_SUB(NOW(), INTERVAL 14 DAY)");
		
	}


	/**
	 * order_tracking_numbers()
	 *
	 * returns tracking numbers (messages from Shipped statuses)
	 * 
	 */
	public function order_tracking_numbers()
	{
		$order_id = ee()->TMPL->fetch_param('order_id');

		$query = ee()->db
			->where('order_id', $order_id)
			->where('order_status_name', 'Shipped')
			->get('store_order_history');

		$variables = array();

		foreach($query->result() as $row)
		{
			
			$exploded = explode('|', $row->order_status_message);

			if(count($exploded) == 2)
			{
				$variables[] = array(
            		'tracking_url' => $this->get_tracking_url($exploded[0], $exploded[1]),
            		'tracking_number' => $exploded[1]
            	
        		);
			}
			else
			{
				$variables[] = array(
            		'tracking_url' => '',
            		'tracking_number' => $row->order_status_message
            	
        		);
			}	
			
		}	

    	return ee()->TMPL->parse_variables(ee()->TMPL->tagdata, $variables);	

	} // end of order_tracking_numbers()

	/**
	 * get_tracking_url()
	 *
	 * turns a tracking number type (fedex, ups or usps) and tracking number
	 * into a url
	 */
	private function get_tracking_url($type, $tracking_number) {
		$url = '';

		switch($type)
		{
			case "fedex":
				$url = 'http://www.fedex.com/Tracking?language=english&cntry_code=us&tracknumbers=' . $tracking_number;
				break;

			case "ups":
				$url = 'http://wwwapps.ups.com/WebTracking/processInputRequest?TypeOfInquiryNumber=T&InquiryNumber1=' . $tracking_number;
				break;

			case "usps":
				$url = 'https://tools.usps.com/go/TrackConfirmAction?qtc_tLabels1=' . $tracking_number;
				break;		

		}

		return $url;

		
	} // end of get_tracking_url()

	/**
	 * item_primary_image()
	 *
	 * returns the primary image for an item, to be used inside the cart tag that doens't load
	 * custom fields
	 * 
	 */
	public function item_primary_image()
	{
		$product_id = ee()->TMPL->fetch_param('product_id');

		$query = ee()->db
			->select('field_id_32')
			->where('channel_id', 1)
			->where('entry_id', $product_id)
			->get('channel_data');

		return $query->row('field_id_32');	
	} // end of item_primary_image()

	/**
	 * mailing_list_subscribe()
	 *
	 * performs a subscribe to the contact contact list  when the tag is
	 * called from a template
	 */
	public function mailing_list_subscribe()
	{
		// get parameters for first name, last name and email
		$first_name = ee()->TMPL->fetch_param('first_name');
		$last_name = ee()->TMPL->fetch_param('last_name');
		$email = ee()->TMPL->fetch_param('email');

		if(empty($email))
		{
			return;
		}	

		$this->update_constant_contact('create', $email, $first_name, $last_name);

	} // end of mailing_list_subscribe()
	
	/**
	 * update_constant_contact()
	 *
	 * helper function for connecting to constant contact to either add or remove
	 * an address from the list
	 *
	 */
	public function update_constant_contact($action = 'create', $email_address, $first_name = '', $last_name = '')
	{
		// Get API Key, List and Access Token
		$api_key = ee()->config->item('cc_api_key');
		$access_token = ee()->config->item('cc_access_token');
		$list_id = ee()->config->item('cc_list_id');

		if(!$api_key || !$access_token || !$list_id)
		{
			return;
		}	
		
		$returnContact = null;

		$cc = new ConstantContact($api_key);

		// find the contact record if it exists
		$response = $cc->getContactByEmail($access_token, $email_address);

		// if the acton is delete
		if($action == 'delete')
		{	
			// make sure the contact exists first
			if (!empty($response->results))
			{
				// get contact object and delete contact from the list
				$contact = $response->results[0];
				$returnContact = $cc->deleteContactFromList($access_token, $contact, $list_id);
			}	

		}

		// if the action is create
		if($action == 'create')
		{
			// check if the contact exists already
			if (!empty($response->results))
			{
				// get contact object and add list to it
				$contact = $response->results[0];
				$contact->addList($list_id);

				$returnContact = $cc->updateContact($access_token, $contact, true);
			}	
			else
			{
				// since the contact doesn't exist, we make a new one
				$contact = new Contact();
        		$contact->addEmail($email_address);
        		$contact->addList($list_id);
        		$contact->first_name = $first_name;
            	$contact->last_name = $last_name;

        		$returnContact = $cc->addContact($access_token, $contact, false);
			}
			

		}
	} // end of update_constant_contact()

	/**
	 * manufacturers_list()
	 *
	 * this tag allows the list of manufacturers to be listed for the
	 * filter dropdown
	 *
	 */
	public function manufacturers_list()
	{

		$cat_id = ee()->TMPL->fetch_param('cat_id');

		$variables = array();

		$query = ee()->db
			->select('field_id_19')
			->where('channel_id', 1)
			->where('field_id_19 !=', '')
			->like('field_id_3', '[' . $cat_id . ']', 'after')
			->distinct()
			->order_by('field_id_19', 'asc')
			->get('channel_data');

		foreach($query->result() as $row)
		{
			$variables[] = array(
        		'manufacturer' => $row->field_id_19
    		);
			
		}

		if(count($variables) < 1)
		{
			return '';
		}	

    	return ee()->TMPL->parse_variables(ee()->TMPL->tagdata, $variables);

	} // end of manufacturers_list()

	/**
	 * cart_no_shipping()
	 *
	 * this tag allows us to output the total weight of the cart
	 * and whether or not the cart has items with missing dimensions
	 *
	 */
	public function cart_no_shipping()
	{

		// get the store cart cookie
		$cookie = ee()->input->cookie('store_cart');

		// if the cookie has a value
		if ($cookie) {

			// query for the items in the order
			$query = ee()->db
				->select('sku, exp_store_order_items.url_title, weight, height, width, length, field_id_26 as container')
	        	->where('order_hash', $cookie)
	        	->join('store_orders', 'exp_store_orders.id = exp_store_order_items.order_id')
	        	->join('channel_data', 'exp_channel_data.entry_id = exp_store_order_items.entry_id')
	        	->get('store_order_items');

	        // default the values
	        $vars['cart_weight'] = 0;
	        $vars['missing_dimensions'] = 'n';
	        $vars['container_self'] = 'n';

	        // loop through the cart and update the values
	        foreach($query->result() as $item)
	        {
	        	if(!empty($item->weight))
	        	{
	        		$vars['cart_weight'] += $item->weight;
	        	}	
	        	

	        	if(!$item->weight || !$item->height || !$item->width || !$item->length)
	        	{
	        		$vars['missing_dimensions'] = 'y';
	        	}

	        	if($item->container == 'Self')
	        	{
	        		$vars['container_self'] = 'y';
	        	}	
	        }

	    	// parse the variables into the tagdata
	    	return ee()->TMPL->parse_variables_row(ee()->TMPL->tagdata, $vars);
	        	
        } // end of if

	} // end of cart_no_shipping

	/**
	 * newest_entry_start()
	 *
	 * this tag calculates the start date for the newest listings page (7 days agp)
	 *
	 */
	public function newest_entry_start()
	{
		return ee()->localize->format_date('%Y-%m-%d %H:%i', ee()->localize->now - 604800);
	}

	/**
	 * state_options()
	 *
	 * this tag outputs state options for editing the user profile
	 *
	 */
	public function state_options()
	{
		$states = ee()->db
				->where('country_id', 233)
				->get('store_states');

		$output = '<option value="">--Select State--</option>' . "\n";

		foreach($states->result() as $state)
		{
			if($state->code == ee()->TMPL->fetch_param('state_code'))
			{
				$selected = ' selected="selected"';
			}
			else
			{
				$selected = '';
			}

			$output .= '<option value="' . $state->code . '"' . $selected . ' >' . $state->name . '</option>' . "\n";

		}		

		return $output;
	}

	/**
	 * country_options()
	 *
	 * this tag outputs country options for editing the user profile
	 *
	 */
	public function country_options()
	{
		$countries = ee()->db
				->where('enabled', 1)
				->get('store_countries');

		$output = '<option value="">--Select Country--</option>' . "\n";

		foreach($countries->result() as $country)
		{
			if($country->code == ee()->TMPL->fetch_param('country_code'))
			{
				$selected = ' selected="selected"';
			}
			else
			{
				$selected = '';
			}

			$output .= '<option value="' . $country->code . '"' . $selected . ' >' . $country->name . '</option>' . "\n";

		}		

		return $output;
	}

	
}
/* End of file mod.bmi_custom.php */
/* Location: /system/expressionengine/third_party/bmi_custom/mod.bmi_custom.php */