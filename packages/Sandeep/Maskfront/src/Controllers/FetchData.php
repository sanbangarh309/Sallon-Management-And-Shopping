<?php
namespace Sandeep\Maskfront\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
use App\User;
use San_Help;
use Illuminate\Support\Str;
use TCG\Voyager\Models\Service;
use TCG\Voyager\Models\Assistant;
use TCG\Voyager\Models\Booking;
use TCG\Voyager\Models\Review;
use TCG\Voyager\Models\Provider;
use TCG\Voyager\Models\Avail;
use Validator;
use Illuminate\Support\Facades\Hash;

class FetchData extends Controller
{
  function fetchUsers(){
    $login_detail = new \stdClass();
    $db_ext = DB::connection('mysql2');
    $users = $db_ext->table('wp_users')->skip(801)->take(200)->get();
    $role_id = '';
    $total_user = [];
    foreach ($users as $key => $user) {
      $capability = $this->getUserMeta($user->ID,'wp_capabilities');
      $userdata = $this->getUserMeta($user->ID);
      $sallon_id = $this->getUserMeta($user->ID,'sallon_id');
      $balance = $this->getUserRewardBalance($user->ID);
      // echo '<pre>';print_r($balance);
      // echo '<pre>';print_r($user);
      // echo '<pre>';print_r($userdata);
      if ($sallon_id) {
        $sallon = $db_ext->table('wp_posts')->where('ID',$sallon_id)->first();
        $sallonmeta = $this->getPostMeta($sallon_id);
        if (isset($sallonmeta['salon_settings'])) {
          $aviladata = unserialize($sallonmeta['salon_settings'])['availabilities'][1];
        }
      }
      if ($capability) {
        $roles = unserialize($capability);
      }
      $pass = San_Help::gen_password(6,8,true,true,true);
      if (isset($roles['service_provider'])) {
        $role_id = 2;
      }
      if (isset($roles['sln_customer'])) {
        $role_id = 3;
      }
      if (isset($roles['sln_staff'])) {
        $role_id = 4;
      }
      if ($user->user_email) {
        $validator = Validator::make(array('email'=>$user->user_email), [
          'email' => 'required|unique:users|max:100',
        ]);
        // if (!$validator->fails()) {
          $name = $user->display_name;
          $phone = '';
          if (isset($userdata['_sln_phone'])) {
            $phone =  $userdata['_sln_phone'];
          }
          if (!$name) {
            $name = $userdata['first_name'];
          }
          if (!$name && isset($userdata['last_name'])) {
            $name = $userdata['last_name'];
          }
          if (!$name) {
            $name = $user->user_nicename;
          }
          if (!$phone && isset($userdata['contact_number'])) {
            $phone =  $userdata['contact_number'];
          }
          if ($name) {
            $customuser = new User();
            $customuser->password = Hash::make($pass);
            $customuser->real_pass = $pass;
            $customuser->email = $user->user_email;
            $customuser->lname = $userdata['last_name'];
            $customuser->address = isset($userdata['_sln_address']) ? $userdata['_sln_address'] : '';
            if (isset($userdata['date_of_birth']) && $userdata['date_of_birth'] !='') {
              $customuser->dob = $userdata['date_of_birth'];
            }
            $customuser->email = $user->user_email;
            $customuser->name = $name;
            $customuser->phone = $phone;
            $customuser->rewardpoint_balance = $balance;
            $customuser->source = isset($userdata['device_type']) ? $userdata['device_type'] : 'web';
            $customuser->gender = isset($userdata['_sln_gender_field']) ? $userdata['_sln_gender_field'] : 'Male';
            $customuser->role_id = $role_id;
            $customuser->wp_id = $user->ID;
            // $customuser->save();
            // DB::table('user_roles')->insert(
            //   ['user_id' => $customuser->id, 'role_id' => $role_id]
            // );
            $this->updateProfilePic($user->ID);
            array_push($total_user,$user->ID);
            $pro_count = 0;
            if ($role_id == 2) {
              // $this->addTeam($user->ID);
              // array_push($total_user,$user->ID);
              // $id = $this->addProvider(array_merge(array('san_user_id'=>$customuser->id),(array) $sallon, $sallonmeta, $userdata));
              // $avail = new Avail();
              // $avail->provider_id = $id;
              // $avail->availability = isset($aviladata) ? serialize($aviladata) : '';
              // $avail->extra = isset($sallonmeta['extra_features']) ? $sallonmeta['extra_features'] : '';
              // $avail->save();
              // $pro_count++;
            }
            if ($role_id == 3) {
              // $this->updateUser($user->ID);
              // array_push($total_user,$user->ID);
              // $this->addReview($user->user_email);
              // array_push($total_user,$user->ID);
            }
          }
          /* Send Email */
          // $login_detail->name = isset($user->display_name) ?  : $userdata['first_name'];
          // $login_detail->password = $pass;
          // $login_detail->from = 'digittrix@gmail.com';
          // $login_detail->to = $user->user_email;
          // $login_detail->subject = 'Mask - Your Login Credentials';
          // San_Help::sanSendMail('maskFront::emails.send_login_cred_change',$login_detail);
        // }

      }
    }
    return $total_user;
  }

