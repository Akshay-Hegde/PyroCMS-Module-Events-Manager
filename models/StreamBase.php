<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

// v1.0.0

class StreamBase {
	
	protected $namespace = 'philsquare_events_manager';
	
	protected $disable = 'id|created|image|updated|created_by';
	
	public function __construct()
	{

	}
	
	public function paginate($seg)
	{
		$this->limit = Settings::get('records_per_page');
		$this->paginate = 'yes';
		$this->pag_segment = $seg;
		
		return $this;
	}
	
	public function get($id)
	{
		return $this->ci->streams->entries->get_entry($id, $this->stream, $this->namespace, false);
	}
	
	public function getAll()
	{
		return $this->ci->streams->entries->get_entries($this->getParams());
	}
	
	public function where($field, $operator = null, $value = null)
	{
		// Assume "=" and $operator is the $value
		if (func_num_args() == 2)
		{
			$value = $operator;
			$this->where = "`$field` = '{$value}'";
			
			return $this;
		}
		
		$this->where = "`$field` $operator '{$value}'";
		
		return $this;
	}
	
	public function first()
	{
		$query = $this->getAll();
		
		if($query['total']) return $query['entries'][0];
		
		return false;
	}
	
	public function upcoming()
	{
		$this->show_upcoming = 'yes';
		$this->show_past = 'no';
		
		return $this;
	}
	
	public function past()
	{
		$this->show_upcoming = 'no';
		$this->show_past = 'yes';
		
		return $this;
	}
	
	public function date($year, $month = null, $day = null)
	{
		$this->year = $year;
		
		if($month) $this->month = $month;
		
		if($day) $this->day = $day;
		
		return $this;
	}
	
	public function insert($data)
	{
		return $this->ci->streams->entries->insert_entry($data, $this->stream, $this->namespace);
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