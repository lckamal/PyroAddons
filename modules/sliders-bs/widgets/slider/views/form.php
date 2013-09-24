<ol>
	<fieldset>
		<legend>General Settings</legend>

		<li class="<?php echo alternator('even', ''); ?>">
			<label for="captions">Captions</label>
			<?php echo form_dropdown('captions', array('true' => 'On', 'false' => 'Off',), $options['captions']); ?>
		</li>

	</fieldset>
</ol>