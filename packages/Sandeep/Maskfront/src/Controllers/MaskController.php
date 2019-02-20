<?php
namespace Sandeep\Maskfront\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\User;
use San_Help;
use TCG\Voyager\Models\Provider;
use TCG\Voyager\Models\Service;
use TCG\Voyager\Models\Assistant;
use TCG\Voyager\Models\Booking;
use TCG\Voyager\Models\Order;
use TCG\Voyager\Models\Offer;
use TCG\Voyager\Models\Avail;
use TCG\Voyager\Models\Category;
use TCG\Voyager\Models\Review;
use TCG\Voyager\Models\Product;
use TCG\Voyager\Models\Cart;
use TCG\Voyager\Models\Page;
use TCG\Voyager\Models\Post;
use Illuminate\Support\Facades\Hash;
use TCG\Voyager\Models\MultiImage;
use Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Str;
use TCG\Voyager\Http\Controllers\VoyagerBaseController;

class MaskController extends Controller
{

    protected $services;

    protected $categories;

    protected $fixed_cats;

    protected $data;

    protected $locale;

    public function __construct()
    {
        $this->data = array();
        $this->request = app('request');
        $action = $this->request->route()->getAction();
        if (isset($action['cat'])) {
            $this->data['cat'] = $action['cat'];
        }
        $this->nav_fixed_cats = config('maskfront.fixed_cats');
        $this->base_cont = new VoyagerBaseController();
        $this->data['locale'] = app('request')->segment(1);
        if (strpos(url()->full(), '/'.$this->data['locale'].'/') !== false) {
            $this->data['previous_url'] = url()->full();
        }else{
            $this->data['previous_url'] = url()->full().'/';
        }
        if (isset(Auth::user()->id)) {
            $this->userId = Auth::user()->id;
            $this->data['cart_data'] = Cart::with('product')->where('user_id',$this->userId)->get();
        }
        $this->data['currency'] = $this->request->session()->get('currency');
        if (!$this->request->session()->get('currency')) {
            $this->request->session()->put('currency', 'SAR');
            $this->data['currency'] = 'SAR';
        }
    }

    public function index()
    {
        // echo '<pre>';print_r(San_Help::money(20,'EGP',true));exit;
        $this->data['services'] = Category::has('getServices')->whereNotNull('parent_id')->get();
        $this->data['sallons'] = Provider::with('reviews')->orderBy('id', 'desc')->where('status', 2)->take(9)->get();
        // echo '<pre>';print_r($this->data['sallons']);exit;
        // if (isset($this->data['sallons']->reviews)) {
        //     echo '<pre>';print_r($this->data['sallons']->reviews);exit;
        // }
        // Provider::with('reviews')->orderBy('id', 'desc')->where('status', 2)->take(9);
        $this->data['blogs'] = Post::orderBy('id', 'desc')->take(10)->get();
        $this->data['fixed_cats'] = $this->nav_fixed_cats;
        // $this->data['products'] = $this->SanGetProducts();
        $this->data['products'] = $this->SanGetCategories('', 'procategory');
        $this->data['reviews'] = Review::latest()->limit(5)->get();
        $this->data['home'] = Page::where('slug', 'home')->first();
        $this->data['page_type'] = app('request')->segment(2);
        if ($this->data['page_type'] == 'shop') {
          $this->data['prods'] = Product::orderBy('id', 'desc')->where('active', 1)->take(9)->get();
        }
        return View('maskFront::pages.home', $this->data);
    }

    public function business()
    {
        $this->data['services'] = $this->SanGetServices();
        $this->data['business'] = Page::where('slug', 'business')->first();
        return View('maskFront::pages.business', $this->data);
    }

    public function san_Team()
    {
        $this->data['team'] = Page::where('slug', 'team')->first();
        return View('maskFront::pages.team', $this->data);
    }

    public function career()
    {
        $this->data['career'] = Page::where('slug', 'career')->first();
        return View('maskFront::pages.career', $this->data);
    }

    public function blog_detail($id)
    {
        $this->data['post'] = Post::find($id);
        return View('maskFront::pages.blog_detail', $this->data);
    }

    public function offer()
    {
        $this->data['offer'] = Page::where('slug', 'offer')->first();
        return View('maskFront::pages.offer', $this->data);
    }

    public function thankyou()
    {
        $session_cart = array('temp_book_id','product_qty','product_color','product_id','provider_id','booking_type','bookid','reward_points','sids','aids','book_date','book_time','total_amnt','total_amount');
        $this->data['data'] = $this->request->session()->all();
        // echo '<pre>';print_r($this->request->all());exit;
        if (isset($this->userId) && isset($this->data['data']['book_date'])) {
            if(isset($this->data['data']['product_id'])){
                Cart::where('user_id',Auth::user()->id)->where('product_id',$this->data['data']['product_id'])->where('color',$this->data['data']['product_color'])->delete();
            }
            if(isset($this->data['data']['booking_type']) && $this->data['data']['booking_type'] == 'cart_book'){
                Cart::where('user_id',$this->userId)->delete();
            }
            foreach ($session_cart as $session_var) {
                $this->request->session()->forget($session_var);
            }
            if($this->request->error_msg){
                $this->request->session()->put('message', $this->request->error_msg);
                $this->request->session()->put('alert-type', 'warning');
                return redirect($this->data['locale'] . '/');
            }
            $this->data['cart_data'] = Cart::with('product')->where('user_id',$this->userId)->get();
            return View('maskFront::pages.thankyou', $this->data);
        }else{
            return redirect($this->data['locale'] . '/');
        }
    }

    public function offers()
    {
        $this->data['offers'] = Offer::with('provider')->whereDate('valid_to', '>=', Carbon::now('Asia/Calcutta'))->get();
        return View('maskFront::pages.offer', $this->data);
    }

    public function userDetail()
    {
        if (! isset($this->userId)) {
            return redirect('/');
        }
        $this->data['user'] = User::with('getBookings')->with('getRewards')->with('orders')->with('reviews')->find($this->userId);
        return View('maskFront::pages.userdetail', $this->data);
    }

    public function addReview(){
        $validator = Validator::make($this->request->all(), [
            // 'record_id' => 'required',
            'rating_on' => 'required',
            'review' => 'required'
        ]);
        if ($validator->fails()) {
            $error = $validator->errors()->first();
            if ($this->request->ajax()) {
                return response()->json(array(
                    'message' => $error
                ));
            }
        }
        if (Auth::check()) {
          if ($this->request->has('reply_on')) {
            $review = Review::find($this->request->reply_on);
            if ($review) {
              $review->reply = $this->request->review;
              $review->updated_at = new \DateTime();
              $review->save();
              return response()->json(compact('review'));
            }else{
              return response()->json(array(
                  'message' => 'Something Went Wrong'
              ));
            }
          }
          if ($this->request->has('record_id')) {
            $user_review = DB::table('reviews')->where('user_id',Auth::user()->id)->where('type',$this->request->rating_on)->where('record_id',$this->request->record_id)->first();
            if ($user_review) {
                DB::table('reviews')->where('user_id',Auth::user()->id)->where('record_id',$this->request->record_id)->update([
                    'rating' => $this->request->rating,
                    'review' => $this->request->review,
                    'updated_at' => new \DateTime()
                ]);
            }else{
                $revid = DB::table('reviews')->insertGetId([
                    'record_id' => $this->request->record_id,
                    'user_id' => Auth::user()->id,
                    'rating' => $this->request->rating,
                    'review' => $this->request->review,
                    'type' => $this->request->rating_on,
                    'created_at' => new \DateTime(),
                    'updated_at' => new \DateTime()
                ]);
            }
            $avg = DB::table('reviews')->where('record_id',$this->request->record_id)->where('type',$this->request->rating_on)->avg('rating');
            $avg = number_format($avg, 2, '.', '');
            $pro = Product::find($this->request->record_id);
            $pro->rating = $avg;
            $pro->save();
            $reviews = DB::table('reviews')->where('record_id',$this->request->record_id)->where('type',$this->request->rating_on)->get();
            $reviews_html = View('maskFront::includes.review_list', [
                'product_reviews' => $reviews
            ])->render();
            return response()->json(compact('reviews_html'));
          }else{
            return response()->json(array(
                'message' => 'Record id is required'
            ));
          }
        }else{
            return response()->json('err');
        }
    }

    function setCurrency($curr){
        $this->request->session()->put('currency', $curr);
        return redirect()->intended(url()->previous());
    }

    function productReviews($id=''){
        $review_query = Review::where(function ($query) {
                            $query->where('type', '=', 'product')->orWhere('type', '=', 'order');
                        })->get()->toArray();
        foreach ($review_query as $keyy => $value) {
            if ($value['type'] == 'order') {
                $order = Order::find($value['record_id']);
                if ($order && is_null($order->product_ids)) {
                    foreach (unserialize($order->provider_id) as $key => $products) {
                        if (!in_array($id, $products)) {
                           unset($review_query[$keyy]);
                           continue;
                        }else{
                            $review_query[$keyy]['image'] = User::find($value['user_id'])->avatar;
                        }
                    }
                }
            }else{
                if ($id != $value['record_id']) {
                    unset($review_query[$keyy]);
                    continue;
                }else{
                    $review_query[$keyy]['image'] = User::find($value['user_id'])->avatar;
                }
            }
        }
        return $review_query;
    }

