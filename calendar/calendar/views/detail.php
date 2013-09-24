<?php 
echo @$flash_message;
if (!empty($data_read)): 

$num_loop = 0;
$post = $data_read; 
    $num_loop++;
    if($num_loop%2 == 0){
        $class_even = 'list_darken';
    }else{
        $class_even = '';
    }
	
	$date_begin = substr($post->event_date_begin, 0, 13);
	if(!empty($date_str) and $date_begin != $date_str){
		$date_start = $date_str.':00:00';
		$date_url = substr(str_replace(' ', '-', $date_start), 0, 13);
		$date_end = null;
	}elseif(!empty($date_str) and $date_begin == $date_str){
		$date_start = $date_str.':00:00';
		$date_end = empty($post->event_date_end) ? null : $post->event_date_end;
	}else{
		$date_start = $post->event_date_begin;
		$date_url = substr(str_replace(' ', '-', $date_start), 0, 13);
		$date_end = empty($post->event_date_end) ? null : $post->event_date_end;
	}
?>
<div class="calendar_list<?php echo ' '.$class_even; ?>">
		<div class="calendar_list_heading"><?php echo  anchor('calendar/detail/' .$post->id_eventcal .'/'.$date_url.'.'.preg_replace('{[^0-9-a-zA-Z]+}', '-', $post->event_title), stripslashes($post->event_title)); ?></div>
		<p class="calendar_list_info">
			<?php echo lang('calendar_event_date_label');?>: {{helper:date format="<?php echo $dateformat; ?>" timestamp="<?php echo $date_start; ?>"}}
			<?php if(!empty($date_end)): ?>- {{helper:date format="<?php echo $dateformat; ?>" timestamp="<?php echo $date_end; ?>"}}<?php endif; ?>
		</p>
		<div class="calendar_list_intro">
			<?php echo stripslashes($post->event_content); ?>
		</div>
</div>
<?php endif; ?>
