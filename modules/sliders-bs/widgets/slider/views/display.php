<?php if(count($images) != 0): ?>
<div id="carousel-example-generic" class="carousel slide">
  <!-- Indicators -->
  <ol class="carousel-indicators">
    <?php foreach($images as $key => $image): ?>
    <li data-target="#carousel-example-generic" data-slide-to="<?php echo $key; ?>" class="<?php echo $key == 0 ? 'active' : ''?>"></li>
    <?php endforeach; ?>
  </ol>

  <!-- Wrapper for slides -->
  <div class="carousel-inner">
    <?php foreach($images as $key => $image): ?>
        <div class="item <?php echo $key == 0 ? 'active' : ''?>">
          <img src="<?php echo $image->path; ?>" <?php echo ($options['captions'] === 'true') ? 'title="'.$image->name.'"' : null; ?> />
          <?php if( ! empty($image->alt_attribute) && ! empty($image->description)):?>
          <div class="carousel-caption">
                <h3><?php echo $image->alt_attribute; ?></h3>
                <p><?php echo $image->description; ?> &raquo;</p>
          </div>
            <?php endif; ?>

        </div>
    <?php endforeach; ?>

  </div>

  <!-- Controls -->
  <a class="left carousel-control" href="#carousel-example-generic" data-slide="prev">
    <span class="icon-prev"></span>
  </a>
  <a class="right carousel-control" href="#carousel-example-generic" data-slide="next">
    <span class="icon-next"></span>
  </a>
</div>
<script type="text/javascript">
    $(function(){
        $('.carousel').carousel();
    });
</script>
<?php endif; ?>