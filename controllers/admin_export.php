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

		role_or_die('philsquare_events_manager', 'export');
    }

	public function index()
	{		
		// @todo add validation
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
			$from = $this->input->post('from');
			$to = $this->input->post('to');
			
			$this->load->dbutil();
			$this->load->helper('download');
			
			$sql = "SELECT `title`, `start`, `end`, `details`, `location` FROM " . SITE_REF . "_philsquare_events_manager_events WHERE start between '" . $from . "' AND '" . $to . "'";

			$query = $this->db->query($sql);
			$data = $this->dbutil->csv_from_result($query);
			$name = 'calendar_events_' . $from . '_to_' . $to . '.csv';
			$results = $query->result_array();
			
			if(empty($results))
			{
				$this->session->set_flashdata('error', 'No events are available in that range.');
				
				redirect('admin/events_manager/export');
			}

			force_download($name, $data);
		}
		else
		{

			// Set partials and boom!
			$this->template
				->title('Export Events to a CSV file')
				->set_partial('contents', 'admin/export/filters')
				->build('admin/tpl/container', $data);
		}
	}
}