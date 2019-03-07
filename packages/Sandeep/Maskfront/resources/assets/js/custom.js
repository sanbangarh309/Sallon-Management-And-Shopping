//STEPS-SECTION
var book_date = '';
var book_time = '';
var avail_dta = $('#avail').val();
// if (avail_dta) {
try {
  var avail_dta = JSON.parse($('#avail_data').val());
}catch(err){
  var avail_dta = {days:[]};
}
var avail_arr = $.map(avail_dta.days, function(value, index) {
  return [index];
});
// }
// else{
//     var avail_arr = [];
// }
// date :- ui-state-active
// month:- ui-datepicker-month
// year:- ui-datepicker-year
$(function() {
  var session_date = $('#book_date_san').val();
  if (session_date) {
      $('#selected_date').val(session_date);
  }
  var session_time = $('#book_time_san').val();
  $('ul.time-list li .time-btn').each(function() {
    if ($.trim($(this).val()) == $.trim(session_time)) {
      $(this).prop('checked', true);
    }
  });
  // $("ul.time-list .time-btn input[value='"+session_time+"']").prop('checked', true);
  $( "#datepicker" ).datepicker({
    minDate: 0,
    dateFormat: 'mm/dd/yy',
    beforeShowDay: function(date){
      var string = jQuery.datepicker.formatDate('mm/dd/yy', new Date());
      var crnt_day = new Date().getDay();
      crnt_day = parseInt(crnt_day)+1;
      // console.log(crnt_day);
      // console.log(avail_arr);
      // console.log(string);
      if ($.inArray( crnt_day, avail_arr )) {
        book_date = string;
        $('#selected_date').val(string);
      }
      var day = date.getDay();
      var final_day = parseInt(day)+1;
      return [ avail_arr.indexOf(final_day.toString()) !== -1 ]
    },
    onSelect: function(dateText, inst) {
      var dateAsString = dateText;console.log(dateAsString);
      $('#selected_date').val(dateAsString);
      book_date = dateAsString;
    }
  });
  $( "#datepicker2" ).datepicker();
  $( "#datepicker3" ).datepicker();
});
// }
//DATEPICKER
$(".catt-tab > li").click(function(){
  $("#bs-example-navbar-collapse-7").removeClass("in");
});

var tab_type = $('#tab_type').val();
if ('check' === $.trim(tab_type)) {
  $('#step-1').hide();
  $('#step-2').hide();
  $('#step-3').hide();
  $("#step-4").show();
}

if ('profile' === $.trim(tab_type)) {
  $('#step-1').show();
  $('#step-2').hide();
  $('#step-3').hide();
  $("#step-4").hide();
}
if ('summary' === $.trim(tab_type)) {
  $('#step-1').hide();
  $('#step-2').hide();
  $('#step-4').hide();
  $("#step-3").hide();
  $("#step-5").show();
}
if ('payment' === $.trim(tab_type)) {
  $('#step-1').hide();
  $('#step-2').hide();
  $('#step-4').hide();
  $("#step-3").hide();
  $("#step-5").hide();
  $("#step-6").show();
}
$(".NextBtn3").click(function(){
  var chkdate = getSandate();
  $('span#serv-day').text(book_date);
  $('span#time-format').text($('#selected_time').val());
  if (chkdate) {
    var providerid = $('#pro_id').val();
    checkAvail(providerid);
    $('#step-1').hide();
    $('#step-3').hide();
    $('#step-4').hide();
    $("#step-2").show();
    scrollToTop();
  }
});
$(".BackBtn").click(function(){
  $('#step-2').hide();
  $('#step-3').hide();
  $('#step-4').hide();
  $("#step-1").show();
});

$(".BackBtn3").click(function(){
  $('#step-2').show();
  $('#step-3').hide();
  $('#step-4').hide();
  $("#step-1").hide();
});

$(".BackBtn4").click(function(){
  checkAvail('');
  $('#step-2').hide();
  $('#step-3').show();
  $('#step-4').hide();
  $('#step-5').hide();
  $("#step-1").hide();
});

$("#submit_form_back").click(function(){
  $('#step-1').hide();
  $('#step-3').hide();
  $('#step-4').hide();
  $("#step-2").show();
});

