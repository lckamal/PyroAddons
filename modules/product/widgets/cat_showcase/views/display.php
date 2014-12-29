{{ if showcase_widget }}
<?php Asset::add_path('showcase', 'addons/shared_addons/modules/product/widgets/cat_showcase/');?>
{{ asset:css file="showcase::logo_perspective.css" group="showcase" }}
{{ asset:js file="showcase::jquery-ui.1.9.2.js" group="showcase" }}
{{ asset:js file="showcase::jquery.ui.touch-punch.min.js" group="showcase" }}
{{ asset:js file="showcase::logo_perspective.js" group="showcase" }}
{{ asset:render_js group="showcase" }}
{{ asset:render_css group="showcase" }}

<script>
		$(function() {
			$('#logo_perspective_black').logo_perspective({
				skin: 'black',
				width: 1060,
				imageWidth:95,
				imageHeight:55,
				responsive:true,
				elementsHorizontalSpacing:110,
				elementsVerticalSpacing:0,
				showNavArrows:false,
				showBottomNav:false,
				border:1,
				borderColorOFF:'#cccccc',				
				autoPlay: 2,
				numberOfVisibleItems:8,
				borderColorON: '#4672a7'
			});		

		});
	</script>
	<div id="logo_perspective_black">
   		<div class="myloader"></div>
        <!-- CONTENT -->
        <ul class="logo_perspective_list">

		{{ showcase_widget }}
			{{ if category_icon }}
            	<li><a href="{{url:site}}product/cat/{{category_slug}}"><img src="{{url:site}}files/thumb/{{category_icon}}/100/60" alt="{{category_title}}" width="155" height="100" title="{{category_title}}" /></a></li>
            {{ else }}
            	<li><a href="{{url:site}}product/cat/{{category_slug}}"><img src="{{url:site}}files/thumb/{{category_image}}/100/60" alt="{{category_title}}" width="155" height="100" title="{{category_title}}" /></a></li>
			{{ endif }}
		{{ /showcase_widget }}
        </ul>              	
	</div>
	{{ endif }}

