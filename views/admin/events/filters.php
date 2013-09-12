<fieldset id="filters">

    <legend><?php echo lang('global:filters'); ?></legend>

    <?php echo form_open(); ?>

        <ul>  
            <li>
                <label name="month">Month</label>
				<?php echo form_dropdown('month', range(1, 12), set_value('month', $filters->month)) ?>
            </li>
			<li>
				<label name="year">Month</label>
				<?php echo form_dropdown('year', range(2013, 2016), set_value('year', $filters->year)) ?>
			</li>
			<li><?php echo form_submit('submit', 'Filter', 'class="button"'); ?></li>
            <li><?php echo anchor(current_url(), 'Reset', 'class="button"'); ?></li>
        </ul>
    <?php echo form_close(); ?>

</fieldset>