<section class="title">
	<h4><?php echo lang('choice:choices'); ?></h4>
</section>
<section class="item">
	<div class="content">
		<?php if(count($choices) > 0):?>
		<table>
			<tr>
				<th>Field Name</th>
				<th>Field Slug</th>
				<th>Stream Namespace</th>
				<th></th>
			</tr>
			<?php foreach($choices as $choice): ?>
			<tr>
				<td><?php echo $this->fields->translate_label($choice['field_name']); ?></td>
				<td><?php echo $choice['field_slug']; ?></td>
				<td><?php echo $choice['field_namespace']; ?></td>
				<td>
					<a class="button" href="<?php echo site_url('admin/choice/view/'.$choice['field_slug']);?>">View Choices</a>
				</td>
			</tr>
			<?php endforeach; ?>
		</table>
		<?php //echo $choices['pagination']; ?>
		<?php else: ?>
			<div class="no_data"><?php echo lang('choice:no_choices'); ?></div>
		<?php endif;?>
	</div>
</section>