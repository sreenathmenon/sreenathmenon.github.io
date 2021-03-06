<?php 

class Detour_pro_ext {

	public $settings        = array();
	public $name            = 'Detour Pro';
	public $version         = '1.4';
	public $description     = 'Reroute urls to another URL.';
	public $settings_exist  = 'y';
	public $docs_url        = 'http://www.cityzen.com/addons/detour_pro';

	function Detour_pro_ext($settings = FALSE)
	{
		$this->__construct($settings);
	}


	function __construct($settings = FALSE)
	{
		$this->settings = $settings;
		$this->EE =& get_instance();
	}


	function sessions_start()
	{
		if(session_id() == '')
		{
			session_start();
		}
				
		if(!array_key_exists('detour', $_SESSION))
		{
			if(is_array($this->settings) && array_key_exists('url_detect', $this->settings) && $this->settings['url_detect'] == 'php')
			{
				$site_index_file = ($this->EE->config->item('site_index')) ? $this->EE->config->item('site_index') . '/' : null;
				$url = trim(str_replace($site_index_file, '', $_SERVER['REQUEST_URI']), '/');
			}
			else
			{
				$url = trim($this->EE->uri->uri_string);
			}
			
			$url = urldecode($url);
			
			$sql = "SELECT detour_id, original_url, new_url, detour_method, start_date, end_date 
			FROM exp_detours 
			WHERE (start_date IS NULL OR start_date <= NOW())
			AND (end_date IS NULL OR end_date >= NOW())
			AND '" . $this->EE->db->escape_str($url) . "' LIKE REPLACE(original_url, '_', '[_') ESCAPE '['   
			AND site_id = " . $this->EE->config->item('site_id');
			
			$detour = $this->EE->db->query($sql)->row_array();
	
			if(!empty($detour))
			{
				$tail = $this->_get_tail($url, $detour['original_url']);
				
				$site_url = ($this->EE->config->item('site_url')) ? rtrim($this->EE->config->item('site_url'),'/') . '/' : '';
				$site_index = ($this->EE->config->item('site_index')) ? rtrim($this->EE->config->item('site_index'),'/') . '/' : '';
				
				$site_index = $site_url . $site_index;
				
				// Update detours_hits table
				$this->EE->db->set('detour_id', $detour['detour_id']);
				$this->EE->db->set('hit_date', 'NOW()', FALSE);
				$this->EE->db->insert('detours_hits');
				
				if(strpos($this->EE->db->escape_str($url), '%'))
				{
					$_SESSION['detour'] = TRUE;
				}
				
				if(substr($detour['new_url'],0,4) == 'http')
				{
					header('Location: ' . $detour['new_url'] . $tail, TRUE, $detour['detour_method']);	
				}
				else
				{
					header('Location: ' . $site_index . $detour['new_url'] . $tail, TRUE, $detour['detour_method']);
				}	
				$this->extensions->end_script;
				exit;
			}	
		}
		else
		{
			unset($_SESSION['detour']);
		}
	}

	function settings()
	{
		$url_detect_options = array(
			'ee' => 'Expression Engine Native'
		);
		
		if(array_key_exists('REQUEST_URI', $_SERVER))
		{
			$url_detect_options['php'] = 'PHP $_SERVER[\'REQUEST_URI\'] ';
		}
		
		$settings['url_detect']    = array('s', $url_detect_options, 'ee');
		
		return $settings;
	}

	function activate_extension()
	{

		$this->EE->db->where('class', 'Detour_ext');
		$this->EE->db->delete('extensions');
	
		$this->EE->load->dbforge();

		$data = array(
		  'class'       => __CLASS__,
		  'hook'        => 'sessions_start',
		  'method'      => 'sessions_start',
		  'settings'    => serialize($this->settings),
		  'priority'    => 1,
		  'version'     => $this->version,
		  'enabled'     => 'n'
		);
		
		$this->EE->functions->clear_caching('db');
		$this->EE->db->insert('exp_extensions', $data);
	}

	function disable_extension()
	{
		$this->EE->load->dbforge();

		$this->EE->functions->clear_caching('db');
		$this->EE->db->where('class', __CLASS__);
		$this->EE->db->delete('exp_extensions');
	}	

	function update_extension($current = '')
	{
	    if ($current == '' OR $current == $this->version)
	    {
	        return FALSE;
	    }
	
	    if ($current < '1.0')
	    {
	        // Update to version 1.0
	    }
	
	    $this->EE->db->where('class', __CLASS__);
	    $this->EE->db->update(
	                'extensions',
	                array('version' => $this->version)
	    );
	}

	// Protected Methods
	
	protected function _site_url()
	{
		 if(array_key_exists('PATH_INFO', $_SERVER) === true)
		 {
		 	return $_SERVER['PATH_INFO'];
		 }
		
		 $whatToUse = basename(__FILE__);
		
		 return substr($_SERVER['PHP_SELF'], strpos($_SERVER['PHP_SELF'], $whatToUse) + strlen($whatToUse));
	}
	
	protected function _get_tail($url, $detour)
	{
		$tail = '';
	
		if(substr($detour,-2,2) == '%%')
		{
			$detour = substr($detour,0,-2);
			$tail = str_replace($detour, '', $url);
		}
		
		return $tail;
	}
}
//END CLASS