<?php if($testimonial_widget['total'] > 0): ?>

<h1 class="section-heading text-highlight">Testimonials <small>Our Clients Love Us!</small></h1>
<?php if($testimonial_widget['total'] > 1): ?>
<div class="carousel-controls">
    <a class="prev" href="index.html#testimonials-carousel" data-slide="prev"><i class="fa fa-caret-left"></i></a>
    <a class="next" href="index.html#testimonials-carousel" data-slide="next"><i class="fa fa-caret-right"></i></a>
</div><!--//carousel-controls-->
<?php endif; ?>
<div class="section-content">
    <div id="testimonials-carousel" class="testimonials-carousel carousel slide">
        <div class="carousel-inner">
        <?php foreach($testimonial_widget['entries'] as $key => $entry): ?>
            <div class="item <?php echo $key == 0 ? 'active' : ''; ?>">
            	<?php if($entry['photo']):?>
            		<img src="<?php echo base_url('files/thumb/'.$entry['photo'].'/80/80'); ?>" alt="<?php echo $entry['company'] ?>" style="width:80px;height:80px;" class="img-circle pull-left ml15 mr15">
            	<?php endif; ?>
                <blockquote class="quote">                                  
                    <p><i class="fa fa-quote-left"></i><?php echo $entry['quote'] ?> - <span class="name"><?php echo $entry['company'] ?></span></p>

                </blockquote>                               
            </div><!--//item-->  
    	<?php endforeach ?> 
        </div><!--//carousel-inner-->
    </div><!--//testimonials-carousel-->
</div><!--//section-content-->
<?php endif; ?>
<br style="clear:both;">