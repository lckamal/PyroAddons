<?php echo form_open("comments/create/{$module}", 'role="form" id="create-comment"') ?>

	<noscript><?php echo form_input('d0ntf1llth1s1n', '', 'style="display:none"') ?></noscript>

	<?php echo form_hidden('entry', $entry_hash) ?>
	
	<fieldset>
		<legend><h4><?php echo lang('comments:your_comment') ?></h4></legend>

	<?php if ( ! is_logged_in()): ?>

			<div class="form_name form-group">
				<label for="name"><?php echo lang('comments:name_label') ?><span class="required">*</span></label>
				<input type="text" class="form-control" name="name" id="name" maxlength="40" value="<?php echo $comment['name'] ?>">
			</div>

			<div class="form_email form-group">
				<label for="email"><?php echo lang('global:email') ?><span class="required">*</span></label>
				<input type="text" class="form-control" name="email" maxlength="40" value="<?php echo $comment['email'] ?>">
			</div>

			<div class="form_url form-group">
				<label for="website"><?php echo lang('comments:website_label') ?></label>
				<input type="text" class="form-control" name="website" maxlength="40" value="<?php echo $comment['website'] ?>">
			</div>

	<?php endif ?>

	<div class="form_textarea form-group">
		<label for="comment"><?php echo lang('comments:message_label') ?><span class="required">*</span></label>
		<textarea name="comment" id="comment" rows="5" cols="30" class="form-control width-full"><?php echo $comment['comment'] ?></textarea>
	</div>

	<div class="form_submit">
		<?php echo form_submit('submit', lang('comments:send_label'), 'class="btn btn-default"') ?>
	</div>

	</fieldset>

<?php echo form_close() ?>