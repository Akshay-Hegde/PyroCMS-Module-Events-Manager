<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Events_manager_base_model extends Philsquare_stream_model {
	
	protected $namespace = 'philsquare_events_manager';
	
	protected $disable = 'id|created|image|updated|created_by';
	
}