<style type="text/css">
	.form_inputs ul li > label{
		display:inline-block;
		width:100px;
	}
	.form_inputs ul li > input{
		width: 225px;
	}
</style>
<div class="one_half">
	<section class="title">
		<h4><?php echo lang('watermark:watermark'); ?></h4>
	</section>

	<section class="item">
		<div class="content">
		<?php echo form_open(uri_string(), 'class="crud"'); ?>
			<div class="form_inputs">
				<fieldset>
					<ul>
						<li class="<?php echo alternator('even', ''); ?>">
							<label for="folder_id"><?php echo lang('watermark:folder').' <span>*</span>';?></label>
							<?php echo form_dropdown('folder_id', array('' => '---Select folder---') + (array)$folders, set_value('folder_id')); ?>
						</li>
						<li class="<?php echo alternator('even', ''); ?>">
							<label for="font_color"><?php echo lang('watermark:text').' <span>*</span>';?></label>
							<?php echo form_input('text', set_value('text', '© '.Settings::get('site_name')), 'id="text"'); ?>
						</li>
						<li class="<?php echo alternator('even', ''); ?>">
							<label for="position"><?php echo lang('watermark:position').' <span>*</span>';?></label>
							<?php echo form_dropdown('position', $positions, set_value('position', 'MM')); ?>
						</li>
						<li class="<?php echo alternator('even', ''); ?>">
							<label for="font_size"><?php echo lang('watermark:font_size').' <span>*</span>';?></label>
							<?php 
							for($i = 20; $i <= 60; $i+=2)
							{
								$font_options[$i] = $i;
							}
							
							echo form_dropdown('font_size', $font_options, set_value('font_size')); ?>
						</li>
						<li class="<?php echo alternator('even', ''); ?>">
							<label for="font_color"><?php echo lang('watermark:font_color').' <span>*</span>';?></label>
							<?php echo form_input('font_color', set_value('font_color', 'ffffff'), 'id="colorpicker" readonly="readonly"'); ?>
						</li>
						<li class="<?php echo alternator('even', ''); ?>">
							<label for="font_size"><?php echo lang('watermark:transparancy').' <span>*</span>';?></label>
							<?php 
							for($i = 0; $i <= 90; $i+=10)
							{
								$transparent_options[$i] = $i;
							}
							
							echo form_dropdown('opacity', $transparent_options, set_value('opacity', 20)); ?>
						</li>
						<li class="<?php echo alternator('even', ''); ?>">
							<label for="font_size"><?php echo lang('watermark:rotation').' <span>*</span>';?></label>
							<?php 
							for($i = -180; $i <= 180; $i+=45)
							{
								$rotation_options[$i] = $i;
							}
							
							echo form_dropdown('rotation', $rotation_options, set_value('rotation', 0)); ?>
						</li>
					</ul>
				</fieldset>
			</div>

			<div class="buttons align-right padding-top">
				<?php $this->load->view('admin/partials/buttons', array('buttons' => array('save', 'cancel') )); ?>
			</div>

		<?php echo form_close(); ?>
		</div>
	</section>
</div>

<div class="one_half last scroll-follow">	
	<section class="title">
		<h4><?php echo lang('watermark:explanation'); ?></h4>
	</section>
	
	<section class="item">
	
		<div class="content">
		<p>
			<?php echo lang('watermark:instructions'); ?>
		</p>
		<p>Images watermarked with this module once won't be watermarked again.</p>
		</div>
	</section>
</div>