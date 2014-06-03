<?php
/*
_|                                                      _|
_|_|_|     _|_|     _|_|   _|    _|   _|_|_| _|_|_|   _|_|_|_|
_|    _| _|    _| _|    _| _|    _| _|    _| _|    _|   _|
_|    _| _|    _| _|    _| _|    _| _|    _| _|    _|   _|
_|_|_|     _|_|     _|_|     _|_|_|   _|_|_| _|    _|     _|_|
                                 _|
                             _|_|

Description:		NavEE Module for Expression Engine 2.x
Developer:			Booyant, Inc.
Website:			www.booyant.com/navee
Location:			./system/expressionengine/third_party/modules/navee/mod.navee.php
Contact:			navee@booyant.com  / 978.OKAY.BOB

*/

if (!defined('BASEPATH')) exit('No direct script access allowed');

// Include config file
require_once PATH_THIRD . 'navee/config' . EXT;

class Navee
{

    var $version = NAVEE_VERSION;
    var $navigation_id = 0;
    var $navee_id = 0;
    var $parent = 0;
    var $recursive = TRUE;
    var $ignore_include_in_nav = FALSE;
    var $nav_class = "";
    var $nav_id = "";
    var $last_class = "";
    var $first_class = "";
    var $selected_class = "selected";
    var $parent_selected_class = "selected";
    var $no_selected = FALSE;
    var $selected_class_on_parents = FALSE;
    var $list_type = "ul";
    var $nav = array();
    var $no_last_anchor = FALSE;
    var $spacer = "";
    var $no_last_spacer = FALSE;
    var $last_item = "";
    var $last_item_link = "";
    var $has_kids = FALSE;
    var $kid_count = 0;
    var $start_nav_from_parent = FALSE;
    var $start_nav_from_parent_depth = 1;
    var $start_x_levels_above_selected = 0;
    var $start_nav_on_level_of_selected = FALSE;
    var $start_nav_with_kids_of_selected = FALSE;
    var $only_display_children_of_selected = FALSE;
    var $display_all_children_of_selected = FALSE;
    var $display_entire_branch = FALSE;
    var $max_depth = 0;
    var $ee_install_directory = "";
    var $include_index = FALSE;
    var $escaped_index_page = "index\.php";
    var $include_single_parent = FALSE;
    var $reverse = FALSE;
    var $ignore_regex = FALSE;
    var $force_trailing_slash = "no";
    var $get_info_about_selected = FALSE;
    var $depth_of_selected = 0;
    var $selected_has_kids = FALSE;
    var $cache_disabled = FALSE;
    var $site_url_prefix = FALSE;
    var $site_url_has_trailing_slash = FALSE;
    var $kids_class = FALSE;
    var $navee_uid = '';
    var $disable_title = FALSE;
    var $skip_closed_entries = FALSE;

    function Navee()
    {
        // ExpressionEngine super object
        $this->EE =& get_instance();

        // Set the Site ID
        $this->site_id = $this->EE->config->item('site_id');

        // Determine if EE is installed in a subdirectory
        $this->EE->db->select("k,v");
        $this->EE->db->where("site_id", $this->site_id);
        $keys = array("include_index", "install_directory", "force_trailing_slash", "cache_disabled", "site_url_prefix");
        $this->EE->db->where_in("k", $keys);
        $q = $this->EE->db->get("navee_config");

        if ($q->num_rows() > 0)
        {
            foreach ($q->result() as $r)
            {
                switch ($r->k)
                {
                    case "install_directory":
                        $this->ee_install_directory = $this->_formatInstallDirectory($r->v);
                        break;
                    case "include_index":
                        $this->include_index = $r->v;
                        break;
                    case "force_trailing_slash":
                        $this->force_trailing_slash = $r->v;
                        break;
                    case "cache_disabled":
                        if ($r->v == 'true')
                        {
                            $this->cache_disabled = TRUE;
                        }
                        break;
                    case "site_url_prefix":
                        if ($r->v == 'true')
                        {
                            $this->site_url_prefix = TRUE;

                            if (substr($this->EE->config->slash_item('site_url'), -1) == '/')
                            {
                                $this->site_url_has_trailing_slash = TRUE;
                            }
                        }
                        break;
                }
            }
        }
        $q->free_result();

        // Escape index page
        if (strlen($this->EE->config->item('index_page')) > 0)
        {
            $this->escaped_index_page = str_replace(".", "\.", $this->EE->config->item('index_page'));
        }

        // Comment out the following line to enable caching
        $this->EE->db->cache_off();

        // Uncomment for testing
        $this->EE->db->save_queries = TRUE;
    }

    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~>>
    //	N A V
    //
    //		P A R A M E T E R S
    //		------------------------
    //			* nav_title
    //			* start_node						(optional)
    //			* no_children						(optional)
    //			* class 							(optional)
    //			* id 								(optional)
    //			* ignore_include_in_nav				(optional)
    //			* selected_class_on_parents 		(optional)
    //			* last_class						(optional)
    //			* first_class						(optional)
    //			* selected_class					(optional)
    //			* parent_selected_class				(optional)
    //			* no_selected						(optional)
    //			* list_type							(optional)
    //			* start_nav_from_parent				(optional)
    //			* start_nav_from_parent_depth		(optional)
    //			* max_depth							(optional)
    //			* start_x_levels_above_selected		(optional)
    //			* start_nav_on_level_of_selected	(optional)
    //			* start_nav_with_kids_of_selected	(optional)
    //			* only_display_children_of_selected	(optional)
    //			* display_all_children_of_selected	(optional)
    //			* display_entire_branch				(optional)
    //			* include_single_parent				(optional)
    //			* reverse							(optional)
    //			* site_id							(optional)
    //			* ignore_regex						(optional)
    //          * kids_class                        (optional)
    //          * navee_uid                         (optional)
    //
    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~>>

    function nav()
    {
        $output = "";

        //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~>>
        // Set Parameters
        //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~>>

        if ($this->EE->TMPL->fetch_param("site_id"))
        {
            $this->site_id = $this->EE->TMPL->fetch_param("site_id");
        }

        if ($this->EE->TMPL->fetch_param("nav_title"))
        {
            $this->EE->db->select("navigation_id");
            $this->EE->db->where("nav_title", $this->EE->TMPL->fetch_param("nav_title"));
            $this->EE->db->where("site_id", $this->site_id);
            $q = $this->EE->db->get("navee_navs", 1);
            if ($q->num_rows() > 0)
            {
                $row                 = $q->row();
                $this->navigation_id = $row->navigation_id;
            }
            $q->free_result();
        }

        if ($this->navigation_id)
        {
            if ($this->EE->TMPL->fetch_param("start_node"))
            {
                if (is_numeric($this->EE->TMPL->fetch_param("start_node")))
                {
                    $this->parent = $this->EE->TMPL->fetch_param("start_node");
                }
                else
                {
                    $this->parent = $this->_getBaseCrumb(0, $this->_stripLink($this->EE->TMPL->fetch_param("start_node")));
                }
            }

            if ($this->EE->TMPL->fetch_param("selected_class_on_parents"))
            {
                $this->selected_class_on_parents = TRUE;
            }

            if ($this->EE->TMPL->fetch_param("no_children"))
            {
                $this->recursive = FALSE;
            }

            if ($this->EE->TMPL->fetch_param("class"))
            {
                $this->nav_class = $this->EE->TMPL->fetch_param("class");
            }

            if ($this->EE->TMPL->fetch_param("id"))
            {
                $this->nav_id = $this->EE->TMPL->fetch_param("id");
            }

            if ($this->EE->TMPL->fetch_param("ignore_include_in_nav"))
            {
                $this->ignore_include_in_nav = TRUE;
            }

            if ($this->EE->TMPL->fetch_param("last_class"))
            {
                $this->last_class = $this->EE->TMPL->fetch_param("last_class");
            }

            if ($this->EE->TMPL->fetch_param("first_class"))
            {
                $this->first_class = $this->EE->TMPL->fetch_param("first_class");
            }

            if ($this->EE->TMPL->fetch_param("selected_class"))
            {
                $this->selected_class = $this->EE->TMPL->fetch_param("selected_class");
            }

            if ($this->EE->TMPL->fetch_param("parent_selected_class"))
            {
                $this->parent_selected_class = $this->EE->TMPL->fetch_param("parent_selected_class");
            }
            else
            {
                $this->parent_selected_class = $this->selected_class;
            }

            if ($this->EE->TMPL->fetch_param("list_type") == "ol")
            {
                $this->list_type = "ol";
            }

            if ($this->EE->TMPL->fetch_param("no_selected"))
            {
                $this->no_selected = TRUE;
            }

            if ($this->EE->TMPL->fetch_param("start_nav_from_parent"))
            {
                $this->start_nav_from_parent = TRUE;
                $this->parent                = 0;
            }

            if ($this->EE->TMPL->fetch_param("start_nav_from_parent_depth") > 0)
            {
                $this->start_nav_from_parent_depth = $this->EE->TMPL->fetch_param("start_nav_from_parent_depth");
            }

            if ($this->EE->TMPL->fetch_param("start_x_levels_above_selected") > 0)
            {
                $this->start_x_levels_above_selected = $this->EE->TMPL->fetch_param("start_x_levels_above_selected");
            }

            if ($this->EE->TMPL->fetch_param("start_nav_on_level_of_selected"))
            {
                $this->start_nav_on_level_of_selected = TRUE;
            }

            if ($this->EE->TMPL->fetch_param("start_nav_with_kids_of_selected"))
            {
                $this->start_nav_with_kids_of_selected = TRUE;
            }

            if ($this->EE->TMPL->fetch_param("only_display_children_of_selected"))
            {
                $this->only_display_children_of_selected = TRUE;
            }

            if ($this->EE->TMPL->fetch_param("display_all_children_of_selected"))
            {
                $this->display_all_children_of_selected = TRUE;
            }

            if ($this->EE->TMPL->fetch_param("display_entire_branch"))
            {
                $this->display_entire_branch = TRUE;
            }

            if ($this->EE->TMPL->fetch_param("max_depth") > 0)
            {
                $this->max_depth = $this->EE->TMPL->fetch_param("max_depth");
            }

            if ($this->EE->TMPL->fetch_param("include_single_parent"))
            {
                $this->include_single_parent = TRUE;
            }

            if ($this->EE->TMPL->fetch_param("reverse"))
            {
                $this->reverse = TRUE;
            }

            if ($this->EE->TMPL->fetch_param("ignore_regex"))
            {
                $this->ignore_regex = TRUE;
            }

            if ($this->EE->TMPL->fetch_param("kids_class"))
            {
                $this->kids_class = explode('|', $this->EE->TMPL->fetch_param("kids_class"));
            }

            if ($this->EE->TMPL->fetch_param("navee_uid"))
            {
                $this->navee_uid = $this->EE->TMPL->fetch_param("navee_uid");
            }

            if ($this->EE->TMPL->fetch_param("skip_closed_entries"))
            {
                $this->skip_closed_entries = TRUE;
            }



            if ($this->navigation_id)
            {
                if (!$this->cache_disabled)
                {
                    // Check for Cached Nav
                    $cache = FALSE;
                    $cache = $this->_getCachedNav($this->navigation_id, $this->parent, $this->recursive, $this->ignore_include_in_nav);
                }
                else
                {
                    $cache = FALSE;
                }

                if (!$cache)
                {
                    // There is currently no Cached Nav
                    // We have a Navigation ID, so let's build the navigation
                    $this->nav = $this->_getNav($this->navigation_id, $this->parent, $this->recursive, $this->ignore_include_in_nav);

                    if (!$this->cache_disabled)
                    {
                        // Let's cache this bad larry
                        $this->_setCachedNav($this->navigation_id, $this->parent, $this->recursive, $this->ignore_include_in_nav);
                    }
                }

                // Style the navigation for output
                if ($this->start_nav_on_level_of_selected)
                {
                    $calculatedDepth = $this->_selectedDepth($this->nav) - 1;
                    if ($calculatedDepth > 0)
                    {
                        $this->start_nav_from_parent_depth = $calculatedDepth;
                    }
                }
                elseif ($this->start_x_levels_above_selected > 0)
                {
                    $calculatedDepth = $this->_selectedDepth($this->nav) - $this->start_x_levels_above_selected - 1;
                    if ($calculatedDepth > 0)
                    {
                        $this->start_nav_from_parent_depth = $calculatedDepth;
                    }
                }

                if (($this->start_nav_from_parent) || ($this->start_x_levels_above_selected > 0))
                {

                    $this->nav = $this->_selectedParentSubset($this->nav);
                }
                elseif ($this->start_nav_on_level_of_selected)
                {
                    $this->nav = $this->_selectedSiblingSubset($this->nav);
                }
                elseif ($this->start_nav_with_kids_of_selected)
                {
                    $this->nav = $this->_selectedKidsSubset($this->nav);
                }

                if ($this->include_single_parent)
                {
                    $this->_addSingleParent();
                }

                $output = $this->_styleNav($this->nav);

                // Hook to override custom output
                if ($this->EE->extensions->active_hook('navee_override_custom_output'))
                {
                    $ext_output = $this->EE->extensions->call('navee_override_custom_output', $output, $this);

                    if (is_string($ext_output) && sizeof($ext_output) > 0)
                    {
                        $output = $ext_output;
                    }
                }

            }

        }
        else
        {
            // Some quick, in-template error messaging
            //$output = "NavEE Notice: You must enter a nav_title.";
            $output = "";
        }

        return $output;
    }

    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~>>
    //	C U S T O M
    //
    //		P A R A M E T E R S
    //		------------------------
    //			* nav_title
    //			* start_node						(optional)
    //			* no_children						(optional)
    //			* class 							(optional)
    //			* id 								(optional)
    //			* ignore_include_in_nav				(optional)
    //			* selected_class_on_parents 		(optional)
    //			* last_class						(optional)
    //			* first_class						(optional)
    //			* selected_class					(optional)
    //			* parent_selected_class				(optional)
    //			* no_selected						(optional)
    //			* wrap_type							(optional)
    //			* start_nav_from_parent				(optional)
    //			* start_nav_from_parent_depth		(optional)
    //			* start_x_levels_above_selected		(optional)
    //			* start_nav_on_level_of_selected	(optional)
    //			* start_nav_with_kids_of_selected	(optional)
    //			* only_display_children_of_selected	(optional)
    //			* display_all_children_of_selected	(optional)
    //			* include_single_parent				(optional)
    //			* reverse							(optional)
    //			* site_id							(optional)
    //			* ignore_regex						(optional)
    //			* get_depth_of_selected				(optional)
    //          * kids_class                        (optional)
    //          * navee_uid                         (optional)
    //          * disable_title                     (optional)
    //
    //		V A R I A B L E S
    //		------------------------------
    //			* navee_id
    //			* navigation_id
    //			* text
    //			* link
    //			* class
    //			* id
    //			* rel
    //			* name
    //			* target
    //			* title
    //			* has_kids
    //			* kid_count
    //			* is_selected
    //			* custom
    //			* custom_kids
    //			* navee_entry_id
    //			* link_type
    //			* navee_count
    //			* accesskey
    //			* include_in_nav
    //			* is_first_item_on_level
    //			* is_last_item_on_level
    //			* is_previous_item_selected
    //			* depth_of_selected
    //			* level_count
    //
    //			* count | deprecated 2.2.6 - replaced with {navee_count}
    //
    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~>>

