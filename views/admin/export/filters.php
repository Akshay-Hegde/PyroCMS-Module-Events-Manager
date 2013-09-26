<fieldset id="filters">

    <legend><?php echo lang('global:filters'); ?></legend>

    <?php echo form_open(); ?>

        <ul>  
            <li>
                <label name="from">From</label>
				<?php echo form_input('from', set_value('from', $from), 'id="datepicker"') ?>
            </li>
			<li>
                <label name="to">To</label>
				<?php echo form_input('to', set_value('to', $to), 'id="datepicker"') ?>
            </li>
			<li><?php echo form_submit('submit', 'Export to CSV', 'class="button"'); ?></li>
            <li><?php echo anchor(current_url(), 'Reset', 'class="button"'); ?></li>
        </ul>
    <?php echo form_close(); ?>

</fieldset>