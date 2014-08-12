<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Category extends StreamBase {
	
	protected $stream = 'categories';
	
	public function __construct()
	{
		$this->ci =& get_instance();
	}
	
	public function getBySlug($slug)
	{
		$this->where = "`slug` = '{$slug}'";
		
		$query = parent::getAll();
		
		if($query['total'])
		{
			return parent::get($query['entries'][0]['id']);
		}
		
		return false;
	}
	
}