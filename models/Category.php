<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Category extends StreamBase {
	
	protected $stream = 'categories';
	
	public function __construct()
	{
		$this->ci =& get_instance();
	}	
}