<?php if(count($img_images) > 0): ?>
<div class="<?php echo $options['row_class']; ?> clearfix">
    <?php foreach($img_images as $image): ?>
  <div class="<?php echo $options['col_class']; ?>">
        <img class="img-responsive" src="<?php echo $image->path; ?>" <?php echo ($options['captions'] === 'true') ? 'title="'.$image->name.'"' : null; ?> />
        <?php if( ! empty($image->alt_attribute) && ! empty($image->description)):?>
            <div class="caption">
                    <h1><?php echo $image->alt_attribute; ?></h1>
                    <p><?php echo $image->description; ?> &raquo;</p>
            </div>
        <?php endif; ?>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>
<div class="clearfix"></div>