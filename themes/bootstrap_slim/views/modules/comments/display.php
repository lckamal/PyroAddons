<?php if ($comments): ?>
	
	<?php foreach ($comments as $item): ?>
		
		<div class="media well">
			<div class="pull-left">
				<?php echo gravatar($item->user_email, 60) ?>
			</div>
			<div class="media-body">
				<h4 class="media-heading"><?php echo $item->user_name ?></h4>
				<div class="date">
					<p><?php echo format_date($item->created_on) ?></p>
				</div>
				<?php if (Settings::get('comment_markdown') and $item->parsed): ?>
					<?php echo $item->parsed ?>
				<?php else: ?>
					<p><?php echo nl2br($item->comment) ?></p>
				<?php endif ?>
			</div>
		</div>

	<?php endforeach ?>
	
<?php else: ?>
	<p><?php echo lang('comments:no_comments') ?></p>
<?php endif ?>