  public function addProvider($data){
    $pro = new Provider();
    if (isset($data['post_title']) && $data['san_user_id'] !='') {
      if (strpos($data['post_title'], '[:en]') !== false) {
        // $name = San_Help::get_string_between($data['post_title'],'[:en]','[:ar]');
        $name = str_replace("[:en]","",$data['post_title']);
        $name = str_replace("[:ar]",",",$name);
        $name = str_replace("[:]","",$name);
      }else{
        $name = $data['post_title'];
      }
      if (strpos($data['post_content'], '[:en]') !== false) {
        // $content = San_Help::get_string_between($data['post_content'],'[:en]','[:ar]');
        $content = str_replace("[:en]","",$data['post_content']);
        $content = str_replace("[:ar]",",",$content);
        $content = str_replace("[:]","",$content);
      }else{
        $content = $data['post_content'];
      }
      // echo '<pre>';print_r($data);
      if (isset($data['post_title'])) {
        $pro->name = $name;
      }
      $pro->id = $data['san_user_id'];
      if (isset($data['salon_email'])) {
        $pro->email = $data['salon_email'];
      }
      if (isset($data['post_content'])) {
        $pro->description = $content;
      }
      if (isset($data['salon_phone'])) {
        $pro->phone = $data['salon_phone'];
      }
      if (isset($data['salon_address'])) {
        $pro->address = $data['salon_address'];
      }
      if (isset($data['city_country'])) {
        $pro->city_country = $data['city_country'];
      }
      if (isset($data['latitude'])) {
        $pro->latitude = $data['latitude'];
      }
      if (isset($data['longitude'])) {
        $pro->longitude = $data['longitude'];
      }
      if (isset($data['membership_plan'])) {
        $pro->membership = $data['membership_plan'];
      }
      if (isset($data['device_token'])) {
        $pro->device_token = $data['device_token'];
      }
      if (isset($data['street_address']) || isset($data['salon_address'])) {
        $addr = '';
        if (isset($data['salon_address'])) {
          $addr = $data['salon_address'];
        }
        $pro->street_address = isset($data['street_address']) ? $data['street_address'] : $addr;
      }
      $pro->status = isset($data['approval_status']) ? $data['approval_status'] : 0;
      $pro->save();
      return $pro->id;
    }

  }

