<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

$config = array(
	'add-registrant' => array(
		array(
			'field' => 'name',
			'label' => 'Name',
			'rules'	=> 'required|trim|max_length[100]'
		),
		array(
			'field' => 'email',
			'label' => 'Email',
			'rules'	=> 'required|valid_email|max_length[255]'
		)
	)
);