    function sallon_productReviews($id){
        $review_query = Review::where(function ($query) {
                            $query->where('type', '=', 'product')->orWhere('type', '=', 'order');
                        })->get()->toArray();
        $new_final_arr = array();
        foreach ($review_query as $keyy => $value) {
            if ($value['type'] == 'order') {
                $order = Order::find($value['record_id']);
                if ($order && is_null($order->product_ids)) {
                    foreach (unserialize($order->provider_id) as $key => $products) {
                        if ($key == $id) {
                            $value['image'] = User::find($value['user_id'])->avatar;
                            $new_final_arr[] = $value;
                            // unset($review_query[$keyy]);

                            continue;
                        }else{
                            // $review_query[$keyy]['image'] = User::find($value['user_id'])->avatar;
                        }
                    }
                }
            }else{
                $product = Product::find($value['record_id']);
                if ($id == $product->provider_id) {
                    $value['image'] = User::find($value['user_id'])->avatar;
                    $new_final_arr[] = $value;
                    // unset($review_query[$keyy]);
                    continue;
                }else{
                    // $review_query[$keyy]['image'] = User::find($value['user_id'])->avatar;
                }
            }
        }
        foreach ($new_final_arr as $key => $value) {
            $value['image'] = User::find($value['user_id'])->avatar;
            $new_final_arr[$key] = San_Help::sanReplaceNull($value);
        }
        return $new_final_arr;
    }

    public function favourite(){
        $user = User::find($this->request->user_id);
        $fav = array();
        if ($this->request->type == 'provider') {
            if ($user->favourite) {
                $fav = unserialize($user->favourite);
                if (!in_array($this->request->record_id, $fav)) {
                    array_push($fav, $this->request->record_id);
                    $added = 1;
                }else{
                    if (($key = array_search($this->request->record_id, $fav)) !== false) {
                        unset($fav[$key]);
                    }
                    $added = 0;
                }
            }else{
                array_push($fav, $this->request->record_id);
                $added = 1;
            }
            $user->favourite = serialize($fav);
        }elseif ($this->request->type == 'product') {
            if ($user->fav_products) {
                $fav = unserialize($user->fav_products);
                if (!in_array($this->request->record_id, $fav)) {
                    array_push($fav, $this->request->record_id);
                    $added = 1;
                }else{
                    if (($key = array_search($this->request->record_id, $fav)) !== false) {
                        unset($fav[$key]);
                    }
                    $added = 0;
                }
            }else{
                array_push($fav, $this->request->record_id);
                $added = 1;
            }
            $user->fav_products = serialize($fav);
        }
        $user->save();
        $result = array();
        if($added == 1){
            $result['res'] = 1;
            $result['msg'] = 'Provider added to Favorite Successfully';
        }else{
            $result['res'] = 2;
            $result['msg'] = 'Provider Removed from Favorite Successfully';
        }
        return response()->json($result);
    }

    public function addCart()
    {
        $cart = new Cart();
        $chkproduct = Product::find($this->request->product_id);
        if ($chkproduct->qty <=0) {
            return response()->json('Product Not Available!');
        }
        if ($chkproduct) {
            $colors = $chkproduct->color;
            if ($colors) {
                $colors = explode(',', $colors);
                $colors = array_map('strtolower', $colors);
                if (is_array($colors) && !in_array($this->request->color_name, $colors)) {
                    return response()->json('Color Not Found');
                }
            }
        }
        $update = Cart::where('user_id', $this->request->user_id)->where('product_id', $this->request->product_id)
            ->where('color', $this->request->color_name)
            ->first();
        $already = Cart::where('user_id', $this->request->user_id)->where('product_id', $this->request->product_id)
            ->where('color', $this->request->color_name)->where('qty', $this->request->qty)
            ->first();
            if (isset($already->id)) {
                return response()->json('Product Alraedy Addded In Cart');
            }
        if (isset($update->id)) {
            $update->price = $this->request->price;
            $update->qty = $this->request->qty;
            $update->total = $this->request->price * $this->request->qty;
            $update->save();
            $cartdata = Cart::with('product')->where('user_id', $this->request->user_id)->get();
            return response()->json($cartdata);
        }
        $cart->product_id = $this->request->product_id;
        $cart->user_id = $this->request->user_id;
        $cart->price = $this->request->price;
        $cart->color = $this->request->color_name;
        $cart->qty = $this->request->qty;
        $cart->total = $this->request->price * $this->request->qty;
        $cart->save();
        $cartdata = Cart::with('product')->where('user_id', $this->request->user_id)->get();
        return response()->json($cartdata);
    }

    public function removeCartWeb(){
        Cart::destroy($this->request->cart_id);
        $cartdata = Cart::with('product')->where('user_id', $this->request->user_id)->get();
        return response()->json($cartdata);
    }

    public function Search()
    {
        $this->data['services'] = Category::has('getServices')->whereNotNull('parent_id')
            ->where('type', 'category')
            ->get();
        $this->data['type'] = isset($this->request->type) ? $this->request->type : 'services';
        if ($this->request->type == 'services') {
            $this->data['categories'] = Category::has('getServices')->whereNotNull('parent_id')
                ->where('type', 'category')
                ->get();
            $pro = Provider::has('getServices')->with('reviews');
        }
        if ($this->request->type == 'products') {
            $this->data['products'] = Product::with('provider')->where('active', 1);
            $this->data['categories'] = Category::orderBy('id', 'desc')->whereNull('parent_id')
                ->where('type', 'procategory')
                ->get();
        }
        if ($this->request->sr != '' && $this->request->sr != '+') {
            if (! is_numeric($this->request->sr)) {
                $pids = Service::where('name', 'like', '%' . $this->request->sr . '%')->pluck('provider_id')->toArray();
            } else {
                $pids = Service::where('parent_service', $this->request->sr)->pluck('provider_id')->toArray();
            }
	        if (! empty($pids)) {
	            $pro = $pro->whereIn('id',$pids);
	        }else {
	           $pro = '';
	        }
        }
        if ($this->request->pr != '' && $this->request->pr != '+') {
            if (! is_numeric($this->request->pr)) {
              $pr =  $this->request->pr;
              $cids = Category::orderBy('id', 'desc')->whereNull('parent_id')->where('type', 'procategory')->where(function ($query) use($pr) {
                                $query->where('name', 'like', '%' . $pr . '%')->orWhere('slug', 'like', '%' . $pr . '%');
                       })->pluck('id')->toArray();
                       // ->where('name', 'like', '%' . $this->request->pr . '%')

                $this->data['products'] = $this->data['products']->whereIn('category_id', $cids);
            } else {
                $this->data['products'] = $this->data['products']->where('category_id', $this->request->pr);
            }
        }
        if ($this->request->type == 'products') {
            $pro_ids = array();
            $colors__ = array();
            foreach ($this->data['products']->get() as $p_key => $p_value) {
                if ($p_value->color) {
                    $colors = explode(',', $p_value->color);
                    $colors = array_map('strtolower', $colors);
                    $colors__ = array_unique(array_merge($colors__,$colors));
                    if (in_array(trim(strtolower($this->request->clr)), $colors)) {
                        array_push($pro_ids,$p_value->id);
                    }
                }
            }
        }
        
        if ($this->request->clr != '' && $this->request->clr != '+') {
          $this->data['products'] = $this->data['products']->whereIn('id', $pro_ids);
        }
        if ($this->request->has('price') && $this->request->price !='' && $this->request->type == 'products') {
          try {
            $this->data['price_filter'] = explode('@',$this->request->price);
          } catch (\Illuminate\Validation\ValidationException $e) {
            $this->data['price_filter'] = $this->request->price;
          }
          $min = 0;
          $max = 10000;
          if (isset($this->data['price_filter'][0])) {
            $min = $this->data['price_filter'][0];
          }
          if (isset($this->data['price_filter'][1])) {
            $max = $this->data['price_filter'][1];
          }
          $this->data['products'] = $this->data['products']->whereBetween('price', [(int) $min,(int) $max]);
        }
        if ($this->request->has('provider') && $this->request->provider !='' && $this->request->type == 'products') {
          $this->data['products'] = $this->data['products']->where('provider_id', $this->request->provider);
        }
        if (isset($this->request->wr) && $this->request->wr != '' && $this->request->type == 'services') {
            $wr_con = $this->request->wr;
            $pro = $pro->where(function ($query) use($wr_con) {
                            $query->where('city_country', 'like', '%' . $wr_con . '%')->orWhere('address', 'like', '%' . $wr_con . '%');
                   });
        }
        // echo '<pre>';print_r($pro->get()->toArray());exit;
        if (isset($pro) && ! empty($pro) && $this->request->type == 'services') {
            $data = $pro->get();
        } elseif (isset($pro) && empty($pro)) {
            $data = array();
        }else{
            $data = array();
        }
        // echo "<pre>";print_r($data);exit;
        $sallon_arr = array();
        $sallon_arr2 = array();
        if ($this->request->type == 'services') {
            foreach ($data as $key => $dat_val) {
                $data[$key]->avg_rating = $dat_val->reviews->avg('rating');
            }
        }
        // echo "<pre>";print_r($data);
// echo "<pre>";print_r($sallon_arr);exit;
        if (isset($this->request->cust_lat) && $this->request->cust_lat != '' && isset($this->request->cust_long) && $this->request->cust_long != '' && $this->request->type == 'services') {
            $lat = '';
            $lng = '';
            foreach ($data as $key => $dat) {
	                if ($dat->latitude != '' && $dat->longitude != '') {
                        $lat = $dat->latitude;
                        $lng = $dat->longitude;
                    }else{
                        $coord = San_Help::get_Coordinates($dat->address);
                        $lat = $coord['lat'];
                        $lng = $coord['long'];
                    }
	                $baseloc['Lat'] = $this->request->cust_lat;
					$baseloc['Lon'] = $this->request->cust_long;
					$sallon_loc['Lat'] = $lat;
					$sallon_loc['Lon'] = $lng;
	                $sallon_arr2['distance'] = San_Help::distance_btwn_loc($baseloc, $sallon_loc);
	                $sallon_arr[] = array_merge($dat->toArray(),$sallon_arr2);
	        }
	        usort($sallon_arr, function($a,$b){
				return $a["distance"] - $b["distance"];
		    });
        }
        // echo "<pre>";print_r($sallon_arr);exit;
        if ($this->request->type == 'services') {
            $this->data['sallons'] = !empty($sallon_arr) ? $sallon_arr : $data->toArray();
        }
        if ($this->request->type == 'products') {
          $this->data['products'] = $this->data['products']->get();
          $this->data['products_colors'] = $colors__;
        }
        $this->data['business'] = Page::where('slug', 'business')->first();
        return View('maskFront::pages.search', $this->data);
    }

