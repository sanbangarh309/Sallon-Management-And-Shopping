$('#login_btnn').on( 'click', function( event ) {
	$('#login-form').submit();
});
function getValue(event){
    $('.profile_image_section').val(event.value);
}

function showReviewWindow(id){
 $('#'+id).show();
}
function searchProduct(id,type,search_type='',data){
    var url = $('#ajax_url').val()+'/search?type='+type; 
    // console.log(url);
	// var url = 'https://mask-app.com/en/search?type='+type;
	if(search_type == 'reset'){
		window.location.href = url;
		return;
	}
	try {
			var fin_data = JSON.parse(data);
	}catch(err){
			var fin_data = data;
	}
	if(fin_data.pr && search_type != 'category'){
		url = url+'&pr='+fin_data.pr;
	}
	if(search_type == 'category'){
		url = url+'&pr='+id;
	}
	if(fin_data.clr && search_type != 'color'){
		url = url+'&clr='+fin_data.clr;
	}
	if(search_type == 'color'){
		url = url+'&clr='+id;
	}
	if(fin_data.price && search_type != 'price'){
		url = url+'&price='+fin_data.price;
	}
	if(search_type == 'price'){
		var data = $('#min_input').val()+'@'+$('#max_input').val();
		url = url+'&price='+data;
	}
	if(fin_data.provider && search_type != 'seller'){
		url = url+'&provider='+fin_data.provider;
	}
	if(search_type == 'seller'){
		url = url+'&provider='+id;
	}
	window.location.href = url;
}
/* Submit login using ajax */
$('form#login-form').on('submit', function(event){
            event.preventDefault();
            var formData = {
                email : $('#login_username').val(),
                password  : $('#login_password').val(),
            }
            if (formData.email && formData.password) {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('#csrf_token').val()
                    },
                    type : "POST",
                    url  : $('#ajax_url').val()+'/login',
                    data : formData,
                    cache : false,
                    success  : function(data) {
                        if (typeof data == 'string' || data instanceof String) {
                            $('#msg_block').hide();
                            $('#msg_block_success').show();
                            $('#msg_block_success').text('Logged In Successfully. Redirecting in few Seconds');
                            setTimeout(function(){ window.location.href = data; }, 2000);
                        }else{
                            $('#msg_block_success').hide();
                            $('#msg_block').show();
                            $('#msg_block').text(data.message);
                        }
                    }
                });
            }else{
                $('#msg_block_success').hide();
                $('#msg_block').show();
                $('#msg_block').text('Email and Password is required');
            }
            return false;
});
$('form#login_from_checkout').on('submit', function(event){
            event.preventDefault();
            var formData = {
                email : $('#login_username').val(),
                password  : $('#login_password').val(),
            }
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('#csrf_token').val()
                },
                type : "POST",
                url  : $('#ajax_url').val()+'/login',
                data : formData,
                cache : false,
                success  : function(data) {
                    if (typeof data == 'string' || data instanceof String) {
                        $('#msg_block').hide();
                        window.location.href = data;
                    }else{
                        $('#msg_block').show();
                        $('#msg_block').text(data.message);
                    }
                }
            })
            return false;
});
/* End Login */
/* Signup Form Using ajax */
$('#submit_customer_signup').on('click', function(event){
    var err = 0;
    var showalert = 0;
    var mandoryfields = ['c_username','c_user_email','c_contact_number','gender_field','c_user_password','c_user_conrfpassword'];
    $.each(mandoryfields, function( index, value ) {
      if (!$('#'+value).val()) {
        $('#'+value).addClass('error_red');
        err = 1;
      }else{
        $('#'+value).removeClass('error_red');
      }
    });
    if (!$('#c_terms_condition').is(":checked")) {
       $('#c_terms_condition').addClass('error_red');
        err = 1;
        showalert = 1;
    }else{
        $('#c_terms_condition').removeClass('error_red');
    }
    var pwd = $('#c_user_password').val();
    var cpwd = $('#c_user_conrfpassword').val();
    if (pwd !='' && cpwd != '' && pwd !== cpwd) {
        $('#c_user_password').addClass('error_red');
        $('#c_user_conrfpassword').addClass('error_red');
        err = 1;
    }else if(pwd !='' && cpwd != ''){
        $('#c_user_password').removeClass('error_red');
        $('#c_user_conrfpassword').removeClass('error_red');
    }
    if (showalert == 1) {
        $('#custom-check_cust').addClass('error_red');
        return false;
    }else{
        $('#custom-check_cust').removeClass('error_red');
    }
    if (err == 0) {
        $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('#csrf_token').val()
                },
                type : "POST",
                url  : $('#ajax_url').val()+'/member_register',
                data : $('#customer_form').serialize(),
                cache : false,
                success  : function(data) {
                    if (typeof data == 'string' || data instanceof String) {
                        $('#reg_msg_block').hide();
                        $('#reg_msg_block_success').show();
                        $('#reg_msg_block_success').text('User Registered Successfully..');
                        setTimeout(function(){ window.location.href = data; }, 2000);
                    }else{
                        $('#reg_msg_block_success').hide();
                        $('#reg_msg_block').show();
                        $("#customer_register").animate({ scrollTop: 0 }, "slow");
                        $('#reg_msg_block').text(data.message);
                    }
                }
        })
    }
    return false;
});
$('#submit_signup').on('click', function(event){
   var err = 0;
   var showalert = 0;
    var mandoryfields = ['username','manager_name','contact_number','s_email','_type_of_service','street_address','user_password','user_conrfpassword','_duration'];
    $.each(mandoryfields, function( index, value ) {
      if (!$('#'+value).val()) {
        $('#'+value).addClass('error_red');
        err = 1;
      }else{
        $('#'+value).removeClass('error_red');
      }
    });
    if (!$('#terms_condition').is(":checked")) {
       $('#terms_condition').addClass('error_red');
        err = 1;
        showalert = 1;
    }else{
        $('#terms_condition').removeClass('error_red');
    }
    if (!$('input[name="membership"]:checked').is(":checked")) { console.log(err);
       $('input[name="membership"]').addClass('error_red');
        err = 1;
    }else{
        $('input[name="membership"]').removeClass('error_red');
    }
    var pwd = $('#user_password').val();
    var cpwd = $('#user_conrfpassword').val();
    if (pwd !== cpwd) {
        $('#user_password').addClass('error_red');
        $('#user_conrfpassword').addClass('error_red');
        err = 1;
    }else{
        $('#user_password').removeClass('error_red');
        $('#user_conrfpassword').removeClass('error_red');
    }
    if (showalert == 1) {
        $('#custom-check').addClass('error_red');
        return false;
    }else{
        $('#custom-check').removeClass('error_red');
    }
    if (err == 0) {
        $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('#csrf_token').val()
                },
                type : "POST",
                url  : $('#ajax_url').val()+'/provider_register',
                data : $('#pro_signup_form').serialize(),
                cache : false,
                success  : function(data) {
                    if (typeof data == 'string' || data instanceof String) {
                        $('#signup_msg_block').hide();
                        $('#signup_msg_success').show();
                        $("#provider_register").animate({ scrollTop: 0 }, "slow");
                        $('#signup_msg_success').text('Provider Registered Successfully..');
                        $('body').removeClass('modal-open');
                        setTimeout(function(){ window.location.href = data; }, 2000);
                    }else{
                        $('#signup_msg_success').hide();
                        $('#signup_msg_block').show();
                        $("#provider_register").animate({ scrollTop: 0 }, "slow");
                        $('#signup_msg_block').css("color", "red");
                        $('#signup_msg_block').text(data.message);
                    }
                }
        })
    }
    return false;
});
/**************************/