    function custom()
    {

        $output = "";

        //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~>>
        // Set Parameters
        //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~>>

        if ($this->EE->TMPL->fetch_param("site_id"))
        {
            $this->site_id = $this->EE->TMPL->fetch_param("site_id");
        }

        if ($this->EE->TMPL->fetch_param("nav_title"))
        {
            $this->EE->db->select("navigation_id");
            $this->EE->db->where("nav_title", $this->EE->TMPL->fetch_param("nav_title"));
            $this->EE->db->where("site_id", $this->site_id);
            $q = $this->EE->db->get("navee_navs", 1);
            if ($q->num_rows() > 0)
            {
                $row                 = $q->row();
                $this->navigation_id = $row->navigation_id;
            }
            $q->free_result();
        }

        if ($this->navigation_id)
        {
            if ($this->EE->TMPL->fetch_param("start_node"))
            {
                if (is_numeric($this->EE->TMPL->fetch_param("start_node")))
                {
                    $this->parent = $this->EE->TMPL->fetch_param("start_node");
                }
                else
                {
                    $this->parent = $this->_getBaseCrumb(0, $this->_stripLink($this->EE->TMPL->fetch_param("start_node")));
                }
            }

            if ($this->EE->TMPL->fetch_param("selected_class_on_parents"))
            {
                $this->selected_class_on_parents = TRUE;
            }

            if ($this->EE->TMPL->fetch_param("no_children"))
            {
                $this->recursive = FALSE;
            }

            if ($this->EE->TMPL->fetch_param("class"))
            {
                $this->nav_class = $this->EE->TMPL->fetch_param("class");
            }

            if ($this->EE->TMPL->fetch_param("id"))
            {
                $this->nav_id = $this->EE->TMPL->fetch_param("id");
            }

            if ($this->EE->TMPL->fetch_param("ignore_include_in_nav"))
            {
                $this->ignore_include_in_nav = TRUE;
            }

            if ($this->EE->TMPL->fetch_param("last_class"))
            {
                $this->last_class = $this->EE->TMPL->fetch_param("last_class");
            }

            if ($this->EE->TMPL->fetch_param("first_class"))
            {
                $this->first_class = $this->EE->TMPL->fetch_param("first_class");
            }

            if ($this->EE->TMPL->fetch_param("selected_class"))
            {
                $this->selected_class = $this->EE->TMPL->fetch_param("selected_class");
            }

            if ($this->EE->TMPL->fetch_param("parent_selected_class"))
            {
                $this->parent_selected_class = $this->EE->TMPL->fetch_param("parent_selected_class");
            }
            else
            {
                $this->parent_selected_class = $this->selected_class;
            }

            if ($this->EE->TMPL->fetch_param("wrap_type"))
            {
                if (strtolower($this->EE->TMPL->fetch_param("wrap_type")) == "none")
                {
                    $this->list_type = "";
                }
                else
                {
                    $this->list_type = $this->EE->TMPL->fetch_param("wrap_type");
                }
            }

            if ($this->EE->TMPL->fetch_param("no_selected"))
            {
                $this->no_selected = TRUE;
            }

            if ($this->EE->TMPL->fetch_param("start_nav_from_parent"))
            {
                $this->start_nav_from_parent = TRUE;
                $this->parent                = 0;
            }

            if ($this->EE->TMPL->fetch_param("start_nav_from_parent_depth") > 0)
            {
                $this->start_nav_from_parent_depth = $this->EE->TMPL->fetch_param("start_nav_from_parent_depth");
            }

            if ($this->EE->TMPL->fetch_param("start_x_levels_above_selected") > 0)
            {
                $this->start_x_levels_above_selected = $this->EE->TMPL->fetch_param("start_x_levels_above_selected");
            }

            if ($this->EE->TMPL->fetch_param("start_nav_on_level_of_selected"))
            {
                $this->start_nav_on_level_of_selected = TRUE;
            }

            if ($this->EE->TMPL->fetch_param("start_nav_with_kids_of_selected"))
            {
                $this->start_nav_with_kids_of_selected = TRUE;
            }

            if ($this->EE->TMPL->fetch_param("only_display_children_of_selected"))
            {
                $this->only_display_children_of_selected = TRUE;
            }

            if ($this->EE->TMPL->fetch_param("display_all_children_of_selected"))
            {
                $this->display_all_children_of_selected = TRUE;
            }

            if ($this->EE->TMPL->fetch_param("display_entire_branch"))
            {
                $this->display_entire_branch = TRUE;
            }

            if ($this->EE->TMPL->fetch_param("max_depth") > 0)
            {
                $this->max_depth = $this->EE->TMPL->fetch_param("max_depth");
            }

            if ($this->EE->TMPL->fetch_param("include_single_parent"))
            {
                $this->include_single_parent = TRUE;
            }

            if ($this->EE->TMPL->fetch_param("reverse"))
            {
                $this->reverse = TRUE;
            }

            if ($this->EE->TMPL->fetch_param("ignore_regex"))
            {
                $this->ignore_regex = TRUE;
            }

            if ($this->EE->TMPL->fetch_param("kids_class"))
            {
                $this->kids_class = explode('|', $this->EE->TMPL->fetch_param("kids_class"));
            }

            if ($this->EE->TMPL->fetch_param("get_info_about_selected"))
            {
                $this->get_info_about_selected = TRUE;
            }

            if ($this->EE->TMPL->fetch_param("navee_uid"))
            {
                $this->navee_uid = $this->EE->TMPL->fetch_param("navee_uid");
            }

            if ($this->EE->TMPL->fetch_param("disable_title"))
            {
                $this->disable_title = TRUE;
            }

            if ($this->EE->TMPL->fetch_param("skip_closed_entries"))
            {
                $this->skip_closed_entries = TRUE;
            }

            if ($this->navigation_id)
            {
                if (!$this->cache_disabled)
                {
                    // Check for Cached Nav
                    $cache = FALSE;
                    $cache = $this->_getCachedNav($this->navigation_id, $this->parent, $this->recursive, $this->ignore_include_in_nav);
                }
                else
                {
                    $cache = FALSE;
                }

                if (!$cache)
                {
                    // We have a Navigation ID, so let's build the navigation
                    $this->nav = $this->_getNav($this->navigation_id, $this->parent, $this->recursive, $this->ignore_include_in_nav);

                    if (!$this->cache_disabled)
                    {
                        // Let's cache this bad larry
                        $this->_setCachedNav($this->navigation_id, $this->parent, $this->recursive, $this->ignore_include_in_nav);
                    }
                }

                // Style the navigation for output
                if ($this->start_nav_on_level_of_selected)
                {
                    $calculatedDepth = $this->_selectedDepth($this->nav) - 1;
                    if ($calculatedDepth > 0)
                    {
                        $this->start_nav_from_parent_depth = $calculatedDepth;
                    }
                }
                elseif ($this->start_x_levels_above_selected > 0)
                {
                    $calculatedDepth = $this->_selectedDepth($this->nav) - $this->start_x_levels_above_selected - 1;
                    if ($calculatedDepth > 0)
                    {
                        $this->start_nav_from_parent_depth = $calculatedDepth;
                    }

                }

                if (($this->start_nav_from_parent) || ($this->start_x_levels_above_selected > 0))
                {
                    $this->nav = $this->_selectedParentSubset($this->nav);
                }
                elseif ($this->start_nav_on_level_of_selected)
                {
                    $this->nav = $this->_selectedSiblingSubset($this->nav);
                }
                elseif ($this->start_nav_with_kids_of_selected)
                {
                    $this->nav = $this->_selectedKidsSubset($this->nav);
                }

                if ($this->include_single_parent)
                {
                    $this->_addSingleParent();
                }

                $output = $this->_styleCustom($this->nav);

                // Hook to override custom output
                if ($this->EE->extensions->active_hook('navee_override_custom_output'))
                {
                    $ext_output = $this->EE->extensions->call('navee_override_custom_output', $output, $this);

                    if (is_string($ext_output) && sizeof($ext_output) > 0)
                    {
                        $output = $ext_output;
                    }
                }
            }
        }
        else
        {
            // Some quick, in-template error messaging
            //$output = "NavEE Notice: You must enter a nav_title.";
            $output = "";
        }

        return $output;
    }

    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~>>
    //	I S   N A V   E M P T Y
    //
    //		P A R A M E T E R S
    //		------------------------
    //			* nav_title
    //			* start_node						(optional)
    //			* no_children						(optional)
    //			* class 							(optional)
    //			* id 								(optional)
    //			* ignore_include_in_nav				(optional)
    //			* selected_class_on_parents 		(optional)
    //			* last_class						(optional)
    //			* first_class						(optional)
    //			* selected_class					(optional)
    //			* parent_selected_class				(optional)
    //			* no_selected						(optional)
    //			* wrap_type							(optional)
    //			* start_nav_from_parent				(optional)
    //			* start_nav_from_parent_depth		(optional)
    //			* start_x_levels_above_selected		(optional)
    //			* start_nav_on_level_of_selected	(optional)
    //			* start_nav_with_kids_of_selected	(optional)
    //			* only_display_children_of_selected	(optional)
    //			* include_single_parent				(optional)
    //			* site_id							(optional)
    //
    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~>>

