<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Focus Lab, LLC Master Config
 * 
 * This is the master config file for our ExpressionEngine sites
 * The settings will contain database credentials and numerous "config overrides"
 * used throughout the site. This file is used as first point of configuration
 * but there are environment-specific files as well. The idea is that the environment
 * config files contain config overrides that are specific to a single environment.
 * 
 * Some config settings are used in multiple (but not all) environments. You will
 * see the use of conditionals around the ENV constant in this file. This constant is
 * defined in ./config/config.env.php
 * 
 * All config files are stored in the ./config/ directory and this master file is "required"
 * in system/expressionengine/config/config.php and system/expressionengine/config/database.php
 * 
 * require $_SERVER['DOCUMENT_ROOT'] . '/../config/config.master.php';
 * 
 * This config setup is a combination of inspiration from Matt Weinberg and Leevi Graham
 * @link       http://eeinsider.com/articles/multi-server-setup-for-ee-2/
 * @link       http://ee-garage.com/nsm-config-bootstrap
 * 
 * @package    Focus Lab Master Config
 * @version    1.1.1
 * @author     Focus Lab, LLC <dev@focuslabllc.com>
 * @see        https://github.com/focuslabllc/ee-master-config
 */


// Require our environment declatation file if it hasn't
// already been loaded in index.php or admin.php
if ( ! defined('ENV'))
{
	require 'config.env.php'; 
}


// Setup our initial arrays
$env_db = $env_config = $env_global = $master_global = array();


/**
 * Database override magic
 * 
 * If this equates to TRUE then we're in the database.php file
 * We don't want these settings bothered with in our config.php file
 */
if (isset($db['expressionengine']))
{
	/**
	 * Load our environment-specific config file
	 * which contains our database credentials
	 * 
	 * @see config/config.local.php
	 * @see config/config.dev.php
	 * @see config/config.stage.php
	 * @see config/config.prod.php
	 */
	require 'config.' . ENV . '.php';
	
	// Dynamically set the cache path (Shouldn't this be done by default? Who moves the cache path?)
	$env_db['cachedir'] = APPPATH . 'cache/db_cache/';
	
	// Merge our database setting arrays
	$db['expressionengine'] = array_merge($db['expressionengine'], $env_db);

	// Merge our sync database sessings
	$db['sync_db'] = array_merge($db['expressionengine'], $env_sync_db);
	
	// No need to have this variable accessible for the rest of the app
	unset($env_db);
	unset($env_sync_db);
}
// End if (isset($db['expressionengine'])) {}



/**
 * Config override magic
 * 
 * If this equates to TRUE then we're in the config.php file
 * We don't want these settings bothered with in our database.php file
 */
