<section class="title">
	<h4><?php echo lang('calendar_list_title'); ?></h4>
</section>

<section class="item">
	<div class="content">

<?php if ($calendar) : ?>

<?php echo $this->load->view('admin/partials/filters'); ?>

<div id="filter-stage">

	<?php echo form_open('admin/calendar/action'); ?>

		<?php echo $this->load->view('admin/table_list', $calendar); ?>

	<?php echo form_close(); ?>
	
</div>

<?php else : ?>
	<div class="no_data"><?php echo lang('calendar_no_data'); ?></div>
<?php endif; ?>
	</div>
</section>
