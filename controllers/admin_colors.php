<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Events Manager module
 *
 *
 * @author 		Phil Martinez - Philsquare Dev Team
 * @website		http://philsquare.com
 * @package 	PyroCMS
 */

class Admin_colors extends Admin_Controller
{

	/**
	 * The current active section
	 *
	 * @var string
	 */
	protected $section = 'colors';

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

	public function index($offset = 0)
	{
		$extra = array(
			'title' => 'Colors',
			
			'buttons' => array(
				array(
					'label' => 'Edit',
					'url' => 'admin/events_manager/colors/form/-entry_id-'
				),
				array(
					'label' => 'Delete',
					'url' => 'admin/events_manager/colors/delete/-entry_id-',
					'confirm' => true
				)
			),
			
			'columns' => array('color', 'hex')
		);
		
		$this->streams->cp->entries_table('category_colors', 'events_manager', 20, 'admin/events_manager/colors', true, $extra);
	}
	
	public function form($id = null)
	{
		$extra = array(
			'return' => 'admin/events_manager/colors',
			'title' => $id ? 'Edit Color' : 'Add Color'
		);
		
		$this->streams->cp->entry_form('category_colors', 'events_manager', $id ? 'edit' : 'new', $id, true, $extra);
	}
	
	public function delete($id = 0)
	{
		$this->streams->entries->delete_entry($id, 'colors', 'events_manager');
		$this->session->set_flashdata('error', 'Color was deleted.');
		redirect('admin/events_manager/colors');
	}
}