    public function SearchAjax()
    {
        $fin_data = unserialize($this->request->data);
        // echo '<pre>';print_r($this->request->page);exit;
        $this->data['services'] = Category::has('getServices')->whereNotNull('parent_id')
            ->where('type', 'category')
            ->get();
        $this->data['type'] = isset($fin_data['type']) ? $fin_data['type'] : 'services';
        if ($fin_data['type'] == 'services') {
            $this->data['categories'] = Category::has('getServices')->whereNotNull('parent_id')
                ->where('type', 'category')
                ->get();
            $pro = Provider::has('getServices')->with('reviews');
        }
        if ($fin_data['type'] == 'products') {
            $this->data['products'] = Product::with('provider')->where('active', 1)->get();
            $this->data['categories'] = Category::orderBy('id', 'desc')->whereNull('parent_id')
                ->where('type', 'procategory')
                ->get();
        }
        if ($fin_data['sr'] != '' && $fin_data['sr'] != '+') {
            if (! is_numeric($fin_data['sr'])) {
                $pids = Service::where('name', 'like', '%' . $fin_data['sr'] . '%')->pluck('provider_id')->toArray();
            } else {
                $pids = Service::where('parent_service', $fin_data['sr'])->pluck('provider_id')->toArray();
            }
	        if (! empty($pids)) {
	            $pro = $pro->whereIn('id',$pids);
	        }else {
	           $pro = '';
	        }
        }
        if (isset($fin_data['pr']) != '' && $fin_data['pr'] != '+') {
            if (! is_numeric($fin_data['pr'])) {
                $cids = Category::orderBy('id', 'desc')->whereNull('parent_id')->where('type', 'procategory')->where('name', 'like', '%' . $fin_data['pr'] . '%')->pluck('id')->toArray();
                $this->data['products'] = $this->data['products']->whereIn('category_id', $cids);
            } else {
                $this->data['products'] = $this->data['products']->where('category_id', $fin_data['pr']);
            }
        }
        if (isset($fin_data['wr']) && $fin_data['wr'] != '' && $fin_data['type'] == 'services') {
            $wr_con = $fin_data['wr'];
            $pro = $pro->where(function ($query) use($wr_con) {
                            $query->where('city_country', 'like', '%' . $wr_con . '%')->orWhere('address', 'like', '%' . $wr_con . '%');
                   });
        }
        // echo '<pre>';print_r($pro->get()->toArray());exit;
        if (isset($pro) && ! empty($pro) && $fin_data['type'] == 'services') {
            $data = $pro->skip($this->request->page)->take(4)->get();
        } elseif (isset($pro) && empty($pro)) {
            $data = array();
        }else{
            $data = array();
        }
        // echo "<pre>";print_r($data);exit;
        $sallon_arr = array();
        $sallon_arr2 = array();
        if ($fin_data['type'] == 'services') {
            foreach ($data as $key => $dat_val) {
                $data[$key]->avg_rating = $dat_val->reviews->avg('rating');
            }
        }
        // echo "<pre>";print_r($data);
// echo "<pre>";print_r($sallon_arr);exit;
        if (isset($fin_data['cust_lat']) && $fin_data['cust_lat'] != '' && isset($fin_data['cust_long']) && $fin_data['cust_long'] != '' && $fin_data['type'] == 'services') {
            $lat = '';
            $lng = '';
            foreach ($data as $key => $dat) {
	                if ($dat->latitude != '' && $dat->longitude != '') {
                        $lat = $dat->latitude;
                        $lng = $dat->longitude;
                    }else{
                        $coord = San_Help::get_Coordinates($dat->address);
                        $lat = $coord['lat'];
                        $lng = $coord['long'];
                    }
	                $baseloc['Lat'] = $fin_data['cust_lat'];
					$baseloc['Lon'] = $fin_data['cust_long'];
					$sallon_loc['Lat'] = $lat;
					$sallon_loc['Lon'] = $lng;
	                $sallon_arr2['distance'] = San_Help::distance_btwn_loc($baseloc, $sallon_loc);
	                $sallon_arr[] = array_merge($dat->toArray(),$sallon_arr2);
	        }
	        usort($sallon_arr, function($a,$b){
  				return $a["distance"] - $b["distance"];
  		    });
        }

        if ($fin_data['type'] == 'services') {
            $final_arr = !empty($sallon_arr) ? $sallon_arr : $data->toArray();
            // echo "<pre>";print_r($final_arr['data']);exit;
            $this->data['sallons'] = !empty($sallon_arr) ? $sallon_arr : $data->toArray();
            $salon_lists = '';
            // $final_arr['data']
            foreach ($final_arr as $key => $value) {
              $value = (object) $value;
              // echo '<pre>';print_r($value);
              $bookurl = route('booking' ,$value->id);
              $fileurl = url('files/'.$value->avatar);
              $salon_type = '';
              if(isset(config('maskfront.dropdown_fixed')[$value->type])){
                  $salon_type = config('maskfront.dropdown_fixed')[$value->type];
              }
              $salon_lists .= '<li class="list-item-box" onmouseover="hover('.$value->id.')" onmouseout="out('.$value->id.')">
                <div class="item list-group-item">
                  <div class="thumbnail">
                    <a href="'.$bookurl.'" class="user_image_link">
                      <div class="thumb list-group-image" style="background:url('.$fileurl.')">
                      </div>
                    </a>
                    <div class="captions">
                      <div class="top-info-section">
                        <div class="left-img-sec pull-left">
                          <img src="'.$fileurl.'" class="img-circle user-img">
                          <h5><a href="'.$bookurl.'">'.San_Help::sanGetLang($value->name).'<span  class="small sl-category">'.$salon_type.'</span></a></h5>
                        </div>
                        <div class="feed prod-item-list"><ul class="list-inline rating-list">';
                            for ($i = 1; $i <= 5; $i ++){
                              $selected = "";
                            if (!empty($value->reviews) && $i <= $value->avg_rating){
                              $selected = "checked";
                            }
                            $salon_lists .= '<li><span class="fa fa-star '.$selected.'"></span></li>';
                           }
                          $salon_lists .= '</ul>
                        </div>
                      </div>
                      <div class="col-sm-12 pad-0 btm-flexboxx">
                        <div class="share">
                          <ul class="list-inline share-list">';
                            if(Auth::check() && Auth::user()->favourite){
                              $fav = unserialize(Auth::user()->favourite);
                            }
                            $class = '';
                            if(isset($fav) && in_array($value->id,$fav)){
                             $class = 'added';
                            }
                            $hrfurl = url($this->data['locale'].'/search?type=services&wr=&pr=');
                            $salon_lists .= '<li><a href="javascript:void(0)" data-type="provider" data-id="'.$value->id.'" class="_add_favorite '.$class.'" id="add_favorite_'.$value->id.'"><i class="fa fa-heart"></i></a></li>
                            <li><a href="javascript:void(0)"><i class="fa fa-share-alt" data-href="'.$hrfurl.'"></i></a></li>
                          </ul>
                        </div>
                        <div class="col-sm-4 pad-0 features-areas">
                          <ul class="list-inline extra_features">';
                            $avail = \TCG\Voyager\Models\Avail::where('provider_id',$value->id)->first();
                            if(isset($avail->extra)){
                              $realdata = unserialize($avail->extra);
                            }
                              if(isset($realdata)){
                              if(isset($realdata['welcome_drink']) && $realdata['welcome_drink'] == 1){
                                $salon_lists .= '<li>
                                  <img src="/packages/Sandeep/Maskfront/resources/assets/images/WelcomeDrink.png" title="Welcome Drink">
                                </li>';
                              }
                              if(isset($realdata['kids_care']) && $realdata['kids_care'] ==1){
                                $salon_lists .= '<li>
                                  <img src="/packages/Sandeep/Maskfront/resources/assets/images/Kids.png" title="Kids Care">
                                </li>';
                               }
                              if(isset($realdata['pets']) && $realdata['pets'] ==1){
                                $salon_lists .= '<li>
                                  <img src="/packages/Sandeep/Maskfront/resources/assets/images/Pets.png" title="Pets">
                                </li>';
                              }
                              if(isset($realdata['cash']) && $realdata['cash'] ==1){
                                $salon_lists .= '<li>
                                  <img src="/packages/Sandeep/Maskfront/resources/assets/images/cash.png" title="Accept Payment by Cash">
                                </li>';
                              }
                              if(isset($realdata['wifi']) && $realdata['wifi'] ==1){
                                $salon_lists .= '<li>
                                  <img src="/packages/Sandeep/Maskfront/resources/assets/images/Wifi.png" title="Wifi">
                                </li>';
                              }

                              if(isset($realdata['card']) && $realdata['card'] ==1){
                                $salon_lists .= '<li>
                                  <img src="/packages/Sandeep/Maskfront/resources/assets/images/card.png" title="Wifi">
                                </li>';
                              }
                            }
                            $salon_lists .= '</ul>
                          </div>
                          <div class="btn-sec text-right">
                            <a href="'.$bookurl.'" class="btn book-btn">Book now</a>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </li>';
            }
        }
        $this->data['business'] = Page::where('slug', 'business')->first();
        return response()->json(compact('salon_lists'));
    }

    public function SanGetProducts()
    {
        $products = Product::where('active', 1)->get();
        return $products;
    }

