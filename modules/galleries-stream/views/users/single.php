<?php
	foreach( $gallery['entries'] as $g )
	{
		$name 	= $g['galleries_name'];
		$slug 	= $g['galleries_slug'];
		$text 	= $g['galleries_description'];
		
		$comments = $g['galleries_comments_enabled']['key'];
?>
<h1><?php echo $name;?></h1>
<p><?php echo $text; ?></p>
<?php	
		if( is_array($images) )
		{
			?>
			<div class="row">
			<?php foreach( $images as $image ) : ?>
				<div class="col-sm-3 col-xs-12">
					<a href="<?php echo site_url('files/large/'.$image->id); ?>" class="fancybox img-thumbnail" rel="gallery-image" title="<?php echo $image->name; ?>">
					<img class="img-responsive" src="<?php echo site_url('files/thumb/'.$image->id.'/300/300'); ?>" alt="<?php echo $image->name;?>">
					</a>
				</div>
			<?php endforeach;?>
			</div>
			<?php
		}
		else
		{
			echo "No images";
		}
		
		if( $comments == "yes" )
		{
?>
			<div id="comments">
				<div id="existing-comments">
					<h4><?php echo lang('comments:title') ?></h4>
					<?php echo $this->comments->display() ?>
				</div>
			
				
					<?php echo $this->comments->form() ?>
				
			</div>
<?php			
		}
	}
	
	
?>