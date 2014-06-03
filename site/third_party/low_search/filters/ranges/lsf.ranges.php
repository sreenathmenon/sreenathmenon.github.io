<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Filter by search:title="foo"
 *
 * @package        low_search
 * @author         Lodewijk Schutte ~ Low <hi@gotolow.com>
 * @link           http://gotolow.com/addons/low-search
 * @copyright      Copyright (c) 2014, Low
 */
class Low_search_filter_ranges extends Low_search_filter {

	/**
	 * Separator character for ranges
	 */
	private $_sep = '|';

	/**
	 * Search parameters for range:field params and return set of ids that match it
	 *
	 * @access      private
	 * @return      void
	 */
	public function filter($entry_ids)
	{
		// --------------------------------------
		// Get ranges params
		// --------------------------------------

		$params = array_filter(array_merge(
			$this->params->get_prefixed('range:'),
			$this->params->get_prefixed('range-from:'),
			$this->params->get_prefixed('range-to:')
		), 'low_not_empty');

		// --------------------------------------
		// Don't do anything if nothing's there
		// --------------------------------------

		if (empty($params)) return $entry_ids;

		// --------------------------------------
		// Log it
		// --------------------------------------

		$this->_log('Applying '.__CLASS__);

		// --------------------------------------
		// Validate params
		// --------------------------------------

		$ranges = array();

		foreach ($params AS $key => $val)
		{
			// Split key
			list($prefix, $field) = explode(':', $key, 2);

			// Skip invalid fields
			if ( ! ($field_id = $this->_get_field_id($field))) continue;

			// Init this range
			$from = $to = NULL;

			// Check prefix and get from/to values accordingly
			switch ($prefix)
			{
				case 'range':
					// Fallback to ;
					$char = strpos($val, ';') ? ';' : $this->_sep;
					if (strpos($val, $char)) list($from, $to) = explode($char, $val, 2);
				break;

				case 'range-from':
					$from = $val;
				break;

				case 'range-to':
					$to = $val;
				break;
			}

			$from = $this->_validate_value($from, $field);
			$to   = $this->_validate_value($to, $field);

			// If both are invalid, skip it
			if (is_null($from) && is_null($to)) continue;

			// Add from value to field
			if ( ! is_null($from))
			{
				$ranges[$field]['from'] = $from;
			}

			// Add to value to field
			if ( ! is_null($to))
			{
				$ranges[$field]['to'] = $to;
			}

			// Add sql field name to field
			$ranges[$field]['field'] = 'field_id_'.$field_id;
		}

		// --------------------------------------
		// No ranges, bail out
		// --------------------------------------

		if (empty($ranges))
		{
			$this->_log('No valid ranges found');
			return $entry_ids;
		}

		// Get channel IDs before starting the query
		$channel_ids = ee()->low_search_collection_model->get_channel_ids($this->params->get('collection'));

		// --------------------------------------
		// Start query
		// --------------------------------------

		ee()->db->select('entry_id')->from('channel_data');

		// --------------------------------------
		// Limit by channel ids?
		// --------------------------------------

		if ($channel_ids)
		{
			ee()->db->where_in('channel_id', $channel_ids);
		}

		// --------------------------------------
		// Limit by site ids?
		// --------------------------------------

		if ($site_ids = $this->params->site_ids())
		{
			ee()->db->where_in('site_id', $site_ids);
		}

		// --------------------------------------
		// Limit by given entry ids?
		// --------------------------------------

		if ( ! empty($entry_ids))
		{
			ee()->db->where_in('entry_id', $entry_ids);
		}

		// --------------------------------------
		// And filter by the ranges
		// --------------------------------------

		foreach ($ranges AS $field => $range)
		{
			// Exclude values from range?
			$exclude = $this->params->in_param("range:{$field}", 'exclude');

			// Limit by Greater Than option
			if (isset($range['from']))
			{
				$gt = ($exclude || $this->params->in_param("range-from:{$field}", 'exclude'))
					? ' >'
					: ' >=';

				ee()->db->where($range['field'].$gt, $range['from']);
			}

			// Limit by Lesser Than option
			if (isset($range['to']))
			{
				$lt = ($exclude || $this->params->in_param("range-to:{$field}", 'exclude'))
					? ' <'
					: ' <=';

				ee()->db->where($range['field'].$lt, $range['to']);
			}
		}

		// --------------------------------------
		// Execute!
		// --------------------------------------

		$query = ee()->db->get();

		// --------------------------------------
		// And get the entry ids
		// --------------------------------------

		$entry_ids = low_flatten_results($query->result_array(), 'entry_id');
		$entry_ids = array_unique($entry_ids);

		return $entry_ids;
	}

	/**
	 * Validate range value
	 */
	private function _validate_value($val, $field)
	{
		if (is_numeric($val) || is_null($val))
		{
			return $val;
		}
		elseif ($this->_is_date_field($field))
		{
			// @todo: add support for EE < 2.6?
			ee()->load->library('localize');
			return ee()->localize->string_to_timestamp($val);
		}
		else
		{
			return NULL;
		}
	}

	/**
	 * Check whether given string is a date field
	 */
	private function _is_date_field($str)
	{
		$it = FALSE;

		if ($fields = low_get_cache('channel', 'date_fields'))
		{
			$it = (bool) $this->_get_field_id($str, $fields);
		}

		return $it;
	}

	/**
	 * Results: remove rogue {low_search_range...:...} vars
	 */
	public function results($query)
	{
		$this->_remove_rogue_vars(array('range:', 'range-from:', 'range-to:'));
		return $query;
	}

}
// End of file lsf.ranges.php