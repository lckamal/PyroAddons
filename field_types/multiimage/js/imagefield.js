$(function() {
  $('.image_remove').on('click', function(e){
  	var file_id = $(this).attr('data-file');
  	var $file_field = $(this).closest('ul.list-group').next('input[type="hidden"]');
  	var files = $file_field.val();
  	var file_parts = files.split('|');
  	
  	var file_index = file_parts.indexOf(file_id);
  	
  	if (file_index > -1) {
	    file_parts.splice(file_index, 1);
	}

  	var file_values = file_parts.join('|');
	$file_field.val(file_values);
	$(this).closest('li').remove();
    return false;
  });
});
