<section class="title">
	<h4><?php echo lang('stream:streams'); ?></h4>
</section>

<section class="item">
<div class="content">
    <?php if ($streams['total'] > 0): ?>
	<table>
		<thead>
			<tr>
				<th>ID</th>
				<th>Stream Name</th>
				<th>Stream Slug</th>
                <th>Stream Namespace</th>
                <th>Stream Prefix</th>
                <th></th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="7">
					<div class="inner"><?php echo $streams['pagination']; ?></div>
				</td>
			</tr>
		</tfoot>
		<tbody>
			<?php foreach($streams['entries'] as $item): ?>
			<tr>
				<td><?php echo $item['id']?></td>
				<td><?php echo $item['stream_name']?></td>
				<td><?php echo $item['stream_slug']?></td>
				<td><?php echo $item['stream_namespace']?></td>
				<td><?php echo $item['stream_prefix']?></td>
                <td class="actions">
                	<?php echo anchor('admin/mystream/fields/index/' . $item['stream_slug'] .'/'. $item['stream_namespace'], lang('stream:fields'), array('class'=>'button blue')); ?>
                	<?php echo anchor('admin/mystream/edit/' . $item['id'] . '/' . $item['stream_slug'] .'/'. $item['stream_namespace'], lang('global:edit'), array('class'=>'button blue')); ?>
					<?php echo anchor('admin/mystream/delete/' . $item['id'] . '/' . $item['stream_slug'] .'/'. $item['stream_namespace'], lang('global:delete'), array('class'=>'confirm button red delete')); ?>
						
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<?php else: ?>
		<div class="no_data"><?php echo lang('stream:no_streams'); ?></div>
	<?php endif;?>
</div>
</section>