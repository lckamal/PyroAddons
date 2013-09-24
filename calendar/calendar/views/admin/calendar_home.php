<section class="title"><h4><?php echo lang('calendar_dashboard_label');?></h4>
</section>
<section class="item">
	<div class="content">
 <div id="calmain">

	<h2><?php echo $current_month_text; ?></h2>
	<table cellspacing="0">
		<thead>
		<tr>
			<th>Sun</th>
			<th>Mon</th>
			<th>Tue</th>
			<th>Wed</th>
			<th>Thu</th>
			<th>Fri</th>
			<th>Sat</th>
		</tr>
		</thead>
		<tr>
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
						
						echo '<td';
						
						//check if the date is today
						if($date_form == $today)
						{
							echo ' id="today"';
						}
						
						//check if any event stored for the date
						if(array_key_exists($day,$events))
						{
						    //if(date('Y m, d H:i', strtotime($single->event_date_begin)) == date('Y m, d H:i', strtotime($date_form))){
						        
						    //}
						
							//adding the date_has_event class to the <td> and close it
							echo ' class="date_has_event"> ';
							echo anchor("admin/calendar/dayevents/".$current_year."-".$current_month."-".$day,$day);
							
							//adding the eventTitle and eventContent wrapped inside <span> & <li> to <ul>
							echo '<div class="events"><ul>';
							
							foreach ($events as $key=>$event){
								if ($key == $day){
							  	foreach ($event as $single){
							  	    if(empty($single->event_date_end) or strpos($single->event_date_end, '1970-01-01')){
							  	        $enddate = '';
							  	    }else{
							  	        $enddate = '<br/> End : '.date('M,d Y - H:i', strtotime($single->event_date_end));
							  	    
							  	    }
							  	    
							  		echo '<li>'; 					
									echo anchor("admin/calendar/edit/$single->id_eventcal",'<span class="title">'.$single->event_title.'</span><div class="titledate">Start : '.date('M,d Y - H:i', strtotime($single->event_date_begin)).$enddate.'</div><span class="desc">'.$single->event_content.'</span>');
									echo '</li>'; 
  								} // end of for each $event
								}
  								
							} // end of foreach $events
							
							
							echo '</ul></div>';
						} // end of if(array_key_exists...)
					
						else 
						{
							//if there is not event on that date then just close the <td> tag
							echo '> '.anchor("admin/calendar/create/".$current_year."-".$current_month."-".$day,$day);
						}
						echo "</td>";
					}
					else 
					{
						//showing empty cells in the first and last row
						echo '<td class="padding">&nbsp;</td>';
					}
				}
				echo "</tr><tr>";
			}
			
			?>
		</tr>
		<tfoot>		
			<th>
			<?php echo anchor('admin/calendar/index/'.$previous_year,'&laquo;&laquo;', array('title'=>$previous_year_text));?>
			</th>
			<th>
			<?php echo anchor('admin/calendar/index/'.$previous_month,'&laquo;', array('title'=>$previous_month_text));?>
			</th>
			<th>&nbsp;</th>
			<th>&nbsp;</th>
			<th>&nbsp;</th>
			<th>
			<?php echo anchor('admin/calendar/index/'.$next_month,'&raquo;', array('title'=>$next_month_text));?>
			</th>
			<th>
			<?php echo anchor('admin/calendar/index/'.$next_year,'&raquo;&raquo;', array('title'=>$next_year_text));?>
			
			</th>		
		</tfoot>
	</table>
</div>
</div>
</section>