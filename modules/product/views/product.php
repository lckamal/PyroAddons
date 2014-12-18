<div class="goback"><a class="btn btn-default btn-sm pull-right" href="javascript:history.back(-1);"><i class="fa fa-arrow-left"></i> Go Back</a></div>
<section class="slice bg-5">
    <div class="w-section inverse">
        <div class="">
			<div class="row">
				
				<div class="col-sm-7">
					<h2>{{product.product_name}}</h2>
					{{product.product_desc}}
				</div>
				<div class="col-sm-5" style="margin-top:30px;">
					<a href="<?php echo site_url().'files/large/'; ?>{{product.product_image}}" rel="product" class="fancybox thumbnail">
						<img class="img-responsive" src="<?php echo site_url().'files/thumb/'; ?>{{product.product_image}}/350/260" alt="{{product.product_name}}">
					</a>
					{{ if product.product_gallery }}
					<div class="row mb20">
						{{ product.product_gallery }}
							<div class="col-sm-4" style="padding-right:0;">
								<a href="<?php echo site_url().'files/large/'; ?>{{id}}" rel="productg" class="fancybox thumbnail">
									<img src="{{url:site}}files/thumb/{{id}}/150/100" class="img-responsive pgal" title="{{name}}" alt="{{name}}" />
								</a>
							</div>
						{{ /product.product_gallery }}
					</div>
					{{ endif }}
				</div>
			</div>
			
		</div>
	</div>
</section>
