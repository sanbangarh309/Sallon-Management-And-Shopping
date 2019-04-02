@php($page = 'product')
@extends('maskFront::layouts.app')
@section('main-content')
<link href="{{ San_Help::san_Asset('css/product.css') }}" rel="stylesheet">
<link href="{{ San_Help::san_Asset('css/rating.css') }}" rel="stylesheet">
<section id="single-pro" class="single-procontent">
	<div class="container-fluid singlepro-fluid">
		<div class="col-sm-12 featured-serv pad-xs-0">
			<div class="row">
				<div class="col-sm-4">
					<div id="carousel" class="carousel slide carousel-fade product-slider1">
						@if(count($product->product_images) > 1)
						<ol class="carousel-indicators">
							@if(isset($product->product_images))
							@foreach($product->product_images as $key => $product_image)
							<li data-target="#carousel" data-slide-to="{{$key}}" class="myCarousel-target @if($key == 0)active @endif"></li>
							@endforeach
							@endif
						</ol>
						@endif
						<!-- Carousel items -->
						<div class="carousel-inner">
							<div class="item carousel-item active">
								<figure style="background:url({{url('files/'.$product->image)}})" class="productv-1"></figure>
							</div>
							@if(isset($product->product_images))
							@foreach($product->product_images as $key => $product_image)
							<div class="item carousel-item">
								<figure style="background:url({{url('files/'.$product_image->filename)}})" class="productv-1"></figure>
							</div>
							@endforeach
							@endif
						</div>
						@if(count($product->product_images) > 1)
						<!-- Carousel nav -->
						<a class="carousel-control left" href="#carousel" data-slide="prev">‹</a>
						<a class="carousel-control right" href="#carousel" data-slide="next">›</a>
						@endif
					</div>
				</div>
				<div class="col-sm-5 right-prods">
				
					<form role="form" action="{{url($locale.'/postbook/'.$product->id.'?tab=summary')}}" method="POST" id="product_form">
						{{ csrf_field() }}
						<input type="hidden" name="product_id" value="{{$product->id}}">
						<input type="hidden" name="price" value="{{$product->price}}">
						<input type="hidden" name="user_id" value="@if(Auth::user()){{Auth::user()->id}}@endif">
						<h5>@if(!$product->category->isEmpty()){!!San_Help::sanGetLang($product->category[0]->name,$locale)!!}@endif</h5>
						<h1 class="product-title">{!!San_Help::sanGetLang($product->name,$locale)!!}</h1>
						<!-- <p class="pro-content"> {!!$product->description!!}</p> -->
						<div class="pro-author">
							<ul class="list-inline brand-seller">
								<li>
									<a href="@if(!$product->provider->isEmpty()){{url($locale.'/booking/'.$product->provider[0]->id.'?tab=profile')}}@endif" class="seller-image"><img src="@if(!$product->provider->isEmpty()){{url('files/'.$product->provider[0]->avatar)}}@endif" class="b-img"></a>
								</li>
								<li>by</li>
								<li>
									<a href="@if(!$product->provider->isEmpty()){{url($locale.'/booking/'.$product->provider[0]->id.'?tab=profile')}}@endif" class="seller-link">@if(!$product->provider->isEmpty() && isset($product->provider[0])){!!San_Help::sanGetLang($product->provider[0]->name,$locale)!!}@endif</a>
								</li>
							</ul>
						</div>
						
						<div class="ratings-box">
							<ul class="list-inline rate-feedback">
								@for ($i = 1; $i <= 5; $i ++)
								@php($selected = "")
								@if (isset($product->rating) && $i <= $product->rating)
								@php($selected = "checked")
								@endif
								<li><span class="fa fa-star {{$selected}}"></span></li>
								@endfor
							</ul>
						</div>
						<div class="price-box form-group">
							<label class="control-label">{!!San_Help::sanLang('Price')!!}: <b><span class="price-val">{{San_Help::money($product->price)}} {!!$currency!!}</span></b></label>
						</div>
						<div class="price-box qty form-group">
							<label class="control-label">{!!San_Help::sanLang('Quantity')!!}: </label>
							<div class="qty-inputs">
								<div class="input-group">
									<span class="input-group-btn">
										<button type="button" class="btn btn-default btn-number" data-type="minus" data-field="qty">
											<span class="fa fa-minus"></span>
										</button>
									</span>
									<input type="text" name="qty" class="form-control input-number" id="get_qty" value="@if($product->qty > 0)1 @else 0 @endif" min="1" max="{{$product->qty}}">
									<span class="input-group-btn">
										<button type="button" class="btn btn-default btn-number" data-type="plus" data-field="qty">
											<span class="fa fa-plus"></span>
										</button>
									</span>
									@if($product->qty < 5)
									<div class="alert alert-danger warning_btn" role="alert" style="width: -moz-max-content;margin-left: 31px;">
										<i class="fa fa-warning" style="font-size:36px;color:red"></i>
										Products is Less Than {{$product->qty}}!
									</div>
									@endif
								</div>
								<!-- <div class="input-group">
								<span class="input-group-btn">
								<i class="fa fa-warning" style="font-size:36px;color:red"></i>
								<span class="fa fa-warning">Products is Less Than Five!</span>
							</span>
						</div> -->
					</div>
				</div>
				<div class="price-box qty form-group">
					<label class="control-label">{!!San_Help::sanLang('Color')!!}: </label>
					<div class="color-bx">
						<ul class="list-inline swatch-list" id="selecte_colors">
							@if(isset($product->color) && $product->color !='')
							@php($colors = explode(',',$product->color))
							@foreach($colors as $color)
							<li class="{{strtolower($color)}}"><input class="{{strtolower($color)}}" name="color" id="{{strtolower($color)}}" type="radio"><span></span></li>
							@endforeach
							@endif
						</ul>
					</div>
				</div>
				<div class="btn-set form-group">
					<ul class="list-inline btn-flex">
						<li><a href="javascript:void(0)" id="add_to_cart" class="btn add-cart">{!!San_Help::sanLang('Add to cart')!!}</a></li>
						<li><a href="javascript:void(0)" id="buy_product" class="btn btn-buy">{!!San_Help::sanLang('Buy')!!}</a></li>
					</ul>
				</div>
			</form>
		</div>
	</div>
	<div class="col-sm-12 pad-0"><!---PRODUCT_INFO-STARTS-->
		<div class="col-md-12 product-info p-xs-0">
			<ul class="nav nav-tabs">
				<li class="active">
					<a class="" data-toggle="tab" href="#service-one">{!!San_Help::sanLang('MORE INFO')!!}</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" data-toggle="tab" href="#service-two">{!!San_Help::sanLang('REVIEWS')!!}</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" data-toggle="tab" href="#service-three">{!!San_Help::sanLang('SHIPPING POCLICY')!!}</a>
				</li>
			</ul>
			<div id="myTabContent" class="tab-content">
				<div class="tab-pane fade in active" id="service-one">
					<div class="product-info">
						{!!$product->description!!}
					</div>
				</div>
				<div class="tab-pane fade" id="service-two">
					<div class="col-sm-12 p-0">
						<div class="spr-container">
							<div class="spr-header">
								<h2 class="spr-header-title">{!!San_Help::sanLang('Customer Reviews')!!}</h2>
								<div class="spr-summary">
									<div id="review_list">
										@include('maskFront::includes.review_list')
									</div>
									<span class="spr-summary-actions">
										<a href="javascript:void(0)" class="spr-summary-actions-newreview" @if(!Auth::check()) data-target="#login-modal" data-toggle="modal" @endif><h3 class="spr-form-title">{!!San_Help::sanLang('Write a review')!!}</h3></a>
									</span>
								</div>
							</div>
							@if(Auth::check())
							@include('maskFront::includes.review')
							@else
							@endif
						</div>
					</div>

				</div>
				<div class="tab-pane fade" id="service-three">
					<div class="col-sm-12 p-0">
						<div class="description__attribute">
							<h2>Shipping policy for our store</h2>
							<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate</p>
							<ul>
								<li>1-2 business days (Typically by end of day)</li>
								<li><a href="#">30 days money back guaranty</a></li>
								<li>24/7 live support</li>
								<li>odio dignissim qui blandit praesent</li>
								<li>luptatum zzril delenit augue duis dolore</li>
								<li>te feugait nulla facilisi.</li>
							</ul>
							<p>Nam liber tempor cum soluta nobis eleifend option congue nihil imperdiet doming id quod mazim placerat facer possim assum. Typi non habent claritatem insitam; est usus legentis in iis qui facit eorum</p>
							<p>claritatem. Investigationes demonstraverunt lectores legere me lius quod ii legunt saepius. Claritas est etiam processus dynamicus, qui sequitur mutationem consuetudium lectorum. Mirum est notare quam littera gothica, quam nunc putamus parum claram, anteposuerit litterarum formas humanitatis per</p>
							<p>seacula quarta decima et quinta decima. Eodem modo typi, qui nunc nobis videntur parum clari, fiant sollemnes in futurum.</p>
						</div>
					</div>
				</div>
			</div>

		</div>
	</div>
