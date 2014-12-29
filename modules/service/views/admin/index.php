<section class="title">
	<h4><?php echo lang('service:service'); ?></h4>
</section>

<section class="item">
<div class="content">
    <?php if ($service['total'] > 0): ?>
	<table>
		<thead>
			<tr>
				<th>Name</th>
				<th>Status</th>
                <th>Featured</th>
                <th></th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="7">
					<div class="inner"><?php echo $service['pagination']; ?></div>
				</td>
			</tr>
		</tfoot>
		<tbody>
			<?php foreach($service['entries'] as $item): ?>
			<tr>
				<td><?php echo $item['name']?></td>
                <td><?php if($item['status']['key'] == 1) { echo '<p style="color:green">Active</p>'; } else { echo '<p style="color:red">Inactive</p>'; } ?></td>
                <td><?php if($item['featured']['key'] == 1) { echo '<p style="color:green">Yes</p>'; } else { echo '<p style="color:red">No</p>'; } ?></td>
                <td class="actions">
                	<?php echo anchor('admin/service/edit/' . $item['id'], lang('global:edit'), array('class'=>'button blue')); ?>
					<?php echo anchor('admin/service/delete/' . $item['id'], lang('global:delete'), array('class'=>'confirm button red delete')); ?>	
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<?php else: ?>
		<div class="no_data"><?php echo lang('service:no_service'); ?></div>
	<?php endif;?>
</div>
</section>