$('#submit_search').on('click',function(){
     var sr_id = $('#serv_cats option[value="' + $('#service_srch_list').val() + '"]').data('id');
     if ($('#appended_sr').hasClass('appended_sr')) {
        $('#appended_sr').val(sr_id);
     }else{
        $('<input>').attr({
            type: 'hidden',
            id: 'appended_sr',
            class: 'appended_sr',
            value: sr_id,
            name: 'sr'
        }).appendTo('#search_form');
     }
   $('#search_form').submit();
});
$('#submit_pro_search').on('click',function(){
    var pr_id = $('#prrr_cats option[value="' + $('#product_srch_list').val() + '"]').data('id');
     if ($('#appended_pr').hasClass('appended_pr')) {
        $('#appended_pr').val(pr_id);
     }else{
        $('<input>').attr({
            type: 'hidden',
            id: 'appended_pr',
            class: 'appended_pr',
            value: pr_id,
            name: 'pr'
        }).appendTo('#product_buy_form');
     }
   $('#product_buy_form').submit();
});
$('#submit_search2').on('click',function(){
   $('#search_form2').submit();
});
/* Bookings */
$('.booking_accept').on('click',function(){
    var bookid = $(this).data('id');
    var userid = $(this).data('uid');
    var status = $(this).data('status');
    var proid = $(this).attr('id');
    $('.loading_').show();
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('#csrf_token').val()
        },
        type:'POST',
        url:$('#accept_booking').val(),
        data: {
            'bookid': bookid,
            'status': status,
            'proid': proid,
            'userid': userid
        },
        success:function(data){
            if (status =='Pending') {
                $('.booking-content div#pending_bookings_wrapper').html(data.pen_html);
                $('.booking-content div#confirmed_bookings_wrapper').html(data.con_html);
            }
            if (status =='Confirmed') {
                $('.booking-content div#completed_bookings_wrapper').html(data.com_html);
                $('.booking-content div#confirmed_bookings_wrapper').html(data.con_html);
            }
            $('.loading_').hide();
        }
    });
});
$('.booking_reject').on('click',function(){
    var bookid = $(this).data('id');
    var userid = $(this).data('uid');
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('#csrf_token').val()
        },
        type:'POST',
        url:$('#reject_booking').val(),
        data: {
            'bookid': bookid,
            'userid': userid
        },
        success:function(data){
            $('#confirmed_bookings_wrapper').fadeIn('slow');
        }
    });
});
$('#near_me').on('click',function(){
   if(this.checked) {
        var cust_lat = jQuery("#cust_lat").val();
        var cust_long = jQuery("#cust_long").val();

        if(cust_lat.length==0 && cust_long.length==0){
            showMap();
        }

    }
});
if (typeof DataTable === 'object'){
    $('#product-table').DataTable( {
        dom: 'Bfrtip',
        buttons: [
            {
                text: 'Add new',
                className: 'btn bttn-addnew',
                action: function ( e, dt, node, config ) {
                    $('#add-newitem').modal('show');
                }
            }
        ]
    } );
    $('#profile_table').DataTable( {
            dom: 'Bfrtip',
            buttons: [
                {
                    text: 'Add Team',
                    className: 'btn',
                    action: function ( e, dt, node, config ) {
                        $('#addteam_Modal').modal('show');
                    }
                }
            ]
        } );
    $('#service_table').DataTable( {
            dom: 'Bfrtip',
            buttons: [
                {
                    text: 'Add Service',
                    className: 'btn',
                    action: function ( e, dt, node, config ) {
                        $('#add_service_Modal').modal('show');
                    }
                }
            ]
        } );

    $('#san_pending_bookings').DataTable();
}

    try {
        var extraaa = JSON.parse($('#extra_features_san').val());
    }catch(err){
        var extraaa = $('#extra_features_san').val();
    }
    try {
        var availability_san = JSON.parse($('#availability_san').val());
    }catch(err){
        var availability_san = $('#extra_features_san').val();
    }
    if (extraaa != undefined) {
       var extra = $.map(extraaa, function(value, index) {
            if(value == 1) {
                return [index];
            };
        });
        $( ".san_chks" ).each(function( index ) {
            if (extraaa[$( this ).attr('id')] == 1) {
                $( this ).prop('checked', true);
            }
        });
    }
	var avail_days = [];
    if (availability_san != undefined && availability_san !='' && availability_san != null) {
		if(availability_san.days){
			avail_days = $.map(availability_san.days, function(value, index) {
				return [index];
			});
		}
        
        $.each(avail_days, function( index, value ) {
            $('#_sln_attendant_availabilities___new___days_'+value).prop('checked', true);
            // $( this ).prop('checked', true);
        });
        $('#san_slider-time-from').text(availability_san.from[0]);
        $('#input1_slider-time-from').val(availability_san.from[0]);

        $('#san_slider-time-to').text(availability_san.to[0]);
        $('#input1_slider-time-to').val(availability_san.to[0]);

        $('#san2_slider-time-from').text(availability_san.from[1]);
        $('#input_slider-time-from').val(availability_san.from[1]);

        $('#san2_slider-time-to').text(availability_san.to[1]);
        $('#input_slider-time-to').val(availability_san.to[1]);
    }