    public function booking($id)
    {
        // echo "<pre>";print_r($this->request->all());exit;
        if (! isset($this->request->tab)) {
            $this->request->session()->forget('sids');
            $this->request->session()->forget('aids');
            $this->request->session()->forget('book_date');
            $this->request->session()->forget('book_time');
            $this->request->session()->forget('total_amnt');
        }
        $this->data['provider'] = Provider::with('getAvail')->with('getServices')
            ->with('getAssistants')
            ->with('getBookings')
            ->with('getProducts')
            ->with('provider_images')
            ->find($id);
        $bids = Booking::where('salon_id',$id)->pluck('id')->toArray();
        $this->data['reviews'] = Review::where('type','booking')->whereIn('record_id',$bids)->get();
        $this->data['product_reviews'] = $this->sallon_productReviews($id);
        // echo "<pre>";print_r($this->data['product_reviews']);exit;
        $parids = $this->data['provider']->getServices->pluck('parent_service')->toArray();
        $service_ids = $this->data['provider']->getServices->pluck('id')->toArray();
        if (! empty($parids)) {
            $this->data['cats'] = Category::whereIn('id', $parids)->with([
                'getServices' => function ($service) use ($service_ids) {
                    return $service->whereIn('id', $service_ids);
                }
            ])->get();
        }
        if ($this->request->session()->get('aids')) {
            $aids = unserialize($this->request->session()->get('aids'));
            $this->data['ass_names'] = Assistant::whereIn('id', $aids)->pluck('name')->toArray();
        }
        if ($this->request->session()->get('sids')) {
            $sids = unserialize($this->request->session()->get('sids'));
            $this->data['ser_names'] = Service::whereIn('id', $sids)->get(array(
                'id',
                'name',
                'price'
            ));
            $this->data['sids'] = Service::whereIn('id', $sids)->pluck('id')->toArray();
            $this->data['total_amount'] = Service::whereIn('id', $sids)->sum('price');
        }
        if (isset($this->userId) && isset($this->request->tab) && $this->request->tab == 'check') {
            return redirect($this->data['locale'] . '/booking/' . $id . '?tab=summary');
        }
        if ((! isset($this->userId) && isset($this->request->tab) && $this->request->tab == 'summary') || (isset($this->request->tab) && ! $this->request->session()->get('book_date') && $this->request->tab != 'profile')) {
            return redirect($this->data['locale'] . '/booking/' . $id);
        }
        if (! $this->request->session()->get('book_date') && isset($this->request->tab) && $this->request->tab == 'payment') {
            return redirect($this->data['locale'] . '/booking/' . $id);
        }
        // echo "<pre>";print_r($this->data);exit;
        return View('maskFront::pages.booking', $this->data);
    }

    function proBooking($id)
    {
    	if ($this->request->tab == 'cartsummary') {
    		$this->data['cartdata'] = Cart::with('product')->where('user_id', $this->userId)->get();
    	}else{
    		if ($this->request->qty > 0) {
	            $this->request->session()->put('product_qty', $this->request->qty);
	            $this->request->session()->put('product_color', $this->request->color_name);
	        }
	        $this->data['product'] = Product::with('product_images')->with('provider')
	            ->with('category')
	            ->with('related_products')
	            ->find($id);
	            if ($this->data['product']) {
	            	$this->data['total_amount'] = $this->data['product']->price;
	            }else{
	            	$this->data['booking_type'] = 'cart_book';
	            }
    	}
      // echo "<pre>";print_r($this->request->session()->all());exit;
        return View('maskFront::pages.product_cart', $this->data);
    }

    function cartBooking(){
    	$this->request->session()->put('booking_type', 'cart_book');
    	$this->request->session()->put('total_amnt', $this->request->total_amount);
    	return redirect($this->data['locale'] . '/probooking/' . $this->userId . '?tab=payment');
    }

    function bookProduct($id)
    {
        $this->request->session()->put('product_id', $id);
        $this->request->session()->put('booking_type', 'products');
        $this->data['product'] = Product::with('product_images')->with('provider')
            ->with('category')
            ->with('related_products')
            ->find($id);
        $this->request->session()->put('provider_id', $this->data['product']->provider[0]->id);
        $this->request->session()->put('total_amnt', $this->request->total_amount);
        return redirect($this->data['locale'] . '/probooking/' . $id . '?tab=payment');
    }

    function productDetail($id)
    {
        $this->data['product'] = Product::with('product_images')->with('provider')
            ->with('category')
            ->with('related_products')
            ->find($id);
        if (Auth::check()) {
            $this->data['user_review'] = DB::table('reviews')->where('user_id',Auth::user()->id)->where('record_id',$id)->first();
        }else{
            $this->data['user_review'] = '';
        }
        $this->data['product_reviews'] = DB::table('reviews')->where('record_id',$id)->get();
        return View('maskFront::pages.product_detail', $this->data);
    }

    public function BookingPost($id)
    {
        $this->data['provider'] = Provider::with('getAvail')->with('getServices')
            ->with('getAssistants')
            ->with('getBookings')
            ->with('getProducts')
            ->find($id);
        $parids = $this->data['provider']->getServices->pluck('parent_service')->toArray();
        if (! empty($parids)) {
            $this->data['cats'] = Category::whereIn('id', $parids)->with('getServices')->get();
        }
        $sids = [];
        $aids = [];
        // if (! $this->request->session()->get('aids') && ! $this->request->session()->get('sids')) {
            if (isset($this->request->sids) && isset($this->request->aids)) {
                $sids = $this->request->sids;
                $aids = $this->request->aids;
            } elseif ($this->request->services) {
                foreach ($this->request->services as $sid => $service) {
                    array_push($sids, $sid);
                    if ($service != 'on') {
                        foreach ($service as $ais => $assistant) {
                            if ($assistant == 'on') {
                                array_push($aids, $ais);
                            }
                        }
                    } else {
                        $assss = Assistant::inRandomOrder()->where('provider_id',$id)->first();
                        if ($assss) {
                            if (! in_array('any', $aids))
                                array_push($aids, $assss->id);
                        }
                    }
                }
            }
            if (isset($aids) && empty($aids)) {
                $assss = Assistant::inRandomOrder()->where('provider_id',$id)->first();
                if ($assss) {
                   array_push($aids, $assss->id);
                }
            }
            $this->request->session()->put('pro_id', $id);
            $this->request->session()->put('booking_type', 'services');
            if (!empty($sids)) {
              $this->request->session()->put('sids', serialize($sids));
            }
            if (!empty($aids)) {
              $this->request->session()->put('aids', serialize($aids));
            }
            if ($this->request->book_date) {
                $this->request->session()->put('book_date', $this->request->book_date);
                $this->request->session()->put('book_time', $this->request->book_time);
                $this->request->session()->put('reward_points', $this->request->reward_points);
            }
        // }
        if ($this->request->total_amount) {
            $this->request->session()->put('total_amnt', $this->request->total_amount);
        }
        if ($this->request->reward_points) {
            $this->request->session()->put('reward_points', $this->request->reward_points);
        }
        $chk = 'check';
        if (isset($this->request->tab)) {
            if ($this->request->tab == 'payment') {
                $this->request->session()->put('temp_book_id', San_Help::gen_password(4, 6, true, true));
            }
            $chk = $this->request->tab;
        }
        if (isset($this->userId) && ! isset($this->request->tab)) {
            $chk = 'summary';
        }
        return redirect($this->data['locale'] . '/booking/' . $id . '?tab=' . $chk);
    }

    /* Accept Booking */
    function acceptBooking()
    {
        $type = '';
        $status = $this->request->status;
        $proid = $this->request->proid;
        $book = Booking::find($this->request->bookid);
        if ($status == 'Pending') {
            $book->status = 'Confirmed';
            $type = 'booking_accepted';
        }
        if ($status == 'Confirmed') {
            $book->status = 'Completed';
        }
        $book->save();
        if ($type != '') {
            $data_sms = array(
                'type' => $type,
                '_booking_id' => $book->id,
                'pro_id' => $book->salon_id
            );
            San_Help::sanSendSms($data_sms);
            $msg_data['key'] = '';
            $msg_data['_booking_id'] = $book->id;
            $msg_data['sallon_id'] = $book->salon_id;
            /* Send Mail */
            San_Help::send_Email($type, $this->userId, $msg_data);
        }
        $provider = Provider::with('getAvail')->with('getServices')
            ->with('getAssistants')
            ->with('getBookings')
            ->with('getProducts')
            ->find($proid);
        if ($status == 'Pending') {
            $pen_html = View('maskFront::includes.booking_list', [
                'status' => 'Pending',
                'provider' => $provider
            ])->render();
            $con_html = View('maskFront::includes.booking_list', [
                'status' => 'Confirmed',
                'provider' => $provider
            ])->render();
            return response()->json(compact('pen_html', 'con_html'));
        }
        if ($status == 'Confirmed') {
            $con_html = View('maskFront::includes.booking_list', [
                'status' => 'Confirmed',
                'provider' => $provider
            ])->render();
            $com_html = View('maskFront::includes.booking_list', [
                'status' => 'Completed',
                'provider' => $provider
            ])->render();
            return response()->json(compact('con_html', 'com_html'));
        }
        return response()->json('err');
    }

    /* Reject Booking */
    function rejectBooking()
    {
        $type = '';
        $status = $this->request->status;
        $action = $this->request->action;
        $book = Booking::find($this->request->bookid);
        if ($status == 'Pending') {
            $book->status = 'Refused';
            $type = 'booking_rejected';
        }
        if ($status == 'Confirmed') {
            $book->status = 'Canceled';
            $type = 'booking_canceled';
        }
        $book->save();
        if ($type != '') {
            $data_sms = array(
                'type' => $type,
                '_booking_id' => $book->id,
                'pro_id' => $book->salon_id
            );
            San_Help::sanSendSms($data_sms);
            $msg_data['key'] = '';
            $msg_data['_booking_id'] = $book->id;
            $msg_data['sallon_id'] = $book->salon_id;
            /* Send Mail */
            San_Help::send_Email($type, $this->userId, $msg_data);
        }
        if (isset($action) && $action == 'user_action') {
            $user = User::with('getBookings')->with('getRewards')->find($this->request->userid);
            $can_html = View('maskFront::includes.user_bookings', [
                'status' => 'Canceled',
                'user' => $user
            ])->render();
            return response()->json(compact('can_html'));
        }
        return response()->json([
            'status' => 'success'
        ]);
    }

