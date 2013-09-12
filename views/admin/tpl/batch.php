<?php if(isset($template['partials']['filters'])) echo $template['partials']['filters'] ?>

<?php if($items): ?>

<?php

	$check_all = form_checkbox(array('name' => 'action_to_all', 'class' => 'check-all'));
	$this->table->set_heading($check_all, 'Title', '&nbsp;');
	
	foreach($items as $item)
	{
		$check =  form_checkbox('action_to[]', $item->id);
		$column1 = $item->title;
		$action1 = anchor(site_url('admin/simple/action'), 'Action 1', 'class="button blue"');
		$action2 = anchor(site_url('admin/simple/action'), 'Action 2', 'class="button gray"');
		
		$actions = array(
			'data' => $action1 . ' ' . $action2,
			'class' => "actions"
		);
		
		$this->table->add_row($check, $column1, $actions);
	}

	echo $this->table->generate();
	
	echo $pagination['links'];

?>

<br>

<div class="table_action_buttons">
	<?php echo anchor(site_url(), 'Action', 'class="btn blue"') ?>
</div>

<?php else: ?>

<div class="no_data">No items</div>

<?php endif ?>