<section class="title">
	<h4><?php echo lang('contact:message_details') ?></h4>
</section>

<section class="item">

	<div class="content form_inputs">
		<fieldset>
		<ul>
			<li class="<?php echo alternator('even', '') ?>">
				<label><?php echo lang('global:email') ?></label>
				<div class="input"><?php echo $message->email ?></div>
			</li>
			<li class="<?php echo alternator('even', '') ?>">
				<label><?php echo lang('contact:subject') ?></label>
				<div class="input"><?php echo $message->subject ?></div>
			</li>
			<li class="<?php echo alternator('even', '') ?>">
				<label><?php echo lang('contact:sent_at') ?></label>
				<div class="input"><?php echo format_date($message->sent_at,'d/m/Y H:i:s') ?></div>
			</li>
			<li class="<?php echo alternator('even', '') ?>">
				<label><?php echo lang('contact:attachments') ?></label>
				<div class="input"><?php echo $message->attachments ?></div>
			</li>
			<li class="<?php echo alternator('even', '') ?>">
				<label><?php echo lang('contact:message') ?></label>
				<div class="input"><?php echo $message->message ?></div>
			</li>
			<li class="<?php echo alternator('even', '') ?>">
				<label><?php echo lang('contact:sender_agent') ?></label>
				<div class="input"><?php echo $message->sender_agent ?></div>
			</li>
			<li class="<?php echo alternator('even', '') ?>">
				<label><?php echo lang('contact:sender_ip') ?></label>
				<div class="input"><?php echo $message->sender_ip ?></div>
			</li>
			<li class="<?php echo alternator('even', '') ?>">
				<label><?php echo lang('contact:sender_os') ?></label>
				<div class="input"><?php echo $message->sender_os ?></div>
			</li>
		</ul>
		</fieldset>
		
		<div class="buttons">
			<?php echo anchor('admin/contact',lang('contact:back_to_list'),'class="button primary"') ?>
		</div>

	</div><!-- .content -->
	
</section><!-- .item -->