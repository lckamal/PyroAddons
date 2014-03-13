<h2><?php echo lang('galleries.galleries_label'); ?></h2>

<div class="row" id="gallery_index">
	
	<?php if ( ! empty($galleries)): foreach ($galleries as $gallery): if (empty($gallery->parent)): ?>
	<div class="col-3">
		<?php if ( ! empty($gallery->filename)): ?>
			<a class="thumbnail" href="<?php echo site_url('galleries/'.$gallery->slug); ?>">
				<?php echo img(array('src' => site_url('files/thumb/'.$gallery->file_id.'/220/150'), 'alt' => $gallery->title)); ?>
			</a>
			<center><?php echo anchor('galleries/' . $gallery->slug, $gallery->title); ?></center>
		<?php endif; ?>
	</div>
		<!-- <div class="col-3">
			
			<p><?php echo anchor('galleries/' . $gallery->slug, $gallery->title); ?></p>
		</div> -->
		
	<?php endif; endforeach;
		echo $this->pagination->create_links();
	 else: ?>
		
	<p><?php echo lang('galleries.no_galleries_error'); ?></p>
	
	<?php endif; ?>
</div>