<input id="page-id" type="hidden" value="<?php echo $page->category_id ?>" />
<fieldset>
	<legend><?php echo lang('category:details') ?></legend>
	<p>
		<strong>ID:</strong> #<?php echo $page->category_id ?>
	</p>
	<p>
		<strong><?php echo lang('category:status') ?>:</strong> <?php echo lang("category:status_{$page->category_status}") ?>
	</p>
	<p>
		<strong><?php echo lang('category:title') ?>:</strong>
		<?php echo $page->category_title; ?>
	</p>
</fieldset>


<div class="buttons">
	<?php echo anchor('admin/category/lang?parent='.$page->category_id, lang('category:add_child'), 'class="button"');?>
	<?php //echo anchor('admin/category/duplicate/'.$page->category_id, lang('category:duplicate_label'), 'class="button"') ?>
	<?php echo anchor('admin/category/lang/'.$page->category_id, lang('global:edit'), 'class="button"') ?>
	<?php echo anchor('admin/category/delete/'.$page->category_id, lang('global:delete'), 'class="confirm button"') ?>
</div>