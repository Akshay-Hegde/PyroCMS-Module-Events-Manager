<ul class="events-manager-widget-display-events">
	<?php foreach($events as $event): $event = (object) $event; ?>
		<li><?php echo anchor(site_url('events_manager/event' . date('/Y/m/d/', $event->start) . $event->slug), date('M jS', $event->start) . ' - ' . $event->title); ?>
		</li>
	<?php endforeach; ?>
</ul>