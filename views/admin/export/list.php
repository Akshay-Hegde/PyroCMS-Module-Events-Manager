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
		
		$this->table->add_row($column1, $column2);
	}

	echo $this->table->generate();
	
	echo $pagination;

?>

<?php else: ?>

<div class="no_data">No Events</div>

<?php endif ?>