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

		// Load assets
		Asset::css('module::admin.css');
		Asset::js('module::admin.js');
		
		// Templates use this lib
		$this->load->library(array('table'));
		
		$this->load->model(array('modulesetting', 'category', 'color'));
		
		// Set calendar
		$this->table->set_template(array('table_open'  => '<table>'));

		$categories = $this->category->getAll();
		$this->template->set('categories', $categories['entries']);
    }
	
	public function index($year = null, $month = null, $day = null)
	{
		$month = $month ? $month : date('n');
		$year = $year ? $year : date('Y');
		$layout = $this->modulesetting->get('calendar_layout');
		
		if($day)
		{			
			$results = $this->event->getRange($year, $month, $day);
			
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
				->title('Events for ' . date('M j, Y', mktime(null, null, null, $month, $day, $year)))
				->set('events', $events)
				->build('front/list');
		}
		else
		{
			$results = $this->event->getRange($year, $month);

			$events = $results['entries'];

			$data = $this->_build($events, $year, $month);

			$this->template
				->title('Upcoming Events')
				->set_layout($layout)
				->build('front/calendar/view', $data);
		}
		
	}
	
	public function category($slug = '', $year = null, $month = null)
	{
		$month = $month ? $month : date('n');
		$year = $year ? $year : date('Y');
		$layout = $this->modulesetting->get('calendar_layout');
		
		// $category = $this->category->getBySlug($slug);
		
		$category = $this->category->where('slug', $slug)->first();
		
		if($category)
		{
			$this->template->title('Calendar Events listed as "' . $category['title'] . '"');
			
			$results = $this->event->getByCategoryIdAndRange($category['id'], $year, $month);

			$events = $results['entries'];
		}
		
		else
		{
			show_404();
		}
		
		$data = $this->_build($events, $year, $month, $slug);
		
		$this->template
			->set_layout($layout)
			->build('front/calendar/view', $data);
	}
	
	private function _build($events, $year, $month, $category = null)
	{
		$dayOption = $this->modulesetting->get('calendar_day_option');
		
		$event_list = array();
		
		// Assign events to keyed array ex. $var[4] = array(event1, event2, etc.)
		foreach($events as $event)
		{
			$event = (object) $event;
			
			$event_day = date('j', $event->start);
			
			// We need the color
			$colorId = $event->category_id['color_id'];
			
			$color = $this->color->get($colorId);

			$event_days[$event_day][] = array(
				'title' => $event->title,
				'start' => $event->start,
				'end' => $event->end,
				'slug' => $event->slug,
				'color_slug' => $color->slug,
				'hex' => $color->hex,
				'event' => (array) $event
			);
		}
		
		if(isset($event_days))
		{
			if($dayOption == 'list')
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
				{
					// echo '<pre>'; print_r($event_days[$day]); die();
					
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