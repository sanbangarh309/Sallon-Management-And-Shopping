$(function() {
	$( "#san_datepicker1" ).datepicker({
		minDate: 0,
		dateFormat: 'yy-mm-dd'
	});
	$( "#san_datepicker2" ).datepicker({
		minDate: 0,
		dateFormat: 'yy-mm-dd'
	});
	var avail_dta = $('#avail').val();
	try {
		applyDateFilter(JSON.parse(avail_dta));
	}catch{
		// applyDateFilter(avail_dta);
	}
	// $('#change_cats').on('change', show_services);
	$('#san_services').on('change', function(){
		var name = $(this).children("option:selected").text();
		if ($('#servive_name').hasClass('servive_name')) {
			$('#servive_name').val(name);
		}else{
			var el = '<input type="hidden" id="servive_name" class="servive_name" name="name" value="'+name+'"></input>';
			$('#service_form').append(el);
		}
	});
	$('#check_for_validation').on('click', function(){
		// var cat = $('#change_cats').val();
		var sers = $('#san_servicess').val();
		if (sers) {
			$('#assistant_form').submit();
		}else{
			alert('Enter Category or Service');
		}
		return false;
	});
	$('#service_for_validation').on('click', function(){
		// var cat = $('#change_cats').val();
		var sers = $('#san_servicess').val();
		if (sers) {
			// $('#assistant_form').submit();
		}else{
			alert('Enter Category or Service');
		}
		return false;
	});
	$('#booking_validation').on('click', function(){
		// var cat = $('#change_cats').val();
		var booking_user_id = $('#booking_user_id').val();
		var salon_id = $('#salon_id').val();
		var san_assistnats = $('#san_assistnats').val();
		var first_name = $('#first_name').val();
		var email = $('#email').val();
		var price = $('#price').val();
		if (booking_user_id && salon_id && san_assistnats && first_name && email && price) {
			$('#booking_form').submit();
		}else{
			alert('Missing Some Fields');
		}
		return false;
	});
	$('#offer_validation').on('click', function(){
		// var cat = $('#change_cats').val();
		var san_datepicker1 = $('#san_datepicker1').val();
		var san_datepicker2 = $('#san_datepicker2').val();
		var amount = $('#amount').val();
		if (san_datepicker1 && san_datepicker2 && amount) {
			$('#offerr_form').submit();
		}else{
			alert('Missing Some Fields');
		}
		return false;
	});

	// $('#check_for_validation_pro').on('click', function(){
		
	// });
	$('#salon_id').on('change', show_related);
	$('#san_service').on('change', check_serviceBooking);
	$('#booking_user_id').on('change', fill_user_details);
})

function show_services() {
	var $this = $(this);
	$.ajax({
		type:'GET',
		url:$('#service_url').val(),
		data: {
			'cat_id': $this.val()
		},
		success:function(data){
			var option = '<option value="">Select Service</option>';
			$.each(data, function( index, value ) {
				option += '<option value="'+value.id+'">'+value.name+'</option>';
			});
			$('#san_services').html(option);
			$('#san_services').selectpicker('refresh');
		}
	});
};

function show_related() {
	hideError();
	var $this = $(this);
	var date_arr = [];
	$.ajax({
		type:'GET',
		url:$('#asst_url').val(),
		data: {
			'asst_ids': $this.find(':selected').data('id'),
			'service_ids' : $this.find(':selected').data('serviceids'),
			'provider_id' : $this.val()
		},
		success:function(data){ 
			$('#san_assistnats').html('');
			$('#san_service').html('');
			var option = '<option value="">Select Assistant</option>';
			var option1 = '<option value="">Select Service</option>';
			$.each(data.assistants, function( index, value ) {
				option += '<option value="'+value.id+'">'+value.name+'</option>';
			});
			$.each(data.services, function( index, value ) {
				option1 += '<option value="'+value.id+'">'+value.name+'</option>';
			});
			applyDateFilter(data.availability);
			$('#san_assistnats').html(option);
			$('#san_service').html(option1);
		}
	});
};

function applyDateFilter(availability){
	$( ".datepicker" ).prop("disabled", false);
	$( ".timepicker" ).prop("disabled", false);
	var avail_arr = $.map(availability.days, function(value, index) {
		return [index];
	});
	/* Date Picker*/
	$(".datepicker").datepicker("destroy");
	$( ".datepicker" ).datepicker({
		minDate: 0,
		beforeShowDay: function(date){
			var string = jQuery.datepicker.formatDate('yy-mm-dd', date);
			var day = date.getDay(); 
			var final_day = parseInt(day)+1;
			return [ avail_arr.indexOf(final_day.toString()) == -1 ]
		}
	});
	$( ".datepicker" ).datepicker("refresh");
	/* Time Picker*/
	if ($('#san_timepicker').hasClass('ui-timepicker-input')) {
		$( ".timepicker" ).timepicker('option',{
			'timeFormat': 'H:i',
			'minTime': availability.from[0],
			'maxTime': availability.to[1],
		});
	}else{
		$( ".timepicker" ).timepicker({
			'timeFormat': 'H:i',
			'minTime': availability.from[0],
			'maxTime': availability.to[1],
		});
	}
	// .on('changeTime', function() {
			// 	var get_time = $('.timepicker').timepicker('getTime');
			// 	get_time.setMinutes(get_time.getMinutes() + parseInt(0, 10), 0);
			//     $( ".timepicker" ).timepicker({});
			//   });
		}

		function check_serviceBooking(){ alert($this.val());
			var $this = $(this);
			if (!$this.val()) {
				showError();
			}else{
				$.ajax({
					type:'GET',
					url:$('#chk_book').val(),
					data: {
						'service_id': $this.val(),
						'date':$('#san_datepicker').val(),
						'time':$('#san_timepicker').val(),
						'booking_id':$('#booking_id').val()
					},
					success:function(data){
						if (data && data == 1) {
							showError();
						}else{
							hideError();
						}
					}
				});
			}
			
		}

		function fill_user_details(){
			var $this = $(this);
			if (!$this.val()) {
				showError();
			}else{
				$.ajax({
					type:'GET',
					url:$('#get_user').val(),
					data: {
						'user_id': $this.val()
					},
					success:function(user){
						if (user) {
							$('#first_name').val(user.name);
							$('#email').val(user.email);
							$('#phone').val(user.phone);
							// $('#gender').val(user.gender);
							var gender = 'Female';
							$("#gender option[value="+user.gender+"]").attr('selected', 'selected');
							$("#gender").select2("destroy");
							$("#gender").select2();
						}
					}
				});
			}
		}

		function get_products(){
			var $this = $(this);
			if (!$this.val()) {
				
			}else{
				$.ajax({
					type:'GET',
					url:$('#get_products').val(),
					data: {
						'provider_id': $this.val()
					},
					success:function(data){
						var option = '<option value="">Select Product</option>';
						$.each(data, function( index, value ) {
							option += '<option value="'+value.id+'">'+value.name+'</option>';
						});
						$('#order_product_ids').html(option);
					}
				});
			}
		}

		function showError(){
			$('#service_err').text('This Service not available at selected time!. Change Service');
			$('#service_err').show();
			$( "#booking_validation" ).prop("disabled", true);
		}

		function hideError(){
			$('#service_err').text('');
			$('#service_err').hide();
			$( "#booking_validation" ).prop("enabled", true);
		}