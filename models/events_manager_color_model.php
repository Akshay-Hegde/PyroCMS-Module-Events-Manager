<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Events_manager_color_model extends Events_manager_base_model {
	
	protected $stream = 'colors';
	
	public function __construct()
	{
		$this->ci =& get_instance();
	}
	
}