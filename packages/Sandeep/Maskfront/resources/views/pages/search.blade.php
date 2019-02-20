@php($page = 'search')
@extends('maskFront::layouts.app')
@section('custom_css')
<style type="text/css">
.prod-item-list > li .checked {
	color: #c79044;
}

.prod-item-list li span.fa.checked {
	color: #c79044;
}
.share-list li a._add_favorite.added {
	color: tomato;
	font-size: 18px;
}
</style>
@if(app('request')->type == 'products')
@php($page = $page.'_products')
@endif
@endsection
@section('main-content')
<input type="hidden" value="{{url('files/market.png')}}" id="marker1">
<input type="hidden" value="{{url('files/market_light_80.png')}}" id="marker2">
<section id="search-result">
	<input type="hidden" value='@if(isset($sallons) && !empty($sallons)){{json_encode($sallons)}}@endif' id="sallons_array">
	<div class="container-fluid page-fluid">
		<div class="col-sm-12 featured-serv pad-xs-0">
			<div class="row">
				<div class="@if(app('request')->type == 'services') col-sm-6 @else col-sm-12 @endif">
					<div class="panel-tp @if(app('request')->type == 'products') full_width_box @endif">
						<div class="col-sm-12 pad-xs-0 custom-slects">
							<div class="btn-group cst-group">
								<div class="col-sm-12 pad-0">
									<div class="tab-content">
										<div id="tab-1" class="tab-pane fade in active">
											<div id="products" class="list-group">
												@if($type == 'services')
												<h3 class="text-uppercase">Popular Salons</h3>
												@endif
												<div id="content-1" class="content">
													<ol class="list-inline pro--list2 content mCustomScrollbar _mCS_1 mCS-autoHide" id = "san_append_provider_list">
														@if($type == 'services')
														@if(!empty($sallons))
														@foreach($sallons as $sallon)
														<li class="list-item-box" onmouseover="hover({{$sallon['id']}})" onmouseout="out({{$sallon['id']}})">
															<div class="item list-group-item">
																<div class="thumbnail">
																	<a href="{{route('booking' ,$sallon['id'])}}" class="user_image_link">
																		<div class="thumb list-group-image" style="background:url({{url('files/'.$sallon['avatar'])}})">
																		</div>
																	</a>

																	<div class="captions">
																		<div class="top-info-section">
																			<div class="left-img-sec pull-left">
																				<img src="{{url('files/'.$sallon['avatar'])}}" class="img-circle user-img">
																				<h5><a href="{{route('booking' ,$sallon['id'])}}">{!!San_Help::sanGetLang($sallon['name'])!!}<span  class="small sl-category">@if(isset(config('maskfront.dropdown_fixed')[$sallon['type']])){{config('maskfront.dropdown_fixed')[$sallon['type']]}}@endif</span></a></h5>
																			</div>
																			<div class="feed prod-item-list">
																				<ul class="list-inline rating-list">
																					@for ($i = 1; $i <= 5; $i ++)
																					@php($selected = "")
																					@if (!empty($sallon['reviews']) && $i <= $sallon['avg_rating'])
																					@php($selected = "checked")
																					@endif
																					<li><span class="fa fa-star {{$selected}}"></span></li>
																					@endfor
																				</ul>
																			</div>
																		</div>
																		<div class="col-sm-12 pad-0 btm-flexboxx">
																			<div class="share">
																				<ul class="list-inline share-list">
																					@if(Auth::check() && Auth::user()->favourite)
																					@php($fav = unserialize(Auth::user()->favourite))
																					@endif
																					<li><a href="javascript:void(0)" data-type="provider" data-id="{{$sallon['id']}}" class="_add_favorite @if(isset($fav) && in_array($sallon['id'],$fav))added @endif" id="add_favorite_{{$sallon['id']}}"><i class="fa fa-heart"></i></a></li>
																					<!-- fb-share-button -->
																					<li><a href="javascript:void(0)"><i class="fa fa-share-alt" data-href="{{url($locale.'/search?type=services&wr=&pr=')}}"></i></a></li>
																				</ul>
																			</div>
																			<div class="col-sm-4 pad-0 features-areas">
																				<ul class="list-inline extra_features">
																					@php($avail = \TCG\Voyager\Models\Avail::where('provider_id',$sallon['id'])->first())
																					@if(isset($avail->extra))
																					@php($realdata = unserialize($avail->extra))
																					@endif
																					<?php //echo '<pre>';print_r($realdata); ?>
																						@if(isset($realdata))
																						@if(isset($realdata['welcome_drink']) && $realdata['welcome_drink'] == 1)
																						<li>
																							<img src="/packages/Sandeep/Maskfront/resources/assets/images/WelcomeDrink.png" title="Welcome Drink">
																						</li>
																						@endif
																						@if(isset($realdata['kids_care']) && $realdata['kids_care'] ==1)
																						<li>
																							<img src="/packages/Sandeep/Maskfront/resources/assets/images/Kids.png" title="Kids Care">
																						</li>
																						@endif
																						@if(isset($realdata['pets']) && $realdata['pets'] ==1)
																						<li>
																							<img src="/packages/Sandeep/Maskfront/resources/assets/images/Pets.png" title="Pets">
																						</li>
																						@endif
																						@if(isset($realdata['cash']) && $realdata['cash'] ==1)
																						<li>
																							<img src="/packages/Sandeep/Maskfront/resources/assets/images/cash.png" title="Accept Payment by Cash">
																						</li>
																						@endif
																						@if(isset($realdata['wifi']) && $realdata['wifi'] ==1)
																						<li>
																							<img src="/packages/Sandeep/Maskfront/resources/assets/images/Wifi.png" title="Wifi">
																						</li>
																						@endif
																						@if(isset($realdata['card']) && $realdata['card'] ==1)
																						<li>
																							<img src="/packages/Sandeep/Maskfront/resources/assets/images/card.png" title="Wifi">
																						</li>
																						@endif
																						@endif
																					</ul>
																				</div>
																				<div class="btn-sec text-right">
																					<a href="{{route('booking' ,$sallon['id'])}}" class="btn book-btn">Book now</a>
																				</div>
																			</div>
																		</div>
																	</div>
																</div><!-- LIST_ENDS_HERE -->
															</li>
															@endforeach
															@else
															<li class="product_box" style="width: 100%;text-align:center">
																<div class="well pr-well">
																	<div class="captions">
																			No Sallon Found. Please Search Again
																	</div>
																</div>
															</li>
															@endif
															@endif
															@if($type == 'products')
															<div class="col-sm-12">
																<div class="row">
																@if(!$products->isEmpty())
																	<div class="col-sm-3 col-md-2 left-filters">
																		@include('maskFront::includes.product_filter')
																	</div>
																@endif
																	<div class="@if(!$products->isEmpty()) col-sm-9 @else col-sm-12 @endif col-md-10">
																		<div class="row prod-item-list">
																			@if(!$products->isEmpty())
																			@foreach($products as $product)
																			<li class="product_box">
																				<div class="well pr-well">
																					@php($img = url('files/'.$product->image))
																					<a href="{{route('product' ,$product->id)}}" class="product_image_link">
																						<div class="thumb list-group-image item_thumb" style="background:url({{$img}})">
																						</div>
																					</a>
																					<div class="captions">
																						<div class="col-sm-12 pad-0 user-areas">
																							<div class="img-sec">
																								@php($img2 = url('files/'.$product->provider[0]->avatar))
																								<img src="{{$img2}}" class="img-circle user-img">
																								<h5>{!!San_Help::sanGetLang($product->name)!!}<span  class="small">{!!San_Help::sanGetLang($product->provider[0]->name)!!}</span></h5>

																							</div>
																							<div class="feed feed-outer">
																								<ul class="list-inline rating-list">
																									@for ($i = 1; $i <= 5; $i ++)
																									@php($selected = "")
																									@if (isset($product->rating) && $i <= $product->rating)
																									@php($selected = "checked")
																									@endif
																									<li><span class="fa fa-star {{$selected}}"></span></li>
																									@endfor
																								</ul>
																								<h5 class="item-price">{{San_Help::money($product->price)}} {!!$currency!!}</h5>
																							</div>
																						</div>
																						<!-- <h4 class="group inner list-group-item-heading">Brighten Up Your Hair Color</h4> -->
																						<p class="group inner list-group-item-text">{!!San_Help::sanLimited($product->description,50,route('product' ,$product->id))!!}</p>
																						<div class="col-sm-12 pad-0 pro-btmspice">
																							<div class="share pull-left text-right">
																								<ul class="list-inline share-list">
																									@if(Auth::check() && Auth::user()->fav_products)
																									@php($fav = unserialize(Auth::user()->fav_products))
																									@endif
																									<li><a href="#" data-type="product" data-id="{{$product->id}}" class="_add_favorite @if(isset($fav) && in_array($product->id,$fav))added @endif" id="add_favorite_{{$product->id}}"><i class="fa fa-heart"></i></a></li>
																									<li><a href="#"><i class="fa fa-share-alt"></i></a></li>
																								</ul>
																							</div>
																							<div class="btn-sec text-right">
																								<a href="{{route('product' ,$product->id)}}" class="btn book-btn">Buy now</a>
																							</div>
																						</div>
																					</div>
																				</div>
																			</li>
																			@endforeach
																			@else
																			<li class="product_box" style="width: 100%;text-align:center">
																				<div class="well pr-well">
																					<div class="captions">
																					<h2 style="text-align:center">We are loading products..</h2>
																					</div>
																				</div>
																			</li>
																			@endif
																		</div>
																	</div>
																</div>
															</div>
															@endif
														</ol>
														<!-- <div class="ajax-loading" style="text-align: center;display:none"><img src="{{ San_Help::san_Asset('images/loader.gif') }}" /></div> -->
													</div>

												</div>
											</div><!-- TAB_ENDS_HERE -->


										</div>

									</div>
								</div>
							</div>
						</div>
					</div>
					@if(app('request')->type == 'services' || (isset($type) && $type == 'services'))
					<div class="col-sm-6 right-map hidden-xs">
						<div id="googleMap_search" style="width:100%;height:807px;"></div>
					</div><!-- right-map -->
					@endif
				</div>
			</div>
		</div>
	</section>
	@push('scripts')
	<script src="{{ San_Help::san_Asset('js/jquery.mCustomScrollbar.concat.min.js') }}"></script>
	<!-- <script src="{{ San_Help::san_Asset('js/san_map.js') }}"></script> -->
	@endpush
	@section('javascript')
	<script type="text/javascript">
	@if($type == 'services')
	// var page = 1;
	// load_more(page);
	// $(window).scroll(function() { //detect page scroll
	// 		if($(window).scrollTop() + $(window).height() >= $(document).height()) { //if user scrolled from top to bottom of the page
	// 				page++; //page number increment
	// 				console.log(page);
	// 				load_more(page); //load content
	// 		}
	// });
	function load_more(page=4){
		$.ajax({
			headers: {
				'X-CSRF-TOKEN': $('#csrf_token').val()
			},
			type : "POST",
			url  : $('#ajax_url').val()+'/searchajax?page=' + page,
			data : {
				data  : '{!!serialize($_GET)!!}'
			},
			cache : false,
			beforeSend: function()
			{
					$('.ajax-loading').show();
			},
			success  : function(data) {
				if (!data.salon_lists) {
					$('.ajax-loading').html("No more records!");
					return;
				}
				$('.ajax-loading').hide();
			 $('#san_append_provider_list').html(data.salon_lists);
			}
		});
	}
	@endif
	$(function(){
		@if($type == 'services')
		 initialize();
		@endif
		$("._add_favorite").click(function(event) {
			event.preventDefault();
			var ajax_url = $( "#ajax_url" ).val()+'/favourite';
			var record_id = $(this).attr('data-id');
			var type = $(this).attr('data-type');
			var current_user_id = $("#user_id").val();
			if (current_user_id) {
				$.ajax({
					headers: {
						'X-CSRF-TOKEN': $('#csrf_token').val()
					},
					type:'POST',
					url: ajax_url,
					data: {
						record_id: record_id,
						type: type,
						user_id: current_user_id,
					},
					dataType: "json",
					beforeSend: function(){
					},
					success:function(data){
						if(data['res']==1){
							swal('',data['msg'], "success");
							$('#add_favorite_'+record_id).addClass('added');
						}
						if(data['res']==2){
							swal('',data['msg'], "success");
							$('#add_favorite_'+record_id).removeClass('added');
						}
					}
				});
			}else{
				$('#login-modal').modal('show');
			}
		});
	});
	</script>
	@endsection
	@endsection
