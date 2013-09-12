<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Simple example widget
 *
 * @author  Phil Martinez, Philsquare Dev Team
 * @package Simple
 */
class Widget_Sample extends Widgets
{

	public $author = 'Phil Martinez';

	public $website = 'http://philsquare.com';

	public $version = '1.0.0';

	public $title = array(
		'en' => 'Sample'
	);

	public $description = array(
		'en' => 'Sample example widget'
	);
	
	// build form fields for the backend
	// MUST match the field name declared in the form.php file
	public $fields = array(
		array(
			'field' => 'limit',
			'label' => 'Number of posts',
		)
	);

	public function form($options)
	{
		// Data for form
		return array(
			'key' => 'value'
		);
	}

	public function run($options)
	{
		// Load data
		$this->load->model('item_model', 'items');
		
		// Return data
		return array(
			'key' => 'value'
		);
	}

}
