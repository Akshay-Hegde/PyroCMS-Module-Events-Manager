<?php if(isset($template['partials']['filters'])) echo $template['partials']['filters'] ?>

<?php if($registrants['total']): ?>
	
	<table>
		<thead>
			<tr>
				<th>Name</th>
				<th>Email</th>
				<th>&nbsp;</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($registrants['entries'] as $registrant): ?>
				<tr>
					<td><?php echo $registrant['name'] ?></td>
					<td><?php echo $registrant['email'] ?></td>
					<td class="actions">
						<?php echo anchor(site_url("admin/events_manager/delete_registrant/{$registrant['id']}/$event->id"), 'Remove', 'class="button confirm"') ?>
					</td>
				</tr>
			<?php endforeach ?>
		</tbody>
	</table>

	<?php echo $registrants['pagination'] ?>

	<br>

<?php else: ?>

	<div class="no_data">No Registrants</div>

<?php endif ?>

<div class="table_action_buttons">
	
	<?php echo anchor(site_url('admin/events_manager/add_registrant/' . $event->id), 'Add Registrant', 'class="btn blue"') ?>
	<?php echo anchor(site_url('admin/events_manager/form/' . $event->id), 'Edit Event', 'class="btn orange"') ?>
	<?php echo anchor(site_url('admin/events_manager'), '&larr; Back to list', 'class="btn gray"') ?>
	
</div>