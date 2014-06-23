<?php echo form_open('admin/newsletters/recipients/delete');?>
<section class="title">
    <h4><?php echo lang('newsletters.newsletter_recipients'); ?></h4>
</section>

<section class="item">
<div class="inner"><?php $this->load->view('admin/partials/pagination'); ?></div>	
	
	<div class="content">	
<?if(empty($recipients)):?>

<?else:?>
	<table border="0" class="table-list">    
		<thead>
			<tr>
				<th><?php echo form_checkbox('action_to_all');?></th>
				<th>Date Registered</th>
				<th>Recipient Name</th>
				<th>Recipient Email</th>
				<th>Groups</th>
				<th>Action</th>
			</tr>
		</thead>
		<tbody>
			<?foreach($recipients as $recipient):?>
			<tr>
				<td><?php echo form_checkbox('action_to[]', $recipient->id);?></td>
				<td><?=$recipient->created?></td>
				<td><?=htmlentities($recipient->name)?></td>
				<td><?=$recipient->email?></td>
				<td>
				<?$groups=$this->newsletters->user_groups($recipient->id)?>
				<?count($groups) > 1 ? $delimiter=' &nbsp; ' : $delimiter='';?>
				<?foreach($groups as $group):?>
					<a href="<?= site_url('admin/newsletters/recipients/index/'.$recipient->id)?>"><strong><?=htmlentities($group->group_name)?></strong></a><?=$delimiter?>
				<?endforeach?>
				</td>
				<td>
					<a class="button" href="<?= site_url('admin/newsletters/recipients/edit/'.$recipient->id)?>">Edit</a>
				</td>
			</tr>
			<?endforeach?>
		</tbody>
	</table>
    <?php $this->load->view('admin/partials/buttons', array('buttons' => array('delete') )); ?>
			<?endif?>
	</div>
	
	<div class="inner"><?php $this->load->view('admin/partials/pagination'); ?></div>


	
</div>
<?php echo form_close();?>