    /* Cancel Order */
    function cancelOrder()
    {
        $type = '';
        $order = Order::find($this->request->orderid);
        $order->order_status = 'cancelled';
        $type = 'order_canceled';
        $order->save();
        if ($type != '') {
            // $data_sms = array(
            //     'type' => $type,
            //     '_booking_id' => $book->id,
            //     'pro_id' => $book->salon_id
            // );
            // San_Help::sanSendSms($data_sms);
            // $msg_data['key'] = '';
            // $msg_data['_booking_id'] = $order->id;
            // $msg_data['sallon_id'] = $order->salon_id;
            // /* Send Mail */
            // San_Help::send_Email($type, $this->userId, $msg_data);
        }
        $user = User::with('orders')->find($this->request->userid);
        $order_html = View('maskFront::includes.order_history', [
            'user' => $user
        ])->render();
        return response()->json(compact('order_html'));
    }

    function payLater($id)
    {
        $msg_data = array();
        if ($this->request->session()->get('booking_type') == 'products') {
            $type = 'new_order';
            $bookid = $this->addOrder($id, 'cash');
            $id = $this->request->session()->get('provider_id');
        } else {
            $bookid = $this->addBooking($id, 'cash');
            $type = 'new_booking';
        }
        if ($bookid == 'not_ok') {
            $this->request->session()->put('message', 'Something Went Wrong');
            $this->request->session()->put('alert-type', 'warning');
            return redirect($this->data['locale'] . '/booking/' . $id);
        }
        $data_sms = array(
            'type' => $type,
            '_booking_id' => $bookid,
            'pro_id' => $id
        );
        San_Help::sanSendSms($data_sms);
        $msg_data['key'] = '';
        $msg_data['_booking_id'] = $bookid;
        $msg_data['sallon_id'] = $id;
        /* Send Mail */
        San_Help::send_NewBookingEmail($type, $id, $bookid);
        San_Help::send_Email($type, $this->userId, array('key'=>Str::random(10)));
        $this->request->session()->put('bookid', $bookid);
        return redirect($this->data['locale'] . '/thankyou/' . $id);
    }

    function payCart()
    {
        $msg_data = array();
        if (Auth::check()) {
        	$cartdata = Cart::with('product')->where('user_id', $this->userId)->get();
	        $proids = array();
        	foreach ($cartdata as $key => $value) {
        		array_push($proids, $value->product->provider_id);
        	}
	        $bookid = $this->addOrder($cartdata->pluck('product_id')->toArray(), 'cash');
	        $data_sms = array(
	            'type' => 'new_order',
	            '_booking_id' => $bookid,
	            'pro_id' => $proids
	        );
	        San_Help::sanSendSms($data_sms);
	        $type = 'new_order';
	        $msg_data['key'] = '';
	        $msg_data['_booking_id'] = $bookid;
	        $msg_data['sallon_id'] = $proids;
	        /* Send Mail */
	        San_Help::send_NewBookingEmail($type, $proids, $bookid);
            San_Help::send_Email($type, $this->userId, array('key'=>''));
	        $this->request->session()->put('bookid', $bookid);
            $this->updateproducts();
	        return redirect($this->data['locale'] . '/thankyou/' . $this->userId);
        }

    }

    function addOrder($id, $method = 'cash')
    {
        $book = new Order();
        $book->order_user_id = $this->userId;
        $total = 0;
        $qty = 0;
        $colors = array();
        if (is_array($id)) {
        	$cartdata = Cart::with('product')->where('user_id', $this->userId)->get();
        	$serilize_arr = array();
        	foreach ($cartdata as $key => $value) {
                $total += $value->price;
                $qty += $value->qty;
                array_push($colors, $value->color);
        		$serilize_arr[$value->product->provider_id][] = $value->product_id;
        	}
        	$book->provider_id = serialize($serilize_arr);
        	$book->product_ids = null;
        }else{
        	$book->provider_id = $this->request->session()->get('provider_id');
        	$book->product_ids = $id;
        }
        // echo '<pre>';print_r($serilize_arr);exit;
        $this->request->session()->put('book_date', date("Y/m/d"));
        $this->request->session()->put('book_time', date('Y/m/d H:i:s'));
        $book->payment_method = $method;
        $book->price = $this->request->session()->get('total_amnt') ? $this->request->session()->get('total_amnt'): $total;
        $book->qty = $this->request->session()->get('product_qty') ? $this->request->session()->get('product_qty') : $qty;
        $book->color = $this->request->session()->get('product_color') ? $this->request->session()->get('product_color') : implode(',', array_unique($colors));;
        $book->status = 0;
        $book->order_status = 'pending';
        $book->order_status = 'pending';
        $book->currency = $this->data['currency'];
        $book->save();
        return $book->id;
    }

    function updateproducts(){
        $cartdata = Cart::with('product')->where('user_id', $this->userId)->get();
        foreach ($cartdata as $keyy => $value) {
            $pro = Product::find($value->product_id);
            if (isset($pro->id)) {
                // if ($value->color) {
                //     $colors = explode(',', $pro->color);
                //     if (in_array($value->color, $colors)) {
                //          if (($key = array_search($value->color, $colors)) !== false) {
                //             unset($colors[$key]);
                //             $pro->color = implode(',', $colors);
                //         }
                //     }
                // }
                if ($value->qty) {
                    $pro->qty = $pro->qty-$value->qty;
                }
                $pro->save();
            }
        }
    }

    function addBooking($id, $method = 'cash')
    {
        $data = $this->request->session()->all();
        // echo '<pre>';print_r($data);exit;
        $condition = $this->checkBooking(array('sids'=>$data['sids'],'date'=>$data['book_date'],'time'=>$data['book_time']));
        if ($condition == 'not_ok') {
            return 'not_ok';
        }
        elseif (is_array($condition)) {
            foreach ($condition as $svalue) {
               if (($key = array_search($svalue, $data['sids'])) !== false) {
                    unset($data['sids'][$key]);
                }
            }
        }
        $user = Auth::user();
        if (! isset($user)) {
            return redirect('/');
        }
        $book = new Booking();
        $book->user_id = $user->id;
        $book->salon_id = $id;
        $book->assistent_ids = implode(',', unserialize($data['aids']));
        $book->service_ids = implode(',', unserialize($data['sids']));
        $book->book_date = $data['book_date'];
        $book->time = $data['book_time'];
        $book->price = $data['total_amnt'];
        $book->pay_method = $method;
        $book->first_name = $user->name;
        $book->last_name = $user->lname;
        $book->email = $user->email;
        $book->phone = $user->phone;
        $book->address = $user->address;
        $book->gender = $user->gender;
        $book->currency = $this->data['currency'];
        $book->type = $data['booking_type'];
        $book->save();
        return $book->id;
    }

    public function payment($id)
    {
        $chk = 'payment';
        return redirect($this->data['locale'] . '/booking/' . $id . '?tab=' . $chk);
    }

    public function checkBooking($data)
    {
        $err = 0;
        $service_ids = unserialize($data['sids']);
        $chk_ids = array();
        foreach ($service_ids as $id) {
            $books = Booking::whereRaw('find_in_set(?, service_ids)', [$id])->where('book_date', $data['date'])->get()->toArray();
            foreach ($books as $bookkey => $bookvalue) {
                if (!in_array($bookvalue['id'], $chk_ids)) {
                    array_push($chk_ids, $bookvalue['id']);
                    $bookings[] = $bookvalue;
                }
            }
        }
        $not_available_services = [];
        $chk_available_services = [];
        $ser_cnt = 0;
        if (!empty($bookings)) {
            foreach ($bookings as $booking) {
                $cnterr = 1;
                $date_arr = explode('/', $booking['book_date']);
                if (! empty($service_idss)) {
                    $servicess = Service::whereIn('id', explode(',', $booking['service_ids']))->get();
                } else {
                    $servicess = Service::whereIn('id', explode(',', $booking['service_ids']))->get();
                }
                $fin_date = $date_arr[2] . '-' . $date_arr[0] . '-' . $date_arr[1];
                if ($data['time'] == $booking['time']) {
                    foreach ($servicess as $service) {
                        array_push($chk_available_services, $service->id);
                        $ser_cntt = array_count_values($chk_available_services);
                        $ser_cnt = $ser_cntt[$service->id];
                        if ($ser_cnt >= $service['per_hour']) {
                            array_push($not_available_services, $service->id);
                        }
                    }
                }
            }
        }
        if (count($service_ids) === count($not_available_services) && end($service_ids) === end($not_available_services)) {
            return 'not_ok';
        }elseif(!empty($not_available_services)){
           return 'not_ok';
        }
        return 'ok';
    }

