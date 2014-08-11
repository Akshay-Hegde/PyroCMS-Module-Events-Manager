<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Event extends StreamBase {
	
	protected $stream = 'events';
	
	protected $order_by = 'start';
	
	protected $sort = 'asc';
	
	protected $date_by = 'start';
	
	public function __construct()
	{
		$this->ci =& get_instance();
	}
	
	public function getFuture()
	{		
		$this->show_past = 'no';

		return parent::getAll();
	}
	
}