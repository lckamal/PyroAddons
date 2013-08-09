// A closure, to keep thing private.
// http://enterprisejquery.com/2010/10/how-good-c-habits-can-encourage-bad-javascript-habits-part-1/
(function(ImagePicker, $, undefined) {

 	// Define some default properties.
 	var DEFAULT_COLORBOX_WIDTH			= 1200,
		DEFAULT_COLORBOX_HEIGHT			= 650,
		INITIAL_IMAGEPICKER_URL			= 'admin/imagepicker/index/0';

	// Options for the php part.
	var options = {
		showSizeSlider					: false,
		showAlignButtons				: false,
		onPickCallback					: function() {},
		fileType						: 'i'
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
			height			: DEFAULT_COLORBOX_HEIGHT
		});
	}

	$('#imagepicker-box .selectable').livequery('click', function() {
		var alignment = $('input[name=insert_float]:checked').val() || 'none';
		var size = $('#insert_width').val() || 0;
		var type = $(this).find('input#type').val();
		var name = $(this).find('input#name').val();
		var imageId = 0;
		$(this).children('span').each(function () {
			imageId = $(this).text();
		});
		// Call the callback so the developer that uses this module can do whatever he wants to do with the data...
		options.onPickCallback(imageId, size, alignment, type, name);

		// Close the colorbox.
		$.colorbox.close();
	});

	/**
	 * left files navigation handler
	 * - handles loading of different folders
	 * - manipulates dom classes etc
	 */
	$('#files-nav li a').live('click', function(e) {
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
			var box = $('#upload-box');
		    if (box.is( ":visible" )) {
				// Hide - slide up.
				box.fadeOut( 800 );
			} else {
				// Show - slide down.
				box.fadeIn( 800 );
			}
		}
	});

	$( '#files_right_pane' ).livequery(function() {
		$(this).children().fadeIn('slow');
		$('#upload-box').hide();
	});

	$('select[name=parent_id]').live('change', function() {
		var folder_id = $(this).val();
		var controller = $(this).attr('title');
		var href_val = SITE_URL + 'admin/imagepicker/index/' + folder_id + '/' + (options.showSizeSlider ? '1' : '0') + '/' + (options.showAlignButtons ? '1' : '0') + '/' + (options.fileType ? options.fileType : 'i');
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

	$( "#slider" ).livequery(function() {
		$(this).fadeIn('slow');
		$(this).slider({
			value:200,
			min: 50,
			max: 800,
			step: 1,
			slide: function( event, ui ) {
				$( "#insert_width" ).val( ui.value + 'px' );
			}
		});
		$( "#insert_width" ).val( $( "#slider" ).slider( "value" ) + 'px' );
	});

	$('#radio-group').livequery(function(){
		$(this).children('.set').buttonset();
		$(this).fadeIn('slow');
	});

}(window.ImagePicker = window.ImagePicker || {}, jQuery));


