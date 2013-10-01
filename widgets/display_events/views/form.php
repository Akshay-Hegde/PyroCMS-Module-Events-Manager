<ol>
	<li class="even">
		<label for="limit">How many events to show.</label>
		<?php echo form_input('limit', $options['limit']); ?>
	</li>
	
	<li class="odd">
		<label for="category_id">Category</label>
		<?php echo form_dropdown('category_id', $categories, $options['category_id']); ?>
	</li>
</ol>