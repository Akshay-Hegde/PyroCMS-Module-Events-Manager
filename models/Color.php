<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Color extends BaseModel {
	
	protected $stream = 'colors';
	
	public function __construct()
	{
		$this->ci =& get_instance();
	}
	
}