<section class="title">
	<h4>Create Stream</h4>
</section>

<section class="item">
<div class="content">
	<?php echo form_open_multipart($this->uri->uri_string(), 'class="crud"'); ?>
		<div class="form_inputs">
		<ul>
			<li>
				<label for="view_options">View Options</label>
				<div class="input">
					<?php echo form_multiselect('view_options[]', $field_options); ?>
				</div>
			</li>
		</ul>
		
		</div>
		
		<div class="buttons">
			<?php $this->load->view('admin/partials/buttons', array('buttons' => array('save', 'cancel') )); ?>
		</div>
		
	<?php echo form_close(); ?>
</div>
</section>