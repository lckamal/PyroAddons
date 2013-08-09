<?php if($testimonial_widget['total'] > 0): ?>
<ul id="testimonial_widget" class="testimonials">
    <?php foreach($testimonial_widget['entries'] as $entry): ?>
        <li class="clearfix">
            
            <h4 class="widget_title"><?php echo $entry['company'] ?></h4>
            <p class="widget_quote"><?php echo $entry['quote'] ?></p>
            
        </li>
    <?php endforeach ?>
</ul>
<?php endif; ?>
<br style="clear:both;">