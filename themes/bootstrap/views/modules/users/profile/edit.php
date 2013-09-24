<h2 id="page_title" class="page-title">
	<?php echo ($this->current_user->id !== $_user->id) ?
					sprintf(lang('user:edit_title'), $_user->display_name) :
					lang('profile_edit') ?>
</h2>
<div>
	<?php if (validation_errors()):?>
	<div class="error-box">
		<?php echo validation_errors();?>
	</div>
	<?php endif;?>

	<?php echo form_open_multipart('', array('id'=>'user_edit', 'role' => 'form', 'class' => 'form-horizontal'));?>

	<fieldset id="profile_fields">
		<legend><?php echo lang('user:details_section') ?></legend>
			<div class="form-group clearfix">
				<label class="col-lg-3 control-label" for="display_name"><?php echo lang('profile_display_name') ?></label>
				<div class="col-lg-8">
				<?php echo form_input(array('name' => 'display_name', 'id' => 'display_name', 'value' => set_value('display_name', $display_name), 'class' => 'form-control input-sm')) ?>
				</div>
			</div>

			<?php foreach($profile_fields as $field): ?>
				<?php if($field['input']): ?>
					<div class="form-group clearfix">
						<label class="col-lg-3 control-label" for="<?php echo $field['field_slug'] ?>">
							<?php echo (lang($field['field_name'])) ? lang($field['field_name']) : $field['field_name'];  ?>
							<?php if ($field['required']) echo '<span>*</span>' ?>
						</label>						
						<div class="col-lg-8 add-control-css">
							<?php echo $field['input'] ?>
							<?php if($field['instructions']) echo '<span class="help-block">'.$field['instructions'].'</span>' ?>
						</div>
					</div>
				<?php endif ?>
			<?php endforeach ?>
	</fieldset>

	<fieldset id="user_names">
		<legend><?php echo lang('global:email') ?></legend>

			<div class="form-group clearfix">
				<label class="col-lg-3 control-label" for="email"><?php echo lang('global:email') ?></label>
				<div class="col-lg-8">
					<?php echo form_input('email', $_user->email, ' class="form-control input-sm"') ?>
				</div>
			</div>
		</ul>
	</fieldset>

	<fieldset id="user_password">
		<legend><?php echo lang('user:password_section') ?></legend>
		
			<div class="form-group clearfix spacer-right">
				<label class="col-lg-3 control-label" for="password"><?php echo lang('global:password') ?></label>
				<div class="col-lg-8">
					<?php echo form_password('password', '', 'autocomplete="off" class="form-control input-sm"') ?>
				</div>
			</div>
		</ul>
	</fieldset>
	
	<script type="text/javascript">
	
	$(function(){
		$('.add-control-css input[type="text"], .add-control-css textarea, .add-control-css select').addClass('form-control input-sm');
		$('.add-control-css select#gender, select[name="lang"], select#country').attr('style','width:50%');
		$('select[name^="dob_"]').addClass('pull-left');
		$('select[name="dob_month"]').attr('style', 'width:100px');
		$('select[name="dob_day"]').attr('style', 'width:60px');
		$('select[name="dob_year"]').attr('style', 'width:80px');
	})
	</script>
	<?php if (Settings::get('api_enabled') and Settings::get('api_user_keys')): ?>
		
	<script type="text/javascript">
	
	jQuery(function($) {
		
		$('input#generate_api_key').click(function(){
			
			var url = "<?php echo site_url('api/ajax/generate_key') ?>",
				$button = $(this);
			
			$.post(url, function(data) {
				$button.prop('disabled', true);
				$('span#api_key').text(data.api_key).parent('li').show();
			}, 'json');
			
		});
		
	});
	</script>
		
	<fieldset>
		<legend><?php echo lang('profile_api_section') ?></legend>
		
			<div <?php $api_key or print('style="display:none"') ?>><?php echo sprintf(lang('api:key_message'), '<span id="api_key">'.$api_key.'</span>') ?></div>
			<div class="form-group clearfix">
				<input class="btn" type="button" id="generate_api_key" value="<?php echo lang('api:generate_key') ?>" />
			</div>
		</ul>
	
	</fieldset>
	<?php endif ?>
	<div class="row-fluid">
		<?php echo form_submit('', lang('profile_save_btn'), ' class="btn btn-primary col-lg-offset-3" ') ?>
	</div>
	
	<?php echo form_close() ?>
</div>