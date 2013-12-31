<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Events Manager module
 *
 *
 * @author 		Phil Martinez - Philsquare Dev Team
 * @website		http://philsquare.com
 * @package 	PyroCMS
 */

class Em_calendar extends Public_Controller
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
	
	public function index($year = null, $month = null, $day = null)
	{
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
		
		if($day)
		{
			// @todo format setting
			$this->template->title('Events for ' . date('M j, Y', mktime(null, null, null, $month, $day, $year)));
			
			$params['day'] = $day;
			
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
				->set('events', $events)
				->build('front/list');
		}
		else
		{
			$results = $this->streams->entries->get_entries($params);

			$events = $results['entries'];

			$data = $this->_build($events, $year, $month);

			$this->template
				->set_layout(Settings::get('em_calendar_layout'))
				->build('front/calendar/view', $data);
		}
		
	}
	
	public function category($slug = '', $year = null, $month = null)
	{
		$month = $month ? $month : date('n');
		$year = $year ? $year : date('Y');
		
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
			
			$this->template->title('Calendar Events listed as "' . $category['category'] . '"');
			$id = $category['id'];
			
			$params = array(
				'stream' => 'events',
				'namespace' => 'events_manager',
				'order_by' => 'start',
				'date_by' => 'start',
				'month' => $month,
				'year' => $year,
				'where' => "`category_id` = '{$id}'"
			);

			$results = $this->streams->entries->get_entries($params);

			$events = $results['entries'];
			
			$results = $this->streams->entries->get_entries($params);
		}
		
		$data = $this->_build($events, $year, $month, $slug);
		
		$this->template
			->set_layout(Settings::get('em_calendar_layout'))
			->build('front/calendar/view', $data);
	}
	
	private function _build($events, $year, $month, $category = null)
	{
		$event_list = array();
		
		// Assign events to keyed array ex. $var[4] = array(event1, event2, etc.)
		foreach($events as $event)
		{
			$event = (object) $event;
			
			$event_day = date('j', $event->start);
			
			// We need the color
			$color_id = $event->category_id['color_id'];
			
			$params = array(
				'stream' => 'category_colors',
				'namespace' => 'events_manager',
				'where' => "`id` = '{$color_id}'"
			);

			$results = $this->streams->entries->get_entries($params);
			$color = $results['entries'][0];

			$event_days[$event_day][] = array(
				'title' => $event->title,
				'start' => $event->start,
				'end' => $event->end,
				'slug' => $event->slug,
				'color_slug' => $color['color_slug']
			);
		}
		
		if(isset($event_days))
		{
			if(Settings::get('em_calendar_day_option') == 'list')
			{
				foreach($event_days as $day => $event)
				{
					$cell = $this->template
						->set_layout(null)
						->set('events', $event_days[$day])
						->set('day', $day)
						->build('front/calendar/cell_format_list', '', true);

					$event_list[$day] = $cell;
				}
			}
			else
			{
				foreach($event_days as $day => $event)
				{ // print_r($event_days[$day]); die();
					$cell = $this->template
						->set_layout(null)
						->set('events', array($event_days[$day][0]))
						->set('day', $day)
						->build('front/calendar/cell_format_link', '', true);

					$event_list[$day] = $cell;
				}
			}
		}

		$prev_month = $month == 1 ? 12 : $month - 1;
		$prev_year = $prev_month == 12 ? $year - 1 : $year;
		
		$next_month = $month == 12 ? 1 : $month + 1;
		$next_year = $next_month == 1 ? $year + 1 : $year;
		
		if($category)
		{
			$previous = anchor(site_url('events_manager/calendar/category/'.$category.'/'.$prev_year.'/'.$prev_month), '&larr;');
			$next     = anchor(site_url('events_manager/calendar/category/'.$category.'/'.$next_year.'/'.$next_month), '&rarr;');
		}
		else
		{
			$previous = anchor(site_url('events_manager/calendar/'.$prev_year.'/'.$prev_month), '&larr;');
			$next     = anchor(site_url('events_manager/calendar/'.$next_year.'/'.$next_month), '&rarr;');
		}
		
		$prefs['template'] = '

		   {table_open}<table id="calendar">{/table_open}

		   {heading_row_start}<tr id="calendar-heading">{/heading_row_start}

		   {heading_title_cell}<th colspan="{colspan}">'.$previous.' '.anchor(site_url('events_manager/calendar/'.$year.'/'.$month), '{heading}').' '.$next.'</th>{/heading_title_cell}

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
		
		return $data;
	}
}