<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Events Manager module
 *
 *
 * @author 		Phil Martinez - Philsquare Dev Team
 * @website		http://philsquare.com
 * @package 	PyroCMS
 */

class Admin extends Admin_Controller
{

	/**
	 * The current active section
	 *
	 * @var string
	 */
	protected $section = 'events';

    public function __construct()
    {
        parent::__construct();

		// Load lang
        $this->lang->load('events_manager');

		// Load assets
		Asset::css('module::admin.css');
		Asset::js('module::admin.js');
		
		// Templates use this lib
		$this->load->library('table');
		
		// Set CP GUI table attr
		$this->table->set_template(array('table_open'  => '<table class="table-list" border="0" cellspacing="0">'));
    }

	public function index($offset = 0)
	{
		if($this->uri->segment(3) != 'index') redirect(current_url() . '/index');
		
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
			'pag_segment' => 4
		);
		
		if($data->filters->month = $this->input->post('month'))
		{
			$params['month'] = $data->filters->month + 1;
		}
		
		if($data->filters->year = $this->input->post('year'))
		{
			$params['year'] = $data->filters->year + 2013;
		}
		
		$events = $this->streams->entries->get_entries($params);
		
		$data->pagination = $events['pagination'];
		
		foreach($events['entries'] as $event)
		{
			if($event['registration']['key'] == 'yes')
			{
				$params = array(
					'stream' => 'registrations',
					'namespace' => 'events_manager',
					'where' => '`event_id` = ' . $event['id']
				);
				
				$registrations = $this->streams->entries->get_entries($params);
				
				$event['registration_count'] = $registrations['total'];
			}
			
			$data->events[] = $event;
		}
		
		// Set partials and boom!
		$this->template
			->set_partial('filters', 'admin/events/filters')
			->set_partial('contents', 'admin/events/list')
			->build('admin/tpl/container', $data);
	}
	
	public function form($id = null)
	{
		$extra = array(
			'return' => 'admin/events_manager',
			'title' => $id ? 'Edit Event' : 'Add Event'
		);
		
		$this->streams->cp->entry_form('events', 'events_manager', $id ? 'edit' : 'new', $id, true, $extra);
	}
	
	public function delete($id = 0)
	{
		$this->load->model('search/search_index_m');
		
		$this->streams->entries->delete_entry($id, 'events', 'events_manager');
		$this->search_index_m->drop_index('events_manager', 'event', $id);
		$this->session->set_flashdata('error', 'Event was deleted.');
		redirect('admin/events_manager');
	}
	
	public function registrations($event_id)
	{
		$data->event = $this->streams->entries->get_entry($event_id, 'events', 'events_manager', false);
		
		$params = array(
			'stream' => 'registrations',
			'namespace' => 'events_manager',
			'where' => "`event_id` = '{$event_id}'"
		);
		
		$data->registrants = $this->streams->entries->get_entries($params);
		
		$this->template
			->set_partial('contents', 'admin/registrants/list')
			->build('admin/tpl/container', $data);
	}
	
	public function add_registrant()
	{
		
	}
	
	public function delete_registration($event_id, $user_id)
	{
		
	}
}