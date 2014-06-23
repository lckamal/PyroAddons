<?php if(!empty($widget->body)): ?>
	<?php if($widget->slug == 'slider'): ?>
		<?php echo $widget->body ?>
	<?php else: ?>
		<div class="panel panel-default <?php echo $widget->slug ?>">
	<?php if ($widget->options['show_title']): ?>
			<div class="panel-heading"><?php echo $widget->instance_title ?></div>
		<?php endif ?> 
	  <div class="panel-body">
	    <?php echo $widget->body ?>
	  </div>
	</div>
	<?php endif; ?>

<?php endif; ?>