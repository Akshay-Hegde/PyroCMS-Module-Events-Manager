<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class ModuleSetting extends BaseModel {
	
	protected $stream = 'settings';
	
	public function __construct()
	{
		$this->ci =& get_instance();
	}
	
	public function get($field)
	{		
		$settings = parent::getAll();
		
		$settings = $settings['entries'][0];
		
		if(is_array($settings[$field]))
		{
			return $settings[$field]['key'];
		}
		
		return $settings[$field];
	}
	
}