$(".NextBtn2").click(function(){
  var done = checkAvail('');
  if (done) {
    $('#step-1').hide();
    $('#step-2').hide();
    $('#step-4').hide();
    $("#step-3").show();
  }else{
    swal("","Service not Selected", "warning");
  }
});
$("#submit_details").click(function(){
  $('#step-1').hide();
  $('#step-2').hide();
  $('#step-4').hide();
  $("#step-3").hide();
  $("#step-5").show();
});
$(".backbtn2").click(function(){
  $('#step-1').hide();
  $('#step-3').hide();
  $('#step-4').hide();
  $("#step-2").show();
});
$(".time-btn").click(function(){
  $('#selected_time').val($(this).val());
});
// $("#add_guest").click(function(){
//     var allok = 0;
// 	var cwd = $('#guest_pwd').val();
// 	var c_pwd = $('#guest_cpwd').val();
// 	var email = $('#guest_email').val();
//     // if (!$('#'+value).val()) {
//     //     $('#'+value).addClass('error_red');
//     //     err = 1;
//     //   }else{
//     //     $('#'+value).removeClass('error_red');
//     // }

//     $.ajax({
//         headers: {
//             'X-CSRF-TOKEN': $('#csrf_token').val()
//         },
//         type:'POST',
//         url:$('#chk_email').val(),
//         data: {
//             'email': email
//         },
//         success:function(data){
//             if (data == 1) {
//                 allok = 1;
//                 $('#guest_email').addClass('error_red');
//                 swal("","Email Already Exist", "warning");
//                 return false;
//             }else{
//                 var mandoryfields = ['fname','lname','guest_email','guest_address','guest_pwd','guest_cpwd'];
//                 $.each(mandoryfields, function( index, value ) {
//                   if (!$('#'+value).val()) {
//                     $('#'+value).addClass('error_red');
//                     allok = 1;
//                   }else{
//                     $('#'+value).removeClass('error_red');
//                   }
//                 });
//                 $('#guest_email').removeClass('error_red');
//                 if ($.trim(cwd) == $.trim(c_pwd) && email) {
//                 }else{
//                     allok = 1;
//                 }
//                 if (allok == 0) {
  
//                     $('#guest_form').submit();
//                 }
//             }
//         }
//     });

// });
$('#add_guest').on('click', function(event){
  var err = 0;
  // ajax_url
  var showalert = 0;
  var mandoryfields = ['fname','lname','guest_email','guest_mobile','guest_address','guest_pwd','guest_cpwd'];
  $.each(mandoryfields, function( index, value ) {
    if (!$('#'+value).val()) {
      $('#'+value).addClass('error_red');
      err = 1;
    }else{
      $('#'+value).removeClass('error_red');
    }
  });
  var pwd = $('#guest_pwd').val();
  var cpwd = $('#guest_cpwd').val();
  if (pwd !='' && cpwd != '' && pwd !== cpwd) {
    $('#guest_pwd').addClass('error_red');
    $('#guest_cpwd').addClass('error_red');
    err = 1;
  }else if(pwd !='' && cpwd != ''){
    $('#guest_pwd').removeClass('error_red');
    $('#guest_cpwd').removeClass('error_red');
  }
  if (err == 0) {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('#csrf_token').val()
        },
        type:'POST',
        url:$('#ajax_url').val()+'/member_register',
        data: $('#guest_form').serialize(),
        success:function(data){
          if (typeof data == 'string' || data instanceof String) {
              $('#pay_msg_block').hide();
              $('#pay_msg_block_success').show();
              $('#pay_msg_block_success').text('User Registered Successfully..');
              $('body').removeClass('modal-open');
              setTimeout(function(){ window.location.href = data; }, 2000);
          }else{
              $('#pay_msg_block_success').hide();
              $('#pay_msg_block').show();
              $("#provider_register").animate({ scrollTop: 0 }, "slow");
              $('#pay_msg_block').css("color", "red");
              $('#pay_msg_block').text(data.message);
          }
        }
    });
  }
  return false;
});
$(".NextBtn4").click(function(){
  var srids = [];
  $('li .san_box_ass').each(function() {
    if ($(this).is(':checked')) {
      srids.push($(this).attr('id'));
    }
  });
  if (srids.length > 0) {
    var date = $('#selected_date').val();
    var time = $('#selected_time').val();
    $('#book_date').val(date);
    $('#book_time').val(time);
    $('#service_form').submit();
  }else{
    swal("","Assistant not Selected", "warning");
  }
});