</div>
<div class="related-pro">
	<h2>{!!San_Help::sanLang('Same Products From Seller')!!}</h2>
	<div class="col-sm-12 pro-slide">
		<div id="demos">
			<div class="row">
				<div class="large-12 columns">
					<div class="owl-theme">
						<!-- owl-carousel  -->
						<!-- Listing Stasts -->

						@if(isset($product->related_products))
						@php($rel_products = $product->related_products->where('id', '!=', $product->id))
						@foreach($rel_products as $rel_product)
						<div class="item product_box col-sm-3">
							<div class="well pr-well">
								@if(!$rel_product->image)
									@php($img = url('files/not_available.jpg'))
								@else
									@php($img = url('files/'.$rel_product->image))
								@endif
								<div class="thumb list-group-image" style="background:url({{$img}})">
								</div>
								<div class="captions">
									<div class="col-sm-12 pad-0 user-areas">
										<div class="img-sec">
											<img src="@if(!$product->provider->isEmpty()){{url('files/'.$product->provider[0]->avatar)}}@endif" class="img-circle user-img">
											<h5>{{$rel_product->name}}<span  class="small">{{$rel_product->name}}</span></h5>
											<h5 class="item-price">{{San_Help::money($rel_product->price)}} {!!$currency!!}</h5>
										</div>
										<div class="feed">
											<ul class="list-inline rating-list">
												<li><i class="fa fa-star"></i></li>
												<li><i class="fa fa-star"></i></li>
												<li><i class="fa fa-star"></i></li>
												<li><i class="fa fa-star"></i></li>
												<li><i class="fa fa-star"></i></li>
											</ul>
										</div>
									</div>
									<!-- <h4 class="group inner list-group-item-heading">Brighten Up Your Hair Color</h4> -->
									<p class="group inner list-group-item-text">{!!San_Help::sanLimited($rel_product->description,50,route('product' ,$rel_product->id))!!}</p>
									<div class="col-sm-12 pad-0">
										<div class="share pull-left text-right">
											<ul class="list-inline share-list">
												<li><a href="#"><i class="fa fa-heart"></i></a></li>
												<li><a href="#"><i class="fa fa-share-alt"></i></a></li>
											</ul>
										</div>
										<div class="btn-sec text-right">
											<a href="{{route('product' ,$rel_product->id)}}" class="btn book-btn">{!!San_Help::sanLang('Buy now')!!}</a>
										</div>
									</div>
								</div>
							</div>
						</div>
						@endforeach
						@endif
						<!-- Listing end -->
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
<script>
$('document').ready(function () {
	$('#buy_product').on('click', function(){
		if($('#selecte_colors').find('input[type="radio"]:checked').length > 0){
			var color = $('#selecte_colors').find('input[type="radio"]:checked').attr('id');
			var qty = $('#get_qty').val();
			if ($('#color_name').hasClass('color_name')) {
				$('#color_name').val(color);
			}else{
				var el = '<input type="hidden" id="color_name" class="color_name" name="color_name" value="'+color+'"></input>';
				$('#product_form').append(el);
			}
			if (color && qty > 0) {
				var chk = addToCart(1);
				if (chk =='already') {
					$('#cart_form').submit();
					return false;
				}
				var userid = "@if(Auth::user()){{Auth::user()->id}}@endif";
				if (!userid) {
					$('#login-modal').modal('show');
					return false;
				}
				setTimeout(function(){ $('#product_form').submit(); }, 2000);
			}
		}else{
			swal("","Please Select Color",'warning');
		}
	});
	$('#add_to_cart').on('click', addToCart);
});

