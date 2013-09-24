<section class="title"><h4><?php echo lang('calendar_edit_label');?></h4>
</section>
<section class="item">
	<div class="content">

<?php
//check if there is any alert message set
if(isset($alert) && !empty($alert))
{
	//message alert
	echo $alert;
}

echo form_open(uri_string());?>
<div class="form_inputs">
    <fieldset>
		<ul>
            <li class="even date-meta _eventdate">
                <label><?php echo lang('calendar_date_start_label'); ?> <span>*</span></label>
                <div class="input_tight">
                    <?php echo form_input('event_date_begin', date('Y-m-d', strtotime($post->event_date_begin)), 'maxlength="10" id="datepicker" class="text width-20"'); ?>
                </div>
                <label class="time-meta"><?php echo lang('calendar_time_label'); ?></label>
                <?php echo form_dropdown('date_s_hour', $hours, date('H', strtotime($post->event_date_begin))) ?>
                <?php echo form_dropdown('date_s_minute', $minutes, date('i', ltrim(strtotime($post->event_date_begin), '0'))) ?>
            </li>
            <li class="date-meta _eventdate">
                <label><?php echo lang('calendar_date_end_label'); ?></label>
                <div class="input_tight">
                    <?php echo form_input('event_date_end', $post->event_date_end, 'maxlength="10" id="datepicker2" class="text width-20 datepicker"'); ?>
                </div>
                <label class="time-meta"><?php echo lang('calendar_time_label'); ?></label>
                <?php echo form_dropdown('date_e_hour', $hours, $post->event_date_end_hour) ?>
                <?php echo form_dropdown('date_e_minute', $minutes, $post->event_date_end_minute) ?>
            </li>
			<li class="even">
                <label><?php echo lang('calendar_event_repeat_label'); ?></label>
                <div class="input_content">
				<?php
				
					if(!empty($post->event_repeat) and $post->event_repeat == 1){
						$valyes = true;
						$valno = false;
						$css_repeat = 'block';
					}else{
						$valyes = false;
						$valno = true;
						$css_repeat = 'none';
					}
					
					$ar_date = array_combine($ar_date=range(0, 23), $ar_date);
					$ar_week = array('0'=>lang('calendar_day_sunday'), '1'=>lang('calendar_day_monday'), '2'=>lang('calendar_day_tuesday'), '3'=>lang('calendar_day_wednesday'), '4'=>lang('calendar_day_thursday'), '5'=>lang('calendar_day_friday'), '6'=>lang('calendar_day_saturday'));
				?>
                    <?php echo form_radio('event_repeat', 1, $valyes,'class="radio width-20" '); ?><?php echo lang('calendar_yes_title'); ?>
                    <?php echo form_radio('event_repeat', 0, $valno, 'class="radio width-20" '); ?><?php echo lang('calendar_no_title'); ?>
                </div><br style="clear:both" />
				<label id="repeat_title" style="display:<?php echo $css_repeat; ?>;"><?php echo lang('calendar_repeat_by_title'); ?></label>
                <div style="display:<?php echo $css_repeat; ?>;" id="repeat_box">
					<?php echo form_dropdown('repeat_type', array('0'=>lang('calendar_repeat_day_title'), '1'=>lang('calendar_repeat_week_title'), '2'=>lang('calendar_repeat_month_title')), @$post->repeat_prm->type, 'style="display:'.$css_repeat.';"') ?>
                    <?php echo form_dropdown('repeat_date', $ar_date, @$post->repeat_prm->date, 'title="'.lang("calendar_date_label").'"'); ?>
                    <?php echo form_dropdown('repeat_day', $ar_week, @$post->repeat_prm->day, 'title="'.lang("calendar_day_title").'"'); ?>
					<?php echo form_dropdown('repeat_time', $hours, @$post->repeat_prm->time, 'title="'.lang("calendar_created_hour").'"'); ?>
                </div>
				<br id="repeat_box_br" style="clear:both" />
            </li>
            <li>
				<label for="title"><?php echo lang('calendar_title_label'); ?> <span>*</span></label>
				<?php echo form_input('event_title', htmlspecialchars_decode($post->event_title), 'maxlength="100" size="50"'); ?>
			</li>
            
			<li class="even editor">
			    <label for="body"><?php echo lang('calendar_content_label'); ?></label><br style="clear: both;" />
			</li>
			<li>
				<?php echo form_textarea(array('id' => 'body', 'name' => 'event_content', 'value' => $post->event_content, 'rows' => 40, 'class' => 'input_full wysiwyg-advanced')); ?>
			</li>
	
        </ul>
	</fieldset>
</div>
<div class="buttons float-right padding-top">
	<?php $this->load->view('admin/partials/buttons', array('buttons' => array('save', 'cancel'))); ?>
</div>
	<?php form_close() ;?>
	</div>
</section>
