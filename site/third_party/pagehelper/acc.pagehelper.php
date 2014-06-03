<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * ExpressionEngine Page Helper Accessory
 *
 * Helps users generate a Pages Module URI by allowing them to generate a Page URI by selecting a parent page
 * and automatically pulling the URL Title from the entry.
 *
 * @package		Page Helper
 * @author		Conflux Group, Inc. <support@confluxgroup.com>
 * @link		http://confluxgroup.com
 * @addon link	http://devot-ee.com/add-ons/page-helper/
 * @copyright 	Copyright (c) 2013 Conflux Group, Inc.
 * @license   	http://confluxgroup.com/addons/license.txt
 * @version     1.1.1
 */
 
class Pagehelper_acc {

	var $name			= 'Page Helper';
	var $id				= 'pagehelper';
	var $version		= '1.1.1';
	var $description	= 'Helps users generate a Pages Module URI by allowing them to select a parent and automatically pulling the URL Title from the entry.';
	var $sections		= array();
	
	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->EE =& get_instance();
	}
	
	/**
	 * Set Sections
	 *
	 * Set content for the accessory
	 *
	 * @access	public
	 * @return	void
	 */
	public function set_sections()
	{	
		// Make sure the accessory only runs on the publish page by updating the 
		// database.
		$this->EE->db->where('class', 'Pagehelper_acc');
		$this->EE->db->update('accessories', array('controllers' => 'content_publish|content_edit'));
		
		// Store the CSS for the accessory in $css.
		$css = '
		<style type="text/css">
			/* Styles for Page Helper Accessory */
			#pages_dropdown {
				margin-right: 15px;
			}

			#page_url_title {
				width: 300px;
				margin-left: 15px;
			}

			#generate_uri {
				height: 100%;
				width: auto;
				margin-left: 15px;

			}	
		</style>
		';
		
		// Add the CSS to the <head> of the page.
		$this->EE->cp->add_to_head($css);
		
		// Load the Page Helper JavaScript file.
		$this->EE->cp->load_package_js('pagehelper');
		
		// Get the list of pages and output it to the accessory tab
		$this->sections['Pages'] = $this->_page_select(); 
	
	} // end of set_sections()
	
	/**
	 * Page Select
	 *
	 * Generates HTML for a dropdown menu of Pages
	 *
	 * @access	private
	 * @return	string
	 */
	private function _page_select(){
		// Gather the site_pages and site_id config items.
		$site_pages = $this->EE->config->item('site_pages');
		$site_id = $this->EE->config->item('site_id');
		
		// Open the <select> tag and add the first two options, the select prompt and / (No Parent)
		$return = "<select style='display:none;' id='pages_dropdown'><option value=''>Select a Parent</option><option value=''>/ (No Parent)</option>";
		
		// Make sure there are some URIs to work with
		if(isset($site_pages[$site_id]["uris"]) && is_array($site_pages[$site_id]["uris"]))
		{
			// Set $pages to the array of URIs from the site_pages config item and sort the elements.
			$pages = $site_pages[$site_id]["uris"];
			sort($pages);
			
			// Loop through the pages and add each as an option to the <select> menu.
			if (sizeof($pages)>0){
				foreach ($pages as $k=>$v){
					if($v != '/')
						$return .= "<option value='".$v."'>".$v."</option>";
				}
			}
		}
		
		// Add the closing </select> tag.
		$return .= "</select>";
		
		// Return the generated HTML.
		return $return;
	} // end of _page_select()
	
	/**
	 * Install
	 *
	 * Verifies that the Pages module is installed before installing Page Helper. Redirects
	 * to the Modules page for Pages installation if it's not already installed.
	 *
	 * @access	public
	 * @return	void
	 */
	public function install()
	{
		
		// Get the list of installed addons.
		$addons = $this->EE->addons->get_installed($type = 'modules');
		
		// Check if Pages is not in the list of installed addons.
		if(!isset($addons['pages']))
		{
			// Since Pages is not installed, we'll set an error message.
			$this->EE->session->set_flashdata('message_failure', 'You do not have the Pages module installed. Please install it first before installing Page Helper');
			
			// Then we redirect the user to the Modules page, to install Pages.
			$this->EE->functions->redirect(BASE.AMP.'C=addons_modules');	
			
		}
	
	} // end of install()
}
// END CLASS

/* End of file acc.pagehelper.php */
/* Location: ./system/expressionengine/third_party/pagehelper/acc.pagehelper.php */