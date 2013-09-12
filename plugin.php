<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Plugin for events manager
 *
 * @author 		Phil Martinez - Philsquare Dev Team
 * @website		http://philsquarelabs.com
 * @package 	PyroCMS
 * @subpackage 	Template Module
 */
class Plugin_Events_manager extends Plugin
{

	public $version = '1.0.0';
	public $name = array(
		'en' => 'Event Manager'
	);
	public $description = array(
		'en' => 'Events Manager plugin'
	);
	
	public function _self_doc()
	{
		$info = array(
			'method' => array(
				'description' => array(
					'en' => ''
				),
				'single' => true,
				'double' => false,
				'variables' => '',
				'attributes' => array(
					'id' => array(
						'type' => 'number',
						'flags' => '',
						'default' => '',
						'required' => true,
					),
				),
			)
		);
	
		return $info;
	}
	
	public function test()
	{
		
	}

}

/* End of file plugin.php */