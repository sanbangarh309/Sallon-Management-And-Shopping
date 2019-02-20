@php($page = 'booking')
@extends('maskFront::layouts.app')
@section('main-content')
<style type="text/css">
li.disabled{
	pointer-events:none;
	opacity:0.4;
}
</style>
<link href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" rel = "stylesheet">
<section id="profile-section">
	<input type="hidden" id="chk_book_service" value="{{ url($locale.'/chk_service_book') }}">
	<input type="hidden" id="redeem_points" value="{{ url($locale.'/redeem_points') }}">
	<input type="hidden" id="user_image" value="{{url('files/')}}">
	<input type="hidden" id="provider_image" value="{{url('files/'.$provider->avatar)}}">
	<input type="hidden" id="apply_code" value="{{ url($locale.'/applycode') }}">
	<input type="hidden" id="chk_email" value="{{ url($locale.'/chk_email') }}">
	<input type="hidden" id="tab_type" value="@if(isset($_GET['tab'])){{$_GET['tab']}} @endif">
	<input type="hidden" id="pro_id" value="@if(isset($provider->id)){{$provider->id}} @endif">
	<input type="hidden" id="total_amount" value="@if(isset($total_amount)){{$total_amount}} @else @endif">
	<input type="hidden" id="latitude" value="@if(isset($provider->latitude)){{$provider->latitude}} @endif">
	<input type="hidden" id="longitude" value="@if(isset($provider->longitude)){{$provider->longitude}} @endif">
	<input type="hidden" id="salon_address" value="@if(isset($provider->address)){{$provider->address}} @endif">
	<input type="hidden" id="c_latitude" value="">
	<input type="hidden" id="c_longitude" value="">
	<input type="hidden" id="book_date_san" value="@if(session('book_date')){{session('book_date')}} @endif">
	<input type="hidden" id="book_time_san" value="@if(session('book_time')){{session('book_time')}} @endif">
	<input type="hidden" id="c_address" value="">
	<div class="container">
		<div class="col-sm-12 pad-0">
			<div class="row">
				<div class="col-sm-9">
					<ul class="nav nav-tabs nav-justified profile-tabs">
						<li @if(isset($_GET['tab']) && $_GET['tab'] =='profile') class="active" @endif><a data-toggle="tab" href="#protab-1">{!!San_Help::sanLang('Profile')!!}</a></li>
						<li @if(!isset($_GET['tab']) || (isset($_GET['tab']) && in_array($_GET['tab'] , ['check','summary','payment']))) class="active" @endif @if(!isset($cats)) id="disable_booking_tab" @endif ><a data-toggle="tab" href="#protab-2">{!!San_Help::sanLang('Book Service')!!}</a></li>
						<li @if(!isset($_GET['tab']) || (isset($_GET['tab']) && in_array($_GET['tab'] , ['check','summary','payment']))) class="" @endif @if(!isset($cats)) id="disable_booking_tab_service" @endif ><a data-toggle="tab" href="#protab-service">{!!San_Help::sanLang('Services')!!}</a></li>
						<li @if(!isset($cats)) id="disable_product_tab" @endif><a data-toggle="tab" href="#product_tab">{!!San_Help::sanLang('Products')!!}</a></li>
						<li @if(!isset($cats)) id="disable_product_tab_review" @endif><a data-toggle="tab" href="#product_tab_review">{!!San_Help::sanLang('Product Reviews')!!}</a></li>
						<li><a data-toggle="tab" href="#protab-3">{!!San_Help::sanLang('Gallery')!!}</a></li>
						<li><a data-toggle="tab" href="#protab-4"> {!!San_Help::sanLang('Reviews')!!}</a></li>
					</ul>
					<div class="col-sm-12 pad-0">
						<div class="tab-content pro-tabcontent">
							<div id="protab-1" class="tab-pane fade @if(isset($_GET['tab']) && $_GET['tab'] =='profile')in active @endif">
								<h3>@if(isset($provider->name)){!!San_Help::sanGetLang($provider->name)!!}@endif<span class="aside">@if(isset(config('maskfront.dropdown_fixed')[$provider->type])){{config('maskfront.dropdown_fixed')[$provider->type]}}@endif</span></h3>
								<p>@if(isset($provider->description)){!!$provider->description!!}@endif</p>
								<div class="add_team_div">
									<h3>{!!San_Help::sanLang('Our Team')!!}</h3>
								</div>
								<div class="col-sm-12 pad-0">
									<div class="well tabs-well">
										<div class="col-sm-12">
											<ul class="list-inline members-list">
												@if($provider->getAssistants)
												@foreach($provider->getAssistants as $assistant)
												@php($services = San_Help::getAssService(unserialize($assistant->service_ids)))
												<li>
													<a href="#" class="team-member">
														<div class="member-image">
															<img class="img-responsive img-circle" src="@if(isset($assistant->image) && $assistant->image !=''){{url('files/'.$assistant->image)}} @else {{ San_Help::san_Asset('images/member-1.jpg') }} @endif" alt="">
														</div>
														<div class="member-info">
															<h3>@if(isset($assistant->name)){{$assistant->name}}@endif<span class="aside h5">@if(isset($assistant->service_ids)){{implode(',',$services)}}@endif</span></h3>
														</div>
													</a>
												</li>
												@endforeach
												@endif
											</ul>
										</div>
									</div>
								</div>
							</div>
							<div id="protab-2" class="tab-pane fade @if(!isset($_GET['tab']) || (isset($_GET['tab']) && in_array($_GET['tab'] , ['check','summary','payment']))) in active @endif ">
								<a data-target=".bottom-menu7" data-toggle="collapse" class="butt-select visible-xs" type="">Select Your Services <i class="pull-right fa fa-angle-down"></i></a>
								<div class="col-sm-12 pad-0">
									<div class="tab-inner">
										<div class="col-sm-12 pad-0">
											<div class="tab-content tab-content2">
												<div id="catt-1" class="tab-pane fade in active">
													<div id="step-1" class="col-sm-12 setup-content" style="display:block;">
														<h3 class="text-uppercase hidden-xs">Select Date and time </h3>
														<div id="datepicker"></div>
														<input type="hidden" id="selected_date" value="">
														<div id="timeset" class="col-sm-12">
															<div class="t-txt pull-left">
																<h4>Select Time:</h4>
															</div>
															<div class="time-boxes pull-left">
																<ul class="list-inline time-list">
																	<input type="hidden" id="selected_time" value="">
																	@if(isset($provider->getAvail->availability))
																	<input type="hidden" id="avail_data" value="{{json_encode(unserialize($provider->getAvail->availability))}}">
																	@php($times = unserialize($provider->getAvail->availability))
																	@foreach(San_Help::durationData($times['from'][0],$times['to'][1]) as $dur)
																	<li>
																		<input  name="time" class="time-btn" value="{{$dur}}" type="radio">
																		<span class="time-value">{{$dur}}</span>
																	</li>
																	@endforeach
																	@endif
																</ul>
															</div>
														</div>
														<div class="col-sm-12 btm-section">
															<div class="row">
																<div class="col-sm-6 text-left">
																	<button class="btn next--btn BackBtn2">Back</button>
																</div>
																<div class="col-sm-6 text-right">
																	<button class="btn next--btn NextBtn3">Next</button>
																</div>
															</div>
														</div>
														<!-- <button class="btn next-btn NextBtn">Next</button> -->
													</div><!-- STEP-1-ENDS -->
													<div id="step-2" class="col-sm-12 pad-0 setup-content" style="display:none;">
														<!--BOOKING-SECTION-STARTS-HERE-->
														<div id="sln-salon" class="sln-bootstrap container-fluid sln-salon--l sln-step-services">
															<form id="salon-step-services" method="post" action="/sallons/bobbys-sallon/?tab=_services&amp;sln_step_page=services" role="form">
																<div class="tab-inner">
																	<a href="javascript:void(0)" id="drop-toggle3" data-target=".bottom-menu73" data-toggle="collapse" class="drop-toggle visible-xs" type="">Services List<i class="pull-right fa fa-bars"></i></a>
																	<div class="bottom-menu7" role="navigation" aria-expanded="true" style="">
																		<div class="bstimeslider">
																			<a class="slide-arrow" href="javascript:void(0)" id="previous-column"><i class="fa fa-angle-left"></i></a>
																			<div class="table-container">
																				<ul class="sliding-window nav nav-tabs catt-tab" id="">
																					@if(isset($cats))
																					@foreach($cats as $key => $cat)
																					<li class="@if($key == 0) active @endif"><a data-toggle="tab" href="#catt-{{$cat->id}}"><img src="@if(isset($cat->icon) && $cat->icon !=''){{url('files/'.$cat->icon)}} @else {{url('files/services/spa.png')}} @endif  " alt=""><span class="aside">@if(isset($cat->name)){!!San_Help::sanGetLang($cat->name)!!}@endif</span></a></li>
																					@endforeach
																					@endif
																				</ul>
																			</div>
																			<a class="slide-arrow" href="javascript:void(0)" id="next-column"><i class="fa fa-angle-right"></i></a>
																		</div>
																	</div>
																	<div class="tab-content tab-content2">
																		<!-- <div class="sln-service-list"> -->
																		@if(isset($cats))
																		@if(session('sids'))
																		@php($sids = unserialize(session('sids')))
																		@endif
																		@foreach($cats as $key => $cat)
																		<div id="catt-{{$cat->id}}" class="tab-pane fade @if($key == 0 ) in active @endif">
																			<div id="step-1" class="col-sm-12 setup-content" style="display:block;">
																				<div class="rows sln-service sln-service--6765">
																					<ul class="list-inline services_lists">
																						@foreach($cat->getServices as $service)
																						<li id="{{$service->id}}">
																							@php($checked = '')
																							@if(isset($sids) && is_array($sids) &&  in_array($service->id,$sids))
																							@php($checked = 1)
																							@endif
																							<input type="checkbox" name="services" @if($checked == 1) checked = "checked" @endif class="serv_chkbxes" id="{{$service->id}}" value="1" data-price="{{$service->price}}" data-duration="{{$service->duration}}">
																							<label class="serv_ctrl">
																								<span class="srv_name">{!!San_Help::sanGetLang($service->name)!!}<span class="srv_duration">{{San_Help::money($service->price)}} {!!$currency!!} for {{$service->duration}}</span></span>
																							</label>
																						</li>
																						@endforeach
																					</ul>
																				</div>
																			</div>
																		</div>
																		@endforeach
																		@endif
																		<!-- </div> -->
																	</div>
																</div>
															</form>
															<div id="sln-notifications"></div>
														</div>

														<!--BOOKING-SECTION-STARTS-HERE-->
														<div class="col-sm-12 btm-section">
															<div class="row">
																<div class="col-sm-6 text-left">
																	<button class="btn next--btn BackBtn">Back</button>
																</div>
																<div class="col-sm-6 text-right">
																	<button class="btn next--btn NextBtn2">Next</button>
																</div>
															</div>
														</div>
													</div><!-- STEP-2-ENDS -->
													<div id="step-3" class="col-sm-12 setup-content" style="display:none;">
														<form class="form-edit-add" role="form" action="{{url($locale.'/booking/'.$provider->id.'?tab=check')}}" method="POST" enctype="multipart/form-data" autocomplete="off" id="service_form">
															{{ csrf_field() }}
															<input type="hidden" name="book_date" id="book_date" value="">
															<input type="hidden" name="book_time" id="book_time" value="">
															<input type="hidden" name="total_amount" id="san_ttl_amntt" value="@if(isset($total_amount)){{$total_amount}} @else 0.00 @endif">
															<div class="well workers-box">
																<h2 class="text-uppercase tbs-head">{!!San_Help::sanLang('Select Your Assistant')!!}</h2>
																<div id="append_selected_services">

																</div>

															</div>
															<!--BOOKING-SECTION-STARTS-HERE-->
															<div class="col-sm-12 btm-section">
																<div class="row">
																	<div class="col-sm-6 text-left">
																		<button type="button" class="btn next--btn BackBtn3">Back</button>
																	</div>
																	<div class="col-sm-6 text-right">
																		<button type="button" class="btn next--btn NextBtn4">Next</button>
																	</div>
																</div>
															</div>
														</form>
													</div><!-- STEP-3-ENDS -->
													<div id="step-4" class="col-sm-12 pad-0 setup-content" style="display:none;">
														@if(!Auth::check())
														@include('maskFront::includes.authenticate')
														@endif
													</div><!-- STEP-4-ENDS -->
													<div id="step-5" class="col-sm-12 pad-0 setup-content" style="display:none;">
														<div class="tab-content pro-tabcontent slon--contentt">
															<div id="protab-6" class="tab-pane fade in active">
																@include('maskFront::includes.booking_summary')
															</div>									</div>
														</div>
														<div id="step-6" class="col-sm-12 pad-0 setup-content" style="display:none;">
															@include('maskFront::includes.payment')
														</div>
													</div><!-- INNTER_TAB_CONTENT_END -->
												</div>
											</div>
										</div>
									</div>
								</div>
								<div id="protab-service" class="tab-pane fade">
									<div id="sln-salon" class="sln-bootstrap container-fluid sln-salon--l sln-step-services">
										<div class="tab-inner">
											<a href="javascript:void(0)" id="drop-toggle3" data-target=".bottom-menu73" data-toggle="collapse" class="drop-toggle visible-xs" type="">Services List<i class="pull-right fa fa-bars"></i></a>
											<div class="bottom-menu7" role="navigation" aria-expanded="true" style="">
												<div class="bstimeslider">
													<a class="slide-arrow" href="javascript:void(0)" id="previous-column"><i class="fa fa-angle-left"></i></a>
													<div class="table-container">
														<ul class="sliding-window nav nav-tabs catt-tab" id="">
															@if(isset($cats))
															@foreach($cats as $key => $cat)
															<li class="@if($key == 0) active @endif"><a data-toggle="tab" href="#scatt-{{$cat->id}}"><img src="@if(isset($cat->icon) && $cat->icon !=''){{url('files/'.$cat->icon)}} @else {{url('files/services/spa.png')}} @endif  " alt=""><span class="aside">@if(isset($cat->name)){!!San_Help::sanGetLang($cat->name)!!}@endif</span></a></li>
															@endforeach
															@endif
														</ul>
													</div>
													<a class="slide-arrow" href="javascript:void(0)" id="next-column"><i class="fa fa-angle-right"></i></a>
												</div>
											</div>
											<div class="tab-content tab-content2">
												<!-- <div class="sln-service-list"> -->
												@if(isset($cats))
												@foreach($cats as $key => $cat)
												<div id="scatt-{{$cat->id}}" class="tab-pane fade @if($key == 0 ) in active @endif">
													<div class="col-sm-12 setup-content" style="display:block;">
														<div class="rows sln-service sln-service--6765">
															<ul class="list-inline services_lists">
																@foreach($cat->getServices as $service)
																<li id="{{$service->id}}">
																	<label class="serv_ctrl">
																		<span class="srv_name">{!!San_Help::sanGetLang($service->name)!!}<span class="srv_duration">{{San_Help::money($service->price)}} {!!$currency!!} for {{$service->duration}}</span></span>
																	</label>
																</li>
																@endforeach
															</ul>
														</div>
													</div>
												</div>
												@endforeach
												@endif
												<!-- </div> -->
											</div>
										</div>
										<div id="sln-notifications"></div>
									</div>
								</div>
								<div id="product_tab" class="tab-pane fade">
									<h3 class="text-uppercase">Products</h3>
									<div class="col-sm-12 pad-0 item-boxtab">
										<div class="well pro-gallery-well">
											<ul class="list-inline prod-item-list  hair-list proitem-list">
												@if(isset($provider->getProducts))
												@foreach($provider->getProducts as $product)
												<li class="product_box">

													<a href="javascript:void(0)" class="hair-style">
														<div class="hair-image" style="background:url(@if(isset($product->image) && $product->image !=''){{url('files/'.$product->image)}} @endif)"></div>
													</a>
													<div class="captions">
														<div class="col-sm-12 pad-0 user-areas">
															<div class="img-sec">
																<h5>{{$product->name}}<span class="small">{!!San_Help::sanLimited($product->description,40,route('product',$product->id))!!}</span></h5>
																<h5 class="item-price">{{San_Help::money($product->price)}} {!!$currency!!}</h5>
															</div>
															<div class="feed">
																<ul class="list-inline rating-list">
																	@for ($i = 1; $i <= 5; $i ++)
																	@php($selected = "")
																	@if (isset($product->rating) && $i <= $product->rating)
																	@php($selected = "checked")
																	@endif
																	<li><span class="fa fa-star {{$selected}}"></span></li>
																	@endfor
																</ul>
															</div>
														</div>
														<!-- <h4 class="group inner list-group-item-heading">Brighten Up Your Hair Color</h4> -->

														<div class="col-sm-12 pad-0">
															<div class="share pull-left text-right">
																<ul class="list-inline share-list">
																	<li><a href="#"><i class="fa fa-heart"></i></a></li>
																	<li><a href="#"><i class="fa fa-share-alt"></i></a></li>
																</ul>
															</div>
															<div class="btn-sec text-right">
																<a href="{{url($locale.'/product/'.$product->id)}}" class="btn book-btn">Buy now</a>
															</div>
														</div>
													</div>
												</li>
												<!-- <tr>
												<td data-th="Image"><img style="width: 20%" src="@if(isset($product->image) && $product->image !=''){{url('files/'.$product->image)}} @endif  " alt=""></td>
												<td data-th="Name">{{$product->name}}</td>
												<td data-th="Price">{{$product->price}}</td>
												<td data-th="Quantity">{{$product->qty}}</td>
											</tr> -->
											@endforeach
											@endif
										</ul>
									</div>
								</div>
							</div>
							<div id="product_tab_review" class="tab-pane fade">
								<ul class="list-inline rev-list">
									<!-- Start Listing -->
									@if(!empty($product_reviews))
									@foreach($product_reviews as $review)
									<li>
										<a href="javascript:void(0)" class="list-group-item active">
											<div class="media col-md-2 col-xs-2 pad-0">
												<figure class="pull-left">
													<img class="media-object img-circle img-responsive"  src="@if(isset($review['user_id'])){{url('files/'.\App\User::find($review['user_id'])->avatar)}}@endif" alt="" >
												</figure>
											</div>
											<div class="col-md-10 col-xs-10">
												<p class="list-group-item-text"> {!!$review['review']!!}
												</p>
												<div class="col-sm-12">
													<div class="feed col-sm-6 pad-0 pull-left">
														<ul class="list-inline rating-list">
															@for ($i = 1; $i <= 5; $i ++)
															@php($selected = "")
															@if ($i <= $review['rating'])
															@php($selected = "checked")
															@endif
															<li><span class="fa fa-star {{$selected}}"></span></li>
															@endfor
														</ul>
													</div>
													<div class="author-des col-sm-6 pad-0 text-right">
														<p>@if(isset($review['user_id'])){{\App\User::find($review['user_id'])->name}}@endif, {{ Carbon\Carbon::parse($review['created_at'])->format('d F Y') }}</p>
													</div>
												</div>
											</div>

										</a>
									</li>
									@endforeach
									@else
									<li style="text-align: center;">
										Review Not Exist
									</li>
									@endif
									<!-- End Listing -->
								</ul>
							</div>
							<div id="protab-3" class="tab-pane fade">
								<h3 class="text-uppercase">Our Gallery</h3>
								<div class="col-sm-12 pad-0">
									<div class="well gallery-well">
										<ul class="list-inline hair-list">
											@isset($provider->provider_images)
											@foreach($provider->provider_images as $provider_image)
											@php($img = url('files/'.$provider_image->filename))
											<li>
												<a href="javascript:void(0)" class="hair-style">
													<div class="hair-image" style="background:url({{$img}})"></div>
												</a>
											</li>
											@endforeach
											@endif
										</ul>
									</div>
								</div>
							</div>
							<div id="protab-4" class="tab-pane fade">
								<ul class="list-inline rev-list">
									<!-- Start Listing -->
									@if(!$reviews->isEmpty())
									@foreach($reviews as $review)
									<li>
										<a href="#" class="list-group-item active">
											<div class="media col-md-2 col-xs-2 pad-0">
												<figure class="pull-left">
													<img class="media-object img-circle img-responsive"  src="@if(isset($review->user_id)){{url('files/'.\App\User::find($review->user_id)->avatar)}}@endif" alt="" >
												</figure>
											</div>
											<div class="col-md-10 col-xs-10">
												<p class="list-group-item-text"> {!!$review->review!!}
												</p>
												<div class="col-sm-12">
													<div class="feed col-sm-6 pad-0 pull-left">
														<ul class="list-inline rating-list">
															@for ($i = 1; $i <= 5; $i ++)
															@php($selected = "")
															@if ($i <= $review->rating)
															@php($selected = "checked")
															@endif
															<li><span class="fa fa-star {{$selected}}"></span></li>
															@endfor
														</ul>
													</div>
													<div class="author-des col-sm-6 pad-0 text-right">
														<p>@if(isset($review->user_id)){{\App\User::find($review->user_id)->name}}@endif, {{ Carbon\Carbon::parse($review->created_at)->format('d F Y') }}</p>
													</div>
												</div>
											</div>

										</a>
									</li>
									@endforeach
									@else
									<li style="text-align: center;">
										Review Not Exist
									</li>
									@endif
									<!-- End Listing -->
								</ul>
							</div>
						</div>
					</div>
				</div>
				<div class="col-sm-3 right-sidebar">
					<!-- <div class="top-mapp hidden-xs">
					<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1612502.511845104!2d-123.54149079038609!3d37.87388356475237!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x808583a3a688d7b5%3A0x8c891b8457461fa9!2sSan+Francisco+Bay+Area%2C+CA%2C+USA!5e0!3m2!1sen!2sin!4v1521811459551" width="100%" height="250" frameborder="0" style="border:0" allowfullscreen></iframe>
				</div> -->
				<div id="map" style="display:none; height: 500px; width: 100%"></div>
				<div class="col-sm-12 text-center gap-btm-30" id="viewon_gmp" style="display:none;">
					<a href="" target="_blank" id="get_gmp" class="btn btn-get">{!!San_Help::sanLang('View On Google Map')!!}</a>
				</div>
				<div class="col-sm-12 text-center gap-btm-30 hidden-xs">
					<a id="get_direction" onclick="initdirectionMap()" href="javascript:void(0)" class="btn btn-get">{!!San_Help::sanLang('Get Directions')!!}</a>
				</div>
				<div class="col-sm-12 pad-0 b-summary gap-30">
					<div class="well sumary-well">
						<h4 class="text-uppercase"><b>{!!San_Help::sanLang('Booking Summary')!!} :</b></h4>
						<p>Date : 	<span id="serv-day">{{session('book_date')}}</span></p>
						<p>Timings : <span id="time-format">{{session('book_time')}}</span></p>
						<p>Service : @if(isset($ser_names)) @foreach($ser_names as $ser_name)<span id="serv-type">{!!San_Help::sanGetLang($ser_name->name)!!}</span>@endforeach @else <span id="serv-type"></span> @endif</p>
						<p>Stylist : <span id="expert-type">@if(isset($ass_names) && !empty($ass_names)){{implode(',',$ass_names)}} @elseif(isset(unserialize(session('aids'))[0])) {{unserialize(session('aids'))[0]}} @endif</span></p>
						<!-- @if(session('book_date')){{session('book_date')}}@endif / @if(session('book_time')){{session('book_time')}}@endif -->
					</div>
				</div>
				<div class="col-sm-12 pad-0 buss-time gap-30">
					<h4 class="text-uppercase">SHARE PROFILE</h4>
					<ul class="list-inline social-list">
						<li><a href="#"><i class="fa fa-facebook-square"></i></a></li>
						<li><a href="#"><i class="fa fa-twitter-square"></i></a></li>
						<li><a href="#"><i class="fa fa-instagram"></i></a></li>
					</ul>
				</div>
			</div>

		</div>
	</div>
</div>
</section>
@push('scripts')
<script src="{{ San_Help::san_Asset('js/custom.js') }}"></script>
@endpush
@endsection