    function is_nav_empty()
    {
        $output = "";

        //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~>>
        // Set Parameters
        //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~>>

        if ($this->EE->TMPL->fetch_param("site_id"))
        {
            $this->site_id = $this->EE->TMPL->fetch_param("site_id");
        }

        if ($this->EE->TMPL->fetch_param("nav_title"))
        {
            $this->EE->db->select("navigation_id");
            $this->EE->db->where("nav_title", $this->EE->TMPL->fetch_param("nav_title"));
            $this->EE->db->where("site_id", $this->site_id);
            $q = $this->EE->db->get("navee_navs", 1);
            if ($q->num_rows() > 0)
            {
                $row                 = $q->row();
                $this->navigation_id = $row->navigation_id;
            }
            $q->free_result();
        }

        if ($this->navigation_id)
        {
            if ($this->EE->TMPL->fetch_param("start_node"))
            {
                if (is_numeric($this->EE->TMPL->fetch_param("start_node")))
                {
                    $this->parent = $this->EE->TMPL->fetch_param("start_node");
                }
                else
                {
                    $this->EE->db->select("navee_id");
                    $this->EE->db->where("link", $this->EE->TMPL->fetch_param("start_node"));
                    $this->EE->db->where("navigation_id", $this->navigation_id);
                    $this->EE->db->where("site_id", $this->site_id);
                    $q = $this->EE->db->get("navee", 1);

                    if ($q->num_rows() > 0)
                    {
                        $row          = $q->row();
                        $this->parent = $row->navee_id;
                    }
                    $q->free_result();
                }
            }

            if ($this->EE->TMPL->fetch_param("selected_class_on_parents"))
            {
                $this->selected_class_on_parents = TRUE;
            }

            if ($this->EE->TMPL->fetch_param("no_children"))
            {
                $this->recursive = FALSE;
            }

            if ($this->EE->TMPL->fetch_param("class"))
            {
                $this->nav_class = $this->EE->TMPL->fetch_param("class");
            }

            if ($this->EE->TMPL->fetch_param("id"))
            {
                $this->nav_id = $this->EE->TMPL->fetch_param("id");
            }

            if ($this->EE->TMPL->fetch_param("ignore_include_in_nav"))
            {
                $this->ignore_include_in_nav = TRUE;
            }

            if ($this->EE->TMPL->fetch_param("last_class"))
            {
                $this->last_class = $this->EE->TMPL->fetch_param("last_class");
            }

            if ($this->EE->TMPL->fetch_param("first_class"))
            {
                $this->first_class = $this->EE->TMPL->fetch_param("first_class");
            }

            if ($this->EE->TMPL->fetch_param("selected_class"))
            {
                $this->selected_class = $this->EE->TMPL->fetch_param("selected_class");
            }

            if ($this->EE->TMPL->fetch_param("parent_selected_class"))
            {
                $this->parent_selected_class = $this->EE->TMPL->fetch_param("parent_selected_class");
            }
            else
            {
                $this->parent_selected_class = $this->selected_class;
            }

            if ($this->EE->TMPL->fetch_param("wrap_type"))
            {
                if (strtolower($this->EE->TMPL->fetch_param("wrap_type")) == "none")
                {
                    $this->list_type = "";
                }
                else
                {
                    $this->list_type = $this->EE->TMPL->fetch_param("wrap_type");
                }
            }

            if ($this->EE->TMPL->fetch_param("no_selected"))
            {
                $this->no_selected = TRUE;
            }

            if ($this->EE->TMPL->fetch_param("start_nav_from_parent"))
            {
                $this->start_nav_from_parent = TRUE;
                $this->parent                = 0;
            }

            if ($this->EE->TMPL->fetch_param("start_nav_from_parent_depth") > 0)
            {
                $this->start_nav_from_parent_depth = $this->EE->TMPL->fetch_param("start_nav_from_parent_depth");
            }

            if ($this->EE->TMPL->fetch_param("start_x_levels_above_selected") > 0)
            {
                $this->start_x_levels_above_selected = $this->EE->TMPL->fetch_param("start_x_levels_above_selected");
            }

            if ($this->EE->TMPL->fetch_param("start_nav_on_level_of_selected"))
            {
                $this->start_nav_on_level_of_selected = TRUE;
            }

            if ($this->EE->TMPL->fetch_param("start_nav_with_kids_of_selected"))
            {
                $this->start_nav_with_kids_of_selected = TRUE;
            }

            if ($this->EE->TMPL->fetch_param("only_display_children_of_selected"))
            {
                $this->only_display_children_of_selected = TRUE;
            }

            if ($this->EE->TMPL->fetch_param("max_depth") > 0)
            {
                $this->max_depth = $this->EE->TMPL->fetch_param("max_depth");
            }

            if ($this->EE->TMPL->fetch_param("include_single_parent"))
            {
                $this->include_single_parent = TRUE;
            }

            if ($this->navigation_id)
            {
                if (!$this->cache_disabled)
                {
                    // Check for Cached Nav
                    $cache = FALSE;
                    $cache = $this->_getCachedNav($this->navigation_id, $this->parent, $this->recursive, $this->ignore_include_in_nav);
                }
                else
                {
                    $cache = FALSE;
                }

                if (!$cache)
                {
                    // We have a Navigation ID, so let's build the navigation
                    $this->nav = $this->_getNav($this->navigation_id, $this->parent, $this->recursive, $this->ignore_include_in_nav);

                    if (!$this->cache_disabled)
                    {
                        // Let's cache this bad larry
                        $this->_setCachedNav($this->navigation_id, $this->parent, $this->recursive, $this->ignore_include_in_nav);
                    }
                }

                // Style the navigation for output
                if ($this->start_nav_on_level_of_selected)
                {
                    $calculatedDepth = $this->_selectedDepth($this->nav) - 1;
                    if ($calculatedDepth > 0)
                    {
                        $this->start_nav_from_parent_depth = $calculatedDepth;
                    }
                }
                elseif ($this->start_x_levels_above_selected > 0)
                {
                    $calculatedDepth = $this->_selectedDepth($this->nav) - $this->start_x_levels_above_selected - 1;
                    if ($calculatedDepth > 0)
                    {
                        $this->start_nav_from_parent_depth = $calculatedDepth;
                    }

                }

                if (($this->start_nav_from_parent) || ($this->start_x_levels_above_selected > 0))
                {
                    $this->nav = $this->_selectedParentSubset($this->nav);
                }
                elseif ($this->start_nav_on_level_of_selected)
                {
                    $this->nav = $this->_selectedSiblingSubset($this->nav);
                }
                elseif ($this->start_nav_with_kids_of_selected)
                {
                    $this->nav = $this->_selectedKidsSubset($this->nav);
                }

                if ($this->include_single_parent)
                {
                    $this->_addSingleParent();
                }

                if (sizeof($this->nav) == 0)
                {
                    $output = "true";
                }
                else
                {
                    $output = "false";
                }
            }
        }
        else
        {
            // Some quick, in-template error messaging
            //$output = "NavEE Notice: You must enter a nav_title.";
            $output = "";
        }

        return $output;
    }


    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~>>
    //	C R U M B S
    //
    //		P A R A M E T E R S
    //		------------------------
    //			* nav_title
    //			* list_type					(optional)
    //			* class						(optional)
    //			* id						(optional)
    //			* ignore_include_in_nav		(optional)
    //			* no_last_anchor			(optional)
    //			* last_item					(optional)
    //			* last_item_link			(optional)
    //			* reverse					(optional)
    //			* entry_id 					(optional)
    //
    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~>>

    function crumbs()
    {
        $output   = "";
        $entry_id = 0;

        //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~>>
        // Set Parameters
        //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~>>

        if ($this->EE->TMPL->fetch_param("site_id"))
        {
            $this->site_id = $this->EE->TMPL->fetch_param("site_id");
        }

        if ($this->EE->TMPL->fetch_param("nav_title"))
        {
            $this->EE->db->select("navigation_id");
            $this->EE->db->where("nav_title", $this->EE->TMPL->fetch_param("nav_title"));
            $this->EE->db->where("site_id", $this->site_id);
            $q = $this->EE->db->get("navee_navs", 1);
            if ($q->num_rows() > 0)
            {
                $row                 = $q->row();
                $this->navigation_id = $row->navigation_id;
            }
            $q->free_result();
        }

        if ($this->EE->TMPL->fetch_param("list_type") == "ol")
        {
            $this->list_type = "ol";
        }

        if ($this->EE->TMPL->fetch_param("wrap_type"))
        {
            if (strtolower($this->EE->TMPL->fetch_param("wrap_type")) == "none")
            {
                $this->list_type = "";
            }
            else
            {
                $this->list_type = $this->EE->TMPL->fetch_param("wrap_type");
            }
        }

        if ($this->EE->TMPL->fetch_param("class"))
        {
            $this->nav_class = $this->EE->TMPL->fetch_param("class");
        }

        if ($this->EE->TMPL->fetch_param("id"))
        {
            $this->nav_id = $this->EE->TMPL->fetch_param("id");
        }

        if ($this->EE->TMPL->fetch_param("ignore_include_in_nav"))
        {
            $this->ignore_include_in_nav = TRUE;
        }

        if ($this->EE->TMPL->fetch_param("no_last_anchor"))
        {
            $this->no_last_anchor = TRUE;
        }

        if ($this->EE->TMPL->fetch_param("last_item"))
        {
            $this->last_item = $this->EE->TMPL->fetch_param("last_item");
        }

        if ($this->EE->TMPL->fetch_param("last_item_link"))
        {
            $this->last_item_link = $this->EE->TMPL->fetch_param("last_item_link");
        }

        if ($this->EE->TMPL->fetch_param("reverse"))
        {
            $this->reverse = TRUE;
        }

        if ($this->EE->TMPL->fetch_param("entry_id"))
        {
            $entry_id = $this->EE->TMPL->fetch_param("entry_id");
        }

        //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~>>
        // Everything looks good, let's do this thing
        //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~>>

        $this->navee_id = $this->_getBaseCrumb($entry_id);

        if ($this->navee_id)
        {
            $crumbArray = $this->_getCrumbs($this->navee_id);

            if (strlen($this->last_item) > 0)
            {
                $pushMe             = array();
                $pushMe["text"]     = $this->last_item;
                $pushMe["link"]     = $this->last_item_link;
                $pushMe["navee_id"] = "";
                $pushMe["parent"]   = "";
                $pushMe["class"]    = "";
                $pushMe["id"]       = "";
                $pushMe["rel"]      = "";
                $pushMe["name"]     = "";
                $pushMe["target"]   = "";

                array_push($crumbArray, $pushMe);
            }
            $output = $this->_styleCrumbs($crumbArray);
        }
        else
        {
            $this->EE->db->select("navee_id, regex");
            $this->EE->db->where("navigation_id", $this->navigation_id);
            $this->EE->db->where("site_id", $this->site_id);
            $q = $this->EE->db->get("navee");

            if ($q->num_rows() > 0)
            {
                foreach ($q->result() as $r)
                {
                    if (strlen($r->regex) > 0)
                    {
                        if (preg_match($r->regex, $this->EE->uri->uri_string()))
                        {
                            $crumbArray = $this->_getCrumbs($r->navee_id);

                            if (strlen($this->last_item) > 0)
                            {
                                $pushMe             = array();
                                $pushMe["text"]     = $this->last_item;
                                $pushMe["link"]     = $this->last_item_link;
                                $pushMe["navee_id"] = "";
                                $pushMe["parent"]   = "";
                                $pushMe["class"]    = "";
                                $pushMe["id"]       = "";
                                $pushMe["rel"]      = "";
                                $pushMe["name"]     = "";
                                $pushMe["target"]   = "";

                                array_push($crumbArray, $pushMe);
                            }
                            $output = $this->_styleCrumbs($crumbArray);
                            break;
                        }
                    }
                }
            }
            $q->free_result();
        }

        return $output;
    }

    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~>>
    //	C U S T O M _ C R U M B S
    //
    //		P A R A M E T E R S
    //		------------------------
    //			* nav_title
    //			* class						(optional)
    //			* id						(optional)
    //			* ignore_include_in_nav		(optional)
    //			* wrap_type					(optional)
    //			* spacer					(optional)
    //			* no_last_spacer			(optional)
    //			* last_item					(optional)
    //			* last_item_link			(optional)
    //			* reverse					(optional)
    //			* entry_id 					(optional)
    //
    //
    //		V A R I A B L E S
    //		------------------------
    //
    //			* text
    //			* link
    //			* navee_id
    //			* navigation_id
    //			* class
    //			* id
    //			* rel
    //			* name
    //			* target
    //			* spacer
    //			* is_last_item
    //			* navee_count
    //			* custom
    //			* custom_kids
    //
    //			* count | deprecated 2.2.6 - replaced with {navee_count}
    //
    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~>>

