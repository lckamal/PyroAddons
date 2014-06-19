{{ if sliders.total > 0}}
<?php Asset::add_path('nivoslider', 'addons/shared_addons/modules/slider/widgets/nivoslider/');?>
{{ asset:css file="nivoslider::themes/{{options.theme}}/{{options.theme}}.css" group="nivoslider" }}
{{ asset:css file="nivoslider::nivo-slider.css" group="nivoslider" }}
{{ asset:js file="nivoslider::jquery.nivo.slider.pack.js" group="nivoslider" }}
{{ asset:render_js group="nivoslider" }}
{{ asset:render_css group="nivoslider" }}
<script type="text/javascript">
$(function(){
    if($.isFunction($.fn.nivoSlider)){
      $('.nivoSlider').nivoSlider({{options.settings}});
    }
    });
</script>
    <div class="slider-wrapper theme-{{options.theme}} clearfix">
        <div class="nivoSlider">
        {{ sliders.entries }}
            <img 
                src="{{image.image}}" 
                alt="{{caption}}" 
                {{ if options.caption == 'true' }}
                title="{{caption}}"  
                {{ endif }}
                data-thumb="{{image.thumb}}"
            />
         {{ /sliders.entries }}
        </div>
    </div>
<div class="clearfix"></div>
{{ endif }}