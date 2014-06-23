<link href="<?php echo $module_path; ?>/css/<?php echo $css_name; ?>" type="text/css" rel="stylesheet" />
<?php
if(!empty($js_name)){
?>
<script src="<?php echo $module_path; ?>/js/<?php echo $js_name; ?>" type="text/javascript"></script>
<?php
}
if($caldt): ?>
    <div id="calclip">
    
	<b><?php echo $caldt->current_month_text; ?></b>
	<div class="cc_box">
		<span class="cc_head">
		<ul>
			<li>S</li>
			<li>M</li>
			<li>T</li>
			<li>W</li>
			<li>T</li>
			<li>F</li>
			<li>S</li>
		</ul>
		</span>
		<span class="cc_body">
		<ul>
			<?php
			for($i=0; $i< $caldt->total_rows; $i++)
			{
				for($j=0; $j<7;$j++)
				{
					$caldt->day++;					
					
					if($caldt->day>0 && $caldt->day<=$caldt->total_days_of_current_month)
					{
						//YYYY-MM-DD date format
						$date_form = $caldt->current_year."/".$caldt->current_month."/".$caldt->day;
						echo '<li';
						
						//check if the date is today
						if($date_form == $caldt->today)
						{
							echo ' id="today"';
						}
						
						//check if any event stored for the date
						if(array_key_exists($caldt->day,$caldt->events))
						{
						
							//adding the date_has_event class to the <li> and close it
							echo ' class="date_has_event"> ';
							if($caldt->path_display == 'display'){
                                echo anchor("calendar/".$caldt->path_display."/".$date_form, $caldt->day);
                            }else{
                                echo anchor("calendar/".$caldt->path_display."/", $caldt->day);
                            }
							
							echo '<div class="events"><div class="event_box">';
							foreach ($caldt->events as $key=>$event){
								if ($key == $caldt->day){
							  	foreach ($event as $single){
									$oridate = str_replace(' ', '-', $single->event_date_begin);
									$strdate = substr($oridate, 0, 8).$caldt->day.substr($oridate, 10, 3);
							  	    $title_link = site_url('calendar/detail/' .$single->id_eventcal .'/'.$strdate.'.'.preg_replace('{[^0-9-a-zA-Z]+}', '-', $single->event_title), stripslashes($single->event_title));
							  		echo '<div class="clip_event_content">'; 					
									echo sprintf('<span class="title"><a href="%s">%s</a></span>', $title_link, $single->event_title);
									echo '</div>'; 
  								} // end of for each $event
								}
  								
							} // end of foreach $events
							echo '</div></div>';
							
						} // end of if(array_key_exists...)
					
						else 
						{
							//if there is not event on that date then just close the <li> tag
							echo '> '.$caldt->day;
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
				echo "</ul><ul>";
			}
			
			?>
		</ul>
		</span>
		<span class="cc_head" id="clip_title"></span>
	</div>
    </div>
<?php endif; ?>
