<?php defined('BASEPATH') or exit('No direct script access allowed');

class Module_Events_manager extends Module {

	public $version = '0.9.0';

	public function info()
	{
		$info = array(
			'name' => array(
				'en' => 'Events Manager'
			),
			'description' => array(
				'en' => 'Create and manage calendar events'
			),
			'frontend' => true,
			'backend' => true,
			'skip_xss' => false,
			'menu' => 'content',
			'sections' => array(
				'events' => array(
					'name' => 'events_manager:events:title',
					'uri' => 'admin/events_manager',
					'class' => '',
					'shortcuts' => array(
						'create' => array(
							'name' 	=> 'events_manager:events:add',
							'uri' 	=> 'admin/events_manager/form',
							'class' => 'add'
						)
					)
				)
			),
			'roles' => array(
				'categories', 'custom_fields'
			)
		);
		
		// // Adding short cuts to specific sections
		// if ($this->controller == 'admin_simple' && $this->uri->segment(4) != 'other_conditions')
		// {
		// 	$info['sections']['simple']['shortcuts'] = array(
		// 					array(
		// 					    'name' => 'simple:shortcut',
		// 					    'uri' => '',
		// 					    'class' => ''
		// 					)
		// 			    );
		// }
		
		// Add section only if they have permission
		if (function_exists('group_has_role'))
		{
			if(group_has_role('events_manager', 'categories'))
			{
				$info['sections']['categories'] = array(
					'name' 	=> 'events_manager:categories:title',
					'uri' 	=> 'admin/events_manager/categories',
					'shortcuts' => array(
						'create' => array(
							'name' 	=> 'events_manager:categories:add',
							'uri' 	=> 'admin/events_manager/categories/form',
							'class' => 'add'
						)
					)
				);
			}
			
			if(group_has_role('events_manager', 'custom_fields'))
			{
				$info['sections']['custom_fields'] = array(
					'name' 	=> 'events_manager:custom_fields:title',
					'uri' 	=> 'admin/events_manager/fields',
					'shortcuts' => array(
						'create' => array(
							'name' 	=> 'events_manager:custom_fields:add',
							'uri' 	=> 'admin/events_manager/fields/form',
							'class' => 'add'
						)
					)
				);
			}
		}
		
		return $info;
	}

