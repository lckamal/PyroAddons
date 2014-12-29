

<script>
     // $(window).load(function() {
     //          $('.flexslider').flexslider({
     //            animation: "slide",
     //            animationLoop: false,
     //            itemWidth: 210,
     //            itemMargin: 10
     //          });
     //        });
</script>


<style>

#mainslider
{
  margin-left: 13.7%;
  width: 72.9%;
}

ul .slides
{
  
}

/*ul.slides li:first-child
{
  margin-left: -47px !important;
} */

<style>
ul.slides li
{
  margin-top: 0px !important;
  min-width: 20px !important;
  min-height: 20px !important;
  
}

#mainslider .slides li{
max-width: 220px !important;
  max-height: 130px !important;
  padding:15px !important;
}

</style>






<div class="sapce"></div>
<!-- <div class="slider-wrapper" id = "mainslider">            
  <section class="slider">
        <div class="flexslider carousel">
          <ul class="slides">
          <?php foreach($product_widget as $product) { ?>
            <li>
              <a href = "<?php echo base_url()?>product/list_product_single/<?php echo $product->id;?>"><img alt="Colombo 05" src="<?php echo base_url();?>/files/thumb/<?php echo $product->business_logo; ?>"></a>
          </li>
          <?php } ?>
          </ul>
        </div>
      </section>
</div> -->



<script type="text/javascript">

jQuery(document).ready(function() {
    jQuery('#mycarousel').jcarousel({
      wrap: 'circular'
    });

});
</script>

<div class="row_two css_images">



<div class=" jcarousel-skin-tango"><div class="jcarousel-container jcarousel-container-horizontal" style="position: relative; display: block;"><div class="jcarousel-clip jcarousel-clip-horizontal" style="position: relative;"><ul class="jcarousel-list jcarousel-list-horizontal" id="mycarousel" style="overflow: hidden; position: relative; top: 0px; margin: 0px; padding: 0px; left: 0px; width: 1460px;">


    <?php foreach($product_widget as $product) { ?>
    <li class="jcarousel-item jcarousel-item-horizontal jcarousel-item-1 jcarousel-item-1-horizontal" style="float: left; list-style: none outside none;" jcarouselindex="1">
    <a title="iMac" href="<?php echo base_url()?>product/list_product_single/<?php echo $product->id;?>">
    <img style="position:relative; top:0" alt="iMac" src="<?php echo base_url();?>/files/thumb/<?php echo $product->business_logo; ?>">
    <span><?php echo $product->product_name;?></span>
    </a></li>
    <?php } ?>

   </ul></div><div class="jcarousel-prev jcarousel-prev-horizontal" style="display: block;" disabled="false"></div><div class="jcarousel-next jcarousel-next-horizontal" style="display: block;" disabled="false"></div></div></div>

</div>










        <div class="middle css_images">
   

</div>

<div class="clearfix">
	
</div>