<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

// v1.0.0

class StreamBase {
	
	protected $namespace = 'philsquare_events_manager';
	
	protected $disable = 'id|created|image|updated|created_by';
	
	protected $params = array();
	
	public function __construct()
	{
		
	}
	
	public function get($id)
	{
		return $this->ci->streams->entries->get_entry($id, $this->stream, $this->namespace, false);
	}
	
	public function getAll()
	{
		return $this->ci->streams->entries->get_entries($this->getParams());
	}
	
	public function delete($id)
	{
		return $this->ci->streams->entries->delete_entry($id, $this->stream, $this->namespace);
	}
	
	private function getParams()
	{
		$entries_params = $this->ci->streams->entries->entries_params;
		
		foreach($entries_params as $param => $default)
		{
			if(isset($this->$param)) $params[$param] = $this->$param;
			
			else $params[$param] = $default;
		}
		
		return $params;
	}
}