$('div.editable').on('click', function() {
  var self = $(this);
  self.addClass('focus');
  var text  = self.find('.text');
  var input = self.find('input');
  input.val(text.text().trim()).trigger('focus');
});
$('div.editable .input input').on('blur', function() {
  var self = $(this);
  var div  = self.closest('.editable');
  div.removeClass('focus');
  var text = div.find('.text');
  text.html(self.val());
});
$("#redeempoint_checkbox").on('click', function() {
  var data_diamonds = jQuery(this).attr('data_diamonds');
  var user_id = jQuery(this).attr('data_user_id');
  var data_total = jQuery(this).attr('data-total');
  if(jQuery("#redeempoint_checkbox").prop('checked') == true){
    if(data_total<75){
      swal("","Cart value must be greater then 75SAR", "warning");
      $("#redeempoint_checkbox").prop('checked', false);
      return false;
    }else{
      redeem_point_fn('apply',data_diamonds,user_id,data_total);
    }
  }else{
    redeem_point_fn('remove',data_diamonds,user_id,data_total);
  }
});

function redeem_point_fn(c_action,data_diamonds,user_id,data_total) {
  var ajax_url = jQuery( "#redeem_points" ).val();
  jQuery.ajax({
    headers: {
      'X-CSRF-TOKEN': $('#csrf_token').val()
    },
    type:'POST',
    url: ajax_url,
    data: {
      action: 'redeem_point_fn',
      c_action: c_action,
      data_diamonds: data_diamonds,
      user_id: user_id,
      data_total: data_total,
    },
    dataType: "json",
    beforeSend: function(){
    },
    success:function(data){
      if(data['status']=='success'){
        $('#reward_points').val(data['redeem_sar']);
        jQuery("#sln_redeem_jewelries").html(data['redeem_sar']+'SAR');
        jQuery("h3.sln-total-prices").html(data['total_after_redeem']+'SAR');
        jQuery("#san_ttl_amnt").val(data['total_after_redeem']);
        jQuery("#san_ttl_amntt").val(data['total_after_redeem']);
        jQuery("#sln_discount").val('');
        jQuery("#sln_discount_value").html(data['redeem_sar']+'SAR');
        jQuery("#sln_discount_btn2").addClass('hidden');
        jQuery("#sln_discount_status").html('');
        swal("",data['msg'], "success");

      }else if(data['status']=='failure'){
        $("#redeempoint_checkbox").prop('checked', false);
        swal("",data['msg'], "error");
      }
    }
  });
}


/* Booking */
function getSandate(){
  var date = $('#selected_date').val();
  var time = $('#selected_time').val();
  if (date && time) {
    return true;
  }else{
    swal("","Please Select Date and Time", "error");
    return false;
  }
}
function checkAvail(providerid){
  var err = 0;
  var date = $('#selected_date').val();
  // console.log('check:-'+date);
  var time = $('#selected_time').val();
  var srids = [];
  $('li .serv_chkbxes').each(function() {
    if ($(this).is(':checked')) {
      srids.push($(this).attr('id'));
    }
  });
  if (srids.length == 0) {
    return false;
  }
  if (srids.length > 0 || providerid !='') {
    $.ajax({
      headers: {
        'X-CSRF-TOKEN': $('#csrf_token').val()
      },
      type:'POST',
      url:$('#chk_book_service').val(),
      data: {
        'service_ids': srids,
        'date':date,
        'providerid':providerid,
        'time':time
      },
      success:function(data){
        if (providerid !='') {
          if (data.length > 0) {
            $.each(data, function( index, value ) { console.log(value);
              $('#sln-salon ul.services_lists li#'+value).addClass('disabled');
              $('#sln-salon ul.services_lists li#'+value).attr('title', 'Service Not Available On Selected Time')
            });
          }
        }else{
          if (data == 1) {
            swal("","Service not available on selected time", "warning");
            err = 1;
            return false;
          }else{
            var services = '';
            var serv_type = [];
            var expert_type = [];
            var assistant_exist = 1;
            var provider_image = $('#provider_image').val();
            $.each(data, function( index, value ) {
              serv_type.push(value.name);
              services += '<div class="specialist worker_'+value.id+'">'+
              '<h4 class="service-type">'+value.name+'</h4>'+
              '<ul class="list-inline worker-list">';
              if (value.assistants.length > 0) {
                $.each(value.assistants, function( index2, value2 ) {
                  expert_type.push(value2.name);
                  var img = $('#user_image').val()+'/'+value.image;
                  services += '<li>'+
                  '<div class="custom-check2">'+
                  '<input name="services['+value.id+']['+value2.id+']" class="cust-box san_box_ass" type="radio">'+
                  '<span class="outer-bx"></span>'+
                  '<img src="'+img+'" alt="">'+
                  '<h4 class="pull-left">'+value2.name+'<span class="aside small">'+value.name+'</span></h4>'+
                  '</div>'+
                  '</li>';
                });
              }else{
                if ($.inArray('any', expert_type) == -1) expert_type.push('any');
                services += '<li>'+
                '<div class="custom-check2">'+
                '<input name="services['+value.id+']" class="cust-box san_box_ass" type="radio">'+
                '<span class="outer-bx"></span>'+
                '<img src="'+provider_image+'" alt="">'+
                '<h4 class="pull-left">Choose an assistant for me</h4>'+
                '</div>'+
                '</li>';
              }
              services += '</ul></div>';
            });
            $('span#serv-type').text(serv_type.join(","));
            $('span#expert-type').text(expert_type.join(","));
            err = 0;
            $('#append_selected_services').html(services);
          }
        }
      }
    });
  }
  if (err == 1) {
    return false;
  }else{
    return true;
  }
}

