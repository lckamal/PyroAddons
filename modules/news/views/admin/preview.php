<h1><?php echo $post->title; ?></h1>

<p style="float:left; width: 40%;">
	<?php echo anchor('news/' .date('Y/m', $post->created_on) .'/'. $post->slug, NULL, 'target="_blank"'); ?>
</p>

<p style="float:right; width: 40%; text-align: right;">
	<?php echo anchor('admin/news/edit/'. $post->id, lang('global:edit'), ' target="_parent"'); ?>
</p>

<iframe src="<?php echo site_url('news/' .date('Y/m', $post->created_on) .'/'. $post->slug); ?>" width="99%" height="400"></iframe>