    public function checkServiceBooking()
    {
        $service_idss = array();
        if ($this->request->providerid != '') {
            $service_idss = Service::where('provider_id', $this->request->providerid)->pluck('id')->toArray();
        }
        $err = 0;
        if (! empty($this->request->service_ids) || ! empty($service_idss)) {
            if (isset($this->request->booking_id) && $this->request->booking_id != '' && ! empty($this->request->booking_id)) {
                $bookings = Booking::whereIn('service_ids', $this->request->service_ids)->where('book_date', $this->request->date)
                    ->whereNotIn('id', [
                    $this->request->booking_id
                ])->get()->toArray();
            } elseif (! empty($this->request->service_ids)) {
                $bookings = Booking::whereIn('service_ids', $this->request->service_ids)->where('book_date', $this->request->date)->get()->toArray();
            } elseif (! empty($service_idss)) {
                $chk_ids = array();
                foreach ($service_idss as $id) {
                    $books = Booking::whereRaw('find_in_set(?, service_ids)', [$id])->where('book_date', $this->request->date)->get()->toArray();
                    foreach ($books as $bookkey => $bookvalue) {
                        if (!in_array($bookvalue['id'], $chk_ids)) {
                            array_push($chk_ids, $bookvalue['id']);
                            $bookings[] = $bookvalue;
                        }
                    }
                }
            }
            $not_available_services = [];
            $chk_available_services = [];
            $ser_cnt = 0;
            if (!empty($bookings)) {
                foreach ($bookings as $booking) {
                    $cnterr = 1;
                    $date_arr = explode('/', $booking['book_date']);
                    if (! empty($service_idss)) {
                        $servicess = Service::whereIn('id', explode(',', $booking['service_ids']))->get();
                    } else {
                        $servicess = Service::whereIn('id', explode(',', $booking['service_ids']))->get();
                    }
                    $fin_date = $date_arr[2] . '-' . $date_arr[0] . '-' . $date_arr[1];
                    if ($this->request->time == $booking['time']) {
                        foreach ($servicess as $service) {
                            array_push($chk_available_services, $service->id);
                            $ser_cntt = array_count_values($chk_available_services);
                            $ser_cnt = $ser_cntt[$service->id];
                            if ($ser_cnt >= $service['per_hour']) {
                                array_push($not_available_services, $service->id);
                            }
                        }
                        $cnterr ++;
                    }
                }
            }
        }
        if (empty($not_available_services)) {
            $err = 1;
        }
        if (! empty($service_idss)) {
            // $parids = Service::whereIn('id',$service_idss)->pluck('parent_service')->toArray();
            // if (!empty($parids)) {
            // $this->data['cats'] = Category::whereIn('id',$parids)->with('getServices')->get();
            // }
            $services = Service::whereIn('id', $service_idss)->whereNotIn('id', $not_available_services)
                ->get()
                ->toArray();
            return response()->json($not_available_services);
        } else {
            $services = Service::whereIn('id', $this->request->service_ids)->whereNotIn('id', $not_available_services)
                ->get()
                ->toArray();
        }
        $assistants = Assistant::all()->toArray();
        foreach ($services as $key => $value) {
            $services[$key]['name'] = San_Help::sanGetLang($value['name'], $this->data['locale']);
            $services[$key]['assistants'] = array();
            foreach ($assistants as $key2 => $assistant) {
                $sids = unserialize($assistant['service_ids']);
                if (is_array($sids)) {
                    if (in_array($value['id'], $sids)) {
                        $services[$key]['assistants'][] = $assistant;
                    }
                }
            }
        }
        // if ($err ==0) {
        return response()->json($services);
        // }else{
        // return response()->json($err);
        // }
    }

    public function dashboard()
    {
        $user = Auth::user();
        if ((isset($user->role_id) && $user->role_id != 2) || ! isset($user->role_id)) {
            return redirect('/');
        }
        $this->data['provider'] = Provider::with('getAvail')->with('getServices')
            ->with('getAssistants')
            ->with('reviews')
            ->with('getBookings')
            ->with('getProducts')
            ->find($user->id);
        // echo "<pre>";print_r($this->data['provider']->getAvail->extra);exit;
        return View('maskFront::pages.dashboard', $this->data);
    }

    public function addTeam()
    {
        $this->request->merge([
            'slug' => 'assistants'
        ]);
        if ($this->request->edit_id && $this->request->edit_id !='') {
            $asst = Assistant::find($this->request->edit_id);
            if ($this->request->image) {
                $asst->image = San_Help::uploadFile();
            }
            $asst->name = $this->request->ass_name;
            $asst->service_ids = serialize($this->request->service_ids);
            $asst->save();
            $msg = 'Updated';
        }else{
            $asst = new Assistant();
            if ($this->request->image) {
                $asst->image = San_Help::uploadFile();
            }
            $asst->name = $this->request->ass_name;
            $asst->provider_id = $this->userId;
            $asst->user_id = 1;
            $asst->service_ids = serialize($this->request->service_ids);
            $asst->save();
            $provider = Provider::find($this->userId);
            if ($provider->assistant_ids != '') {
                $provider->assistant_ids = $provider->assistant_ids . ',' . $asst->id;
            } else {
                $provider->assistant_ids = $asst->id;
            }
            $provider->save();
            $msg = 'Added';
        }
        $this->request->session()->put('message', 'Team Member '.$msg);
        $this->request->session()->put('alert-type', 'success');
        return redirect($this->data['locale'] . '/dashboard');
    }

    public function add_Product()
    {
        $this->request->merge([
            'slug' => 'products'
        ]);
        if ($this->request->edit_id && $this->request->edit_id != '') {
            $pro = Product::find($this->request->edit_id);
            if ($this->request->image) {
                $pro->image = San_Help::uploadFile();
            }
            $pro->name = $this->request->name;
            $pro->category_id = $this->request->category;
            $pro->price = $this->request->price;
            $pro->description = $this->request->description;
            $pro->color = $this->request->color;
            $pro->active = $this->request->active;
            $pro->qty = $this->request->qty;
            $pro->save();
            $msg = 'Updated';
        }else{
            $pro = new Product();
            if ($this->request->image) {
                $pro->image = San_Help::uploadFile();
            }
            $pro->name = $this->request->name;
            $pro->category_id = $this->request->category;
            $pro->price = $this->request->price;
            $pro->description = $this->request->description;
            $pro->color = $this->request->color;
            $pro->active = $this->request->active;
            $pro->qty = $this->request->qty;
            $pro->provider_id = $this->userId;
            $pro->save();
            $msg = 'Added';
        }
        $this->request->session()->put('message', 'Product '.$msg);
        $this->request->session()->put('alert-type', 'success');
        return redirect($this->data['locale'] . '/dashboard?tab=product');
    }

    public function add_Service()
    {
        $this->request->merge([
            'slug' => 'services'
        ]);
        if ($this->request->edit_id && $this->request->edit_id != '') {
            $ser = Service::find($this->request->edit_id);
            if ($this->request->image) {
                $ser->image = San_Help::uploadFile();
            }
            $ser->name = $this->request->name;
            $ser->category_id = $this->request->category_id;
            $ser->price = $this->request->price;
            $ser->description = $this->request->description;
            $ser->per_hour = $this->request->per_hour;
            $ser->duration = $this->request->duration;
            $ser->parent_service = $this->request->parent_service;
            $ser->save();
            $msg = 'Updated';
        }else{
            $ser = new Service();
            if ($this->request->image) {
                $ser->image = San_Help::uploadFile();
            }
            $ser->name = $this->request->name;
            $ser->category_id = $this->request->category_id;
            $ser->price = $this->request->price;
            $ser->description = $this->request->description;
            $ser->per_hour = $this->request->per_hour;
            $ser->duration = $this->request->duration;
            $ser->parent_service = $this->request->parent_service;
            $ser->provider_id = $this->userId;
            $ser->save();
            $provider = Provider::find($this->userId);
            if ($provider->service_ids != '') {
                $provider->service_ids = $provider->service_ids . ',' . $ser->id;
            } else {
                $provider->service_ids = $ser->id;
            }
            $provider->save();
            $msg = 'Added';
        }
        $this->request->session()->put('message', 'Service '.$msg);
        $this->request->session()->put('alert-type', 'success');
        return redirect($this->data['locale'] . '/dashboard?tab=service');
    }

    public function update_Profile()
    {
        // echo "<pre>";print_r($this->request->all());exit;
        $this->request->merge([
            'slug' => 'providers'
        ]);
        $pro = Provider::find($this->userId);
        $user = User::find($this->userId);
        if (isset($this->request->image) && $this->request->image != '') {
            $pro->avatar = San_Help::uploadFile();
        }
        $pro->name = $this->request->name;
        $user->name = $this->request->name;
        $pro->email = $this->request->email;
        $user->email = $this->request->email;
        $pro->phone = $this->request->phone;
        $user->phone = $this->request->phone;
        $pro->address = $this->request->address;
        $pro->save();
        $user->save();

        $avail = Avail::where('provider_id', $this->userId)->first();
        if ($avail) {
            $avail->availability = serialize($this->request->salon_settings['availabilities'][1]);
            $avail->extra = serialize($this->request->extra_features);
            $avail->save();
        }else{
            $avail = new Avail();
            $avail->provider_id = $this->userId;
            $avail->availability = serialize($this->request->salon_settings['availabilities'][1]);
            $avail->extra = serialize($this->request->extra_features);
            $avail->save();
        }
        $this->request->session()->put('message', 'Profile Updated');
        $this->request->session()->put('alert-type', 'success');
        return redirect($this->data['locale'] . '/dashboard?tab=setting');
    }

    public function upload_Image()
    {
        $this->request->merge([
            'slug' => 'providers'
        ]);
        $pro = Provider::find($this->userId);
        if (isset($this->request->image) && $this->request->image != '' && $this->request->type == 'profile') {
            $pro->avatar = San_Help::uploadFile();
        }
        if (isset($this->request->image) && $this->request->image != '' && $this->request->type == 'banner') {
            $pro->banner = San_Help::uploadFile();
        }
        $pro->save();
        $this->request->session()->put('message', 'Image Uploaded');
        $this->request->session()->put('alert-type', 'success');
        return redirect($this->data['locale'] . '/dashboard?tab=setting');
    }

    public function addUpdateImages($id,$type){
        $this->request->merge(['slug' => $type]);
        $uploaded_images = San_Help::uploadFiles();
        foreach ($uploaded_images as $image) {
            $pro = new MultiImage();
            $pro->record_id = $id;
            $pro->filename = $image;
            $pro->type = $type;
            $pro->path = $image;
            $pro->save();
        }
        return redirect($this->data['locale'] . '/dashboard?tab=gallary');
    }

