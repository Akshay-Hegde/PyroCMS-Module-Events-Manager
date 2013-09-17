<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Events Manager module
 *
 *
 * @author 		Phil Martinez - Philsquare Dev Team
 * @website		http://philsquare.com
 * @package 	PyroCMS
 */

class Events_manager extends Public_Controller
{
	private $categories;
	
    public function __construct()
    {
        parent::__construct();

		// Load lang
        $this->lang->load('events_manager');

		// @todo Use defaults in settings to redirct to either
		// list or calendar view
		
		redirect('events_manager/' . Settings::get('em_default_view'));
    }

	public function index()
	{
		
	}

}