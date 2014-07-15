<ol>
	<li class="even">
		<label>Number display from each category</label>
		<?php echo form_input('limit', $options['limit']) ?>
	</li>
	<li class="odd">
		<label>Categories</label>
		<?php echo form_multiselect('categories[]', $cat_options, $options['categories']) ?>
	</li>
	<li class="even">
		<label>Display Type</label>
		<?php echo form_dropdown('type', array('list' => 'List', 'scroll' => 'Scrolling text','notice' => 'Notice'), $options['type']) ?>
	</li>
</ol>