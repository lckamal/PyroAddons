<?php if(!empty($error_string)): ?>
<div class="alert alert-danger">
	<?php echo $error_string ?>
</div>
<?php endif;?>
<?php echo form_open('users/activate', 'id="activate-user" class="form-horizontal"') ?>
<fieldset>
	<legend>Activate Account</legend>
	<div class="form-group">
		<label class="control-label col-lg-3" for="email"><?php echo lang('global:email') ?></label>
		<div class="col-lg-6">
		<?php echo form_input('email', isset($_user['email']) ? $_user['email'] : '', 'maxlength="40" class="form-control input-sm"');?>
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-lg-3" for="activation_code"><?php echo lang('user:activation_code') ?></label>
		<div class="col-lg-6">
		<?php echo form_input('activation_code', '', 'maxlength="40" class="form-control input-sm"');?>
		</div>
	</div>

	<div class="form-group">
		<div class="col-lg-4 col-lg-offset-3">
		<?php echo form_submit('btnSubmit', lang('user:activate_btn'), 'class="btn btn-primary"') ?>
		</div>
	</div>
</fieldset>
<?php echo form_close() ?>