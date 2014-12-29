<div class="row mb20">
	<div class="col-sm-8">
	{{ if category.category_icon }}
	<div class="cat-image">
		<img src="{{url:site}}files/thumb/{{category.category_icon}}/150/100" class="img-responsive mb20" alt="{{category.category_title}}" title="{{category.category_title}}" />
	</div>
	{{ else }}
	<h2 class="page-title">{{ category.category_title }}</h2>
	{{ endif }}
		{{category.category_desc}}
	</div>
	<div class="col-sm-4">
		{{ if category.category_image }}
		<img src="{{url:site}}/files/thumb/{{category.category_image}}/400/400" class="img-responsive" alt="category.category_title" />
		{{ endif }}
	</div>
</div>
<div class="row product-page">
<?php if(count($subcats)> 0):
	foreach ($subcats as $key => $subcat) : ?>
	<div class="col-sm-12 clearfix">
		<a href="<?php echo site_url('product/cat/'.$category->category_slug.'/'.$subcat->category_slug);?>">
		<?php if($subcat->category_image != null) :?>
			<img src="{{url:site}}files/large/<?php echo $subcat->category_image?>" class="img-responsive" alt="<?php echo $subcat->category_title?>" title="<?php echo $subcat->category_title?>" />
		<?php else: ?>
			<h3><?php echo $subcat->category_title;?></h3>
		<?php endif; ?>
		</a>
	</div>
<?php endforeach; 
endif;?>