<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Event extends BaseModel {
	
	protected $stream = 'events';
	
	protected $order_by = 'start';
	
	protected $sort = 'asc';
	
	protected $date_by = 'start';
	
	public function __construct()
	{
		$this->ci =& get_instance();
	}
	
	public function delete()
	{
		$this->ci->load->model('search/search_index_m');
		$this->ci->search_index_m->drop_index($this->namespace, 'event', $id);
		
		return parent::delete($id);
	}
	
}