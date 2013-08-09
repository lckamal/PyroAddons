<?php
$fields=array('group_name','group_description','id','group_public');
isset($groups) ? $action='Edit':$action='Add';
foreach($fields as $field):
	if(set_value($field)) $$field=set_value($field);
	elseif(isset($groups)) foreach($groups as $group){$$field=$group->$field;}
	else $$field='';
endforeach;
?>
<section class="title">
    <h4><?php echo lang('newsletters.add_title'); ?></h4>
</section>

<section class="item">
    <div class="content">
		<?php echo form_open_multipart($this->uri->uri_string()); ?>
        <div class="form_inputs">
            <ul>
				<li class="<?php echo alternator('', 'even'); ?>">
					<label for="group_name">Group Name <span>*</span></label>
					<div class="input">
						<input type="text" class="width-20" name="group_name" value="<?=$group_name?>" />
					</div>
				</li>
				<li class="<?php echo alternator('', 'even'); ?>">
					<label for="group_description">Group Description <span>*</span></label>
					<div class="input">
						<input type="text" class="width-30" name="group_description" value="<?=$group_description?>" />
					</div>
				</li>
				<li class="<?php echo alternator('', 'even'); ?>">
					<label for="group_public">Allow public signup?</label>
					<div class="checker">
						<label>Yes <?php echo form_radio('group_public','1',$group_public==1 ? true:false); ?></label>
						<label>No <?php echo form_radio('group_public','0',$group_public==0 ? true:false); ?></label>
					</div>
				</li>
			</ul>
			
			<div class="buttons">
                <?php $this->load->view('admin/partials/buttons', array('buttons' => array('save', 'cancel') )); ?>
            </div>
		</div>
		<?=form_close()?>
	</div>
</section>