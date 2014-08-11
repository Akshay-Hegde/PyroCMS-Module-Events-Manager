<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Events Manager module
 *
 *
 * @author 		Phil Martinez - Philsquare Dev Team
 * @website		http://philsquare.com
 * @package 	PyroCMS
 */

class Api extends Public_Controller
{
	public function __construct()
    {
        parent::__construct();

		// Only AJAX gets through!
	   	if( ! $this->input->is_ajax_request() ) die('Invalid request.');
    }
	
	public function get()
	{
		$params = array(
			'stream' => 'events',
			'namespace' => 'philsquare_events_manager',
			'limit' => $this->input->post('limit', true),
			'offset' => $this->input->post('offset', true),
			'order_by' => 'start',
			'sort' => 'asc',
			'date_by' => 'start',
			'show_past' => 'no'
		);
		
		$events = $this->streams->entries->get_entries($params);
		
		echo json_encode($events);
	}
}