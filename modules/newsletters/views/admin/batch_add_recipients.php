<div class="box">

	<h3>Newsletter Groups</h3>				
	
	<div class="box-container">
	
	
	

		<div class="tabs">
			
			<ul class="tab-menu">
				<li><a href="#from-csv"><span>From CSV</span></a></li>
				<li><a href="#quick-add"><span>Quick Add</span></a></li>
			</ul>
				
			<div id="from-csv">
			<form action="/admin/newsletters/add_users_from_file" method="post" class="crud" enctype="multipart/form-data">	
			<ol>
				
				<li>Add a CSV format file with email address contacts.</li>
				
				<li class="even">
				<label>Add to group:</label>
				<?foreach($groups as $group):?>
						<input type="checkbox" name="group[]" value="<?=$group->id?>"><?=htmlentities($group->group_name)?> &nbsp; &nbsp; &nbsp; &nbsp;
				<?endforeach?>
				</li>
				
				<li>
					<label for="userfile">Upload a CSV file</label>
					<input type="file" name="userfile" />
				</li>
				
				<li class="even"><a href="/admin/public/files/example.csv" />Download a sample CSV file</a></li>
				<li><button type="submit" name="btnAction" value="save"><span>Add Users</span></button>
			<div class="button"><a href="http://localhost/admin/newsletters" class="ajax">Cancel</a></div></li>
				
			</ol>
			</form>
			</div>
			
			<div id="quick-add">
			<form class="crud" action="/admin/newsletters/quick_add_users" method="post">
			<ol>
				
				<li>
					<label>Type or paste email addresses below</label>
					<textarea name="quick_add"></textarea>
				</li>
				
				<li class="even">
				<label>Add to group:</label>
				<?foreach($groups as $group):?>
						<input type="checkbox" name="group[]" value="<?=$group->id?>"><?=htmlentities($group->group_name)?> &nbsp; &nbsp; &nbsp; &nbsp;
				<?endforeach?>
				</li>

				<li>
					<button type="submit" name="btnAction" value="save"><span>Save</span></button>
			<div class="button"><a href="http://localhost/admin/newsletters" class="ajax">Cancel</a></div>
				</li>
			</ol>
			</form>
			</div>
		
		</div>
			
			

	

</div>
</div>