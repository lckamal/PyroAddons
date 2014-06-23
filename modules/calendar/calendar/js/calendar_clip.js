(function($)
{
	$(document).ready(function(){

	$('.cc_body li.date_has_event').each(function () {
		
		var distance = 10;
		var time = 250;
		var hideDelay = 500;
        
		var hideDelayTimer = null;
        
		// tracker
		var beingShown = false;
		var overHidden = false;
		var shown = false;
        
		var cc_trigger = $(this);
		var cc_popup = $('.events .event_box .clip_event_content', this).css('opacity', 0);
			
        if(typeof cc_popup.get(0) != 'undefined'){

			// set the mouseover and mouseout on both element
			$([cc_trigger.get(0), cc_popup.get(0)]).mouseover(function () {
				// stops the hide event if we move from the cc_trigger to the cc_popup element
				
				if (hideDelayTimer) clearTimeout(hideDelayTimer);

				// don't cc_trigger the animation again if we're being shown, or already visible
				if (beingShown || shown) {
					return;
				} else {
					beingShown = true;
/*
					$('#clip_title').html(cc_popup.html());
					$('#clip_title').animate({
						bottom: '+=' + distance + 'px',
						opacity: 1
					}, time, 'swing', function() {
						// once the animation is complete, set the tracker variables
						beingShown = false;
						shown = true;
					});*/
					if($('#calclip').parent().parent().css('overflow-x')=='hidden'){
						overHidden = true;
						$('#calclip').parent().parent().css('overflow-x', 'visible');
					}
					// reset position of popup box
					cc_popup.css({
						top: 20,
						left: -76,
						width: 120,
						display: 'block' // brings the popup back in to view
					})

					// (we're using chaining on the popup) now animate it's opacity and position
					.animate({
						bottom: '+=' + distance + 'px',
						opacity: 1
					}, time, 'swing', function() {
						// once the animation is complete, set the tracker variables
						beingShown = false;
						shown = true;
					});
					// reset position of cc_popup box
				}
			}).mouseout(function () {
				// reset the timer if we get fired again - avoids double animations
				if (hideDelayTimer) clearTimeout(hideDelayTimer);

				// store the timer so that it can be cleared in the mouseover if required
				hideDelayTimer = setTimeout(function () {
					hideDelayTimer = null;
					/*$('#clip_title').animate({
						bottom: '-=' + distance + 'px',
						opacity: 0
					}, time, 'swing', function () {
						// once the animate is complete, set the tracker variables
						shown = false;
						// hide the cc_popup entirely after the effect (opacity alone doesn't do the job)
						cc_popup.css('display', 'none');
					});*/
					
					cc_popup.animate({
						bottom: '-=' + distance + 'px',
						opacity: 0
					}, time, 'swing', function () {
						// once the animate is complete, set the tracker variables
						shown = false;
						// hide the popup entirely after the effect (opacity alone doesn't do the job)
						cc_popup.css('display', 'none');
					});
					if(overHidden){
						setTimeout(function(){
						$('#calclip').parent().parent().css('overflow-x', 'hidden');
						}, hideDelay);
					}
				}, hideDelay);
			});
		}
	});
	
	
});







})(jQuery);
