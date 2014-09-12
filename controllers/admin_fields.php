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
    }

	public function index($offset = 0)
	{
		$limit = Settings::get('records_per_page');
		
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

		$this->streams->cp->assignments_table(
			'events',
			'philsquare_events_manager',
			$limit,
			'admin/events_manager/fields/index',
			true,
			$extra,
			$exclude
		);
	}
	
	public function form($assign_id = null)
	{
		$this->streams->cp->field_form(
			'events',
			'philsquare_events_manager',
			$assign_id ? 'edit' : 'new',
			'admin/events_manager/fields',
			$assign_id,
			array(),
			true,
			array()
		);
	}
	
	public function delete($assign_id)
	{
		$this->streams->cp->teardown_assignment_field($assign_id, true);
		$this->session->set_flashdata('error', 'Field was deleted.');
		redirect('admin/events_manager/fields');
	}
}