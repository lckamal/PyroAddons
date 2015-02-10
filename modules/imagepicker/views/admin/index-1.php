<div id="upload-box" class="modal-box">
	<h3><?php echo lang('files:upload') ?><span class="close ui-icon ui-icon-closethick">&#10060;</span></h3>
    <?php echo form_open_multipart('imagepicker/upload') ?>
		<?php //echo form_hidden('redirect_to', uri_string()) ?>
		<ul>
			<li>
				<label for="name"><?php echo lang('files:name') ?></label>
				<?php echo form_input('name', set_value('name'), 'id="name"') ?>
			</li>
			<li>
				<label for="file">&nbsp;</label>
				<?php echo form_upload('userfile', 'id="file"') ?>
			</li>
			<li>
				<label for="folder_id"><?php echo lang('files:folder') ?></label>
				<?php echo form_dropdown('folder_id', array(0 => lang('files:select_folder')) + $folders_tree, 'id="folder"') ?>
			</li>
			<li>
				<label for="description"><?php echo lang('files:description') ?></label>
				<?php echo form_textarea('description', set_value('description'), 'id="description"') ?>
			</li>
			<li>
                <label for="file">&nbsp;</label>
				<?php echo form_submit('button_action', lang('save_label'), 'class="button"') ?>
				<a href="<?php echo current_url() ?>#" class="button cancel"><?php echo lang('cancel_label') ?></a>
			</li>
		</ul>
	<?php echo form_close() ?>
</div>
<div id="folder-box" class="modal-box">
    <h3><?php echo lang('files:new_folder') ?><span class="close ui-icon ui-icon-closethick">&#10060;</span></h3>
    <?php echo form_open('imagepicker/admin/new_folder') ?>
        <?php //echo form_hidden('redirect_to', uri_string()) ?>
        <ul>
            <li>
                <label for="folder_id"><?php echo lang('files:folder') ?></label>
                <?php echo form_dropdown('folder_id', array(0 => lang('files:select_folder')) + $folders_tree, 'id="folder"') ?>
            </li>
            <li>
                <label for="name"><?php echo lang('files:name') ?></label>
                <?php echo form_input('name', set_value('name'), 'id="name"') ?>
            </li>
            <li>
                <label for="file">&nbsp;</label>
                <?php echo form_submit('button_action', lang('save_label'), 'class="button"') ?>
                <a href="<?php echo current_url() ?>#" class="button cancel"><?php echo lang('cancel_label') ?></a>
            </li>
        </ul>
    <?php echo form_close() ?>
</div>
<div id="imagepicker-box">
<div id="files_browser">
	<div id="files_left_pane">
		<h3><?php echo lang('files:folders'); ?></h3>
        <ul class="button-group">
            <li>
                <?php echo anchor("imagepicker/upload", lang('files:upload'), 'title="upload" class="open-uploader"') ?>
                <?php echo anchor("imagepicker/new_folder", lang('files:new_folder'), 'title="New Folder" class="open-folderform"') ?>
            </li>
        </ul>
		<ul id="files-nav">
		<?php foreach ($folders as $folder): ?>
		<?php if ( ! $folder->parent_id): ?>
			<li id="folder-id-<?php echo $folder->id; ?>" class="<?php echo $current_folder && $current_folder->id == $folder->id ? 'current' : ''; ?>">
				<?php $fileType = $fileType ? $fileType : 'i'; ?>
				<?php echo anchor("admin/imagepicker/index/{$folder->id}/{$fileType}", $folder->name, 'title="'.$folder->slug.'"'); ?>
			</li>
		<?php endif; ?>
		<?php endforeach; ?>
		<?php if ($folders): ?>
			
		<?php endif ?>
		</ul>
	</div>
	<div id="files_right_pane">
		<div id="files-wrapper">
		<?php if ($current_folder): ?>
            <div class="files-header">
			<h3 class="folder-title"><?php echo $current_folder->name; ?></h3>
            <label class="subfolder-select" for="folder"><?php echo lang('files:subfolders'); ?>:
                <?php echo form_dropdown('parent_id', $subfolders, in_array($current_folder->id) ? $current_folder->id : '', 'id="parent_id" title="image"'); ?>
            </label>
            </div>
			<!-- folder contents -->
			<?php  if ($current_folder->items): ?>
			<table class="table-list" border="0">
				<thead>
					<tr>
						<th>Select</th>
						<th><?php echo lang('files:name') . '/' . lang('files:description'); ?></th>
						<th><?php echo lang('imagepicker.meta.width'); ?></th>
						<th><?php echo lang('imagepicker.meta.height'); ?></th>
						<th><?php echo lang('imagepicker.meta.size'); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($current_folder->items as $image): ?>
					<tr class="<?php echo alternator('', 'alt'); ?>">
						<td class="image">
							<div class="selectable">
                            <?php if($image->type == 'i'): ?>
								<img class="pyro-image" src="<?php echo base_url(); ?>files/thumb/<?php echo $image->id; ?>/50/50" alt="<?php echo $image->name; ?>" width="50"/>
                            <?php else: ?>
                                <a href="javascript:;" style="cursor:pointer"><?php echo $image->name ?></a>
                            <?php endif; ?>
								<span style="visibility: hidden; display: none;"><?php echo $image->id; ?></span>
								<input id="type" type="hidden" name="type" value="<?php echo $image->type; ?>" />
								<input id="name" type="hidden" name="name" value="<?php echo $image->name; ?>" />
							</div>
						</td>
						<td class="name-description">
							<p><?php echo $image->name; ?><p>
							<p><?php echo $image->description; ?></p>
						</td>
						<td class="meta width"><?php echo $image->width; ?></td>
						<td class="meta height"><?php echo $image->height; ?></td>
						<td class="meta size"><?php echo $image->filesize; ?></td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
			<?php else: ?>
			<p><?php echo lang('files.no_files'); ?></p>
			<?php endif; ?>
		<?php else: ?>
			<div class="blank-slate file-folders">
				<h2><?php echo lang('file_folders.no_folders');?></h2>
			</div>
		<?php endif; ?>
		</div>
	</div>
