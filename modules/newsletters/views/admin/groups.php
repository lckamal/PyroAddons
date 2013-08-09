<section class="title">
    <h4><?php echo lang('newsletters.newsletter_groups'); ?></h4>
</section>

<section class="item">
	<div class="content">	

		<table border="0" class="table-list"> 
			<thead>
				<tr>
					<th>Group Name</th>
					<th>Group Description</th>
					<th>Signup</th>
					<th>Users</th>
					<th>Date Modified</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
				<?foreach($groups as $group):?>
				<tr>
					<td><strong><a href="/admin/newsletters/groups/index/<?=$group->id?>"><?=htmlentities($group->group_name)?></a></strong></td>
					<td><?=htmlentities($group->group_description)?></td>
					<td><?php echo $group->group_public==1 ? 'Public' : 'Private'; ?></td>
					<td><?=$this->newsletters->count('recipients',$group->id)?></td>
					<td><?=$group->modified?></td>
					<td>
						<a class="button" href="<?= site_url('admin/newsletters/groups/edit/'.$group->id)?>">Edit</a> 
						<a class="button" href="<?= site_url('admin/newsletters/groups/delete/'.$group->id)?>" class="confirm">Delete</a>
					</td>
				</tr>
				<?endforeach?>
			</tbody>
		</table>


		
<?php if(isset($recipients)): ?>
</div>
</section>
<div class="clear" style="margin-top:15px;"></div>
<section class="title clear">
    <h4><?php if(count($recipients) > 1) echo count($recipients); ?> Recipients in "<?php echo htmlentities($group->group_name); ?>"</h4>
</section>
<section class="item">
	
	<div class="box-container">	


	<table border="0" class="table-list">   
	<thead>
		<tr>
			<th>Recipient Name</th>
			<th>Recipient Email</th>
			<th>Created</th>
			<th>Groups</th>
			<th>Action</th>
		</tr>
	</thead>
	<tbody>
	
		<?if(empty($recipients)):?>
		<tr>
			<td colspan="6"><p class="notice message">There are no recipients in this group.</p></td>
		</tr>
		<?else:?>
		
		
		
		<?foreach($recipients as $recipient):?>
		<tr>
			<td><?=htmlentities($recipient->name)?></td>
			<td><?=$recipient->email?></td>
			<td><?=$recipient->created?></td>
			<td>
			<?$groups=$this->newsletters->user_groups($recipient->id)?>
			<?foreach($groups as $group):?>
				<a class="nowrap" href="/admin/newsletters/groups/<?=$group->group_id?>"><strong><?=htmlentities($group->group_name)?></strong></a>
			<?endforeach?>
			</td>
			<td><a class="button" href="/admin/newsletters/recipients/edit/<?=$recipient->id?>">Edit</a> 
			<a class="button" href="/admin/newsletters/recipients/delete/<?=$recipient->id?>" class="confirm">Delete</a></td>
		</tr>
		<?endforeach?>
		
		
		
		<?endif?>
	</tbody>
	</table>
<?endif?>

</div>
</section>

