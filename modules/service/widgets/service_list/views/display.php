                <div class="product-catogory">
                  <h3 style="text-align:left;">Services</h3>
                  <div class="list-group list-categ">
                    <a class="list-group-item" href="<?php echo site_url().'service'; ?>">All</a>
                    <?php foreach ($service_widget as $service) { ?>
                    <a class="list-group-item" href="<?php echo site_url().'service/service_info/'.$service->service_slug; ?>"><?php echo $service->title; ?></a>
                    <?php } ?>                  
                    
                  </div>
                </div>
