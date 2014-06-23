
<?foreach($newsletters as $mail):?>
<table class="data">
<thead>
	<tr class="top">
		<th colspan="2">View Message</th>
	</tr>
<?if($this->input->post('delete')==$mail->id):?>
	<tr>
		<td colspan="2">
			<div class="notice">
			<p><strong>Are you sure you want to delete this message?</strong></p>
			<?=form_open(ROOT.'newsletters/do_action/move_to_trash/'.$mail->id,'style="float:left"')?>
			<input type="hidden" name="__redirect" value="newsletters" />
			<input type="submit" value="Delete" />
			<?=form_close()?>
			<?=form_open(ROOT.'newsletters')?>
			<input type="submit" value="Cancel" />
			<?=form_close()?>
			</div>
		</td>
	</tr>
<?endif?>
	<tr>
		<th>
			<span style="font-size:140%"><strong>Subject:</strong> <?=clean_str($mail->subject)?></span>
		</td>
		<th>
			<form class="right" action="<?=ROOT?>newsletters/do_action/send_mail/<?=$mail->id?>" method="post">
			<input type="hidden" name="preview" value="true" />
			<input type="submit" value="Send me a preview" />
			</form>
			<form class="right" action="<?=ROOT?>newsletters/edit_newsletter/<?=$mail->id?>" method="post">
			<input type="submit" value="Edit this email" />
			</form>
		</th>
	</tr>
</thead>
<tbody>
	<tr>
		<td colspan="2">


				<iframe width="100%" height="300px" src="<?=ROOT.'newsletters/view/'.$mail->id?>"></iframe>


		</td>
	</tr>
</tbody>
</table>
<?if(!$this->input->post('delete')):?>
<form id="send_newsletter" action="<?=ROOT?>newsletters/do_action/send_mail/<?=$mail->id?>" method="post">
	<table class="data">
	<thead>
		<tr class="top">
			<th colspan="2">Send Message</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>
				<fieldset>
				<legend>Newsletter Recipient Groups</legend>
				<h4>Please select the groups to send this message to.</h4>
				<?if($mail->date_sent != '0000-00-00 00:00:00'):?>
				<p class="notice message"><strong>Note:</strong> This message has been sent one or more times.</p>
				<?endif?>
			<?foreach($groups as $group):?>
				<label class="checkbox"<?if(!empty($group->group_description)){echo ' title="'.clean_str($group->group_description).'"';}?>>
				<input type="checkbox" name="group[]" value="<?=$group->id?>" />
				<strong><?=clean_str($group->group_name)?></strong>
				 - (<em><?=$this->newsletters->count_users($group->id)?> Users</em>)
				</label><br />
			<?endforeach?>
				</fieldset>
			</td>
			<td>
				<h4>You may add as many additional recipients as you like. Please provide a comma or line separated list of email addresses to send this mail to. Each user will be emailed separately.</h4>
				<textarea name="additional_recipients"></textarea>
			</td>
		</tr>
	</tbody>
	<tfoot>
		<tr>
			<td colspan="2">
				<input style="float:right" type="submit" value="Send Mail!" />
			</td>
		</tr>
	</tfoot>
</table>
</form>
<?endif?>


	


<?endforeach?>