<div class="clearfix">
	<ul class="partners">
		<?php foreach($partner_widget['entries'] as $partner): ?>
		<li>
			<a class="tooltipsy" href="<?php echo $partner['web']; ?>" target="_blank" title="<?php echo $partner['description']; ?>">
				<img src="<?php echo site_url('files/thumb/'.$partner['icon'].'/50/50'); ?>" height="50" />
			</a>
		</li>
		<?php endforeach; ?>
	</ul>
</div>