$('#changed_services').on('change', function(){
        var name = $(this).children("option:selected").text();
        if ($('#servive_name').hasClass('servive_name')) {
            $('#servive_name').val(name);
        }else{
            var el = '<input type="hidden" id="servive_name" class="servive_name" name="name" value="'+name+'"></input>';
            $('#servicee_form_add').append(el);
        }
    });

$('.edit_assistant').on('click',function(){
    var id = $(this).data('id');
    if (!id) {
        $('#add_team_head').text('Add Team Member');
        $("#san_servicess option:selected").prop("selected", false);
        $('#_sln_attendant_name').val('');
        if ($('#addteam_form #edit_id').hasClass('edit_class')) {
            $('#addteam_form #edit_id').val('');
        }
        var chkforservic = $('#check_for_services').val();
        if($.trim(chkforservic) != 1){
            swal({
                title: "Service Not Available!",
                text: "Please Add Service First!",
                icon: "warning",
              });
            return false;
        }
        $('#addteam_Modal').modal('show');
        return false;
    }
    $('#add_team_head').text('Edit Team Member');
    var name = $(this).data('name');
    var sids = $(this).data('sids');
    if ($('#addteam_form #edit_id').hasClass('edit_class')) {
        $('#addteam_form #edit_id').val(id);
    }else{
        var el = '<input type="hidden" id="edit_id" class="edit_class" name="edit_id" value="'+id+'"></input>';
        $('#addteam_form').append(el);
    }
    $.each(sids, function( index, value ) {
      // $('#san_servicess').val(value);
      $("#san_servicess option[value='" + value + "']").prop("selected", true);
    });
    // $('.selectpicker').selectpicker('refresh');
    // $('select[name=selValue]').val(1);
    // $('.selectpicker').selectpicker('refresh');
    $('#_sln_attendant_name').val(name);
    $('#addteam_Modal').modal('show');
});

