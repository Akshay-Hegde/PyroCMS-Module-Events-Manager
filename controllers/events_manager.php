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
		
        $this->load->model('events_manager_setting_model', 'setting');

		$settings = $this->setting->get(1);

		if($this->uri->segment(2) == '') redirect('events_manager/' . $settings->default_view);
    }

}