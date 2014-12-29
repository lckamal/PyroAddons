<section class="title">
	<h4>Choices</h4>
</section>
<section class="item">
	<div class="content">
		<?php echo form_open(current_url().'?layout='.$layout);  ?>
		
		<div class="form_inputs">
			<?php foreach($langs as $lang_code => $lang): ?>
			<?php 
			if(isset($choice[$lang_code]['id']))
			{
				echo form_hidden($lang_code.'[id]', $choice[$lang_code]['id']);
			}
			echo form_hidden($lang_code.'[choice_lang]', $lang_code);
			if(isset($choice->id))
			{
				echo form_hidden($lang_code.'[choice_id]', $choice->id);
			}
			
			?>
				<fieldset>
				<legend><p><?php echo ucfirst($lang['folder']); ?></p></legend>
				<ul>
					<li>
						<label for="meta_title">Title</label>
						<div class="input">
							<?php echo form_input($lang_code.'[choice_title]', isset($choice[$lang_code]['choice_title']) ? $choice[$lang_code]['choice_title'] : null);?>
						</div>
					</li>
				</ul>
				</fieldset>
			<?php endforeach; ?>
			
		</div>    
		<div class="buttons align-right padding-top">
			<?php $this->load->view('admin/partials/buttons', array('buttons' => array('save', 'cancel') )) ?>
		</div>
		<?php echo form_close();?>
	</div>
</section>