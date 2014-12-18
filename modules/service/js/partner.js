function get_remote()
{
  var ajaxcaptchaTime= new Date().getTime();
  var totalcaptchaTime;
  $.ajax({
  url: SITE_URL+"reservation/ajax/recaptcha_check_answer?response=ashdvbaghsvd&challenge=ahsdbahsd",
  type : 'GET',
  async : false,
  }).done(function() {
  totalcaptchaTime = new Date().getTime()-ajaxcaptchaTime;
});
  return totalcaptchaTime;
}

$(function() {
  capval = get_remote();

// check if they have the flash players.

$("#show_route").on('click', function()
  {
    non_empty_one = $(".non_empty_one").val();
    non_empty_two = $(".non_empty_two").val();
    if((non_empty_one != "") && (non_empty_two != ""))
    {
    initialize(non_empty_one,non_empty_two);
    }
  });
});



$(document).ready(function() {
  var totalTime = 0;
  $( "#my_loca" ).change(function() {
    var my_loc = $(this).val();
    var ajaxTime= new Date().getTime();

    $.getJSON(SITE_URL+"partner/partner_search",{'partner' : partner}).done(function(d){
      my_iso = d.iso_code_2;
      my_lat = d.lattitude;
      my_long = d.longitude;
    }).done(function () {
    var totalTime = new Date().getTime()-ajaxTime;
    t = setTimeout("autocompleteinit()",totalTime);
    // Here I want to get the how long it took to load some.php and use it further
    });
}).change();


/*  $('#my_loca').change(function(){
    
  }).change();*/


   $( "#currently_in" ).change(function() {
     var currently_in = $(this).val();

    $.getJSON(SITE_URL+"reservation/ajax/get_currently_in",{'currently_in' : currently_in}).done(function(d){
    });

  }).change();
});



  var location1;
  var location2;
  
  var address1;
  var address2;

  var latlng;
  var geocoder;
  var map;
  
  var distance;
  
  // finds the coordinates for the two locations and calls the showMap() function



  function autocompleteinit() {  
    distance = undefined;
    if(distance == undefined)
    {
      $(".hideproceed").hide();
    }

    var input = document.getElementById('address1');
    var input1 = document.getElementById('address2')


    var options = {
    componentRestrictions: {country: my_iso}
    };

    var service1 = new google.maps.places.Autocomplete(input, options);
    var service2 = new google.maps.places.Autocomplete(input1, options);

    var mapOptions = {
    center: new google.maps.LatLng(my_lat, my_long),
    zoom: 8,
    mapTypeId: google.maps.MapTypeId.ROADMAP
    };

    var map = new google.maps.Map(document.getElementById("map_canvas"),
    mapOptions);
  }



