<section class="title">
	<h4><?php echo lang('category:category'); ?></h4>
</section>
<section class="item">
	<div class="content">
		<?php if($categories['total'] > 0):?>
		<table>
			<tr>
				<th>Category Title</th>
				<th>Status</th>
				<th></th>
			</tr>
			<?php foreach($categories['entries'] as $category): ?>
			<tr>
				<td><?php echo $category['category_title']; ?></td>
				<td><?php echo $category['category_status']['value']; ?></td>
				<td>
					<a class="button" href="<?php echo site_url('admin/category/lang/'.$category['id']);?>">Edit</a>
					<a class="button confirm" href="<?php echo site_url('admin/category/delete/'.$category['id']);?>">Delete</a>
				</td>
			</tr>
			<?php endforeach; ?>
		</table>
		<?php echo $categories['pagination']; ?>
		<?php else: ?>
			<div class="no_data"><?php echo lang('category:no_categories'); ?></div>
		<?php endif;?>
	</div>
</section>