<fieldset id="filters">
	
	<legend><?php echo lang('global:filters'); ?></legend>
	
	<?php echo form_open('admin/calendar/ajax_filter'); ?>

	<?php echo form_hidden('f_module', $module_details['slug']); ?>
		<ul>  
		
			<li>
        		<?php echo lang('calendar_title_label'); ?> :<?php echo form_input('f_title'); ?>
    		</li>
			
			<li style="display:none;"><?php echo lang('calendar_date_start_label'); ?> :<?php echo form_input('f_date', '', 'id="datepicker"'); ?></li>
			<li><?php echo anchor(current_url() . '#', lang('calendar_btn_cancel'), 'class="cancel"'); ?></li>
		</ul>
	<?php echo form_close(); ?>
</fieldset>
