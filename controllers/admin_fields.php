<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Events Manager module
 *
 *
 * @author 		Phil Martinez - Philsquare Dev Team
 * @website		http://philsquare.com
 * @package 	PyroCMS
 */

class Admin_fields extends Admin_Controller
{

	/**
	 * The current active section
	 *
	 * @var string
	 */
	protected $section = 'custom_fields';

    public function __construct()
    {
        parent::__construct();

		role_or_die('philsquare_events_manager', 'custom_fields');

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
			'title' => 'Event Custom Fields',
			
			'buttons' => array(
				array(
			        'label'     => lang('global:edit'),
			        'url'       => 'admin/events_manager/fields/form/-assign_id-'
			    ),
			    array(
			        'label'     => lang('global:delete'),
			        'url'       => 'admin/events_manager/fields/delete/-assign_id-',
			        'confirm'   => true,
			    )
			)
		);
		
		$exclude = array(
			'title',
			'slug',
			'start',
			'end',
			'introduction',
			'details',
			'registration',
			'limit',
			'category_id'
		);

		$this->streams->cp->assignments_table('events', 'philsquare_events_manager', 15, 'admin/events_manager/fields/index', true, $extra, $exclude);
	}
	
	public function form($assign_id = null)
	{
		$this->streams->cp->field_form('events', 'philsquare_events_manager', $assign_id ? 'edit' : 'new', 'admin/events_manager/fields', $assign_id, array(), true, array());
	}
	
	public function delete($assign_id)
	{
		$this->streams->cp->teardown_assignment_field($assign_id, true);
		$this->session->set_flashdata('error', 'Field was deleted.');
		redirect('admin/events_manager/fields');
	}
}