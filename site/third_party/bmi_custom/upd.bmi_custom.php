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
 * BMI Custom Module Install/Update File
 *
 * @package		ExpressionEngine
 * @subpackage	Addons
 * @category	Module
 * @author		Conflux Group (Jeremy Gimbel)
 * @link		http://confluxgroup.com
 */

class Bmi_custom_upd {
	
	public $version = '1.0';
	
	private $EE;
	
	/**
	 * Constructor
	 */
	public function __construct()
	{
		
	}
	
	// ----------------------------------------------------------------
	
	/**
	 * Installation Method
	 *
	 * @return 	boolean 	TRUE
	 */
	public function install()
	{
		$mod_data = array(
			'module_name'			=> 'Bmi_custom',
			'module_version'		=> $this->version,
			'has_cp_backend'		=> "n",
			'has_publish_fields'	=> 'n'
		);
		
		ee()->db->insert('modules', $mod_data);
		
		$act_data = array(
    		'class'     => 'Bmi_custom' ,
    		'method'    => 'cron_action'
		);

		ee()->db->insert('actions', $act_data);

		$act_data = array(
    		'class'     => 'Bmi_custom' ,
    		'method'    => 'product_action'
		);

		ee()->db->insert('actions', $act_data);

		
		
		return TRUE;
	}

	// ----------------------------------------------------------------
	
	/**
	 * Uninstall
	 *
	 * @return 	boolean 	TRUE
	 */	
	public function uninstall()
	{
		$mod_id = ee()->db
			->select('module_id')
			->get_where('modules', array(
					'module_name'	=> 'Bmi_custom'
				))
			->row('module_id');
		
		ee()->db
			->where('module_id', $mod_id)
			->delete('module_member_groups');
		
		ee()->db
			->where('module_name', 'Bmi_custom')
			 ->delete('modules');

		ee()->db
			->where('class', 'Bmi_custom')
			->delete('actions');			 
		
		return TRUE;
	}
	
	// ----------------------------------------------------------------
	
	/**
	 * Module Updater
	 *
	 * @return 	boolean 	TRUE
	 */	
	public function update($current = '')
	{
		// If you have updates, drop 'em in here.
		return TRUE;
	}
	
}
/* End of file upd.bmi_custom.php */
/* Location: /system/expressionengine/third_party/bmi_custom/upd.bmi_custom.php */