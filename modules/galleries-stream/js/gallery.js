
//fancybox gallery init
if($.isFunction($.fn.fancybox)){
	$(".fancybox").fancybox({type:'image'});
}

// Prettyprint
$('pre').addClass('prettyprint linenums');

//close message box
$('.close').on('click', 'a', function(){
	$(this).closest('.alert').hide();
});
$('.fadeout').delay(90000).fadeOut("slow");

//fancybox init
if($.isFunction($.fn.fancybox)){
	$(".fancybox").fancybox({type: 'image'});
		  
	//fancybox ajax popup init
	$(".various").fancybox({
		maxWidth	: 800,
		maxHeight	: 600,
		fitToView	: false,
		width		: '70%',
		height		: '70%',
		autoSize	: false,
		closeClick	: false,
		openEffect	: 'none',
		closeEffect	: 'none'
	});
}