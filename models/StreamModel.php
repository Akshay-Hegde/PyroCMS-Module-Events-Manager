<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

// v1.0.0

class StreamModel {
	
	/**
	 * Stream Name
	 *
	 * @var		str
	 */
	protected $stream;
	
	/**
	 * Stream Namespace
	 *
	 * @var		str
	 */
	protected $namespace;
	
	/**
	 * Limit rows
	 *
	 * @var		int
	 */
	protected $limit;
	
	/**
	 * Offset rows
	 *
	 * @var		int
	 */
	protected $offset;
	
	/**
	 * Date field to sort by
	 *
	 * @var		str
	 */
	protected $date_by;
	
	/**
	 * Filter by year
	 *
	 * @var		int
	 */
	protected $year;
	
	/**
	 * Filter by month
	 *
	 * @var int
	 */
	protected $month;
	
	/**
	 * Filter by day
	 *
	 * @var int
	 */
	protected $day;
	
	/**
	 * Future dates based on date_by
	 *
	 * @var bool
	 */
	protected $show_upcoming;
	
	/**
	 * Past dates based on date_by
	 *
	 * @var bool
	 */
	protected $show_past;
	
	/**
	 * Must be owned by current_user
	 *
	 * @var bool
	 */
	protected $restrict_user;
	
	/**
	 * Where string
	 *
	 * @var str
	 */
	protected $where;
	
	/**
	 * Exclude values based on exclude_by field
	 * separated by "|"
	 * 
	 * @var str
	 */
	protected $exclude;
	
	/**
	 * Exclude field name
	 *
	 * @var str
	 */
	protected $exclude_by;
	
	/**
	 * Include values based on include_by field
	 * separated by "|"
	 *
	 * @var str
	 */
	protected $include;
	
	/**
	 * Include field name
	 *
	 * @var str
	 */
	protected $include_by;
	
	/**
	 * Disable stream formatting for these fields
	 *
	 * @var str
	 */
	protected $disable;
	
	/**
	 * Order field for sort
	 *
	 * @var str
	 */
	protected $order_by;
	
	/**
	 * Sort order
	 *
	 * @var str
	 */
	protected $sort;
	
	/**
	 * To paginate or not to paginate
	 *
	 * @var bool
	 */
	protected $paginate;
	
	/**
	 * Pagination URI segment
	 *
	 * @var int
	 */
	protected $pag_segment;
	
	/**
	 * Whether to cache query or not
	 *
	 * @var bool
	 */
	protected $cache_query;
	
	/**
	 * Expiration in seconds for cache
	 *
	 * @var int
	 */
	protected $cache_expires;
	
	/*
	| -------------------------------------------------------------------
	| RETRIEVE
	| -------------------------------------------------------------------
	*/
	
	public function get($id)
	{
		return $this->ci->streams->entries->get_entry($id, $this->stream, $this->namespace, false);
	}
	
	public function getAll()
	{
		return $this->ci->streams->entries->get_entries($this->getParams());
	}
	
	public function first()
	{
		$this->limit = 1;
		
		$query = $this->getAll();
		
		if($query['total']) return $query['entries'][0];
		
		return false;
	}
	
	/*
	| -------------------------------------------------------------------
	| QUERY BUILDERS
	| -------------------------------------------------------------------
	*/
	
	public function paginate($seg)
	{
		$this->limit = Settings::get('records_per_page');
		$this->paginate = true;
		$this->pag_segment = $seg;
		
		return $this;
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
	
	public function upcoming()
	{
		$this->show_upcoming = true;
		$this->show_past = false;
		
		return $this;
	}
	
	public function past()
	{
		$this->show_upcoming = false;
		$this->show_past = true;
		
		return $this;
	}
	
	public function date($year, $month = null, $day = null)
	{
		$this->year = $year;
		
		if($month) $this->month = $month;
		
		if($day) $this->day = $day;
		
		return $this;
	}
	
	public function restrict()
	{
		$this->restrict_user = true;
		
		return $this;
	}
	
	public function whereIn($field, $values = null)
	{
		if (func_num_args() == 1)
		{
			$field = 'id';
			$values = $field;
		}
		
		$values = implode('|', $values);
		
		$this->include = $values;
		$this->include_by = $field;
		
		return $this;
	}
	
	public function whereNotIn($field, $values = null)
	{
		if (func_num_args() == 1)
		{
			$field = 'id';
			$values = $field;
		}
		
		$values = implode('|', $values);
		
		$this->exclude = $values;
		$this->exclude_by = $field;
		
		return $this;
	}
	
	public function disable($string)
	{
		$this->disable = $string;
		
		return $this;
	}
	
	public function orderBy($field, $dir = null)
	{
		if($dir) $this->sort = $dir;
		
		$this->order_by = $field;
		
		return $this;
	}
	
	public function cache($expires = null)
	{
		if($expires) $this->cache_expires = $expires;
		
		$this->cache_query = true;
		
		return $this;
	}
	
	/*
	| -------------------------------------------------------------------
	| CREATE
	| -------------------------------------------------------------------
	*/
	
	public function insert($data)
	{
		return $this->ci->streams->entries->insert_entry($data, $this->stream, $this->namespace);
	}
	
	/*
	| -------------------------------------------------------------------
	| DESTROY
	| -------------------------------------------------------------------
	*/
	
	public function delete($id)
	{
		return $this->ci->streams->entries->delete_entry($id, $this->stream, $this->namespace);
	}
	
	/*
	| -------------------------------------------------------------------
	| HELPERS
	| -------------------------------------------------------------------
	*/
	
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