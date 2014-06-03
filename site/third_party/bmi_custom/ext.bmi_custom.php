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
 * BMI Custom Extension
 *
 * @package		ExpressionEngine
 * @subpackage	Addons
 * @category	Extension
 * @author		Conflux Group Inc
 * @link		http://confluxgroup.com
 */

class Bmi_custom_ext {
	
	public $settings 		= array();
	public $description		= 'BMI Custom Add-on';
	public $docs_url		= '';
	public $name			= 'BMI Custom';
	public $settings_exist	= 'n';
	public $version			= '1.0';
	
	private $EE;
	
	/**
	 * Constructor
	 *
	 * @param 	mixed	Settings array or empty string if none exist.
	 */
	public function __construct($settings = '')
	{
		$this->settings = $settings;
	}// ----------------------------------------------------------------------
	
	/**
	 * Activate Extension
	 *
	 * This function enters the extension into the exp_extensions table
	 *
	 * @see http://codeigniter.com/user_guide/database/index.html for
	 * more information on the db class.
	 *
	 * @return void
	 */
	public function activate_extension()
	{
		// Setup custom settings in this array.
		$this->settings = array();
		
		$batch = array();

		$batch[] = array(
			'class'		=> __CLASS__,
			'method'	=> 'store_order_complete_end',
			'hook'		=> 'store_order_complete_end',
			'settings'	=> serialize($this->settings),
			'version'	=> $this->version,
			'enabled'	=> 'y'
		);

		$batch[] = array(
			'class'		=> __CLASS__,
			'method'	=> 'sessions_end',
			'hook'		=> 'sessions_end',
			'settings'	=> serialize($this->settings),
			'version'	=> $this->version,
			'enabled'	=> 'y'
		);

		$batch[] = array(
			'class'		=> __CLASS__,
			'method'	=> 'store_payment_gateways',
			'hook'		=> 'store_payment_gateways',
			'settings'	=> serialize($this->settings),
			'version'	=> $this->version,
			'enabled'	=> 'y'
		);

		$batch[] = array(
			'class'		=> __CLASS__,
			'method'	=> 'freemember_update_member_custom_start',
			'hook'		=> 'freemember_update_member_custom_start',
			'settings'	=> serialize($this->settings),
			'version'	=> $this->version,
			'enabled'	=> 'y'
		);

		ee()->db->insert_batch('extensions', $batch);	

					
		
	}	

	// ----------------------------------------------------------------------
	
	/**
	 * store_order_complete_end
	 *
	 * This hook is called at the completion of an order. It takes the order object
	 * and outputs all the relevent details to the sync db, so Fishbowl can collect
	 * the information and import it.
	 * 
	 */
	public function store_order_complete_end($order)
	{
		// load the sync db
		ee()->sync_db = ee()->load->database('sync_db', true);

		// load the order_map config file
		include_once dirname(dirname(__FILE__)).'/bmi_custom/config/order_map.php';

		$order_array = $order->toTagArray();
		//file_put_contents('./' . time() . '-order.json', json_encode($order_array));

		$order_record = array();

		// loop through all the static fields from the order map
		foreach($order_fields as $field=>$key)
		{
			$order_record[$field] = $order_array[$key];
		}

		// now we do some replacements for shipping, tax and payment based on the mappings
		if(!empty($shipping_methods[$order_record['shipping_carrier']]))
		{
			$order_record['shipping_carrier'] = $shipping_methods[$order_record['shipping_carrier']];
		}	

		if(!empty($payment_methods[$order_record['order_payment_method']]))
		{
			$order_record['order_payment_method'] = $payment_methods[$order_record['order_payment_method']];
		}	

		if(empty($order_record['order_tax_rate_name']))
		{

			if($order_record['shipping_state'] == 'MA')
			{
				$order_record['order_tax_rate_name'] = 'Non Taxable';
			}
			else
			{
				$order_record['order_tax_rate_name'] = 'Out of State';
			}	

		}


		ee()->sync_db->insert('orders', $order_record);

		// loop through the items in the order
		foreach($order_array['items'] as $item)
		{
			$item_record = array();

			// loop through the static fields from the item_map
			foreach($item_fields as $field=>$key)
			{
				$item_record[$field] = $item[$key];
			}

			ee()->sync_db->insert('order_items', $item_record);	
		}			  
	}

	/**
     * This hook is called when Store is searching for available payment gateways
     * We will use it to tell Store about our custom gateway (Wire Transfer)
     */
    public function store_payment_gateways($gateways)
    {
        // tell Store about our new payment gateway
        // (this must match the name of your gateway in the Omnipay directory)
        $gateways[] = 'WireTransfer';

        // tell PHP where to find the gateway classes
        // Store will automatically include your files when they are needed
        $composer = require(PATH_THIRD.'store/autoload.php');
        $composer->add('Omnipay', __DIR__);

        return $gateways;
    }

    /**
     * Will update constant contact when member updates their profile or registers
     * 
     */
    public function freemember_update_member_custom_start($member_id, $data)
    {
    	require(PATH_THIRD.'bmi_custom/mod.bmi_custom.php');

    	$mod = new Bmi_custom();

    	if(!empty($data['m_subscribe']))
    	{
    		if($data['m_subscribe'] == 'y')
    		{
    			$mod->update_constant_contact('create', $data['email'], $data['m_first_name'], $data['m_last_name']);
    		}	
    		else
    		{
    			$mod->update_constant_contact('delete', $data['email'], $data['m_first_name'], $data['m_last_name']);
    		}	

    	}

    	return $data;
    }

    // just in case we need it.
	public function sessions_end()
	{
		// nothing to see here
	}

	// ----------------------------------------------------------------------

	/**
	 * Disable Extension
	 *
	 * This method removes information from the exp_extensions table
	 *
	 * @return void
	 */
	function disable_extension()
	{
		ee()->db->where('class', __CLASS__);
		ee()->db->delete('extensions');
	}

	// ----------------------------------------------------------------------

	/**
	 * Update Extension
	 *
	 * This function performs any necessary db updates when the extension
	 * page is visited
	 *
	 * @return 	mixed	void on update / false if none
	 */
	function update_extension($current = '')
	{
		if ($current == '' OR $current == $this->version)
		{
			return FALSE;
		}
	}	
	
	// ----------------------------------------------------------------------
}

/* End of file ext.bmi_custom.php */
/* Location: /system/expressionengine/third_party/bmi_custom/ext.bmi_custom.php */