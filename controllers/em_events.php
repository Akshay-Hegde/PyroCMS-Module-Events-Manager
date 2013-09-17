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
		
		// Set calendar
		$this->table->set_template(array('table_open'  => '<table>'));
		
		// We always need the category list as a keyed array
		$params = array(
			'stream' => 'categories',
			'namespace' => 'events_manager',
		);

		$categories = $this->streams->entries->get_entries($params);
		
		foreach($categories['entries'] as $category)
		{
			echo '<pre>'; print_r($category);
			$this->categories[$category['slug']] = $category['category'];
		}
    }

	public function events($category = null, $offset = 0)
	{
		if( ! $category) redirect('events/all');
		
		$this->template->title('Upcoming Events');
		
		$params = array(
			'stream' => 'events',
			'namespace' => 'events_manager',
			'limit' => 5, //Settings::get('records_per_page'),
			'offset' => $offset,
			'order_by' => 'start',
			'sort' => 'asc',
			'date_by' => 'start',
			'show_past' => 'no',
			'paginate' => 'yes',
			'pag_segment' => 3
		);
		
		if($category != 'all')
		{
			if(! array_key_exists($category, $this->categories)) redirect('events/all');
			
			$this->template->title('Upcoming Events listed as "' . $category->category . '"');
			$params['where'] = "`category` = '{$category}'";
			
		}
		
		$results = $this->streams->entries->get_entries($params);
		
		$this->template
			->set('pagination', $results['pagination'])
			->set('events', $results['entries'])
			->build('front/list');
	}
	
	public function event($year, $month, $day, $slug)
	{
		// @todo Should we hope they don't have identical slugs on the same day?

		$this->template->title('Event');
		
		$params = array(
			'stream'    => 'events',
			'namespace' => 'events_manager',
			'limit'     => 1,
			'date_by'   => 'start',
			'where'     => "`slug` = '{$slug}'",
			'year'      => $year,
			'month'     => $month,
			'day'       => $day
		);
		
		$results = $this->streams->entries->get_entries($params);

		list($event) = $results['entries'];
		
		// echo '<pre>'; print_r($event); die();

		$this->template
			->set('event', array($event))
			->build('front/view');
	}
	
	public function calendar($year = null, $month = null)
	{
		$event_list = array();
		
		$this->template->title('Upcoming Events');
		
		$params = array(
			'stream' => 'events',
			'namespace' => 'events_manager',
			'order_by' => 'start',
			'date_by' => 'start',
			'month' => $month ? $month : date('n'),
			'year' => $year ? $year : date('Y')
		);
		
		$results = $this->streams->entries->get_entries($params);
		
		$events = $results['entries'];
		
		// Assign events to keyed array ex. $var[4] = array(event1, event2, etc.)
		foreach($events as $event)
		{
			$event = (object) $event;
			
			$event_day = date('j', $event->start);

			$event_days[$event_day][] = array(
				'title' => $event->title,
				'url' => site_url('event' . date('/Y/m/d/', $event->start) . $event->slug)
			);
		}
		
		// Consolidate events into uls and assign to a day
		if(isset($event_days))
		{
			foreach($event_days as $day => $event)
			{
				$cell = $this->template
					->set_layout(null)
					->set('events', $event_days[$day])
					->set('day', $day)
					->build('front/calendar/cell_format', '', true);
					
				$event_list[$day] = $cell;
			}
		}

		$prev_month = $month == 1 ? 12 : $month - 1;
		$prev_year = $prev_month == 12 ? $year - 1 : $year;
		
		$next_month = $month == 12 ? 1 : $month + 1;
		$next_year = $next_month == 1 ? $year + 1 : $year;
		
		$previous = anchor(site_url('events/calendar/'.$prev_year.'/'.$prev_month), '&larr;');
		$next     = anchor(site_url('events/calendar/'.$next_year.'/'.$next_month), '&rarr;');
		
		$prefs['template'] = '

		   {table_open}<table id="calendar">{/table_open}

		   {heading_row_start}<tr id="calendar-heading">{/heading_row_start}

		   {heading_title_cell}<th colspan="{colspan}">'.$previous.' '.anchor(site_url('events/calendar/'.$year.'/'.$month), '{heading}').' '.$next.'</th>{/heading_title_cell}

		   {heading_row_end}</tr>{/heading_row_end}

		   {week_row_start}<tr id="weekdays">{/week_row_start}
		   {week_day_cell}<td>{week_day}</td>{/week_day_cell}
		   {week_row_end}</tr>{/week_row_end}

		   {cal_row_start}<tr>{/cal_row_start}
		   {cal_cell_start}<td>{/cal_cell_start}

		   {cal_cell_content}{content}{/cal_cell_content}
		   {cal_cell_content_today}{content}{/cal_cell_content_today}

		   {cal_cell_no_content}<div class="wrap no-event"><span class="day-num">{day}</span></div>{/cal_cell_no_content}
		   {cal_cell_no_content_today}<div class="wrap no-event"><span class="day-num today">{day}</span></div>{/cal_cell_no_content_today}

		   {cal_cell_blank}&nbsp;{/cal_cell_blank}

		   {cal_cell_end}</td>{/cal_cell_end}
		   {cal_row_end}</tr>{/cal_row_end}

		   {table_close}</table>{/table_close}
		';
		
		$prefs['day_type']     = 'long';

		$this->load->library('calendar', $prefs);

		$data->month = $month;
		$data->year = $year;
		$data->event_list = $event_list;

		$this->template
			->set_layout('default.html')
			->build('front/calendar/view', $data);
	}
}