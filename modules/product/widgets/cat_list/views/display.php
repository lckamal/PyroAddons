                <div class="product-catogory">
                  <h3 style="text-align:left;">Categories</h3>
                  <div class="list-group list-categ">
                    <a class="list-group-item" href="<?php echo site_url().'product'; ?>">All</a>
                    <?php foreach ($cat_widget as $cat) { ?>
                    <a class="list-group-item" href="<?php echo site_url().'product/cat/'.$cat->id; ?>"><?php echo $cat->category_title; ?></a>
                    <?php } ?>                  
                    
                  </div>
                </div>