$('.edit_services').on('click',function(){
    var id = $(this).data('id');
    if (!id) {
		$('#servicee_form_add #edit_id').remove();
        $('h3#add_services').show();
        $('h3#edt_services').hide();
        $('button#add_service_btn').show();
        $('button#edit_service_btn').hide();
        $("#san_change_category option:selected").prop("selected", false);
        $('#changed_services').html('<option value="">Select Service</option>');
        $('#_sln_service_price').val(0);
        $('#post_excerpt').val('');
        $("#_sln_service_unit option:selected").prop("selected", false);
        $("#_sln_service_duration option:selected").prop("selected", false);
        $('#add_service_Modal').modal('show');
        return false;
    }
    var service = $(this).data('service');
    if ($('#servicee_form_add #edit_id').hasClass('edit_class')) {
        $('#servicee_form_add #edit_id').val(id);
    }else{
        var el = '<input type="hidden" id="edit_id" class="edit_class" name="edit_id" value="'+id+'"></input>';
        $('#servicee_form_add').append(el);
    }
    $("#san_change_category option[value='" + service.category_id + "']").prop("selected", true);
    $("#changed_services option[value='" + service.parent_service + "']").prop("selected", true);
    $('#_sln_service_price').val(service.price);
    $('#post_name').val(service.name);
    $('#post_excerpt').val(service.description);
    $("#_sln_service_unit option[value='" + service.per_hour + "']").prop("selected", true);
    $("#_sln_service_duration option[value='" + service.duration + "']").prop("selected", true);
    $('h3#add_services').hide();
    $('h3#edt_services').show();
    $('button#add_service_btn').hide();
    $('button#edit_service_btn').show();
    $('.selectpicker').selectpicker('refresh');
    $('#add_service_Modal').modal('show');
});

