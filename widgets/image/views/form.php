<ul>
	<fieldset>
		<legend>General Settings</legend>
		<li class="<?php echo alternator('even', ''); ?>">
			<label for="captions">Image folder</label>
			<?php echo form_dropdown('folder_id', $folder_options, $options['folder_id']); ?>
		</li>
		<li class="<?php echo alternator('even', ''); ?>">
			<label for="captions">Captions</label>
			<?php echo form_dropdown('captions', array('true' => 'On', 'false' => 'Off',), $options['captions']); ?>
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