// we will use this code once we find out the lattitude and logitude of africa
/*  var southWest = new google.maps.LatLng( 25.341233, 68.289986 );
  var northEast = new google.maps.LatLng( 25.450715, 68.428345 );
  var hyderabadBounds = new google.maps.LatLngBounds( southWest, northEast );

  var options = {
    bounds: hyderabadBounds,
    types:  ['establishment [or whatever fits your usage]'],
    componentRestrictions: { country: 'pk' }
  };*/


  function initialize(param1,param2)
  {
    geocoder = new google.maps.Geocoder(); // creating a new geocode object
    address1 = param1;
    address2 = param2;

    // getting the two address values
  /*  address1 = document.getElementById("address1").value;
    address2 = document.getElementById("address2").value;*/
    

    // finding out the coordinates
    if (geocoder) 
    {
      geocoder.geocode( { 'address': address1}, function(results, status) 
      {
        if (status == google.maps.GeocoderStatus.OK) 
        {
          //location of first address (latitude + longitude)
          location1 = results[0].geometry.location;
          var location1_type = results[0].types;
          $.post(SITE_URL+"reservation/ajax/get_location1_type", { 'location1_type': location1_type});
          
        } else 
        {
          alert("Geocode was not successful for the following reason: " + status);
        }
      });
      geocoder.geocode( { 'address': address2}, function(results, status) 
      {
        if (status == google.maps.GeocoderStatus.OK) 
        {
          //location of second address (latitude + longitude)
          location2 = results[0].geometry.location;
          var location2_type = results[0].types;
          $.post(SITE_URL+"reservation/ajax/get_location2_type", { 'location2_type': location2_type});
          // calling the showMap() function to create and show the map 
          showMap();
        } else 
        {
          alert("Geocode was not successful for the following reason: " + status);
        }
      });
    }
  }
    
  // creates and shows the map
  function showMap()
  {



    // center of the map (compute the mean value between the two locations)
    latlng = new google.maps.LatLng((location1.lat()+location2.lat())/2,(location1.lng()+location2.lng())/2);
    
    // set map options
      // set zoom level
      // set center
      // map type
    var mapOptions = 
    {
      zoom: 1,
      center: latlng,
      mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    
    // create a new map object
      // set the div id where it will be shown
      // set the map options
    map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);
    
    // show route between the points
    directionsService = new google.maps.DirectionsService();
    directionsDisplay = new google.maps.DirectionsRenderer(
    {
      suppressMarkers: true,
      suppressInfoWindows: true
    });
    directionsDisplay.setMap(map);
    var request = {
      origin:location1, 
      destination:location2,
      travelMode: google.maps.DirectionsTravelMode.DRIVING
    };
    directionsService.route(request, function(response, status) 
    {
      if (status == google.maps.DirectionsStatus.OK) 
      {
        directionsDisplay.setDirections(response);
        var distance = response.routes[0].legs[0].distance.text;

        if(distance != 0)
        {
        if(distance != undefined)
        {
        $(".hideproceed").show();
        }
        }



        $.post(SITE_URL+"reservation/ajax/store_distance", { 'distance': distance});
        $.post(SITE_URL+"reservation/ajax/store_address1", { 'address1': address1});
        $.post(SITE_URL+"reservation/ajax/store_address2", { 'address2': address2});

        //distance = "The distance between the two points on the chosen route is: "+response.routes[0].legs[0].distance.text;
        distance = "<br/>The aproximative driving time is: "+response.routes[0].legs[0].duration.text;
        document.getElementById("distance_road").innerHTML = distance;
      }
    });
    
    // show a line between the two points
    var line = new google.maps.Polyline({
      map: map, 
      path: [location1, location2],
      strokeWeight: 7,
      strokeOpacity: 0.8,
      strokeColor: "#FFAA00"
    });
    
    // create the markers for the two locations   
    var marker1 = new google.maps.Marker({
      map: map, 
      position: location1,
      title: address1,
      draggable: true
    });
    var marker2 = new google.maps.Marker({
      map: map, 
      position: location2,
      title: address2,
      draggable: true
    });




    
    // create the text to be shown in the infowindows
    var text1 = '<div id="content">'+
        '<h1 id="firstHeading">First location</h1>'+
        '<div id="bodyContent">'+
        '<p>Coordinates: '+location1+'</p>'+
        '<p>Address: '+address1+'</p>'+
        '</div>'+
        '</div>';
        
    var text2 = '<div id="content">'+
      '<h1 id="firstHeading">Second location</h1>'+
      '<div id="bodyContent">'+
      '<p>Coordinates: '+location2+'</p>'+
      '<p>Address: '+address2+'</p>'+
      '</div>'+
      '</div>';
    
    // create info boxes for the two markers
    var infowindow1 = new google.maps.InfoWindow({
      content: text1
    });
    var infowindow2 = new google.maps.InfoWindow({
      content: text2
    });

    // add action events so the info windows will be shown when the marker is clicked
    google.maps.event.addListener(marker1, 'click', function() {
      infowindow1.open(map,marker1);
    });
    google.maps.event.addListener(marker2, 'click', function() {
      infowindow2.open(map,marker1);
    });
    
    // compute distance between the two points
    var R = 6371; 
    var dLat = toRad(location2.lat()-location1.lat());
    var dLon = toRad(location2.lng()-location1.lng()); 
    
    var dLat1 = toRad(location1.lat());
    var dLat2 = toRad(location2.lat());
    
    var a = Math.sin(dLat/2) * Math.sin(dLat/2) +
        Math.cos(dLat1) * Math.cos(dLat1) * 
        Math.sin(dLon/2) * Math.sin(dLon/2); 
    var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a)); 
    var d = R * c;
    
    /*document.getElementById("distance_direct").innerHTML = "<br/>The distance between the two points (in a straight line) is: "+d;*/
  }
  
  function toRad(deg) 
  {
    return deg * Math.PI/180;
    input = "";
    input1 = "";
  }





