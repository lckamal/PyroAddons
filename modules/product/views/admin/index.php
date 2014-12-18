
<section class="title">
	<h4><?php echo lang('advertisement:advertisements'); ?></h4>
</section>

<?php
	if ($this->session->flashdata('status') != '')
	{ 
    echo $this->session->flashdata('status'); 
	}
?>

<section class="item">
	
	<?php if ($advertisements['total'] > 0 ): ?>
	
		<table>
			<thead>
				<tr>
					<th><?php echo lang('advertisement:question'); ?></th>
					<th><?php echo lang('advertisement:answer'); ?></th>
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
				<?php foreach($advertisements['entries'] as $advertisement ): ?>
				<tr>
					<td><?php echo $advertisement['question']; ?></td>
					<td><?php echo $advertisement['answer']; ?></td>
					<td><?php echo anchor('admin/advertisement/edit/' . $advertisement['id'], lang('global:edit'), 'class="btn orange edit"'); ?>
                                            <?php echo anchor('admin/advertisement/delete/' . $advertisement['id'], lang('global:delete'), array('class' => 'confirm btn red delete')); ?>
                                        </td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		
		<?php echo $advertisements['pagination']; ?>
		
	<?php else: ?>
		<div class="no_data"><?php echo lang('advertisement:no_advertisements'); ?></div>
	<?php endif;?>
	
</section>