<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Events Manager module
 *
 *
 * @author 		Phil Martinez - Philsquare Dev Team
 * @website		http://philsquare.com
 * @package 	PyroCMS
 */

class Em_events extends Public_Controller
{
    public function __construct()
    {
        parent::__construct();

		// Load lang
        $this->lang->load('events_manager');

		// Helpers
		$this->load->helper('events');

		// Load assets
		Asset::css('module::admin.css');
		Asset::js('module::admin.js');
		
		// Templates use this lib
		$this->load->library('table');
		
		// Set CP GUI table attr
		$this->table->set_template(array('table_open'  => '<table class="table-list" border="0" cellspacing="0">'));
    }

	public function events($year = null, $month = null, $day = null, $offset = 0)
	{
		$this->template->title('Upcoming Events');
		
		$params = array(
			'stream' => 'events',
			'namespace' => 'events_manager',
			'limit' => Settings::get('records_per_page'),
			'offset' => $offset,
			'order_by' => 'start',
			'sort' => 'asc',
			'date_by' => 'start',
			'show_past' => 'no',
			'paginate' => 'yes',
			'pag_segment' => 3
		);
		
		if($data->filters->month = $this->input->post('month'))
		{
			$params['month'] = $data->filters->month + 1;
		}
		
		if($data->filters->year = $this->input->post('year'))
		{
			$params['year'] = $data->filters->year + 2013;
		}
		
		$results = $this->streams->entries->get_entries($params);
		
		$this->template
			->set('events', $results['entries'])
			->build('front/list');
	}
	
	public function calendar()
	{
		
	}
}