    function custom_crumbs()
    {
        $output   = "";
        $entry_id = 0;

        //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~>>
        // Set Parameters
        //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~>>

        if ($this->EE->TMPL->fetch_param("site_id"))
        {
            $this->site_id = $this->EE->TMPL->fetch_param("site_id");
        }

        if ($this->EE->TMPL->fetch_param("nav_title"))
        {
            $this->EE->db->select("navigation_id");
            $this->EE->db->where("nav_title", $this->EE->TMPL->fetch_param("nav_title"));
            $this->EE->db->where("site_id", $this->site_id);
            $q = $this->EE->db->get("navee_navs", 1);
            if ($q->num_rows() > 0)
            {
                $row                 = $q->row();
                $this->navigation_id = $row->navigation_id;
            }
            $q->free_result();
        }

        if ($this->EE->TMPL->fetch_param("wrap_type"))
        {
            if (strtolower($this->EE->TMPL->fetch_param("wrap_type")) == "none")
            {
                $this->list_type = "";
            }
            else
            {
                $this->list_type = $this->EE->TMPL->fetch_param("wrap_type");
            }
        }

        if ($this->EE->TMPL->fetch_param("class"))
        {
            $this->nav_class = $this->EE->TMPL->fetch_param("class");
        }

        if ($this->EE->TMPL->fetch_param("id"))
        {
            $this->nav_id = $this->EE->TMPL->fetch_param("id");
        }

        if ($this->EE->TMPL->fetch_param("ignore_include_in_nav"))
        {
            $this->ignore_include_in_nav = TRUE;
        }

        if ($this->EE->TMPL->fetch_param("spacer"))
        {
            $this->spacer = $this->EE->TMPL->fetch_param("spacer");
        }

        if ($this->EE->TMPL->fetch_param("no_last_spacer"))
        {
            $this->no_last_spacer = TRUE;
        }

        if ($this->EE->TMPL->fetch_param("last_item"))
        {
            $this->last_item = $this->EE->TMPL->fetch_param("last_item");
        }

        if ($this->EE->TMPL->fetch_param("last_item_link"))
        {
            $this->last_item_link = $this->EE->TMPL->fetch_param("last_item_link");
        }

        if ($this->EE->TMPL->fetch_param("reverse"))
        {
            $this->reverse = TRUE;
        }

        if ($this->EE->TMPL->fetch_param("entry_id"))
        {
            $entry_id = $this->EE->TMPL->fetch_param("entry_id");
        }

        //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~>>
        // Everything looks good, let's do this thing
        //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~>>

        $this->navee_id = $this->_getBaseCrumb($entry_id);

        if ($this->navee_id)
        {
            $crumbArray = $this->_getCrumbs($this->navee_id);

            if (strlen($this->last_item) > 0)
            {
                $pushMe                = array();
                $pushMe["text"]        = $this->last_item;
                $pushMe["link"]        = $this->last_item_link;
                $pushMe["navee_id"]    = "";
                $pushMe["parent"]      = "";
                $pushMe["class"]       = "";
                $pushMe["id"]          = "";
                $pushMe["rel"]         = "";
                $pushMe["name"]        = "";
                $pushMe["target"]      = "";
                $pushMe["custom"]      = "";
                $pushMe["custom_kids"] = "";

                array_push($crumbArray, $pushMe);
            }
            $output = $this->_styleCustomCrumbs($crumbArray);
        }
        else
        {
            $this->EE->db->select("navee_id, regex");
            if ($this->navigation_id)
            {
                $this->EE->db->where("navigation_id", $this->navigation_id);
            }
            $this->EE->db->where("site_id", $this->site_id);
            $q = $this->EE->db->get("navee");

            if ($q->num_rows() > 0)
            {
                foreach ($q->result() as $r)
                {
                    if (strlen($r->regex) > 0)
                    {
                        if (preg_match($r->regex, $this->EE->uri->uri_string()))
                        {
                            $crumbArray = $this->_getCrumbs($r->navee_id);

                            if (strlen($this->last_item) > 0)
                            {
                                $pushMe                = array();
                                $pushMe["text"]        = $this->last_item;
                                $pushMe["link"]        = $this->last_item_link;
                                $pushMe["navee_id"]    = "";
                                $pushMe["parent"]      = "";
                                $pushMe["class"]       = "";
                                $pushMe["id"]          = "";
                                $pushMe["rel"]         = "";
                                $pushMe["name"]        = "";
                                $pushMe["target"]      = "";
                                $pushMe["custom"]      = "";
                                $pushMe["custom_kids"] = "";

                                array_push($crumbArray, $pushMe);
                            }
                            $output = $this->_styleCustomCrumbs($crumbArray);
                            break;
                        }
                    }
                }
            }
            $q->free_result();
        }

        return $output;
    }

    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~>>
    //	S T Y L E   N A V 
    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~>>

    function _styleNav($nav, $depth = 0, $custom_kids = "", $expand_branch = FALSE)
    {
        $returnMe = '';

        if (sizeof($nav) > 0)
        {
            $returnMe .= '<' . $this->list_type;
            // If this is the first <ul> apply assigned class & id
            if ($depth == 0)
            {
                if (strlen($this->nav_id) > 0)
                {
                    $returnMe .= ' id="' . $this->nav_id . '"';
                }

                if (strlen($this->nav_class) > 0)
                {
                    $returnMe .= ' class="' . $this->nav_class . '"';
                }
            }
            else if ($this->kids_class && $depth > 0 && isset($this->kids_class[$depth]))
            {
                $kids_class_depth = count($this->kids_class) > 1 ? $depth - 1 : 0;
                $returnMe .= ' class="' . $this->kids_class[$kids_class_depth] . '"';
            }

            if (strlen($custom_kids) > 0)
            {
                $returnMe .= ' ' . $custom_kids;
            }

            $returnMe .= '>';
            $count    = 0;
            $navCount = sizeof($nav);

            // If the reverse parameter has been passed, let's reverse it.
            if ($this->reverse)
            {
                $nav = array_reverse($nav);
            }

            foreach ($nav as $k => $v)
            {

                $class = '';
                $count++;

                // add site url prefix
                if ($this->site_url_prefix)
                {
                    $v["link"] = $this->_prefix_site_url($v["link"]);
                }

                // Open the <li> for our nav item
                $returnMe .= '<li';

                // Add appropriate 'selected' classes
                if ($v["passive"] == 0)
                {
                    if (!$this->no_selected)
                    {
                        if (strlen($v["regex"]) > 0 && $this->ignore_regex == FALSE)
                        {
                            if (preg_match($v["regex"], $this->EE->uri->uri_string()))
                            {
                                $class .= $this->selected_class;
                            }
                        }
                        else
                        {
                            // If this page matches the link
                            if ((strlen($v["link"]) > 0) && ($this->_stripLink($v["link"]) == $this->EE->uri->uri_string()))
                            {
                                $class .= $this->selected_class;
                            }

                        }

                        if ($this->selected_class_on_parents)
                        {
                            // Check to see if descendent is selected
                            if (!preg_match("/" . $this->selected_class . "/i", $class))
                            {
                                if ($this->_isDescendentSelected($v["kids"]))
                                {
                                    $class .= $this->parent_selected_class;
                                }
                            }
                        }
                    }
                }

                // Add first/last classes
                if (strlen($this->last_class) > 0)
                {
                    if ($count == $navCount)
                    {
                        if (strlen($class) > 0)
                        {
                            $class .= ' ';
                        }
                        $class .= $this->last_class;
                    }
                }

                if (strlen($this->first_class) > 0)
                {
                    if ($count == 1)
                    {
                        if (strlen($class) > 0)
                        {
                            $class .= ' ';
                        }
                        $class .= $this->first_class;
                    }
                }

                // Apply class
                if (strlen($v["class"]) > 0)
                {
                    if (strlen($class) > 0)
                    {
                        $class .= ' ';
                    }
                    $class .= $v["class"];
                }

                if (strlen($class) > 0)
                {
                    $returnMe .= ' class="' . $class . '"';
                }

                // Apply ID
                if (strlen($v["id"]) > 0)
                {
                    $returnMe .= ' id="' . $v["id"] . '"';
                }

                // Apply Custom
                if (strlen($v["custom"]) > 0)
                {
                    $returnMe .= ' ' . $v["custom"];
                }

                $returnMe .= '>';

                // Begin <a>
                if (strlen($v["link"]) > 0)
                {
                    $returnMe .= '<a href="' . $v["link"] . '"';

                    // Rel, Name and Target
                    if (strlen($v["rel"]))
                    {
                        $returnMe .= ' rel="' . $v["rel"] . '"';
                    }

                    if (strlen($v["name"]))
                    {
                        $returnMe .= ' name="' . $v["name"] . '"';
                    }

                    if (strlen($v["title"]))
                    {
                        $returnMe .= ' title="' . $v["title"] . '"';
                    }

                    if (strlen($v["target"]))
                    {
                        $returnMe .= ' target="' . $v["target"] . '"';
                    }

                    if (strlen($v["access_key"]))
                    {
                        $returnMe .= ' accesskey="' . $v["access_key"] . '"';
                    }

                    $returnMe .= '>' . $v["text"] . '</a>';
                }
                else
                {
                    $returnMe .= $v["text"];
                }

                // If our nav item has kids, let's recurse
                if ((sizeof($v["kids"]) > 0) && ($this->recursive) && (($this->max_depth == 0) || (($depth + 1) < $this->max_depth)))
                {
                    if ($this->only_display_children_of_selected)
                    {
                        if ($this->_isSelected($v))
                        {
                            $returnMe .= $this->_styleNav($v["kids"], $depth + 1, $v["custom_kids"]);
                        }
                    }
                    elseif ($this->display_all_children_of_selected)
                    {
                        if ($this->_isSelected($v) || $this->_isParentSelected($v))
                        {
                            $returnMe .= $this->_styleNav($v["kids"], $depth + 1, $v["custom_kids"]);
                        }
                    }
                    elseif ($this->display_entire_branch)
                    {
                        if ($this->_isSelected($v) || $expand_branch)
                        {
                            $returnMe .= $this->_styleNav($v["kids"], $depth + 1, $v["custom_kids"], TRUE);
                        }
                    }
                    else
                    {
                        $returnMe .= $this->_styleNav($v["kids"], $depth + 1, $v["custom_kids"]);
                    }
                }

                // Close out the </li>
                $returnMe .= '</li>';
            }
            $returnMe .= '</' . $this->list_type . '>';
        }
        return $returnMe;
    }

    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~>>
    //	S T Y L E   C U S T O M
    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~>>

