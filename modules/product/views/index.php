<?php // var_dump($products);die(); ?>
<div class="pg-opt pin" style="margin-bottom:6px;">
    <div class="">
        <div class="row">
            <div class="col-md-6 col-sm-6">
                <h2>{{template:title}}<?php if(!empty($catn)){echo " &raquo;  $catn";} ?></h2>
            </div>            
        </div>
    </div>
</div>
<section class="slice animate-hover-slide bg-3">
    <div class="w-section inverse">
        <div class="">
            {{ if products.total > 0 }}
            <div class="row">
                {{ products.entries }}
                <div class="col-md-4 col-sm-6 product">
                    <div class="w-box">
                        <a href="<?php echo site_url().'product/' ?>{{product_slug}}">
                        <div class="figure">
                            {{ if product_image }}
                            <img src="<?php echo site_url().'files/thumb/'; ?>{{ product_image }}/300/260" class="img-responsive pcat" alt="{{ product_name }}" />
                            {{ else }}
                            <img src="http://placehold.it/300x260/0C4CA3/ffffff&text={{product_name}}" class="img-responsive pcat"alt="{{ product_image.name }}"/>
                            {{ endif }}                           
                            
                        </div>
                        <h3>{{product_name}}</h3>
                        </a>
                        <p>
                        {{ excerpt:excerpt text=product_desc word_count="25" show_link="true" url="product/{{product_slug}}" link_class="capitalise" }}
                        <!-- <a class="btn btn-link btn-sm" href="#">read more</a> -->
                        </p>
                    </div>
                </div>
                {{ /products.entries }}
            </div>
        {{ else }}
            <div class="alert alert-info">There are currently no products.</div>
        {{ endif }}
        </div>
    </div>
</section>