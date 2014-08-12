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
		
		$this->load->library(array('streambase', 'event', 'modulesetting', 'registration'));
		
		// Templates use this lib
		$this->load->library('table');
		
		// Set CP GUI table attr
		$this->table->set_template(array('table_open'  => '<table class="table-list" border="0" cellspacing="0">'));
    }

	public function index($offset = 0)
	{
		$filters['month'] = $this->input->post('month');
		$filters['year'] = $this->input->post('year');
		
		if($filters['month'])
		{
			$events = $this->event->getRange($filters['month'], $filters['year']);
		}
		
		else
		{
			$events = $this->event->getFuture();
		}
		
		$pagination = $events['pagination'];
		
		foreach($events['entries'] as $index => $event)
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
			
			$events['entries'][$index] = $event;
		}

		$this->template
			->title('Upcoming Events')
			->set_partial('filters', 'admin/events/filters')
			->set_partial('contents', 'admin/events/list')
			->build('admin/tpl/container', compact('filters', 'events', 'pagination'));
	}
	
	public function form($id = null)
	{
		if($id && ! $this->_canEdit($id))
		{
			$this->session->set_flashdata('error', 'You do not have permission to edit this event');
			
			redirect('admin/events_manager/index');
		}
		
		$extra = array(
			'return' => 'admin/events_manager',
			'title' => $id ? 'Edit Event' : 'Add Event'
		);
		
		$skips = array();
		
		if($this->modulesetting->get('allow_registrations') == 'no')
		{
			$skips = array('registration', 'limit');
		}
		
		$this->streams->cp->entry_form(
			'events',
			'philsquare_events_manager',
			$id ? 'edit' : 'new',
			$id,
			true,
			$extra,
			$skips
		);
	}
	
	public function delete($id = 0)
	{
		if($id && ! $this->_canEdit($id))
		{
			$this->session->set_flashdata('error', 'You do not have permission to delete this event');
			
			redirect('admin/events_manager/index');
		}
		
		if($this->event->delete($id))
		{
			$this->session->set_flashdata('error', 'Event was deleted.');
		}
		
		else
		{
			$this->session->set_flashdata('error', 'Unable to delete event.');
		}
		
		redirect('admin/events_manager');
	}
	
	public function registrations($event_id)
	{
		$event = $this->event->get($event_id);
		$registrants = $this->registration->getRegistrants($event_id);

		$this->template
			->title('Registrants for ' . $event->title)
			->set_partial('contents', 'admin/registrants/list')
			->build('admin/tpl/container', compact('event', 'registrants'));
	}
	
	public function add_registrant($eventId)
	{		
		$extra = array(
			'return' => 'admin/events_manager/registrations/' . $eventId,
			'title' => 'Add Registrant'
		);
		
		$skips = array();
		
		$this->streams->cp->entry_form(
			'registrations',
			'philsquare_events_manager',
			'new',
			null,
			true,
			$extra,
			$skips,
			false,
			array('event_id'),
			array('event_id' => $eventId)
		);
	}
	
	public function delete_registrant($registrationId, $eventId)
	{
		$results = $this->registration->delete($registrationId);
		
		if($results)
		{
			// Success
			$this->session->set_flashdata('success', 'Registrant deleted');
		}
		else
		{
			// Failure adding answers
			$this->session->set_flashdata('error', 'There was an issue.');
		}
		
		redirect("admin/events_manager/registrations/$eventId");
	}
	
	private function _canEdit($eventId)
	{
		if(group_has_role('philsquare_events_manager', 'edit_all')) return true;
		
		$event = $this->event->get($id);
		$userId = $this->current_user->id;
		
		if($event->created_by_user_id == $userId)
		{
			return true;
		}
		
		return false;
	}
}