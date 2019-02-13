<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script> 
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php echo e(San_Help::san_Asset('js/bootstrap.min.js')); ?>"></script>
<?php if($page == 'home'): ?>
	<script src="<?php echo e(San_Help::san_Asset('js/juicyslider-min.js')); ?>"></script>
	<script type="text/javascript">
		$(function() {
			$('#myslider2').juicyslider({
				width: '100%',
				height: 200,
				mask: 'square',
				show: {effect: 'drop', duration: 3000},
				hide: {effect: 'drop', duration: 3000},
			});
		});
	</script>
<?php endif; ?>
<script src="<?php echo e(San_Help::san_Asset('js/customSliderRange.js')); ?>"></script>
<script src="<?php echo e(San_Help::san_Asset('js/wow.min.js')); ?>"></script>
<script src="<?php echo e(San_Help::san_Asset('js/main.js')); ?>"></script>
<script>
$(".catg-list").click(function(){
	$(".btm-main-menu").slideToggle( "slow", function() {});
});
</script>
<?php if($page == 'booking'): ?>
	<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo e(config('maskfront.google_key')); ?>&libraries=places"></script>
<?php endif; ?>
<script src="<?php echo e(San_Help::san_Asset('js/sweetalert.js')); ?>"></script>
<?php echo $__env->yieldPushContent('scripts'); ?>
<?php echo $__env->yieldPushContent('boot_scripts'); ?>
<?php if($page == 'search'): ?>
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo e(config('maskfront.google_key')); ?>&libraries=places"></script>
<script type="text/javascript">
	var input3 = document.getElementById('location_wr3');
	if (typeof google.maps.places === 'object'){
	    new google.maps.places.Autocomplete(input3);
	}
	</script>
<script src="<?php echo e(San_Help::san_Asset('js/san_map.js')); ?>"></script>
<?php endif; ?>
<script src="<?php echo e(San_Help::san_Asset('js/san_custom.js')); ?>"></script>

<?php if($page == 'product'): ?>
<script src="<?php echo e(San_Help::san_Asset('js/product_detail.js')); ?>"></script>
<?php endif; ?>
<?php if($page == 'home'): ?>
<script src="<?php echo e(San_Help::san_Asset('js/owl.carousel.min.js')); ?>"></script>
<script type="text/javascript">
	$('.owl-carousel').owlCarousel({
	    loop:true,
	    margin:10,
	    autoplay:true,
	    autoplayTimeout:2000,
	    autoplayHoverPause:true,
	    responsiveClass:true,
	    responsive:{
	        0:{
	            items:1,
	            nav:true
	        },
	        600:{
	            items:3,
	            nav:false
	        },
	        1000:{
	            items:3,
	            nav:true,
	            loop:true
	        }
	    }
	});
</script>
<?php endif; ?>
<script type="text/javascript">
    $(function(){
    	$('#lang_chooser').on('change',function(){
    		window.location.href = $(this).val();
    	})
    	$('#currency_chooser').on('change',function(){
    		window.location.href = $(this).val();
    	})
    })
</script>
<script type="text/javascript">
        $(function(){
          $('.open_shpng_cart').on('click',function(){
          	var userid = $('#user_id').val();
			if (!userid) {
			   $('#login-modal').modal('show');
			   return false;
			}
          	jQuery('.cart-box').addClass('open-menu');
          });
          $('#shopping-cart #append_cart_data').on('click','a.action-remove-from-cart',function(){
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('#csrf_token').val()
                },
                type : "POST",
                url  : $('#ajax_url').val()+'/remove_cart',
                data : {
                  cart_id : $(this).data('id'),
                  user_id : $('#user_id').val()
                },
                cache : false,
                success  : function(data) { 
                  var html = '';
                  var total_amnt = 0;
                  var count = 0;
                  $.each(data, function( index, value ) {
                  	  count = parseInt(count)+1;
                  	  total_amnt = parseFloat(total_amnt)+parseFloat(value.total);
                      html +='<tr>'+
	                      '<td height="30"><h5 class="item-name">'+value.product.name+'</h5></td>'+
	                      '<td height="30"><h4 class="ui header" style="color: white;">SAR '+value.total+'</h4></td>'+
	                      '<td><a href="javascript:void(0);" data-id="'+value.id+'" class="ui icon button action-remove-from-cart">&times;</a></td>'+
	                   '</tr>';
	                   $('.checkout_san_btn').prop('disabled', false);
                  });
                  if (html == '') {
                  	var url = "<?php echo e(url($locale.'/search?type=products&wr=&pr=')); ?>";
                  	html +='<tr style="text-align: center; font-size: 1.1em;" id="no-items-in-cart">'+
	                  '<td height="60">'+
	                     '<div class="ui hidden divider"></div>'+
	                     '<span style="font-style: italic;">Your Shopping Cart is Currently Empty...</span>'+
	                     '<br>'+
	                     '<a href="'+url+'">Click here to browse our accounts</a>'+
	                     '<div class="ui hidden divider"></div>'+
	                  '</td>'+
	                  '<td class="collapsing currency-target left aligned"></td>'+
					  '<td></td>'+
	               '</tr>';
	               $('.checkout_san_btn').prop('disabled', true);
                  }
                  $('li.shopping-cart .badge').text(count);
                  $('#shopping-cart #append_cart_data').html(html);
                  $('#shopping-cart b#cart_total_amnt').text(total_amnt);
                }
            });
          });
          <?php if(isset($_GET['uid'])): ?>
          	$('#forgot_pwd').modal('show');
          <?php endif; ?>
          /* Ajax run on submit login button on ajax login popup */
		    $("#update_pass").click(function(event) {
		        event.preventDefault();
		        $.ajax({
		        	headers: {
	                    'X-CSRF-TOKEN': $('#csrf_token').val()
	                },
		            type:'POST',
		            url: $('#ajax_url').val()+'/update_pass',
		            data: {
		                password: $(".custom_forgot_pass_form #new_pass").val(),
		                password_confirm: $(".custom_forgot_pass_form #conf_new_pass").val(),
		                user_id: $('.custom_forgot_pass_form #forget_user_id').val(),
		            },
		            dataType: "json",
		            beforeSend: function(){
		            },
		            success:function(data){
		                if(data['res']==0){
		                    $('#update_msg_block').show();
		                    $('#update_msg_block').html(data['msg']);
		                }else if (data['res']==1){
		                    $('#update_msg_block').hide();
		                    $('#update_msg_success').show();
		                    $('#update_msg_success').html(data['msg']); 
		                     window.setTimeout(function(){
		             		$('#forgot_pwd').hide();
		                	$('#forgot_pwd').removeClass('in');
		                    $('#login-modal').show();
		              		$('#login-modal').addClass('in');
		                    }, 1000);
		                }
		            }
		        });
		    });
        });
</script>