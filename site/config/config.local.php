<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Local config overrides & db credentials
 * 
 * Our database credentials and any environment-specific overrides
 * This file should be specific to each developer and not tracked in Git
 * 
 * @package    Focus Lab Master Config
 * @version    1.1.1
 * @author     Focus Lab, LLC <dev@focuslabllc.com>
 */


// Local db
//$env_db['hostname'] = 'staging.cxgp.net';
//$env_db['username'] = 'cxgp_s_db';
//$env_db['password'] = 'cx3355134';
//$env_db['database'] = 'cxgp_s_bmi';

$env_db['hostname'] = 'localhost';
$env_db['username'] = 'root';
$env_db['password'] = '';
$env_db['database'] = 'bmi_staging_ee';

// Local sync db
//$env_sync_db['hostname'] = 'staging.cxgp.net';
//$env_sync_db['username'] = 'cxgp_s_db';
//$env_sync_db['password'] = 'cx3355134';
//$env_sync_db['database'] = 'cxgp_s_bmim';
//$env_sync_db['dbprefix'] = '';

// Local sync db
$env_sync_db['hostname'] = 'localhost';
$env_sync_db['username'] = 'root';
$env_sync_db['password'] = '';
$env_sync_db['database'] = 'bmi_staging_sync';
$env_sync_db['dbprefix'] = '';

$env_config['mail_protocol'] = "mail";

$env_config['integration_product_import_id'] = '10';

$env_global['global:secure'] = 'no';
$env_global['global:google_analytics'] = '';

#$env_config['mail_protocol'] = "smtp";
#$env_config['smtp_server'] = "mail.cxgp.net";
#$env_config['smtp_username'] = "projects@confluxgroup.com";
#$env_config['smtp_passwrd'] = "cx3355134";

// test
//$env_config['cc_api_key'] = 'p64ds5guge2wgxcbvk6j3cuu';
//$env_config['cc_access_token'] = '6cccafe2-ebd2-4499-b67c-f7fb4c862f93';
//$env_config['cc_list_id'] = '1106014065';

// live
//$env_config['cc_api_key'] = 'p64ds5guge2wgxcbvk6j3cuu';
//$env_config['cc_access_token'] = 'e837efbd-0c53-4154-a343-6474a28ec4e7';
//$env_config['cc_list_id'] = '1';



/* End of file config.local.php */
/* Location: ./config/config.local.php */
