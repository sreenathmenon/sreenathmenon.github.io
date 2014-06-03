<?php
/*
 __    __                                   __       ___     __
/\ \__/\ \                                 /\ \__  /'___\ __/\ \__
\ \ ,_\ \ \___      __         ___   __  __\ \ ,_\/\ \__//\_\ \ ,_\
 \ \ \/\ \  _ `\  /'__`\      / __`\/\ \/\ \\ \ \/\ \ ,__\/\ \ \ \/
  \ \ \_\ \ \ \ \/\  __/     /\ \L\ \ \ \_\ \\ \ \_\ \ \_/\ \ \ \ \_
   \ \__\\ \_\ \_\ \____\    \ \____/\ \____/ \ \__\\ \_\  \ \_\ \__\
    \/__/ \/_/\/_/\/____/     \/___/  \/___/   \/__/ \/_/   \/_/\/__/
*/

if (!defined('BASEPATH'))
{
    exit('No direct script access allowed');
}

// config
require_once PATH_THIRD . 'navee/config' . EXT;
require_once PATH_THIRD . 'navee/models/navee_base' . EXT;

/**
 * NavEE CP Model
 *
 * @package        NavEE
 * @category       model
 * @author         The Outfit, Inc
 * @link           http://fromtheoutfit.com/navee
 * @copyright      Copyright (c) 2012 - 2014, The Outfit, Inc.
 */
class Navee_cp extends Navee_base
{

    public function __construct()
    {
        // ee super object
        $this->EE      =& get_instance();
        $this->site_id = $this->EE->config->item('site_id');

        // comment out the following line to enable caching
        $this->EE->db->cache_off();
    }

    /**
     * Delete nav_settings for a given nav
     *
     * @access public
     * @param string $prefix
     * @return void
     */

    public function delete_nav_settings($prefix)
    {
        $this->EE->db->where('site_id', $this->site_id);
        $this->EE->db->like('k', $prefix, 'after');
        $this->EE->db->delete('navee_config');
    }

    /**
     * Inserts new nav_settings for a given nav
     *
     * @access public
     * @param array $data
     * @return void
     */

    public function set_nav_settings($data)
    {
        if (sizeof($data) > 0)
        {
            $this->EE->db->insert_batch('navee_config', $data);
        }
    }


}

/* End of file navee_cp.php */
/* Location: ./system/expressionengine/third_party/navee/models/navee_cp.php */