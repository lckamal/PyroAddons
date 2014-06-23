<?php if (validation_errors()): ?>
<div class="block-message error"><ul><?php echo validation_errors();?></ul></div>
<?php endif ?>
<h2>Gallery &raquo; <?php echo $holiday->trip_title?></h2>
<div class="block-message warning">NOTE: The uploaded images will be published on our site after approval.</div>
<form name="terms_conditions" enctype="multipart/form-data" action="" method="post">
	<input type="hidden" name="booking_ref" value="<?php echo $booking->booking_ref; ?>" />
	<input type="hidden" name="max_images" value="<?php echo $max_images; ?>" />
	<input type="hidden" name="datenprice_id" value="<?php echo isset($datenprice->id) ? $datenprice->id : 0;?>" />
	<input type="hidden" name="gallery_id" value="<?php echo isset($gallery->id) ? $gallery->id : 0;?>" />
	<ul>
		<li>
			<label for="name">Name:</label>
			<input type="text" name="name" id="name" value="<?php echo isset($gallery->title) ? $gallery->title : '';?>" />
		</li>
		<li>
			<label for="description">Description:</label>
			<textarea name="description" id="description" ><?php echo isset($gallery->description) ? $gallery->description : '';?></textarea>
		</li>
		<?php
		for($i = 1; $i <= $max_images; $i++): $j = ($i - 1);?>
		<li class="one_third">
			<label for="image<?php echo $i?>">Image <?php echo $i?>:</label>
				<?php if(isset($images[$j]))
				{
					
					echo '<div class="image_container">';
					echo form_hidden('image'.$i,$images[$j]->file_id);
					echo '<a class="fancybox" rel="group" href="'.base_url().'files/large/'.$images[$j]->file_id.'">';
					echo '<img class="home-image" src="'.base_url().'files/thumb/'.$images[$j]->file_id.'/160/160" width="160" />';
					echo '</a>';
					echo form_checkbox('chk_image'.$i, $i, FALSE, 'class="replace_img" id="chk_image'.$i.'" style="margin-left:10px"');
					echo form_label('Replace', 'chk_image'.$i);
					echo '</div>';
				}
				?>
			<input type="file" name="<?php echo "image".$i?>" id="<?php echo "image".$i?>" />
		</li>	
		<?php endfor; ?>
		<li class="clearfix">
			<input class="btn blue" type="submit" name="submit" value="Save Gallery" />
		</li>
	</ul>
</form>