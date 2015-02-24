<h1><?php echo lang('user:register_header') ?></h1>

<div class="row">
	<div class="col-sm-8 col-sm-offset-2">

<?php if ( ! empty($error_string)):?>
<!-- Woops... -->
<div class="alert alert-danger">
	<?php echo $error_string;?>
</div>
<?php endif;?>

<?php echo form_open('register', array('id' => 'register'), 'role="form"') ?>

	
	<?php if ( ! Settings::get('auto_username')): ?>
	<div class="form-group">
		<label for="username"><?php echo lang('user:username') ?></label>
		<input type="text" name="username" maxlength="100" value="<?php echo $_user->username ?>" class="form-control">
	</div>
	<?php endif ?>
	
	<div class="form-group">
		<label for="email"><?php echo lang('global:email') ?></label>
		<input type="text" name="email" maxlength="100" value="<?php echo $_user->email ?>" class="form-control">
		<?php echo form_input('d0ntf1llth1s1n', ' ', 'class="default-form" style="display:none"') ?>
	</div>
	
	<div class="form-group">
		<label for="password"><?php echo lang('global:password') ?></label>
		<input type="password" name="password" maxlength="100" class="form-control">
	</div>
	
	<div id="profile-fields">

		<?php foreach($profile_fields as $field): ?>

			<?php if($field['required'] and $field['field_slug'] != 'display_name'): ?>
				<div class="form-group">
					<label for="<?php echo $field['field_slug'] ?>"><?php echo (lang($field['field_name'])) ? lang($field['field_name']) : $field['field_name'];  ?></label>
					<?php echo $field['input'] ?>
				</div>
			<?php endif; ?>
			
		<?php endforeach; ?>

	</div>

	<?php echo form_submit('btnSubmit', lang('user:register_btn'), 'class="btn btn-default"') ?>

<?php echo form_close() ?>

</div>
</div>