if (isset($config))
{

	/**
	 * Dynamic path settings
	 * 
	 * Make it easy to run the site in multiple environments and not have to switch up
	 * path settings in the database after each migration
	 * As inspired by Matt Weinberg: http://eeinsider.com/articles/multi-server-setup-for-ee-2/
	 */
	$protocol                          = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ? 'https://' : 'http://';
	$base_url                          = $protocol . $_SERVER['HTTP_HOST'];
	$base_path                         = $_SERVER['DOCUMENT_ROOT'];
	$system_folder                     = APPPATH . '../';
	$images_folder                     = 'images';
	$images_path                       = $base_path . '/' . $images_folder;
	$images_url                        = $base_url . '/' . $images_folder;

	$env_config['index_page']          = '';
	$env_config['site_index']          = '';
	$env_config['base_url']            = $base_url . '/';
	$env_config['site_url']            = $env_config['base_url'];
	$env_config['cp_url']              = $env_config['base_url'] . 'admin.php';
	$env_config['theme_folder_path']   = $base_path   . '/themes/';
	$env_config['theme_folder_url']    = $base_url    . '/themes/';
	$env_config['emoticon_path']       = $images_url  . '/smileys/';
	$env_config['emoticon_url']        = $images_url  . '/smileys/';
	$env_config['captcha_path']        = $images_path . '/captchas/';
	$env_config['captcha_url']         = $images_url  . '/captchas/';
	$env_config['avatar_path']         = $images_path . '/avatars/';
	$env_config['avatar_url']          = $images_url  . '/avatars/';
	$env_config['photo_path']          = $images_path . '/member_photos/';
	$env_config['photo_url']           = $images_url  . '/member_photos/';
	$env_config['sig_img_path']        = $images_path . '/signature_attachments/';
	$env_config['sig_img_url']         = $images_url  . '/signature_attachments/';
	$env_config['prv_msg_upload_path'] = $images_path . '/pm_attachments/';
	$env_config['third_party_path']    = $base_path . '/site/third_party/';

	// Updater paths
	$env_config['updater']['path_map']['root'] = $base_path; // Document Root
	$env_config['updater']['path_map']['backup'] = $base_path . '/site/backup/'; // Backup Dir
	$env_config['updater']['path_map']['system'] = $base_path . '/system/'; // System Dir
	$env_config['updater']['path_map']['system_third_party'] = $base_path . '/site/third_party/'; // Third Party dir system dir
	$env_config['updater']['path_map']['themes'] = $env_config['theme_folder_path']; // Themes dir
	$env_config['updater']['path_map']['themes_third_party'] = $env_config['theme_folder_path'] . '/third_party/'; // Third Party dir in themes dir


	// CE Image paths
	$env_config['ce_image_cache_dir'] = '/uploads/cache/';


	/**
	 * Custom upload directory paths
	 * 
	 * The array keys must match the ID from exp_upload_prefs
	 */
	$env_config['upload_preferences'] = array(
		1 => array(
	    	'name'        => 'Page Images',
	        'server_path' => $base_path . '/uploads/page_images/',
	        'url'         => $base_url  . '/uploads/page_images/'
	   	),
	    2 => array(
	        'name'        => 'Misc Images',
	        'server_path' => $base_path . '/uploads/misc_images/',
	        'url'         => $base_url  . '/uploads/misc_images/'
	    ),
	    4 => array(
	        'name'        => 'Category Images',
	        'server_path' => $base_path . '/uploads/category_images/',
	        'url'         => $base_url  . '/uploads/category_images/'
	    ),
	    5 => array(
	        'name'        => 'Blog Images',
	        'server_path' => $base_path . '/uploads/blog_images/',
	        'url'         => $base_url  . '/uploads/blog_images/'
	    ),
	    6 => array(
	        'name'        => 'Product Additional Images',
	        'server_path' => $base_path . '/uploads/product_addl_images/',
	        'url'         => $base_url  . '/uploads/product_addl_images/'
	    ),
	    7 => array(
	        'name'        => 'Form Uploads',
	        'server_path' => $base_path . '/uploads/form_uploads/',
	        'url'         => $base_url  . '/uploads/form_uploads/'
	    )
	);

	/* Removed product images since we don't use this.
		3 => array(
	        'name'        => 'Product Images',
	        'server_path' => $base_path . '/uploads/product_images/',
	        'url'         => $base_url  . '/uploads/product_images/'
	    ),
	*/


	/**
	 * Template settings
	 * 
	 * Working locally we want to reference our template files.
	 * In staging and production we do not use flat files (for ever-so-slightly better performance)
	 * This approach requires that we synchronize templates after each deployment of template changes
	 * 
	 * For the distributed Focus Lab, LLC Master Config file this is commented out
	 * You can enable this "feature" by uncommenting the second 'save_tmpl_files' line
	 */
	$env_config['save_tmpl_files']           = 'y';
	// $env_config['save_tmpl_files']           = (ENV == 'prod') ? 'n' : 'y';
	$env_config['tmpl_file_basepath']        = $base_path . '/site/templates';
	$env_config['hidden_template_indicator'] = '.'; 

	/**
	 * Snippet Sync settings
	 * 
	 * Set the path of the snippets directory for Snippet Sync
	 */
	$env_config['snippet_file_basepath']        = $base_path . '/site/snippets';
	$env_config['snippets_sync_prefix'] = 'snippet:'; 


	/**
	 * Debugging settings
	 * 
	 * These settings are helpful to have in one place
	 * for debugging purposes
	 */
	$env_config['is_system_on']         = 'y';
	$env_config['allow_extensions']     = 'y';
	$env_config['email_debug']          = (ENV_DEBUG) ? 'y' : 'n' ;
	// If we're not in production show the profile on the front-end but not in the CP
	$env_config['show_profiler']        = ( ! ENV_DEBUG OR (isset($_GET['D']) && $_GET['D'] == 'cp')) ? 'n' : 'y' ;
	// Show template debugging if we're not in production
	$env_config['template_debugging']   = (ENV_DEBUG) ? 'y' : 'n' ;
	/**
	 * Set debug to '2' if we're in dev mode, otherwise just '1'
	 * 
	 * 0: no PHP/SQL errors shown
	 * 1: Errors shown to Super Admins
	 * 2: Errors shown to everyone
	 */
	$env_config['debug']                = (ENV_DEBUG) ? '2' : '1' ;



	/**
	 * Tracking & Performance settings
	 * 
	 * These settings may impact what happens on certain page loads
	 * and turning them off could help with performance in general
	 */
	$env_config['disable_all_tracking']        = 'y'; // If set to 'y' some of the below settings are disregarded
	$env_config['enable_sql_caching']          = 'n';
	$env_config['disable_tag_caching']         = 'n';
	$env_config['enable_online_user_tracking'] = 'n';
	$env_config['dynamic_tracking_disabling']  = '500';
	$env_config['enable_hit_tracking']         = 'n';
	$env_config['enable_entry_view_tracking']  = 'n';
	$env_config['log_referrers']               = 'n';
	$env_config['gzip_output']                 = 'y';



	/**
	 * 3rd Party Add-on config items as needed
	 */
	



	/**
	 * Member-based settings
	 */
	$env_config['profile_trigger']          = rand(0,time()); // randomize the member profile trigger word because we'll never need it



	/**
	 * Other system settings
	 */
	$env_config['new_version_check']        = 'n'; // no slowing my CP homepage down with this
	$env_config['daylight_savings']         = ((bool) date('I')) ? 'y' : 'n'; // Autodetect DST
	$env_config['use_category_name']        = 'y';
	$env_config['reserved_category_word']   = 'category';
	$env_config['word_separator']           = 'dash'; // dash|underscore




	/**
	 * Load our environment-specific config file
	 * May contain override values from similar above settings
	 * 
	 * @see config/config.local.php
	 * @see config/config.dev.php
	 * @see config/config.stage.php
	 * @see config/config.prod.php
	 */
	require $_SERVER['DOCUMENT_ROOT'] . '/site/config/config.' . ENV . '.php';




	/**
	 * Setup our template-level global variables
	 * 
	 * As inspired by NSM Bootstrap Config
	 * @see http://ee-garage.com/nsm-config-bootstrap
	 */
	global $assign_to_config;
	if( ! isset($assign_to_config['global_vars']))
	{
		$assign_to_config['global_vars'] = array();
	}
	
	// Start our array with environment variables. This gives us {global:env} and {global:env_full} tags for our templates.
	$master_global = array(
		'global:env'      => ENV,
		'global:env_full' => ENV_FULL
	);



	/**
	 * Merge arrays to form final datasets
	 * 
	 * We've created our base config and global key->value stores
	 * We've also included the environment-specific arrays now
	 * Here we'll merge the arrays to create our final array dataset which
	 * respects "most recent data" first if any keys are duplicated
	 * 
	 * This is how our environment settings are "king" over any defaults
	 */
	$assign_to_config['global_vars'] = array_merge($assign_to_config['global_vars'], $master_global, $env_global); // global var arrays
	$config = array_merge($config, $env_config); // config setting arrays
	
}
// End if (isset($config)) {}


/* End of file config.master.php */
/* Location: ./config/config.master.php */