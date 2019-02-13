@php($page = 'product cart')
@extends('maskFront::layouts.app')
@section('main-content')
<style type="text/css">
	h2.salon-step-title {
		padding: 20px;
		text-align: center;
		color: #fff !important;
		font-weight: bold !important;
		background: #131313;
		font-size: 20px !important;
	}
	#profile-section h2 {
		color: #fff;
	}
	.sln-step-summary .btn-glow {
		border-color: #928373 !important;
		background-color: #928373 !important;
		color: #fff;
		display: inline-block;
		font-weight: normal;
		height: 54px !important;
		text-transform: uppercase;
		font-size: 17px;
		padding: 15px 10px !important;
		border-radius: 3px;
		line-height: 27px !important;
		float: left;
		width: 100%;
	}
</style>
<section id="profile-section">
	<input type="hidden" id="chk_email_pro" value="{{ url($locale.'/chk_email') }}">
	<div class="container">
		<div class="col-sm-12 pad-0">
			<div class="row">
				<div class="col-sm-9">
					<div class="col-sm-12 pad-0">
						<div class="tab-content pro-tabcontent">
							<div id="protab-1" class="tab-pane fade in active">
								@if(Auth::check() && isset($_GET['tab']) && $_GET['tab'] =='payment')
									@include('maskFront::includes.payment')
								@else
									@if(!Auth::check())
										@include('maskFront::includes.authenticate')
									@else
										<input type="hidden" id="pro_id" value="@if(isset($product->id)){{$product->id}}@endif">
										<input type="hidden" id="apply_code" value="{{ url($locale.'/applycode') }}">
										<input type="hidden" id="redeem_points" value="{{ url($locale.'/redeem_points') }}">
										<input type="hidden" id="total_amount" value="@if(isset($product->price)){{$product->price}}@endif">
										@if(Auth::check() && isset($_GET['tab']) && $_GET['tab'] =='cartsummary')
											@include('maskFront::includes.cart_book',['sum_type' => 'Order'])
										@else
											@include('maskFront::includes.booking_summary',['sum_type' => 'Order'])
										@endif
									@endif
								@endif
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
@endsection
@section('javascript')
@if(Auth::check())
	<script src="{{ San_Help::san_Asset('js/custom.js') }}"></script>
@endif
<script>
	$('document').ready(function () {
		$("#add_guest").click(function(){
		    var allok = 0;
			var cwd = $('#guest_pwd').val();
			var c_pwd = $('#guest_cpwd').val();
			var email = $('#guest_email').val();
		    $.ajax({
		        headers: {
		            'X-CSRF-TOKEN': $('#csrf_token').val()
		        },
		        type:'POST',
		        url:$('#chk_email_pro').val(),
		        data: {
		            'email': email
		        },
		        success:function(data){
		            if (data == 1) {
		                allok = 1;
		                $('#guest_email').addClass('error_red');
		                alert('Email Already Exist!');
		                return false;
		            }else{
		                var mandoryfields = ['fname','lname','guest_email','guest_address','guest_pwd','guest_cpwd'];
		                $.each(mandoryfields, function( index, value ) {
		                  if (!$('#'+value).val()) {
		                    $('#'+value).addClass('error_red');
		                    allok = 1;
		                  }else{
		                    $('#'+value).removeClass('error_red');
		                  }
		                });
		                $('#guest_email').removeClass('error_red');
		                if ($.trim(cwd) == $.trim(c_pwd) && email) {
		                }else{
		                    allok = 1;
		                }
		                console.log(allok);
		                if (allok == 0) {
		                    $('#guest_form').submit();
		                }
		            }
		        }
		    });

		});
	});
</script>

@stop