    function _styleCustom($nav, $depth = 0, $expand_branch = FALSE)
    {
        $returnMe = "";
        if (sizeof($nav) > 0)
        {
            $count                 = 0;
            $navCount              = sizeof($nav);
            $is_prev_item_selected = FALSE;

            // If the reverse parameter has been passed, let's reverse it.
            if ($this->reverse)
            {
                $nav = array_reverse($nav);
            }

            // If we are including the depth of selected in the results
            if ($this->get_info_about_selected && $depth == 0)
            {
                $this->depth_of_selected = $this->_selectedDepth($nav);
            }

            foreach ($nav as $k => $v)
            {

                $class            = "";
                $navee_entry_id   = "";
                $navee_channel_id = "";
                $count++;
                $is_selected            = FALSE;
                $this->has_kids         = FALSE;
                $this->kid_count        = 0;
                $is_first_item_on_level = 0;
                $is_last_item_on_level  = 0;

                if ((sizeof($v["kids"]) > 0) && ($this->recursive) && (($this->max_depth == 0) || (($depth + 1) < $this->max_depth)))
                {
                    if ($this->only_display_children_of_selected)
                    {
                        if ($this->_isSelected($v))
                        {
                            $kids            = $this->_styleCustom($v["kids"], $depth + 1);
                            $this->has_kids  = TRUE;
                            $this->kid_count = count($v["kids"]);
                        }
                        else
                        {
                            $kids = "";
                        }
                    }
                    elseif ($this->display_all_children_of_selected)
                    {
                        if ($this->_isSelected($v) || $this->_isParentSelected($v))
                        {
                            $kids            = $this->_styleCustom($v["kids"], $depth + 1);
                            $this->has_kids  = TRUE;
                            $this->kid_count = count($v["kids"]);
                        }
                        else
                        {
                            $kids = FALSE;
                        }
                    }
                    elseif ($this->display_entire_branch)
                    {
                        if ($this->_isSelected($v) || $expand_branch)
                        {
                            $kids            = $this->_styleCustom($v["kids"], $depth + 1, TRUE);
                            $this->has_kids  = TRUE;
                            $this->kid_count = count($v["kids"]);
                        }
                        else
                        {
                            $kids = FALSE;
                        }
                    }
                    else
                    {
                        $kids            = $this->_styleCustom($v["kids"], $depth + 1);
                        $this->has_kids  = TRUE;
                        $this->kid_count = count($v["kids"]);
                    }

                }
                else
                {
                    $kids = "";
                }

                // Add appropriate 'selected' classes
                if ($v["passive"] == 0)
                {
                    if (!$this->no_selected)
                    {
                        if (strlen($v["regex"]) > 0 && $this->ignore_regex == FALSE)
                        {
                            if (preg_match($v["regex"], $this->EE->uri->uri_string()))
                            {
                                $class .= $this->selected_class;
                                $is_selected = TRUE;
                            }
                        }
                        else
                        {
                            // If this page matches the link
                            if ((strlen($v["link"]) > 0) && ($this->_stripLink($v["link"]) == $this->EE->uri->uri_string()))
                            {
                                $class .= $this->selected_class;
                                $is_selected = TRUE;
                            }
                        }

                        if ($this->selected_class_on_parents)
                        {
                            // Check to see if descendent is selected
                            if (!preg_match("/" . $this->selected_class . "/i", $class))
                            {
                                if ($this->_isDescendentSelected($v["kids"]))
                                {
                                    $class .= $this->parent_selected_class;
                                }
                            }
                        }
                    }
                }

                // Add first/last classes
                if (strlen($this->last_class) > 0)
                {
                    if ($count == $navCount)
                    {
                        if (strlen($class) > 0)
                        {
                            $class .= " ";
                        }
                        $class .= $this->last_class;
                    }
                }

                if (strlen($this->first_class) > 0)
                {
                    if ($count == 1)
                    {
                        if (strlen($class) > 0)
                        {
                            $class .= " ";
                        }
                        $class .= $this->first_class;
                    }
                }

                // Apply item class
                if (strlen($v["class"]) > 0)
                {
                    if (strlen($class) > 0)
                    {
                        $class .= " ";
                    }
                    $class .= $v["class"];
                }

                // Set navee_entry_id variable
                if (isset($v["entry_id"]) && $v["entry_id"] > 0)
                {
                    $navee_entry_id = $v["entry_id"];
                }
                else
                {
                    $navee_entry_id = 0;
                }

                // Set navee_entry_id variable
                if (isset($v["channel_id"]) && $v["channel_id"] > 0)
                {
                    $navee_channel_id = $v["channel_id"];
                }
                else
                {
                    $navee_channel_id = 0;
                }

                // Failsafe
                if (isset($v["link_type"]))
                {
                    $link_type = $v["link_type"];
                }
                else
                {
                    $link_type = "manual";
                }

                // First/Last items on level
                if ($count == 1)
                {
                    $is_first_item_on_level = 1;
                }

                if ($count == sizeof($nav))
                {
                    $is_last_item_on_level = 1;
                }

                // add site url prefix
                if ($this->site_url_prefix)
                {
                    $v["link"] = $this->_prefix_site_url($v["link"]);
                }

                $vars[] = array(
                    'text'                   => $v["text"],
                    'link'                   => $v["link"],
                    'navee_link'             => $v["link"],
                    'kids'                   => $kids,
                    'navee_id'               => $v["navee_id"],
                    'navigation_id'          => $this->navigation_id,
                    'class'                  => $class,
                    'id'                     => $v["id"],
                    'rel'                    => $v["rel"],
                    'name'                   => $v["name"],
                    'target'                 => $v["target"],
                    'accesskey'              => $v["access_key"],
                    'title'                  => $v["title"],
                    'navee_title'            => $v["title"],
                    'level'                  => $depth + 1,
                    'has_kids'               => $this->has_kids,
                    'is_selected'            => $is_selected,
                    'kid_count'              => $this->kid_count,
                    'custom'                 => $v["custom"],
                    'custom_kids'            => $v["custom_kids"],
                    'navee_entry_id'         => $navee_entry_id,
                    'navee_channel_id'       => $navee_channel_id,
                    'is_empty'               => FALSE,
                    'link_type'              => $link_type,
                    'count'                  => $count,
                    'navee_count'            => $count,
                    'level_count'            => $navCount,
                    'include_in_nav'         => $v["include"],
                    'is_first_item_on_level' => $is_first_item_on_level,
                    'is_last_item_on_level'  => $is_last_item_on_level,
                    'is_prev_item_selected'  => $is_prev_item_selected,
                    'depth_of_selected'      => $this->depth_of_selected,
                    'selected_has_kids'      => $this->selected_has_kids
                );

                if ($this->disable_title)
                {
                    for ($i = 0; $i < sizeof($vars); $i++)
                    {
                        unset($vars[$i]['title']);
                    }
                }

                $returnMe = $this->EE->TMPL->parse_variables($this->EE->TMPL->tagdata, $vars);

                if ($is_selected)
                {
                    $is_prev_item_selected = TRUE;
                }
                else
                {
                    $is_prev_item_selected = FALSE;
                }

            }

            $openTagContents = "";

            if ($depth == 0)
            {
                if (strlen($this->nav_id) > 0)
                {
                    $openTagContents .= " id='" . $this->nav_id . "'";
                }
            }

            if ((strlen($this->nav_class) > 0) && ($depth == 0))
            {
                $openTagContents .= " class='" . $this->nav_class . "'";
            }
            else if ($this->kids_class && $depth > 0)
            {
                $kids_class_depth = count($this->kids_class) > 1 ? $depth - 1 : 0;
                $openTagContents .= " class='" . $this->kids_class[$kids_class_depth] . "'";
            }

            if (strlen($this->list_type) > 0)
            {
                $returnMe = "<" . $this->list_type . $openTagContents . ">" . $returnMe . "</" . $this->list_type . ">";
            }

        }
        else
        {
            // If this navigation is empty
            $vars[] = array(
                'is_empty' => TRUE
            );
        }

        return $returnMe;
    }

    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~>>
    //	S T Y L E   C R U M B S
    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~>>

    function _styleCrumbs($crumbs)
    {
        $returnMe = '';
        if ($this->no_last_anchor)
        {
            $count = 1;
        }
        else
        {
            $count = 0;
        }

        $crumbCount = sizeof($crumbs);

        if ($crumbCount > 0)
        {
            if (strlen($this->list_type) > 0)
            {
                $returnMe = '<' . $this->list_type;

                if (strlen($this->nav_class) > 0)
                {
                    $returnMe .= ' class="' . $this->nav_class . '"';
                }

                if (strlen($this->nav_id) > 0)
                {
                    $returnMe .= ' id="' . $this->nav_id . '"';
                }

                $returnMe .= '>';
            }


            // If the reverse parameter has been passed, let's reverse it.
            if ($this->reverse)
            {
                $crumbs = array_reverse($crumbs);
            }

            foreach ($crumbs as $k => $v)
            {

                // -------------------------------------------
                //  'navee_modify_crumb' hook
                //      - Modify the variables, URL, Label etc.
                //      - Credit to Brian Litzinger for this addition
                //
                if ($this->EE->extensions->active_hook('navee_modify_crumb'))
                {
                    $v = $this->EE->extensions->call('navee_modify_crumb', $this->navigation_id, $v);
                }
                //
                // -------------------------------------------

                // add site url prefix
                if ($this->site_url_prefix)
                {
                    $v["link"] = $this->_prefix_site_url($v["link"]);
                }

                if ($count < $crumbCount)
                {
                    $returnMe .= '<li><a href="' . $v["link"] . '">' . $v["text"] . '</a></li>';
                }
                else
                {
                    $returnMe .= '<li>' . $v["text"] . '</li>';
                }
                $count++;
            }
            if (strlen($this->list_type) > 0)
            {
                $returnMe .= '</' . $this->list_type . '>';
            }

        }
        return $returnMe;

    }

    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~>>
    //	S T Y L E   C U S T O M   C R U M B S
    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~>>

