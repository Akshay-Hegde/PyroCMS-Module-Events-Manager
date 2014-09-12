<?php if(isset($template['partials']['filters'])) echo $template['partials']['filters'] ?>

<?php if(count($events)): ?>
	
	<table>
		<thead>
			<tr>
				<th>Date</th>
				<th>Event</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($events as $event): ?>
				<tr>
					<td><?php echo date(Settings::get('date_format'), $event['start']) ?></td>
					<td><?php echo $event['title'] ?></td>
				</tr>
			<?php endforeach ?>
		</tbody>
	</table>

<?php else: ?>

	<div class="no_data">No Events</div>

<?php endif ?>