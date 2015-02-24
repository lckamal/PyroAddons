<?php echo form_open('users/login', array('id'=>'login-small'), array('redirect_to' => $redirect_to)) ?>

<div class="form-group">
	<label for="email"><?php echo lang('global:email') ?></label>
	<input type="text" id="email" name="email" class="form-control">
</div>

<div class="form-group">
	<label for="password"><?php echo lang('global:password') ?></label>
	<input type="password" id="password" name="password" class="form-control">

</div>

<input type="submit" value="<?php echo lang('user:login_btn') ?>" name="btnLogin" class="btn btn-default">

<p>
	<?php if (Settings::get('enable_registration')): ?>
		<?php anchor('register', lang('user:register_btn')) ?>
	<?php endif ?>
</p>

<div class="checkbox">
	<label>
		<?php echo form_checkbox('remember', '1', false) ?>
		<?php echo lang('user:remember')?>
	</label>
</div>

<?php echo form_close() ?>