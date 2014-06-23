<h2>{{ template:title }}</h2>
<div class="content">
    <?php if ($bookings['total'] > 0): ?>
	<table>
		<thead>
			<tr>
				<th>Booking Referance</th>
				<th>Gallery</th>
                <th></th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="7">
					<div class="inner"><?php echo $bookings['pagination']; ?></div>
				</td>
			</tr>
		</tfoot>
		<tbody>
			<?php foreach($bookings['entries'] as $item): ?>
			<tr>
                <td><?php echo $item['holiday'] . " [" . $item['booking_ref'] . "]"?></td>
                <td><?php echo ($item['gallery'] != NULL) ? $item['gallery']->title : ''?></td>
                <td class="actions">
                	<?php
                	if($item['gallery'] == NULL)
					{
						echo anchor('myaccess/galleries/create/' . $item['id'], lang('galleries:create_gallery'), array('class'=>'btn blue'));
					}
					else{
						echo anchor('myaccess/galleries/create/' . $item['id'], 'Upload&nbsp;/&nbsp;Edit', array('class'=>'btn green'));
					}
                	?>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>	
	<?php else: ?>
		<div class="no_data"><?php echo lang('booking:no_bookings'); ?></div>
	<?php endif;?>
</div>