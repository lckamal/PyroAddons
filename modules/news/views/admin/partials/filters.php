<fieldset id="filters">
	
	<legend><?php echo lang('global:filters'); ?></legend>
	
	<?php echo form_open('admin/news/ajax_filter'); ?>

	<?php echo form_hidden('f_module', $module_details['slug']); ?>
		<ul>  
			<li>
        		<?php echo lang('news_status_label', 'f_status'); ?>
        		<?php echo form_dropdown('f_status', array(0 => lang('global:select-all'), 'draft'=>lang('news_draft_label'), 'live'=>lang('news_live_label'))); ?>
    		</li>
		
			<li>
        		<?php echo lang('news_category_label', 'f_category'); ?>
        		<?php echo form_dropdown('f_category', array(0 => lang('global:select-all')) + $categories); ?>
    		</li>
			<div class="buttons">
            <?php $this->load->view('admin/partials/buttons', array('buttons' => array('cancel') )); ?>
        </div>
			<!-- <li><?php echo form_input('f_keywords'); ?></li>
			<li><?php echo anchor(current_url() . '#', lang('buttons.cancel'), 'class="cancel"'); ?></li> -->
		</ul>
	<?php echo form_close(); ?>
</fieldset>