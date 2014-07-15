<?php //var_dump($newss);die(); ?>
<style type="text/css">
  .cat-title {
    color: #30538E;
  }
</style>
<h2 class="page-title">{{ template.title }}</h2>
  <?php foreach ($newss as $news) { ?>
    
  
  <h3 class="cat-title"><?php echo $news->name;  ?></h3>
  <p><?php echo $news->body;  ?></p>
  <?php } ?>
  <!-- <span><a class="pull-right btn btn-xs btn-primary" href="<?php echo site_url('news'); ?>">View all News</a></span> -->
