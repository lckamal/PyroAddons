(function($)
{
	$(document).ready(function(){

//configure the date format to match mysql date
	//$('#date').datepicker({ dateFormat: 'yy-mm-dd' });

	if($("#datepicker2").length > 0){
		$("#datepicker2").datepicker({dateFormat: 'yy-mm-dd'});
	}

	var formatDigit = function(dx){
		if(dx.toString().length == 1) dx = '0' + dx.toString();
		return dx;
	};
	$('.date_has_event').each(function () {
		// options
		
		var distance = 10;
		var time = 250;
		var hideDelay = 500;
        
		var hideDelayTimer = null;
        
		// tracker
		var beingShown = false;
		var shown = false;
        
		var trigger = $(this);
		var popup = $('.events ul li', this).css('opacity', 0);
        
		// set the mouseover and mouseout on both element
		$([trigger.get(0), popup.get(0)]).mouseover(function () {
			// stops the hide event if we move from the trigger to the popup element
			if (hideDelayTimer) clearTimeout(hideDelayTimer);

			// don't trigger the animation again if we're being shown, or already visible
			if (beingShown || shown) {
				return;
			} else {
				beingShown = true;

				// reset position of popup box
				popup.css({
					top: 20,
					left: -76,
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
			}
		}).mouseout(function () {
			// reset the timer if we get fired again - avoids double animations
			if (hideDelayTimer) clearTimeout(hideDelayTimer);

			// store the timer so that it can be cleared in the mouseover if required
			hideDelayTimer = setTimeout(function () {
				hideDelayTimer = null;
				popup.animate({
					bottom: '-=' + distance + 'px',
					opacity: 0
				}, time, 'swing', function () {
					// once the animate is complete, set the tracker variables
					shown = false;
					// hide the popup entirely after the effect (opacity alone doesn't do the job)
					popup.css('display', 'none');
				});
			}, hideDelay);
		});
	});
	
	
	$.get(SITE_URL+'admin/calendar/ajax_group', function(data) {
	  if(data == 'nok'){
		$(".calsetting").css('display', 'none');
	  }
	});
	
	var first_load = true;
	
    var showRepeatPrm = function(){
      var ob_rep = $('select[name="repeat_type"]').chosen();
      if(ob_rep.val() == 0){
		$('#'+$('select[name="repeat_time"]').attr('id')+'_chzn').show();
		$('._time_label').css('display','inline-block');
		$('#'+$('select[name="repeat_day"]').attr('id')+'_chzn').hide();
		$('._day_label').css('display','none');
		$('#'+$('select[name="repeat_date"]').attr('id')+'_chzn').hide();
		$('._date_label').css('display','none');
	  }
	  if(ob_rep.val() == 1){
		$('#'+$('select[name="repeat_time"]').attr('id')+'_chzn').show();
		$('._time_label').css('display','inline-block');
		$('#'+$('select[name="repeat_day"]').attr('id')+'_chzn').show();
		$('._day_label').css('display','inline-block');
		$('#'+$('select[name="repeat_date"]').attr('id')+'_chzn').hide();
		$('._date_label').css('display','none');
	  }
	  if(ob_rep.val() == 2){
		$('#'+$('select[name="repeat_time"]').attr('id')+'_chzn').show();
		$('._time_label').css('display','inline-block');
		$('#'+$('select[name="repeat_day"]').attr('id')+'_chzn').hide();
		$('._day_label').css('display','none');
		$('#'+$('select[name="repeat_date"]').attr('id')+'_chzn').show();
		$('._date_label').css('display','inline-block');
	  }
	  $('select[name="repeat_type"]').val(ob_rep.val());
    }
	
	var strDateEvent = new Array("", "");
	$('input[name="event_repeat"]').live('click', function() {
		
	  if($(this).val() == 1){
		  $('#repeat_box').show();
		  $('#repeat_title').show();
		  $('._eventdate').hide();
		  var curdate=new Date();
		  var strdate = curdate.getFullYear()+'-'+formatDigit(curdate.getMonth()+1)+'-'+formatDigit(curdate.getDate());
		  if ($('input[name="event_date_begin"]').val() != "") {
			  strDateEvent[0] = $('input[name="event_date_begin"]').val();
		  }else if ($('input[name="event_date_begin"]').val() == "") {
			  $('input[name="event_date_begin"]').val(strdate);
			  strDateEvent[0] = strdate;
		  }
		  if ($('input[name="event_date_end"]').val() != "") {
			  strDateEvent[1] = $('input[name="event_date_end"]').val();
		  }
		  $('#repeat_box_br').css('display', 'block');
	  }else{
		  $('#repeat_box').hide();
		  $('#repeat_title').hide();
		  $('._eventdate').show();
		  $('#repeat_box_br').css('display', 'none');
		  $('input[name="event_date_begin"]').val(strDateEvent[0]);
		  $('input[name="event_date_end"]').val(strDateEvent[1]);
	  }
	  
	  first_load = false;
	});
	
	if ($('#repeat_box_br').get(0) != undefined) {
		$('#repeat_box_br').css('display', 'none');
	}
	setTimeout(function(){
		initFormInput();
	}, 700);
	
	var frmInputCounter = 0;
	var initFormInput = function(){
		var ob_chzn = $('._eventdate [id*=_chzn]').get(0);
		if (ob_chzn != undefined && frmInputCounter < 5) {
			strDateEvent[0] = $('input[name="event_date_begin"]').val();
			strDateEvent[1] = $('input[name="event_date_end"]').val();
			if ($('input[name="event_repeat"]').get(0) != undefined) {
				var checkedval = $('input[name="event_repeat"]:checked').val();
				if (checkedval == 1) {
					$('#repeat_box').show();
					$('#repeat_title').show();
					$('._eventdate').hide();
					$('#repeat_box_br').css('display', 'block');
				}else{
					$('#repeat_box').hide();
					$('#repeat_title').hide();
					$('._eventdate').show();
					$('#repeat_box_br').css('display', 'none');
				}
			}
			
			var seret = $('#'+$('select[name="repeat_time"]').attr('id')+'_chzn');
			seret.show();
			seret.before('<div class="repeat_label _time_label" style="display:inline-block;">'+$('select[name="repeat_time"]').attr("title")+'</div>');
			var sered = $('#'+$('select[name="repeat_day"]').attr('id')+'_chzn');
			sered.hide();
			sered.before('<div class="repeat_label _day_label" style="display:none;">'+$('select[name="repeat_day"]').attr("title")+'</div>');
			var serea = $('#'+$('select[name="repeat_date"]').attr('id')+'_chzn');
			serea.hide();
			serea.before('<div class="repeat_label _date_label" style="display:none;">'+$('select[name="repeat_date"]').attr("title")+'</div>');
			
			$('input[name="event_repeat"]:selected').click();
			showRepeatPrm();
		}else{
			setTimeout(function(){
				frmInputCounter++;
				initFormInput();
			}, 300);
		}
		
		
	}
	
	$('select[name="repeat_type"]').live('change', function() {
	  showRepeatPrm();
	});
	
	setTimeout(function(){
		$('ul>li.date-meta').livequery(function(){
			$('ul>li.date-meta .chzn-container').each(function(i, ele){
				$(ele).css('width', '116px');
				$(ele).find('.chzn-drop').css('width', '114px');
				$(ele).find('.chzn-search input').css('width', '80px');
				$(ele).find('.search-field input').css('width', '105px');
			}); 
		});
		
	}, 1000);
    
	setTimeout(function(){
		$('#repeat_box').livequery(function(){
			$('#repeat_box .chzn-container').each(function(i, ele){
				$(ele).css('width', '136px');
				$(ele).find('.chzn-drop').css('width', '134px');
				$(ele).find('.chzn-search input').css('width', '100px');
				$(ele).find('.search-field input').css('width', '125px');
			}); 
		});
	}, 1600);
	
});







})(jQuery);
