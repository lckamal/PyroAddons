<ul>
	<fieldset>
		<legend>General Settings</legend>
		<li class="<?php echo alternator('even', ''); ?>">
			<label for="captions">Slider</label>
			<?php echo form_dropdown('folder_id', $folder_options, $options['folder_id']); ?>
		</li>
		<li class="<?php echo alternator('even', ''); ?>">
			<label for="captions">Captions</label>
			<?php echo form_dropdown('captions', array('true' => 'On', 'false' => 'Off',), $options['captions']); ?>
		</li>
	</fieldset>
</ul>