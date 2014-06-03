<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// include base class
if ( ! class_exists('Low_search_base'))
{
	require_once(PATH_THIRD.'low_search/base.low_search.php');
}

/**
 * Low Search extension class
 *
 * @package        low_search
 * @author         Lodewijk Schutte <hi@gotolow.com>
 * @link           http://gotolow.com/addons/low-search
 * @copyright      Copyright (c) 2014, Low
 */
class Low_search_ext extends Low_search_base {

	// --------------------------------------------------------------------
	// PROPERTIES
	// --------------------------------------------------------------------

	/**
	 * Do settings exist?
	 *
	 * @access      public
	 * @var         bool
	 */
	public $settings_exist = TRUE;

	/**
	 * Settings array
	 *
	 * @var        array
	 * @access     public
	 */
	public $settings = array();

	/**
	 * Extension is required by module
	 *
	 * @access      public
	 * @var         array
	 */
	public $required_by = array('module');

	// --------------------------------------------------------------------
	// PUBLIC METHODS
	// --------------------------------------------------------------------

	/**
	 * Constructor
	 *
	 * @access      public
	 * @param       array
	 * @return      void
	 */
	public function __construct($settings = array())
	{
		// Call Base constructor
		parent::__construct();

		// Set the Settings object
		ee()->low_search_settings->set($settings);

		// Assign current settings
		$this->settings = ee()->low_search_settings->get();
	}

	// --------------------------------------------------------------------

	/**
	 * Settings: redirect to module
	 *
	 * @access      public
	 * @return      void
	 */
	public function settings()
	{
		ee()->functions->redirect($this->mcp_url('settings'));
	}

	// --------------------------------------------------------------------
	// HOOKS
	// --------------------------------------------------------------------

	/**
	 * Add/modify entry in search index
	 *
	 * @access      public
	 * @param       int
	 * @param       array
	 * @param       array
	 * @return      void
	 */
	public function entry_submission_end($entry_id, $meta, $data)
	{
		// Use the index lib to rebuild this entry
		ee()->load->library('Low_search_index');
		ee()->low_search_index->build(FALSE, $entry_id);
	}

	/**
	 * Delete entry from search index
	 *
	 * @access      public
	 * @param       int
	 * @param       int
	 * @return      void
	 */
	public function delete_entries_loop($entry_id, $channel_id)
	{
		ee()->low_search_index_model->delete($entry_id, 'entry_id');
	}

	/**
	 * Add search score to channel entries
	 *
	 * @access      public
	 * @param       object
	 * @param       array
	 * @return      array
	 */
	public function channel_entries_query_result($obj, $query)
	{
		// -------------------------------------------
		// Get the latest version of $query
		// -------------------------------------------

		if (ee()->extensions->last_call !== FALSE)
		{
			$query = ee()->extensions->last_call;
		}

		// -------------------------------------------
		// Bail out if we're not Low Searching
		// -------------------------------------------

		if (ee()->TMPL->fetch_param('low_search') != 'yes') return $query;

		// -------------------------------------------
		// Get variables from parameters
		// -------------------------------------------

		$vars = ee()->low_search_params->get_vars(ee()->low_search_settings->prefix);

		// -------------------------------------------
		// Add shortcut data to vars
		// -------------------------------------------

		if ($row = low_get_cache(LOW_SEARCH_PACKAGE, 'shortcut'))
		{
			foreach (ee()->low_search_shortcut_model->get_template_attrs() AS $key)
			{
				$vars[ee()->low_search_settings->prefix.$key] = $row[$key];
			}
		}

		// -------------------------------------------
		// Loop through entries and add items
		// -------------------------------------------

		foreach ($query AS &$row)
		{
			// Add all search parameters to entry
			$row = array_merge($row, $vars);
		}

		// Check what the filters are doing
		$query = ee()->low_search_filters->results($query);

		return $query;
	}

	// --------------------------------------------------------------------

	/**
	 * Category hooks
	 */
	public function category_save($cat_id, $data)
	{
		return $this->_update_index_by_category(array($cat_id));
	}

	/**
	 * Category hooks
	 */
	public function category_delete($cat_ids)
	{
		return $this->_update_index_by_category($cat_ids);
	}

	// --------------------------------------------------------------------
	// PRIVATE METHODS
	// --------------------------------------------------------------------

	/**
	 * Update by category
	 */
	private function _update_index_by_category($cat_ids)
	{
		if (empty($cat_ids)) return $cat_ids;

		// Get entries for this category
		$query = ee()->db->select('entry_id')
		       ->from('category_posts')
		       ->where_in('cat_id', $cat_ids)
		       ->get();

		$entry_ids = low_flatten_results($query->result_array(), 'entry_id');
		$entry_ids = array_unique($entry_ids);

		if ($entry_ids)
		{
			ee()->load->library('Low_search_index');
			ee()->low_search_index->build(FALSE, $entry_ids);
		}
	}
}
/* End of file ext.low_search.php */