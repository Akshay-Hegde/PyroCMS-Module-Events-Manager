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
		
		$this->load->model(array('modulesetting', 'registration'));
    }

	public function index($offset = 0)
	{
		$filters['month'] = $this->input->post('month');
		$filters['year'] = $this->input->post('year');
		
		if($filters['month'])
		{
			$events = $this->event->date($filters['year'], $filters['month'])->getAll();
		}
		
		else
		{
			$events = $this->event->upcoming()->paginate(2)->getAll();
		}
		
		$pagination = $events['pagination'];
		
		foreach($events['entries'] as $index => $event)
		{
			if($event['registration']['key'] == 'yes')
			{
				$registrations = $this->registration->where('event_id', $event['id'])->getAll();
				
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
		
		$fields = $this->streams->streams->get_assignments('events', 'philsquare_events_manager');
		
		$regFields = array('registration', 'limit');
		
		foreach($fields as $field)
		{
			if( ! in_array($field->field_slug, $regFields)) $genFields[] = $field->field_slug;
		}
		
		$extra = array(
			'return' => 'admin/events_manager',
			'title' => $id ? 'Edit Event' : 'Add Event'
		);
		
		$skips = array();
		
		if($this->modulesetting->get('allow_registrations') == 'no')
		{
			$skips = array('registration', 'limit');
			$tabs = false;
		}
		
		else
		{
			$tabs = array(
			    array(
			        'title'     => "General Information",
			        'id'        => 'general-tab',
			        'fields'    => $genFields
			    ),
			    array(
			        'title'     => "Registration",
			        'id'        => 'additional-tab',
			        'fields'    => $regFields
			    )
			);
		}
		
		$this->streams->cp->entry_form(
			'events',
			'philsquare_events_manager',
			$id ? 'edit' : 'new',
			$id,
			true,
			$extra,
			$skips,
			$tabs
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
		$registrants = $this->registration->where('event_id', $event_id)->getAll();

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