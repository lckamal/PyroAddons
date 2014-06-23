<?php
$fields=array('name','email','id');
isset($recipient) ? $action='Edit':$action='Add';
foreach($fields as $field)
{
	if(set_value($field))
		$$field=set_value($field);
	elseif(isset($recipient))
		foreach($recipient as $user){$$field=$user->$field;}
	else
		$$field='';
}
?>
<section class="title">
    <h4><?=$action?> Recipient</h4>
</section>

<section class="item">
    <div class="content">
    <?php echo form_open_multipart($this->uri->uri_string(), 'class="crud"'); ?>
       <div class="form_inputs">
            <ul>
				<li>
					<label for="name">Recipient Name <span>*</span></label>
					<div class="input">
						<input type="text" name="name" value="<?=$name?>" />
					</div>
				</li>
				<li class="even">
					<label for="email">Email <span>*</span></label>
					<div class="input">
						<input type="text" name="email" value="<?=$email?>" />
					</div>
				</li>
			<li>
				<label for="group_id[]">Groups</label>
			<?if(isset($groups)):?>
				<?if(count($groups)==1):?>
					<?foreach($groups as $group):?>
						<?=htmlentities($group->group_name)?>
						<input type="hidden" name="group[]" value="<?=$group->id?>" />
					<?endforeach;?>
				<?else:?>
					<?foreach($groups as $group):?>
						<?
						$user_groups=array();
						if(isset($recipient)):
							foreach($this->newsletters->user_groups($id) as $user_group):
								$user_groups[]=$user_group->group_id;
							endforeach;
						endif;
						in_array($group->id,$user_groups) ? $checked=' checked="checked"' : $checked='';
						?>
						<span>
							<input type="checkbox"<?=$checked?> name="group[]" value="<?=$group->id?>"><?=htmlentities($group->group_name)?>
						</span>&nbsp;&nbsp;&nbsp;
					<?endforeach?>
				<?endif?>
			<?endif?>
			</li>
		</ul>
	</div>
	<div class="buttons">
        <?php $this->load->view('admin/partials/buttons', array('buttons' => array('save', 'cancel') )); ?>
    </div>
		<?=form_close()?>
    </div>
</section>