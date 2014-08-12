<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class ModuleSetting extends StreamBase {
	
	protected $stream = 'settings';
	
	public function __construct()
	{
		$this->ci =& get_instance();
	}
	
	public function get($field)
	{		
		$settings = parent::getAll();
		
		$settings = $settings['entries'][0];
		
		return $settings[$field];
	}
	
}