  function fetchBookings($uid){
    $db_ext = DB::connection('mysql2');
    $bookings = $db_ext->table('wp_posts')->where('post_author',$uid)->where('post_type','sln_booking')->get();
    if (!$bookings->isEmpty()) {
      foreach ($bookings as $key => $value) {
        $bkngmeta = $this->getPostMeta($value->ID);
        $aids = array();
        $sids = array();
        if (isset($bkngmeta['_sln_booking_sallon_id'])) {
          if (isset($bkngmeta['_sln_booking_services']) && $bkngmeta['_sln_booking_services'] != '') {
            $serdata = unserialize($bkngmeta['_sln_booking_services']);
            foreach ($serdata as $serkey => $servalue) {
              if (isset($servalue['attendant'])) {
                $orgAss = Assistant::where('wp_id',$servalue['attendant'])->first();
                if ($orgAss) {
                  array_push($aids,$orgAss->id);
                }
              }
              if (isset($servalue['service'])) {
                $orgSer = Service::where('wp_id',$servalue['service'])->first();
                if ($orgSer) {
                  array_push($sids,$orgSer->id);
                }

              }
            }
          }
          $user = User::where('wp_id',$bkngmeta['_sln_booking_sallon_id'])->first();
          if ($user) {
            $book = new Booking();
            $book->user_id = $uid;
            $book->salon_id = $user->id;
            $book->assistent_ids = implode(',', $aids);
            $book->service_ids = implode(',', $sids);
            if (isset($bkngmeta['_sln_booking_date'])) {
              $book->book_date = $bkngmeta['_sln_booking_date'];
            }
            if (isset($bkngmeta['_sln_booking_time'])) {
              $book->time = $bkngmeta['_sln_booking_time'];
            }
            if (isset($bkngmeta['_sln_booking_amount'])) {
              $book->price = $bkngmeta['_sln_booking_amount'];
            }
            if (isset($bkngmeta['_sln_booking_firstname'])) {
              $book->first_name = $bkngmeta['_sln_booking_firstname'];
            }
            $book->pay_method = 'cash';
            if (isset($bkngmeta['_sln_booking_lastname'])) {
              $book->last_name = $bkngmeta['_sln_booking_lastname'];
            }
            if (isset($bkngmeta['_sln_booking_email'])) {
              $book->email = $bkngmeta['_sln_booking_email'];
            }
            if (isset($bkngmeta['_sln_booking_phone'])) {
              $book->phone = $bkngmeta['_sln_booking_phone'];
            }
            if (isset($bkngmeta['_sln_booking_address'])) {
              $book->address = $bkngmeta['_sln_booking_address'];
            }
            if (isset($bkngmeta['_sln_booking_gender_field'])) {
              $book->gender = $bkngmeta['_sln_booking_gender_field'];
            }
            if (trim($value->post_status) == 'sln-b-completed') {
              $book->status = 'Completed';
            }
            if (trim($value->post_status) == 'sln-b-canceled') {
              $book->status = 'Cancelled';
            }
            if (trim($value->post_status) == 'sln-b-novisit') {
              $book->status = 'NoVisit';
            }
            $book->currency = 'SAR';
            $book->type = 'service';
            $book->wp_id = $value->ID;
            $book->save();
    // echo '<pre>';print_r($book);
          }
        }
      }
    }
  }

  function fetchServices($id){
    $db_ext = DB::connection('mysql2');
    $fin_arra = [];
    $user = User::where('wp_id',$id)->first();
    $services = $db_ext->table('wp_posts')->where('post_type','sln_service')->where('post_author',$id)->where('post_status','publish')->get();
    $sercount = 1;
    foreach ($services as $key => $value) {
      if (strpos($value->post_title, '[:en]') !== false) {
        $name = str_replace("[:en]","",$value->post_title);
        $name = str_replace("[:ar]",",",$name);
        $name = str_replace("[:]","",$name);
      }else{
        $name = $value->post_title;
      }
      if (strpos($value->post_content, '[:en]') !== false) {
        $content = str_replace("[:en]","",$value->post_content);
        $content = str_replace("[:ar]",",",$content);
        $content = str_replace("[:]","",$content);
      }else{
        $content = $value->post_content;
      }
      $srmeta = $this->getPostMeta($value->ID);
      // echo '<pre>'; print_r($services);
      // echo '<pre>'; print_r($srmeta);exit;
      if (isset($name) && $name != '') {
        $ser = new Service();
        $ser->name = $name;
        // $ser->category_id = $this->request->category_id;
        if (isset($srmeta['_sln_service_price'])) {
          $ser->price = $srmeta['_sln_service_price'];
        }else{
          $ser->price = 0;
        }

        $ser->description = $content;
        if (isset($srmeta['_sln_service_unit']) && $srmeta['_sln_service_unit'] !='') {
          $ser->per_hour = $srmeta['_sln_service_unit'];
        }else{
          $ser->per_hour = 0;
        }
        if (isset($srmeta['_sln_service_duration'])) {
          $ser->duration = $srmeta['_sln_service_duration'];
        }else{
          $ser->duration = 0;
        }
        // $ser->parent_service = $this->request->parent_service;
        $ser->provider_id = $user->id;
        $ser->wp_id = $value->ID;
        $ser->save();
      }

      // echo '<pre>';print_r($ser);
      $sercount++;
    }
  }

