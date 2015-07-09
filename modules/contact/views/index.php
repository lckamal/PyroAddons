<section class="title">
	<h4><?php echo lang('contact:messages_title') ?></h4>
</section>

<section class="item">

	<div class="content">
	
	<?php if ($contact_log) : ?>
		<table class="table-list" border="0" cellspacing="0">
			<thead>
				<tr>
					<th class="collapse">ID</th>
					<th class="collapse"><?php echo lang('global:email') ?></th>
					<th><?php echo lang('contact:subject') ?></th>
					<th class="collapse"><?php echo lang('contact:sent_at') ?></th>
					<th width="180"><?php echo lang('global:actions') ?></th>
				</tr>
			</thead>
			<tbody>
			<?php foreach ($contact_log as $message) : ?>
				<tr>
					<td class="collapse"><?php echo $message->id ?></td>
					<td class="collapse"><a href="mailto:<?php echo $message->email ?>"><?php echo $message->email ?></a></td>
					<td><?php echo $message->subject ?></td>
					<td class="collapse"><?php echo format_date($message->sent_at,"d/m/Y H:i") ?></td>
					<td>
                        <a href="<?php echo site_url('admin/contact/view/' . $message->id) ?>#" title="<?php echo lang('global:view')?>" class="button"><?php echo lang('global:view') ?></a>
						<a href="<?php echo site_url('admin/contact/delete/' . $message->id) ?>" title="<?php echo lang('global:delete')?>" class="button confirm"><?php echo lang('global:delete') ?></a>
					</td>
				</tr>
			<?php endforeach ?>
			</tbody>
		</table>
		
		<?php $this->load->view('admin/partials/pagination') ?>
		
	<?php else : ?>
		<div class="no_data"><?php echo lang('contact:no_messages') ?></div>
	<?php endif ?>


	</div><!-- .content -->
	
</section><!-- .item -->