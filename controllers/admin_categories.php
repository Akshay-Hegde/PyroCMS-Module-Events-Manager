<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Events Manager module
 *
 *
 * @author 		Phil Martinez - Philsquare Dev Team
 * @website		http://philsquare.com
 * @package 	PyroCMS
 */

class Admin_categories extends Admin_Controller
{

	/**
	 * The current active section
	 *
	 * @var string
	 */
	protected $section = 'categories';

    public function __construct()
    {
        parent::__construct();

		role_or_die('philsquare_events_manager', 'categories');

		// Load lang
        $this->lang->load('philsquare_events_manager');

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
			'title' => 'Categories',
			
			'buttons' => array(
				array(
					'label' => 'Edit',
					'url' => 'admin/events_manager/categories/form/-entry_id-'
				),
				array(
					'label' => 'Delete',
					'url' => 'admin/events_manager/categories/delete/-entry_id-',
					'confirm' => true
				)
			),
			
			'columns' => array('category', 'color_id')
		);
		
		$this->streams->cp->entries_table('categories', 'philsquare_events_manager', 20, 'admin/events_manager/categories/index', true, $extra);
	}
	
	public function form($id = null)
	{
		$extra = array(
			'return' => 'admin/events_manager/categories',
			'title' => $id ? 'Edit Category' : 'Add Category'
		);
		
		$this->streams->cp->entry_form('categories', 'philsquare_events_manager', $id ? 'edit' : 'new', $id, true, $extra);
	}
	
	public function delete($id = 0)
	{
		$this->streams->entries->delete_entry($id, 'categories', 'philsquare_events_manager');
		$this->session->set_flashdata('error', 'Category was deleted.');
		redirect('admin/events_manager/categories');
	}
}