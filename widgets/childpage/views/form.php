<ul>
	<fieldset>
		<legend>General Settings</legend>
		<li class="<?php echo alternator('even', ''); ?>">
			<label for="captions">Select Parent page</label>
			<select name="page_id" >
				<?php echo $page_options;?>
			</select>
		</li>
		<li class="<?php echo alternator('even', ''); ?>">
			<label for="captions">Limit</label>
			<?php echo form_input('limit', $options['limit']); ?>
		</li>
		<li class="<?php echo alternator('even', ''); ?>">
			<label for="captions">Show Read more?</label>
			<?php echo form_dropdown('read_more', array('0' => 'No', '1' => 'Yes'), $options['read_more']); ?>
		</li>
		<li class="<?php echo alternator('even', ''); ?>">
			<label for="captions">Layout</label>
			<?php echo form_dropdown('layout', $layout_options, $options['layout']); ?>
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