	public function install()
	{
		$this->load->driver('Streams');
		
		// Add Category colors
		if(!$this->streams->streams->add_stream('Category Colors', 'category_colors', 'events_manager', 'em_', 'Colors for Event Categories')) return false;
		
		$fields = array(
			array(
				'name' => 'Color',
				'slug' => 'color',
				'namespace' => 'events_manager',
				'type' => 'text',
				'extra' => array('max_length' => 20),
				'assign' => 'category_colors',
				'title_column' => true,
				'required' => true,
				'unique' => true
			),
			array(
				'name' => 'Hex',
				'slug' => 'hex',
				'namespace' => 'events_manager',
				'type' => 'text',
				'extra' => array('max_length' => 6),
				'assign' => 'category_colors',
				'title_column' => false,
				'required' => true,
				'unique' => true
			)
		);
		
		$this->streams->fields->add_fields($fields);
		
		$colors = array(
			array(
				'color' => 'Grey',
				'hex' => '999999'
			),
			array(
				'color' => 'Yellow',
				'hex' => 'ffff0'
			),
			array(
				'color' => 'Orange',
				'hex' => 'ff9900'
			),
			array(
				'color' => 'Purple',
				'hex' => '000066'
			),
			array(
				'color' => 'Red',
				'hex' => 'ff0000'
			),
			array(
				'color' => 'Green',
				'hex' => '006600'
			),
			array(
				'color' => 'Blue',
				'hex' => '0000ff'
			),
			array(
				'color' => 'Brown',
				'hex' => '663300'
			),
			array(
				'color' => 'Black',
				'hex' => '000000'
			)
		);
		
		foreach($colors as $color)
		{
			$this->streams->entries->insert_entry($color, 'category_colors', 'events_manager');
		}
		
		$category_colors_stream = $this->streams->streams->get_stream('category_colors', 'events_manager');
		
		// Add Categories
		if(!$this->streams->streams->add_stream('Categories', 'categories', 'events_manager', 'em_', 'A list of categories')) return false;
		
		$fields = array(
			array(
				'name' => 'Category',
				'slug' => 'category',
				'namespace' => 'events_manager',
				'type' => 'text',
				'extra' => array('max_length' => 50),
				'assign' => 'categories',
				'title_column' => true,
				'required' => true,
				'unique' => true
			),
			array(
				'name' => 'Color',
				'slug' => 'color_id',
				'namespace' => 'events_manager',
				'type' => 'relationship',
				'extra' => array('choose_stream' => $category_colors_stream->id),
				'assign' => 'categories',
				'title_column' => false,
				'required' => true
			)
		);
		
		$this->streams->fields->add_fields($fields);
		
		$entry_data = array(
		        'category'  => 'No Category',
				'color_id' => 1
		    );
		
		$this->streams->entries->insert_entry($entry_data, 'categories', 'events_manager');
		
		// Add Events
		if(!$this->streams->streams->add_stream('Events', 'events', 'events_manager', 'em_', 'Events')) return false;
		
		$categories_stream = $this->streams->streams->get_stream('categories', 'events_manager');
		
		$fields = array(
			array(
				'name' => 'Title',
				'slug' => 'title',
				'namespace' => 'events_manager',
				'type' => 'text',
				'extra' => array('max_length' => 255),
				'assign' => 'events',
				'title_column' => true,
				'required' => true,
				'unique' => false
			),
			array(
				'name' => 'Slug',
				'slug' => 'slug',
				'namespace' => 'events_manager',
				'type' => 'slug',
				'extra' => array('space_type' => '-', 'slug_field' => 'title'),
				'assign' => 'events',
				'title_column' => false,
				'required' => true,
				'unique' => false
			),
			array(
				'name' => 'Start',
				'slug' => 'start',
				'namespace' => 'events_manager',
				'type' => 'datetime',
				'extra' => array('use_time' => 'yes', 'storage' => 'datetime', 'input_type' => 'datepicker'),
				'assign' => 'events',
				'title_column' => false,
				'required' => true,
				'unique' => false
			),
			array(
				'name' => 'End',
				'slug' => 'end',
				'namespace' => 'events_manager',
				'type' => 'datetime',
				'extra' => array('use_time' => 'yes', 'storage' => 'datetime', 'input_type' => 'datepicker'),
				'assign' => 'events',
				'title_column' => false,
				'required' => true,
				'unique' => false
			),
			array(
				'name' => 'Description',
				'slug' => 'description',
				'namespace' => 'events_manager',
				'type' => 'wysiwyg',
				'extra' => array('editor_type' => 'advanced'),
				'assign' => 'events',
				'title_column' => false,
				'required' => true,
				'unique' => false
			),
			array(
				'name' => 'Location',
				'slug' => 'location',
				'namespace' => 'events_manager',
				'type' => 'text',
				'extra' => array('max_length' => 100),
				'assign' => 'events',
				'title_column' => false,
				'required' => true,
				'unique' => false
			),
			array(
				'name' => 'Registration Required',
				'slug' => 'registration',
				'namespace' => 'events_manager',
				'type' => 'choice',
				'extra' => array('choice_data' => 'Yes', 'choice_type' => 'checkboxes'),
				'assign' => 'events',
				'title_column' => false,
				'required' => false,
				'unique' => false
			),
			array(
				'name' => 'Limit',
				'slug' => 'limit',
				'namespace' => 'events_manager',
				'type' => 'integer',
				'extra' => array('max_length' => 4),
				'assign' => 'events',
				'title_column' => false,
				'required' => false,
				'unique' => false
			),
			array(
				'name' => 'Category',
				'slug' => 'category_id',
				'namespace' => 'events_manager',
				'type' => 'relationship',
				'extra' => array('choose_stream' => $categories_stream->id),
				'assign' => 'events',
				'title_column' => false,
				'required' => true
			)
		);
		
		$this->streams->fields->add_fields($fields);
		
		// Add Events
		if(!$this->streams->streams->add_stream('Registrations', 'registrations', 'events_manager', 'em_', 'Registrations')) return false;
		
		$events_stream = $this->streams->streams->get_stream('events', 'events_manager');
		$users_stream = $this->streams->streams->get_stream('profiles', 'users');
		
		$fields = array(
			array(
				'name' => 'User',
				'slug' => 'user_id',
				'namespace' => 'events_manager',
				'type' => 'relationship',
				'extra' => array('choose_stream' => $users_stream->id),
				'assign' => 'registrations',
				'title_column' => false,
				'required' => false
			),
			array(
				'name' => 'Event',
				'slug' => 'event_id',
				'namespace' => 'events_manager',
				'type' => 'relationship',
				'extra' => array('choose_stream' => $events_stream->id),
				'assign' => 'registrations',
				'title_column' => false,
				'required' => false
			),
			array(
				'name' => 'Name',
				'slug' => 'name',
				'namespace' => 'events_manager',
				'type' => 'text',
				'extra' => array('max_length' => 100),
				'assign' => 'registrations',
				'title_column' => false,
				'required' => false,
				'unique' => false
			),
			array(
				'name' => 'Email',
				'slug' => 'email',
				'namespace' => 'events_manager',
				'type' => 'text',
				'extra' => array('max_length' => 255),
				'assign' => 'registrations',
				'title_column' => false,
				'required' => false,
				'unique' => false
			)
		);
		
		$this->streams->fields->add_fields($fields);
			
		return true;
	}

	public function uninstall()
	{
		$this->load->driver('Streams');

        $this->streams->utilities->remove_namespace('events_manager');

        return true;
	}


	public function upgrade($old_version)
	{
		// Upgrade Logic

		// if($old_version == 'A')
		// {
		// 	// Upgrade from A to B
		// 	
		// 	$old_version = 'B';
		// }
		// 
		// if($old_version == 'B')
		// {
		// 	// Upgrade from B to C
		// 	
		// 	$old_version = 'current';
		// }
		
		return true;
	}
}
/* End of file details.php */
