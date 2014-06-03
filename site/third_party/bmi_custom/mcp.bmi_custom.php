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
 * BMI Custom Module Control Panel File
 *
 * @package		ExpressionEngine
 * @subpackage	Addons
 * @category	Module
 * @author		Conflux Group (Jeremy Gimbel)
 * @link		http://confluxgroup.com
 */

class Bmi_custom_mcp {
	
	public $return_data;
	
	private $_base_url;
	
	/**
	 * Constructor
	 */
	public function __construct()
	{}
		
		$this->_base_url = BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=bmi_custom';
		
		ee()->cp->set_right_nav(array(
			'module_home'	=> $this->_base_url,
			// Add more right nav items here.
		));
	}
	
	// ----------------------------------------------------------------

	/**
	 * Index Function
	 *
	 * @return 	void
	 */
	public function index()
	{
		ee()->cp->set_variable('cp_page_title', 
								lang('bmi_custom_module_name'));
		
		/**
		 * This is the addons home page, add more code here!
		 */		
	}

	/**
	 * Start on your custom code here...
	 */
	
}
/* End of file mcp.bmi_custom.php */
/* Location: /system/expressionengine/third_party/bmi_custom/mcp.bmi_custom.php */