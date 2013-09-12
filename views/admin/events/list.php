<?php if(isset($template['partials']['filters'])) echo $template['partials']['filters'] ?>

<?php if($events['total']): ?>

<?php

	$this->table->set_heading('Date', 'Event', '&nbsp;');
	
	foreach($events['entries'] as $event)
	{
		$event = (object) $event;
		
		// Column Data
		$column1 = date(Settings::get('date_format'), $event->start);
		$column2 = $event->title;
		
		// Actions
		$action1 = anchor(site_url("admin/events_manager/form/$event->id"), 'Edit', 'class="button"');
		$action2 = anchor(site_url("admin/events_manager/delete/$event->id"), 'Delete', 'class="button confirm"');
		
		$actions = array(
			'data' => $action1 . ' ' . $action2,
			'class' => "actions"
		);
		
		$this->table->add_row($column1, $column2, $actions);
	}

	echo $this->table->generate();
	
	echo $events['pagination'];

?>

<?php else: ?>

<div class="no_data">No Events</div>

<?php endif ?>