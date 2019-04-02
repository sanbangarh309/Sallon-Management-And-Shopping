<?php
$locale = app('request')->segment(1);
$this->app->setLocale($locale);

Route::group(['namespace' => 'Sandeep\Maskfront\Controllers','prefix' => $locale,'middleware' => ['mask_middle','web']], function()
{
	// Fetch wp data
	Route::get('fetch_users', 'FetchData@fetchUsers');
	Route::get('fetch_services', 'FetchData@fetchServices');
	Route::get('fetch_asssss', 'FetchData@addTeam');
	Route::get('update_users', 'FetchData@updateIds');
	/* Front End Routes */
	Route::get('localization/{lang?}','LanguageController@index');
	Route::get('/' , 'MaskController@index');
	Route::get('/book' , 'MaskController@index')->name('book');
	Route::get('/shop' , 'MaskController@index')->name('shop');
	Route::get('/business' , 'MaskController@business')->name('business');
	Route::get('/dashboard' , 'MaskController@dashboard')->name('dashboard');
	Route::get('/dashboard/team/{id}' , 'MaskController@team')->name('team');
	Route::get('/search' , 'MaskController@Search')->name('search');
	Route::post('/searchajax' , 'MaskController@SearchAjax')->name('searchajax');
	Route::get('/team' , 'MaskController@san_Team')->name('team');
	Route::post('/addteam' , 'MaskController@addTeam')->name('addteam');
	Route::post('/add_product' , 'MaskController@add_Product')->name('add_product');
	Route::post('/add_service' , 'MaskController@add_Service')->name('add_service');
	Route::post('/chk_service_book' , 'MaskController@checkServiceBooking')->name('chk_service_book');
	Route::post('/redeem_points' , 'MaskController@ajax_redeem_point')->name('redeem_points');
	Route::post('/update_profile' , 'MaskController@update_Profile')->name('update_profile');
	Route::post('/upload_image' , 'MaskController@upload_Image')->name('upload_image');
	Route::post('/applycode' , 'MaskController@sanApplyCoupon')->name('applycode');
	Route::get('/set_currency/{currency}' , 'MaskController@setCurrency');
	Route::post('/chk_email' , function(){
		$user = \App\User::where('email',app('request')->email)->first();
		if (isset($user->id) && $user->id !='') {
			return response()->json(1);
		}else{
			return response()->json(0);
		}
	})->name('chk_email');
	Route::get('/activate_user/{id}/{key}' , function($id,$key){
		$user = \App\User::find($id);
		if (isset($user->id) && $user->id !='' && $user->email_verified_at === null) {
			$user->email_verified_at = new \DateTime();
			$user->save();
			session()->put('message', 'User Verified By Email');
			session()->put('alert-type', 'success');
			return redirect(app('request')->segment(1).'/');
		}else{
			return response()->json('Some Error Occured!');
		}
	})->name('chk_email');
	Route::post('/acceptbooking' , 'MaskController@acceptBooking')->name('acceptbooking');
	Route::post('/rejectbooking' , 'MaskController@rejectBooking')->name('rejectbooking');
	Route::post('/cancel_order' , 'MaskController@cancelOrder')->name('cancel_order');
	Route::post('/update_account' , 'MaskController@updateAccount')->name('update_account');
	Route::post('/payment' , 'MaskController@proceesRoute')->name('payment');
	Route::get('/payment' , 'MaskController@proceesRoute')->name('payment');
	Route::get('/thankyou/{id}' , 'MaskController@thankyou')->name('thankyou');
	Route::get('/userdetail' , 'MaskController@userDetail')->name('userdetail');
	Route::get('/career' , 'MaskController@career')->name('career');
	Route::get('/blogs' , 'MaskController@blogs')->name('blogs');
	Route::get('/offers' , 'MaskController@offers')->name('offers');
	Route::get('/blog/{id}' , 'MaskController@blog_detail')->name('blog');
	// Route::post('/add_guest' , 'MaskController@sanRegister')->name('add_guest');
	Route::get('/booking/{id}' , 'MaskController@Booking')->name('booking');
	Route::post('/booking/{id}' , 'MaskController@BookingPost')->name('booking');
	Route::get('/product/{id}' , 'MaskController@productDetail')->name('product');
	Route::get('/paylater/{id}' , 'MaskController@payLater')->name('paylater');
	Route::get('/paylater' , 'MaskController@payCart')->name('paylater');
	Route::get('/probooking/{id}' , 'MaskController@proBooking')->name('probooking');
	Route::post('/postbook/{id}' , 'MaskController@proBooking')->name('postbook');
	Route::post('/cart_book' , 'MaskController@cartBooking')->name('cart_book');
	Route::get('/postbook/{id}' , 'MaskController@proBooking')->name('postbook');
	Route::post('/probooking/{id}' , 'MaskController@bookProduct')->name('probooking');
	Route::post('/add_to_cart' , 'MaskController@addCart')->name('add_to_cart');
	Route::post('/remove_cart' , 'MaskController@removeCartWeb')->name('remove_cart');
	Route::post('/addreview' , 'MaskController@addReview')->name('addreview');
	Route::post('/favourite' , 'MaskController@favourite')->name('favourite');
	Route::post('/user_forgot_pass', 'MaskController@forgotPwd')->name('user_forgot_pass');
	Route::post('/update_pass' , 'MaskController@updatePwd')->name('update_pass');
	Route::get('/gallary/del/{id}' , 'MaskController@delGallary');
	Route::post('/orderstatus' , 'MaskController@orderStatus')->name('orderstatus');
	/* Delete Product*/
	Route::get('/del_pro/{id}' , function($id){
		\TCG\Voyager\Models\Product::destroy($id);
		app('request')->session()->put('message', 'Product Deleted');
		app('request')->session()->put('alert-type', 'success');
		return redirect(app('request')->segment(1) . '/dashboard?tab=product');
	});
	/* Delete Service*/
	Route::get('/del_ser/{id}' , function($id){
		\TCG\Voyager\Models\Service::destroy($id);
		app('request')->session()->put('message', 'Service Deleted');
		app('request')->session()->put('alert-type', 'success');
		return redirect(app('request')->segment(1) . '/dashboard?tab=service');
	});
	/* Delete Assistant*/
	Route::get('/del_ass/{id}' , function($id){
		\TCG\Voyager\Models\Assistant::destroy($id);
		app('request')->session()->put('message', 'Assistant Deleted');
		app('request')->session()->put('alert-type', 'success');
		return redirect(app('request')->segment(1) . '/dashboard');
	});
	/* Delete Order*/
	Route::get('/del_or/{id}' , function($id){
		\TCG\Voyager\Models\Order::destroy($id);
		app('request')->session()->put('message', 'Order Deleted');
		app('request')->session()->put('alert-type', 'success');
		return redirect(app('request')->segment(1) . '/userdetail');
	});
	Route::post('/upload_gallary/{id}/{type}' , 'MaskController@addUpdateImages')->name('upload_gallary');

	/* Register and Logins */
	Route::post('/member_register', 'MaskController@sanRegister')->name('member_register');
	Route::post('/provider_register', 'MaskController@sanRegister')->name('provider_register');
	Route::post('/login', 'MaskController@memLogin')->name('login');
	Route::post('/clogin', ['login_type'=>'check','uses' =>'MaskController@memLogin'])->name('clogin');
	Route::get('/clogout', 'MaskController@memLogout')->name('clogout');

	$fixed_cats = config('maskfront.fixed_cats');
	foreach ($fixed_cats as $slug => $label) {
		Route::get($slug , ['cat'=> $slug,'uses' => 'MaskController@handleCatSearch'])->name($slug);
	}

	$cats = \TCG\Voyager\Models\Category::has('getproducts')->whereNull('parent_id')->get();
	foreach ($cats as $slug => $label) {
		Route::get($label->slug , ['cat'=> $label->slug,'uses' => 'MaskController@handleProductSearch'])->name($label->slug);
	}

	$mask_pages = config('maskfront.mask_pages');
	foreach ($mask_pages as $slug) {
		Route::get($slug , function() use($slug) {
			$data = array();
			$data['page_data'] = \TCG\Voyager\Models\Page::where('slug', $slug)->first();
			$data['page_name'] = $slug;
			$data['locale'] = app('request')->segment(1);
			if (strpos(url()->full(), '/'.$data['locale'].'/') !== false) {
				$data['previous_url'] = url()->full();
			}else{
				$data['previous_url'] = url()->full().'/';
			}
			if ($slug == 'blog') {
				$data['posts'] = \TCG\Voyager\Models\Post::orderBy('id', 'desc')->get();
			}
			return View('maskFront::pages.template', $data);
		})->name($slug);
	}
	Route::post('/help-center', 'MaskController@helpMessage')->name('help-center');
	/* Front End Routes End */
});
/* Access Files */
Route::get('files/{type}/{mnth}/{image}',function($type,$month,$image){
	return San_Help::get_file($type.'/'.$month.'/'.$image);
});
Route::get('files/{type}/{image}',function($type,$image){
	return San_Help::get_file($type.'/'.$image);
});
Route::get('files/{image}',function($image){
	return San_Help::get_file($image);
});
/***/

