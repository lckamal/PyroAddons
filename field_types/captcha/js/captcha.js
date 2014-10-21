$(function(){
	$(".reload-captcha").on('click', function(e){
		var form_slug = $(this).data('form_slug');
		var $img = $(this).prev('img');
		console.log($img);
		$.post(BASE_URL + 'streams_core/public_ajax/field/captcha/captcha', {img_width : '100', img_height : '30', form_slug : form_slug}, function(data){
			$img.replaceWith(data);
		})

		e.preventDefault();
	});
});