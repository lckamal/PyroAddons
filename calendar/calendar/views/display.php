<div  id="calcontainerback" style="position:absolute; width:100%; height:100%; top:0px; left:0px; display:none; z-index:200;"></div>
<?php echo @$menu_calendar; ?>
<div id="calformcont">
<form action="<?php echo site_url('calendar/display'); ?>" method="POST">
<center><b><?php echo lang('calendar_search_title');?></b></center>
<br/>
<div class="cal_search"><?php echo lang('calendar_date_start_label'); ?></div>: <?php echo lang('calendar_date_from');?> <input id="date_from" name="txs_from" type="text" size="11" maxlength="10" value="<?php echo @$search_prm['date_start']; ?>" />
&nbsp;&nbsp;<?php echo lang('calendar_date_to');?> <input id="date_to" name="txs_to" type="text" size="11" maxlength="10" value="<?php echo @$search_prm['date_end']; ?>" />
&nbsp;&nbsp; <br style="clear:both;"/>
<div class="cal_search"><?php echo lang('calendar_event_title');?> </div>: <input type="text" name="txs_title" value="<?php echo @$search_prm['title']; ?>" />
<br style="clear:both;"/>
<?php
if(!empty($search_prm['getrepeat']) and $search_prm['getrepeat'] == 1){
	$is_checked = 'checked="checked"';
}else{
	$is_checked = '';
}
?>
<div class="cal_search">&nbsp; </div>&nbsp; <input type="checkbox" name="txs_repeat" value="1" style="width:20px;" <?php echo $is_checked; ?> /> <?php echo lang('calendar_show_repeat'); ?>
<br style="clear:both;"/>
&nbsp;<div class="cal_search">&nbsp;</div>  <input type="submit" value=" - <?php echo lang('calendar_search_button_title'); ?> - "/>
</form></div>
<?php 
echo @$flash_message;
if (!empty($data_read)): 

$num_loop = 0;
foreach ($data_read as $post): 
    $num_loop++;
    if($num_loop%2 == 0){
        $class_even = 'list_darken';
    }else{
        $class_even = '';
    }
    
    if(strtotime($post->event_date_begin) < time()){
        $img_sts = "bullet_gray.png";
        $lbl_sts = "Expired";
    }else{
        $img_sts = "bullet_green.png";
        $lbl_sts = "Live";
    }
	
	$date_begin = substr($post->event_date_begin, 0, 13);
	if(!empty($date_str) and $date_begin != $date_str){
		$date_start = $date_str.':00:00';
		$date_end = null;
	}elseif(!empty($date_str) and $date_begin == $date_str){
		$date_start = $date_str.':00:00';
		$date_end = empty($post->event_date_end) ? null : $post->event_date_end;
	}else{
		$date_start = $post->event_date_begin;
		$date_end = empty($post->event_date_end) ? null : $post->event_date_end;
	}
	$date_url = substr(str_replace(' ', '-', $date_start), 0, 13);
?>
<div class="calendar_list<?php echo ' '.$class_even; ?>">
		<div class="calendar_list_heading"><?php echo  anchor('calendar/detail/' .$post->id_eventcal .'/'.$date_url.'.'.preg_replace('{[^0-9-a-zA-Z]+}', '-', $post->event_title), stripslashes($post->event_title)); ?>
        <img style="float:right; clear:left; margin:18px 2px 1px 2px; border:none;" src="<?php echo site_url($this->module_details['path'].'/img/'.$img_sts); ?>" title="<?php echo $lbl_sts; ?>" />
        </div>
		<p class="calendar_list_info">
			<?php echo lang('calendar_event_date_label');?>: {{helper:date format="<?php echo $dateformat; ?>" timestamp="<?php echo $post->event_date_begin; ?>"}}
			<?php if(!empty($post->event_date_end)): ?>- {{helper:date format="<?php echo $dateformat; ?>" timestamp="<?php echo $post->event_date_end; ?>"}}<?php endif; ?>
		</p>
		
</div>
<?php endforeach; ?>

<?php echo $pagination['links']; ?>

<?php else: ?>
	<p><?php echo lang('calendar_no_data');?></p>
<?php endif; ?>
