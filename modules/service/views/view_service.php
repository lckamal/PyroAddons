<?php //var_dump($clients); die(); ?>
<?php if(!function_exists('excerpt')){
    $this->load->helper('excerpt');
    } ?>
<h2 style="margin-bottom:23px;">Services</h2>

<div class="row">
<?php foreach($services as $service){ ?>
                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="services sfheight">
                    <a href="<?php echo base_url()?>service/service_info/<?php echo $service->service_slug;?>">
                      <h4><?php echo $service->title;?></h4>
                      <p>{{theme:image file="moka/settings.png"}}
                        <?php echo excerpt($service->description, 50);?>
                        </p>
                      </a>
                    </div>
                  </div>
        <?php } ?>
        </div>