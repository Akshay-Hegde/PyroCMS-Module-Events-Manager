<?php if(isset($template['partials']['filters'])) echo $template['partials']['filters'] ?>

<?php if(count($events)): ?>

<?php

	$this->table->set_heading('Date', 'Event', '&nbsp;');
	
	foreach($events['entries'] as $event)
	{
		$event = (object) $event;
		
		// Column Data
		$column1 = date(Settings::get('date_format'), $event->start);
		$column2 = $event->title;
		
		// Actions
		$actions = array();
		$actions[] = ($event->registration['key'] == 'yes') ? anchor(site_url("admin/events_manager/registrations/$event->id"), 'Registrants (' . $event->registration_count . ')', 'class="button"') : '';
		$actions[] = anchor(site_url('events_manager/event' . date('/Y/m/d/', $event->start) . $event->slug), 'View', 'class="button"');
		$actions[] = anchor(site_url("admin/events_manager/form/$event->id"), 'Edit', 'class="button"');
		$actions[] = anchor(site_url("admin/events_manager/delete/$event->id"), 'Delete', 'class="button confirm"');
		
		$actions = array(
			'data' => implode(' ', $actions),
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