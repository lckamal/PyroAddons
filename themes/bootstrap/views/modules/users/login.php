<?php if (validation_errors()): ?>
<div class="alert alert-danger">
	<?php echo validation_errors();?>
</div>
<?php endif ?>

<?php echo form_open('users/login', array('id'=>'login', 'class' => 'form-horizontal'), array('redirect_to' => $redirect_to)) ?>
<div class="row-fluid">
	<div class="well">
		<fieldset>
		<legend><?php echo lang('user:login_header') ?></legend>
		<ul>
			<li class="form-group">
				<!-- <label class="control-label col-lg-4" for="email"><?php echo lang('global:email') ?></label> -->
				<div class="col-lg-12">
					<?php echo form_input('email', $this->input->post('email') ? $this->input->post('email') : '', 'class="form-control input-sm" placeholder="Email or Username"')?>
				</div>
			</li>
			<li class="form-group">
				<!-- <label class="control-label col-lg-4" for="password"><?php echo lang('global:password') ?></label> -->
				<div class="col-lg-12">
					<input class="form-control input-sm" type="password" id="password" name="password" maxlength="20" placeholder="Password" />
				</div>
			</li>
			<li class="form-group" id="remember_me">
				<label class="col-lg-12">
					<?php echo form_checkbox('remember', '1', false) ?>
					Stay signed in 
				</label>
				<span class="help-block col-lg-12 text-sm m0">(Always clear the tick box if you are on a shared computer.)</span>
			</li>
			<li class="form-group">
				<div class="col-lg-6">
					<input class="btn btn-primary" type="submit" value="Sign in" name="btnLogin" />
					<span class="btn btn-link register"><?php echo anchor('register', 'Register School');?></span>
				</div>
				<div class="col-lg-6 text-right">
					<?php echo anchor('users/reset_pass', lang('user:reset_password_link'), 'class="btn btn-link "');?>
				</div>
			</li>
		</ul>
		</fieldset>
	</div>
</div>
<?php echo form_close() ?>