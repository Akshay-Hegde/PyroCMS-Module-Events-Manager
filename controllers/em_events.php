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
		$events = array();
		
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
		$events = array();
		
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
}