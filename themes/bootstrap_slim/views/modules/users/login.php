<h1><?php echo lang('user:login_header') ?></h1>

<div class="row">
	<div class="col-sm-8 col-sm-offset-2">

	<?php if (validation_errors()): ?>
		<div class="alert alert-danger">
			<?php echo validation_errors();?>
		</div>
		<?php endif ?>

		<?php echo form_open('users/login', array('id'=>'login'), array('redirect_to' => $redirect_to, 'role' => 'form')) ?>

		<div class="form-group">

			<label for="email"><?php echo lang('global:email') ?></label>
			<?php echo form_input('email', $this->input->post('email') ? $this->input->post('email') : '', 'class="form-control"')?>
		</div>

		<div class="form-group">
			<label for="password"><?php echo lang('global:password') ?></label>
			<input type="password" id="password" name="password" maxlength="20" class="form-control">
		</div>

		<div class="checkbox">
			<label>
				<?php echo form_checkbox('remember', '1', false) ?>
				<?php echo lang('user:remember') ?>
			</label>
		</div>

		<input type="submit" value="<?php echo lang('user:login_btn') ?>" name="btnLogin" class="btn btn-default">

		<p><?php echo anchor('users/reset_pass', lang('user:reset_password_link'));?> | <?php echo anchor('register', lang('user:register_btn'));?></p>

		<?php echo form_close() ?>

	</div>
</div>