<section class="title">
	<h4><?php echo lang('calendar_setting_title'); ?></h4>
</section>

<section class="item">
	<div class="content">
<span id="progress_loading" style="display:none;"><img src="<?php echo site_url($this->module_details['path'].'/img/progress.gif'); ?>"/></span>

	<?php echo form_open(uri_string(), 'class="crud"'); ?>

<div class="tabs">

	<!-- Content tab -->
	<div class="form_inputs" id="member-content-tab">
	
		<fieldset>
		<ul>
			<li>
				<label for="set_calendar_default"><?php echo lang('calendar_home_default_label'); ?> </label>
				<?php echo form_dropdown('set_calendar_default', array('0' => lang('calendar_style_calendar'), '1' => lang('calendar_style_list')), $post->set_calendar_default); ?>
				
			</li>
			<li class="even">
				<label for="set_widget_default"><?php echo lang('calendar_widget_default_label'); ?></label>
				<?php echo form_dropdown('set_widget_default', array('0' => lang('calendar_style_calendar'), '1' => lang('calendar_style_list')), $post->set_widget_default); ?>
                
			</li>
			<li>
				<label for="set_menu_status"><?php echo lang('calendar_menu_status_label'); ?> </label>
				<?php echo form_dropdown('set_menu_status', array('0' => lang('calendar_hide_title'), '1' => lang('calendar_show_title')), $post->set_menu_status); ?>
				
			</li>
			<li class="even">
				<label for="set_widget_size"><?php echo lang('calendar_widget_size_label'); ?></label>
				<?php echo form_dropdown('set_widget_size', array('0' => lang('calendar_widget_smallest_title'), '1' => lang('calendar_widget_small_title')), $post->set_widget_size); ?>
                
			</li>
			<li>
				<label for="set_widget_hover"><?php echo lang('calendar_widget_hover_label'); ?> </label>
				<?php echo form_dropdown('set_widget_hover', array('0' => lang('calendar_hide_title'), '1' => lang('calendar_show_title')), $post->set_widget_hover); ?>
				
			</li>
			<li class="even">
				<label for="set_calendar_size"><?php echo lang('calendar_size_label'); ?></label>
				<?php echo form_dropdown('set_calendar_size', array('biggest' => lang('calendar_widget_biggest_title'), 'big' => lang('calendar_widget_big_title')), $post->set_calendar_size); ?>
                
			</li>
			<li>
				<label for="set_calendar_style"><?php echo lang('calendar_style_label'); ?> </label>
				<?php echo form_dropdown('set_calendar_style', 
				array('lightred' => lang('calendar_theme_lightred'), 'lightblue' => lang('calendar_theme_lightblue'), 'lightgreen' => lang('calendar_theme_lightgreen'), 'darkred' => lang('calendar_theme_darkred'), 'darkblue' => lang('calendar_theme_darkblue'), 'darkgreen' => lang('calendar_theme_darkgreen'), 'orig' => lang('calendar_theme_original')), 
				$post->set_calendar_style); ?>
				
			</li>
			<li class="even">
				<label for="set_calendar_dateformat"><?php echo lang('calendar_dateformat_label'); ?> </label>
				<?php echo form_dropdown('set_calendar_dateformat', array('M d, Y H:i' => 'Jul 20, 2012 17:34', 'd M Y H:i' => '20 Jul 2012 17:34', 'M d, Y h:i A' => 'Jul 20, 2012 05:34 PM', 'M d, Y' => 'Jul 20, 2012', 'd M Y' => '20 Jul 2012'), 
				$post->set_calendar_dateformat); ?>
				
			</li>
			<li>
				<label><?php echo lang('calendar_repeat_count'); ?> </label>
				<?php 
				$ar_default_repeat = array('daily'=>array(0, 1), 'weekly'=>array(0, 1), 'monthly'=>array(0, 1));
				if(!empty($post->set_repeat_emersion)){
					$ar_repeat = json_decode($post->set_repeat_emersion, true);
				}else{
					$ar_repeat = $ar_default_repeat;
				}
				echo '<span class="width-100">'.lang('calendar_repeat_day_title') . '</span>:&nbsp;<span>';
				echo lang('calendar_repeat_prev').' '.form_input('set_repeat_daily_p', @$ar_repeat['daily'][0], 'class="width-50"').' - '; 
				echo lang('calendar_repeat_next').' '.form_input('set_repeat_daily_n', @$ar_repeat['daily'][1], 'class="width-50"');
				echo '</span><br style="clear: both;" />';
				
				echo '<label>&nbsp;</label>';
				echo '<span class="width-100">'.lang('calendar_repeat_week_title') . '</span>:&nbsp;<span>';
				echo lang('calendar_repeat_prev').' '.form_input('set_repeat_weekly_p', @$ar_repeat['weekly'][0], 'class="width-50"').' - '; 
				echo lang('calendar_repeat_next').' '.form_input('set_repeat_weekly_n', @$ar_repeat['weekly'][1], 'class="width-50"');
				echo '</span><br style="clear: both;" />';
				
				echo '<label>&nbsp;</label>';
				echo '<span class="width-100">'.lang('calendar_repeat_month_title') . '</span>:&nbsp;<span>';
				echo lang('calendar_repeat_prev').' '.form_input('set_repeat_monthly_p', @$ar_repeat['monthly'][0], 'class="width-50"').' - '; 
				echo lang('calendar_repeat_next').' '.form_input('set_repeat_monthly_n', @$ar_repeat['monthly'][1], 'class="width-50"');
				echo '</span><br style="clear: both;" />';
				
				?>
				
			</li>
		</ul>
		</fieldset>
	</div>

</div>

<div class="buttons float-right padding-top">
	<?php $this->load->view('admin/partials/buttons', array('buttons' => array('save'))); ?>
</div>
		

	<?php echo form_close(); ?>
	
	</div>
</section>
