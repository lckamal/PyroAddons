<?php if(count($images) > 0): 

$nivotheme = isset($options['theme']) ? $options['theme'] : 'default';
$settings = isset($options['settings']) ? $options['settings'] : '';
Asset::add_path('nivoslider', 'addons/shared_addons/widgets/nivoslider/');
Asset::css('nivoslider::themes/'.$nivotheme.'/'.$nivotheme.'.css', false, 'nivoslider');
Asset::css('nivoslider::nivo-slider.css', false, 'nivoslider');
Asset::js('nivoslider::jquery.nivo.slider.pack.js', false, 'nivoslider');
echo Asset::render_js('nivoslider');
echo Asset::render_css('nivoslider');
?>
<script type="text/javascript">
$(function(){
    if($.isFunction($.fn.nivoSlider)){
      $('.nivoSlider').nivoSlider(<?php echo $settings;?>);
    }
    });
</script>
    <div class="slider-wrapper theme-<?php echo $nivotheme;?> clearfix">
        <div class="nivoSlider">
        <?php foreach($images as $image): ?>
            <img 
                src="<?php echo $image->path; ?>" 
                alt="<?php echo $image->alt_attribute;?>" 
                <?php echo ($options['captions'] === 'true') ? 'title="'.$image->description.'"' : null; ?> 
                data-thumb="<?php echo site_url('files/thumb/'.$image->id.'/100/100'); ?>"
            />
         <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>
<div class="clearfix"></div>