function applyDiscountCode() {
  var code = $('#dicount_code').val();
  var pro_id = $('#pro_id').val();
  var total_amount = $('#total_amount').val();
  $.ajax({
    headers: {
      'X-CSRF-TOKEN': $('#csrf_token').val()
    },
    url: $('#apply_code').val(),
    data: {
      'code': code,
      'pro_id':pro_id
    },
    method: 'POST',
    beforeSend: function(){
      $('#sln-salon .img_loader2').show();
    },
    success: function (data) { console.log(data);
      $('#sln_discount_status').find('.sln-alert').remove();
      var alertBox;
      $('#sln-salon .img_loader2').hide();
      if (data.success) {
        if(data.success==1){
          $('#sln_discount_value').html(data.discount);
          var fin_ttl = parseFloat(total_amount)-parseInt(data.discount);
          $('.sln-total-prices').html(fin_ttl.toFixed(2));
          $('#san_ttl_amnt').val(fin_ttl.toFixed(2));
          $('#san_ttl_amntt').val(fin_ttl.toFixed(2));
          alertBox = $('<div class="sln-alert sln-alert--success"></div>');
          $('.discount_action #sln_discount_btn2').removeClass('hidden');
          $("#sln_redeem_jewelries").html('0SAR');
          $("#redeempoint_checkbox").prop('checked', false);
          $('#dicount_code').val(data.code);
        }if(data.success==2){ console.log(total_amount);
          $('#sln_discount_value').html(0);
          $('.sln-total-prices').html(total_amount);
          $('#san_ttl_amnt').val(total_amount);
          $('#san_ttl_amntt').val(total_amount);
          alertBox = $('<div class="sln-alert sln-alert--problem"></div>');
          $('.discount_action #sln_discount_btn2').addClass('hidden');
        }
      }
      else {
        alertBox = $('<div class="sln-alert sln-alert--problem"></div>');
      }
      $(data.errors).each(function () {
        alertBox.append('<p>').html(this);
      });
      $('#sln_discount_status').html('').append(alertBox);
    },
    error: function(data){swal("","Service not available on selected time", "error"); }
  });

  return false;
}

