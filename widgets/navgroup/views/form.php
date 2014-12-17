<ul>
	<fieldset>
		<legend>General Settings</legend>
		<li class="<?php echo alternator('even', ''); ?>">
			<label for="captions">Navigation group</label>
			<?php echo form_dropdown('navigation_group', $nav_options, $options['navigation_group']); ?>
		</li>
		<li class="<?php echo alternator('even', ''); ?>">
			<label for="captions">Limit</label>
			<?php echo form_input('limit', $options['limit']); ?>
		</li>
		<li class="<?php echo alternator('even', ''); ?>">
			<label for="captions">Row Class</label>
			<?php echo form_input('row_class', $options['row_class']); ?>
		</li>
		<li class="<?php echo alternator('even', ''); ?>">
			<label for="captions">Column class</label>
			<?php echo form_input('col_class', $options['col_class']); ?>
		</li>
	</fieldset>
</ul>