
	
<?php echo form_open(); ?>

<div class="form_inputs">

<ul>
    <li>
        <label for="name">Name <span>*</span></label>
        <div class="input"><?php echo form_input('name', set_value('name')); ?></div>
    </li>

	<li>
        <label for="email">Email <span>*</span></label>
        <div class="input"><?php echo form_input('email', set_value('email')); ?></div>
    </li>
</ul>

</div><!-- /.form_inputs -->

<div class="buttons">
    <?php $this->load->view('admin/partials/buttons', array('buttons' => array('save', 'cancel') )); ?>
</div><!-- /.buttons -->

<?php echo form_close(); ?>