/* Api */
Route::group(['namespace' => 'Sandeep\Maskfront\Controllers','prefix' => 'api'], function()
{
	Route::post('/login', 'ApiController@Login');
	Route::post('/register', ['reg_type'=>'provider','uses' =>'ApiController@Register']);
	Route::post('/userregister', ['reg_type'=>'user','uses' =>'ApiController@Register'])->name('userregister');
	Route::post('/mask_get_uservices', ['reg_type'=>'user','uses' =>'ApiController@maskServices'])->name('mask_get_uservices');
	Route::post('/mask_chang_password', 'ApiController@changePassword')->name('mask_chang_password');
	Route::post('/get_provider', 'ApiController@getProvider')->name('get_provider');
	Route::post('/editprofile', 'ApiController@update_Profile')->name('editprofile');
	Route::post('/mask_get_bookingsda', 'ApiController@getBooking')->name('mask_get_bookingsda');
	Route::post('/mask_booking_actions', 'ApiController@doactionBooking')->name('mask_booking_actions');
	Route::post('/booking_novisit_mask', 'ApiController@noVisit')->name('booking_novisit_mask');
	Route::post('/mask_get_gallery', 'ApiController@getGallary')->name('mask_get_gallery');
	Route::post('/mask_get_services_', 'ApiController@getServices')->name('mask_get_services_');
	Route::post('/get_user_profile_z', 'ApiController@getProfile')->name('get_user_profile_z');
	Route::post('/mask_offers_', 'ApiController@offers')->name('mask_offers_');
	Route::post('/mask_single_saloon', 'ApiController@getSallon')->name('mask_single_saloon');
	Route::post('/mask_add_fav', 'ApiController@favourite')->name('mask_add_fav');
	Route::post('/mask_search_uservices', 'ApiController@search')->name('mask_search_uservices');
	Route::post('/_single_salon_services', 'ApiController@salonServices')->name('_single_salon_services');
	Route::post('/mask_get_fav', 'ApiController@getFavourite')->name('mask_get_fav');
	Route::post('/get_mask_time_', 'ApiController@getTime')->name('get_mask_time_');
	Route::post('/mask_booking_date', 'ApiController@checkServiceBooking')->name('mask_booking_date');
	Route::post('/mask_booking_date_ios', 'ApiController@getCatServices')->name('mask_booking_date_ios');
	Route::post('/mask_booking_assistant', 'ApiController@bookAssistant')->name('mask_booking_assistant');
	Route::post('/mask_help_message', 'ApiController@helpMessage')->name('mask_help_message');
	Route::post('/user_forgot_pass', 'ApiController@forgotPwd')->name('user_forgot_pass');
	Route::post('/order_summary', 'ApiController@orderSummary')->name('order_summary');
	Route::post('/order_summaryy', 'ApiController@orderSummary_ios')->name('order_summaryy');
	Route::post('/order_finish', 'ApiController@orderFinish')->name('order_finish');
	Route::post('/mask_share_saloon', 'ApiController@shareSallon')->name('mask_share_saloon');
	Route::post('/add_payment_status', 'ApiController@addPayStatus')->name('add_payment_status');
	Route::post('/show_user_booking', 'ApiController@userBookings')->name('show_user_booking');
	Route::post('/_user_cancel_booking', ['status'=>'Canceled','uses' =>'ApiController@doactionBooking'])->name('_user_cancel_booking');
	Route::post('/reject_mask_bookin', ['status'=>'Rejected','uses' =>'ApiController@doactionBooking'])->name('reject_mask_bookin');
	Route::post('/complete_mask_bookin', ['status'=>'Completed','uses' =>'ApiController@doactionBooking'])->name('complete_mask_bookin');
	Route::post('/user_add_review', 'ApiController@addReview')->name('user_add_review');
	Route::post('/sp_add_reply', 'ApiController@reviewReply')->name('sp_add_reply');
	Route::post('/sallon_reviews', 'ApiController@sallonReviews')->name('sallon_reviews');
	Route::post('/verify_otp_api', 'ApiController@verifyOtp')->name('verify_otp_api');
	Route::post('/get_user_reward_points', 'ApiController@getRewardPoints')->name('get_user_reward_points');
	Route::post('/redeem_point_fn', 'ApiController@redeemPoints')->name('redeem_point_fn');
	Route::post('/jewelries_info', 'ApiController@jewelriesInfo')->name('jewelries_info');
	Route::post('/terms_conditions', 'ApiController@termCondition')->name('terms_conditions');
	Route::post('/mask_edit_prof', 'ApiController@updateAccount')->name('mask_edit_prof');
	Route::get('/product_cats', function(){
		$cats = \TCG\Voyager\Models\Category::orderBy('id', 'desc')->whereNull('parent_id')->where('type', 'procategory')->get()->toArray();
		foreach ($cats as $key => $value) {
			$value['name'] = San_Help::sanGetLang($value['name'], app('request')->lng?app('request')->lng:'en' );
			$cats[$key] = San_Help::sanReplaceNull($value);
		}
		return response()->json([
			'status' =>'success',
			'detail' => $cats
		], 200);
	})->name('product_cats');
	Route::get('/product_colors', function(){
		$colors = array();
		foreach (config('maskfront.colors') as $key => $value) {
			array_push($colors,$key);
		}
		return response()->json([
			'status' =>'success',
			'detail' => $colors
		], 200);
	})->name('product_colors');
	Route::get('/product_providers', function(){
		$providers = array();
		foreach (\TCG\Voyager\Models\Provider::has('getProducts')->get() as $key => $value) {
			$providers[] = array('id'=>$value->id,'name'=>San_Help::sanGetLang($value->name, app('request')->lng?app('request')->lng:'en' ));
		}
		return response()->json([
			'status' =>'success',
			'detail' => $providers
		], 200);
	});
	Route::post('/product_search', 'ApiController@productSearch')->name('product_search');
	Route::post('/mask_add_gallery', ['type'=>'providers','uses' =>'ApiController@addUpdateImages'])->name('mask_add_gallery');
	Route::get('/mask_get_uservices_fx', function(){
		$fixs = config('maskfront.dropdown_fixed');
		foreach ($fixs as $val => $label) {
			$final_arr[] = array('value'=>$val,'label'=>$label,'image'=>'fix_cat_images/'.$val.'.png');
		}
		return response()->json([
			'status' =>'success',
			'detail' => $final_arr
		], 200);
	})->name('mask_get_uservices_fx');
	Route::get('/sb_get_country_codes', function(){
		return response()->json([
			'status' =>'success',
			'detail' => DB::table('san_country')->get()
		], 200);
	})->name('sb_get_country_codes');
	Route::get('/mask_currencies', function(){
		$detail_currency = array();
		foreach(config('money') as $name => $currency){
			$detail_currency[] = array('name' => $name, 'symbol' => $currency['symbol']);
		}
		return response()->json([
			'status' =>'success',
			'detail' => $detail_currency
		], 200);
	})->name('mask_currencies');
	/* Products Section */
	Route::post('/products', 'ApiController@getProducts');
	Route::post('/add_to_cart', 'ApiController@addCart');
	Route::post('/remove_product', 'ApiController@removeCart');
	Route::post('/cart_list', 'ApiController@getCart');
	Route::post('/shop_orders', 'ApiController@orders');
	Route::post('/add_to_wishlist', 'ApiController@addWishList');
	Route::post('/wishlist', 'ApiController@getWishList');
	Route::post('/place_order', 'ApiController@payCart');
	Route::post('/salon_shop_orders', 'ApiController@salonOrders');
	Route::post('/salon_products', 'ApiController@salonProducts');
	Route::post('/add_order_status', 'ApiController@addOrderPayStatus');
	Route::post('/cancel_order' , 'ApiController@cancelOrder')->name('cancel_order');
	Route::post('/review_order' , 'ApiController@addProductReview')->name('review_order');
	Route::post('/product_reviews' , 'ApiController@productReviews')->name('product_reviews');
	/* Product setion end */
});

