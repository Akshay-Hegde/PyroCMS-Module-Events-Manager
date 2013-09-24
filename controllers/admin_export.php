<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Events Manager module
 *
 *
 * @author 		Phil Martinez - Philsquare Dev Team
 * @website		http://philsquare.com
 * @package 	PyroCMS
 */

class Admin_export extends Admin_Controller
{

	/**
	 * The current active section
	 *
	 * @var string
	 */
	protected $section = 'export';

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

	public function index()
	{
		$data->events = array();
		
		$this->template->title('Export Events');
		
		$params = array(
			'stream' => 'events',
			'namespace' => 'events_manager',
			'order_by' => 'start',
			'sort' => 'asc',
			'date_by' => 'start',
			'show_past' => 'no',
			'paginate' => 'no'
		);
		
		if($data->filters->month = $this->input->post('month'))
		{
			$params['month'] = $data->filters->month + 1;
			$params['show_past'] = 'yes';
		}
		else
		{
			$params['month'] = date('n');
		}
		
		if($data->filters->year = $this->input->post('year'))
		{
			$params['year'] = $data->filters->year + 2013;
			$params['show_past'] = 'yes';
		}
		else
		{
			$params['year'] = date('Y');
		}
		
		$events = $this->streams->entries->get_entries($params);
		
		if($this->input->post('submit') == 'Export to CSV')
		{

		}
		else
		{
			$data->events = $events['entries'];

			$data->pagination = $events['pagination'];

			// Set partials and boom!
			$this->template
				->set_partial('filters', 'admin/export/filters')
				->set_partial('contents', 'admin/export/list')
				->build('admin/tpl/container', $data);
		}
	}
}