<h1><?php echo lang('user:login_header') ?></h1>

<div class="row">
	<div class="col-sm-8 col-sm-offset-8">

		<div class="alert alert-success">
			<?php echo $this->lang->line('user:activated_message') ?>
		</div>

		<?php echo form_open('users/login', array('id'=>'login')) ?>

		<div class="form-group">
			<label for="email"><?php echo lang('global:email') ?></label>
			<?php echo form_input('email', '', 'class="form-control"') ?>
		</div>

		<div class="form-group">
			<label for="password"><?php echo lang('global:password') ?></label>
			<?php echo form_password('password', '', 'class="form-control"') ?>
		</div>

		<input type="submit" value="<?php echo lang('user:login_btn') ?>" name="btnLogin" class="btn btn-default">

		<?php echo form_close() ?>

	</div>
</div>