Route::get('/chk_currency',function(){
	San_Help::sanMoney('USD',10);
	$url = "http://data.fixer.io/api/latest?access_key=b351d69bc03f985b204b2e7875c4fbff";
	$client = new \GuzzleHttp\Client();
	$res = $client->request('GET', $url);
	$data = json_decode($res->getBody());
	echo '<pre>';print_r($data);exit;
	return response()->json('mail sent');
});

Route::get('/test_sms',function(){
	$data_sms = array(
		'type' => 'new_register',
		'contact_number' => 919896747812,
		'otp' => 12345
	);
	$res = San_Help::sanSendSms($data_sms);
	return response()->json($res);
	
});

Route::get('/chk_currency',function(){

});

Route::get('/notify_mobile',function(){
	// foreach($users as $user){
		$obj = new \Sandeep\Maskfront\Controllers\NotificationController;
		// $res = $obj->sb_notification_fucntions(80,'booking_accepted');
		$res = $obj->chkNotification_ios();
		return response()->json($res);
	// }
});


Route::get('/notify_users',function(){
	$users = App\User::all();
	foreach($users as $user){
		$notify = Sandeep\Maskfront\Controllers\NotificationController();
        $notify->sb_notification_fucntions($book->id,$type);
		San_Help::sendSms(array('contact_number'=>$user->phone,'type'=>'notify_user'));
	}
	// $res = $obj->sb_notification_fucntions(80,'booking_accepted');
	// $res = $obj->chkNotification();
	return response()->json('done');
});
?>
