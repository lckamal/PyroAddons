<section class="title">
	<h4><?php echo lang('testimonials:testimonials'); ?></h4>
</section>

<section class="item">
	
	<?php if ($testimonials['total'] > 0 ): ?>
	
		<table>
			<thead>
				<tr>
					<th><?php echo lang('testimonials:company'); ?></th>
					<th><?php echo lang('testimonials:quote'); ?></th>
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
				<?php foreach($testimonials['entries'] as $testimonial ): ?>
				<tr>
					<td><?php echo $testimonial['company']; ?></td>
					<td><?php echo character_limiter($testimonial['quote'], 50); ?></td>
					<td><?php echo anchor('admin/testimonials/edit/' . $testimonial['id'], lang('global:edit'), 'class="btn orange edit"'); ?>
                                            <?php echo anchor('admin/testimonials/delete/' . $testimonial['id'], lang('global:delete'), array('class' => 'confirm btn red delete')); ?>
                                        </td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		
		<?php echo $testimonials['pagination']; ?>
		
	<?php else: ?>
		<div class="no_data"><?php echo lang('testimonials:no_testimonials'); ?></div>
	<?php endif;?>
	
</section>