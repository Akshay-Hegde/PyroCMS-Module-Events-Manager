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
		$this->categories = $categories['entries'];
		$this->template->set('categories', $this->categories);
    }

	public function index()
	{
		$this->template->title('Upcoming Events');
		
		$params = array(
			'stream' => 'events',
			'namespace' => 'events_manager',
			'limit' => 2, //Settings::get('records_per_page'),
			'order_by' => 'start',
			'sort' => 'asc',
			'date_by' => 'start',
			'show_past' => 'no',
			'paginate' => 'yes',
			'pag_base' => site_url('events_manager/events/page'),
			'pag_segment' => 4
		);
		
		$results = $this->streams->entries->get_entries($params);
		
		// Need colors
		// @todo DRY
		foreach($results['entries'] as $event)
		{
			$id = $event['category_id']['color_id'];
			
			$params = array(
				'stream' => 'category_colors',
				'namespace' => 'events_manager',
				'where' => "`id` = '{$id}'"
			);

			$color = $this->streams->entries->get_entries($params);
			
			$event['color_slug'] = $color['entries'][0]['color_slug'];
			
			$events[] = $event;
		}
		
		$this->template
			->set('pagination', $results['pagination'])
			->set('events', $events)
			->build('front/list');
	}
	
	public function category($slug = '')
	{
		// Exists?
		$params = array(
			'stream' => 'categories',
			'namespace' => 'events_manager',
			'where' => "`category_slug` = '{$slug}'"
		);

		$results = $this->streams->entries->get_entries($params);
		
		if($results['total'])
		{
			$category = $results['entries'][0];
			
			$params = array(
				'stream' => 'events',
				'namespace' => 'events_manager',
				'limit' => 2, //Settings::get('records_per_page'),
				'order_by' => 'start',
				'sort' => 'asc',
				'date_by' => 'start',
				'show_past' => 'no',
				'paginate' => 'yes',
				'pag_segment' => 5
			);

			$this->template->title('Upcoming Events listed as "' . $category['category'] . '"');
			$id = $category['id'];
			$params['where'] = "`category_id` = '{$id}'";

			$results = $this->streams->entries->get_entries($params);
		}
		
		// Need colors
		foreach($results['entries'] as $event)
		{
			$id = $event['category_id']['color_id'];
			
			$params = array(
				'stream' => 'category_colors',
				'namespace' => 'events_manager',
				'where' => "`id` = '{$id}'"
			);

			$color = $this->streams->entries->get_entries($params);
			
			$event['color_slug'] = $color['entries'][0]['color_slug'];
			
			$events[] = $event;
		}
		
		$this->template
			->set('pagination', $results['pagination'])
			->set('events', $events)
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
		
		$this->template
			->set('event', array($event))
			->build('front/view');
	}
	
	public function calendar($year = null, $month = null, $selected_category = 'all')
	{
		$event_list = array();
		
		$month = $month ? $month : date('n');
		$year = $year ? $year : date('Y');
		
		$this->template->title('Upcoming Events');
		
		$params = array(
			'stream' => 'events',
			'namespace' => 'events_manager',
			'order_by' => 'start',
			'date_by' => 'start',
			'month' => $month,
			'year' => $year
		);

		if($selected_category != 'all')
		{
			// @todo DRY
			foreach($this->categories as $category)
			{
				if($category['category_slug'] == $selected_category)
				{
					$this->template->title('Upcoming Events listed as "' . $category['category'] . '"');
					$id = $category['id'];
					$params['where'] = "`category_id` = '{$id}'";
				}
			}
		}
		
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
		
		$previous = anchor(site_url('events/calendar/'.$prev_year.'/'.$prev_month.'/'.$selected_category), '&larr;');
		$next     = anchor(site_url('events/calendar/'.$next_year.'/'.$next_month.'/'.$selected_category), '&rarr;');
		
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