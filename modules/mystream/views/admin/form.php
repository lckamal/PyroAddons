<section class="title">
	<h4>Stream From</h4>
</section>

<section class="item">
<div class="content">
	<?php echo form_open_multipart($this->uri->uri_string(), 'class="crud"'); ?>
		<div class="form_inputs">
		<ul>
		<?php foreach($stream_fields as $field) { 
		    if(!in_array($field['field_slug'], $exclude_fields)) { ?>
		    <li class="<?php echo alternator('', 'even'); ?>">
		    	<label for="<?php echo $field['field_slug'] ?>">
				    <?php echo (lang($field['field_name'])) ? lang($field['field_name']) : $field['field_name'];  ?>
				    <?php echo $field['required'] ? '<span class="text-danger">*</span>' : '';?>
		    	</label>
		    	<div class="input">
		    		<?php
		    		if($field['field_slug'] == 'view_options')
		    		{
		    			echo form_multiselect('view_options[]', $field_options);
		    		} 
		    		else{
		    			echo $field['input'];
		    		}
		    		?>
		    	</div>
			</li>
		<?php } } ?>
		</ul>
		
		</div>
		
		<div class="buttons">
			<?php $this->load->view('admin/partials/buttons', array('buttons' => array('save', 'cancel') )); ?>
		</div>
		
	<?php echo form_close(); ?>
</div>
</section>