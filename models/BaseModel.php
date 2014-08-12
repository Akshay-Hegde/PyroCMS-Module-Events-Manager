<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class BaseModel extends StreamModel {
	
	protected $namespace = 'philsquare_events_manager';
	
	protected $disable = 'id|created|image|updated|created_by';
	
}