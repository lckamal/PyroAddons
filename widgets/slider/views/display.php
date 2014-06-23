<?php if(count($images) > 0): ?>

<?php 
Asset::add_path('slider', 'addons/shared_addons/widgets/slider/');
Asset::css('slider::flexslider.css', false, 'flexslider');
Asset::js('slider::jquery.flexslider-min.js', false, 'flexslider');
echo Asset::render_js('flexslider');
echo Asset::render_css('flexslider');
?>
<script type="text/javascript">
$(function(){
    if($.isFunction($.fn.flexslider)){
      $('.myflexslider').flexslider({
        animation: "slide"
      });
    }
    });
</script>
        <div class="myflexslider">
          <ul class="slides">
        <?php foreach($images as $image): ?>
                <li><img src="<?php echo $image->path; ?>" <?php echo ($options['captions'] === 'true') ? 'title="'.$image->name.'"' : null; ?> />
                <?php if( ! empty($image->alt_attribute) && ! empty($image->description)):?>
                    <div class="container_fix">
	                    <div class="slide_info">
	                        <h1><?php echo $image->alt_attribute; ?></h1>
	                        <p><?php echo $image->description; ?> &raquo;</p>
	                    </div>
                    </div>
                <?php endif; ?>
                </li>
        <?php endforeach; ?>
    </ul>
</div>
<?php endif; ?>
<div class="clearfix"></div>