<section class="title">
	<h4><?php echo lang('choice:choices'); ?> &raquo; <?php echo $this->fields->translate_label($field->field_name); ?></h4>
</section>
<section class="item">
	<div class="content">
		<div class="clearfix">
			<a href="<?php echo site_url('admin/choice/create/'.$field->field_slug); ?>" class="btn blue alignright" style="margin-bottom: 10px;">New Choice</a>
		</div>
		<?php if(count($field_choices) > 0):?>
			<?php echo form_open(current_url()); ?>
		<table>
			<tr>
				<th></th>
				<th>ID</th>
				<th>Title</th>
				<th></th>
			</tr>
			<?php foreach($field_choices as $choice): ?>
			<tr>
			<td width="30" class="handle"><?php echo Asset::img('icons/drag_handle.gif', 'Drag Handle'); ?></td>
				<td><?php echo $choice->choice_id; ?></td>
				<td>
					<input type="hidden" name="action_to[]" value="<?php echo $choice->choice_id;?>" />
					<?php echo $choice->choice_title; ?>
					</td>
				<td>
					<a class="button" href="<?php echo site_url('admin/choice/edit/'.$choice->field_slug.'/'.$choice->choice_id);?>">Edit</a>
					<a class="button confirm" href="<?php echo site_url('admin/choice/delete/'.$choice->field_slug.'/'.$choice->choice_id);?>">Delete</a>
				</td>
			</tr>
			<?php endforeach; ?>
		</table>
		<?php echo form_close(); //echo $choices['pagination']; ?>
		<?php else: ?>
			<div class="no_data"><?php echo lang('choice:no_choices'); ?></div>
		<?php endif;?>
	</div>
</section>