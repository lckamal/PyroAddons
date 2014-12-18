<div class="row ca-menu">
<?php foreach($featured_widget as $featured): ?>
            <div class="col-md-3 col-sm-6 col-xs-6 block-480">
              <div class="ca-wrapper">
                <a href="<?php echo base_url()?>service/service_info/<?php echo $featured->service_slug;?>">
                <span class="ca-icon"><?php echo $featured->icon; ?></span>
                <div class="ca-content">
                  <h2 class="ca-main"><?php echo $featured->title; ?></h2>
                  <h3 class="ca-sub"><?php echo $featured->quote; ?></h3>
                </div>
                </a>
              </div>
            </div>
            <?php endforeach; ?> 
          </div>