function removeDiscountCode() {
  var code = $('#dicount_code').val();
  var pro_id = $('#pro_id').val();
  var total_amount = $('#total_amount').val();
  $.ajax({
    headers: {
      'X-CSRF-TOKEN': $('#csrf_token').val()
    },
    url: $('#apply_code').val(),
    data: {
      'code': code,
      'pro_id':pro_id,
      'remove':1
    },
    method: 'POST',
    beforeSend: function(){
      $('#sln-salon .img_loader2').show();
    },
    success: function (data) {
      $('#sln_discount_status').find('.sln-alert').remove();
      var alertBox;
      $('#sln-salon .img_loader2').hide();
      if (data.success) {
        if(data.success==1){
          $('#sln_discount_value').html(data.discount);
          $('.sln-total-prices').html(parseFloat(total_amount)-parseInt(data.discount));
          $('#san_ttl_amnt').val(parseFloat(total_amount)-parseInt(data.discount));
          $('#san_ttl_amntt').val(parseFloat(total_amount)-parseInt(data.discount));
          alertBox = $('<div class="sln-alert sln-alert--success"></div>');
          $('.discount_action #sln_discount_btn2').removeClass('hidden');
          $("#sln_redeem_jewelries").html('0SAR');
          $("#redeempoint_checkbox").prop('checked', false);
        }if(data.success==2){
          $('#sln_discount_value').html(0);
          $('.sln-total-prices').html(total_amount);
          $('#san_ttl_amnt').val(total_amount);
          $('#san_ttl_amntt').val(total_amount);
          alertBox = $('<div class="sln-alert sln-alert--problem"></div>');
          $('.discount_action #sln_discount_btn2').addClass('hidden');
        }
      }
      else {
        alertBox = $('<div class="sln-alert sln-alert--problem"></div>');
      }
      $(data.errors).each(function () {
        alertBox.append('<p>').html(this);
      });
      $('#sln_discount_status').html('').append(alertBox);
    },
    error: function(data){alert('error'); }
  });

  return false;
}

function initdirectionMap() {
  var latitude = jQuery("#latitude").val();
  var longitude = jQuery("#longitude").val();

  var map = new google.maps.Map(document.getElementById('map'), {
    center: {lat: -34.397, lng: 150.644},
    zoom: 17,
    types: ['(cities)'],
  });

  var infoWindow = new google.maps.InfoWindow({map: map});

  // Try HTML5 geolocation.
  if (navigator.geolocation) {
    console.log('navigator.geolocation');
    navigator.geolocation.getCurrentPosition(function (p) {

      var pos = {
        lat: p.coords.latitude,
        lng: p.coords.longitude
      };

      // console.log('p.coords.latitude - '+p.coords.latitude);
      // console.log('p.coords.longitude - '+p.coords.longitude);

      var laturl = p.coords.latitude;
      var lngurl = p.coords.longitude;

      var gmp_url = 'http://maps.google.com/?saddr='+laturl+','+lngurl+'&daddr='+latitude+','+longitude;

      jQuery('#get_gmp').attr('href',gmp_url);

      jQuery("#c_latitude").val(laturl);
      jQuery("#c_longitude").val(lngurl);
      map.setCenter(pos);
      var latlng = new google.maps.LatLng(laturl, lngurl);
      var geocoder = new google.maps.Geocoder();
      geocoder.geocode({ 'latLng': latlng }, function (results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
          if (results[0]) {
            if(jQuery("#c_address").length){
              jQuery("#c_address").val(results[0].formatted_address);

              var directionsService = new google.maps.DirectionsService;
              var directionsDisplay = new google.maps.DirectionsRenderer;

              directionsDisplay.setMap(map);

              calculateAndDisplayRoute(directionsService, directionsDisplay);

              jQuery('#map').show();
              jQuery('#viewon_gmp').show();

            }
          }
        }
      });

    }, function () {

      handleLocationError(true, infoWindow, map.getCenter());
    });
  }

}

function calculateAndDisplayRoute(directionsService, directionsDisplay) {

  var c_latitude = jQuery("#c_latitude").val();//'24.66982554682196';
  var c_longitude = jQuery("#c_longitude").val();//'46.69187656366273';
  var c_address = jQuery("#c_address").val();

  var latitude = jQuery("#latitude").val();
  var longitude = jQuery("#longitude").val();
  var salon_address = jQuery("#salon_address").val();

  directionsService.route({
    origin: c_latitude+','+c_longitude,
    destination: latitude+','+longitude,

    travelMode: 'DRIVING'
  }, function(response, status) {
    if (status === 'OK') {
      directionsDisplay.setDirections(response);

    } else {
      // window.alert('Directions request failed due to ' + status);
      swal("","Distance limit exceeds, Unable to show Direction", "error");
      jQuery('#map').hide();
      jQuery('#viewon_gmp').hide();
    }
  });
}

function handleLocationError(browserHasGeolocation, infoWindow, pos) {
  infoWindow.setPosition(pos);
  infoWindow.setContent(browserHasGeolocation ?
    'Error: The Geolocation service failed.' :
    'Error: Your browser doesn\'t support geolocation.');
  }

  function scrollToTop(){
    $('body,html').animate({
      scrollTop: 0
    }, 1600);
  }
