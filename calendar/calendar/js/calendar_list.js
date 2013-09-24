(function($)
{
	$(document).ready(function(){


    calendar.set("date_from");

    calendar.set("date_to");

    $("#calcontainerback").live('click', function(){
        $(this).css('display', 'none');
        $(".calendar-box").css('display', 'none');
    });
    
    
    $(window).scroll(function() {
           $("#calcontainerback").css('top', ($(window).scrollTop()) + "px");
    });


});
})(jQuery);

