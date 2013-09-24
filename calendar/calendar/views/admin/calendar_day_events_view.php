
<div id="navcontainer">
<ul>
<li><?php echo anchor('admin/calendar/create', 'Add Events to Calendar');?> </li>
<li><?php echo anchor('admin/calendar/index/', 'Show Site Calendar');?></li>

</ul>
</div>
<div id="header">
  <?php 
  	foreach ($dayevents as $dayevent){
  		echo "<div class=\"dayevents\">";
  		echo "<h3>Event date: </h3>";
  		echo $dayevent['eventDate'];
  		echo "<br />\n";
  		echo "<h3>Event Title: </h3>";
  		echo $dayevent['eventTitle'];
  		echo "<br />\n";
  		echo "<h3>Event Content: </h3>";
  		echo $dayevent['eventContent'];
  		echo "</div>";
  	}
  		
  
  ?>
	
 </div>
  
 