</div>
</div>
<script type="text/javascript">
    $(function(){
        $(".open-uploader").live('click', function(){
            $(".modal-box").hide();
            $("#upload-box").show();

            return false;
        });
        $(".open-folderform").live('click', function(){
            $(".modal-box").hide();
            $("#folder-box").show();

            return false;
        });

        $(".close, .cancel").live('click', function(){
            $(".modal-box").hide();
            return false;
        });

        $(".selectable").live('click', function(){
            var file_id = $(this).find('span').text();
            //parent.$("#preview_source_logo").html("Some Updated Text");
            var alignment = $('input[name=insert_float]:checked').val() || 'none';
            var size = $('#insert_width').val() || 0;
            var type = $(this).find('input#type').val();
            var name = $(this).find('input#name').val();
            var imageId = 0;
            $(this).children('span').each(function () {
                imageId = $(this).text();
            });

            parent.ImagePicker.close({
                imageId: imageId,
                size : size,
                alignment : alignment,
                type : type,
                name : name
            });
            return false;
        });

        $('select#parent_id').live('change', function() {
            var folder_id = $(this).val();
            var controller = $(this).attr('title');
            var href_val = SITE_URL + 'admin/imagepicker/index/' + folder_id + '/' + (parent.ImagePicker.options.fileType ? parent.ImagePicker.options.fileType : 'i');
            $('#files_right_pane').load(href_val + ' #files-wrapper', function() {
                $(this).children().fadeIn('slow');
                var class_exists = $('#folder-id-' + folder_id).html();
                $( 'div.notification' ).fadeOut('fast');
                if(class_exists !== null) {   
                    $('#files-nav li').removeClass('current');
                    $('li#folder-id-'+folder_id).addClass('current');
                }
            });
        });

        $('#files-nav li a').on('click', function(e) {
            e.preventDefault();
            var href_val = $(this).attr('href');

            //remove existing 'current' classes
            $('#files-nav li').removeClass('current');

            //add class to click anchor parent
            $(this).parent('li').addClass('current');
            //remove any notifications
            $( 'div.notification' ).fadeOut('fast');
            if ($(this).attr('title') != 'upload') {
                $('#files_right_pane').load(href_val + ' #files-wrapper', function() {
                    $(this).children().fadeIn('slow');
                });
            } else {
                var box = $('.modal-box');
                if (box.is( ":visible" )) {
                    // Hide - slide up.
                    box.fadeOut( 800 );
                } else {
                    // Show - slide down.
                    box.fadeIn( 800 );
                }
            }
        });
        //auto hide alert
        $(".alert.fadeIn").delay(5000).hide(0);
    });
</script>
