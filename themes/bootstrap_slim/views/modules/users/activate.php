<h1><?php echo lang('user:register_header') ?></h1>

<div class="row">
	<div class="col-sm-8 col-sm-offset-2">

		<div class="alert alert-success">
			<p>You should receive an email with a link that will allow you to activate your account. Please check your spam folder if you do not see the email after a couple minutes. Alternatively, you can activate your account by filling out the form below using your activation code.</p>
		</div>

		<?php if(!empty($error_string)): ?>
			<div class="error-box">
				<?php echo $error_string ?>
			</div>
		<?php endif;?>

		<?php echo form_open('users/activate', 'id="activate-user"') ?>
		<div class="form-group">
			<label for="email"><?php echo lang('global:email') ?></label>
			<?php echo form_input('email', isset($_user['email']) ? $_user['email'] : '', 'class="form-control"');?>
		</div>

		<div class="form-group">
			<label for="activation_code"><?php echo lang('user:activation_code') ?></label>
			<?php echo form_input('activation_code', '', 'class="form-control"');?>
		</div>

		<?php echo form_submit('btnSubmit', lang('user:activate_btn'), 'class="btn btn-default"') ?>

		<?php echo form_close() ?>

	</div>
</div>