    function _styleCustomCrumbs($crumbs)
    {
        $returnMe   = "";
        $count      = 1;
        $crumbCount = sizeof($crumbs);

        if ($crumbCount > 0)
        {
            if (strlen($this->list_type) > 0)
            {
                $returnMe = "<" . $this->list_type;

                if (strlen($this->nav_class) > 0)
                {
                    $returnMe .= " class='" . $this->nav_class . "'";
                }

                if (strlen($this->nav_id) > 0)
                {
                    $returnMe .= " id='" . $this->nav_id . "'";
                }

                $returnMe .= ">";
            }

            // If the reverse parameter has been passed, let's reverse it.
            if ($this->reverse)
            {
                $crumbs = array_reverse($crumbs);
            }

            foreach ($crumbs as $k => $v)
            {
                // add site url prefix
                if ($this->site_url_prefix)
                {
                    $v["link"] = $this->_prefix_site_url($v["link"]);
                }

                // -------------------------------------------
                //  'navee_modify_crumb' hook
                //      - Modify the variables, URL, Label etc.
                //      - Credit to Brian Litzinger for this addition
                //
                if ($this->EE->extensions->active_hook('navee_modify_crumb'))
                {
                    $v = $this->EE->extensions->call('navee_modify_crumb', $this->navigation_id, $v);
                }
                //
                // -------------------------------------------

                $vars[] = array(
                    'text'          => $v["text"],
                    'link'          => $v["link"],
                    'navee_id'      => $v["navee_id"],
                    'navigation_id' => $this->navigation_id,
                    'class'         => $v["class"],
                    'id'            => $v["id"],
                    'rel'           => $v["rel"],
                    'name'          => $v["name"],
                    'target'        => $v["target"],
                    'custom'        => $v["custom"],
                    'custom_kids'   => $v["custom_kids"],
                    'spacer'        => (($count == $crumbCount) && ($this->no_last_spacer) ? "" : $this->spacer),
                    'is_last_item'  => ($count == $crumbCount) ? TRUE : FALSE,
                    'count'         => $count,
                    'navee_count'   => $count

                );

                // If no_last_spacer has been passed and this is
                // the last item, remove spacer

                $myCrumbs = $this->EE->TMPL->parse_variables($this->EE->TMPL->tagdata, $vars);
                $count++;
            }
            $returnMe .= $myCrumbs;
            if (strlen($this->list_type) > 0)
            {
                $returnMe .= "</" . $this->list_type . ">";
            }
        }
        return $returnMe;
    }

    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~>>
    //	G E T   C A C H E D   N A V
    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~>>

    function _getCachedNav($navId, $parent = 0, $recursive = TRUE, $ignoreInclude = FALSE)
    {
        $returnMe = FALSE;
        // Let's start by checking for a cached version of this nav
        $this->EE->db->select("cache");
        $this->EE->db->where("site_id", $this->site_id);
        $this->EE->db->where("group_id", $this->EE->session->userdata['group_id']);
        $this->EE->db->where("navigation_id", $navId);
        $this->EE->db->where("parent", $parent);

        if ($recursive)
        {
            $this->EE->db->where("recursive", 1);
        }
        else
        {
            $this->EE->db->where("recursive", 0);
        }

        if ($ignoreInclude)
        {
            $this->EE->db->where("ignore_include", 1);
        }
        else
        {
            $this->EE->db->where("ignore_include", 0);
        }

        if (($this->start_nav_from_parent) || ($this->start_nav_on_level_of_selected) || ($this->start_x_levels_above_selected > 0))
        {
            $this->EE->db->where("start_from_parent", 1);
            $this->EE->db->where("start_from_kid", 0);
        }
        elseif ($this->start_nav_with_kids_of_selected)
        {
            $this->EE->db->where("start_from_kid", 1);
            $this->EE->db->where("start_from_parent", 0);
        }

        if ($this->include_single_parent)
        {
            $this->EE->db->where("single_parent", 1);
        }
        else
        {
            $this->EE->db->where("single_parent", 0);
        }

        // -------------------------------------------
        //  'navee_get_cache' hook
        //      - Add additional where clauses to the query
        // 		- Credit to Brian Litzinger for this addition
        //
        if ($this->EE->extensions->active_hook('navee_get_cache'))
        {
            $edata = $this->EE->extensions->call('navee_get_cache', $navId, $parent, $recursive, $ignoreInclude);

            if ($edata && is_array($edata))
            {
                $this->EE->db->where($edata);
            }
        }
        //
        // -------------------------------------------

        $q = $this->EE->db->get("navee_cache");

        if ($q->num_rows() > 0)
        {
            $r         = $q->row();
            $this->nav = unserialize($r->cache);
            $returnMe  = TRUE;
        }

        return $returnMe;
    }

    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~>>
    //	S E T   C A C H E D   N A V
    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~>>

    function _setCachedNav($navId, $parent = 0, $recursive = TRUE, $ignoreInclude = FALSE)
    {

        $is_recursive      = 0;
        $ignore_include    = 0;
        $start_from_parent = 0;
        $start_from_kid    = 0;
        $single_parent     = 0;

        if ($recursive)
        {
            $is_recursive = 1;
        }

        if ($ignoreInclude)
        {
            $ignore_include = 1;
        }

        if (($this->start_nav_from_parent) || ($this->start_nav_on_level_of_selected) || ($this->start_x_levels_above_selected > 0))
        {
            $start_from_parent = 1;
        }
        elseif ($this->start_nav_with_kids_of_selected)
        {
            $start_from_kid = 1;
        }

        if ($this->include_single_parent)
        {
            $single_parent = 1;
        }

        $data = array(
            'site_id'           => $this->site_id,
            'navigation_id'     => $navId,
            'group_id'          => $this->EE->session->userdata['group_id'],
            'parent'            => $parent,
            'recursive'         => $is_recursive,
            'ignore_include'    => $ignore_include,
            'start_from_parent' => $start_from_parent,
            'start_from_kid'    => $start_from_kid,
            'single_parent'     => $single_parent,
            'cache'             => serialize($this->nav)
        );

        // -------------------------------------------
        //  'navee_set_cache' hook
        //      - Add additional where clauses to the query
        // 		- Credit to Brian Litzinger for this addition
        //
        if ($this->EE->extensions->active_hook('navee_set_cache'))
        {
            $edata = $this->EE->extensions->call('navee_set_cache', $navId, $parent, $recursive, $ignoreInclude);

            if ($edata && is_array($edata))
            {
                $data = array_merge($data, $edata);
            }
        }
        //
        // -------------------------------------------

        $this->EE->db->insert('navee_cache', $data);

        return TRUE;
    }

    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~>>
    //	G E T   N A V
    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~>>