$('.edit_products').on('click',function(){
    var id = $(this).data('id');
    if (!id) {
        $('h3#add_productss').show();
        $('h3#edit_productss').hide();
        $("#product_category option:selected").prop("selected", false);
        $('#add-newproduct #product_name').val('');
        $('#add-newproduct #product_price').val('');
        $('#add-newproduct #product_desc').val('');
        $('#add-newproduct #colornames').val('');
        $("#quater_opt option:selected").prop("selected", false);

        $('#add-newproduct').modal('show');
        return false;
    }
    $('.loading_').show();
    var prodct = $(this).data('product');
    if ($('#product_form_edit_add #edit_id').hasClass('edit_class')) {
        $('#product_form_edit_add #edit_id').val(id);
    }else{
        var el = '<input type="hidden" id="edit_id" class="edit_class" name="edit_id" value="'+id+'"></input>';
        $('#product_form_edit_add').append(el);
    }
    $("#product_category option[value='" + prodct.category_id + "']").prop("selected", true);
    $('#add-newproduct #product_name').val(prodct.name);
    $('#add-newproduct #product_price').val(prodct.price);
    $('#add-newproduct #product_desc').val(prodct.description);
    $('#add-newproduct #colornames').val(prodct.color);
    $('#add-newproduct #product_stck').val(prodct.qty);
    $("#quater_opt option[value='" + prodct.active + "']").prop("selected", true);
    $('h3#add_productss').hide();
    $('h3#edit_productss').show();
    $('.selectpicker').selectpicker('refresh');
    $('.loading_').hide();
    $('#add-newproduct').modal('show');
});
// $('#services').on('change',function(){
//    alert($(this).data('id'))
// });
// $("#street_address, #salon_address").click(function(event) {
// 	event.preventDefault();
// 	if($("#street_address").length){
// 		var salon_address = $("#street_address").val();
// 		if(salon_address.length==0){
// 			initMap();
// 		}
// 	}
// 	if($("#salon_address").length){
// 		var salon_address = $("#salon_address").val();
//             initMap();
//         }
// });

function show_services(id) {
    $.ajax({
        type:'GET',
        url:$('#service_url').val(),
        data: {
            'cat_id': id
        },
        success:function(data){
            var option = '<option value="">Select Service</option>';
            $.each(data, function( index, value ) {
                option += '<option value="'+value.id+'">'+value.name+'</option>';
            });
            $('#changed_services').html(option);
            $('.selectpicker').selectpicker('refresh');
        }
    });
};

