 <header id="<?php echo e($page); ?>">
 	<input type="hidden" id="csrf_token" value="<?php echo e(csrf_token()); ?>">
 	<input type="hidden" id="user_id" value="<?php if(Auth::check()): ?><?php echo e(Auth::user()->id); ?><?php endif; ?>">
 	<input type="hidden" id="ajax_url" value="<?php if(isset($locale)): ?><?php echo e(url('/'.$locale)); ?><?php else: ?> <?php echo e(url('/')); ?><?php endif; ?>">
 	<?php if($page =='search'): ?>
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
 				<a class="navbar-brand" href="<?php echo e(url($locale.'/')); ?>">
 					<h1><img class="img-responsive" src="<?php echo e(San_Help::san_Asset('images/logo.png')); ?>" alt="logo"></h1>
 				</a>
				<div class="cart-ob visible-xs">
					<a href="javascript:void(0)" style="background:none;" class="open_shpng_cart"><i class="fa fa-shopping-cart"></i><sup class="badge"></sup></a>
				</div>
 				<div class="page-search pull-left hidden-xs hidden-sm">
 					<div class="panel form-panel search-block">
 						<form id="search_form2" rel="" autocomplete="off" method="GET" action="<?php echo e(route('search')); ?>">
 							<div class="col-sm-12 pad-0 inputt-grp">
 								<div class="row">
 									<div class="col-sm-6 pad-0">
 										<div class="form-group">
 											<div class="input-group">
 												<span class="input-helper-addon"><i class="fa fa-search"></i></span>
 												<?php if(isset($type) && $type == 'services'): ?>
 												<input type="hidden" name="type" value="services">
 												<input class="form-control" list="serv_cats" placeholder="<?php echo San_Help::sanLang('Search Services'); ?>" name="sr"type="text" value="<?php if(isset($_GET['sr']) && ! is_numeric($_GET['sr'])): ?><?php echo e($_GET['sr']); ?> <?php endif; ?>">
 												<datalist id="serv_cats">
 													<?php $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
 													<option data-id="<?php echo e($service->id); ?>" value="<?php echo San_Help::sanGetLang($service->name); ?>">
 														<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
 													</datalist>
 													<?php else: ?>
 													<input type="hidden" name="type" value="products">
 													<input class="form-control" list="prod_cats" placeholder="<?php echo San_Help::sanLang('Search Products'); ?>" name="pr"type="text" value="<?php if(isset($_GET['pr']) && ! is_numeric($_GET['pr'])): ?><?php echo e($_GET['pr']); ?> <?php endif; ?>">
 													<datalist id="prod_cats">
 														<?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
 														<option data-id="<?php echo e($product->id); ?>" value="<?php echo e($product->name); ?>">
 															<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
 														</datalist>
 														<?php endif; ?>
 													</div>
 												</div>
 											</div>
 											<div class="col-sm-4 pad-0">
 												<div class="form-group">
 													<div class="input-group">
 														<span class="input-group-addon"><i class="fa fa-map-marker"></i></span>
 														<input class="form-control" id="location_wr3" value="<?php if(isset($_GET['wr']) && trim($_GET['wr']) !=''): ?> <?php echo $_GET['wr']; ?> <?php endif; ?>" placeholder="<?php echo San_Help::sanLang('Where'); ?>" name="wr" type="text">
 													</div>
 												</div>
 											</div>

 											<div class="col-sm-2 pad-xs-0">
 												<div class="form-group">
 													<a href="javascript:void(0)" id="submit_search2" class="btn search-btn"><i class="fa fa-search"></i> <?php echo San_Help::sanLang('Search'); ?></a>
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
							<?php if(auth()->check()): ?>
							<li>
								<div class="custom-slect menu-atlogin">
									<div class="btn-group cst-group">
										<a data-toggle="dropdown" class="btn btn-default dropdown-toggle user-gutter" type="button" aria-expanded="true"><img src="<?php echo e(url('files/'.Auth::user()->avatar)); ?>" class="img-circle user-img">
											<span class="pull-left" data-bind="label"><?php echo San_Help::sanGetLang(Auth::user()->name); ?></span>&nbsp;<span class="fa fa-angle-down pull-right"></span>
										</a>

										<ul role="menu" class="dropdown-menu dropdown-menu-right">
											<li><a href="<?php if(Auth::user()->role_id == 2): ?><?php echo e(route('dashboard')); ?> <?php elseif(Auth::user()->role_id == 3): ?> <?php echo e(route('userdetail')); ?> <?php endif; ?>"><?php echo San_Help::sanLang('Dashboard'); ?></a></li>
											<li><a href="<?php echo e(route('clogout')); ?>"><?php echo San_Help::sanLang('logout'); ?></a></li>
										</ul>
									</div>
								</div>
							</li>
							<li class="hidden-xs">
				                <div class="reward_points">
				                    <a href="<?php echo e(route('userdetail')); ?>">
				                        <div class="_img_div">
				                            <img src="<?php echo e(San_Help::san_Asset('images/diamond.png')); ?>" class="diamond_img">
				                        </div>
				                        <div class="_points">
				                        <?php echo e(Auth::user()->rewardpoint_balance); ?>

				                        </div>
				                    </a>
				                </div>
				            </li>
							<?php else: ?>
							<li><a class="cd-signin" href="<?php echo e(route('business')); ?>"><?php echo San_Help::sanLang('Business'); ?></a></li>
							<?php if(!auth()->check()): ?><li><a class="cd-signin" data-target="#customer_register" data-toggle="modal" role="button" href="#"><?php echo San_Help::sanLang('Sign Up'); ?></a></li><?php endif; ?>
							<?php if(!auth()->check()): ?><li><a class="cd-signin" data-target="#login-modal" data-toggle="modal" href="#" role="button"><?php echo San_Help::sanLang('Log In'); ?> </a></li> <?php endif; ?>
							<?php endif; ?>
							<li>
								<div class="custom-slect">
									<div class="btn-group cst-group lang-select">
										<select id="lang_chooser">
											<option <?php if(isset($locale) && $locale == 'en'): ?> selected="selected" <?php endif; ?> value="<?php echo e(str_replace('/'.$locale.'/','/en/',$previous_url)); ?>">English</option>
											<option <?php if(isset($locale) && $locale == 'ar'): ?> selected="selected" <?php endif; ?> value="<?php echo e(str_replace('/'.$locale.'/','/ar/',$previous_url)); ?>">العربية</option>
										</select>
										<i class="fa fa-angle-down"></i>
									</div>
								</div>
							</li>
							<li>
								<div class="custom-slect">
									<div class="btn-group cst-group lang-select">
										<select id="currency_chooser">
											<?php $__currentLoopData = config('money'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $name => $currency): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
											<option <?php if(session()->get('currency') == $name): ?> selected="selected" <?php endif; ?> value="<?php echo e(url($locale.'/set_currency/'.$name)); ?>"><?php echo e($name.'('.$currency['symbol'].')'); ?></option>
											<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
										</select>
										<i class="fa fa-angle-down"></i>
									</div>
								</div>
							</li>
							<li class="hidden-xs shopping-cart"><a href="javascript:void(0)" class="open_shpng_cart" style="background:none;"><i class="fa fa-shopping-cart"></i><sup class="badge"><?php if(Auth::check()): ?><?php echo e(\TCG\Voyager\Models\Cart::where('user_id',Auth::user()->id)->count()); ?><?php endif; ?></sup></a></li>
						</ul>
					</div>
 				</div>
 			</div>
 			<?php else: ?>
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
 						<a class="navbar-brand-tp" href="<?php echo e(url($locale.'/')); ?>">
 							<h1><img class="img-responsive" src="<?php echo e(San_Help::san_Asset('images/logo.png')); ?>" alt="logo"></h1>
 						</a>
						<div class="cart-ob visible-xs">
							<a href="javascript:void(0)" style="background:none;" class="open_shpng_cart"><i class="fa fa-shopping-cart"></i><sup class="badge"><?php if(Auth::check()): ?><?php echo e(\TCG\Voyager\Models\Cart::where('user_id',Auth::user()->id)->count()); ?><?php endif; ?></sup></a>
						</div>
 					</div>
 					<div id="navbar" class="navbar-collapse collapse">
 						<ul class="list-inline navbar-right tp-nav">
 							<li><a class="cd-signin" href="<?php echo e(route('business')); ?>"><?php echo San_Help::sanLang('Business'); ?></a></li>
 							<?php if(!auth()->check()): ?><li><a class="cd-signin" data-target="#customer_register" data-toggle="modal" role="button" href="#"><?php echo San_Help::sanLang('Sign Up'); ?></a></li><?php endif; ?>
 							<?php if(!auth()->check()): ?><li><a class="cd-signin" data-target="#login-modal" data-toggle="modal" href="#" role="button"><?php echo San_Help::sanLang('Log In'); ?> </a></li> <?php endif; ?>
 							<?php if(auth()->check()): ?>
 							<li>
 								<div class="custom-slect menu-atlogin">
 									<div class="btn-group cst-group">
 										<a data-toggle="dropdown" class="btn btn-default dropdown-toggle user-gutter" type="button" aria-expanded="true"><img src="<?php echo e(url('files/'.Auth::user()->avatar)); ?>" class="img-circle user-img">
 											<span class="pull-left" data-bind="label"><?php echo San_Help::sanGetLang(Auth::user()->name); ?></span>&nbsp;<span class="fa fa-angle-down pull-right"></span>
 										</a>

 										<ul role="menu" class="dropdown-menu dropdown-menu-right">
 											<li><a href="<?php if(Auth::user()->role_id == 2): ?><?php echo e(route('dashboard')); ?> <?php elseif(Auth::user()->role_id == 3): ?> <?php echo e(route('userdetail')); ?> <?php endif; ?>"><?php echo San_Help::sanLang('Dashboard'); ?></a></li>
 											<li><a href="<?php echo e(route('clogout')); ?>"><?php echo San_Help::sanLang('logout'); ?></a></li>
 										</ul>
 									</div>
 								</div>
 							</li>
 							<li class="hidden-xs">
				                <div class="reward_points">
				                    <a href="<?php echo e(route('userdetail')); ?>">
				                        <div class="_img_div">
				                            <img src="<?php echo e(San_Help::san_Asset('images/diamond.png')); ?>" class="diamond_img">
				                        </div>
				                        <div class="_points">
				                        <?php echo e(Auth::user()->rewardpoint_balance); ?>

				                        </div>
				                    </a>
				                </div>
				            </li>
 							<?php endif; ?>
 							<li>
 								<div class="custom-slect">
 									<div class="btn-group cst-group lang-select">
 										<select id="lang_chooser">
											<option <?php if(isset($locale) && $locale == 'en'): ?> selected="selected" <?php endif; ?> value="<?php echo e(str_replace('/'.$locale.'/','/en/',$previous_url)); ?>">English</option>
											<option <?php if(isset($locale) && $locale == 'ar'): ?> selected="selected" <?php endif; ?> value="<?php echo e(str_replace('/'.$locale.'/','/ar/',$previous_url)); ?>">العربية</option>
										</select>
										<i class="fa fa-angle-down"></i>
 									</div>
 								</div>
 							</li>
 							<li>
								<div class="custom-slect">
									<div class="btn-group cst-group lang-select">
										<select id="currency_chooser">
											<?php $__currentLoopData = config('money'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $name => $currency): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
											<option <?php if(session()->get('currency') == $name): ?> selected="selected" <?php endif; ?> value="<?php echo e(url($locale.'/set_currency/'.$name)); ?>"><?php echo e($name.'('.$currency['symbol'].')'); ?></option>
											<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
										</select>
										<i class="fa fa-angle-down"></i>
									</div>
								</div>
							</li>
 							<li class="hidden-xs shopping-cart"><a href="javascript:void(0)" style="background:none;" class="open_shpng_cart"><i class="fa fa-shopping-cart"></i><sup class="badge"><?php if(Auth::check()): ?><?php echo e(\TCG\Voyager\Models\Cart::where('user_id',Auth::user()->id)->count()); ?><?php endif; ?></sup></a></li>
 						</ul>
 					</div>
 				</div>
 			</div>
 			<?php endif; ?>
 			<?php if($page =='dashboard'): ?>
 			<div class="profile-dash" style="background:url(<?php if(isset($provider->banner) && $provider->banner !=''): ?><?php echo e(url('files/'.$provider->banner)); ?> <?php else: ?> https://mask-app.com/wp-content/uploads/2018/04/20130305-174142-1.jpg <?php endif; ?>)">
 				<a href="#" type="button" class="btn banner_update" data-toggle="modal" data-target="#update_banner_Modal" title="Update Banner Image"><i class="fa fa-edit"></i></a>
 				<div class="container">
 					<div class="col-sm-12 texd-center pad-0">
 						<a href="#" class="user-part text-center">
 							<div class="user-profileimg" style="background:url(<?php if(isset($provider->avatar) && $provider->avatar !=''): ?><?php echo e(url('files/'.$provider->avatar)); ?> <?php else: ?> <?php echo e(San_Help::san_Asset('images/user-img.jpg')); ?> <?php endif; ?>)">
 								<a href="#" type="button" class="btn img_update" data-toggle="modal" data-target="#update_image_Modal" title="Update Provider Image"><img src="<?php echo e(San_Help::san_Asset('images/camera2.png')); ?>"></a>
 							</div>
 							<h4><?php if(isset($provider->name)): ?><?php echo San_Help::sanGetLang($provider->name); ?><?php endif; ?><span class="small"><?php if(isset($provider->type)): ?><?php echo San_Help::sanLang(config('maskfront.dropdown_fixed')[$provider->type]); ?><?php endif; ?></span></h4>
 							<ul class="list-inline rating-list">
 								<?php for($i = 1; $i <= 5; $i ++): ?>
									<?php ($selected = ""); ?>
									<?php if(!$provider->reviews->isEmpty() && $i <= $provider->reviews->avg('rating')): ?>
										<?php ($selected = "checked"); ?>
									<?php endif; ?>
									<li><span class="fa fa-star <?php echo e($selected); ?>"></span></li>
								<?php endfor; ?>
 							</ul>
 						</a>

 					</div>
 				</div>
 			</div>
 			<?php endif; ?>
 		<?php if($page =='home' || $page =='business'): ?>
 		<?php echo $__env->make('maskFront::includes.slider', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
 		<?php endif; ?>
 		<?php if($page =='home'): ?>
 		<div class="main-nav">
 			<a href="javascript:void(0)" class="visible-xs catg-list">Category List <i class="fa fa-cog pull-right icon--1"></i></a>
 			<div class="btm-main-menu">
 				<ul class="nav navbar-nav navbar-left navigation-main">
          <?php if(isset($page_type) && $page_type == 'shop'): ?>
            <?php ($cats = \TCG\Voyager\Models\Category::has('getproducts')->whereNull('parent_id')->where('featured',1)->get()); ?>
            <?php $__currentLoopData = $cats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $slug => $fixed_cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
   					<li class=""><a href="<?php echo e(url($locale.'/search?type=products&wr=&pr='.$fixed_cat->slug)); ?>"><?php echo San_Help::sanGetLang($fixed_cat->name); ?></a></li>
   					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          <?php else: ?>
 					<?php $__currentLoopData = $fixed_cats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $slug => $fixed_cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
 					<li class=""><a href="<?php echo e(route($slug)); ?>"><?php echo San_Help::sanLang($fixed_cat); ?></a></li>
 					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          <?php endif; ?>
 				</ul>
 			</div>
 		</div><!--/#main-nav-->
 		<?php endif; ?>
 		<?php if($page =='booking'): ?>
 		<div class="profile-dash" style="background:url(<?php if(isset($provider->banner) && $provider->banner !=''): ?><?php echo e(url('files/'.$provider->banner)); ?> <?php else: ?> <?php echo e(San_Help::san_Asset('images/profile-bg.jpg')); ?> <?php endif; ?>">
 			<div class="container">
 				<div class="col-sm-12 texd-center pad-0">
 					<a href="#">
 						<img src="<?php if(isset($provider->avatar) && $provider->avatar !=''): ?><?php echo e(url('files/'.$provider->avatar)); ?> <?php else: ?> <?php echo e(San_Help::san_Asset('images/user-img.jpg')); ?> <?php endif; ?>" class="userprofile-img img-circle" alt="">
 						<h4><?php echo San_Help::sanGetLang($provider->name); ?><span class="small"><?php if(isset(config('maskfront.dropdown_fixed')[$provider->type])): ?><?php echo e(config('maskfront.dropdown_fixed')[$provider->type]); ?><?php endif; ?></span></h4>
 						<ul class="list-inline rating-list">
 							<?php for($i = 1; $i <= 5; $i ++): ?>
								<?php ($selected = ""); ?>
								<?php if(!$provider->reviews->isEmpty() && $i <= $provider->reviews->avg('rating')): ?>
									<?php ($selected = "checked"); ?>
								<?php endif; ?>
								<li><span class="fa fa-star <?php echo e($selected); ?>"></span></li>
							<?php endfor; ?>
 						</ul>
 					</a>

 				</div>
 			</div>
 		</div>
 		<?php endif; ?>
    </header>
