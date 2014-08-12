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
	private $categories;
	
    public function __construct()
    {
        parent::__construct();

		// Load lang
        $this->lang->load('philsquare_events_manager');

		// Helpers
		$this->load->helper('events');

		// Load assets
		Asset::css('module::admin.css');
		Asset::js('module::admin.js');
		
		// Templates use this lib
		$this->load->library('table');
		
		$this->load->model(array('event', 'modulesetting', 'category', 'color', 'registration'));
		
		// Set calendar
		$this->table->set_template(array('table_open'  => '<table>'));
		
		$categories = $this->category->getAll();
		$this->template->set('categories', $categories['entries']);
    }

	public function index()
	{
		$events = array();
		$layout = $this->modulesetting->get('list_layout');
		
		$results = $this->event->paginate(3)->getFuture();
		
		// Need colors
		// @todo DRY
		foreach($results['entries'] as $event)
		{
			$id = $event['category_id']['color_id'];
			
			$color = $this->color->get($id);
			
			$event['color_slug'] = $color->slug;
			$event['hex'] = $color->hex;
			
			$events[] = $event;
		}
		
		$this->template
			->title('Upcoming Events')
			->set('pagination', $results['pagination'])
			->set('events', $events)
			->set_layout($layout)
			->build('front/list');
	}
	
	public function category($slug = '')
	{
		$events = array();
		
		$category = $this->category->where('slug', $slug)->first();
		
		if($category)
		{
			$results = $this->event
				->where('category_id', $category['id'])
				->paginate(5)
				->getAll();
		}
		
		else
		{
			show_404();
		}
		
		// Need colors
		foreach($results['entries'] as $event)
		{
			$id = $event['category_id']['color_id'];
			
			$color = $this->color->get($id);
			
			$event['color_slug'] = $color->slug;
			$event['hex'] = $color->hex;
			
			$events[] = $event;
		}
		
		$this->template
			->title('Upcoming Events listed as "' . $category['title'] . '"')
			->set('pagination', $results['pagination'])
			->set('events', $events)
			->build('front/list');
	}
	
	public function event($year, $month, $day, $slug)
	{
		// @todo Should we hope they don't have identical slugs on the same day?
		
		$event = $this->event
			->date($year, $month, $day)
			->where('slug', $slug)
			->first();
		
		if($event['registration']['key'] == 'yes')
		{
			$registrations = $this->registration->where('event_id', $event['id'])->getAll();
			
			if($registrations['total'] < $event['limit'])
			{				
				if($this->form_validation->run('add-registrant'))
				{
					$name = $this->input->post('name');
					$email = $this->input->post('email');
					$is_registered = false;
					
					foreach($registrations['entries'] as $registration)
					{
						if($registration['email'] == $email) $is_registered = true;
					}
					
					if($is_registered)
					{
						$this->template
							->set_partial('registration', 'front/already_registered')
							->set('event', array($event))
							->build('front/view');
					}
					else
					{
						$entry = array(
							'event_id' => $event['id'],
							'name' => $name,
							'email' => $email
						);
						
						$insert = $this->registration->insert($entry);
						
						if($insert)
						{
							$this->template->set_partial('registration', 'front/registration_success')
							->set('event', array($event))
							->build('front/view');
						}
					}

				}
				else
				{
					$this->template
						->set_partial('registration', 'front/registration_form')
						->set('form_open', form_open())
						->set('form_close', form_close())
						->set('name', $this->input->post('name'))
						->set('email', $this->input->post('email'))
						->set('validation_errors', validation_errors())
						->set('event', array($event))
						->build('front/view');
				}
			}
			else
			{
				$this->template
					->set_partial('registration', 'front/registration_full')
					->set('event', array($event))
					->build('front/view');
			}
			
		}
		else
		{
			$this->template
				->title('Event')
				->set('event', array($event))
				->build('front/view');
		}
		
		

	}
}