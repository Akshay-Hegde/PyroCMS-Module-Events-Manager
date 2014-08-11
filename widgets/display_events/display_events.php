<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Display Events
 *
 *
 * @author 		Philsquare Dev Team
 * @website		http://philsquare.com
 * @package 	PyroCMS
 * @subpackage 	Events Manager Module
 */

class Widget_Display_events extends Widgets
{

	public $author = 'Phil Martinez';

	public $website = 'http://philsquare.com';

	public $version = '1.0.0';

	public $title = array(
		'en' => 'Display Events'
	);

	public $description = array(
		'en' => 'List events from the Events Manager module.'
	);
	
	// build form fields for the backend
	// MUST match the field name declared in the form.php file
	public $fields = array(
		array(
			'field' => 'limit',
			'label' => 'Number of posts',
		),
		array(
			'field' => 'category_id',
			'label' => 'Category'
		)
	);

	public function form($options)
	{
		$this->load->driver('streams');
		
		$params = array(
			'stream' => 'categories',
			'namespace' => 'philsquare_events_manager'
		);
		
		$results = $this->streams->entries->get_entries($params);
		
		$categories[0] = 'Any';
		
		foreach($results['entries'] as $category)
		{
			$categories[$category['id']] = $category['category'];
		}
		
		// Limit
		!empty($options['limit']) OR $options['limit'] = 5;
		!empty($options['category_id']) OR $options['category_id'] = 0;
		
		return array(
			'options' => $options,
			'categories' => $categories
		);
	}

	public function run($options)
	{
		$this->load->driver('streams');
		
		empty($options['limit']) AND $options['limit'] = 5;
		
		$params = array(
			'stream' => 'events',
			'namespace' => 'philsquare_events_manager',
			'limit' => $options['limit'],
			'order_by' => 'start',
			'sort' => 'asc',
			'date_by' => 'start',
			'show_past' => 'no',
		);

		if(isset($options['category_id']) && $options['category_id'])
		{
			if($options['category_id']) $id = $options['category_id'];
			$params['where'] = "`category_id` = '{$id}'";
		}	

		$events = $this->streams->entries->get_entries($params);
		
		return array(
			'events' => $events['entries'],
			'category_id' => $options['category_id']
		);
	}

}