  function addTeam($id){
    $db_ext = DB::connection('mysql2');
    $user = User::where('wp_id',$id)->first();
    // $user = Service::where('wp_id',$id)->first();
    $attendants = $db_ext->table('wp_posts')->where('post_type','sln_attendant')->where('post_author',$id)->where('post_status','publish')->get();

    foreach ($attendants as $key => $value) {
      $atmeta = $this->getPostMeta($value->ID);
      // echo '<pre>';print_r($value);
      // echo '<pre>';print_r($atmeta);
      $asst = new Assistant();
      if (strpos($value->post_title, '[:en]') !== false) {
        $name = str_replace("[:en]","",$value->post_title);
        $name = str_replace("[:ar]",",",$name);
        $name = str_replace("[:]","",$name);
      }else{
        $name = $value->post_title;
      }
      if ($name) {
        $asst->name = $name;
        $asst->provider_id = $user->id;
        $asst->user_id = 1;
        $asst->wp_id = $value->ID;
        // $asst->service_ids = serialize($this->request->service_ids);
        $asst->save();
      }
    }
  }

  function addReview($email){
    $db_ext = DB::connection('mysql2');
    $comments = $db_ext->table('wp_comments')->where('comment_type','sln_review')->where('comment_author_email',$email)->get();
    if (!$comments->isEmpty()) {
      foreach ($comments as $key => $value) {
        $booking = Booking::where('wp_id',$value->comment_post_ID)->first();
        if ($booking) {
          $rev = new Review();
          $rev->record_id = $booking->id;
          $rev->user_id = $booking->user_id;
          $rev->rating = 0;
          $rev->review = $value->comment_content;
          $rev->type = 'booking';
          $rev->created_at = $value->comment_date;
          $rev->updated_at = new \DateTime();
          $rev->save();
          // echo '<pre>';print_r($rev);
        }
      }
    }
  }

  function addRewards($id){
    $db_ext = DB::connection('mysql2');
    $rewards = $db_ext->table('wp_reward_points')->where('user_id',$id)->get();
    $user = User::where('wp_id',$id)->first();
    if (!$rewards->isEmpty()) {
      foreach ($rewards as $key => $value) {
        $booking = Booking::where('wp_id',$value->relation)->first();
        if ($booking) {
          $data = array(
            'user_id' => $user->id,
            'rewards' => $value->rewards,
            'type' => $value->type,
            'entry_type' => $value->entry_type,
            'total_rewards' => $value->total_rewards,
            'relation' => $booking->id,
            'created_at' => $value->date_time
          );
          // if (!empty($data)) {
            // echo '<pre>';print_r($value);
            // echo '<pre>';print_r($data);
          // }
          \TCG\Voyager\Models\Reward::firstOrCreate($data);
        }
      }
    }
  }

