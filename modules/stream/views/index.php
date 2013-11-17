<section class="title">
	<h4><?php echo lang('partner:partners'); ?></h4>
</section>

<section class="item">
<div class="content">
    <?php if ($partners['total'] > 0): ?>
	<table>
		<thead>
			<tr>
				<th>Name</th>
				<th>Icon</th>
				<th>About Partner</th>
                <th>Website</th>
                <th>Status</th>
                <th></th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="7">
					<div class="inner"><?php echo $partners['pagination']; ?></div>
				</td>
			</tr>
		</tfoot>
		<tbody>
			<?php foreach($partners['entries'] as $item): ?>
			<tr>
				<td><?php echo $item['name']?></td>
                <td><img src="files/thumb/<?php echo $item['icon']?>/80/80" height="80" /></td>
                <td><?php echo $item['description']?></td>
                <td><?php echo $item['web']?></td>
                <td><?php echo $item['status'] ? '<p style="color:green">Active</p>' : '<p style="color:red">Inactive</p>'?></td>
                <td class="actions">
                	<?php echo anchor('admin/partner/edit/' . $item['id'], lang('global:edit'), array('class'=>'btn blue')); ?>
					<?php echo anchor('admin/partner/delete/' . $item['id'], lang('global:delete'), array('class'=>'confirm btn red delete')); ?>	
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>	
	<?php else: ?>
		<div class="no_data"><?php echo lang('partner:no_partners'); ?></div>
	<?php endif;?>
</div>
</section>