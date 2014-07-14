<?php if(count($images) > 0): 
$settings = isset($options['settings']) ? $options['settings'] : '';
Asset::add_path('flexslider', 'addons/shared_addons/widgets/flexslider/');
Asset::css('flexslider::flexslider.css', false, 'flexslider');
Asset::js('flexslider::jquery.flexslider-min.js', false, 'flexslider');
echo Asset::render_js('flexslider');
echo Asset::render_css('flexslider');
?>
<script type="text/javascript">
$(function(){
    if($.isFunction($.fn.flexslider)){
      $('.myflexslider').flexslider(<?php echo $settings;?>);
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