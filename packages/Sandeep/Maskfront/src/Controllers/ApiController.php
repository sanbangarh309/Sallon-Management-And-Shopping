<?php
namespace Sandeep\Maskfront\Controllers;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Auth;
use App\User;
use San_Help;
use TCG\Voyager\Models\Provider;
use TCG\Voyager\Models\Order;
use TCG\Voyager\Models\Service;
use TCG\Voyager\Models\Assistant;
use TCG\Voyager\Models\Booking;
use TCG\Voyager\Models\Review;
use TCG\Voyager\Models\Offer;
use TCG\Voyager\Models\Avail;
use TCG\Voyager\Models\Cart;
use TCG\Voyager\Models\Reward;
use TCG\Voyager\Models\Category;
use TCG\Voyager\Models\Product;
use TCG\Voyager\Models\MultiImage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Validator;

class ApiController extends Controller
{

  protected $services;

  protected $categories;

  protected $fixed_cats;

  protected $data;

  protected $locale;

  protected $actions;

  protected $currency;

  public function __construct()
  {
    $this->data = array();
    $this->request = app('request');
    $this->actions = $this->request->route()->getAction();
    // echo San_Help::sanLang('Are you sure');exit;
    $this->nav_fixed_cats = config('maskfront.fixed_cats');
    $this->lng = $this->request->lng;
    if (! isset($this->lng)) {
      $this->lng = 'en';
    }
    $this->currency = $this->request->currency;
    if (! isset($this->currency)) {
      $this->currency = 'SAR';
    }
    // echo San_Help::get_file('categories/October2018/dQYwLrcAaHyYFiud7PTX.png');exit;
  }

  public function Login()
  {
    $validator = Validator::make($this->request->all(), [
      'email' => 'required|string|email|max:255',
      'password' => 'required'
    ]);
    if ($validator->fails()) {
      $error = $validator->errors()->first();
      return response()->json([
        'status' => 'failure',
        'detail' => $error
      ]);
    }
    // $this->updateBooking();
    $credentials = $this->request->only('email', 'password');
    $action = $this->request->route()->getAction();
    if (Auth::attempt($credentials)) {
      if ($this->request->device_type && $this->request->device_token) {
        $user = User::find(Auth::user()->id);
        $user->device_type = $this->request->device_type;
        $user->device_token = $this->request->device_token;
        $user->save();
      }
      return response()->json([
        'status' => 'success',
        'detail' => Auth::user(),
        'status_code' => 200
      ], 200);
    } else {
      return response()->json([
        'status' => 'failure',
        'detail' => 'Email or Password Incorrect'
      ]);
    }
  }

