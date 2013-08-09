<?php
$fields=array('subject','body','template_id','id');
foreach($fields as $field)
{
	if(set_value($field))
		$$field=set_value($field);
	elseif(isset($newsletter))
	{
		$action='Edit';
		foreach($newsletter as $mail){$$field=$mail->$field;}
	}
	else
		$$field='';
		$action='Add';
}
?>
<section class="title">
    <h4><?php echo lang('newsletters.add_title'); ?></h4>
</section>

<section class="item">
	<div class="content">
    <?php echo form_open_multipart($this->uri->uri_string(), 'class="crud"'); ?>
	   <div class="form_inputs">
			<ul>
				<li class="<?php echo alternator('', 'even'); ?>">
					<label for="subject"><?php echo lang('newsletters_subject_label');?> <span>*</span></label>
					<div class="input"><?php echo form_input('subject', set_value('subject', isset($subject) ? $subject : ''), 'class="width-30"'); ?></div>
				</li>
				
				<li class="<?php echo alternator('', 'even'); ?>">
				    <label for="template">Template:</label>
				    <div class="input"><?=form_dropdown('template_id',$templates,$template_id)?><?php echo anchor(site_url('admin/templates/create'), 'Add Template','class="button"')?></div>
				</li>
				
				<li class="<?php echo alternator('', 'even'); ?> full_width_input">
                    <label for="body">Message:</label>
					<?php echo form_textarea(array('id'=>'body', 'name'=>'body', 'value' => $body, 'rows' => 40, 'class'=>'wysiwyg-advanced')); ?>
				</li>
			</ul>
			
        <div class="buttons">
			<?php $this->load->view('admin/partials/buttons', array('buttons' => array('save', 'cancel') )); ?>
	    </div>
	   </div>
	   </div>
    <?php echo form_close(); ?>
</section>