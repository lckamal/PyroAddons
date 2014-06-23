<?php echo @$menu_calendar; ?>
<h3><?php echo lang('calendar_home_label');?></h3>
 <div id="calmain">
	<h2><?php echo $current_month_text; ?></h2>
	<div class="caltbl">
		<span class="caltbl_head">
		<ul>
			<li><?php echo lang('calendar_day_sunday');?></li>
			<li><?php echo lang('calendar_day_monday');?></li>
			<li><?php echo lang('calendar_day_tuesday');?></li>
			<li><?php echo lang('calendar_day_wednesday');?></li>
			<li><?php echo lang('calendar_day_thursday');?></li>
			<li><?php echo lang('calendar_day_friday');?></li>
			<li><?php echo lang('calendar_day_saturday');?></li>
		</ul>
		</span>
		<span class="caltbl_body">
		<ul>
			<?php
			for($i=0; $i< $total_rows; $i++)
			{
				for($j=0; $j<7;$j++)
				{
					$day++;					
					
					if($day>0 && $day<=$total_days_of_current_month)
					{
						//YYYY-MM-DD date format
						$date_form = "$current_year/$current_month/$day";
						
						echo '<li';
						
						//check if the date is today
						if($date_form == $today)
						{
							echo ' id="today"';
						}
						
						//check if any event stored for the date
						if(array_key_exists($day,$events))
						{
						
							//adding the date_has_event class to the <li> and close it
							echo ' class="date_has_event"> ';
							echo anchor("#",$day);
							
							echo '<div class="events"><div class="event_box">';
							
							foreach ($events as $key=>$event){
								if ($key == $day){
							  	foreach ($event as $single){
									
									$orimonth = intval(date('m', strtotime($single->event_date_begin)));
									$strdate = substr($single->event_date_begin, 0, 8).$key.substr($single->event_date_begin, 10, 3);
									$startdate = $single->event_date_begin;
									$urldate = substr(str_replace(' ', '-', $startdate), 0, 13);
									
									$enddate = '';
									if(!empty($single->event_repeat_prm)){
										if(empty($single->event_date_end) or strpos($single->event_date_end, '1970-01-01')){
											$enddate = '';
										}else{
											$enddate = '<br/> End : '.date($dateformat, strtotime($single->event_date_end));
										}
									}
							  	    
							  		echo '<div class="event_content">'; 					
									echo '<span class="title">'.$single->event_title.'</span><div class="titledate">Start : '.date($dateformat, strtotime($startdate)).$enddate.'</div><span class="desc">'.$single->event_content.'</span>';
									echo '</div>'; 
  								} // end of for each $event
								}
  								
							} // end of foreach $events
							
							
							echo '</div></div>';
						} // end of if(array_key_exists...)
					
						else 
						{
							//if there is not event on that date then just close the <li> tag
							echo '> '.$day;
							//echo anchor("#",$day);
						}
						echo "</li>";
					}
					else 
					{
						//showing empty cells in the first and last row
						echo '<li class="padding">&nbsp;</li>';
					}
				}
			}
			
			?>
		</ul>
		</span>
		<span class="caltbl_foot">	
		  <ul>
			<li>
			<?php echo anchor('calendar/'.$path_src.'/'.$previous_year,'&laquo;&laquo;', array('title'=>$previous_year_text));?>
			</li>
			<li>
			<?php echo anchor('calendar/'.$path_src.'/'.$previous_month,'&laquo;', array('title'=>$previous_month_text));?>
			</li>
			<li>&nbsp;</li>
			<li>&nbsp;</li>
			<li>&nbsp;</li>
			<li>
			<?php echo anchor('calendar/'.$path_src.'/'.$next_month,'&raquo;', array('title'=>$next_month_text));?>
			</li>
			<li>
			<?php echo anchor('calendar/'.$path_src.'/'.$next_year,'&raquo;&raquo;', array('title'=>$next_year_text));?>
			</li>		
		  </ul>		
		</span>
	</div>
</div>

	
