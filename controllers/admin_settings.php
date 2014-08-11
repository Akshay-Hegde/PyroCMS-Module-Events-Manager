<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Events Manager module
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

		role_or_die('philsquare_events_manager', 'settings');

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

	public function index()
	{
		$this->template->build('admin/settings/index');
	}

}