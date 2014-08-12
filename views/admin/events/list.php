<?php if(isset($template['partials']['filters'])) echo $template['partials']['filters'] ?>

<?php if(count($events)): ?>
	
	<table>
		<thead>
			<tr>
				<th>Date</th>
				<th>Event</th>
				<th>&nbsp;</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($events['entries'] as $event): ?>
				<tr>
					<td><?php echo date(Settings::get('date_format'), $event['start']) ?></td>
					<td><?php echo $event['title'] ?></td>
					<td class="actions">
						<?php if($event['registration']['key'] == 'yes'): ?>
							<?php echo anchor(site_url("admin/events_manager/registrations/" . $event['id']), 'Registrants (' . $event['registration_count'] . ')', 'class="button"') ?>
						<?php endif ?>
					
						<?php echo anchor(site_url('events_manager/event' . date('/Y/m/d/', $event['start']) . $event['slug']), 'View', 'class="button"') ?>
						<?php echo anchor(site_url("admin/events_manager/form/" . $event['id']), 'Edit', 'class="button"') ?>
						<?php echo anchor(site_url("admin/events_manager/delete/" . $event['id']), 'Delete', 'class="button confirm"') ?>
					</td>
				</tr>
			<?php endforeach ?>
		</tbody>
	</table>

	<?php echo $pagination ?>

<?php else: ?>

	<div class="no_data">No Events</div>

<?php endif ?>