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

		role_or_die('philsquare_events_manager', 'settings');
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