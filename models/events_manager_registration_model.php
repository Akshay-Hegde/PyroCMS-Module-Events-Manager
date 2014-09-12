<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Events_manager_registration_model extends Events_manager_base_model {
	
	protected $stream = 'registrations';
	
	public function __construct()
	{
		$this->ci =& get_instance();
	}
	
}