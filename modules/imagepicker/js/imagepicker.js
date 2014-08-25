// A closure, to keep thing private.
// http://enterprisejquery.com/2010/10/how-good-c-habits-can-encourage-bad-javascript-habits-part-1/
(function(ImagePicker, $, undefined) {

 	// Define some default properties.
 	var DEFAULT_COLORBOX_WIDTH			= 1200,
		DEFAULT_COLORBOX_HEIGHT			= 650,
		//INITIAL_IMAGEPICKER_URL			= 'streams_core/public_ajax/field/imagepicker/viewpicker/0';
		INITIAL_IMAGEPICKER_URL			= 'admin/imagepicker/index/0';

	// Options for the php part.
	var options = {
		showSizeSlider					: false,
		showAlignButtons				: false,
		onPickCallback					: function() {},
		fileType						: 'i',
	};

	/**
	 * Opens a colorbox dialog and opens the url to the imagepicker.
	 *
	 * @param opts Options array
	 */
	ImagePicker.open = function(opts) {
		// Extend the options object with the provided options.
		$.extend(options, opts);

		var urlToOpen = INITIAL_IMAGEPICKER_URL + '/' + (options.showSizeSlider ? '1' : '0') + '/' + (options.showAlignButtons ? '1' : '0') + '/' + (options.fileType ? options.fileType : 'i');;

		// Open a colorbox dialog.
		$.colorbox({
			href			: urlToOpen,
			initialWidth	: DEFAULT_COLORBOX_WIDTH,
			initialHeight	: DEFAULT_COLORBOX_HEIGHT,
			width			: DEFAULT_COLORBOX_WIDTH,
			height			: DEFAULT_COLORBOX_HEIGHT,
			iframe 			: true,
		});
	}

	ImagePicker.close = function(opts) {
		
		var alignment = opts.alignment || $('input[name=insert_float]:checked').val() || 'none';
		var size = opts.size || $('#insert_width').val() || 0;
		var type = opts.type || $(this).find('input#type').val();
		var name = opts.name || $(this).find('input#name').val();
		var imageId = opts.imageId || 0;
		
		options.onPickCallback(imageId, size, alignment, type, name);

		// Close the colorbox.
		$.colorbox.close();
	};

}(window.ImagePicker = window.ImagePicker || {}, jQuery));

$(".selectable").on('click', function(){
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

    ImagePicker.close({
        imageId: imageId,
        size : size,
        alignment : alignment,
        type : type,
        name : name
    });
    return false;
});