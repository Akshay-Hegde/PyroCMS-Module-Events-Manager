<?php if(isset($template['partials']['filters'])) echo $template['partials']['filters'] ?>

<?php if($items): ?>

<?php

	$this->table->set_heading('Title', '&nbsp;');
	
	foreach($items as $item)
	{
		$column1 = $item->title;
		$action1 = anchor(site_url('admin/simple/action'), 'Action 1', 'class="button blue"');
		$action2 = anchor(site_url('admin/simple/action'), 'Action 2', 'class="button gray"');
		
		$actions = array(
			'data' => $action1 . ' ' . $action2,
			'class' => "actions"
		);
		
		$this->table->add_row($column1, $actions);
	}

	echo $this->table->generate();
	
	echo $pagination['links'];

?>

<?php else: ?>

<div class="no_data">No items</div>

<?php endif ?>