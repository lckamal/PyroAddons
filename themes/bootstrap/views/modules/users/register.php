<?php if ( ! empty($error_string)):?>
<!-- Woops... -->
<div class="alert alert-danger">
	<?php echo $error_string;?>
</div>
<?php endif;?>

<?php echo form_open('register', array('id' => 'register', 'class' => 'form-horizontal')) ?>
<fieldset>
	<legend><?php echo lang('user:register_header') ?></legend>
	<?php foreach($profile_fields as $field) { if($field['required'] and $field['field_slug'] != 'display_name') { ?>
	<div class="form-group">
		<label class="col-lg-3 control-label" for="<?php echo $field['field_slug'] ?>"><?php echo (lang($field['field_name'])) ? lang($field['field_name']) : $field['field_name'];  ?> <?php echo $field['required'] ? '<span class="text text-danger">*</span>' : ''; ?></label>
		<div class="col-lg-6 add-control-css"><?php echo $field['input'] ?></div>
	</div>
	<?php } } ?>
	
	<?php if ( ! Settings::get('auto_username')): ?>
	<div class="form-group">
		<label class="col-lg-3 control-label" for="username"><?php echo lang('user:username') ?><span class="text text-danger">*</span></label>
		<div class="col-lg-6 add-control-css">
			<input type="text" name="username" maxlength="100" value="<?php echo $_user->username ?>" />
		</div>
	</div>
	<?php endif ?>
	
	<div class="form-group">
		<label class="col-lg-3 control-label" for="email"><?php echo lang('global:email') ?><span class="text text-danger">*</span></label>
		<div class="col-lg-6 add-control-css">
			<input type="text" id="email" name="email" maxlength="100" value="<?php echo $_user->email ?>" />
			<?php echo form_input('d0ntf1llth1s1n', ' ', 'class="default-form" style="display:none"') ?>
		</div>
	</div>
	
	<div class="form-group">
		<label class="col-lg-3 control-label" for="password"><?php echo lang('global:password') ?><span class="text text-danger">*</span></label>
		<div class="col-lg-6 add-control-css">
			<input type="password" name="password" id="password" maxlength="100" class="form-control input-sm" />
		</div>
	</div>
		
	<div>
		<div class="col-lg-4 col-lg-offset-3">
			<?php echo form_submit('btnSubmit', lang('user:register_btn'), 'class="btn btn-primary" id="submit"') ?>
		</div>
	</div>
</fieldset>
<?php echo form_close() ?>
<script type="text/javascript">
	$(function(){
		// $("#email_confirm").blur(function(){
			// var email_confirm = $(this).val();
			// var $msg = $(this).next('span');
			 // if(email_confirm == ''){
			 	// $msg.html('<p class="text-danger">Email field cannot be blank.</p>');
			 // }
		     // else if(email_confirm != $("#email").val()){
               // $msg.html('<p class="text-danger">Email does not match.</p>');
		     // }
		     // else{
		     	// $msg.html('<p class="text-success">Email matched</p>');
		     // }
		// });
// 		
		// $("#submit").on('click', function(e){
// 			
			// var email_confirm = $("#email_confirm").val();
			// if(email_confirm == ''){
				// alert('Email is required.');
				// return false;
			// }
		// });
		$('.add-control-css input[type="text"], .add-control-css textarea, .add-control-css select').addClass('form-control input-sm');
	});
</script>