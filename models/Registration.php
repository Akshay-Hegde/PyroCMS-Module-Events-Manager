<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Registration extends BaseModel {
	
	protected $stream = 'registrations';
	
	public function __construct()
	{
		$this->ci =& get_instance();
	}
	
}