$(function(){
    var startyear = new Date().getFullYear();
    var endyear = startyear + 2;
    var yearRange = startyear + ":" + endyear;


    //$(".datepicker").datepicker({dateFormat: 'yy-mm-dd',minDate: new Date, yearRange: yearRange, changeMonth: true, changeYear: true});
    $( ".date_from_today" ).datepicker({
            dateFormat: 'yy-mm-dd', yearRange: yearRange, changeMonth: true, changeYear: true, minDate: new Date,
            onClose: function( selectedDate ) {
                    $( ".date_to" ).datepicker( "option", "minDate", selectedDate );
            }
    });
    $( ".date_to" ).datepicker({
            dateFormat: 'yy-mm-dd', yearRange: yearRange, changeMonth: true, changeYear: true,
            onClose: function( selectedDate ) {
                    $( ".date_from_today" ).datepicker( "option", "maxDate", selectedDate );
            }
    });
  //$( ".datepicker" ).datepicker();
  if($.isFunction($.fn.timepicker)){
  	$('.timepicker').timepicker({ 'step': 15, 'timeFormat': 'h:i A' });
  }

  //on change location filter cities
  $("#location").on('change', function(){
    //$("body").append("<div>Loading..</div>");
    var country_id = $(this).val()
    //pickupcity
    $.getJSON( SITE_URL + "reservation/ajax/get_locations", { country_id: country_id, type: 'pickup' },function(data) {
       //$("#pickupcity_container").html(data);

        //city
        var pickupcityselect = $("#pickupcity"); 
        var dropoffcityselect = $("#dropoffcity");
        var pickupcityoptions = pickupcityselect.prop('options');
        var dropoffcityoptions = dropoffcityselect.prop('options'); 
        //hotel
        var pickuphotelselect = $("#pickuphotel"); 
        var dropoffhotelselect = $("#dropoffhotel");
        var pickuphoteloptions = pickuphotelselect.prop('options');
        var dropoffhoteloptions = dropoffhotelselect.prop('options'); 
        //airport
        var pickupairportselect = $("#pickupairport"); 
        var dropoffairportselect = $("#dropoffairport");
        var pickupairportoptions = pickupairportselect.prop('options');
        var dropoffairportoptions = dropoffairportselect.prop('options'); 
        //business
        var pickupbusinessselect = $("#pickupbusiness"); 
        var dropoffbusinessselect = $("#dropoffbusiness");
        var pickupbusinessoptions = pickupbusinessselect.prop('options');
        var dropoffbusinessoptions = dropoffbusinessselect.prop('options');

         $.each(data, function(opt, key) {
          
          //replace cities options with json data
          if (opt == "cities") {
            $('option', pickupcityselect).remove();
            $('option', dropoffcityselect).remove();
            $.each(key, function(opt1, key1) {
              pickupcityoptions[pickupcityoptions.length] = new Option(key1, opt1);
              dropoffcityoptions[dropoffcityoptions.length] = new Option(key1, opt1);
            });
          } 

          //replace hotels options with json data
          else if (opt == "hotels") {
            $('option', pickuphotelselect).remove();
            $('option', dropoffhotelselect).remove();
            $.each(key, function(opt1, key1) {
              pickuphoteloptions[pickuphoteloptions.length] = new Option(key1, opt1);
              dropoffhoteloptions[dropoffhoteloptions.length] = new Option(key1, opt1);
            });
          }

          //replace airport options with json data
          else if (opt == "airports") {
            $('option', pickupairportselect).remove();
            $('option', dropoffairportselect).remove();
            $.each(key, function(opt1, key1) {
              pickupairportoptions[pickupairportoptions.length] = new Option(key1, opt1);
              dropoffairportoptions[dropoffairportoptions.length] = new Option(key1, opt1);
            });
          } 

          //replace business options with json data
          else if (opt == "businesses") {
            $('option', pickupbusinessselect).remove();
            $('option', dropoffbusinessselect).remove();
            $.each(key, function(opt1, key1) {
              pickupbusinessoptions[pickupbusinessoptions.length] = new Option(key1, opt1);
              dropoffbusinessoptions[dropoffbusinessoptions.length] = new Option(key1, opt1);
            });
          }

          //if payment is enabled or not
          else if(opt == "payment"){
           if(key == true){
            $('.payment_info').show().removeClass('label-danger').addClass('label-success').text('Payment available for this location');
           }
           else{
            $('.payment_info').show().removeClass('label-success').addClass('label-danger').text('Payment not available for this location');
           }

         
          //if the user chooses no country
          }
        value=$('#location').val();
        if(value==0)
        {
           $('.payment_info').show().removeClass('label-success').addClass('label-danger').text('Please select a country.');

        }
        });
    
    });
    
  }).change();
  
  $("input[name='travel']").on('change', function(){
    var value = $("input[name='travel']:checked").val();

    if(value == 'One Way'){
      $(".return_dt").hide();
      $(".return_dt").find("input").attr('required', false);
    }
    else{
      $(".return_dt").show();
      $(".return_dt").find("input").attr('required', true);
    }
    
  }).change();


  $("legend").click(function () {

  	var $text = $(this).find("span");
  	$text.toggleClass('glyphicon-chevron-down').toggleClass('glyphicon-chevron-up');
  //$(this).find("span").text("+");
  $(this).next(".showandhide").slideToggle("slow");
  });


  $("#bookingtype").on('change', function(){
      var value=$("#bookingtype").val();
      var $form_group = $(this).closest('div.form-group');
      if (value=='individual') {
        $form_group.removeClass('col-3').addClass('col-6');
         $("#noofpass").hide();
         $("#noofpass").find('input').attr('required',false).val(1);
      }
      else
      {
        $form_group.removeClass('col-6').addClass('col-3');
         $("#noofpass").show();
         $("#noofpass").find('input').attr('required',true);
         $("#noofpass").find('input').attr('required',false).val('');     
      }
  }).change();

    // when focus is done in the no of bags the div is added.
    $('.bags').focus(function(){ 
    $('<div class="label label-info bags" style="position:absolute; top:0; left:100px;">More than two bags cost charge.</div>').insertAfter(this);
  });


    // when focus is left in the no of bags the div will be removed.
    $('.bags').blur(function(){
      $(this).next('.bags').remove();     
      });


    //when focus is done in the no of pets the div will be added. 
    $('.pets').focus(function(){
    $('<div class="label label-info pets" style="position:absolute; top:0; left:100px;">Pets will chage extra fee.</div>').insertAfter(this);
    });


    //when the focus is left in the no of pets the div will be removed.
    $('.pets').blur(function(){
    $(this).next('.pets').remove();     
    });

    $('.age_two').on('click', function(){
      var val = $(this).val();
    if(val == '0')
    {
      $(this).closest('#overage2').find('span').show();
    }
    else{
      $(this).closest('#overage2').find('span').hide();
    }
    }).click();

    


    //when the checkbox for the passenger age is checked the div will be added.
    //$('.agetwo').focus(function(){
    //$('<div class = "agetwo">Pets will chage extra fee.</div>').insertAfter(this);
    //});

    //$('.agetwo').blur(function(){
    //$(this).next('.agetwo').remove();
    //});

  $('#pickupoptions').on('change', function(){
    val = $('#pickupoptions').val();
    if(val=='airport')
    {
      $('.pickupairport').show();
      $('.pickupbusiness').hide();
      $('.pickuphotel').hide();
      $('.pickupcity').hide();
    }
    else if(val=='business')
    {
      $('.pickupbusiness').show();
      $('.pickupairport').hide();
      $('.pickuphotel').hide();
      $('.pickupcity').hide();
    }
    else if(val=='hotel')
    {
      $('.pickuphotel').show();
      $('.pickupbusiness').hide();
      $('.pickupairport').hide();
      $('.pickupcity').hide();
    }
    else if(val=='other')
    {
      $('.pickupcity').show();
      $('.pickupbusiness').hide();
      $('.pickuphotel').hide();
      $('.pickupairport').hide();
    }
    else
    {
      //this happens when there is no value
    }
  }).change();



  $('#dropoffoptions').on('change', function()
  {
  val = $('#dropoffoptions').val();
    if(val=='airport')
    {
      $('.dropoffairport').show();
      $('.dropoffbusiness').hide();
      $('.dropoffhotel').hide();
      $('.dropoffcity').hide();
    }
    else if(val=='business')
    {
      $('.dropoffbusiness').show();
      $('.dropoffairport').hide();
      $('.dropoffhotel').hide();
      $('.dropoffcity').hide();
    }
    else if(val=='hotel')
    {
      $('.dropoffhotel').show();
      $('.dropoffbusiness').hide();
      $('.dropoffairport').hide();
      $('.dropoffcity').hide();
    }
    else if(val=='other')
    {
      $('.dropoffcity').show();
      $('.dropoffbusiness').hide();
      $('.dropoffhotel').hide();
      $('.dropoffairport').hide();
    }
    else
    {
    //this happens when there is no value
    }

  }
).change();


$('#need_driver').click(function(){
  if($(this).is(':checked'))
  {
    $('.need_driver').show();
    $('.driver_date').show();
    $('.need_driver_vehicle').hide();
  }
});


$('#need_driver_vehicle').click(function(){
if($(this).is(':checked'))
{
  $('.need_driver').hide();
  $('.need_driver_vehicle').show();
  $('.driver_date').show();
}
});

$('#dont_need').click(function(){
if($(this).is(':checked'))
{
  $('.need_driver').hide();
  $('.need_driver_vehicle').hide();
  $('.driver_date').hide();
}
});



$('#no_need_anything').click(function(){
  if($(this).is(':checked'))
  {
  $('.need_driver').hide();
  $('.need_driver_vehicle').hide();
  $('.driver_date').hide();
  }
});



$('#need_driver').click(function(){

if($(this).is(':checked'))
{
  $('.has_no_vehicle').hide();
  $('.has_vehicle').show();
  $('.dateandtime').show();
}

});


$('#need_driver_vehicle').click(function(){

if($(this).is(':checked'))
{
  $('.has_vehicle').hide();
  $('.has_no_vehicle').show();
  $('.dateandtime').show();
}
});


$('#no_need_anything').click(function(){
if($(this).is(':checked'))
{
  $('.has_vehicle').hide();
  $('.has_no_vehicle').hide();
  $('.dateandtime').hide();
}
});
// this is the check for the checked field


$('#my_loca').on('click', function(){
      $('.showloc').show();
    }).click();

$('#need_airport').on('click',function(){
    $('.hide_book_airport').show();
}).click();


$('#need_not_airport').on('click',function(){
    $('.hide_book_airport').hide();
});

});

function test(response, challenge)
{
    $.ajax({
    url: SITE_URL+"reservation/ajax/recaptcha_check_answer",
    type : 'GET',
    data: { response : response, challenge : challenge},
    async : false,
    }).done(function(d) {
      if(d == 1)
      {
        $('.captcha_error').hide();
        $('.captcha_right').show();
        s = 1;
      }
      else if(d == 0)
      {
       $('.captcha_error').show();
       $('.catpcha_right').hide();
       s = 0;
      }
      else
      {
        alert('no input');
        s = 3;
      }
    });
    return s;
    
/*      sleep(capval);
      return false;*/
}

function check_submit()
{
    var response = $("#recaptcha_response_field").val();
    var challenge = $("#recaptcha_challenge_field").val();
    status = test(response, challenge);
    sleep(capval);
    if(status == 0)
    {
    return false;
    }
    if(status == 1)
    {
    return true;
    }
}


//this function makes other function sleep for certain time.
function sleep(milliseconds) {
  var start = new Date().getTime();
  for (var i = 0; i < 1e7; i++) {
    if ((new Date().getTime() - start) > milliseconds){
      break;
    }
  }
}
