<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Events_manager_event_model extends Events_manager_base_model {
	
	protected $stream = 'events';
	
	protected $order_by = 'start';
	
	protected $sort = 'asc';
	
	protected $date_by = 'start';
	
	public function delete($id)
	{
		$this->ci->load->model('search/search_index_m');
		$this->ci->search_index_m->drop_index($this->namespace, 'event', $id);
		
		return parent::delete($id);
	}
	
}