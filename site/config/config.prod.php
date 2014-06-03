<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Production config overrides & db credentials
 * 
 * Our database credentials and any environment-specific overrides
 * 
 * @package    Focus Lab Master Config
 * @version    1.1.1
 * @author     Focus Lab, LLC <dev@focuslabllc.com>
 */

$env_db['hostname'] = 'localhost';
$env_db['username'] = 'bmi_ee';
$env_db['password'] = 's51TlbH890';
$env_db['database'] = 'bmi_ee';

// Local sync db
$env_sync_db['hostname'] = 'localhost';
$env_sync_db['username'] = 'bmi_ee';
$env_sync_db['password'] = 's51TlbH890';
$env_sync_db['database'] = 'bmi_sync';
$env_sync_db['dbprefix'] = '';

$env_config['mail_protocol'] = "smtp";
$env_config['smtp_server'] = "smtp.voonami.com";
$env_config['smtp_username'] = "";
$env_config['smtp_passwrd'] = "";
$env_config['smtp_port'] = '25';

$env_config['cc_api_key'] = 'p64ds5guge2wgxcbvk6j3cuu';
$env_config['cc_access_token'] = 'e837efbd-0c53-4154-a343-6474a28ec4e7';
$env_config['cc_list_id'] = '1';

$env_config['integration_product_import_id'] = '13';


$env_global['global:google_analytics'] = 'UA-5873111-1';

$env_global['global:secure'] = 'yes';

/* End of file config.prod.php */
/* Location: ./config/config.prod.php */