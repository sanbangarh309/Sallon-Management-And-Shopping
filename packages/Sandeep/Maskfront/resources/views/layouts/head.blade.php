 <header id="{{$page}}">
 	<input type="hidden" id="csrf_token" value="{{ csrf_token() }}">
 	<input type="hidden" id="user_id" value="@if(Auth::check()){{ Auth::user()->id }}@endif">
 	<input type="hidden" id="ajax_url" value="@if(isset($locale)){{ url('/'.$locale) }}@else {{url('/')}}@endif">
 	@if($page =='search')
 	<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css" />
 	<div class="page-nav navbar-fixed-top">
 		<div class="container-fluid">
 			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
 				<a class="navbar-brand" href="{{url($locale.'/')}}">
 					<h1><img class="img-responsive" src="{{ San_Help::san_Asset('images/logo.png') }}" alt="logo"></h1>
 				</a>
				<div class="cart-ob visible-xs">
					<a href="javascript:void(0)" style="background:none;" class="open_shpng_cart"><i class="fa fa-shopping-cart"></i><sup class="badge"></sup></a>
				</div>
 				<div class="page-search pull-left hidden-xs hidden-sm">
 					<div class="panel form-panel search-block">
 						<form id="search_form2" rel="" autocomplete="off" method="GET" action="{{route('search')}}">
 							<div class="col-sm-12 pad-0 inputt-grp">
 								<div class="row">
 									<div class="col-sm-6 pad-0">
 										<div class="form-group">
 											<div class="input-group">
 												<span class="input-helper-addon"><i class="fa fa-search"></i></span>
 												@if(isset($type) && $type == 'services')
 												<input type="hidden" name="type" value="services">
 												<input class="form-control" list="serv_cats" placeholder="{!!San_Help::sanLang('Search Services')!!}" name="sr"type="text" value="@if(isset($_GET['sr']) && ! is_numeric($_GET['sr'])){{$_GET['sr']}} @endif">
 												<datalist id="serv_cats">
 													@foreach($services as $service)
 													<option data-id="{{ $service->id }}" value="{!!San_Help::sanGetLang($service->name)!!}">
 														@endforeach
 													</datalist>
 													@else
 													<input type="hidden" name="type" value="products">
 													<input class="form-control" list="prod_cats" placeholder="{!!San_Help::sanLang('Search Products')!!}" name="pr"type="text" value="@if(isset($_GET['pr']) && ! is_numeric($_GET['pr'])){{$_GET['pr']}} @endif">
 													<datalist id="prod_cats">
 														@foreach($categories as $product)
 														<option data-id="{{ $product->id }}" value="{{ $product->name }}">
 															@endforeach
 														</datalist>
 														@endif
 													</div>
 												</div>
 											</div>
 											<div class="col-sm-4 pad-0">
 												<div class="form-group">
 													<div class="input-group">
 														<span class="input-group-addon"><i class="fa fa-map-marker"></i></span>
 														<input class="form-control" id="location_wr3" value="@if(isset($_GET['wr']) && trim($_GET['wr']) !='') {!!$_GET['wr']!!} @endif" placeholder="{!!San_Help::sanLang('Where')!!}" name="wr" type="text">
 													</div>
 												</div>
 											</div>

 											<div class="col-sm-2 pad-xs-0">
 												<div class="form-group">
 													<a href="javascript:void(0)" id="submit_search2" class="btn search-btn"><i class="fa fa-search"></i> {!!San_Help::sanLang('Search')!!}</a>
 												</div>
 											</div>
 										</div>
 									</div>
 								</form>
 							</div>
 						</div>
 					</div>
					<div id="navbar" class="navbar-collapse collapse page-navi">
						<ul class="nav navbar-nav navbar-right">
							@if(auth()->check())
							<li>
								<div class="custom-slect menu-atlogin">
									<div class="btn-group cst-group">
										<a data-toggle="dropdown" class="btn btn-default dropdown-toggle user-gutter" type="button" aria-expanded="true"><img src="{{url('files/'.Auth::user()->avatar)}}" class="img-circle user-img">
											<span class="pull-left" data-bind="label">{!!San_Help::sanGetLang(Auth::user()->name)!!}</span>&nbsp;<span class="fa fa-angle-down pull-right"></span>
										</a>

										<ul role="menu" class="dropdown-menu dropdown-menu-right">
											<li><a href="@if(Auth::user()->role_id == 2){{route('dashboard')}} @elseif(Auth::user()->role_id == 3) {{route('userdetail')}} @endif">{!!San_Help::sanLang('Dashboard')!!}</a></li>
											<li><a href="{{route('clogout')}}">{!!San_Help::sanLang('logout')!!}</a></li>
										</ul>
									</div>
								</div>
							</li>
							<li class="hidden-xs">
				                <div class="reward_points">
				                    <a href="{{route('userdetail')}}">
				                        <div class="_img_div">
				                            <img src="{{ San_Help::san_Asset('images/diamond.png') }}" class="diamond_img">
				                        </div>
				                        <div class="_points">
				                        {{Auth::user()->rewardpoint_balance}}
				                        </div>
				                    </a>
				                </div>
				            </li>
							@else
							<li><a class="cd-signin" href="{{route('business')}}">{!!San_Help::sanLang('Business')!!}</a></li>
							@if(!auth()->check())<li><a class="cd-signin" data-target="#customer_register" data-toggle="modal" role="button" href="#">{!!San_Help::sanLang('Sign Up')!!}</a></li>@endif
							@if(!auth()->check())<li><a class="cd-signin" data-target="#login-modal" data-toggle="modal" href="#" role="button">{!!San_Help::sanLang('Log In')!!} </a></li> @endif
							@endif
							<li>
								<div class="custom-slect">
									<div class="btn-group cst-group lang-select">
										<select id="lang_chooser">
											<option @if(isset($locale) && $locale == 'en') selected="selected" @endif value="{{str_replace('/'.$locale.'/','/en/',$previous_url)}}">English</option>
											<option @if(isset($locale) && $locale == 'ar') selected="selected" @endif value="{{str_replace('/'.$locale.'/','/ar/',$previous_url)}}">العربية</option>
										</select>
										<i class="fa fa-angle-down"></i>
									</div>
								</div>
							</li>
							<li>
								<div class="custom-slect">
									<div class="btn-group cst-group lang-select">
										<select id="currency_chooser">
											@foreach(config('money') as $name => $currency)
											<option @if(session()->get('currency') == $name) selected="selected" @endif value="{{url($locale.'/set_currency/'.$name)}}">{{$name.'('.$currency['symbol'].')'}}</option>
											@endforeach
										</select>
										<i class="fa fa-angle-down"></i>
									</div>
								</div>
							</li>
							<li class="hidden-xs shopping-cart"><a href="javascript:void(0)" class="open_shpng_cart" style="background:none;"><i class="fa fa-shopping-cart"></i><sup class="badge">@if(Auth::check()){{\TCG\Voyager\Models\Cart::where('user_id',Auth::user()->id)->count()}}@endif</sup></a></li>
						</ul>
					</div>
 				</div>
 			</div>
 			@else
 			<div class="navbar navbar-inverse main-menu">
 				<div class="container-fluid">
 					<div class="navbar-header">
 						<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
 							<span class="sr-only">Toggle navigation</span>
 							<span class="icon-bar"></span>
 							<span class="icon-bar"></span>
 							<span class="icon-bar"></span>
 						</button>
 						<a href="#" class="visible-xs srch-btn" type="button"><i class="fa fa-search"></i></a>
 						<a class="navbar-brand-tp" href="{{url($locale.'/')}}">
 							<h1><img class="img-responsive" src="{{ San_Help::san_Asset('images/logo.png') }}" alt="logo"></h1>
 						</a>
						<div class="cart-ob visible-xs">
							<a href="javascript:void(0)" style="background:none;" class="open_shpng_cart"><i class="fa fa-shopping-cart"></i><sup class="badge">@if(Auth::check()){{\TCG\Voyager\Models\Cart::where('user_id',Auth::user()->id)->count()}}@endif</sup></a>
						</div>
 					</div>
 					<div id="navbar" class="navbar-collapse collapse">
 						<ul class="list-inline navbar-right tp-nav">
 							<li><a class="cd-signin" href="{{route('business')}}">{!!San_Help::sanLang('Business')!!}</a></li>
 							@if(!auth()->check())<li><a class="cd-signin" data-target="#customer_register" data-toggle="modal" role="button" href="#">{!!San_Help::sanLang('Sign Up')!!}</a></li>@endif
 							@if(!auth()->check())<li><a class="cd-signin" data-target="#login-modal" data-toggle="modal" href="#" role="button">{!!San_Help::sanLang('Log In')!!} </a></li> @endif
 							@if(auth()->check())
 							<li>
 								<div class="custom-slect menu-atlogin">
 									<div class="btn-group cst-group">
 										<a data-toggle="dropdown" class="btn btn-default dropdown-toggle user-gutter" type="button" aria-expanded="true"><img src="{{url('files/'.Auth::user()->avatar)}}" class="img-circle user-img">
 											<span class="pull-left" data-bind="label">{!!San_Help::sanGetLang(Auth::user()->name)!!}</span>&nbsp;<span class="fa fa-angle-down pull-right"></span>
 										</a>

 										<ul role="menu" class="dropdown-menu dropdown-menu-right">
 											<li><a href="@if(Auth::user()->role_id == 2){{route('dashboard')}} @elseif(Auth::user()->role_id == 3) {{route('userdetail')}} @endif">{!!San_Help::sanLang('Dashboard')!!}</a></li>
 											<li><a href="{{route('clogout')}}">{!!San_Help::sanLang('logout')!!}</a></li>
 										</ul>
 									</div>
 								</div>
 							</li>
 							<li class="hidden-xs">
				                <div class="reward_points">
				                    <a href="{{route('userdetail')}}">
				                        <div class="_img_div">
				                            <img src="{{ San_Help::san_Asset('images/diamond.png') }}" class="diamond_img">
				                        </div>
				                        <div class="_points">
				                        {{Auth::user()->rewardpoint_balance}}
				                        </div>
				                    </a>
				                </div>
				            </li>
 							@endif
 							<li>
 								<div class="custom-slect">
 									<div class="btn-group cst-group lang-select">
 										<select id="lang_chooser">
											<option @if(isset($locale) && $locale == 'en') selected="selected" @endif value="{{str_replace('/'.$locale.'/','/en/',$previous_url)}}">English</option>
											<option @if(isset($locale) && $locale == 'ar') selected="selected" @endif value="{{str_replace('/'.$locale.'/','/ar/',$previous_url)}}">العربية</option>
										</select>
										<i class="fa fa-angle-down"></i>
 									</div>
 								</div>
 							</li>
 							<li>
								<div class="custom-slect">
									<div class="btn-group cst-group lang-select">
										<select id="currency_chooser">
											@foreach(config('money') as $name => $currency)
											<option @if(session()->get('currency') == $name) selected="selected" @endif value="{{url($locale.'/set_currency/'.$name)}}">{{$name.'('.$currency['symbol'].')'}}</option>
											@endforeach
										</select>
										<i class="fa fa-angle-down"></i>
									</div>
								</div>
							</li>
 							<li class="hidden-xs shopping-cart"><a href="javascript:void(0)" style="background:none;" class="open_shpng_cart"><i class="fa fa-shopping-cart"></i><sup class="badge">@if(Auth::check()){{\TCG\Voyager\Models\Cart::where('user_id',Auth::user()->id)->count()}}@endif</sup></a></li>
 						</ul>
 					</div>
 				</div>
 			</div>
 			@endif
 			@if($page =='dashboard')
 			<div class="profile-dash" style="background:url(@if(isset($provider->banner) && $provider->banner !=''){{url('files/'.$provider->banner)}} @else ../images/mobile_slider.jpg @endif)">
 				<a href="#" type="button" class="btn banner_update" data-toggle="modal" data-target="#update_banner_Modal" title="Update Banner Image"><i class="fa fa-edit"></i></a>
 				<div class="container">
 					<div class="col-sm-12 texd-center pad-0">
 						<a href="#" class="user-part text-center">
 							<div class="user-profileimg" style="background:url(@if(isset($provider->avatar) && $provider->avatar !=''){{url('files/'.$provider->avatar)}} @else {{ San_Help::san_Asset('images/user-img.jpg') }} @endif)">
 								<a href="#" type="button" class="btn img_update" data-toggle="modal" data-target="#update_image_Modal" title="Update Provider Image"><img src="{{ San_Help::san_Asset('images/camera2.png') }}"></a>
 							</div>
 							<h4>@if(isset($provider->name)){!!San_Help::sanGetLang($provider->name)!!}@endif<span class="small">@if(isset($provider->type)){!!San_Help::sanLang(config('maskfront.dropdown_fixed')[$provider->type])!!}@endif</span></h4>
 							<ul class="list-inline rating-list">
 								@for ($i = 1; $i <= 5; $i ++)
									@php($selected = "")
									@if (!$provider->reviews->isEmpty() && $i <= $provider->reviews->avg('rating'))
										@php($selected = "checked")
									@endif
									<li><span class="fa fa-star {{$selected}}"></span></li>
								@endfor
 							</ul>
 						</a>

 					</div>
 				</div>
 			</div>
 			@endif
 		@if($page =='home' || $page =='business')
 		@include('maskFront::includes.slider')
 		@endif
 		@if($page =='home')
 		<div class="main-nav">
 			<a href="javascript:void(0)" class="visible-xs catg-list">Category List <i class="fa fa-cog pull-right icon--1"></i></a>
 			<div class="btm-main-menu">
 				<ul class="nav navbar-nav navbar-left navigation-main">
          @if(isset($page_type) && $page_type == 'shop')
            @php($cats = \TCG\Voyager\Models\Category::has('getproducts')->whereNull('parent_id')->where('featured',1)->get())
            @foreach($cats as $slug => $fixed_cat)
   					<li class=""><a href="{{url($locale.'/search?type=products&wr=&pr='.$fixed_cat->slug)}}">{!!San_Help::sanGetLang($fixed_cat->name)!!}</a></li>
   					@endforeach
          @else
 					@foreach($fixed_cats as $slug => $fixed_cat)
 					<li class=""><a href="{{route($slug)}}">{!!San_Help::sanLang($fixed_cat)!!}</a></li>
 					@endforeach
          @endif
 				</ul>
 			</div>
 		</div><!--/#main-nav-->
 		@endif
 		@if($page =='booking')
 		<div class="profile-dash" style="background:url(@if(isset($provider->banner) && $provider->banner !=''){{url('files/'.$provider->banner)}} @else {{ San_Help::san_Asset('images/profile-bg.jpg') }} @endif">
 			<div class="container">
 				<div class="col-sm-12 texd-center pad-0">
 					<a href="#">
 						<img src="@if(isset($provider->avatar) && $provider->avatar !=''){{url('files/'.$provider->avatar)}} @else {{ San_Help::san_Asset('images/user-img.jpg') }} @endif" class="userprofile-img img-circle" alt="">
 						<h4>{!!San_Help::sanGetLang($provider->name)!!}<span class="small">@if(isset(config('maskfront.dropdown_fixed')[$provider->type])){{config('maskfront.dropdown_fixed')[$provider->type]}}@endif</span></h4>
 						<ul class="list-inline rating-list">
 							@for ($i = 1; $i <= 5; $i ++)
								@php($selected = "")
								@if (!$provider->reviews->isEmpty() && $i <= $provider->reviews->avg('rating'))
									@php($selected = "checked")
								@endif
								<li><span class="fa fa-star {{$selected}}"></span></li>
							@endfor
 						</ul>
 					</a>

 				</div>
 			</div>
 		</div>
 		@endif
    </header>
