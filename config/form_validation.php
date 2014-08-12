<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

$config = array(
	
	'add-registrant' => array(
		array(
			'field' => 'name',
			'label' => 'Name',
			'rules'	=> 'required'
		),
		array(
			'field' => 'email',
			'label' => 'Email',
			'rules'	=> 'valid_email|required'
		)
	)
	
);