    public function sanRegister()
    {
        if ($this->request->user_type && $this->request->user_type == 'provider') {
            $validator = Validator::make($this->request->all(), [
                'email' => 'required|unique:users|max:100',
                'name' => 'required',
                'membership' => 'required',
                'street_address' => 'required',
                '_type_of_service' => 'required',
                'phone' => 'required|unique:users',
                'duration' => 'required',
                'password' => 'required',
                'user_conrfpassword' => 'required|same:password'
            ]);
        } else {
            $validator = Validator::make($this->request->all(), [
                'email' => 'required|unique:users|max:100',
                'name' => 'required',
                'password' => 'required',
                'user_conrfpassword' => 'required|same:password',
                'phone' => 'required|unique:users'
            ]);
        }
        if ($validator->fails()) {
            $error = $validator->errors()->first();
            $this->request->session()->put('message', $error);
            $this->request->session()->put('alert-type', 'danger');
            if ($this->request->ajax()) {
                return response()->json(array(
                    'message' => $error
                ));
            }else{
                return redirect('/')->with([
                    'message' => $error,
                    'alert-type' => 'danger'
                ]);
            }
        }

        $data = array();
        if ($this->request->name) {
            $data['name'] = $this->request->name;
        }
        if ($this->request->lname) {
            $data['lname'] = $this->request->lname;
        }
        if ($this->request->address) {
            $data['address'] = $this->request->address;
        }
        if ($this->request->email) {
            $data['email'] = $this->request->email;
        }
        if ($this->request->password) {
            $data['password'] = Hash::make($this->request->password);
        }
        if ($this->request->gender) {
            $data['gender'] = $this->request->gender;
        }
        if ($this->request->phone) {
            $data['phone'] = $this->request->phone;
        }
        if ($this->request->country) {
            $data['country'] = $this->request->country;
        }
        if ($this->request->user_type == 'provider') {
            $data['role_id'] = 2;
        } else {
            $data['role_id'] = 3;
        }
        $data['status'] = 2;
        $key = San_Help::gen_password(3, 8, true, true);
        // echo "<pre>";print_r($this->request->all());exit;
        $user = User::create($data);
        if ($this->request->user_type == 'provider') {
            $this->addProvider(array_merge(array(
                'id' => $user->id
            ), $this->request->all()));
        } else {
            $data_reward = array(
                'type' => 'new_register',
                'user_id' => $user->id
            );
            San_Help::updateRewardPoints($data_reward);
        }
        DB::table('user_roles')->insert([
            'user_id' => $user->id,
            'role_id' => $data['role_id']
        ]);
        /* Send Email */
        $login_detail = new \stdClass();
        $login_detail->name = $user->name;
        $login_detail->locale = $this->data['locale'];
        $login_detail->user_id = $user->id;
        $login_detail->key = $key;
        $login_detail->password = $this->request->password;
        $login_detail->from = 'digittrix@gmail.com';
        $login_detail->to = $user->email;
        // $login_detail->to = 'sandeep.digittrix@gmail.com';
        if ($this->request->user_type && $this->request->user_type == 'provider') {
            $login_detail->subject = San_Help::email_subjects('user_register');
        } else {
            $login_detail->subject = San_Help::email_subjects('simpleuser_register');
        }
        San_Help::sanSendMail('maskFront::emails.user_register', $login_detail);
        if ($this->request->user_type && $this->request->user_type == 'provider') {
            $adtemp = 'new_provider';
            $login_detail->subject = San_Help::email_subjects('adminuser_register');
        } else {
            $adtemp = 'admin_user_register';
            $login_detail->subject = San_Help::email_subjects('adminsimpleuser_register');
        }
        San_Help::sanSendMail('maskFront::emails.' . $adtemp, $login_detail);
        /* End Email section */
        if (isset($this->request->booking_type) && $this->request->booking_type == 'service') {
            $credentials = $this->request->only('email', 'password');
            Auth::attempt($credentials);
            if ($this->request->ajax()) {
                return response()->json(url($this->data['locale'] . '/booking/' . $this->request->pro_id . '?tab=summary'));
            }
            return redirect($this->data['locale'] . '/booking/' . $this->request->pro_id . '?tab=summary');
        }
        if (isset($this->request->booking_type) && $this->request->booking_type == 'product') {
            $credentials = $this->request->only('email', 'password');
            Auth::attempt($credentials);
            if ($this->request->ajax()) {
                return response()->json(url($this->data['locale'] . '/postbook/' . $this->request->pro_id . '?tab=summary'));
            }
            return redirect($this->data['locale'] . '/postbook/' . $this->request->pro_id . '?tab=summary');
        }
        if ($this->request->ajax()) {
            return response()->json(url()->previous());
        }
        return redirect($this->data['locale'] . '/')->with('message', 'Registered Successfully');
    }

    public function addProvider($data)
    {
        $pro = new Provider();
        $pro->id = $data['id'];
        $pro->name = $data['name'];
        $pro->branch_name = $data['name'];
        $pro->email = $data['email'];
        $pro->phone = $data['phone'];
        $pro->duration = $data['duration'];
        $pro->city_country = $data['city_country'];
        $pro->latitude = $data['latitude'];
        $pro->longitude = $data['longitude'];
        $pro->address = $data['street_address'];
        $pro->membership = $data['membership'];
        $pro->fixed_service = $data['_type_of_service'];
        $pro->status = 0;
        $pro->save();
    }

    function updateAccount()
    {
        $user = User::find($this->request->user_id);
        if (isset($this->request->image) && $this->request->image != '') {
            $this->request->merge([
                'slug' => 'users'
            ]);
            $user->avatar = San_Help::uploadFile();
        }
        if (isset($this->request->name)) {
            $user->name = $this->request->name;
        }
        if (isset($this->request->lname)) {
            $user->lname = $this->request->lname;
        }
        if (isset($this->request->email)) {
            $user->email = $this->request->email;
        }
        if (isset($this->request->dob)) {
            $user->dob = $this->request->dob;
        }
        if (isset($this->request->gender)) {
            $user->gender = $this->request->gender;
        }
        if (isset($this->request->phone)) {
            $user->phone = $this->request->phone;
        }
        if (isset($this->request->password) && $this->request->password != '' && trim($this->request->password) == trim($this->request->confpassword)) {
            $user->password = Hash::make($this->request->password);
        }
        $user->save();
        $result['res'] = 1;
        $information = San_Help::sanLang('Information Updated Successfully');
        $result['msg'] = $information;
        return response()->json($result);
    }

    public function memLogin()
    {
        $credentials = $this->request->only('email', 'password');
        $action = $this->request->route()->getAction();
        // && Auth::attempt($credentials)
        $chkuser = User::where('email', $this->request->email)->where('status', 2)->first();
        if (! $chkuser || empty($chkuser)) {
            if ($this->request->ajax()) {
                return response()->json(array(
                    'message' => 'Email Or Password is not Correct'
                ));
            }
            return redirect()->intended(url()->previous());
        }
        if (isset($this->request->booking_type) && $this->request->booking_type == 'product') {
            if (Auth::attempt($credentials)) {
                if ($this->request->ajax()) {
                    return response()->json(url($this->data['locale'] . '/postbook/' . $this->request->pro_id . '?tab=summary'));
                }
                return redirect($this->data['locale'] . '/postbook/' . $this->request->pro_id . '?tab=summary');
            } else {
                return response()->json(array(
                    'message' => 'Email Or Password is not Correct'
                ));
            }
        }

        if (isset($action['login_type']) && $action['login_type'] == 'check' && Auth::attempt($credentials)) {
            if (Auth::attempt($credentials)) {
                if ($this->request->ajax()) {
                    return response()->json(url($this->data['locale'] . '/booking/' . $this->request->pro_id . '?tab=summary'));
                }
                return redirect($this->data['locale'] . '/booking/' . $this->request->pro_id . '?tab=summary');
            } else {
                return response()->json(array(
                    'message' => 'Email Or Password is not Correct'
                ));
            }
        } else {
            if (Auth::attempt($credentials)) {
                if ($this->request->ajax()) {
                    if (Auth::user()->role_id == 1) {
                        return response()->json(url($this->data['locale'] . '/'));
                    }elseif (Auth::user()->role_id == 2) {
                        if (url()->previous() == \URL::to($this->data['locale'])) {
                            $uri = url($this->data['locale'] . '/dashboard');
                        } else {
                            $uri = url()->previous();
                        }
                        return response()->json($uri);
                    }elseif (Auth::user()->role_id == 3) {
                        if (url()->previous() == \URL::to($this->data['locale'])) {
                            $uri = url($this->data['locale'] . '/search?type=services&cust_lat=&cust_long=&sr=&wr=');
                        } else {
                            $uri = url()->previous();
                        }
                        return response()->json($uri);die();
                    }else{
                    	return response()->json(array(
		                    'message' => 'Role Not Defined'
		                ));
                    }
                } else {
                    if (Auth::user()->role_id == 1) {
                        return redirect()->intended($this->data['locale'] . '/');
                    }
                    if (Auth::user()->role_id == 2) {
                        if (url()->previous() == \URL::to($this->data['locale'])) {
                            $uri = $this->data['locale'] . '/dashboard';
                        } else {
                            $uri = url()->previous();
                        }
                        return redirect()->intended($uri);
                    }
                    if (Auth::user()->role_id == 3) {
                        if (url()->previous() == \URL::to($this->data['locale'])) {
                            $uri = $this->data['locale'] . '/search?type=services&cust_lat=&cust_long=&sr=&wr=';
                        } else {
                            $uri = url()->previous();
                        }
                        return redirect()->intended($uri);
                    }
                }
            } else {
                if ($this->request->ajax()) {
                    return response()->json(array(
                        'message' => 'Email Or Password is not Correct'
                    ));
                } else {
                    $this->request->session()->put('message', 'Email Or Password is not Correct');
                    $this->request->session()->put('alert-type', 'danger');
                    return redirect()->intended('/');
                }
            }
        }
        return redirect($this->data['locale'] . '/');
    }

    public function memLogout()
    {
        Auth::logout();
        return redirect()->intended($this->data['locale'] . '/');
    }

    public function SanGetSallons($limit = '', $serid = '')
    {
        if ($limit) {
            $pro = Provider::orderBy('id', 'desc')->where('status', 2)->take($limit);
            if ($serid) {
                $pro->where('service_ids', '!=', '');
            }
            return $pro->get();
        }
        if ($serid) {
            return Provider::where('service_ids', '!=', '')->where('status', 2)->get();
        } else {
            return Provider::where('status', 2)->get();
        }
    }

