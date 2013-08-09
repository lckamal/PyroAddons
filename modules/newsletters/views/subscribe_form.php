<h2><?php echo lang('letter_letter_title');?></h2>
<p><?php echo lang('letter_subscripe_desc');?></p>
<?php echo form_open('newsletters/subscribe'); ?>
<ul>
	<li>
		<label for="email"><?php echo lang('letter_email_label');?>:</label>
		<?php echo form_input('email', set_value('email'), 'id="email"')?>
		<?php echo form_error('email', '<span class="error">', '</span>');?>
	</li>
	
	<li>
		<label for="name">Name:</label>
		<?php echo form_input('name', set_value('name'), 'id="name"')?>
		<?php echo form_error('name', '<span class="error">', '</span>');?>
	</li>
	
	<?php if(isset($groups)): ?>
	<li>
	    <label style="clear:both;display:block" for="name">Newsletter Groups:</label>
	<?php foreach($groups as $group): ?>
			<?php echo form_label(htmlentities($group->group_name),'group_'.$group->id); ?>
			<input id="group_<?php echo $group->id?>" type="checkbox" name="group[]" value="<?php echo $group->id?>" <?php echo set_checkbox('group[]', $group->id); ?> />
	<?php endforeach; ?>
	</li>
	<?php echo form_error('group[]', '<span class="error">', '</span>');?>
	<?php endif; ?>
		
	<li><?php echo form_submit('btnSubscribe', lang('newsletters.subscribe')) ?></li>
</ul>
<?php echo form_close(); ?>