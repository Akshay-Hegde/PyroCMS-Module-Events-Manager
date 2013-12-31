<?php defined('BASEPATH') or exit('No direct script access allowed');

class Module_Events_manager extends Module {

	public $version = '1.0.0';

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
				'categories', 'custom_fields', 'colors', 'settings', 'export'
			)
		);
		
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
			
			if(group_has_role('events_manager', 'colors'))
			{
				$info['sections']['colors'] = array(
					'name' 	=> 'events_manager:colors:title',
					'uri' 	=> 'admin/events_manager/colors',
					'shortcuts' => array(
						'create' => array(
							'name' 	=> 'events_manager:colors:add',
							'uri' 	=> 'admin/events_manager/colors/form',
							'class' => 'add'
						)
					)
				);
			}
			
			if(group_has_role('events_manager', 'export'))
			{
				$info['sections']['export'] = array(
					'name' 	=> 'events_manager:export:title',
					'uri' 	=> 'admin/events_manager/export'
				);
			}
			
			if(group_has_role('events_manager', 'settings'))
			{
				$info['sections']['settings'] = array(
					'name' 	=> 'events_manager:settings:title',
					'uri' 	=> 'admin/events_manager/settings'
				);
			}
		}
		
		return $info;
	}

	public function install()
	{
		$this->load->driver('Streams');
		$this->load->library('files/files');
		$this->streams->utilities->remove_namespace('events_manager');
		$this->db->delete('settings', array('module' => 'events_manager'));
		Files::delete_folder(Settings::get('em_categories_folder_id'));
		
		// Create Folder
		$folder = Files::create_folder(0, 'EM Category Images');
		if($folder['status'] != 1) return false;
		$em_categories_folder_id = $folder['data']['id'];
		
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
				'name' => 'Slug',
				'slug' => 'color_slug',
				'namespace' => 'events_manager',
				'type' => 'slug',
				'extra' => array('space_type' => '-', 'slug_field' => 'color'),
				'assign' => 'category_colors',
				'title_column' => false,
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
				'hex' => '999999',
				'slugs' => 'grey'
			),
			array(
				'color' => 'Yellow',
				'hex' => 'ffff00',
				'slugs' => 'yellow'
			),
			array(
				'color' => 'Orange',
				'hex' => 'ff9900',
				'slugs' => 'orange'
			),
			array(
				'color' => 'Purple',
				'hex' => '000066',
				'slugs' => 'purple'
			),
			array(
				'color' => 'Red',
				'hex' => 'ff0000',
				'slugs' => 'red'
			),
			array(
				'color' => 'Green',
				'hex' => '006600',
				'slugs' => 'green'
			),
			array(
				'color' => 'Blue',
				'hex' => '0000ff',
				'slugs' => 'blue'
			),
			array(
				'color' => 'Brown',
				'hex' => '663300',
				'slugs' => 'brown'
			),
			array(
				'color' => 'Black',
				'hex' => '000000',
				'slugs' => 'black'
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
				'name' => 'Slug',
				'slug' => 'category_slug',
				'namespace' => 'events_manager',
				'type' => 'slug',
				'extra' => array('space_type' => '-', 'slug_field' => 'category'),
				'assign' => 'categories',
				'title_column' => false,
				'required' => true,
				'unique' => true
			),
			array(
				'name' => 'Description',
				'slug' => 'category_description',
				'namespace' => 'events_manager',
				'type' => 'textarea',
				'assign' => 'categories',
				'title_column' => false,
				'required' => false,
				'unique' => false
			),
			array(
				'name' => 'Color',
				'slug' => 'color_id',
				'namespace' => 'events_manager',
				'type' => 'relationship',
				'extra' => array('choose_stream' => $category_colors_stream->id),
				'assign' => 'categories',
				'title_column' => false,
				'required' => true,
				'unique' => true
			),
			array(
				'name' => 'Category Image',
				'slug' => 'category_image',
				'namespace' => 'events_manager',
				'type' => 'image',
				'extra' => array('folder' => $folder['data']['id']),
				'assign' => 'categories',
				'title_column' => false,
				'required' => false,
				'unique' => false
			),
		);
		
		$this->streams->fields->add_fields($fields);
		
		$entry_data = array(
		        'category'  => 'No Category',
				'color_id' => 1,
				'category_slug' => 'no-category'
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
				'extra' => array('choice_data' => "yes : Yes\nno : No", 'choice_type' => 'radio', 'default_value' => 'no'),
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
		
		// Add Registrations
		if(!$this->streams->streams->add_stream('Registrations', 'registrations', 'events_manager', 'em_', 'Registrations')) return false;
		
		$events_stream = $this->streams->streams->get_stream('events', 'events_manager');
		
		$fields = array(
			array(
				'name' => 'Event',
				'slug' => 'event_id',
				'namespace' => 'events_manager',
				'type' => 'relationship',
				'extra' => array('choose_stream' => $events_stream->id),
				'assign' => 'registrations',
				'title_column' => false,
				'required' => true
			),
			array(
				'name' => 'Name',
				'slug' => 'name',
				'namespace' => 'events_manager',
				'type' => 'text',
				'extra' => array('max_length' => 100),
				'assign' => 'registrations',
				'title_column' => false,
				'required' => true,
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
				'required' => true,
				'unique' => false
			)
		);
		
		$this->streams->fields->add_fields($fields);
		
		// Ok, now for some settings
		
		$settings = array(
			array(
				'slug' => 'em_default_view',
				'title' => 'Default View',
				'description' => 'Calendar or list view.',
				'`default`' => 'calendar',
				'`value`' => 'calendar',
				'type' => 'radio',
				'`options`' => 'calendar=Calendar|events=List',
				'is_required' => 1,
				'is_gui' => 1,
				'module' => 'events_manager',
				'order' => 100
			),
			array(
				'slug' => 'em_calendar_day_option',
				'title' => 'Calendar day option',
				'description' => 'Show a list of events on the days in the calendar view or just link to the day view.',
				'`default`' => 'list',
				'`value`' => 'list',
				'type' => 'radio',
				'`options`' => 'list=Show Events|link=Link to Day View',
				'is_required' => 1,
				'is_gui' => 1,
				'module' => 'events_manager',
				'order' => 90
			),
			array(
				'slug' => 'em_allow_registrations',
				'title' => 'Enable Registrations',
				'description' => 'Enabling this will allow you to optionally accept registration for your events. Currently, the registration does not require the registrant to login and only acquires their name and email.',
				'`default`' => 'no',
				'`value`' => 'no',
				'type' => 'radio',
				'`options`' => 'no=No|yes=yes',
				'is_required' => 1,
				'is_gui' => 1,
				'module' => 'events_manager',
				'order' => 80
			),
			array(
				'slug' => 'em_categories_folder_id',
				'title' => 'EM Categories Folder ID',
				'description' => 'The ID of the folder where the category images are kept.',
				'`default`' => '0',
				'`value`' => $em_categories_folder_id,
				'type' => 'text',
				'`options`' => '',
				'is_required' => 1,
				'is_gui' => 0,
				'module' => 'events_manager',
				'order' => 0
			),
			array(
				'slug' => 'em_calendar_layout',
				'title' => 'Calendar View Theme Layout',
				'description' => 'Type in the name of the theme layout file you would like to use for the calendar view.',
				'`default`' => 'default.html',
				'`value`' => 'default.html',
				'type' => 'text',
				'`options`' => '',
				'is_required' => 1,
				'is_gui' => 1,
				'module' => 'events_manager',
				'order' => 70
			),
		);
		// Let's try running our DB Forge Table and inserting some settings
		if ( ! $this->db->insert_batch('settings', $settings))
		{
			return false;
		}
			
		return true;
	}

	public function uninstall()
	{
		$this->load->driver('Streams');
		$this->load->library('files/files');
        $this->streams->utilities->remove_namespace('events_manager');
		$this->db->delete('settings', array('module' => 'events_manager'));
		
		// Delete files and then folder
		$folder_id = Settings::get('em_categories_folder_id');
		$files = Files::folder_contents($folder_id);
		
		foreach($files['data']['file'] as $file)
		{
			Files::delete_file($file->id);
		}
		
		Files::delete_folder($folder_id);

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
