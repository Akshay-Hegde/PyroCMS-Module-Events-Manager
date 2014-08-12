<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Events manager module
 *
 *
 * @author 		Phil Martinez - Philsquare Dev Team
 * @website		http://philsquare.com
 * @package 	PyroCMS
 */

class Admin_settings extends Admin_Controller
{

	/**
	 * The current active section
	 *
	 * @var string
	 */
	protected $section = 'settings';

    public function __construct()
    {
        parent::__construct();

		// role_or_die('classes_pro', 'classes');
		
		// Templates use this lib
		$this->load->library('table');
		
		// Set CP GUI table attr
		$this->table->set_template(array('table_open'  => '<table class="table-list" border="0" cellspacing="0">'));
    }

	public function index()
	{
		$extra = array(
			'return' => 'admin/events_manager/settings',
			'title' => 'Edit Settings'
		);
		
		$this->streams->cp->entry_form('settings', 'philsquare_events_manager', 'edit', 1, true, $extra);
	}
}