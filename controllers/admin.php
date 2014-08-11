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
        $this->lang->load('philsquare_events_manager');

		// Load assets
		Asset::css('module::admin.css');
		Asset::js('module::admin.js');
		
		$this->load->library(array('streambase', 'event'));
		
		// Templates use this lib
		$this->load->library('table');
		
		// Set CP GUI table attr
		$this->table->set_template(array('table_open'  => '<table class="table-list" border="0" cellspacing="0">'));
    }

	public function index($offset = 0)
	{		
		$this->template->title('Upcoming Events');
		
		if($data->filters->month = ($this->input->post('submit') == 'Filter'))
		{
			$data->filters->month = $this->input->post('month');
			$params['month'] = $data->filters->month + 1;
			$params['show_past'] = 'yes';
		}
		
		if($data->filters->year = ($this->input->post('submit') == 'Filter'))
		{
			$data->filters->year = $this->input->post('year');
			$params['year'] = $data->filters->year + 2013;
			$params['show_past'] = 'yes';
		}
		
		$events = $this->event->getFuture();
		
		$data->pagination = $events['pagination'];
		
		foreach($events['entries'] as $event)
		{
			if($event['registration']['key'] == 'yes')
			{
				$params = array(
					'stream' => 'registrations',
					'namespace' => 'philsquare_events_manager',
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
		if($id && !group_has_role('philsquare_events_manager', 'edit_all'))
		{
			$event = $this->streams->entries->get_entry($id, 'events', 'philsquare_events_manager', false);
			$user_id = $this->current_user->id;
			
			if($event->created_by_user_id != $user_id)
			{
				$this->session->set_flashdata('error', 'You do not have permission to edit this event');
				
				redirect('admin/events_manager/index');
			}
		}
		
		$extra = array(
			'return' => 'admin/events_manager',
			'title' => $id ? 'Edit Event' : 'Add Event'
		);
		
		$skips = array();
		
		if(Settings::get('em_allow_registrations') == 'no')
		{
			$skips = array('registration', 'limit');
		}

		$this->streams->cp->entry_form('events', 'philsquare_events_manager', $id ? 'edit' : 'new', $id, true, $extra, $skips);
	}
	
	public function delete($id = 0)
	{
		if(!group_has_role('philsquare_events_manager', 'edit_all'))
		{
			$event = $this->streams->entries->get_entry($id, 'events', 'philsquare_events_manager', false);
			$user_id = $this->current_user->id;
			
			if($event->created_by_user_id != $user_id)
			{
				$this->session->set_flashdata('error', 'You do not have permission to delete this event');
				
				redirect('admin/events_manager/index');
			}
		}
		
		$this->load->model('search/search_index_m');
		
		$this->streams->entries->delete_entry($id, 'events', 'philsquare_events_manager');
		$this->search_index_m->drop_index('philsquare_events_manager', 'event', $id);
		$this->session->set_flashdata('error', 'Event was deleted.');
		redirect('admin/events_manager');
	}
	
	public function registrations($event_id)
	{
		$data->event = $this->streams->entries->get_entry($event_id, 'events', 'philsquare_events_manager', false);
		
		$params = array(
			'stream' => 'registrations',
			'namespace' => 'philsquare_events_manager',
			'where' => "`event_id` = '{$event_id}'"
		);
		
		$data->registrants = $this->streams->entries->get_entries($params);
		
		$this->template
			->title('Registrants for ' . $data->event->title)
			->set_partial('contents', 'admin/registrants/list')
			->build('admin/tpl/container', $data);
	}
	
	public function add_registrant($event_id)
	{
		$validation_rules = array(
			array(
				'field' => 'name',
				'label' => 'Name',
				'rules'	=> 'required|trim|max_length[100]'
			),
			array(
				'field' => 'email',
				'label' => 'Email',
				'rules'	=> 'required|valid_email|max_length[255]'
			)
		);

		$this->form_validation->set_rules($validation_rules);
		
		if($this->form_validation->run())
		{
			$insert = array(
				'name' => $this->input->post('name'),
				'email' => $this->input->post('email'),
				'event_id' => $event_id
			);
			
			$result = $this->streams->entries->insert_entry($insert, 'registrations', 'philsquare_events_manager');
			
			if($result)
			{
				// Success
				$this->session->set_flashdata('success', 'Added registrant successfully');
			}
			else
			{
				// Failure adding answers
				$this->session->set_flashdata('error', 'Unable to add registrant');
			}
			
			redirect("admin/events_manager/registrations/$event_id");
		}
		else
		{
			$event = $this->streams->entries->get_entry($event_id, 'events', 'philsquare_events_manager');

			$this->template
				->title('Add Registrant to ' . $event->title)
				->set_partial('contents', 'admin/registrants/add')
				->build('admin/tpl/container');
		}
	}
	
	public function delete_registrant($registration_id)
	{
		$registration = $this->streams->entries->get_entry($registration_id, 'registrations', 'philsquare_events_manager');
		$result = $this->streams->entries->delete_entry($registration_id, 'registrations', 'philsquare_events_manager');
		
		if($result)
		{
			// Success
			$this->session->set_flashdata('success', 'Registrant deleted');
		}
		else
		{
			// Failure adding answers
			$this->session->set_flashdata('error', 'There was an issue.');
		}
		
		redirect("admin/events_manager/registrations/$registration->event_id");
	}
}