function addToCart(buy){
	var userid = "@if(Auth::user()){{Auth::user()->id}}@endif";
	if (!userid) {
		$('#login-modal').modal('show');
		return false;
	}
	if($('#selecte_colors').find('input[type="radio"]:checked').length > 0){
		var color = $('#selecte_colors').find('input[type="radio"]:checked').attr('id');
		var qty = $('#get_qty').val();
		if ($('#color_name').hasClass('color_name')) {
			$('#color_name').val(color);
		}else{
			var el = '<input type="hidden" id="color_name" class="color_name" name="color_name" value="'+color+'"></input>';
			$('#product_form').append(el);
		}
		if (color && qty > 0) {
			var formData = $('#product_form').serialize();
			$.ajax({
				headers: {
					'X-CSRF-TOKEN': $('#csrf_token').val()
				},
				url: "{{url($locale.'/add_to_cart')}}",
				data: formData,
				method: 'POST',
				success: function (data) {
					if (typeof data == 'string' || data instanceof String) {
						if (buy == 1) {
							return 'already';
						}else{
							swal("",data,"warning");
							return false;
						}
					}else{
						var html = '';
						var count = 0;
						var total_amnt = 0;
						let currency = '{!!session()->get('currency')!!}'
						$.each(data, function( index, value ) {
							// var fin_price = value.price;
							count = parseInt(count)+1;
							total_amnt = parseFloat(total_amnt)+parseFloat(value.price);
							@if(session()->get('currency'))
							let currencydata = {!! json_encode(config('money')[session()->get('currency')]) !!}
							// fin_price = "{!!session()->get('currency')!!} "+(currencydata.convert_amnt * parseFloat(fin_price)).toPrecision(3);
							total_amnt = (currencydata.convert_amnt * parseFloat(total_amnt));
							total_amnt = floorFigure(total_amnt,2);
							console.log(currencydata);
							console.log(value.price.toPrecision(3));
							console.log(total_amnt);
							@endif
							
							html +='<tr>'+
							'<td height="30"><h5 class="item-name">'+value.product.name+'</h5></td>'+
							'<td height="30"><h4 class="ui header" style="color: white;">'+currency+' '+floorFigure((currencydata.convert_amnt * parseFloat(value.price)),2)+'</h4></td>'+
							'<td><a href="javascript:void(0);" data-id="'+value.id+'" class="ui icon button action-remove-from-cart">&times;</a></td>'+
							'</tr>';
						});
						$('li.shopping-cart .badge').text(count);
						$('#shopping-cart #append_cart_data').html(html);
						$('#shopping-cart b#cart_total_amnt').text(currency+' '+total_amnt);
						$('#shopping-cart').addClass('open-menu');
						$('.checkout_san_btn').prop('disabled', false);
					}
				},
				error: function(data){alert('error'); }
			});
		}else{
			swal("","{!!San_Help::sanLang('Product Not Available')!!}",'warning');
		}
	}else{
		swal("","{!!San_Help::sanLang('Please Select Color')!!}",'warning');
	}
}

function floorFigure(figure, decimals){
    if (!decimals) decimals = 2;
    var d = Math.pow(10,decimals);
    return (parseInt(figure*d)/d).toFixed(decimals);
};

function submitReview(){
	$.ajax({
		headers: {
			'X-CSRF-TOKEN': $('#csrf_token').val()
		},
		type : "POST",
		url  : $('#ajax_url').val()+'/addreview',
		data : $('#new-review-form').serialize(),
		cache : false,
		success  : function(data) {
			$('#service-two #review_list').html(data.reviews_html);
		}
	});
}
</script>
<script>
$(".spr-summary-actions-newreview").click(function(){
	$(".form-btm").slideToggle();
});
</script>

@stop
