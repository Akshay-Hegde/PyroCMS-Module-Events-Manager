<?php defined('BASEPATH') or exit('No direct script access allowed');

class Module_Events_manager extends Module {

	public $version = '1.0.3';

	public function info()
	{
		$info = array(
			
			'name' => array(
				'en' => 'Events Manager'
			),
			
			'description' => array(
				'en' => 'Create and manage events'
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
				'categories', 'custom_fields', 'colors', 'settings', 'export', 'edit_all'
			)
		);
		
		// Add section only if they have permission
		if (function_exists('group_has_role'))
		{
			if(group_has_role('philsquare_events_manager', 'categories'))
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
			
			if(group_has_role('philsquare_events_manager', 'custom_fields'))
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
			
			if(group_has_role('philsquare_events_manager', 'colors'))
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
			
			if(group_has_role('philsquare_events_manager', 'export'))
			{
				$info['sections']['export'] = array(
					'name' 	=> 'events_manager:export:title',
					'uri' 	=> 'admin/events_manager/export'
				);
			}
			
			if(group_has_role('philsquare_events_manager', 'settings'))
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
		$this->streams->utilities->remove_namespace('philsquare_events_manager');
		
		// Create Folder
		$folder = Files::create_folder(0, 'Event Manager Images');
		if($folder['status'] != 1) return false;
		$folderId = $folder['data']['id'];
		
		if( ! $eventStreamId = $this->streams->streams->add_stream(
			'Events',
			'events',
			'philsquare_events_manager',
			'philsquare_events_manager_',
			'Events'
		)) return false;
			
		if( ! $colorStreamId = $this->streams->streams->add_stream(
			'Colors',
			'colors',
			'philsquare_events_manager',
			'philsquare_events_manager_',
			'Category Colors'
		)) return false;
			
		if( ! $categoryStreamId = $this->streams->streams->add_stream(
			'Categories',
			'categories',
			'philsquare_events_manager',
			'philsquare_events_manager_',
			'Event Categories'
		)) return false;
			
		if( ! $registrationStreamId = $this->streams->streams->add_stream(
			'Registrations',
			'registrations',
			'philsquare_events_manager',
			'philsquare_events_manager_',
			'Registrations'
		)) return false;

		if( ! $settingStreamId = $this->streams->streams->add_stream(
			'Settings',
			'settings',
			'philsquare_events_manager',
			'philsquare_events_manager_',
			'Events Manager Settings'
		)) return false;	
		
		/*
		|--------------------------------------------------------------------------
		| Create Fields
		|--------------------------------------------------------------------------
		|
		|
		*/
		
		$fields = array(
			array(
				'name' => 'Title',
				'slug' => 'title',
				'namespace' => 'philsquare_events_manager',
				'type' => 'text',
				'extra' => array('max_length' => 200)
			),
			array(
				'name' => 'Slug',
				'slug' => 'slug',
				'namespace' => 'philsquare_events_manager',
				'type' => 'slug',
				'extra' => array('space_type' => '-', 'slug_field' => 'title')
			),
			array(
				'name' => 'Hex',
				'slug' => 'hex',
				'namespace' => 'philsquare_events_manager',
				'type' => 'text',
				'extra' => array('max_length' => 6)
			),
			array(
				'name' => 'Description',
				'slug' => 'description',
				'namespace' => 'philsquare_events_manager',
				'type' => 'textarea',
				'extra' => array('editor_type' => 'advanced', 'allow_tags' => 'y'),
			),
			array(
				'name' => 'Details',
				'slug' => 'details',
				'namespace' => 'philsquare_events_manager',
				'type' => 'wysiwyg',
				'extra' => array('editor_type' => 'advanced', 'allow_tags' => 'y'),
			),
			array(
				'name' => 'Start',
				'slug' => 'start',
				'namespace' => 'philsquare_events_manager',
				'type' => 'datetime',
				'extra' => array('use_time' => 'yes', 'storage' => 'datetime', 'input_type' => 'datepicker')
			),
			array(
				'name' => 'End',
				'slug' => 'end',
				'namespace' => 'philsquare_events_manager',
				'type' => 'datetime',
				'extra' => array('use_time' => 'yes', 'storage' => 'datetime', 'input_type' => 'datepicker')
			),
			array(
				'name' => 'Location',
				'slug' => 'location',
				'namespace' => 'philsquare_events_manager',
				'type' => 'text',
				'extra' => array('max_length' => 100)
			),
			array(
				'name' => 'Registration Required',
				'slug' => 'registration',
				'namespace' => 'philsquare_events_manager',
				'type' => 'choice',
				'extra' => array('choice_data' => "yes : Yes\nno : No", 'choice_type' => 'radio', 'default_value' => 'no')
			),
			array(
				'name' => 'Limit',
				'slug' => 'limit',
				'namespace' => 'philsquare_events_manager',
				'type' => 'integer',
				'extra' => array('max_length' => 4)
			),
			array(
				'name' => 'Name',
				'slug' => 'name',
				'namespace' => 'philsquare_events_manager',
				'type' => 'text',
				'extra' => array('max_length' => 100)
			),
			array(
				'name' => 'Email',
				'slug' => 'email',
				'namespace' => 'philsquare_events_manager',
				'type' => 'email'
			),
			array(
				'name' => 'Image',
				'slug' => 'image',
				'namespace' => 'philsquare_events_manager',
				'type' => 'image',
				'extra' => array('folder' => $folderId),
			),
			array(
				'name'      => 'Default View',
				'slug'      => 'default_view',
				'namespace' => 'philsquare_events_manager',
				'type'      => 'choice',
				'extra' => array('choice_data' => "calendar : Calendar\nevents : List", 'choice_type' => 'radio', 'default_value' => 'calendar')
			),
			array(
				'name'      => 'Calendar Day Option',
				'slug'      => 'calendar_day_option',
				'namespace' => 'philsquare_events_manager',
				'type'      => 'choice',
				'extra' => array('choice_data' => "list : Show Events\nlink : Link to Day View", 'choice_type' => 'radio', 'default_value' => 'list')
			),
			array(
				'name'      => 'Enable Registrations',
				'slug'      => 'allow_registrations',
				'namespace' => 'philsquare_events_manager',
				'type'      => 'choice',
				'extra' => array('choice_data' => "no : No\nyes : Yes", 'choice_type' => 'radio', 'default_value' => 'no')
			),
			array(
				'name' => 'Calendar Layout',
				'slug' => 'calendar_layout',
				'namespace' => 'philsquare_events_manager',
				'type' => 'text',
				'extra' => array('max_length' => 40)
			),
			array(
				'name' => 'List Layout',
				'slug' => 'list_layout',
				'namespace' => 'philsquare_events_manager',
				'type' => 'text',
				'extra' => array('max_length' => 40)
			),
			array(
				'name' => 'Event',
				'slug' => 'event_id',
				'namespace' => 'philsquare_events_manager',
				'type' => 'relationship',
				'extra' => array('choose_stream' => $eventStreamId),
			),
			array(
				'name' => 'Category',
				'slug' => 'category_id',
				'namespace' => 'philsquare_events_manager',
				'type' => 'relationship',
				'extra' => array('choose_stream' => $categoryStreamId),
			),
			array(
				'name' => 'Color',
				'slug' => 'color_id',
				'namespace' => 'philsquare_events_manager',
				'type' => 'relationship',
				'extra' => array('choose_stream' => $colorStreamId),
			)
		);
				
		$this->streams->fields->add_fields($fields);	
				
		/*
		|--------------------------------------------------------------------------
		| Assign Fields
		|--------------------------------------------------------------------------
		|
		|
		*/
		
		$assignments = array(
			
			'events' => array(
				
				'title'        => array('title_column' => true, 'required' => true, 'unique' => false),
				'slug'         => array('required' => true, 'unique' => false),
				'start'        => array('required' => true),
				'end'          => array('required' => true),
				'category_id'  => array('required' => true),
				'details'      => array('required' => true),
				'image'        => array(),
				'location'     => array(),
				'registration' => array(),
				'limit'        => array()
				
			),
			
			'categories' => array(
				
				'title' => array('title_column' => true, 'required' => true, 'unique' => true),
				'slug'  => array('required' => true, 'unique' => true),
				'description' => array(),
				'color_id' => array('required' => true),
				'image' => array()
				
			),
			
			'colors' => array(
				
				'title' => array('title_column' => true, 'required' => true, 'unique' => true),
				'slug'  => array('required' => true, 'unique' => true),
				'hex' => array('required' => true, 'unique' => true)
				
			),
			
			'registrations' => array(
				
				'name' => array('required' => true),
				'email' => array('required' => true),
				'event_id' => array('required' => true)
				
			),
			
			'settings' => array(
				
				'default_view' => array('instructions' => 'Calendar or list view', 'required' => true),
				'calendar_day_option' => array(),
				'allow_registrations' => array('instructions' => 'Enabling this will allow you to optionally accept registration for your events. Currently, the registration does not require the registrant to login and only acquires their name and email.'),
				'calendar_layout' => array('instructions' => 'Type in the name of the theme layout file you would like to use for the calendar view.'),
				'list_layout' => array('instructions' => 'Type in the name of the theme layout file you would like to use for the list view.')
					
			)
			
		);

		foreach($assignments as $stream => $fields)
		{
			foreach($fields as $field => $assign_data)
			{
				$this->streams->fields->assign_field('philsquare_events_manager', $stream, $field, $assign_data);
			}
		}
		
		/*
		|--------------------------------------------------------------------------
		| Default Data
		|--------------------------------------------------------------------------
		|
		|
		*/
		
		$colors = array(
			array(
				'title' => 'Gray',
				'hex' => '999999',
				'slug' => 'gray'
			),
			array(
				'title' => 'Yellow',
				'hex' => 'ffff00',
				'slug' => 'yellow'
			),
			array(
				'title' => 'Orange',
				'hex' => 'ff9900',
				'slug' => 'orange'
			),
			array(
				'title' => 'Purple',
				'hex' => '000066',
				'slug' => 'purple'
			),
			array(
				'title' => 'Red',
				'hex' => 'ff0000',
				'slug' => 'red'
			),
			array(
				'title' => 'Green',
				'hex' => '006600',
				'slug' => 'green'
			),
			array(
				'title' => 'Blue',
				'hex' => '0000ff',
				'slug' => 'blue'
			),
			array(
				'title' => 'Brown',
				'hex' => '663300',
				'slug' => 'brown'
			),
			array(
				'title' => 'Black',
				'hex' => '000000',
				'slug' => 'black'
			)
		);
		
		foreach($colors as $color)
		{
			$this->streams->entries->insert_entry($color, 'colors', 'philsquare_events_manager');
		}
		
		$category = array(
		        'title' => 'No Category',
				'slug' => 'no-category',
				'color_id' => 1
		);
		
		$this->streams->entries->insert_entry($category, 'categories', 'philsquare_events_manager');
		
		$settings = array(
			'default_view' => 'calendar',
			'calendar_day_option' => 'list',
			'allow_registrations' => 'no',
			'calendar_layout' => 'default.html',
			'list_layout' => 'default.html'
		);
		
		$this->streams->entries->insert_entry($settings, 'settings', 'philsquare_events_manager');
			
		return true;
	}

	public function uninstall()
	{
		$this->load->driver('Streams');
		$this->load->library('files/files');
        $this->streams->utilities->remove_namespace('philsquare_events_manager');
		$this->db->delete('settings', array('module' => 'philsquare_events_manager'));
		
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

		if($old_version == '1.0.0')
		{
			$settings = array(
				array(
					'slug' => 'em_list_layout',
					'title' => 'List View Theme Layout',
					'description' => 'Type in the name of the theme layout file you would like to use for the list view.',
					'`default`' => 'default.html',
					'`value`' => 'default.html',
					'type' => 'text',
					'`options`' => '',
					'is_required' => 1,
					'is_gui' => 1,
					'module' => 'philsquare_events_manager',
					'order' => 60
				),
			);
			
			// Let's try running our DB Forge Table and inserting some settings
			if ( ! $this->db->insert_batch('settings', $settings))
			{
				return false;
			}
			
			$old_version = '1.0.1';
		}
		
		if($old_version == '1.0.1')
		{
			// Get field and update settings
			$field = $this->streams->fields->get_field_assignments('description', 'philsquare_events_manager');
			$field_data = unserialize($field[0]->field_data);
			$field_data['allow_tags'] = 'y';
			
			$data = array('field_data' => serialize($field_data));
			
			// No update in the streams driver to use active record
			$update = $this->db->update('data_fields', $data, array('id' => $field[0]->id));
			
			if(! $update) return false;
			
			$old_version == '1.0.2';
		}

        if($old_version == '1.0.2')
        {
            // New namespace
            $data = array(
                'stream_namespace' => 'philsquare_events_manager',
                'stream_prefix' => 'philsquare_events_manager_'
            );

            $this->db->where('stream_namespace', 'events_manager')->update('data_streams', $data);
        }
		
		return true;
	}
}
/* End of file details.php */