    function _getNav($navId, $parent = 0, $recursive = TRUE, $ignoreInclude = FALSE)
    {
        $nav   = array();
        $pages = $this->EE->config->item('site_pages');
        if (is_array($pages) && sizeof($pages) > 0)
        {
            $pages = $pages[$this->site_id]["uris"];
        }
        else
        {
            $pages = array();
        }


        $this->EE->db->select("n.navee_id,
								n.parent,
								n.text,
								n.link,
								n.class,
								n.id,
								n.sort,
								n.include,
								n.passive,
								n.rel,
								n.name,
								n.target,
								n.regex,
								n.entry_id,
								n.channel_id,
								n.template,
								n.type,
								n.custom,
								n.custom_kids,
								n.access_key,
								n.title,
								t.template_name,
								tg.group_name,
								ct.url_title,
								ct.status,
								nm.members");
        $this->EE->db->from("navee AS n");
        $this->EE->db->join("navee_members AS nm", "nm.navee_id=n.navee_id", "LEFT OUTER");
        $this->EE->db->join("templates AS t", "n.template=t.template_id", "LEFT OUTER");
        $this->EE->db->join("template_groups AS tg", "t.group_id=tg.group_id", "LEFT OUTER");
        $this->EE->db->join("channel_titles AS ct", "n.entry_id=ct.entry_id", "LEFT OUTER");
        $this->EE->db->where("n.navigation_id", $navId);
        $this->EE->db->where("n.parent", $parent);
        $this->EE->db->where("n.site_id", $this->site_id);
        $this->EE->db->order_by("n.sort", "asc");
        if (!$ignoreInclude)
        {
            $this->EE->db->where("n.include", 1);
        }

        $q = $this->EE->db->get();

        if ($q->num_rows() > 0)
        {
            $count = 0;
            foreach ($q->result() as $r)
            {

                if (!$this->skip_closed_entries || ($this->skip_closed_entries && ($r->status == 'open' || $r->type == 'manual')))
                {
                    $hasAccess = FALSE;
                    if (strlen($r->members) > 0)
                    {
                        $memberGroups = unserialize($r->members);
                        if (in_array($this->EE->session->userdata['group_id'], $memberGroups))
                        {
                            $hasAccess = TRUE;
                        }
                    }
                    else
                    {
                        $hasAccess = TRUE;
                    }

                    if ($hasAccess)
                    {
                        // Build link based on which type it is
                        switch ($r->type)
                        {
                            case "guided":
                                $link_type           = $r->type;
                                $nav[$count]["link"] = "";

                                // add install directory if necessary
                                if (strlen($this->ee_install_directory) > 0)
                                {
                                    $nav[$count]["link"] .= "/" . $this->ee_install_directory;
                                }

                                // add index if necessary
                                if ($this->include_index == "true")
                                {
                                    $nav[$count]["link"] .= "/" . $this->EE->config->item('index_page');
                                }

                                // template group
                                $nav[$count]["link"] .= "/" . $r->group_name;

                                // template
                                if ($r->template_name !== "index")
                                {
                                    $nav[$count]["link"] .= "/" . $r->template_name;
                                }

                                // url_title
                                if (strlen($r->url_title) > 0)
                                {
                                    $nav[$count]["link"] .= "/" . $r->url_title;
                                }

                                break;
                            case "pages":
                                $link_type           = $r->type;
                                $nav[$count]["link"] = "";

                                // add install directory if necessary
                                if (strlen($this->ee_install_directory) > 0)
                                {
                                    $nav[$count]["link"] .= "/" . $this->ee_install_directory;
                                }

                                // add index if necessary
                                if ($this->include_index == "true")
                                {
                                    $nav[$count]["link"] .= "/" . $this->EE->config->item('index_page');
                                }

                                // pages content
                                if (sizeof($pages) > 0)
                                {
                                    if (array_key_exists($r->entry_id, $pages))
                                    {
                                        $nav[$count]["link"] .= $pages[$r->entry_id];
                                    }
                                }

                                break;
                            default:
                                $link_type           = "manual";
                                $nav[$count]["link"] = $this->_replace_globals($r->link);
                                break;
                        }

                        // add trailing slash
                        if ($this->force_trailing_slash !== "no" && $r->type !== "manual")
                        {
                            $nav[$count]["link"] = $this->_forceTrailingSlash($nav[$count]["link"]);
                        }

                        $nav[$count]["navee_id"]    = $r->navee_id;
                        $nav[$count]["parent"]      = $r->parent;
                        $nav[$count]["text"]        = $r->text;
                        $nav[$count]["class"]       = $r->class;
                        $nav[$count]["id"]          = $r->id;
                        $nav[$count]["sort"]        = $r->sort;
                        $nav[$count]["include"]     = $r->include;
                        $nav[$count]["passive"]     = $r->passive;
                        $nav[$count]["rel"]         = $r->rel;
                        $nav[$count]["name"]        = $r->name;
                        $nav[$count]["target"]      = $r->target;
                        $nav[$count]["access_key"]  = $r->access_key;
                        $nav[$count]["title"]       = $r->title;
                        $nav[$count]["regex"]       = $r->regex;
                        $nav[$count]["custom"]      = $r->custom;
                        $nav[$count]["custom_kids"] = $r->custom_kids;
                        $nav[$count]["entry_id"]    = $r->entry_id;
                        $nav[$count]["channel_id"]  = $r->channel_id;
                        $nav[$count]["link_type"]   = $link_type;

                        // -------------------------------------------
                        //  'navee_modify_nav' hook
                        //      - Modify the variables, URL, Label etc. If you use this, cache your queries!!
                        // 		- Credit to Brian Litzinger for this addition
                        //
                        if ($this->EE->extensions->active_hook('navee_modify_nav'))
                        {
                            $nav[$count] = $this->EE->extensions->call('navee_modify_nav', $navId, $nav[$count]);
                        }
                        //
                        // -------------------------------------------

                        if (($this->start_nav_from_parent) || ($this->start_x_levels_above_selected > 0))
                        {
                            $nav[$count]["kids"] = $this->_getNav($navId, $r->navee_id, $recursive, $ignoreInclude);
                        }
                        else
                        {
                            if ($this->recursive)
                            {
                                $nav[$count]["kids"] = $this->_getNav($navId, $r->navee_id, $recursive, $ignoreInclude);
                            }
                            else
                            {
                                $nav[$count]["kids"] = array();
                            }
                        }
                        $count++;
                    }

            }
            }
        }
        $q->free_result();
        return $nav;
    }

    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~>>
    //	G E T   S I N G L E   N A V   I T E M
    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~>>

    function _getSingleNavItem($navee_id)
    {
        $wrapNav = array();
        $count   = 0;
        $pages = $this->EE->config->item('site_pages');
        if (is_array($pages) && sizeof($pages) > 0)
        {
            $pages = $pages[$this->site_id]["uris"];
        }
        else
        {
            $pages = array();
        }

        $this->EE->db->select("n.navee_id,
								n.parent,
								n.text,
								n.link,
								n.class,
								n.id,
								n.sort,
								n.include,
								n.passive,
								n.rel,
								n.name,
								n.target,
								n.access_key,
								n.title,
								n.regex,
								n.entry_id,
								n.channel_id,
								n.template,
								n.type,
								n.custom,
								n.custom_kids,
								t.template_name,
								tg.group_name,
								ct.url_title,
								nm.members");
        $this->EE->db->from("navee AS n");
        $this->EE->db->join("navee_members AS nm", "nm.navee_id=n.navee_id", "LEFT OUTER");
        $this->EE->db->join("templates AS t", "n.template=t.template_id", "LEFT OUTER");
        $this->EE->db->join("template_groups AS tg", "t.group_id=tg.group_id", "LEFT OUTER");
        $this->EE->db->join("channel_titles AS ct", "n.entry_id=ct.entry_id", "LEFT OUTER");

        $this->EE->db->where("n.navee_id", $navee_id);
        $this->EE->db->where("n.site_id", $this->site_id);
        $this->EE->db->limit(1);

        $q = $this->EE->db->get();

        if ($q->num_rows() > 0)
        {
            $r = $q->row();

            $wrapNav[$count]["link"] = "";

            // Build link based on which type it is
            switch ($r->type)
            {
                case "guided":

                    // add install directory if necessary
                    if (strlen($this->ee_install_directory) > 0)
                    {
                        $wrapNav[$count]["link"] .= "/" . $this->ee_install_directory;
                    }

                    // add index if necessary
                    if ($this->include_index == "true")
                    {
                        $wrapNav[$count]["link"] .= "/" . $this->EE->config->item('index_page');
                    }

                    // template group
                    $wrapNav[$count]["link"] .= "/" . $r->group_name;

                    // template
                    if ($r->template_name !== "index")
                    {
                        $wrapNav[$count]["link"] .= "/" . $r->template_name;
                    }

                    // url_title
                    if (strlen($r->url_title) > 0)
                    {
                        $wrapNav[$count]["link"] .= "/" . $r->url_title;
                    }
                    break;
                case "pages":

                    // add install directory if necessary
                    if (strlen($this->ee_install_directory) > 0)
                    {
                        $wrapNav[$count]["link"] .= "/" . $this->ee_install_directory;
                    }

                    // add index if necessary
                    if ($this->include_index == "true")
                    {
                        $wrapNav[$count]["link"] .= "/" . $this->EE->config->item('index_page');
                    }

                    // pages content
                    $wrapNav[$count]["link"] .= $pages[$r->entry_id];

                    break;
                default:
                    $wrapNav[$count]["link"] = $this->_replace_globals($r->link);
                    break;
            }

            $wrapNav[$count]["navee_id"]    = $r->navee_id;
            $wrapNav[$count]["parent"]      = $r->parent;
            $wrapNav[$count]["text"]        = $r->text;
            $wrapNav[$count]["class"]       = $r->class;
            $wrapNav[$count]["id"]          = $r->id;
            $wrapNav[$count]["sort"]        = $r->sort;
            $wrapNav[$count]["include"]     = $r->include;
            $wrapNav[$count]["passive"]     = $r->passive;
            $wrapNav[$count]["rel"]         = $r->rel;
            $wrapNav[$count]["name"]        = $r->name;
            $wrapNav[$count]["target"]      = $r->target;
            $wrapNav[$count]["title"]       = $r->title;
            $wrapNav[$count]["access_key"]  = $r->access_key;
            $wrapNav[$count]["regex"]       = $r->regex;
            $wrapNav[$count]["kids"]        = "";
            $wrapNav[$count]["custom"]      = $r->custom;
            $wrapNav[$count]["custom_kids"] = $r->custom_kids;
        }


        $q->free_result();
        return $wrapNav;
    }

    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~>>
    //	S E T   B A S E   C R U M B
    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~>>

    function _getBaseCrumb($entry_id = 0, $uri_string = "")
    {
        $pages = $this->EE->config->item('site_pages');
        if (is_array($pages) && sizeof($pages) > 0)
        {
            $pages = $pages[$this->site_id]["uris"];
        }
        else
        {
            $pages = array();
        }

        if (strlen($uri_string) == 0)
        {
            $uri_string = $this->EE->uri->uri_string();
        }

        if ($entry_id > 0)
        {
            $this->EE->db->select("navee_id, navigation_id");

            if ($this->navigation_id)
            {
                $this->EE->db->where("navigation_id", $this->navigation_id);
            }
            $this->EE->db->where("site_id", $this->site_id);
            $this->EE->db->where("entry_id", $entry_id);
            $this->EE->db->where("passive", 0);
            $this->EE->db->order_by("navee_id", "desc");
            $q = $this->EE->db->get("navee", 1);

            if ($q->num_rows() > 0)
            {
                $r = $q->row();

                if ($this->navigation_id === 0)
                {
                    $this->navigation_id = $r->navigation_id;
                }

                return $r->navee_id;
            }

            $q->free_result();

        }
        elseif (sizeof($pages) > 0)
        {
            // Check to see if URI String in Pages Array
            foreach ($pages as $k => $v)
            {
                if ($this->_stripLink($v) == $uri_string)
                {
                    $this->EE->db->select("navee_id, navigation_id");

                    if ($this->navigation_id)
                    {
                        $this->EE->db->where("navigation_id", $this->navigation_id);
                    }
                    $this->EE->db->where("site_id", $this->site_id);
                    $this->EE->db->where("entry_id", $k);
                    $this->EE->db->where("passive", 0);
                    $this->EE->db->order_by("navee_id", "desc");
                    $q = $this->EE->db->get("navee");

                    if ($q->num_rows() > 0)
                    {
                        foreach ($q->result() as $r)
                        {
                            if ($this->navigation_id === 0)
                            {
                                $this->navigation_id = $r->navigation_id;
                            }
                            return $r->navee_id;
                        }
                    }

                    $q->free_result();
                }
            }
        }

        // Try to find a Manual Link match
        $this->EE->db->select("navee_id, navigation_id, link");

        if ($this->navigation_id)
        {
            $this->EE->db->where("navigation_id", $this->navigation_id);
        }
        $this->EE->db->where("site_id", $this->site_id);
        $this->EE->db->where("passive", 0);

        if (strlen($uri_string) == 0 || $uri_string == "/")
        {
            $index_array = array("/", "index.php", "/index.php");
            $this->EE->db->where_in("link", $index_array);
        }
        else
        {
            $this->EE->db->like("link", $uri_string);
        }

        $this->EE->db->order_by("navee_id", "desc");
        $q = $this->EE->db->get("navee");

        if ($q->num_rows() > 0)
        {
            foreach ($q->result() as $r)
            {
                if ($this->_stripLink($r->link) == $uri_string)
                {
                    if ($this->navigation_id === 0)
                    {
                        $this->navigation_id = $r->navigation_id;
                    }

                    return $r->navee_id;
                }
            }
        }

        $q->free_result();

        // Look for a Guided Link match
        $this->EE->db->select("n.link,
								n.navee_id,
								n.navigation_id,
								n.type,
								t.template_name,
								tg.group_name,
								ct.url_title");

        $this->EE->db->from("navee AS n");
        $this->EE->db->join("templates AS t", "n.template=t.template_id", "LEFT OUTER");
        $this->EE->db->join("template_groups AS tg", "t.group_id=tg.group_id", "LEFT OUTER");
        $this->EE->db->join("channel_titles AS ct", "n.entry_id=ct.entry_id", "LEFT OUTER");

        $this->EE->db->where("n.site_id", $this->site_id);
        $this->EE->db->where("n.type", "guided");
        $this->EE->db->where("n.passive", 0);

        if ($this->navigation_id)
        {
            $this->EE->db->where("n.navigation_id", $this->navigation_id);
        }

        if (!$this->ignore_include_in_nav)
        {
            $this->EE->db->where("n.include", 1);
        }

        $q = $this->EE->db->get();

        if ($q->num_rows() > 0)
        {
            foreach ($q->result() as $r)
            {
                $url = "";

                // template group
                $url .= "/" . $r->group_name;

                // template
                if ($r->template_name !== "index")
                {
                    $url .= "/" . $r->template_name;
                }

                // url_title
                if (strlen($r->url_title) > 0)
                {
                    $url .= "/" . $r->url_title;
                }

                if ($this->_stripLink($url) == $uri_string)
                {
                    if ($this->navigation_id === 0)
                    {
                        $this->navigation_id = $r->navigation_id;
                    }

                    return $r->navee_id;
                }
            }
        }

        $q->free_result();

        return 0;

    }

    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~>>
    //	G E T   C R U M B S
    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~>>

    function _getCrumbs($navee_id, $crumbs = array())
    {

        $pages = $this->EE->config->item('site_pages');
        if (is_array($pages) && sizeof($pages) > 0)
        {
            $pages = $pages[$this->site_id]["uris"];
        }
        else
        {
            $pages = array();
        }

        $this->EE->db->select("n.text,
								n.entry_id,
								n.link,
								n.navee_id,
								n.parent,
								n.class,
								n.id,
								n.rel,
								n.name,
								n.target,
								n.type,
								n.custom,
								n.custom_kids,
								t.template_name,
								tg.group_name,
								ct.url_title");

        $this->EE->db->from("navee AS n");
        $this->EE->db->join("templates AS t", "n.template=t.template_id", "LEFT OUTER");
        $this->EE->db->join("template_groups AS tg", "t.group_id=tg.group_id", "LEFT OUTER");
        $this->EE->db->join("channel_titles AS ct", "n.entry_id=ct.entry_id", "LEFT OUTER");

        $this->EE->db->where("n.site_id", $this->site_id);
        $this->EE->db->where("n.navee_id", $navee_id);

        if ($this->navigation_id)
        {
            $this->EE->db->where("n.navigation_id", $this->navigation_id);
        }

        if (!$this->ignore_include_in_nav)
        {
            $this->EE->db->where("n.include", 1);
        }

        $q = $this->EE->db->get();

        if ($q->num_rows() > 0)
        {
            $r              = $q->row();
            $pushMe         = array();
            $pushMe["link"] = "";

            // Build link based on which type it is
            switch ($r->type)
            {
                case "guided":

                    // add install directory if necessary
                    if (strlen($this->ee_install_directory) > 0)
                    {
                        $pushMe["link"] .= "/" . $this->ee_install_directory;
                    }

                    // add index if necessary
                    if ($this->include_index == "true")
                    {
                        $pushMe["link"] .= "/" . $this->EE->config->item('index_page');
                    }

                    // template group
                    $pushMe["link"] .= "/" . $r->group_name;

                    // template
                    if ($r->template_name !== "index")
                    {
                        $pushMe["link"] .= "/" . $r->template_name;
                    }

                    // url_title
                    if (strlen($r->url_title) > 0)
                    {
                        $pushMe["link"] .= "/" . $r->url_title;
                    }
                    break;
                case "pages":

                    // add install directory if necessary
                    if (strlen($this->ee_install_directory) > 0)
                    {
                        $pushMe["link"] .= "/" . $this->ee_install_directory;
                    }

                    // add index if necessary
                    if ($this->include_index == "true")
                    {
                        $pushMe["link"] .= "/" . $this->EE->config->item('index_page');
                    }

                    // pages content
                    $pushMe["link"] .= $pages[$r->entry_id];

                    break;
                default:
                    $pushMe["link"] = $this->_replace_globals($r->link);
                    break;
            }

            $pushMe["link_type"]   = $r->type;
            $pushMe["text"]        = $r->text;
            $pushMe["navee_id"]    = $r->navee_id;
            $pushMe["parent"]      = $r->parent;
            $pushMe["class"]       = $r->class;
            $pushMe["id"]          = $r->id;
            $pushMe["rel"]         = $r->rel;
            $pushMe["name"]        = $r->name;
            $pushMe["target"]      = $r->target;
            $pushMe["custom"]      = $r->custom;
            $pushMe["custom_kids"] = $r->custom_kids;

            array_unshift($crumbs, $pushMe);

            if ($r->parent > 0)
            {
                $crumbs = $this->_getCrumbs($r->parent, $crumbs);
            }
        }

        $q->free_result();
        return $crumbs;
    }

    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~>>
    // F O R C E   T R A I L I N G   S L A S H
    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~>>

    function _forceTrailingSlash($link)
    {
        if (strlen($link) > 0 && $link !== "/")
        {
            switch ($this->force_trailing_slash)
            {
                case "add":
                    if (substr($link, -1) !== '/')
                    {
                        $link .= "/";
                    }
                    break;
                case "remove":
                    if (substr($link, -1) == '/')
                    {
                        $link = rtrim($link, '/');
                    }
                    break;
            }
        }

        return $link;
    }

    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~>>
    // I S   D E S C E N D E N T   S E L E C T E D 
    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~>>

    function _isDescendentSelected($kids)
    {
        $returnMe = FALSE;

        if (sizeof($kids) > 0)
        {
            foreach ($kids as $k => $v)
            {
                if (strlen($v["regex"]) > 0 && $this->ignore_regex == FALSE)
                {
                    if (preg_match($v["regex"], $this->EE->uri->uri_string()) && ($v["passive"] == 0))
                    {
                        $returnMe = TRUE;
                        return $returnMe;
                    }
                }
                else
                {
                    // If this page matches the link
                    if ((strlen($v["link"]) > 0) && ($this->_stripLink($v["link"]) == $this->EE->uri->uri_string()) && ($v["passive"] == 0))
                    {
                        $returnMe = TRUE;
                        return $returnMe;
                    }
                }

                if (sizeof($v["kids"]) > 0)
                {
                    $returnMe = $this->_isDescendentSelected($v["kids"]);
                    if ($returnMe)
                    {
                        return $returnMe;
                    }
                }
            }
        }

        return $returnMe;
    }

    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~>>
    // I S   P A R E N T   S E L E C T E D 
    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~>>

    function _isParentSelected($v)
    {
        $returnMe = FALSE;
        if ($v["parent"] > 0)
        {
            $parent = $this->_getSingleNavItem($v["parent"]);
            if ($this->_isSelected($parent[0], FALSE))
            {
                $returnMe = TRUE;
            }
            elseif ($parent[0]["parent"] > 0)
            {
                $returnMe = $this->_isParentSelected($parent[0]);
            }
        }
        return $returnMe;
    }

    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~>>
    // I S   S I B L I N G   S E L E C T E D 
    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~>>

    function _isSiblingSelected($v)
    {
        $returnMe = FALSE;

        if ($v["parent"] > 0)
        {
            $parent = $this->_getSingleNavItem($v["parent"]);
            if ($this->_isSelected($parent[0], FALSE))
            {
                $returnMe = TRUE;
            }
            elseif ($parent[0]["parent"] > 0)
            {
                $returnMe = $this->_isParentSelected($parent[0]);
            }
        }
        return $returnMe;
    }

    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~>>
    // I S   S E L E C T E D 
    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~>>

    function _isSelected($v, $checkChildren = TRUE)
    {
        $returnMe = FALSE;
        if (strlen($v["regex"]) > 0 && $this->ignore_regex == FALSE)
        {
            if (preg_match($v["regex"], $this->EE->uri->uri_string()) && ($v["passive"] == 0))
            {
                $returnMe = TRUE;
                return $returnMe;
            }
        }
        else
        {
            // If this page matches the link
            if ((strlen($v["link"]) > 0) && ($this->_stripLink($v["link"]) == $this->EE->uri->uri_string()) && ($v["passive"] == 0))
            {
                $returnMe = TRUE;
                return $returnMe;
            }
        }

        if ($checkChildren)
        {
            if (sizeof($v["kids"]) > 0)
            {
                $returnMe = $this->_isDescendentSelected($v["kids"]);
                if ($returnMe)
                {
                    return $returnMe;
                }
            }
        }

        return $returnMe;
    }

    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~>>
    // S E L E C T E D   D E P T H
    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~>>

    function _selectedDepth($nav, $depth = 1)
    {
        $returnMe = 0;
        $hasKids  = FALSE;

        if (sizeof($nav) > 0)
        {
            foreach ($nav as $k => $v)
            {
                $hasKids = FALSE;
                if (sizeof($v["kids"]) > 0)
                {
                    $hasKids = TRUE;
                }

                if (strlen($v["regex"]) > 0 && $this->ignore_regex == FALSE)
                {
                    if (preg_match($v["regex"], $this->EE->uri->uri_string()))
                    {
                        $returnMe                = $depth;
                        $this->selected_has_kids = $hasKids;
                        return $returnMe;
                    }
                }
                else
                {
                    // If this page matches the link
                    if ((strlen($v["link"]) > 0) && ($this->_stripLink($v["link"]) == $this->EE->uri->uri_string()))
                    {
                        $returnMe                = $depth;
                        $this->selected_has_kids = $hasKids;
                        return $returnMe;
                    }
                }

                if ($hasKids)
                {
                    $returnMe = $this->_selectedDepth($v["kids"], $depth + 1);
                    if ($returnMe > 0)
                    {
                        return $returnMe;
                    }
                }
            }
        }

        return $returnMe;
    }


    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~>>
    // S E L E C T E D   P A R E N T   S U B S E T 
    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~>>

    function _selectedParentSubset($nav, $depth = 1)
    {
        $subset = array();

        if (sizeof($nav) > 0)
        {
            foreach ($nav as $k => $v)
            {
                if ($this->_isSelected($v))
                {
                    if ($depth == $this->start_nav_from_parent_depth)
                    {
                        $subset = $v["kids"];
                    }
                    else
                    {
                        $subset = $this->_selectedParentSubset($v["kids"], $depth + 1);
                    }
                    break;
                }
            }
        }

        return $subset;
    }

    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~>>
    // S E L E C T E D   K I D   S U B S E T 
    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~>>

    function _selectedKidsSubset($nav)
    {
        $subset = array();

        if (sizeof($nav) > 0)
        {
            foreach ($nav as $k => $v)
            {
                if (strlen($v["regex"]) > 0 && $this->ignore_regex == FALSE)
                {
                    if (preg_match($v["regex"], $this->EE->uri->uri_string()))
                    {
                        $subset = $v["kids"];
                        return $subset;
                    }
                }
                else
                {
                    // If this page matches the link
                    if ((strlen($v["link"]) > 0) && ($this->_stripLink($v["link"]) == $this->EE->uri->uri_string()))
                    {
                        $subset = $v["kids"];
                        return $subset;
                    }
                }

                if (sizeof($v["kids"]) > 0)
                {
                    $subset = $this->_selectedKidsSubset($v["kids"]);
                    if (sizeof($subset) > 0)
                    {
                        return $subset;
                    }
                }
            }
        }

        return $subset;
    }

    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~>>
    // S E L E C T E D   S I B L I N G   S U B S E T 
    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~>>

    function _selectedSiblingSubset($nav, $depth = 1)
    {
        $subset = array();
        if (sizeof($nav) > 0)
        {
            foreach ($nav as $k => $v)
            {
                if ($this->_isSelected($v, $this->selected_class_on_parents))
                {
                    $subset = $nav;
                    break;
                }
                else
                {
                    if (sizeof($v["kids"]) > 0)
                    {
                        $subset = $this->_selectedSiblingSubset($v["kids"], $depth + 1);
                        if (sizeof($subset) > 0)
                        {
                            return $subset;
                        }
                    }
                }
            }
        }

        return $subset;
    }

    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~>>
    // A D D   S I N G L E   P A R E N T 
    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~>>

    function _addSingleParent()
    {
        $wrapNav = array();
        if (sizeof($this->nav) > 0 || $this->parent > 0)
        {
            $parent = sizeof($this->nav) > 0 ? $this->nav[0]["parent"] : $this->parent;
            if ($parent > 0)
            {
                $wrapNav            = $this->_getSingleNavItem($parent);
                $wrapNav[0]["kids"] = $this->nav;
                $this->nav          = $wrapNav;
            }
        }
        return TRUE;
    }

    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~>>
    //	S T R I P   L I N K
    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~>>

    function _stripLink($link)
    {
        if (strlen($this->ee_install_directory) > 0)
        {
            $install_dir = str_replace("/", "\/", $this->ee_install_directory);

            if (substr($link, 0, 1) == '/')
            {
                $escaped_leading_slash = "\/";
            }
            else
            {
                $escaped_leading_slash = '';
            }

            $regex = '/^' . $escaped_leading_slash . $install_dir . '/i';
            $link  = preg_replace($regex, '', $link);
        }
        $link = str_replace("{site_url}", "", $link);
        $link = preg_replace('/.*' . $this->escaped_index_page . '\/*/i', '', $link);
        $link = preg_replace('/^[^A-Za-z0-9-_]+/i', '', $link);
        $link = preg_replace('/(\#|\?).*$/i', '', $link);
        $link = preg_replace('/\/$/i', '', $link);
        $link = preg_replace('/[\.]+\//i', '', $link);

        // -------------------------------------------
        //  'navee_strip_link' hook
        //      - Clean up the link, such as removing language codes, before its used in comparison operators for styling the nav.
        //      - Credit to Brian Litzinger for this addition
        //
        if ($this->EE->extensions->active_hook('navee_strip_link'))
        {
            $link = $this->EE->extensions->call('navee_strip_link', $link);
        }
        //
        // -------------------------------------------

        return $link;
    }

    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~>>
    //	F O R M A T   I N S T A L L   D I R E C T O R Y
    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~>>

    function _formatInstallDirectory($dir)
    {
        $dir = preg_replace('/^\//i', '', $dir);
        $dir = preg_replace('/\/$/i', '', $dir);
        return $dir;
    }

    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~>>
    //	R E P L A C E   G L O B A L
    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~>>

    function _replace_globals($link)
    {
        // Replace path global
        if (strpos($link, 'path=') !== FALSE)
        {
            $link = preg_replace_callback("/" . LD . "\s*path=(.*?)" . RD . "/", array(&$this->EE->functions, 'create_url'), $link);
        }

        // Replace segment variables
        for ($i = 1; $i < 10; $i++)
        {
            $link = str_replace(LD . 'segment_' . $i . RD, $this->EE->uri->segment($i), $link);
        }

        return $link;
    }

    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~>>
    //	P R E F I X   S I T E   U R L
    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~>>

    function _prefix_site_url($link)
    {
        $link_has_leading_slash = FALSE;

        if (substr($link, 0, 1) == '/')
        {
            $link_has_leading_slash = TRUE;
        }

        if (substr($link, 0, 4) !== 'http' && substr($link, 0, 1) !== '#' && strlen($link) > 0)
        {
            if ($link_has_leading_slash && $this->site_url_has_trailing_slash)
            {
                $link = substr($link, 1);
            }

            $link = $this->EE->config->slash_item('site_url') . $link;

        }

        return $link;
    }
}