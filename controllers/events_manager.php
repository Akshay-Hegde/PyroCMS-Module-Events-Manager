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
        $this->lang->load('philsquare_events_manager');
		
		$this->load->library('modulesetting');
		
		$defaultView = $this->modulesetting->get('default_view');

		if($this->uri->segment(3) == '') redirect('events_manager/' . $defaultView);
    }

	public function index()
	{
		
	}

}