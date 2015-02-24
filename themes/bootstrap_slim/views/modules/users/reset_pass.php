<h1><?php echo lang('user:reset_password_title');?></h1>

<div class="row">
	<div class="col-sm-8 col-sm-offset-2">

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

			<?php echo form_open('users/reset_pass', array('id'=>'reset-pass')) ?>

			<div class="form-group">
				<label for="email"><?php echo lang('user:reset_instructions') ?></label>
				<input type="text" name="email" maxlength="100" value="<?php echo set_value('email') ?>" class="form-control">
			</div>

			<?php echo form_submit('', lang('user:reset_pass_btn'), 'class="btn btn-default"') ?>

			<?php echo form_close() ?>

		<?php endif ?>

	</div>
</div>