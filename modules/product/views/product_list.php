<!-- <div class="cat-desc">
	<?php if($category->category_icon != null) :?>
		<div class="cat-image">
			<img src="{{url:site}}files/thumb/<?php echo $category->category_icon?>/100/100" class="img-responsive" alt="<?php echo $category->category_title?>" title="<?php echo $category->category_title?>" />
		</div>
	<?php else: ?>
		<h2><?php echo $category->category_title; ?></h2>
	<?php endif; ?>

	<?php if($category->category_desc != null): ?>
		<p><?php echo $category->category_desc;?></p>
	<?php endif; ?>
</div> -->
<div class="clearfix mb10">
	<a href="javascript:history.back(-1);" class="btn btn-link"><i class="fa fa-arrow-circle-left"></i> Back</a>
</div>
<div class=" product-page">
<?php if($category->category_image != null) :?>
	<div class="cat-image">
		<img src="{{url:site}}files/large/<?php echo $category->category_image?>" class="img-responsive" alt="<?php echo $category->category_title?>" title="<?php echo $category->category_title?>" />
	</div>
<?php else: ?>
	<h2><?php echo $category->category_title; ?></h2>
<?php endif; ?>
</div>
<!-- product list -->
<?php if(count($products)>0) : ?>
<div class="row">
<?php foreach($products as $key => $product) : ?>
	<div class="col-sm-2 product-list">
		<a href="<?php echo site_url('product/'.$product->product_slug);?>">
		<span class="img-thumbnail">
			<img src="<?php echo site_url('files/thumb/'.$product->product_image.'/300/300') ;?>" class="img-responsive" />
		</span>
		<p class="text-center"><?php echo $product->product_name; ?></p>
		</a>
	</div>
<?php if(($key+1) % 6 === 0){
	echo '</div><div class="row">';
}

endforeach; ?>
</div>
<?php endif; ?>