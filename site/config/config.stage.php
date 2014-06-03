<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Staging config overrides & db credentials
 * 
 * Our database credentials and any environment-specific overrides
 * 
 * @package    Focus Lab Master Config
 * @version    1.1.1
 * @author     Focus Lab, LLC <dev@focuslabllc.com>
 */

$env_db['hostname'] = 'localhost';
$env_db['username'] = 'cxgp_s_db';
$env_db['password'] = 'cx3355134';
$env_db['database'] = 'cxgp_s_bmi';

// Local sync db
$env_sync_db['hostname'] = 'localhost';
$env_sync_db['username'] = 'cxgp_s_db';
$env_sync_db['password'] = 'cx3355134';
$env_sync_db['database'] = 'cxgp_s_bmim';
$env_sync_db['dbprefix'] = '';

$env_config['mail_protocol'] = "smtp";
$env_config['smtp_server'] = "mail.cxgp.net";
$env_config['smtp_port'] = "25";
$env_config['smtp_username'] = "projects@confluxgroup.com";
$env_config['smtp_passwrd'] = "cx3355134";

$env_global['global:secure'] = 'no';

/* End of file config.stage.php */
/* Location: ./config/config.stage.php */