  function Register()
  {
    try {
      if (isset($this->actions['reg_type']) && $this->actions['reg_type'] == 'user') {
        $validator = Validator::make($this->request->all(), [
          'email' => 'required|unique:users|max:100',
          'username' => 'required',
          'password' => 'required',
          'contact_number' => 'required',
          'confirm_password' => 'required'
        ]);
      } else {
        $validator = Validator::make($this->request->all(), [
          'email' => 'required|unique:users|max:100',
          'username' => 'required',
          'password' => 'required',
          'confirm_password' => 'required',
          'street_address' => 'required',
          '_type_of_service' => 'required',
          '_duration' => 'required',
          'manager_name' => 'required',
          'membership_plan' => 'required',
          'contact_number' => 'required'
        ]);
      }
      if ($validator->fails()) {
        $error = $validator->errors()->first();
        return response()->json([
          'status' => 'failure',
          'detail' => $error
        ]);
      }
      if (trim($this->request->password) !== trim($this->request->confirm_password)) {
        return response()->json([
          'status' => 'failure',
          'detail' => San_Help::sanLang('password mismatch', $this->lng)
        ]);
      }
      $data = array();
      $str = trim($this->request->contact_number);
      $contactno = trim($this->request->contact_number);
      $substr = "+";
      if (strpos($str, $substr) === 0) {} else {
        $contactno = '+' . $contactno;
      }
      if ($this->request->username) {
        $data['name'] = $this->request->username;
      }
      if ($this->request->street_address) {
        $data['address'] = $this->request->street_address;
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
        $data['phone'] = $contactno;
      }
      if ($this->request->country) {
        $data['country'] = $this->request->country;
      }
      if ($this->request->_reg_source) {
        $data['source'] = $this->request->_reg_source;
      }
      if (isset($this->actions['reg_type']) && $this->actions['reg_type'] == 'provider') {
        $data['role_id'] = 2;
      } else {
        $data['role_id'] = 3;
      }
      $data['status'] = 2;
      $user = User::create($data);
      if (isset($this->actions['reg_type']) && $this->actions['reg_type'] == 'provider') {
        $provider = $this->addProvider(array_merge(array(
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
      $data_sms = array(
        'type' => 'new_register',
        'id' => $user->id,
        'contact_number' => trim($contactno),
        'otp' => mt_rand(100000, 999999)
      );
      San_Help::sanSendSms($data_sms);
      return response()->json([
        'status' => 'success',
        'user_id' => $user->id,
        'detail' => $user,
        'status_code' => 200
      ], 200);
    } catch (\Illuminate\Validation\ValidationException $e) {
      return response()->json([
        'status' => 'failure',
        'detail' => 'Invalid data passed',
        'status_code' => $e->status
      ], 422);
    }
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
    if (isset($this->request->phone)) {
      $user->phone = $this->request->phone;
    }
    $user->save();
    return response()->json([
      'status' => 'success',
      'detail' => San_Help::sanLang('Information Updated Successfully')
    ], 200);
  }

  function maskServices()
  {
    $userid = $this->request->userid;
    $rewardpoint_balance = 0;
    $sallon = Provider::has('getServices')->with('getAvail')
    ->with('reviews')
    ->where('status', 2);
    if (! empty($userid)) {
      $rewardpoint_balance = User::find($userid)->rewardpoint_balance;
    }
    if ($this->request->has('mask_category_id') && $this->request->mask_category_id != '') {
      $data = $sallon->where('type', $this->request->mask_category_id)->get();
    }else{
      $data = $sallon->get();
    }
    if (isset($this->request->cust_lat) && $this->request->cust_lat != '' && isset($this->request->cust_long) && $this->request->cust_long != '') {
      $lat = '';
      $lng = '';
      foreach ($data as $key => $dat) {
        if ($dat->latitude != '' && $dat->longitude != '') {
          $lat = $dat->latitude;
          $lng = $dat->longitude;
        } else {
          $coords = San_Help::get_Coordinates($dat->address);
          $lat = $coords['lat'];
          $lng = $coords['long'];
        }
        $baseloc['Lat'] = $this->request->cust_lat;
        $baseloc['Lon'] = $this->request->cust_long;
        $sallon_loc['Lat'] = $lat;
        $sallon_loc['Lon'] = $lng;
        $distance = number_format((float) San_Help::distance_btwn_loc($baseloc, $sallon_loc), 2, '.', '');
        if ($dat->getAvail && $dat->getAvail->extra != '') {
          $sallon_arr2['extra_feature'] = unserialize($dat->getAvail->extra);
          $dat->unsetRelation('getAvail');
        }
        $sallon_arr2['distance'] = $distance;
        $sallon_arr2['rating'] = $dat->reviews->avg('rating');
        $favourtes = User::whereNotNull('favourite')->get();
        $count_fav = 0;
        foreach ($favourtes as $value) {
          $favs = unserialize($value->favourite);
          if (in_array($dat->id, $favs)) {
            $count_fav ++;
          }
        }
        $sallon_arr2['fav_counts'] = (string) $count_fav;
        $sallon_arr2['share_count'] = (string) $count_fav;
        $sallon_arr[] = San_Help::sanReplaceNull(array_merge($dat->toArray(), $sallon_arr2));
      }
      if (isset($sallon_arr) && is_array($sallon_arr)) {
        usort($sallon_arr, function ($a, $b) {
          if ($a["distance"] == $b["distance"])
          return 0;
          return ($a["distance"] < $b["distance"]) ? - 1 : 1;
        });
      }
    } else {
      $sallon_arr = $data->toArray();
      foreach ($sallon_arr as $keyy => $dat_vall) {
        $sallon_arr[$keyy] = San_Help::sanReplaceNull($dat_vall);
      }
    }
    return response()->json([
      'status' => 'success',
      'rewardpoint' => $rewardpoint_balance,
      'saloons' => isset($sallon_arr) ? $sallon_arr : array()
    ], 200);
    // } else {
    //     $data = $sallon->get();
    // foreach ($data as $key => $dat_val) {

    // }
    //     return response()->json([
    //         'status' => 'success',
    //         'rewardpoint' => $rewardpoint_balance,
    //         'saloons' => $data
    //             ->toArray()
    //     ], 200);
    // }
  }

  public function addProvider($data)
  {
    $pro = new Provider();
    $pro->id = $data['id'];
    $pro->name = $data['username'];
    $pro->branch_name = $data['manager_name'];

    $pro->email = $data['email'];
    if (isset($data['phone'])) {
      $pro->phone = $data['phone'];
    }
    $pro->duration = $data['_duration'];
    if (isset($data['city_country'])) {
      $pro->city_country = $data['city_country'];
    }
    if (isset($data['latitude'])) {
      $pro->latitude = $data['latitude'];
    }
    if (isset($data['longitude'])) {
      $pro->longitude = $data['longitude'];
    }
    $pro->address = $data['street_address'];
    $pro->fixed_service = $data['_type_of_service'];
    $pro->membership = $data['membership_plan'];
    $pro->status = 2;
    $pro->save();
    return $pro;
  }

  /* Change Password */
  public function changePassword()
  {
    $validator = Validator::make($this->request->all(), [
      'userid' => 'required|integer|exists:users,id',
      'old_password' => 'required',
      'new_password' => 'required'
    ]);
    if ($validator->fails()) {
      $error = $validator->errors()->first();
      return response()->json([
        'status' => 'failure',
        'detail' => $error
      ]);
    }
    $user = User::find($this->request->userid);
    if (Hash::check($this->request->old_password, $user->password)) {
      $user->password = Hash::make($this->request->new_password);
      $user->save();
      return response()->json([
        'status' => 'success',
        'detail' => San_Help::sanLang('Password Updated', $this->lng),
        'status_code' => 200
      ], 200);
    } else {
      return response()->json([
        'status' => 'failure',
        'detail' => San_Help::sanLang('password mismatch', $this->lng),
        'status_code' => 422
      ], 422);
    }
  }

  public function getProvider()
  {
    $validator = Validator::make($this->request->all(), [
      'userid' => 'required|integer|exists:providers,id'
    ]);
    if ($validator->fails()) {
      $error = $validator->errors()->first();
      return response()->json([
        'status' => 'failure',
        'detail' => $error
      ]);
    }
    $provider = Provider::with('getServices')
    ->with('getAssistants')
    ->with('getProducts')
    ->with('provider_images')
    ->find($this->request->userid)
    ->toArray();
    if (!empty($provider['get_services'])) {
      foreach ($provider['get_services'] as $key => $value) {
        $value['price'] = San_Help::moneyApi($value['price'],$this->currency);
        $provider['get_services'][$key] = San_Help::sanReplaceNull($value);
      }
    }
    if (!empty($provider['get_avail'])) {
      foreach ($provider['get_avail'] as $keyy => $values) {
        $provider['get_avail'][$keyy] = San_Help::sanReplaceNull($values);
      }
    }
    $provider = San_Help::sanReplaceNull($provider);
    return response()->json([
      'status' => 'success',
      'detail' => $provider,
      'status_code' => 200
    ], 200);
  }

  public function getBooking()
  {
    $validator = Validator::make($this->request->all(), [
      'saloon_id' => 'required|integer|exists:providers,id',
      'booking_status' => 'required'
    ]);
    if ($validator->fails()) {
      $error = $validator->errors()->first();
      return response()->json([
        'status' => 'failure',
        'detail' => $error
      ]);
    }
    $status = $this->request->booking_status;
    if ($status != 'Completed') {
      $bookings = User::with(['getSalonBookings' => function($booking) use($status) {
        return $booking->where('status',$status);
      }])->with('salon_orders')
      ->find($this->request->saloon_id)
      ->toArray();
    }else{
      $bookings = User::with('getSalonBookings')->with('salon_orders')
      ->find($this->request->saloon_id)
      ->toArray();
    }
    $book_final_arr = array();
    foreach ($bookings['get_salon_bookings'] as $key => $booking) {
      $bdat = strtotime($booking['book_date']);
      $booking['org_price'] = $booking['price'];
      $booking['price'] = San_Help::moneyApi($booking['price'],$this->currency);

      $today = date('Y-m-d');
      $tdate = strtotime($today);
      if ($status == 'Completed' && ($bdat < $tdate)) {
        $book_final_arr[] = San_Help::sanReplaceNull(array_merge($booking,$this->getbookingbyDate($booking)));
      }elseif ($bdat >= $tdate && $status != 'Completed') {
        $book_final_arr[] = San_Help::sanReplaceNull(array_merge($booking,$this->getbookingbyDate($booking)));
      }
    }
    // $bookings = San_Help::sanReplaceNull($bookings);
    if ($book_final_arr) {
      return response()->json([
        'status' => 'success',
        'detail' => $book_final_arr,
        'status_code' => 200
      ], 200);
    } else {
      return response()->json([
        'status' => 'failure',
        'detail' => 'Bookings Not Found'
      ]);
    }
  }

  function getbookingbyDate($booking){
    $new_arr = array();
    // $new_arr = San_Help::sanReplaceNull($booking);
    if ($booking['service_ids'] != '') {
      $services = Service::whereIn('id', explode(',', $booking['service_ids']))->pluck('name')->toArray();
      foreach ($services as $ser_key => $ser_val) {
        $services[$ser_key] = San_Help::sanGetLang($ser_val, $this->lng);
      }
      // $bookings[$key]['services'] = $services;
      $new_arr['services'] = implode(',', $services);
      // $bookings->getSalonBookings[$key]['user_image'] = $booking->user_id ? User::find($booking->user_id)->avatar : '';
      // $bookings->getSalonBookings[$key]['rating'] = !$booking->reviews->isEmpty() ? $booking->reviews->avg('rating') : 0;
    } else {
      $new_arr['services'] = [];
    }
    if ($booking['assistent_ids'] != '') {
      $assistants = Assistant::whereIn('id', explode(',', $booking['assistent_ids']))->pluck('name')->toArray();
      $new_arr['assistants'] = implode(',', $assistants);
    } else {
      $new_arr['assistants'] = [];
    }
    $new_arr['user_image'] = User::find($booking['user_id']) ? User::find($booking['user_id'])->avatar : '';
    $review = Review::where('type', 'booking')->where('record_id', $booking['id'])->first();
    $new_arr['rating'] = $review ? $review->rating : 0;
    $new_arr['comment'] = $review ? array('id'=>$review->id,'review'=>$review->review,'date'=>Carbon::parse($review->created_at)->format('d-m-Y H:i')) : array();
    return $new_arr;
  }

  function updateBooking(){
    $bookings = Booking::where('status','!=','Completed')->where('status','!=','NOVISIT')->where('status','!=','Canceled')->get();
    foreach ($bookings as $key => $booking) {
      $bdat = strtotime($booking->book_date);
      $today = date('Y-m-d');
      $tdate = strtotime($today);
      if($bdat < $tdate){
        $booking->status = 'Completed';
        $booking->save();
      }
    }
  }

  public function update_Profile()
  {
    $validator = Validator::make($this->request->all(), [
      'mask_sallon_id' => 'required|integer|exists:providers,id'
    ]);
    if ($validator->fails()) {
      $error = $validator->errors()->first();
      return response()->json([
        'status' => 'failure',
        'detail' => $error
      ]);
    }
    $pro = Provider::find($this->request->mask_sallon_id);
    if ($this->request->image && $this->request->image != '') {
      $this->request->merge([
        'slug' => 'providers'
      ]);
      $pro->banner = San_Help::uploadFile();
    }
    if ($this->request->profile_image && $this->request->profile_image != '') {
      $this->request->merge([
        'slug' => 'providers'
      ]);
      $pro->avatar = San_Help::uploadAnyFile();
    }
    if ($this->request->content && $this->request->content != '') {
      $pro->description = $this->request->content;
    }
    $pro->save();
    return response()->json([
      'status' => 'success',
      'detail' => 'updated sucessfully',
      'data' => $pro,
      'status_code' => 200
    ], 200);
  }

  /* Accept Booking */
  function doactionBooking()
  {
    $validator = Validator::make($this->request->all(), [
      'booking_id' => 'required|integer|exists:bookings,id'
    ]);
    if ($validator->fails()) {
      $error = $validator->errors()->first();
      return response()->json([
        'status' => 'failure',
        'detail' => $error
      ]);
    }
    $type = '';
    $status = $this->request->status;
    if (!$status) {
      $status = 'Confirmed';
    }
    if (isset($this->actions['status'])) {
      $status = $this->actions['status'];
    }
    $book = Booking::find($this->request->booking_id);
    if ($book) {
      if ($this->request->has('user_email')) {
        $userid = User::where('email',$this->request->user_email)->first()->id;
      }else{
        $userid = User::find($book->user_id)->id;
      }
      if ($status == 'Completed') {
        $book->status = 'Completed';
      }
      if ($status == 'Confirmed') {
        $book->status = 'Confirmed';
        $type = 'booking_accepted';
      }
      if ($status == 'Canceled') {
        $book->status = 'Canceled';
        $type = 'booking_canceled';
        $userid = $book->salon_id;
      }
      if ($status == 'Rejected') {
        $book->status = 'Canceled';
        $type = 'booking_rejected';
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
        San_Help::send_Email($type, $userid, $msg_data);
      }
    }

    return response()->json([
      'status' => 'success',
      'detail' => San_Help::sanLang('Booking ' . $status . ' successfully', $this->lng),
      'status_code' => 200
    ], 200);
  }

  function noVisit()
  {
    $validator = Validator::make($this->request->all(), [
      'booking_id' => 'required|integer|exists:bookings,id'
    ]);
    if ($validator->fails()) {
      $error = $validator->errors()->first();
      return response()->json([
        'status' => 'failure',
        'detail' => $error
      ]);
    }
    $book = Booking::find($this->request->booking_id);
    $book->status = 'NOVISIT';
    $book->save();
    return response()->json([
      'status' => 'success',
      'detail' => San_Help::sanLang('Updated successfully', $this->lng),
      'status_code' => 200
    ], 200);
  }

  function getGallary()
  {
    $validator = Validator::make($this->request->all(), [
      'saloon_id' => 'required|integer|exists:providers,id'
    ]);
    if ($validator->fails()) {
      $error = $validator->errors()->first();
      return response()->json([
        'status' => 'failure',
        'detail' => $error
      ]);
    }
    // $gallary = Provider::has('provider_images')->with('provider_images')->find($this->request->saloon_id);
    $gallary = MultiImage::where('type', 'providers')->where('record_id', $this->request->saloon_id)
    ->select('filename')
    ->get();
    if ($gallary) {
      return response()->json([
        'status' => 'success',
        'detail' => $gallary,
        'status_code' => 200
      ], 200);
    }
    return response()->json([
      'status' => 'failure',
      'detail' => 'No Image',
      'status_code' => 404
    ], 404);
  }

  public function addUpdateImages()
  {
    $validator = Validator::make($this->request->all(), [
      'saloon_id' => 'required|integer|exists:providers,id',
      'providers_images' => 'required'
    ]);
    if ($validator->fails()) {
      $error = $validator->errors()->first();
      return response()->json([
        'status' => 'failure',
        'detail' => $error
      ]);
    }
    if (isset($this->actions['type'])) {
      $this->request->merge([
        'slug' => $this->actions['type']
      ]);
    } else {
      return false;
    }
    $uploaded_images = San_Help::uploadFiles();
    foreach ($uploaded_images as $image) {
      $pro = new MultiImage();
      $pro->record_id = $this->request->saloon_id;
      $pro->filename = $image;
      $pro->type = $this->actions['type'];
      $pro->path = $image;
      $pro->save();
    }
    if (! empty($uploaded_images)) {
      return response()->json([
        'status' => 'success',
        'detail' => $uploaded_images,
        'status_code' => 200
      ], 200);
    } else {
      return response()->json([
        'status' => 'failure',
        'detail' => 'error',
        'status_code' => 402
      ], 402);
    }
  }

  function getServices()
  {
    $validator = Validator::make($this->request->all(), [
      'saloon_id' => 'required|integer|exists:providers,id'
    ]);
    if ($validator->fails()) {
      $error = $validator->errors()->first();
      return response()->json([
        'status' => 'failure',
        'detail' => $error
      ]);
    }
    $services = Provider::has('getServices')->with('getServices')->find($this->request->saloon_id)->toArray();
    foreach ($services['get_services'] as $key => $value) {
      $value['price'] = San_Help::moneyApi($value['price'],$this->currency);
      $services['get_services'][$key] = San_Help::sanReplaceNull($value);
    }
    if ($services) {
      return response()->json([
        'status' => 'success',
        'detail' => San_Help::sanReplaceNull($services),
        'status_code' => 200
      ], 200);
    } else {
      return response()->json([
        'status' => 'success',
        'detail' => 'no services',
        'status_code' => 404
      ], 404);
    }
  }

  function getProfile()
  {
    $validator = Validator::make($this->request->all(), [
      'userid' => 'required|integer|exists:users,id'
    ]);
    if ($validator->fails()) {
      $error = $validator->errors()->first();
      return response()->json([
        'status' => 'failure',
        'detail' => $error
      ]);
    }
    $user = User::find(app('request')->userid);
    return response()->json([
      'status' => 'success',
      'detail' => San_Help::sanReplaceNull($user->toArray()),
      'status_code' => 200
    ], 200);
  }

  function offers()
  {
    $offers = Offer::has('provider')->whereDate('valid_to', '>=', Carbon::now('Asia/Calcutta'))
    ->get([
      'id',
      'pro_id',
      'offer_type',
      'code'
      ])->toArray();
      // print_r($offers);exit;
      foreach ($offers as $key => $value) {
        $offers[$key]['image'] = Provider::find($value['pro_id'])->avatar;
      }
      if (! $offers) {
        return response()->json([
          'status' => 'failure',
          'detail' => 'no offer',
          'status_code' => 404
        ], 404);
      }
      return response()->json([
        'status' => 'success',
        'detail' => $offers,
        'status_code' => 200
      ], 200);
    }

    function getSallon()
    {
      $validator = Validator::make($this->request->all(), [
        'saloon_id' => 'required|integer|exists:providers,id',
        // 'user_id' => 'required|integer|exists:users,id'
      ]);
      if ($validator->fails()) {
        $error = $validator->errors()->first();
        return response()->json([
          'status' => 'failure',
          'detail' => $error
        ]);
      }
      $sallon_data = array();
      $days = array(
        '1' => 'Sunday',
        '2' => 'Monday',
        '3' => 'Tuesday',
        '4' => 'Wednesday',
        '5' => 'Thursday',
        '6' => 'Friday',
        '7' => 'Saturday'
      );
      $sallon = Provider::with('getAssistants')->with('reviews')
      ->with('getAvail')
      ->find($this->request->saloon_id);
      $sallon->rating = $sallon->reviews->avg('rating');
      if ($sallon->getAvail && $sallon->getAvail->extra != '') {
        $sallon->extra_feature = unserialize($sallon->getAvail->extra);
      }
      if ($sallon->getAvail && $sallon->getAvail->availability != '') {
        $salon_settings = unserialize($sallon->getAvail->availability);
        $salon_dates = $salon_settings['days'];
        foreach ($days as $key => $day) {
          if (isset($salon_dates[$key]) && $salon_dates[$key] == '1') {
            $set_of_days[] = $day;
          }
        }
      }
      // print_r($salon_settings);exit;
      $sallon->unsetRelation('reviews');
      $sallon->unsetRelation('getAvail');
      if ($sallon->latitude != '' && $sallon->longitude != '') {
        $lat = $sallon->latitude;
        $lng = $sallon->longitude;
      } else {
        $coords = San_Help::get_Coordinates($sallon->address);
        $lat = $coords['lat'];
        $lng = $coords['long'];
      }
      $baseloc['Lat'] = $this->request->cust_lat;
      $baseloc['Lon'] = $this->request->cust_long;
      $sallon_loc['Lat'] = $lat;
      $sallon_loc['Lon'] = $lng;
      $salnnn_data = array_merge(San_Help::sanReplaceNull($sallon->toArray()), array(
        'distance' => number_format((float) San_Help::distance_btwn_loc($baseloc, $sallon_loc), 2, '.', '')
      ));

      $favourtes = User::whereNotNull('favourite')->get();
      $count_fav = 0;
      $fav_status = 0;
      foreach ($favourtes as $key => $value) {
        $favs = unserialize($value->favourite);
        if ($this->request->user_id == $value->id && in_array($this->request->saloon_id, $favs)) {
          $fav_status = 1;
        }
        if (in_array($this->request->saloon_id, $favs)) {
          $count_fav ++;
        }
      }
      $salnnn_data['fav_counts'] = (string) $count_fav;
      $salnnn_data['fav_status'] = (string) $fav_status;
      $salnnn_data['share_count'] = (string) $count_fav;
      if (isset($set_of_days) || ! empty($set_of_days)) {
        $salnnn_data['salon_days'] = $set_of_days;
      } else {
        $salnnn_data['salon_days'] = array();
      }
      if (isset($salon_settings['from'][0]) || ! empty($salon_settings['from'][0])) {
        $salnnn_data['first_start'] = $salon_settings['from'][0];
      } else {
        $salnnn_data['first_start'] = "";
      }

      if (isset($salon_settings['to'][0]) || ! empty($salon_settings['to'][0])) {
        $salnnn_data['first_end'] = $salon_settings['to'][0];
      } else {
        $salnnn_data['first_end'] = "";
      }

      if (isset($salon_settings['from'][1]) || ! empty($salon_settings['from'][1])) {
        $salnnn_data['second_start'] = $salon_settings['from'][1];
      } else {
        $salnnn_data['second_start'] = "";
      }

      if (isset($salon_settings['to'][1]) || ! empty($salon_settings['to'][1])) {
        $salnnn_data['second_end'] = $salon_settings['to'][1];
      } else {
        $salnnn_data['second_end'] = "";
      }
      if ($salnnn_data) {
        return response()->json([
          'status' => 'success',
          'detail' => $salnnn_data,
          'status_code' => 200
        ], 200);
      }
    }

    public function favourite()
    {
      $validator = Validator::make($this->request->all(), [
        'user_id' => 'required|integer|exists:users,id',
        'provider_id' => 'required|integer|exists:providers,id'
      ]);
      if ($validator->fails()) {
        $error = $validator->errors()->first();
        return response()->json([
          'status' => 'failure',
          'detail' => $error
        ]);
      }
      $user = User::find($this->request->user_id);
      $fav = array();
      if ($user->favourite) {
        $fav = unserialize($user->favourite);
        if (! in_array($this->request->provider_id, $fav)) {
          array_push($fav, $this->request->provider_id);
          $added = 1;
        } else {
          if (($key = array_search($this->request->provider_id, $fav)) !== false) {
            unset($fav[$key]);
          }
          $added = 0;
        }
      } else {
        array_push($fav, $this->request->provider_id);
        $added = 1;
      }
      $user->favourite = serialize($fav);
      $user->save();
      $result = array();
      if ($added == 1) {
        $msg = 'Added to Favorites';
      } else {
        $msg = 'Remove from Favorites';
      }
      return response()->json([
        'status' => 'success',
        'message' => San_Help::sanLang($msg, $this->lng),
        'detail' => San_Help::sanLang($msg, $this->lng),
        'status_code' => 200
      ], 200);
    }

    function search()
    {
      $sallon_query = Provider::has('getServices')->with('getAvail')->with('reviews');
      /* Service */
      if ($this->request->has('service__')) {
        if (! is_numeric($this->request->service__)) {
          $pids = Service::where('name', 'like', '%' . $this->request->service__ . '%')->pluck('provider_id')->toArray();
        } else {
          $pids = Service::where('parent_service', $this->request->service__)->pluck('provider_id')->toArray();
        }
        $sallon_query = $sallon_query->whereIn('id', $pids);
      }
      /* Locality */
      if ($this->request->has('locality')) {
        $wr_con = $this->request->locality;
        $sallon_query = $sallon_query->where(function ($query) use($wr_con) {
          $query->where('city_country', 'like', '%' . $wr_con . '%')->orWhere('address', 'like', '%' . $wr_con . '%');
        });
      }
      $data = $sallon_query->get();
      if ($data->isEmpty()) {
        return response()->json([
          'status' => 'failure',
          'message' => 'no service',
          'detail' => 'no service',
          'status_code' => 404
        ], 404);
      }
      $lat = '';
      $lng = '';
      foreach ($data as $key => $dat) {
        if ($dat->latitude != '' && $dat->longitude != '') {
          $lat = $dat->latitude;
          $lng = $dat->longitude;
        } else {
          $coords = San_Help::get_Coordinates($dat->address);
          $lat = $coords['lat'];
          $lng = $coords['long'];
        }
        $baseloc['Lat'] = $this->request->cust_lat;
        $baseloc['Lon'] = $this->request->cust_long;
        $sallon_loc['Lat'] = $lat;
        $sallon_loc['Lon'] = $lng;
        $sallon_arr2['avg_rating'] = $dat->reviews->avg('rating');
        $distance = San_Help::distance_btwn_loc($baseloc, $sallon_loc);
        $sallon_arr2['distance'] = number_format((float) $distance, 2, '.', '');
        $sallon_arr[] = San_Help::sanReplaceNull(array_merge($dat->toArray(), $sallon_arr2));
      }
      /* Near Me */
      if (isset($sallon_arr) && is_array($sallon_arr) && $this->request->has('nearme')) {
        usort($sallon_arr, function ($a, $b) {
          if ($a["distance"] == $b["distance"])
          return 0;
          return ($a["distance"] < $b["distance"]) ? - 1 : 1;
        });
      }
      return response()->json([
        'status' => 'success',
        'message' => isset($sallon_arr) ? $sallon_arr : $data->toArray(),
        'detail' => isset($sallon_arr) ? $sallon_arr : $data->toArray(),
        'status_code' => 200
      ], 200);
    }

    function salonServices()
    {
      $validator = Validator::make($this->request->all(), [
        'saloon_id' => 'required|integer|exists:providers,id'
      ]);
      if ($validator->fails()) {
        $error = $validator->errors()->first();
        return response()->json([
          'status' => 'failure',
          'detail' => $error
        ]);
      }
      $services = Service::where('provider_id', $this->request->saloon_id)->get()->toArray();
      foreach ($services as $keyy => $dat_vall) {
        $dat_vall['price'] = San_Help::moneyApi($dat_vall['price'],$this->currency);
        $services[$keyy] = San_Help::sanReplaceNull($dat_vall);
      }
      return response()->json([
        'status' => 'success',
        'message' => $services,
        'detail' => $services,
        'status_code' => 200
      ], 200);
    }

    function getFavourite()
    {
      $validator = Validator::make($this->request->all(), [
        'user_id' => 'required|integer|exists:users,id'
      ]);
      if ($validator->fails()) {
        $error = $validator->errors()->first();
        return response()->json([
          'status' => 'failure',
          'detail' => $error
        ]);
      }
      $user_data = User::find($this->request->user_id);
      if ($user_data->favourite) {
        $pids = unserialize($user_data->favourite);
        $providers = Provider::whereIn('id', $pids)->get();
        return response()->json([
          'status' => 'success',
          'message' => $providers,
          'detail' => $providers,
          'status_code' => 200
        ], 200);
      } else {
        return response()->json([
          'status' => 'success',
          'message' => [],
          'detail' => [],
          'status_code' => 200
        ], 200);
      }
    }

    function getTime()
    {
      $validator = Validator::make($this->request->all(), [
        'saloon_id' => 'required|integer|exists:providers,id',
        '_select_date' => 'required'
      ]);
      if ($validator->fails()) {
        $error = $validator->errors()->first();
        return response()->json([
          'status' => 'failure',
          'detail' => $error
        ]);
      }
      $data = 0;
      $current_date = date("Y-m-d");
      $gmdate = gmdate("H:i");
      $gmdate = strtotime("$gmdate + 30 mins");
      $c_date_str = strtotime($current_date);
      $sel_date_str = strtotime($this->request->_select_date);
      $provider = Provider::has('getAvail')->with('getServices')->find($this->request->saloon_id);
      $salon_settings = unserialize($provider->getAvail->availability);
      // print_r($salon_settings);exit;
      $salon_dates = $salon_settings['days'];
      if ($salon_dates) {
        $data = 1;
      }
      $_sallon_services = $provider->getServices;
      if (! empty($_sallon_services)) {
        foreach ($_sallon_services as $_sallon_ser) {
          $sallon_ser[$_sallon_ser['duration']] = strtotime($_sallon_ser['duration']);
        }
        $lowest_ser_time = min($sallon_ser);
        $time = date("H:i", $lowest_ser_time);
        $parsed = date_parse($time);
        $lowest_minute = $parsed['hour'] * 60 + $parsed['minute'];
      }
      $salon_from = $salon_settings['from'];
      $salon_to = $salon_settings['to'];
      $first_end = $salon_to['0'];
      $secnd_strt = $salon_from['1'];
      $secnd_end = $salon_to['1'];
      $time = strtotime($salon_from['0']);
      $round = 30 * 60;
      $rounded = ceil($time / $round) * $round;
      $startt = date("H:i", $rounded);

      $time2 = strtotime($secnd_strt);
      $rounded2 = ceil($time2 / $round) * $round;
      $secnd_strt = date("H:i", $rounded2);

      $first_end = date("H:i", strtotime("$first_end - $lowest_minute mins"));
      $secnd_end = date("H:i", strtotime("$secnd_end - $lowest_minute mins"));
      $minutes = San_Help::get_minutes($startt, $first_end, $c_date_str, $sel_date_str);
      if (! empty($minutes)) {
        foreach ($minutes as $minute) {
          $minute;
          $minut[] = $minute;
        }
      }

      $minu = array();
      $minutess = San_Help::get_minutess($secnd_strt, $secnd_end, $c_date_str, $sel_date_str);
      if (! empty($minutess)) {
        foreach ($minutess as $minutea) {
          $minutea;
          $minu[] = $minutea;
        }
      }
      if (isset($minut)) {
        $time_rec = array_merge($minut, $minu);
        if ($time_rec) {
          $data = 1;
        }
      }


      if (isset($data) && $data == 1) {
        return response()->json([
          'status' => 'success',
          'time_rec' => isset($time_rec) ? $time_rec : [],
          'date_rec' => $salon_dates,
          'status_code' => 200
        ], 200);
      } else {
        echo json_encode(array(
          'status' => 'failure',
          "detail" => 'no data found'
        ));
        return response()->json([
          'status' => 'failure',
          'detail' => 'no data found',
          'status_code' => 404
        ], 404);
      }
    }

    public function checkServiceBooking()
    {
      $validator = Validator::make($this->request->all(), [
        'saloon_id' => 'required|integer|exists:providers,id',
        'date_mask' => 'required',
        'time_mask' => 'required'
      ]);
      if ($validator->fails()) {
        $error = $validator->errors()->first();
        return response()->json([
          'status' => 'failure',
          'detail' => $error
        ]);
      }
      $service_idss = array();
      $service_idss = Service::where('provider_id', $this->request->saloon_id)->pluck('id')->toArray();
      $err = 0;
      $prvider = Provider::with('getServices')->find($this->request->saloon_id);
      $parids = $prvider->getServices->pluck('category_id')->toArray();
      if (! empty($parids)) {
        $cats = Category::whereIn('id', $parids)->get()->toArray();
        foreach ($cats as $key => $catvalue) {
          $cats[$key] = San_Help::sanReplaceNull($catvalue);
        }
      }
      // echo '<pre>';print_r($not_available_services);exit;
      if (!empty($service_idss)) {
        $not_available_services = $this->checkBooking(array('sids'=>$service_idss,'date'=>$this->request->date_mask,'time'=>$this->request->time_mask));
        $services = Service::whereIn('id', $service_idss)->whereNotIn('id', $not_available_services)
        ->get()
        ->toArray();
        $assistants = Assistant::all()->toArray();
        foreach ($services as $key => $value) {
          $value['price'] = San_Help::moneyApi($value['price'],$this->currency);
          $services[$key] = San_Help::sanReplaceNull($value);
        }
        return response()->json([
          'status' => 'success',
          'detail' => $services,
          'cats' => $cats,
          'status_code' => 200
        ], 200);
      } else {
        return response()->json([
          'status' => 'failure',
          'detail' => array(),
          'status_code' => 404
        ], 404);
      }
    }

    function getCatServices(){
      $validator = Validator::make($this->request->all(), [
        'cat_id' => 'required|integer|exists:categories,id',
        'saloon_id' => 'required|integer|exists:providers,id',
        'date_mask' => 'required',
        'time_mask' => 'required'
      ]);
      if ($validator->fails()) {
        $error = $validator->errors()->first();
        return response()->json([
          'status' => 'failure',
          'detail' => $error
        ]);
      }
      $service_idss = array();
      $service_idss = Service::where('provider_id', $this->request->saloon_id)->pluck('id')->toArray();
      // echo '<pre>';print_r($not_available_services);exit;
      if (!empty($service_idss)) {
        $not_available_services = $this->checkBooking(array('sids'=>$service_idss,'date'=>$this->request->date_mask,'time'=>$this->request->time_mask));
        $services = Service::whereIn('id', $service_idss)->whereNotIn('id', $not_available_services)->where('category_id',$this->request->cat_id)
        ->get()
        ->toArray();
        $assistants = Assistant::all()->toArray();
        foreach ($services as $key => $value) {
          $value['price'] = San_Help::moneyApi($value['price'],$this->currency);
          $services[$key] = San_Help::sanReplaceNull($value);
        }
        return response()->json([
          'status' => 'success',
          'detail' => $services,
          'status_code' => 200
        ], 200);
      } else {
        return response()->json([
          'status' => 'failure',
          'detail' => array(),
          'status_code' => 404
        ], 404);
      }
    }
    function bookAssistant(){
      $validator = Validator::make($this->request->all(), [
        'saloon_id' => 'required|integer|exists:providers,id',
        'services_t' => 'required|array'
      ]);
      if ($validator->fails()) {
        $error = $validator->errors()->first();
        return response()->json([
          'status' => 'failure',
          'detail' => $error
        ]);
      }
      // $catids = Service::whereIn('id',$this->request->services_t)->pluck('category_id')->toArray();
      // if (! empty($catids)) {
      //     $cats = Category::whereIn('id', $catids)->get()->toArray();
      //     foreach ($cats as $key => $catvalue) {
      //         $cats[$key] = San_Help::sanReplaceNull($catvalue);
      //     }
      // }
      $services = Service::whereIn('id', $this->request->services_t)->get()->toArray();
      $assistants = Assistant::all()->toArray();
      foreach ($services as $key => $value) {
        $services[$key] = San_Help::sanReplaceNull($value);
        $services[$key]['assistants'] = array();
        foreach ($assistants as $key2 => $assistant) {
          $sids = unserialize($assistant['service_ids']);
          if (is_array($sids)) {
            if (in_array($value['id'], $sids)) {
              $ass_data = array('id'=>$assistant['id'],'name'=>$assistant['name'],'image'=>$assistant['image']);
              $services[$key]['assistants'][] = San_Help::sanReplaceNull($ass_data);
            }
          }
        }
      }
      if (!empty($services)) {
        return response()->json([
          'status' => 'success',
          'detail' => $services,
          'status_code' => 200
        ], 200);
      }else{
        return response()->json([
          'status' => 'failure',
          'detail' => 'no service'
        ]);
      }
    }

    public function checkBooking($data)
    {
      $service_ids = $data['sids'];
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
      return $not_available_services;
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
      $data->locale = $this->lng;
      $data->to = 'sandeep.digittrix@gmail.com';
      $data->from = $this->request->email;
      // $login_detail->to = 'sandeep.digittrix@gmail.com';
      $data->subject = 'Help Message';
      \Mail::send('maskFront::emails.support', ['data' => $data], function ($m) use ($data) {
        $m->from($data->from, $data->name);
        // ->cc('sandeep.digittrix@gmail.com',$data->name)
        $m->to($data->to, 'Mask Admin')->subject($data->subject);
      });
      return response()->json([
        'status' => 'success',
        'message' => San_Help::sanGetLang('mail sent', $this->lng),
        'detail' => San_Help::sanGetLang('mail sent', $this->lng),
        'status_code' => 200
      ], 200);
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
      return response()->json([
        'status' => 'success',
        'message' => San_Help::sanGetLang('mail sent', $this->lng),
        'detail' => San_Help::sanGetLang('mail sent', $this->lng),
        'status_code' => 200
      ], 200);
    }

    function orderSummary(){
      $validator = Validator::make($this->request->all(), [
        'userid' => 'required|integer|exists:users,id',
        'saloon_id' => 'required|integer|exists:providers,id',
        'time_mask' => 'required',
        'assistants' => 'required|array',
        'services_t' => 'required|array',
        'date_mask' => 'required'
      ]);
      if ($validator->fails()) {
        $error = $validator->errors()->first();
        return response()->json([
          'status' => 'failure',
          'detail' => $error
        ]);
      }
      $user = User::find($this->request->userid);
      $rewardpoint_balance = 0;
      if ($user) {
        $rewardpoint_balance = $user->rewardpoint_balance;
      }
      $order_summ = array();
      $msg = array();
      $price = 0;
      if ($this->request->app_type =='ios') {
        foreach ($services_t as $ass => $service) {

        }
      }else{
        $assistants = Assistant::whereIn('id',$this->request->assistants)->pluck('name')->toArray();
        foreach ($assistants as $ass_key => $ass_val) {
          $assistants[$ass_key] = San_Help::sanGetLang($ass_val, $this->lng);
        }
        $services = Service::whereIn('id',$this->request->services_t)->get(array('name', 'price'))->toArray();
        foreach ($services as $ser_key => $ser_val) {
          $services[$ser_key] = San_Help::sanGetLang($ser_val['name'], $this->lng);
          $price +=  $ser_val['price'];
        }
      }
      $order_summ['services'] = $services;
      $order_summ['org_price'] = $price;
      $order_summ['price'] = San_Help::moneyApi($price,$this->currency);

      $order_summ['date'] = $this->request->date_mask;
      $order_summ['time'] = $this->request->time_mask;
      $order_summ['assistant'] = $assistants;
      $order_summ['discount'] = 0;
      $order_summ['discount_type'] = 'fixed';
      if ($this->request->has('coupon') && $this->request->coupon !='') {
        $this->request->merge(['pro_id' => $this->request->saloon_id]);
        $this->request->merge(['code' => $this->request->coupon]);
        $msg = $this->sanApplyCoupon();
        if (!empty($msg)) {
          if ($msg['success'] == 1) {
            $order_summ['price'] = $price-$msg['discount'];
            $order_summ['discount'] = $msg['discount'];
          }
        }
      }
      return response()->json([
        'status' => 'success',
        'rewardpoint_balance' => $rewardpoint_balance,
        'detail' => $order_summ,
        'coupon_detail' => $msg,
        'status_code' => 200
      ], 200);
    }
    function orderSummary_ios(){
      $validator = Validator::make($this->request->all(), [
        'userid' => 'required|integer|exists:users,id',
        'saloon_id' => 'required|integer|exists:providers,id',
        'time_mask' => 'required',
        'services_t' => 'required',
        'date_mask' => 'required'
      ]);
      if ($validator->fails()) {
        $error = $validator->errors()->first();
        return response()->json([
          'status' => 'failure',
          'detail' => $error
        ]);
      }
      try {
        $ser_ass_arr = json_decode(preg_replace('/\\\"/',"\"", $this->request->services_t));
      } catch (\Illuminate\Validation\ValidationException $e) {
        $ser_ass_arr = $this->request->services_t;
      }

      $user = User::find($this->request->userid);
      $rewardpoint_balance = 0;
      if ($user) {
        $rewardpoint_balance = $user->rewardpoint_balance;
      }
      $order_summ = array();
      $msg = array();
      $price = 0;
      // return response()->json([
      //   'detail' => $ser_ass_arr
      // ]);
      foreach ($ser_ass_arr as $ass => $service) {
        $ser = Service::find($service->service_id);
        if($service->assistant_id !='c_ass'){
          $assis =  Assistant::find($service->assistant_id);
        }else{
          $assistants[] = 'Choose assistant for me';
        }
        if ($ser) {
          $services[] =  San_Help::sanGetLang($ser->name, $this->lng);
          $price +=  $ser->price;
          // $services[] =  $ser->name;
        }
        if (isset($assis)) {
          $assistants[] = San_Help::sanGetLang($assis->name, $this->lng);
        }
      }
      // return response()->json([
      //   'assistants' => $assistants,
      //   'assistants' => $services
      // ]);
      $order_summ['services'] = $services;
      $order_summ['org_price'] = number_format($price, 2, '.', '');
      $order_summ['price'] = San_Help::moneyApi($price,$this->currency);

      $order_summ['date'] = $this->request->date_mask;
      $order_summ['time'] = $this->request->time_mask;
      $order_summ['assistant'] = $assistants;
      $order_summ['discount'] = 0;
      $order_summ['discount_type'] = 'fixed';
      if ($this->request->has('coupon') && $this->request->coupon !='') {
        $this->request->merge(['pro_id' => $this->request->saloon_id]);
        $this->request->merge(['code' => $this->request->coupon]);
        $msg = $this->sanApplyCoupon();
        if (!empty($msg)) {
          if ($msg['success'] == 1) {
            $order_summ['price'] = (string) San_Help::moneyApi($price-$msg['discount'],$this->currency);
            $order_summ['discount'] = $msg['discount'];
          }
        }
      }
      // print_r(json_encode($order_summ));exit;
      return response()->json([
        'status' => 'success',
        'rewardpoint_balance' => $rewardpoint_balance,
        'detail' => $order_summ,
        'coupon_detail' => $msg,
        'status_code' => 200
      ], 200);
    }

    function orderFinish(){
      $validator = Validator::make($this->request->all(), [
        'userid' => 'required|integer|exists:users,id',
        'saloon_id' => 'required|integer|exists:providers,id',
        'time_mask' => 'required',
        'booking_amount' => 'required',
        'services_t' => 'required',
        'date_mask' => 'required',
        'booking_type' => 'required'
      ]);
      if ($validator->fails()) {
        $error = $validator->errors()->first();
        return response()->json([
          'status' => 'failure',
          'detail' => $error
        ]);
      }
      //  return response()->json([
      //    'status' => 'success',
      //    'detail' => json_decode(preg_replace('/\\\"/',"\"", $this->request->services_t)),
      //    'status_code' => 200
      // ], 200);
      $sids = array();
      $aids = array();
      try {
        $ser_ass_arr = json_decode(preg_replace('/\\\"/',"\"", $this->request->services_t));
      } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
          'status' => 'failure',
          'detail' => 'Invalid data passed',
          'status_code' => $e->status
        ], 422);
      }
      if(is_array($ser_ass_arr)){
        foreach ($ser_ass_arr as $value) {
          $asst = $value->assistant_id;
          $ser = $value->service_id;
          if($asst =='c_ass'){
            $asstnt = Assistant::inRandomOrder()->where('provider_id',$this->request->saloon_id)->first();
            if ($asstnt) {
              $asst = $asstnt->id;
            }else{
              $asst = 8;
            }
          }
          array_push($sids, $ser);
          array_push($aids, $asst);
        }
      }
      $this->request->merge(['sids' => $sids]);
      $this->request->merge(['aids' => $aids]);
      $bookid = $this->addBooking($this->request->saloon_id);
      if ($bookid == 'not_ok') {
        return response()->json([
          'status' => 'failure',
          'detail' => 'Something Went Wrong'
        ], 422);
      }
      if($this->request->redeem == 1){
        $data_reward = array(
          'type' => 'redeem_points',
          'redeemed_points' => $this->request->redeemed_points,
          '_booking_id' => $bookid,
        );
        San_Help::updateRewardPoints($data_reward);
      }
      return response()->json([
        'status' => 'success',
        'message' => San_Help::sanLang('Booked successfully', $this->lng),
        'detail' => San_Help::sanLang('Booked successfully', $this->lng),
        'booking_id' => $bookid,
        'status_code' => 200
      ], 200);
    }

    function sanApplyCoupon()
    {
      $remove = 0;
      if (isset($this->request->remove) && $this->request->remove == 1) {
        $remove = 1;
      }
      if (isset($this->request->pro_id) && is_array($this->request->pro_id) && $this->request->has('offer_type')) {
        $query = Offer::whereIn('pro_id', $this->request->pro_id)->where('offer_type',$this->request->offer_type);
      }elseif (isset($this->request->pro_id)) {
        $query = Offer::where('pro_id', $this->request->pro_id)->where('offer_type','provider');
      }
      if (isset($this->request->code)) {
        $offers = $query->get();
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
              'success' => 2,
              'discount' => 0,
              'code' => $this->request->code,
              'errors' => San_Help::sanLang('coupon date expire', $this->lng)
            );
            break;
          }
        }
        if ($offer_id != '') {
          $ofr = Offer::find($offer_id);
          if ($remove == 1) {
            $ofr->usage = $ofr->usage - 1;
          } else {
            $ofr->usage = $ofr->usage + 1;
          }
          $ofr->save();
          if ($remove == 1) {
            $ret = array(
              'success' => 3,
              'discount' => $ofr->amount,
              'code' => $this->request->code,
              'message' => San_Help::sanLang('Coupon Removed Successfully', $this->lng),
              'detail' => San_Help::sanLang('Coupon Removed Successfully', $this->lng)
            );
          } else {
            $ret = array(
              'success' => 1,
              'discount' => $ofr->amount,
              'code' => $this->request->code,
              'message' => San_Help::sanLang('Coupon applied', $this->lng),
              'detail' => San_Help::sanLang('Coupon applied', $this->lng)
            );
          }
          return $ret;
        }
        $ret = array(
          'success' => 2,
          'discount' => 0,
          'message' => San_Help::sanLang('Invalid Coupon, Please enter valid coupon code', $this->lng),
          'detail' => San_Help::sanLang('Invalid Coupon, Please enter valid coupon code', $this->lng)
        );
        return $ret;
      }
    }

    function addBooking($id, $method = 'cash')
    {
      $data = $this->request->all();
      // echo '<pre>';print_r($data);exit;
      $not_available_services = $this->checkBooking(array('sids'=>$data['sids'],'date'=>$data['date_mask'],'time'=>$data['time_mask']));
      if (is_array($not_available_services) && !empty($not_available_services)) {
        return 'not_ok';
      }
      $user = User::find($data['userid']);
      if (!$user) {
        return 'not_ok';
      }
      $book = new Booking();
      $book->user_id = $user->id;
      $book->salon_id = $id;
      $book->assistent_ids = implode(',', $data['aids']);
      $book->service_ids = implode(',', $data['sids']);
      $book->book_date = $data['date_mask'];
      $book->time = $data['time_mask'];
      $book->price = $data['booking_amount'];
      $book->pay_method = $method;
      $book->first_name = $user->name;
      $book->last_name = $user->lname;
      $book->email = $user->email;
      $book->phone = $user->phone;
      $book->address = $user->address;
      $book->gender = $user->gender;
      $book->type = $data['booking_type'];
      $book->currency = $this->currency;
      $book->status = null;
      $book->save();
      return $book->id;
    }

    function shareSallon(){
      $validator = Validator::make($this->request->all(), [
        'userid' => 'required|integer|exists:users,id',
        'saloon_id' => 'required|integer|exists:providers,id'
      ]);
      if ($validator->fails()) {
        $error = $validator->errors()->first();
        return response()->json([
          'status' => 'failure',
          'detail' => $error
        ]);
      }
      $user = User::find($this->request->userid);
      $share = array();
      if ($user->share) {
        $share = unserialize($user->share);
        if (! in_array($this->request->saloon_id, $share)) {
          array_push($share, $this->request->saloon_id);
          $added = 1;
        } else {
          $added = 0;
        }
      } else {
        array_push($share, $this->request->saloon_id);
        $added = 1;
      }
      $user->share = serialize($share);
      $user->save();
      if ($added == 1) {
        $msg = 'Shared successfully';
      } else {
        $msg = 'already shared';
      }
      return response()->json([
        'status' => 'success',
        'message' => San_Help::sanLang($msg, $this->lng),
        'detail' => San_Help::sanLang($msg, $this->lng),
        'status_code' => 200
      ], 200);
    }

    function addPayStatus(){
      $validator = Validator::make($this->request->all(), [
        'bookid' => 'required|integer|exists:bookings,id',
        'pay_status' => 'required'
      ]);
      if ($validator->fails()) {
        $error = $validator->errors()->first();
        return response()->json([
          'status' => 'failure',
          'detail' => $error
        ]);
      }
      $book = Booking::find($this->request->bookid);
      if ($book) {
        $book->pay_method = $this->request->pay_status;
        $book->status = 'Pending';
        $book->save();
      }
      $data_sms = array(
        'type' => 'new_booking',
        '_booking_id' => $this->request->bookid,
        'pro_id' => $book->salon_id
      );
      San_Help::sanSendSms($data_sms);
      $type = 'new_booking';
      $msg_data['key'] = '';
      $msg_data['_booking_id'] = $this->request->bookid;
      $msg_data['sallon_id'] = $book->salon_id;
      /* Send Mail */
      San_Help::send_NewBookingEmail($type, $book->salon_id, $this->request->bookid);
      $data_reward = array(
        'type' => 'new_booking',
        '_booking_id' => $this->request->bookid,
      );
      San_Help::updateRewardPoints($data_reward);
      return response()->json([
        'status' => 'success',
        'message' => 'Booking Successfull',
        'detail' => 'Booking Successfull',
        'status_code' => 200
      ], 200);
    }

    function userBookings(){
      $validator = Validator::make($this->request->all(), [
        'userid' => 'required|integer|exists:users,id'
      ]);
      if ($validator->fails()) {
        $error = $validator->errors()->first();
        return response()->json([
          'status' => 'failure',
          'detail' => $error
        ]);
      }
      $book_arr = array();
      $book_fin_arr = array();
      $bookings = User::with('getBookings')->with('orders')->with('user_reviews')->find($this->request->userid)->toArray();
      // print_r($bookings);exit;
      foreach ($bookings['get_bookings'] as $key => $value) {
        $book_arr['id'] = $value['id'];
        if($value['status']=='Completed' || $value['status']=='NOVISIT' || $value['status']=='Canceled'){
          $book_arr['booking_s'] = 'history';
        }else{
          $bdat = strtotime($value['book_date']);
          $today = date('Y-m-d');
          $tdate = strtotime($today);
          if($bdat < $tdate){
            $book_arr['booking_s'] = 'history';
          }
          if($bdat >= $tdate){
            $book_arr['booking_s'] = 'next';
          }
        }
        $provider = Provider::find($value['salon_id']);
        $services = Service::whereIn('id',explode(',', $value['service_ids']))->pluck('name')->toArray();
        foreach ($services as $ser_key => $ser_val) {
          $services[$ser_key] = San_Help::sanGetLang($ser_val, $this->lng);
        }
        $book_arr['booking_date'] = $value['book_date'];
        $book_arr['booking_amount'] = San_Help::moneyApi($value['price'],$this->currency);
        $book_arr['org_price'] = $value['price'];
        $book_arr['saloon_provider'] = $provider ? $provider->name : '';
        $book_arr['saloon_phone'] =  $provider ? $provider->phone : '';
        $book_arr['booking_status'] = $value['status'];
        $book_arr['pay_status'] = $value['pay_method'];
        $book_arr['services'] = isset($services) ? $services : '';
        $book_arr['attendants'] = Assistant::whereIn('id',explode(',', $value['assistent_ids']))->pluck('name')->toArray();
        $book_arr['images'] = $provider ? $provider->avatar : '';
        $book_arr['comments'] = array();
        foreach ($bookings['user_reviews'] as $book_cmnt => $book_cmnt_val) {
          if ($book_cmnt_val['record_id'] == $value['id']) {
            $book_arr['comments'] = San_Help::sanReplaceNull($book_cmnt_val);
            break;
          }
        }
        $book_fin_arr[] = San_Help::sanReplaceNull($book_arr);

      }
      // foreach ($bookings['orders'] as $key => $value) {
      //     $bookings['orders'][$key] = San_Help::sanReplaceNull($value);
      // }
      // foreach ($bookings['reviews'] as $key => $value) {
      //     $bookings['reviews'][$key] = San_Help::sanReplaceNull($value);
      // }
      return response()->json([
        'status' => 'success',
        'message' => $book_fin_arr,
        'detail' => $book_fin_arr,
        'status_code' => 200
      ], 200);
    }

    function addReview(){
      $validator = Validator::make($this->request->all(), [
        'userid' => 'required|integer|exists:users,id',
        'bookingid' => 'required|integer|exists:bookings,id',
        // 'comment' => 'required',
        'rating' => 'required'
      ]);
      if ($validator->fails()) {
        $error = $validator->errors()->first();
        return response()->json([
          'status' => 'failure',
          'detail' => $error
        ]);
      }
      $user_review = Review::where('user_id',$this->request->userid)->where('type','booking')->where('record_id',$this->request->bookingid)->first();
      if ($user_review) {
        $user_review->rating = $this->request->rating;
        $user_review->review = $this->request->comment;
        $user_review->updated_at = new \DateTime();
        $user_review->save();
        $msg = 'Updated';
      }else{
        Review::create([
          'record_id' => $this->request->bookingid,
          'user_id' => $this->request->userid,
          'rating' => $this->request->rating,
          'review' => $this->request->comment,
          'type' => 'booking',
          'created_at' => new \DateTime(),
          'updated_at' => new \DateTime()
        ]);
        $msg = 'Added';
      }
      return response()->json([
        'status' => 'success',
        'message' => San_Help::sanLang('Review '.$msg.' Successfully',$this->lng),
        'detail' => San_Help::sanLang('Review '.$msg.' Successfully',$this->lng),
        'status_code' => 200
      ], 200);
    }

    function reviewReply(){
      $validator = Validator::make($this->request->all(), [
        'userid' => 'required|integer|exists:users,id',
        'bookingid' => 'required|integer|exists:bookings,id',
        'comment_id' => 'required|integer|exists:reviews,id',
        'reply' => 'required'
      ]);
      if ($validator->fails()) {
        $error = $validator->errors()->first();
        return response()->json([
          'status' => 'failure',
          'detail' => $error
        ]);
      }
      $user_review = Review::find($this->request->comment_id);
      $user_review->reply = $this->request->reply;
      $user_review->updated_at = new \DateTime();
      $user_review->save();
      return response()->json([
        'status' => 'success',
        'detail' => San_Help::sanLang('Reply Added Successfully',$this->lng),
        'message' => San_Help::sanLang('Reply Added Successfully',$this->lng),
        'status_code' => 200
      ], 200);
    }

    function sallonReviews(){
      $validator = Validator::make($this->request->all(), [
        'saloon_id' => 'required|integer|exists:providers,id'
      ]);
      if ($validator->fails()) {
        $error = $validator->errors()->first();
        return response()->json([
          'status' => 'failure',
          'detail' => $error
        ]);
      }
      $bids = Booking::where('salon_id',$this->request->saloon_id)->pluck('id')->toArray();
      $data = Review::with('user')->whereIn('record_id',$bids)->get();
      $n_rating = array();
      $final_arr = array();
      foreach ($data as $rev_ky => $rev_vl) {
        $n_rating['booking_id'] = $rev_vl->record_id;
        $n_rating['post_author'] = $rev_vl->user->name;
        $n_rating['_rating'] = $rev_vl->rating;
        $n_rating['comment_date'] = Carbon::parse($rev_vl->created_at)->format('d-m-Y H:i');
        $n_rating['comment_content'] = $rev_vl->review;
        $n_rating['user_email'] =$rev_vl->user->email;
        $n_rating['user_img'] = $rev_vl->user->avatar;
        $n_rating['user_name'] = $rev_vl->user->name;
        $n_rating['type'] = 'booking';
        $final_arr[] = San_Help::sanReplaceNull($n_rating);
      }
      $fin_arrrr = array_merge($final_arr,$this->getproductReviews($this->request->saloon_id));
      if (!empty($fin_arrrr)) {
        return response()->json([
          'status' => 'success',
          'detail' => $fin_arrrr,
          'status_code' => 200
        ], 200);
      }else{
        return response()->json([
          'status' => 'failure',
          'detail' => 'Reviews Not Available',
          'status_code' => 404
        ], 404);
      }
    }

    function verifyOtp(){
      $messages = [
        'otp.exists'    => San_Help::sanGetLang('Invalid OTP', $this->lng)
      ];
      $validator = Validator::make($this->request->all(), [
        'user_id' => 'required|integer|exists:users,id',
        'otp' => 'required|exists:users,verify_otp'
      ],$messages);
      if ($validator->fails()) {
        $error = $validator->errors()->first();
        return response()->json([
          'status' => 'failure',
          'detail' => $error
        ]);
      }
      return response()->json([
        'status' => 'success',
        'detail' => San_Help::sanLang('OTP verified successfully',$this->lng),
        'status_code' => 200
      ], 200);
    }

    function getRewardPoints(){
      $validator = Validator::make($this->request->all(), [
        'userid' => 'required|integer|exists:users,id'
      ]);
      if ($validator->fails()) {
        $error = $validator->errors()->first();
        return response()->json([
          'status' => 'failure',
          'detail' => $error
        ]);
      }
      $rewards = Reward::where('user_id',$this->request->userid)->get()->toArray();
      return response()->json([
        'status' => 'success',
        'detail' => $rewards,
        'status_code' => 200
      ], 200);
    }

    function redeemPoints()
    {
      $validator = Validator::make($this->request->all(), [
        'diamonds' => 'required',
        'total' => 'required',
      ]);
      if ($validator->fails()) {
        $error = $validator->errors()->first();
        return response()->json([
          'status' => 'failure',
          'detail' => $error
        ]);
      }
      $c_action = $this->request->action;
      // $org_price = $this->request->org_price;
      $data_diamonds = $this->request->diamonds;
      $data_total = urldecode($this->request->total);
      if ($c_action == 'apply') {
        if ($data_total < 75) {
          $result['message'] = "Cart Total Must Be Greater than 75";
          $result['status'] = "failure";
          return response()->json($result);
        }
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
          $res['total_after_redeem'] = San_Help::moneyApi(($data_total - $redeem_sar),$this->currency);
          $result['message'] = "Jewelries redeemed succesfully";
          $result['status'] = "success";
          $result['total_after_redeem'] = $res['total_after_redeem'];
          // $result['org_price'] = $org_price;
          $result['balance_after_redeem'] = $data_diamonds-$redeem_sar;
          $result['redeem_sar'] = $res['redeem_sar'];
        } else {
          $result['message'] = "You don't not sufficent Jewelries balance";
          $result['status'] = "failure";
        }
      } elseif ($c_action == 'remove') {
        $_SESSION['redeem_points'] = array();
        $result['message'] = "Jewelries redeemption removed succesfully";
        $result['status'] = "success";
        // $result['org_price'] = $org_price;
        $result['total_after_redeem'] = San_Help::moneyApi($data_total,$this->currency);
        $result['redeem_sar'] = '0';
      }
      return response()->json($result);
    }

    function jewelriesInfo(){
      $jewelries_info = DB::table('settings')->select('display_name','value')->where('key','admin.jewelries')->first();
      $data = array (
        'status' => 'success',
        "detail" => array(
          'title' => strip_tags(San_Help::sanGetLang($jewelries_info->display_name,$this->lng)),
          'content' => strip_tags(San_Help::sanGetLang($jewelries_info->value,$this->lng))
        )
      );
      return response()->json($data);
    }

    function termCondition(){
      $terms = DB::table('settings')->select('display_name','value')->where('key','admin.terms-conditions')->first();
      // San_Help::sanGetLang($terms->value,$this->lng);exit;
      $data = array (
        'status' => 'success',
        "detail" => array(
          'title' => strip_tags(San_Help::sanGetLang($terms->display_name,$this->lng)),
          'content' => strip_tags(San_Help::sanGetLang($terms->value,$this->lng))
        )
      );
      return response()->json($data);
    }

    /* Products Section*/
    function getProducts(){
      // $validator = Validator::make($this->request->all(), [
      //   'user_id' => 'required|integer|exists:users,id'
      // ]);
      // if ($validator->fails()) {
      //   $error = $validator->errors()->first();
      //   return response()->json([
      //     'status' => 'failure',
      //     'detail' => $error
      //   ]);
      // }
      $query = Product::with('product_images')->with('reviews')->with('related_products')->where('active',1);
      //  Filter
      if ($this->request->has('cat_name') && $this->request->cat_name !='') {
        $pr = $this->request->cat_name;
        $cids = Category::orderBy('id', 'desc')->whereNull('parent_id')->where('type', 'procategory')->where(function ($quer) use($pr) {
          $quer->where('name', 'like', '%' . $pr . '%')->orWhere('slug', 'like', '%' . $pr . '%');
        })->pluck('id')->toArray();
        $query = $query->whereIn('category_id', $cids);
      }
      if ($this->request->has('clr') && $this->request->clr !='') {
        $pro_ids = array();
        foreach ($query->get() as $p_key => $p_value) {
          if ($p_value->color) {
            $colors = explode(',', $p_value->color);
            $colors = array_map('strtolower', $colors);
            if (in_array(trim(strtolower($this->request->clr)), $colors)) {
              array_push($pro_ids,$p_value->id);
            }
          }
        }
        $query = $query->whereIn('id', $pro_ids);
      }
      if ($this->request->has('minprice') && $this->request->has('maxprice')) {
        $min = $this->request->minprice?$this->request->minprice:0;
        $max = $this->request->maxprice;
        if ($this->request->maxprice =='') {
          $query = $query->where('price','>=', (int) $min);
        }else{
          $query = $query->whereBetween('price', [(int) $min,(int) $max]);
        }
      }
      if ($this->request->has('provider') && $this->request->provider !='') {
        $query = $query->where('provider_id', $this->request->provider);
      }

      if ($this->request->has('product_id')) {
        $product = $query->where('id',$this->request->product_id)->first();
        $newcolors = array();
        if ($product->color) {
          $colors = explode(',', $product->color);
          foreach ($colors as $value) {
            $newcolors[] = array('name'=>strtolower($value),'code'=>isset(config('maskfront.colors')[strtolower($value)]) ? config('maskfront.colors')[strtolower($value)] : $value);
          }
        }
        $product->color = $newcolors;
        $product->org_price = $product->price;
        $product->price = San_Help::moneyApi($product->price,$this->currency);

        $favourtes = User::whereNotNull('favourite')->get();
        $count_fav = 0;
        $fav_status = 0;
        foreach ($favourtes as $key => $value) {
          if ($value->fav_products) {
            $favs = unserialize($value->fav_products);
            if ($this->request->user_id == $value->id && in_array($this->request->product_id, $favs)) {
              $fav_status = 1;
            }
            if (in_array($this->request->product_id, $favs)) {
              $count_fav ++;
            }
          }
        }
        foreach ($product->reviews as $keyyy => $valueeee) {
          $product->reviews[$keyyy]['image'] = User::find($valueeee->user_id)->avatar;
        }
        $product->fav_counts = (string) $count_fav;
        $product->fav_status = (string) $fav_status;
        $product->share_count = (string) $count_fav;
        $product->product_images[] = array('path'=>$product->image);
        return response()->json([
          'status' => 'success',
          'detail' => San_Help::sanReplaceNull($product),
          'cart_total' => Cart::with('product')->where('user_id', $this->request->user_id)->count(),
          'status_code' => 200
        ], 200);
      }
      // $data = $query->get()->toArray();
      $data = $query->get();
      $fina_data = array();
      foreach ($data as $key => $value) {
        $newcolors = array();
        if ($value->color) {
          $colors = explode(',', $value->color);
          foreach ($colors as $cvalue) {
            $newcolors[] = array('name'=>strtolower($cvalue),'code'=>isset(config('maskfront.colors')[strtolower($cvalue)]) ? config('maskfront.colors')[strtolower($cvalue)] : $cvalue);
          }
        }
        $value->color = $newcolors;
        $favourtes = User::whereNotNull('favourite')->get();
        $count_fav = 0;
        $fav_status = 0;
        foreach ($favourtes as $key => $values) {
          if ($values->fav_products) {
            $favs = unserialize($values->fav_products);
            if ($this->request->user_id == $values->id && in_array($value->id, $favs)) {
              $fav_status = 1;
            }
            if (in_array($value->id, $favs)) {
              $count_fav ++;
            }
          }
        }
        $value->org_price = $value->price;
        $value->price = San_Help::moneyApi($value->price,$this->currency);

        $value->fav_counts = (string) $count_fav;
        $value->fav_status = (string) $fav_status;
        $value->share_count = (string) $count_fav;
        foreach ($value->related_products as $keyyy => $valuesss) {
          if (is_object($valuesss)) {
            $valuesss = $valuesss->toArray();
          }
          $value->related_products[$keyyy] = San_Help::sanReplaceNull($valuesss);
        }
        foreach ($value->reviews as $keyyyy => $valueeee) {
          $value->reviews[$keyyyy]['image'] = User::find($valueeee->user_id)->avatar;
        }
        // $data[$key] = San_Help::sanReplaceNull($value);
        $fina_data[] = San_Help::sanReplaceNull($value->toArray());
        // print_r($value);exit;

        // $data[$key]['reviews'] = San_Help::sanReplaceNull($value['reviews']);
      }
      return response()->json([
        'status' => 'success',
        'detail' => $fina_data,
        'status_code' => 200
      ], 200);
    }

    public function addCart()
    {
      $validator = Validator::make($this->request->all(), [
        'user_id' => 'required|integer|exists:users,id',
        'product_id' => 'required|integer|exists:products,id',
        'color_name' => 'required',
        'price' => 'required',
        'qty' => 'required|gt:0',
      ]);
      if ($validator->fails()) {
        $error = $validator->errors()->first();
        return response()->json([
          'status' => 'failure',
          'detail' => $error
        ]);
      }
      $cart = new Cart();
      $chkproduct = Product::find($this->request->product_id);
      if ($chkproduct) {
        $colors = $chkproduct->color;
        if ($colors) {
          $colors = explode(',', $colors);
          $colors = array_map('strtolower', $colors);
          // print_r($colors);exit;
          if (is_array($colors) && !in_array($this->request->color_name, $colors)) {
            return response()->json([
              'status' => 'failure',
              'detail' => 'Color Not Found'
            ]);
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
        return response()->json([
          'status' => 'failure',
          'detail' => San_Help::sanLang('Product Alraedy Addded In Cart', $this->lng)
        ]);
      }
      if (isset($update->id)) {
        $update->price = $this->request->price;
        $update->qty = $this->request->qty;
        $update->total = $this->request->price * $this->request->qty;
        $update->save();
        return response()->json([
          'status' => 'success',
          'detail' => San_Help::sanLang('cart updated', $this->lng),
          'status_code' => 200
        ], 200);
      }
      $cart->product_id = $this->request->product_id;
      $cart->user_id = $this->request->user_id;
      $cart->price = $this->request->price;
      $cart->color = $this->request->color_name;
      $cart->qty = $this->request->qty;
      $cart->total = $this->request->price * $this->request->qty;
      $cart->save();
      return response()->json([
        'status' => 'success',
        'detail' => San_Help::sanLang('product added', $this->lng),
        'status_code' => 200
      ], 200);
    }

    function getCart(){
      $validator = Validator::make($this->request->all(), [
        'user_id' => 'required|integer|exists:users,id'
      ]);
      if ($validator->fails()) {
        $error = $validator->errors()->first();
        return response()->json([
          'status' => 'failure',
          'detail' => $error
        ]);
      }
      $cartdata = Cart::with('product')->where('user_id', $this->request->user_id)->get()->toArray();
      $items = 0;
      $total = 0;
      $discount = 0;
      $discount_msg = '';
      $proids = array();
      foreach ($cartdata as $key => $value) {
        $items++;
        $total += $value['total'];
        array_push($proids, $value['product_id']);
        $cartdata[$key] = San_Help::sanReplaceNull($value);
        $cartdata[$key]['total'] = San_Help::moneyApi($value['total'],$this->currency);
        $cartdata[$key]['org_price'] = $value['price'];
        $cartdata[$key]['price'] = San_Help::moneyApi($value['price'],$this->currency);

        $cartdata[$key]['product'] = San_Help::sanReplaceNull($value['product']);
      }
      if ($this->request->has('coupon') && $this->request->coupon !='') {
        $this->request->merge(['pro_id' => $proids]);
        $this->request->merge(['code' => $this->request->coupon]);
        $this->request->merge(['offer_type' => 'product']);
        $msg = $this->sanApplyCoupon();
        // print_r($msg);exit;
        if (!empty($msg)) {
          $total = $total-$msg['discount'];
          $discount = $msg['discount'];
          $discount_msg = $msg['message'];
        }
      }
      return response()->json([
        'status' => 'success',
        'detail' => $cartdata,
        'rewardpoint_balance' => User::find($this->request->user_id)->rewardpoint_balance,
        'total_items' => $items,
        'total_amount' => San_Help::moneyApi($total,$this->currency),
        'discount' => San_Help::moneyApi($discount,$this->currency),
        'discount_msg' => $discount_msg,
        'status_code' => 200
      ], 200);
    }

    function orders(){
      $validator = Validator::make($this->request->all(), [
        'user_id' => 'required|integer|exists:users,id'
      ]);
      if ($validator->fails()) {
        $error = $validator->errors()->first();
        return response()->json([
          'status' => 'failure',
          'detail' => $error
        ]);
      }
      $orders = Order::with('review')->with('user')->where('order_user_id',$this->request->user_id)->orderBy('id', 'desc')->get()->toArray();
      foreach ($orders as $key => $value) {
        $orders[$key]['org_price'] = $value['price'];
        $orders[$key]['price'] = San_Help::moneyApi($value['price'],$this->currency);

        if ($value['product_ids'] != null) {
          $products = Product::where('id',$value['product_ids'])->orderBy('id', 'desc')->get()->toArray();
          $providers = Provider::where('id',$value['provider_id'])->orderBy('id', 'desc')->pluck('name')->toArray();
        }else{
          $product_ids = array();
          $providers_ids = array();
          $pids = unserialize($value['provider_id']);
          foreach ($pids as $providerid => $productid) {
            $product_ids = $productid;
            // array_push($product_ids, $productid);
            array_push($providers_ids, $providerid);
          }
          $products = Product::whereIn('id',$product_ids)->get()->toArray();
          $providers = Provider::whereIn('id',$providers_ids)->orderBy('id', 'desc')->pluck('name')->toArray();
        }
        $orders[$key] = San_Help::sanReplaceNull($value);
        $orders[$key]['products'] = $products;
        $orders[$key]['providers'] = $providers;
        $orders[$key]['image'] = !empty($orders[$key]['user']) ? $orders[$key]['user']['avatar'] : '';
        $orders[$key]['review'] = !empty($orders[$key]['review']) ? San_Help::sanReplaceNull($orders[$key]['review']) : new \stdClass;
        unset($orders[$key]['user']);
      }
      return response()->json([
        'status' => 'success',
        'detail' => $orders,
        'status_code' => 200
      ], 200);
    }

    function removeCart(){
      $validator = Validator::make($this->request->all(), [
        'user_id' => 'required|integer|exists:users,id',
        'cart_id' => 'required|integer|exists:cart,id',
        'product_id' => 'required|integer|exists:products,id'
      ]);
      if ($validator->fails()) {
        $error = $validator->errors()->first();
        return response()->json([
          'status' => 'failure',
          'detail' => $error
        ]);
      }
      Cart::destroy($this->request->cart_id);
      return response()->json([
        'status' => 'success',
        'detail' => San_Help::sanLang('product removed', $this->lng),
        'status_code' => 200
      ], 200);
    }

    public function addWishList()
    {
      $validator = Validator::make($this->request->all(), [
        'user_id' => 'required|integer|exists:users,id',
        'product_id' => 'required|integer|exists:products,id'
      ]);
      if ($validator->fails()) {
        $error = $validator->errors()->first();
        return response()->json([
          'status' => 'failure',
          'detail' => $error
        ]);
      }
      if ($this->request->has('cart_id') && $this->request->cart_id !='') {
        Cart::destroy($this->request->cart_id);
      }
      $user = User::find($this->request->user_id);
      $fav = array();
      if ($user->fav_products) {
        $fav = unserialize($user->fav_products);
        if (!in_array($this->request->product_id, $fav)) {
          array_push($fav, $this->request->product_id);
          $added = 1;
        }else{
          if (($key = array_search($this->request->product_id, $fav)) !== false) {
            unset($fav[$key]);
          }
          $added = 0;
        }
      }else{
        array_push($fav, $this->request->product_id);
        $added = 1;
      }
      $user->fav_products = serialize($fav);
      $user->save();
      $result = array();
      if ($added == 1) {
        $msg = 'Added to Favorites';
      } else {
        $msg = 'Remove from Favorites';
      }
      return response()->json([
        'status' => 'success',
        'detail' => San_Help::sanLang($msg, $this->lng),
        'status_code' => 200
      ], 200);
    }

    function getWishList()
    {
      $validator = Validator::make($this->request->all(), [
        'user_id' => 'required|integer|exists:users,id'
      ]);
      if ($validator->fails()) {
        $error = $validator->errors()->first();
        return response()->json([
          'status' => 'failure',
          'detail' => $error
        ]);
      }
      $user_data = User::find($this->request->user_id);
      if ($user_data->fav_products) {
        $pids = unserialize($user_data->fav_products);
        $products = Product::whereIn('id', $pids)->get();
        foreach ($products as $key => $value) {
          $products[$key]->price = San_Help::moneyApi($value->price,$this->currency);
        }
        return response()->json([
          'status' => 'success',
          'message' => $products,
          'detail' => $products,
          'status_code' => 200
        ], 200);
      } else {
        return response()->json([
          'status' => 'failure',
          'detail' => 'no favourite product'
        ]);
      }
    }

    function payCart()
    {
      $validator = Validator::make($this->request->all(), [
        'user_id' => 'required|integer|exists:users,id'
      ]);
      if ($validator->fails()) {
        $error = $validator->errors()->first();
        return response()->json([
          'status' => 'failure',
          'detail' => $error
        ]);
      }
      $msg_data = array();
      $cartdata = Cart::with('product')->where('user_id', $this->request->user_id)->get();
      $proids = array();
      foreach ($cartdata as $key => $value) {
        array_push($proids, $value->product->provider_id);
      }
      $bookid = $this->addOrder($cartdata->pluck('product_id')->toArray(), 'cash');
      $data_sms = array(
        'type' => 'new_booking',
        '_booking_id' => $bookid,
        'pro_id' => $proids
      );
      San_Help::sanSendSms($data_sms);
      $type = 'new_booking';
      $msg_data['key'] = '';
      $msg_data['_booking_id'] = $bookid;
      $msg_data['sallon_id'] = $proids;
      /* Send Mail */
      San_Help::send_NewBookingEmail($type, $proids, $bookid);
      if($this->request->has('user_id')){
        $this->updateproducts();
        Cart::where('user_id',$this->request->user_id)->delete();
      }
      return response()->json([
        'status' => 'success',
        'orderid' => $bookid,
        'detail' => San_Help::sanLang('Order Placed Successfully', $this->lng),
        'status_code' => 200
      ], 200);

    }

    function addOrder($id, $method = 'cash')
    {
      $order = new Order();
      $order->order_user_id = $this->request->user_id;
      $total = 0;
      $qty = 0;
      $colors = array();
      if (is_array($id)) {
        $cartdata = Cart::with('product')->where('user_id', $this->request->user_id)->get();
        // print_r($cartdata);exit;
        $serilize_arr = array();
        foreach ($cartdata as $key => $value) {
          $qty += $value->qty;
          $total += $value->price*$qty;
          array_push($colors, $value->color);
          $serilize_arr[$value->product->provider_id][] = $value->product_id;
        }
        $order->provider_id = serialize($serilize_arr);
        $order->product_ids = null;
      }else{
        $order->provider_id = $this->request->session()->get('provider_id');
        $order->product_ids = $id;
      }
      $order->payment_method = $method;
      $order->price = $total;
      $order->qty = $qty;
      $order->color = implode(',', array_unique($colors));
      $order->status = 0;
      $order->order_status = 'pending';
      $order->currency = $this->currency;
      $order->save();
      return $order->id;
    }

    function addOrderPayStatus(){
      $validator = Validator::make($this->request->all(), [
        'orderid' => 'required|integer|exists:orders,id',
        'pay_status' => 'required'
      ]);
      if ($validator->fails()) {
        $error = $validator->errors()->first();
        return response()->json([
          'status' => 'failure',
          'detail' => $error
        ]);
      }
      $order = Order::find($this->request->orderid);
      $order->status = 1;
      $order->save();
      return response()->json([
        'status' => 'success',
        'message' => 'Booking Successfull',
        'detail' => 'Booking Successfull',
        'status_code' => 200
      ], 200);
    }

    function salonOrders(){
      $validator = Validator::make($this->request->all(), [
        'salon_id' => 'required|integer|exists:providers,id'
      ]);
      if ($validator->fails()) {
        $error = $validator->errors()->first();
        return response()->json([
          'status' => 'failure',
          'detail' => $error
        ]);
      }
      $orders = Order::where('order_user_id','!=','')->whereNotNull('order_user_id')->where('status',1)->orderBy('id', 'desc')->get();
      $new_orders = array();
      $fin_arr = array();
      $products = array();
      foreach ($orders as $okey => $order) {
        $user = User::find($order->order_user_id);
        if ($user) {
          $new_orders['name'] = User::find($order->order_user_id)->name;
          $new_orders['phone'] = User::find($order->order_user_id)->phone;
          $new_orders['date'] = Carbon::parse($order->created_at)->format('d-m-Y H:i');
          if ($order->product_ids != null) {
            $pro_names = array();
            $pro_images = array();
            $pro = Product::where('id',$order->product_ids)->select('name','image')->get();
            foreach ($pro as $key => $provalue) {
              array_push($pro_names, $provalue->name);
              array_push($pro_images, $provalue->image);
            }
            $new_orders['products'] = $pro_names;
            $new_orders['products_images'] = $pro_images;
          }else{
            $product_ids = array();
            $pids = unserialize($order->provider_id);
            foreach ($pids as $providerid => $productids) {
              $product_ids = $productids;
            }
            $new_orders['products'] = Product::whereIn('id',$product_ids)->pluck('name')->toArray();
          }
          $new_orders['qty'] = 1;
          $new_orders['price'] = San_Help::moneyApi($order->price,$this->currency);
          $new_orders['id'] = $order->id;
          $new_orders['pay_status'] = $order->payment_method;
          $new_orders['status'] = $order->order_status;
          $fin_arr[] = $new_orders;
        }
      }
      if (!empty($fin_arr)) {
        return response()->json([
          'status' => 'success',
          'detail' => $fin_arr,
          'status_code' => 200
        ], 200);
      }else{
        return response()->json([
          'status' => 'failure',
          'detail' => 'no product'
        ]);
      }
    }

    function salonProducts(){
      $validator = Validator::make($this->request->all(), [
        'salon_id' => 'required|integer|exists:providers,id'
      ]);
      if ($validator->fails()) {
        $error = $validator->errors()->first();
        return response()->json([
          'status' => 'failure',
          'detail' => $error
        ]);
      }
      $products = Product::where('provider_id',$this->request->salon_id)->get()->toArray();
      foreach ($products as $key => $value) {
        $products[$key] = San_Help::sanReplaceNull($value);
      }
      if (!empty($products)) {
        return response()->json([
          'status' => 'success',
          'detail' => $products,
          'status_code' => 200
        ], 200);
      }else{
        return response()->json([
          'status' => 'failure',
          'detail' => 'no product'
        ]);
      }
    }

    function updateproducts(){
      $cartdata = Cart::with('product')->where('user_id', $this->request->user_id)->get();
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

    function productSearch(){
      $cat = Category::orderBy('id', 'desc')->whereNull('parent_id')->where('type', 'procategory');
      $query = Product::has('provider')->with('reviews')->where('active',1);
      /* Service */
      if ($this->request->has('product')) {
        $cids = $cat->where('name', 'like', '%' . $this->request->product . '%')->pluck('id')->toArray();
        $x = new \stdClass();
        $x->cids = $cids;
        $x->pro_name = $this->request->product;
        $query = $query->where(function($queryy) use($x) {
                      return $queryy->where('name', 'like', '%' . $x->pro_name . '%')
                          ->orWhereIn('category_id', $x->cids);
                  });
      }

      if ($this->request->clr != '' && $this->request->clr != '+') {
        $pro_ids = array();
        foreach ($query->get() as $p_key => $p_value) {
          if ($p_value->color) {
              $colors = explode(',', $p_value->color);
              $colors = array_map('strtolower', $colors);
              if (in_array(trim(strtolower($this->request->clr)), $colors)) {
                  array_push($pro_ids,$p_value->id);
              }
          }
        }
        $query = $query->whereIn('id', $pro_ids);
      }
      if ($this->request->has('minprice') && $this->request->has('maxprice')) {
        $min = $this->request->minprice?$this->request->minprice:0;
        $max = $this->request->maxprice;
        if ($this->request->maxprice =='') {
          $query = $query->where('price','>=', (int) $min);
        }else{
          $query = $query->whereBetween('price', [(int) $min,(int) $max]);
        }
      }
      if ($this->request->has('provider') && $this->request->provider !='') {
        $query = $query->where('provider_id', $this->request->provider);
      }

      $data = $query->get();
      if ($data->isEmpty()) {
        return response()->json([
          'status' => 'success',
          'detail' => array(),
          'status_code' => 200
        ], 200);
      }
      foreach ($data as $key => $value) {
        /* Locality */
        $locality = 0;
        if ($this->request->has('locality')) {
          $cnt_lct = Provider::where('id',$value->provider_id)->where('city_country', 'like', '%' . $this->request->locality . '%')->orWhere('address', 'like', '%' . $this->request->locality . '%')->count();
          if ($cnt_lct <= 0) {
            continue;
          }
        }
        $dat = Provider::find($value->provider_id);
        if ($dat->latitude != '' && $dat->longitude != '') {
          $lat = $dat->latitude;
          $lng = $dat->longitude;
        } else {
          $coords = San_Help::get_Coordinates($dat->address);
          $lat = $coords['lat'];
          $lng = $coords['long'];
        }
        $baseloc['Lat'] = $this->request->cust_lat;
        $baseloc['Lon'] = $this->request->cust_long;
        $sallon_loc['Lat'] = $lat;
        $sallon_loc['Lon'] = $lng;
        $favourtes = User::whereNotNull('fav_products')->select('fav_products')->get();
        $fav_status = 0;
        foreach ($favourtes as $key => $values) {
          $favs = unserialize($values->fav_products);
          if (in_array($value->id, $favs)) {
            $fav_status = 1;
          }
        }
        $value->price = San_Help::moneyApi($value->price,$this->currency);
        $sallon_arr2['fav_status'] = (string) $fav_status;
        $sallon_arr2['avg_rating'] = $value->reviews->avg('rating');
        $distance = San_Help::distance_btwn_loc($baseloc, $sallon_loc);
        $sallon_arr2['distance'] = number_format((float) $distance, 2, '.', '');
        $sallon_arr[] = San_Help::sanReplaceNull(array_merge($value->toArray(), $sallon_arr2));
      }
      /* Near Me */
      if (isset($sallon_arr) && is_array($sallon_arr) && $this->request->has('nearme')) {
        usort($sallon_arr, function ($a, $b) {
          if ($a["distance"] == $b["distance"])
          return 0;
          return ($a["distance"] < $b["distance"]) ? - 1 : 1;
        });
      }
      return response()->json([
        'status' => 'success',
        'detail' => isset($sallon_arr) ? $sallon_arr : array(),
        'status_code' => 200
      ], 200);
    }

    /* Cancel Order */
    function cancelOrder()
    {
      $validator = Validator::make($this->request->all(), [
        'orderid' => 'required|integer|exists:orders,id'
      ]);
      if ($validator->fails()) {
        $error = $validator->errors()->first();
        return response()->json([
          'status' => 'failure',
          'detail' => $error
        ]);
      }
      $type = '';
      $order = Order::find($this->request->orderid);
      $order->order_status = 'cancelled';
      $type = 'order_canceled';
      $order->save();
      return response()->json([
        'status' => 'success',
        'message' => San_Help::sanLang('Order Cancelled Successfully', $this->lng),
        'detail' => San_Help::sanLang('Order Cancelled Successfully', $this->lng),
        'status_code' => 200
      ], 200);
    }

    /* Add Product Review */
    function addProductReview(){
      $validator = Validator::make($this->request->all(), [
        'userid' => 'required|integer|exists:users,id',
        'orderid' => 'required|integer|exists:orders,id',
        'rating' => 'required'
      ]);
      if ($validator->fails()) {
        $error = $validator->errors()->first();
        return response()->json([
          'status' => 'failure',
          'detail' => $error
        ]);
      }
      $user_review = Review::where('user_id',$this->request->userid)->where('type','order')->where('record_id',$this->request->orderid)->first();
      if ($user_review) {
        $user_review->rating = $this->request->rating;
        $user_review->review = $this->request->comment;
        $user_review->updated_at = new \DateTime();
        $user_review->save();
        $msg = 'Updated';
      }else{
        Review::create([
          'record_id' => $this->request->orderid,
          'user_id' => $this->request->userid,
          'rating' => $this->request->rating,
          'review' => $this->request->comment,
          'type' => 'order',
          'created_at' => new \DateTime(),
          'updated_at' => new \DateTime()
        ]);
        $msg = 'Added';
      }
      return response()->json([
        'status' => 'success',
        'message' => San_Help::sanLang('Review '.$msg.' Successfully',$this->lng),
        'detail' => San_Help::sanLang('Review '.$msg.' Successfully',$this->lng),
        'status_code' => 200
      ], 200);
    }

    function getproductReviews($provider_id){
      $review_query = Review::with('user')->where(function ($query) {
        $query->where('type', '=', 'product')->orWhere('type', '=', 'order');
      })->get();
      $n_rating = array();
      $nfin_array = array();
      foreach ($review_query as $keyy => $value) {
        if ($value['type'] == 'order') {
          $order = Order::find($value->record_id);
          if ($order && is_null($order->product_ids)) {
            foreach (unserialize($order->provider_id) as $key => $products) {
              if ($provider_id == $key) {
                $n_rating['booking_id'] = $value->record_id;
                $n_rating['post_author'] = $value->user->name;
                $n_rating['_rating'] = $value->rating;
                $n_rating['comment_date'] = Carbon::parse($value->created_at)->format('d-m-Y H:i');
                $n_rating['comment_content'] = $value->review;
                $n_rating['user_email'] =$value->user->email;
                $n_rating['user_img'] = $value->user->avatar;
                $n_rating['user_name'] = $value->user->name;
                $n_rating['type'] = 'order';
                // unset($review_query[$keyy]);
              }
            }
          }
        }else{
          $product_ids = Product::where('provider_id',$provider_id)->pluck('id')->toArray();
          if (in_array($value->record_id,$product_ids)) {
            $n_rating['booking_id'] = $value->record_id;
            $n_rating['post_author'] = $value->user->name;
            $n_rating['_rating'] = $value->rating;
            $n_rating['comment_date'] = Carbon::parse($value->created_at)->format('d-m-Y H:i');
            $n_rating['comment_content'] = $value->review;
            $n_rating['user_email'] =$value->user->email;
            $n_rating['user_img'] = User::find($value->user_id)->avatar;
            $n_rating['user_name'] = $value->user->name;
            $n_rating['type'] = 'product';
            // unset($review_query[$keyy]);
          }
        }
        if (!empty($n_rating)) {
          $nfin_array[] = San_Help::sanReplaceNull($n_rating);
        }
      }
      return $nfin_array;
    }

    function productReviews(){
      $validator = Validator::make($this->request->all(), [
        'product_id' => 'required|integer|exists:products,id'
      ]);
      if ($validator->fails()) {
        $error = $validator->errors()->first();
        return response()->json([
          'status' => 'failure',
          'detail' => $error
        ]);
      }
      $review_query = Review::where(function ($query) {
        $query->where('type', '=', 'product')->orWhere('type', '=', 'order');
      })->get()->toArray();
      foreach ($review_query as $keyy => $value) {
        if ($value['type'] == 'order') {
          $order = Order::find($value['record_id']);
          if ($order && is_null($order->product_ids)) {
            foreach (unserialize($order->provider_id) as $key => $products) {
              if (!in_array($this->request->product_id, $products)) {
                unset($review_query[$keyy]);
                continue;
              }else{
                $review_query[$keyy]['image'] = User::find($value['user_id'])->avatar;
              }
            }
          }
        }else{
          if ($this->request->product_id != $value['record_id']) {
            unset($review_query[$keyy]);
            continue;
          }else{
            $review_query[$keyy]['image'] = User::find($value['user_id'])->avatar;
          }
        }
      }
      foreach ($review_query as $key => $value) {
        $review_query[$key] = San_Help::sanReplaceNull($value);
      }
      return response()->json([
        'status' => 'success',
        'detail' => array_values($review_query),
        'status_code' => 200
      ], 200);
    }

    // function productFilter(){
    //
    // }


  }
