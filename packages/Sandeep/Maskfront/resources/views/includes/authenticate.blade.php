<style type="text/css">
	/* Guest from validation */
	#guest_form input.error_red{
		border: 1px solid red !important;
	} 
	#guest_form select.error_red {
		border: 1px solid red !important;
	}
</style>
@php($cntries = \Sandeep\Maskfront\Models\Country::all())
<div class="panel payment-panel">
	<h4><div id="msg_block"></div></h4>
																	<h2 class="text-uppercase tbs-head">Returning customer? Please, log-in.</h2>
																	<div class="col-sm-12 pad-0 form-details">
																		<form class="" method="post" action="{{route('clogin')}}" role="form" id="login_from_checkout">
																			{{ csrf_field() }}
																			<?php //echo '<pre>';print_r($selected_services); ?>
																			<input type="hidden" name="booking_type" value="@if(isset($provider->id)) service @else product @endif">
																			<input type="hidden" name="pro_id" value="@if(isset($provider->id)){{$provider->id}}@else{{$product->id}} @endif">
																			<input type="hidden" name="sids" value="@if(isset($_GET['sids'])){{json_encode($_GET['sids'])}} @endif">
																			<input type="hidden" name="aids" value="@if(isset($_GET['aids'])){{json_encode($_GET['aids'])}} @endif">
																			<div class="row">
																				<div class="col-sm-6 col-md-4">
																					<label class="form-label" for="login_name">E-mail</label>
																					<input name="email" type="text" id="login_username" class="form-control">
																					<span class="help-block">
																						<a href="javascript:void(0)" class="fg-link">Forgot password?</a>
																					</span>
																				</div>
																				<div class="col-sm-6 col-md-4">
																					<label for="login_password">Password</label>
																					<input name="password" id="login_password" type="password" class="sln-input sln-input--text">
																				</div>
																				<div class="col-sm-6 col-md-4">
																					<label for="login_name">&nbsp;</label>
																					<div class="col-sm-12 pad-0">
																						<button class="btn logg-btn" type="submit" name="submit_details" value="next">Login <i class="glyphicon glyphicon-user"></i>
																						</button>
																					</div>
																					<span class="help-block"></span>
																				</div>
																			</div>
																		</form>
																	</div>
																</div>
																
																<div class="col-md-12 payment-box">
																	<h2 class="tbs-head tbs-head2">Checkout as a guest, An account will be automatically created</h2>
																</div>
																<div class="payment-form">
																	<form action="{{route('member_register')}}" id="guest_form" class="form" method="post">
																		{{ csrf_field() }}
																		<div id="pay_msg_block"></div>
             															<div id="pay_msg_block_success" class="success-msg"></div>
																		<input type="hidden" name="booking_type" value="@if(isset($provider->id)) service @else product @endif">
																		<input type="hidden" name="pro_id" value="@if(isset($provider->id)){{$provider->id}}@else{{$product->id}}@endif">
																		<div class="col-md-12">
																			<div class="row">
																				<div class="col-sm-6">
																					<div class="form-group">
																						 <label for="">First name</label>
																						<input class="form-control" type="text" id="fname" placeholder="Enter First Name" name="name">
																					</div>
																				</div>
																				<div class="col-sm-6">
																					<div class="form-group">
																						<label for="">Last name</label>
																						<input class="form-control" id="lname" type="text" placeholder="Enter Last Name" name="lname">
																					</div>
																				</div>
																			</div>
																		</div>
																		<div class="col-md-12">
																			<div class="row">
																				<div class="col-sm-6">
																					<div class="form-group">
																						 <label for="">E-mail</label>
																						<input class="form-control"  type="email" placeholder="Enter E-Mail" id="guest_email" name="email">
																					</div>
																				</div>
																				<div class="col-sm-6">
																					<div class="form-group">
																						<label for="">Mobile phone</label>
																						<select name="country" class="form-control input-field">
																                           @foreach($cntries as $key => $value)
																                           <option value="{{$value->phonecode}}" @if($value->phonecode == '966') selected = "selected" @endif>{{$value->name}}({{$value->phonecode}})</option>
																                           @endforeach
																                        </select>
																						<input class="form-control" type="text" id="guest_mobile" placeholder="Enter Mobile No." name="phone">
																					</div>
																				</div>
																			</div>
																		</div>
																		<div class="col-md-12">
																			<div class="form-group">
																				<label for="">Address</label>
																				<input class="form-control" id="guest_address" type="text" placeholder="Enter Your Address" name="address">
																			</div>
																		</div>	
																		<div class="col-md-12">
																			<div class="rows">
																				<div class="col-sm-6">
																					<div class="form-group">
																						 <label for="">Password</label>
																						<input class="form-control" id="guest_pwd" type="password" placeholder="Enter Password" name="password">
																					</div>
																				</div>
																				<div class="col-sm-6">
																					<div class="form-group">
																						<label for="">Confirm Password</label>
																						<input class="form-control" id="guest_cpwd" type="password" placeholder="Confirm Password" name="user_conrfpassword">
																					</div>
																				</div>
																			</div>
																		</div>																		
																		<div class="form-group buttns-list">
																			<a href="javascript:void(0)" class="btn submit-btn logg-btn btn-lg" id="submit_form_back"><i class="glyphicon glyphicon-chevron-left"></i> Back</a>
																			<a href="javascript:void(0)" class="btn submit-btn logg-btn btn-lg" id="add_guest">Submit <i class="glyphicon glyphicon-chevron-right"></i></a>
																		</div>
																	</form>
																</div>