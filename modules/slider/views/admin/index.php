<section class="title">
	<h4><?php echo lang('slider:sliders'); ?></h4>
</section>

<section class="item">
	
	<?php if ($sliders['total'] > 0 ): ?>
	
		<table>
			<thead>
				<tr>
					<th><?php echo lang('slider:question'); ?></th>
					<th><?php echo lang('slider:answer'); ?></th>
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
				<?php foreach($sliders['entries'] as $slider ): ?>
				<tr>
					<td><?php echo $slider['question']; ?></td>
					<td><?php echo $slider['answer']; ?></td>
					<td><?php echo anchor('admin/slider/edit/' . $slider['id'], lang('global:edit'), 'class="btn orange edit"'); ?>
                                            <?php echo anchor('admin/slider/delete/' . $slider['id'], lang('global:delete'), array('class' => 'confirm btn red delete')); ?>
                                        </td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		
		<?php echo $sliders['pagination']; ?>
		
	<?php else: ?>
		<div class="no_data"><?php echo lang('slider:no_sliders'); ?></div>
	<?php endif;?>
	
</section>