    public function SanGetCategories($limit = '', $type = 'category')
    {
        if ($limit) {
            return Category::orderBy('id', 'desc')->whereNull('parent_id')
                ->where('type', $type)
                ->take($limit)
                ->get();
        }
        return Category::whereNull('parent_id')->where('type', $type)->get();
    }

    public function SanGetSerCategories($limit = '', $type = 'category')
    {
        if ($limit) {
            return Category::orderBy('id', 'desc')->whereNotNull('parent_id')
                ->where('type', $type)
                ->take($limit)
                ->get();
        }
        return Category::orderBy('id', 'desc')->whereNotNull('parent_id')
            ->where('type', $type)
            ->get();
    }

    public function SanGetServices($limit = '')
    {
        if ($limit) {
            return Category::orderBy('id', 'desc')->whereNotNull('parent_id')
                ->take($limit)
                ->get();
        }
        return Category::whereNotNull('parent_id')->get();
    }

    public function handleCatSearch()
    {
        $this->data['type'] = 'services';
        $this->data['services'] = Category::has('getServices')->whereNotNull('parent_id')
            ->where('type', 'category')
            ->get();
        $this->data['categories'] = Category::has('getServices')->whereNotNull('parent_id')
            ->where('type', 'category')
            ->get();
        $this->data['sallons'] = Provider::has('getServices')->orderBy('id', 'desc')
            ->where('status', 2)
            ->where('type', $this->data['cat'])
            ->get();
        return View('maskFront::pages.search', $this->data);
    }
    public function handleProductSearch()
    {
         return redirect($this->data['locale'].'/search?type=services&wr=&pr='.$this->data['cat']);
    }

    public function getRelatedData($data)
    {
        $assistants = array();
        $services = array();
        $availability = array();
        if (isset($data->asst_ids)) {
            $assistants = Assistant::whereIn('id', explode(',', $data->asst_ids))->get();
        }
        if (isset($data->service_ids)) {
            $services = Service::whereIn('id', explode(',', $data->service_ids))->get();
        }
        if (isset($data->provider_id)) {
            $availability = Avail::where('provider_id', $data->provider_id)->first();
            // print_r(unserialize($availability->availability));exit;
        }
        return array(
            'assistants' => $assistants,
            'services' => $services,
            'availability' => unserialize($availability->availability)
        );
    }

    /* Ajax function to redeem Reward Points on checkout page */
    function ajax_redeem_point()
    {
        $c_action = $this->request->c_action;
        $data_diamonds = $this->request->data_diamonds;
        $user_id = $this->request->user_id;
        $data_total = $this->request->data_total;
        if ($c_action == 'apply') {
            if (! empty($data_diamonds) && $data_diamonds > 375) {
                $mult = floor($data_diamonds / 375);
                $redeem_sar = $mult * 75;
                $redeemed_points = $mult * 375;
                if ($redeem_sar < $data_total) {} else {
                    $check_for_red = 0;
                    for ($i = $mult; $i >= 1; $i --) {
                        $redeem_sar_ch = $i * 75;
                        if ($redeem_sar_ch < $data_total && $check_for_red == 0) {
                            $redeem_sar = $redeem_sar_ch;
                            $redeemed_points = $i * 375;
                            $check_for_red = 1;
                        }
                    }
                }
                $res['redeemed_points'] = $redeemed_points;
                $res['redeem_sar'] = $redeem_sar;
                $res['grand_total'] = $data_total;
                $res['total_after_redeem'] = ($data_total - $redeem_sar);

                $_SESSION['SLN_Wrapper_Booking_Builder']['discount'] = array();

                $_SESSION['redeem_points'] = $res;

                $result['msg'] = "Jewelries redeemed succesfully";
                $result['status'] = "success";
                $result['total_after_redeem'] = $res['total_after_redeem'];
                $result['redeem_sar'] = $res['redeem_sar'];
            } else {
                $result['msg'] = "You don't not sufficent Jewelries balance";
                $result['status'] = "failure";
            }
        } elseif ($c_action == 'remove') {
            $_SESSION['redeem_points'] = array();
            $result['msg'] = "Jewelries redeemption removed succesfully";
            $result['status'] = "success";
            $result['total_after_redeem'] = $data_total;
            $result['redeem_sar'] = '0';
        }
        return response()->json($result);
    }

    function sanApplyCoupon()
    {
        $remove = 0;
        if (isset($this->request->remove) && $this->request->remove == 1) {
            $remove = 1;
        }
        if (isset($this->request->pro_id) && isset($this->request->code)) {
            $offers = \TCG\Voyager\Models\Offer::where('pro_id', $this->request->pro_id)->get();
            $offer_id = '';
            foreach ($offers as $key => $offer) {
                $startarr = explode('-', $offer->valid_from);
                $endarr = explode('-', $offer->valid_to);
                $paymentDate = date('Y-m-d');
                $paymentDate = date('Y-m-d', strtotime($paymentDate));
                // echo $paymentDate; // echos today! y-m-d m/d/Y
                $contractDateBegin = date('Y-m-d', strtotime($startarr['1'] . '/' . $startarr['2'] . '/' . $startarr['0']));
                $contractDateEnd = date('Y-m-d', strtotime($endarr['1'] . '/' . $endarr['2'] . '/' . $endarr['0']));
                if (($paymentDate >= $contractDateBegin) && ($paymentDate <= $contractDateEnd)) {
                    if (trim($this->request->code) === trim($offer->code) && $offer->usage <= $offer->max_use) {
                        $offer_id = $offer->id;
                        break;
                    }
                } elseif($paymentDate > $contractDateEnd) {
                    $ret = array(
                        'success' => 1,
                        'discount' => 0,
                        'code' => $this->request->code,
                        'errors' => array(
                            __('coupon date expire')
                        )
                    );
                    break;
                }
            }
            if ($offer_id != '') {
                $ofr = \TCG\Voyager\Models\Offer::find($offer_id);
                if ($remove == 1) {
                    $ofr->usage = $ofr->usage - 1;
                } else {
                    $ofr->usage = $ofr->usage + 1;
                }
                $ofr->save();
                if ($remove == 1) {
                    $ret = array(
                        'success' => 2,
                        'discount' => $ofr->amount,
                        'code' => $this->request->code,
                        'errors' => array(
                            __('Coupon Removed Successfully')
                        )
                    );
                } else {
                    $ret = array(
                        'success' => 1,
                        'discount' => $ofr->amount,
                        'code' => $this->request->code,
                        'errors' => array(
                            __('Coupon was applied')
                        )
                    );
                }
                return response()->json($ret);
            }
            $ret = array(
                'success' => 2,
                'discount' => 0,
                'errors' => array(
                    __('Coupon is not valid')
                )
            );
            return response()->json($ret);
        }
    }

    function helpMessage(){
        $validator = Validator::make($this->request->all(), [
            'name' => 'required',
            'email' => 'required',
            'message' => 'required'
        ]);
        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response()->json([
                'status' => 'failure',
                'detail' => $error
            ]);
        }
        $data = new \stdClass();
        $data->name = $this->request->name;
        $data->message = $this->request->message;
        $data->to = 'sandeep.digittrix@gmail.com';
        $data->from = $this->request->email;
        $data->subject = 'Help Message';
        \Mail::send('maskFront::emails.support', ['data' => $data], function ($m) use ($data) {
          $m->from($data->from, $data->name);
          $m->to($data->to, 'Mask Admin')->subject($data->subject);
        });
       $this->request->session()->put('message', 'Message Sent,Helpdesk will contact you soon.');
       $this->request->session()->put('alert-type', 'success');
       return redirect(url()->full());
    }

   function forgotPwd(){
        $validator = Validator::make($this->request->all(), [
            'email' => 'required|exists:users|max:100'
        ]);
        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response()->json([
                'status' => 'failure',
                'detail' => $error
            ]);
        }
        /* Send Mail */
        San_Help::send_Email('forgot_password', User::where('email',$this->request->email)->first()->id, array('key'=>Str::random(10)));
        $this->request->session()->put('message', 'Mail Sent,Please check your mail.');
        $this->request->session()->put('alert-type', 'success');
        return redirect()->intended(url()->previous());
    }

    function updatePwd(){
        $messages = [
            'same'    => San_Help::sanGetLang('password mismatch', $this->data['locale'])
        ];
        $validator = Validator::make($this->request->all(), [
            'password'         => 'required',
            'password_confirm' => 'required|same:password',
            'user_id' => 'required'
        ],$messages);
        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response()->json([
                'res' => 0,
                'msg' => $error
            ]);
        }
        $user = User::find($this->request->user_id);
        $user->password = Hash::make($this->request->password);
        $user->save();
        return response()->json(array('res' => 1,'msg' => San_Help::sanGetLang('password update', $this->data['locale'])));
    }

    function proceesRoute()
    {
        if (! isset($this->request->r)) {
            die('Page Not Found!');
        }
        if(isset($this->userId)){
            $user = User::find($this->userId);
            $sids = $this->request->session()->get('sids');
            $ids = unserialize($sids);
            $serarray = Service::whereIn('id',$ids)->pluck('name')->toArray();
            $objFort = new \San_Payfort();
            $objFort->language = $this->data['locale'];
            $objFort->booking_id = $this->request->session()->get('temp_book_id');
            $objFort->amount = $this->request->session()->get('total_amnt');
            $objFort->itemName = $serarray ? implode(',',$serarray) : '';
            // echo "<pre>";print_r($this->request->session()->all());exit;
            $objFort->customerEmail = $user->email;
            $objFort->customername = $user->name;
            $objFort->sallon_link = url($this->data['locale'] . '/thankyou/' . $this->request->session()->get('pro_id'));
            if ($this->request->r == 'getPaymentPage') {
                $objFort->processRequest($this->request->paymentMethod);
            } elseif ($this->request->r == 'merchantPageReturn') {
                $objFort->processMerchantPageResponse();
            } elseif ($this->request->r == 'processResponse') {
                $objFort->processResponse();
            } else {
                echo 'Page Not Found!';
                exit();
            }
        }else{
            die('Page Not Found!');
        }
        
    }
}
