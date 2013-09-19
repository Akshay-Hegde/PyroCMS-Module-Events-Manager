<?php if(isset($template['partials']['filters'])) echo $template['partials']['filters'] ?>

<?php if($registrants['total']): ?>

<?php

	$this->table->set_heading('Name', 'Email', '&nbsp;');
	
	foreach($registrants['entries'] as $registrant)
	{
		$registrant = (object) $registrant;
		
		// Column Data
		$column1 = $registrant->name;
		$column2 = $registrant->email;
		
		// Actions
		$action1 = anchor(site_url("admin/events_manager/delete_registrant/$registrant->id"), 'Remove', 'class="button confirm"');		
		
		$actions = array(
			'data' => $action1,
			'class' => "actions"
		);
		
		$this->table->add_row($column1, $column2, $actions);
	}

	echo $this->table->generate();
	
	echo $registrants['pagination'];

?>

<br>

<div class="table_action_buttons">
	<?php echo anchor(site_url('admin/events_manager/add_registrant/' . $event->id), 'Add Registrant', 'class="btn blue"') ?>
	<?php echo anchor(site_url('admin/events_manager/form/' . $event->id), 'Edit Event', 'class="btn orange"') ?>
	<?php echo anchor(site_url('admin/events_manager'), '&larr; Back to list', 'class="btn gray"') ?>
	
</div>

<?php else: ?>

<div class="no_data">No Registrants</div>

<?php endif ?>