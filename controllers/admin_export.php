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
		$this->template->title('Export Events');
		
		if($_POST)
		{
			$from = $this->input->post('from');
			$to = $this->input->post('to');
		}
		else
		{
			$from = date('Y-m-d');
			$to = date('Y-m-d', strtotime("+1 month"));
		}
		
		$data->from = $from;
		$data->to = $to;
		
		if($this->input->post('submit') == 'Export to CSV')
		{

		}
		else
		{

			// Set partials and boom!
			$this->template
				->set_partial('contents', 'admin/export/filters')
				->build('admin/tpl/container', $data);
		}
	}
}