  function updateFav($id){
    $db_ext = DB::connection('mysql2');
    $fav = array();
    $favs = $db_ext->table('wp_favorite_provider')->where('user_id',$id)->get();
    foreach ($favs as $favkey => $faValue) {
      $orguser = User::where('wp_id',$faValue->provider_id)->first();
      // echo '<pre>';print_r($orguser);
      if ($orguser) {
        $user = User::find($id);
        if ($user) {
          if ($user->favourite) {
              $fav = unserialize($user->favourite);
              if (!in_array($orguser->id, $fav)) {
                  array_push($fav, $orguser->id);
                  $added = 1;
              }else{
                  if (($key = array_search($orguser->id, $fav)) !== false) {
                      unset($fav[$key]);
                  }
                  $added = 0;
              }
          }else{
              array_push($fav, $orguser->id);
              $added = 1;
          }
          $user->favourite = serialize($fav);
          $user->save();
        }
      }
    }
  }
  function updateUser($id){
    $db_ext = DB::connection('mysql2');
    $fav = array();
    $users = $db_ext->table('wp_users')->where('ID',$id)->get();
    foreach ($users as $usrkey => $usrValue) {
      $orguser = User::where('wp_id',$usrValue->ID)->first();
      $umeta = $this->getUserMeta($usrValue->ID);
      if ($orguser) {
        $user = Provider::find($orguser->id);
        if ($user && isset($umeta['_type_of_service'])) {
          switch (trim($umeta['_type_of_service'])) {
            case 'Beauty Center':
              $umeta['_type_of_service'] = 'cosmetic_center';
              break;

            case 'Gift':
              $umeta['_type_of_service'] = 'gift_shop';
              break;

            case 'Photography studios':
              $umeta['_type_of_service'] = 'photography_studios';
              break;

            case 'Beauty Centers':
              $umeta['_type_of_service'] = 'cosmetic_center';
              break;

            case 'Cosmetic Center':
              $umeta['_type_of_service'] = 'cosmetic_center';
              break;

            case 'Fashion Designers':
              $umeta['_type_of_service'] = 'fashion_designers';
              break;

            case 'Make up':
              $umeta['_type_of_service'] = 'makeup_artist';
              break;

            case 'Salons':
              $umeta['_type_of_service'] = 'salons';
              break;

            default:
              // code...
              break;
          }
          // echo '<pre>';print_r($umeta);
          $user->type = $umeta['_type_of_service'];
          $user->save();
        }
      }
    }
  }

  function updateProfilePic($id){
    $db_ext = DB::connection('mysql2');
    // $wp_user = $db_ext->table('wp_users')->where('ID',$id)->first();
      $orguser = User::where('wp_id',$id)->first();
      $umeta = $this->getUserMeta($id);
      if ($orguser && isset($umeta['user_avatar'])) {
        // $attchmnt = $db_ext->table('wp_posts')->where('ID',$umeta['user_avatar'])->first();
        $pmeta = $this->getPostMeta($umeta['user_avatar']);
        if(isset($pmeta['_wp_attached_file']) && $pmeta['_wp_attached_file'] !=''){
          $imgarr = explode('/',$pmeta['_wp_attached_file']);
          if($orguser->role_id == 2){
            $provider = Provider::find($orguser->id);
            $provider->avatar = 'providers/February2019/'.end($imgarr);
            $provider->save();
          }else{
            $orguser->avatar = 'users/February2019/'.end($imgarr);
            $orguser->save();
          }
          // echo '<pre>';print_r($orguser);
          
        }
      }
  }

  public function getUserMeta($id,$key=''){
    $db_ext = DB::connection('mysql2');
    $new_arr = array();
    if ($key) {
      $value = $db_ext->table('wp_usermeta')->where('user_id',$id)->where('meta_key',$key)->select('meta_key','meta_value')->first();
      if ($value) {
        return $value->meta_value;
      }
    }else{
      $data = $db_ext->table('wp_usermeta')->where('user_id',$id)->select('meta_key','meta_value')->get();
      if ($data) {
        foreach ($data as $key => $value) {
          $new_arr[$value->meta_key] = $value->meta_value;
        }
      }
      return $new_arr;
    }
  }
  public function getUserRewardBalance($id){
    $db_ext = DB::connection('mysql2');
    $balance = $db_ext->table('wp_reward_points')->where('user_id',$id)->sum('rewards');
    return $balance;
  }

  public function getPostMeta($id){
    $db_ext = DB::connection('mysql2');
    $new_arr = array();
    // ->where('meta_key',$key)
    $data = $db_ext->table('wp_postmeta')->where('post_id',$id)->select('meta_key','meta_value')->get();
    if ($data) {
      foreach ($data as $key => $value) {
        if (isset($value->meta_key) && isset($value->meta_value)) {
          $new_arr[$value->meta_key] = $value->meta_value;
        }
      }
    }
    return $new_arr;
  }
}
