<?php if(count($images) != 0): ?>
<?php 
Asset::add_path('slider', 'addons/shared_addons/modules/sliders/');
Asset::js('slider::jquery.flexslider-min.js');
Asset::css('slider::flexslider.css');
echo Asset::render_js();
echo Asset::render_css();
?>
<script type="text/javascript">
    $(window).load(function() {
      $('.flexslider').flexslider({
        animation: "slide"
      });
    });
</script>
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
    </ul>
</div>
<?php endif; ?>
<br style="clear:both;">