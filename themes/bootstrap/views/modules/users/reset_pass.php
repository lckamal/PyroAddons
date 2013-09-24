<?php if(!empty($error_string)):?>
	<div class="alert alert-danger">
		<?php echo $error_string;?>
	</div>
<?php endif;?>

<?php if(!empty($success_string)): ?>
	<div class="alert alert-success">
		<?php echo $success_string ?>
	</div>
<?php else: ?>
	
	<?php echo form_open('users/reset_pass', array('id'=>'reset-pass', 'class' => '')) ?>
	<fieldset>
		<legend>My AH Password Reminder</legend>
		<div class="form-group">
			<label class="control-label" for="email">Email or Username</label>
			<input class="form-control input-sm" type="text" name="email" maxlength="100" value="<?php echo set_value('email') ?>" />
			<span class="help-block">Enter your registered email/username address, and our system will automatically send you a email with instructions.</span>
		</div>
		<div class="form-group">
			<?php echo form_submit('', 'Request Password', 'class="btn btn-primary"') ?>
		</div>
	</fieldset>
	<?php echo form_close() ?>
	
<?php endif ?>