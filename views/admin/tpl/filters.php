<fieldset id="filters">

    <legend><?php echo lang('global:filters'); ?></legend>

    <?php echo form_open(); ?>
        <ul>  
            <li>
                <label name="filter">Filter Name</label>
                <?php echo form_dropdown('filter', array(0 => 'Filter 1', 1 => 'Filter 2'), set_value('filter')); ?>
            </li>
            <li><?php echo anchor(current_url() . '#', 'Cancel', 'class="cancel"'); ?></li>
        </ul>
    <?php echo form_close(); ?>
</fieldset>