function showMap() {

	var map = new google.maps.Map(document.getElementById('googleMap'), {
		center: {lat: -34.397, lng: 150.644},
		zoom: 17,
		types: ['(cities)'],
	});


	var infoWindow = new google.maps.InfoWindow({map: map});

    // Try HTML5 geolocation.
    if (navigator.geolocation) {

    	navigator.geolocation.getCurrentPosition(function (p) {

    		if(jQuery("#latitude").val()!='' && jQuery("#longitude").val()!=''){

    			var pos1 = new google.maps.LatLng(jQuery("#latitude").val(),jQuery("#longitude").val());

    			var marker = new google.maps.Marker({
    				map: map,
    				position: pos1,
    				draggable: true,
    			});

                //gets the new latlong if the marker is dragged
                google.maps.event.addListener(marker, "dragend", function(){

                	var laturl=marker.getPosition().lat();
                	var lngurl=marker.getPosition().lng();

                	jQuery("#latitude").val(laturl);
                	jQuery("#longitude").val(lngurl);

                	var latlng = new google.maps.LatLng(laturl, lngurl);
                	var geocoder = new google.maps.Geocoder();
                	geocoder.geocode({ 'latLng': latlng }, function (results, status) {
                		if (status == google.maps.GeocoderStatus.OK) {
                			if (results[0]) {
                				if(jQuery("#street_address").length){
                					jQuery("#street_address").val(results[0].formatted_address);
                				}
                				if(jQuery("#salon_address").length){
                					jQuery("#salon_address").val(results[0].formatted_address);
                				}
                				jQuery("#city_country").val(results[5].formatted_address);

                			}
                		}
                	});

                });

                map.setCenter(pos1);

            }else{

            	var pos = {
            		lat: p.coords.latitude,
            		lng: p.coords.longitude
            	};

            	var lat = p.coords.latitude;
            	var long = p.coords.longitude;

            	var pos1 = new google.maps.LatLng(lat,long);

            	var marker = new google.maps.Marker({
            		map: map,
            		position: pos1,
            		draggable: true,
            	});

                //gets the pre-drag latlong coordinate
                var laturl=marker.getPosition().lat();
                var lngurl=marker.getPosition().lng();
                jQuery("#latitude").val(laturl);
                jQuery("#longitude").val(lngurl);

                var latlng = new google.maps.LatLng(laturl, lngurl);
                var geocoder = new google.maps.Geocoder();
                geocoder.geocode({ 'latLng': latlng }, function (results, status) {
                	if (status == google.maps.GeocoderStatus.OK) {
                		if (results[0]) {
                			if(jQuery("#street_address").length){
                				jQuery("#street_address").val(results[0].formatted_address);
                			}
                			if(jQuery("#salon_address").length){
                				jQuery("#salon_address").val(results[0].formatted_address);
                			}
                			jQuery("#city_country").val(results[5].formatted_address);
                			if(jQuery("#cust_lat").length && jQuery("#cust_long").length){

                				jQuery("#cust_long").val(lngurl);
                				jQuery("#cust_lat").val(laturl);
                				var ajax_url = jQuery('#ajax_url_input').val();
                				jQuery.ajax({
                					type: 'POST',
                					url: ajax_url,
                					data: {
                						action: 'get_address',
                						type: 'allow',
                						lat: laturl,
                						long: lngurl
                					},
                					success: function (data) {

                					}
                				});

                			}
                		}
                	}
                });


                //gets the new latlong if the marker is dragged
                google.maps.event.addListener(marker, "dragend", function(){

                	var laturl=marker.getPosition().lat();
                	var lngurl=marker.getPosition().lng();

                	jQuery("#latitude").val(laturl);
                	jQuery("#longitude").val(lngurl);

                	var latlng = new google.maps.LatLng(laturl, lngurl);
                	var geocoder = new google.maps.Geocoder();
                	geocoder.geocode({ 'latLng': latlng }, function (results, status) {
                		if (status == google.maps.GeocoderStatus.OK) {
                			if (results[0]) {
                				if(jQuery("#street_address").length){
                					jQuery("#street_address").val(results[0].formatted_address);
                				}
                				if(jQuery("#salon_address").length){
                					jQuery("#salon_address").val(results[0].formatted_address);
                				}
                				jQuery("#city_country").val(results[5].formatted_address);

                			}
                		}
                	});

                });

                map.setCenter(pos);
            }

            jQuery("#googleMap").show();


        }, function () {

        	handleLocationError(true, infoWindow, map.getCenter());
        });
} else {
        // Browser doesn't support Geolocation
        handleLocationError(false, infoWindow, map.getCenter());
    }
}

function handleLocationError(browserHasGeolocation, infoWindow, pos) {
    infoWindow.setPosition(pos);
    infoWindow.setContent(browserHasGeolocation ?
            'Error: The Geolocation service failed.' :
            'Error: Your browser doesn\'t support geolocation.');
}
