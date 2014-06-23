<?php if(count($images) != 0): ?>
<div class="flexslider">
    <ul class="slides">
        <?php foreach($images as $image): ?>
                    <li><img src="<?php echo $image->path; ?>" <?php echo ($options['captions'] === 'true') ? 'title="'.$image->name.'"' : null; ?> />
                    <?php if( ! empty($image->alt_attribute) && ! empty($image->description)):?>
	                    <div class="container">
		                    <div id="slide_info">
		                        <h1><?php echo $image->alt_attribute; ?></h1>
		                        <p><?php echo $image->description; ?> &raquo;</p>
		                        <?php //var_dump($image);?>
		                    </div>
	                    </div>
                    <?php endif; ?>
                    </li>
            <?php endforeach; ?>
            
        <!-- <li>{{ theme:image file="responsive.jpg" }}</li>
        <li>{{ theme:image file="backgrounds.jpg" }}</li> -->
    </ul>
</div>
<?php endif; ?>
<br style="clear:both;">