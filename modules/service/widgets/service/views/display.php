<?php 
//var_dump($service_widget);die();
?>

<div class="row" id="con-bot">
  <?php foreach($service_widget as $service): ?>
          <div class="col-md-6 col-xs-12" style="margin-bottom:5px;">
            <a href="<?php if($service->name=='Training') { echo base_url()?>training<?php } else{ echo base_url()?>service/single_service/<?php echo $service->id;} ?>">
            <h4><?php echo $service->name; ?></h4>
            </a>
            <div class="bot-left-img">
              <img src="<?php echo base_url();?>/files/thumb/<?php echo $service->image; ?>/275/150" alt="<?php echo $service->name; ?>" class="img-responsive" />
            </div>
            <div class="left-text">
                      
            <p class="smallfont"><?php echo excerpt($service->introduction,14); ?></p>
            <a class="" href="<?php if($service->name=='Training') { echo base_url()?>training<?php } else{ echo base_url()?>service/single_service/<?php echo $service->id;} ?>">Read More&nbsp;<i class="fa fa-angle-double-right"></i></a>
            </div>
          
          </div>
  <?php endforeach; ?>        
</div>          

