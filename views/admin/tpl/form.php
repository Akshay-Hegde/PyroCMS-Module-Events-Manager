<div class="form_inputs">

<ul>
    <li>
        <label for="input1">Input 1 <span>*</span></label>
        <div class="input"><?php echo form_input('input1', set_value('input1')); ?></div>
    </li>
</ul>

</div><!-- /.form_inputs -->

<div class="buttons">
    <?php $this->load->view('admin/partials/buttons', array('buttons' => array('save', 'cancel') )); ?>
</div><!-- /.buttons -->