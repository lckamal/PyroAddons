	<table>
		<thead>
			<tr>
				<th width="20"><?php echo form_checkbox(array('name' => 'action_to_all', 'class' => 'check-all')); ?></th>
				<th><?php echo lang('calendar_title_label'); ?></th>
				<th><?php echo lang('calendar_date_label'); ?></th>
				<th width="150"><?php echo lang('calendar_written_by_label'); ?></th>
				<th width="150"></th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="5">
					<div class="inner"><?php $this->load->view('admin/partials/pagination'); ?></div>
				</td>
			</tr>
		</tfoot>
		<tbody>
            <?php foreach ($calendar as $post) : ?>
				<tr>
					<td><?php echo form_checkbox('action_to[]', $post->id_eventcal); ?></td>
					<td><?php echo $post->event_title; ?></td>
					<td><?php echo format_date($post->event_date_begin); ?></td>
					<td>
					<?php if (isset($post->display_name)): ?>
						<?php echo anchor('user/' . $post->user_id, $post->display_name, 'target="_blank"'); ?>
					<?php else: ?>
						<?php echo lang('calendar_author_unknown'); ?>
					<?php endif; ?>
					</td>
					<td>
						<?php echo anchor('admin/calendar/edit/' . $post->id_eventcal, lang('global:edit'), 'class="btn orange edit"'); ?>
						<?php echo anchor('admin/calendar/delete/' . $post->id_eventcal, lang('global:delete'), array('class'=>'confirm btn red delete')); ?>
					</td>
				</tr>
			<?php endforeach; ?>
        </tbody>
	</table>

	<div class="table_action_buttons">
		<?php $this->load->view('admin/partials/buttons', array('buttons' => array('delete'))); ?>
	</div>
