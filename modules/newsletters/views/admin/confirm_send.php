<section class="title">
	<h4>Send Mail</h4>				
</section>

<section class="item">
    
	<div class="content">	

<p><strong>Subject:</strong> <?=htmlentities($subject)?></span></p>
    <label>Message Body:</label>
    <div style="padding:10px; border:1px solid #ddd;"><?php echo $body?></div>
<?php echo form_open('admin/newsletters/mails/send_mail/'.$mail_id)?>

		<fieldset>
		<legend>Newsletter Recipient Groups</legend>
		<h4>Please select the groups to send this message to.</h4>
	

	<?foreach($groups as $group):?>
		<label class="checkbox"<?if(!empty($group->group_description)){echo ' title="'.htmlentities($group->group_description).'"';}?>>
		<input type="checkbox" name="group[]" value="<?=$group->id?>" />
		<strong><?=htmlentities($group->group_name)?></strong>
		 - (<em><?=$this->newsletters->count('recipients',$group->id)?> Users</em>)
		</label><br />
	<?endforeach?>

		<h4>You may add as many additional recipients as you like. Please provide a comma or line separated list of email addresses to send this mail to. Each user will be emailed separately.</h4>
		<textarea name="additional_recipients"></textarea>

		<input type="hidden" name="template_id" value="<?php echo $template_id; ?>" />
		<input style="float:right" type="submit" value="Send Mail!" />

				
<?php echo form_close();?>

</div>

</section>
	


