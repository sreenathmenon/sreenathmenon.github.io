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

/**
 * NavEE Base Model
 *
 * @package        NavEE
 * @category       model
 * @author         The Outfit, Inc
 * @link           http://fromtheoutfit.com/navee
 * @copyright      Copyright (c) 2012 - 2014, The Outfit, Inc.
 */
class Navee_base
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
     * Returns the name of a nav by the id
     *
     * @access public
     * @param int $id
     * @return string
     */

    public function get_nav_name_by_id($id)
    {
        $data = '';

        // query
        $this->EE->db->select("nav_name");
        $this->EE->db->where("navigation_id", $id);
        $this->EE->db->where("site_id", $this->site_id);
        $q = $this->EE->db->get("navee_navs");

        // object
        if ($q->num_rows() == 1)
        {
            $data = $q->row()->nav_name;
        }

        return $data;
    }

    /**
     * get nav settings for a given nav
     *
     * @access public
     * @param int $id
     * @return object
     */

    public function get_nav_settings($id)
    {
        $prefix = 'nav_settings_' . $id;

        $this->EE->db->where('site_id', $this->site_id);
        $this->EE->db->like('k', $prefix, 'after');
        return $this->EE->db->get('navee_config');
    }

    /**
     * returns an array of all nav settings for a given nav
     *
     * @access public
     * @param int $id
     * @return array
     */

    public function get_nav_settings_array($id)
    {
        $data   = array(
            'channels'  => array(),
            'templates' => array(),
        );
        $prefix = 'nav_settings_' . $id . '_';

        $settings = $this->get_nav_settings($id);

        if ($settings->num_rows() > 0)
        {
            foreach ($settings->result() as $s)
            {
                switch ($s->k)
                {
                    case $prefix . 'template_hidden':
                        array_push($data['templates'], $s->v);
                        break;
                    case $prefix . 'channel_hidden':
                        array_push($data['channels'], $s->v);
                        break;
                }
            }
        }

        return $data;
    }

    /**
     * Returns an object of templates
     *
     * @access public
     * @return object
     */
    public function get_templates()
    {
        $this->EE->db->select('t.template_id, t.template_name, tg.group_name, tg.group_order');
        $this->EE->db->from('templates as t');
        $this->EE->db->join('template_groups as tg', 'tg.group_id = t.group_id', 'LEFT OUTER');
        $this->EE->db->where('t.site_id', $this->site_id);
        $this->EE->db->where('tg.site_id', $this->site_id);
        $this->EE->db->order_by('tg.group_order', 'asc');
        $this->EE->db->order_by('t.template_name', 'asc');
        return $this->EE->db->get();
    }
}

/* End of file navee_base.php */
/* Location: ./system/expressionengine/third_party/navee/models/navee_base.php */