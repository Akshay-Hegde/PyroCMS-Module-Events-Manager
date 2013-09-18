<?php if(isset($template['partials']['filters'])) echo $template['partials']['filters'] ?>

<?php if(count($events)): ?>

<?php

	$this->table->set_heading('Date', 'Event', '&nbsp;');
	
	foreach($events as $event)
	{
		$event = (object) $event;
		
		// Column Data
		$column1 = date(Settings::get('date_format'), $event->start);
		$column2 = $event->title;
		
		// Actions
		$action1 = ($event->registration['key'] == 'yes') ? anchor(site_url("admin/events_manager/registrations/$event->id"), 'Registrants (' . $event->registration_count . ')', 'class="button"') : '';
		$action2 = anchor(site_url("admin/events_manager/form/$event->id"), 'Edit', 'class="button"');
		$action3 = anchor(site_url("admin/events_manager/delete/$event->id"), 'Delete', 'class="button confirm"');
		
		
		$actions = array(
			'data' => $action1 . ' ' . $action2 . ' ' . $action3,
			'class' => "actions"
		);
		
		$this->table->add_row($column1, $column2, $actions);
	}

	echo $this->table->generate();
	
	echo $pagination;

?>

<?php else: ?>

<div class="no_data">No Events</div>

<?php endif ?>