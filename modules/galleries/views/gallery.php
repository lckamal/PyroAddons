<div class="container_12">
      <div class="grid_12">
        <h2><?php echo $gallery->title; ?>
        <a class="btn btn-danger btn-sm pull-right" href="{{ url:site }}galleries"><i class="fa fa-long-arrow-left fa-lg"></i> Back to galleries</a>
        </h2>
      </div>

      <div class="clear"></div>
	<div class="row">
		<!-- A gallery needs a description.. -->
		<div class="gallery_heading">
			<p><?php echo $gallery->description; ?></p>
		</div>
		<!-- The list containing the gallery images -->
			<?php if ($gallery_images): ?>
			<?php foreach ( $gallery_images as $image): ?>
			<div class="col-4" style="margin-bottom:40px;">
				<a href="<?php echo site_url('files/large/'.$image->file_id); ?>" class="fancybox thumbnail" rel="gallery-image" title="<?php echo $image->name; ?>">
					<?php echo img(array('src' => site_url('files/thumb/'.$image->file_id.'/300/300'), 'alt' => $image->name, 'width' => 300)); ?>
				</a>
			</div>
			<?php endforeach; ?>
			<?php endif; ?>
	</div>
<br style="clear: both;" />
<?php if ( ! empty($sub_galleries) ): ?>
<h2><?php echo lang('galleries.sub-galleries_label'); ?></h2>
<!-- Show all sub-galleries -->
<div class="sub_galleries_container">
	<?php foreach ($sub_galleries as $sub_gallery): ?>
	<div class="gallery clearfix">
		<!-- Heading -->
		<div class="gallery_heading">
			<?php if ( ! empty($sub_gallery->filename)) : ?>
			<a href="<?php echo site_url('galleries/'.$sub_gallery->slug); ?>">
				<?php echo img(array('src' => site_url('files/thumb/'.$sub_gallery->file_id), 'alt' => $sub_gallery->title)); ?>
			</a>
			<?php endif; ?>
			<h3><?php echo anchor('galleries/' . $sub_gallery->slug, $sub_gallery->title); ?></h3>
		</div>
		<!-- And the body -->
		<div class="gallery_body">
			<p>
				<?php echo ( ! empty($sub_gallery->description)) ? $sub_gallery->description : lang('galleries.no_gallery_description'); ?>
			</p>
		</div>
	</div>
	<?php endforeach; ?>
</div>
<?php endif; ?>
<br style="clear: both;" />

<?php if ($gallery->enable_comments == 1): ?>
	<?php //echo display_comments($gallery->id);?>
<?php endif; ?>
  </div>