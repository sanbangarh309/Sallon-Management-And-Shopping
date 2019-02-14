<?php
namespace Sandeep\Maskfront;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response as FacadeResponse;
use App;
use Mail;
use DB;
use File;
use Lang;
use Illuminate\Support\Str;
use App\User;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Sandeep\Maskfront\Currency;
use Sandeep\Maskfront\Money;
class San_Help
{
	public static function san_Asset($path, $secure = null)
	{
		return App::make('url')->to('/packages/Sandeep/Maskfront/resources/assets/'.$path);
	}

	public static function sanLang($value,$lang=''){
		if ($lang == '') {
			$lang = app()->getLocale();
		}
		$finlan = Lang::get('maskLang::en.'.$value.'.'.$lang);
		if (strpos($finlan, 'maskLang::en.') !== false) {
			$finlan = Lang::get('maskLang::en.'.strtolower($value).'.'.$lang);
			if (strpos($finlan, 'maskLang::en.') !== false) {
				return $value;
			}
		}
		return $finlan;
	}

	public static function sanGetLang($value,$lang=''){
		if ($lang == '') {
			$lang = app()->getLocale();
		}
		if (strpos($value, ',') !== false && strpos($value, '[:en]') === false) {
			$val = explode(',', $value);
			if ($lang == 'ar') {
				return $val[1];
			}else{
				return $val[0];
			}
		}
		if ((strpos($value, '[:en]') !== false) || strpos($value, '[:ar]') !== false) {
			$content_blocks = Self::get_language_blocks($value);
			foreach ($content_blocks as $key => $content_block) {
				if ($content_block == '[:en]' && $lang == 'en') {
					return $content_blocks[$key+1];
				}
				if ($content_block == '[:ar]' && $lang == 'ar') {
					return $content_blocks[$key+1];
				}
			}
			return $value;
		}else{
			return $value;
		}
	}

	public static function get_language_blocks($text) {
		$split_regex = "#(<!--:[a-z]{2}-->|<!--:-->|\[:[a-z]{2}\]|\[:\]|\{:[a-z]{2}\}|\{:\})#ism";
		return preg_split($split_regex, $text, -1, PREG_SPLIT_NO_EMPTY|PREG_SPLIT_DELIM_CAPTURE);
	}

	public static function money($amount, $currency = 'SAR', $convert = false)
	{
		$curr = session()->get('currency');
		if (isset($curr) && $curr !='') {
			$currency = $curr;
		}
		// $currency_data = config('money')[$currency];
		// print_r($currency_data);
		$amount = is_numeric($amount) ? $amount : 0;
		foreach (setting('site') as $key => $value) {
			if ($key === strtolower($currency)) {
				return round($amount * $value, 2);
			}
		}
		return $amount;
		// return round($amount * $currency_data['convert_amnt'], $currency_data['precision']).' '.$currency;
		// return new Money($amount, Self::currency($currency), $convert);
	}

	public static function sanMoney($currency,$amount){
		// foreach (setting('site') as $key => $value) {
		// 	if ($key === strtolower($toCurrency)) {
		// 		echo '<pre>';print_r($key.':-   '.$value);
		// 	}
		// }
		// exit;
	}

	public static function moneyApi($amount, $currency = 'SAR', $convert = false)
	{
		$curr = session()->get('currency');
		if (isset($curr) && $curr !='') {
			$currency = $curr;
		}
		// $currency_data = isset(config('money')[$currency]) ? config('money')[$currency] : 'SAR';
		// print_r($currency_data);
		$amount = is_numeric($amount) ? $amount : 0;
		// if (is_array($currency_data)) {
		// 	return round($amount * $currency_data['convert_amnt'], $currency_data['precision'] ? $currency_data['precision'] : 2);
		// }else{
		// 	return $amount;
		// }
		foreach (setting('site') as $key => $value) {
			if ($key === strtolower($currency)) {
				return round($amount * $value, 2);
			}
		}
		return number_format($amount, 2, '.', '');
		// return new Money($amount, Self::currency($currency), $convert);
	}

	public static function fixerCurrency($currency_from,$currency_to,$currency_input){
		// http://data.fixer.io/api/latest?access_key=b351d69bc03f985b204b2e7875c4fbff
		// return $currency_output;
		$url = "http://data.fixer.io/api/latest?access_key=b351d69bc03f985b204b2e7875c4fbff";
		$client = new \GuzzleHttp\Client();
		$res = $client->request('GET', $url);
		$data = json_decode($res->getBody());
		echo '<pre>';print_r($data);exit;
	}

	public static function currency($currency)
	{
		return new Currency($currency);
	}

	/* Set Env Variable */
	public static function set_env($param, $value) {
		$envfile = San_Help::openFile('.env');
		$line = San_Help::getLineString('.env', $param.'=');
		$envfile = str_replace($line, $param . "=" . $value."\n", $envfile);
		file_put_contents('.env', $envfile);

		$_ENV[$param] = $value;
		putenv($param . "=" . $value);
	}
	/* Open File */
	public static function openFile($from) {
		$md = file_get_contents($from);
		return $md;
	}
	/* Get Lines */
	public static function getLineString($fileName, $str) {
		$lines = file($fileName);
		foreach ($lines as $lineNumber => $line) {
			if (strpos($line, $str) !== false) {
				return $line;
			}
		}
		return -1;
	}
	/* Laravel Version */
	public static function laravel_ver() {
		$var = \App::VERSION();

		if(starts_with($var, "5.2")) {
			return 5.2;
		} else if(starts_with($var, "5.3")) {
			return 5.3;
		} else if(substr_count($var, ".") == 3) {
			$var = substr($var, 0, strrpos($var, "."));
			return $var."-str";
		} else {
			return floatval($var);
		}
	}

	/* Create Thumbnails */
	public static function createThumbnail($filepath, $thumbpath, $thumbnail_width, $thumbnail_height, $background=false) {
		list($original_width, $original_height, $original_type) = getimagesize($filepath);
		if ($original_width > $original_height) {
			$new_width = $thumbnail_width;
			$new_height = intval($original_height * $new_width / $original_width);
		} else {
			$new_height = $thumbnail_height;
			$new_width = intval($original_width * $new_height / $original_height);
		}
		$dest_x = intval(($thumbnail_width - $new_width) / 2);
		$dest_y = intval(($thumbnail_height - $new_height) / 2);
		if ($original_type === 1) {
			$imgt = "ImageGIF";
			$imgcreatefrom = "ImageCreateFromGIF";
		} else if ($original_type === 2) {
			$imgt = "ImageJPEG";
			$imgcreatefrom = "ImageCreateFromJPEG";
		} else if ($original_type === 3) {
			$imgt = "ImagePNG";
			$imgcreatefrom = "ImageCreateFromPNG";
		} else {
			return false;
		}
		$old_image = $imgcreatefrom($filepath);
		$new_image = imagecreatetruecolor($thumbnail_width, $thumbnail_height); // creates new image, but with a black background
		// figuring out the color for the background
		if(is_array($background) && count($background) === 3) {
			list($red, $green, $blue) = $background;
			$color = imagecolorallocate($new_image, $red, $green, $blue);
			imagefill($new_image, 0, 0, $color);
			// apply transparent background only if is a png image
		} else if($background === 'transparent' && $original_type === 3) {
			imagesavealpha($new_image, TRUE);
			$color = imagecolorallocatealpha($new_image, 0, 0, 0, 127);
			imagefill($new_image, 0, 0, $color);
		}
		imagecopyresampled($new_image, $old_image, $dest_x, $dest_y, 0, 0, $new_width, $new_height, $original_width, $original_height);
		$imgt($new_image, $thumbpath);
		return file_exists($thumbpath);
	}

	/* Generate Password */
	public static function gen_password($chars_min=6, $chars_max=8, $use_upper_case=false, $include_numbers=false, $include_special_chars=false) {
		$length = rand($chars_min, $chars_max);
		$selection = 'aeuoyibcdfghjklmnpqrstvwxz';
		if($include_numbers) {
			$selection .= "1234567890";
		}
		if($include_special_chars) {
			$selection .= "!@\"#$%&[]{}?|";
			}
			$password = "";
			for($i=0; $i<$length; $i++) {
				$current_letter = $use_upper_case ? (rand(0,1) ? strtoupper($selection[(rand() % strlen($selection))]) : $selection[(rand() % strlen($selection))]) : $selection[(rand() % strlen($selection))];
				$password .=  $current_letter;
			}
			return $password;
		}
		/* Get Tables */
		public static function getDBTables() {
			if(env('DB_CONNECTION') == "sqlite") {
				$tables_sqlite = DB::select('select * from sqlite_master where type="table"');
				$tables = array();
				foreach ($tables_sqlite as $table) {
					if($table->tbl_name != 'sqlite_sequence') {
						$tables[] = $table->tbl_name;
					}
				}
			} else if(env('DB_CONNECTION') == "pgsql") {
				$tables_pgsql = DB::select("SELECT table_name FROM information_schema.tables WHERE table_type = 'BASE TABLE' AND table_schema = 'public' ORDER BY table_name;");
				$tables = array();
				foreach ($tables_pgsql as $table) {
					$tables[] = $table->table_name;
				}
			} else if(env('DB_CONNECTION') == "mysql") {
				$tables = DB::select('SHOW TABLES');
			} else {
				$tables = DB::select('SHOW TABLES');
			}
			return $tables;
		}

		public static function appendFile($from, $to) {
			$md = file_get_contents($from);
			file_put_contents($to, $md, FILE_APPEND);
		}

		public static function copyFile($from, $to) {
			if(!file_exists(dirname($to))) {
				$this->info("mkdir: (".dirname($to).")");
				mkdir(dirname($to));
			}
			copy($from, $to);
		}

		public static function copyFolder($src,$dst) {
			$dir = opendir($src);
			@mkdir($dst, 0777, true);
			while(false !== ( $file = readdir($dir)) ) {
				if (( $file != '.' ) && ( $file != '..' )) {
					if ( is_dir($src . '/' . $file) ) {
						San_Help::copyFolder($src . '/' . $file,$dst . '/' . $file);
					}
					else {
						// ignore files
						if(!in_array($file, [".DS_Store"])) {
							copy($src . '/' . $file, $dst . '/' . $file);
						}
					}
				}
			}
			closedir($dir);
		}

		public static function get_minutes( $start, $end ,$c_date_str, $sel_date_str) {
			$gmdate = gmdate("H:i");
			$gmdate = strtotime( "$gmdate + 30 mins");
			$minutes = array();
			while ( strtotime($start) <= strtotime($end) ) {
				if($c_date_str == $sel_date_str){

					if($gmdate<=strtotime($start)){
						$minutes[] = date("H:i", strtotime( "$start" ) );
					}
				}else{
					$minutes[] = date("H:i", strtotime( "$start" ) );
				}
				$start = date("H:i", strtotime( "$start + 30 mins"));
			}
			return $minutes;
		}

		public static function get_minutess ( $start, $end,$c_date_str, $sel_date_str ) {
			$gmdate = gmdate("H:i");
			$gmdate = strtotime( "$gmdate + 30 mins");
			if($end=='00:00'){
				$end='24:00';
			}
			$minutess = [];
			while ( strtotime($start) <= strtotime($end) && $start!='00:00' ) {
				if($c_date_str == $sel_date_str){
					if($gmdate<=strtotime($start)){
						$minutess[] = date("H:i", strtotime( "$start" ) );
					}
				}else{
					$minutess[] = date("H:i", strtotime( "$start" ) );
				}
				// $minutess[] = date("H:i", strtotime( "$start" ) );
				$start = date("H:i", strtotime( "$start + 30 mins")) ;
			}
			return $minutess;
		}

		/* Replace array or objects null value with empty */
		public static function sanReplaceNull($array)
		{
			if (is_object($array)) {
				foreach ($array as $key => $value) {
					if (is_null($value)) {
						$array->$key = '';
					}
				}
			} elseif (is_array($array)) {
				$array = array_map(function ($value) {
					return $value === NULL ? "" : $value;
				}, $array);
			}
			return $array;
		}

		public static function getSliderImages(){
			$slider_folder = storage_path('app/public/slider/');
			$slider_images = array();
			$dir = opendir($slider_folder);
			while(false !== ( $file = readdir($dir)) ) {
				if (( $file != '.' ) && ( $file != '..' )) {
					if ( !is_dir($slider_folder . '/' . $file) ) {
						array_push($slider_images, url('files/slider/'.$file));
					}
				}
			}
			closedir($dir);
			return $slider_images;
		}

		public static  function get_file($path)
		{
			$path = storage_path('app/public/').$path;
			// print($path);exit;
			if(!$path) {
				return response()->json([
					'status' => "failure",
					'message' => "Unauthorized Access 1"
				]);
			}
			if(!File::exists($path))
			abort(404);
			$file = File::get($path);
			$type = File::mimeType($path);

			$download = Input::get('download');
			if(isset($download)) {
				return response()->download($path, $upload->name);
			} else {
				$response = FacadeResponse::make($file, 200);
				$response->header("Content-Type", $type);
			}
			return $response;
		}

		public static function getName($path){
			if ($path) {
				$arr = explode('/', $path);
				return end($arr);
			}
		}

		public static function get_string_between($string, $start, $end){
			$string = ' ' . $string;
			$ini = strpos($string, $start);
			if ($ini == 0) return '';
			$ini += strlen($start);
			$len = strpos($string, $end, $ini) - $ini;
			return substr($string, $ini, $len);
		}

		public static function sanSendMail($file_path,$data){
			Mail::send($file_path, ['data' => $data], function ($m) use ($data) {
				$m->from($data->from, 'Mask Admin');
				// ->cc('sandeep.digittrix@gmail.com',$data->name)
				$m->to($data->to, $data->name)->subject($data->subject);
			});
		}

		public static function get_Coordinates($addr)
		{
			$address = urlencode($addr);
			$url = "https://maps.google.com/maps/api/geocode/json?address=$address&sensor=false&key=".config('maskfront.google_key');
			$client = new \GuzzleHttp\Client();
			$res = $client->request('GET', $url);
			$data = json_decode($res->getBody());
			// echo "<pre>";print_r($data->results[0]);
			if ($res->getStatusCode() != 200)
			{
				return FALSE;
			}
			else
			{
				$city = '';
				if ($data->results) {
					foreach ($data->results[0]->address_components as $key => $local) {
						if ($local->types[0] == 'locality') {
							$city = $local->long_name;
						}
					}
					$return = array('lat' => $data->results[0]->geometry->location->lat, 'long' => $long = $data->results[0]->geometry->location->lng,'city'=>$city);
					return $return;
				}else{
					return;
				}
			}
		}

		public static function getAssService($ids){
			if (is_array($ids)) {
				$services = \TCG\Voyager\Models\Service::whereIn('id',$ids)->pluck('name')->toArray();
			}else{
				$ids = explode(',', $ids);
				$services = \TCG\Voyager\Models\Service::whereIn('id',$ids)->pluck('name')->toArray();
			}
			return $services;
		}

		public static function durationData($start="00:00",$end="23:00"){
			$tStart = strtotime($start);
			$tEnd = strtotime($end);
			$tNow = $tStart;
			$dur_data = array();
			while($tNow <= $tEnd){
				$tNow = strtotime('+30 minutes',$tNow);
				array_push($dur_data, date('h:i A', $tNow));
			}
			return $dur_data;
		}

		public static function GetDistance($lat1, $lat2, $long1, $long2)
		{
			$url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=".$lat1.",".$long1."&destinations=".$lat2.",".$long2."&mode=driving&key=".config('maskfront.google_key');
			$client = new \GuzzleHttp\Client();
			$res = $client->request('GET', $url);
			$data = json_decode($res->getBody());
			// echo "<pre>";print_r($data->rows[0]->elements[0]);
			if (isset($data->rows[0]->elements[0]->status) && $data->rows[0]->elements[0]->status =='NOT_FOUND' || $data->rows[0]->elements[0]->status == 'ZERO_RESULTS') {
				return false;
			}
			$dist = $data->rows[0]->elements[0]->distance->text;
			$time = $data->rows[0]->elements[0]->duration->text;
			return array('distance' => $dist, 'time' => $time);
		}

		public static function distance_btwn_loc($baseloc,$sallon_loc) {
			$long_lat = array();
			$latitudeFrom = $baseloc['Lat'];
			$longitudeFrom = $baseloc['Lon'];
			$latitudeTo = $sallon_loc['Lat'];
			$longitudeTo = $sallon_loc['Lon'];
			//Calculate distance from latitude and longitude
			$theta = $longitudeFrom - $longitudeTo;
			$dist = sin(deg2rad($latitudeFrom)) * sin(deg2rad($latitudeTo)) +  cos(deg2rad($latitudeFrom)) * cos(deg2rad($latitudeTo)) * cos(deg2rad($theta));
			$dist = acos($dist);
			$dist = rad2deg($dist);
			$miles = $dist * 60 * 1.1515;
			$distance = ($miles * 1.609344);//.' km';
			return $distance;
		}

		public static function array_sort_bycolumn(&$array,$column,$dir = 'asc') {
			foreach($array as $a) $sortcol[$a[$column]][] = $a;
			ksort($sortcol);
			foreach($sortcol as $col) {
				foreach($col as $row) $newarr[] = $row;
			}

			if($dir=='desc') $array = array_reverse($newarr);
			else $array = $newarr;
		}

		public static function uploadFile(){
			$path = 'app/public/'.app('request')->slug.'/'.date('FY');
			if(Input::hasFile('image')) {
				$file = Input::file('image');
				if (!file_exists($path)) {
					mkdir($path, 0777, true);
				}
				$folder = storage_path($path);
				$ext = $file->getClientOriginalExtension();
				if ($ext) {
					$filename = Str::random(20).'.'.$ext;
				}else{
					$filename = Str::random(20);
				}
				$upload_success = $file->move($folder, $filename);
				if( $upload_success ) {
					return app('request')->slug.'/'.date('FY').'/'.$filename;
				}
				return false;
			}else{
				return true;
			}
		}

		public static function uploadAnyFile(){
			$path = 'app/public/'.app('request')->slug.'/'.date('FY');
			$folder = storage_path($path);
			if (!file_exists($path)) {
				mkdir($path, 0777, true);
			}
			$file = app('request')->file();
			foreach ($file as $key => $upld) {
				$ext = $upld->getClientOriginalExtension();
				if ($ext) {
					$filename = Str::random(20).'.'.$ext;
				}else{
					$filename = Str::random(20);
				}
				$upload_success = $upld->move($folder, $filename);
				if( $upload_success ) {
					return app('request')->slug.'/'.date('FY').'/'.$filename;
				}
			}
			return false;
		}

		public static function uploadFiles(){
			$images = array();
			$path = 'app/public/'.app('request')->slug.'/'.date('FY');
			if (!file_exists($path)) {
				mkdir($path, 0777, true);
			}
			$folder = storage_path($path);
			$files = Input::file(app('request')->slug.'_images');
			if (!empty($files) && is_array($files)) {
				foreach ($files as $key => $file) {
					$ext = $file->getClientOriginalExtension();
					if ($ext) {
						$filename = Str::random(20).'.'.$ext;
					}else{
						$filename = Str::random(20);
					}
					$upload_success = $file->move($folder, $filename);
					if( $upload_success ) {
						array_push($images, app('request')->slug.'/'.date('FY').'/'.$filename);
					}
				}
			}elseif (!empty($files) && is_object($files)) {
				$filename = Str::random(20);
				$ext = $files->getClientOriginalExtension();
				if ($ext) {
					$filename = Str::random(20).'.'.$ext;
				}else{
					$filename = Str::random(20);
				}
				$upload_success = $files->move($folder, $filename);
				if( $upload_success ) {
					array_push($images, app('request')->slug.'/'.date('FY').'/'.$filename);
				}
			}
			return $images;
		}

		public static function updateRewardPoints($_data){
			$reward_arr = config('maskfront.reward_values');
			if(!empty($_data)){
				if($_data['type']=="new_register"){
					$_data['rewardtype'] = 'credit';
					$rewards_earn = $reward_arr[$_data['type']];
					$_data['rewards_earn'] = $rewards_earn;
					Self::update_rewards_($_data);
				}
				if($_data['type']=="redeem_points"){
					$_data['rewardtype'] = 'debit';
					$booking_id = $_data['_booking_id'];
					$_data['rewards_earn'] = $_data['redeemed_points'];
					$_booking_info = Self::get_booking_info($booking_id);
					$_data['user_id'] = $_booking_info->user_id;
					Self::update_rewards_($_data);
				}
				if($_data['type']=="booking_canceled"){
					$_data['rewardtype'] = 'debit';
					$booking_id = $_data['_booking_id'];
					$_booking_info = Self::get_booking_info($booking_id);
					$_data['user_id'] = $_booking_info->user_id;
					$rewards_earn = $reward_arr[$_data['type']];

					$results = Self::_rewardpoints_earnedon_booking($booking_id);
					$_data['user_id'] = $_booking_info->user_id;
					if(!empty($results) && isset($results['0'])){
						$rewards_earn = $results['0']->rewards;
					}else{
						$rewards_earn = $reward_arr[$_data['type']];
					}

					$_data['rewards_earn'] = $rewards_earn;

					Self::update_rewards_($_data);

				}
				if($_data['type']=="booking_rejected"){

					$_data['rewardtype'] = 'debit';
					$booking_id = $_data['_booking_id'];
					$_booking_info = Self::get_booking_info($booking_id);
					$results = Self::_rewardpoints_earnedon_booking($booking_id);
					$_data['user_id'] = $_booking_info->user_id;
					if(!empty($results) && isset($results['0'])){
						$rewards_earn = $results['0']->rewards;
					}else{
						$rewards_earn = $reward_arr[$_data['type']];
					}

					$_data['rewards_earn'] = $rewards_earn;

					Self::update_rewards_($_data);

				}
				if($_data['type']=="new_booking" || $_data['type']=="review_added"){

					$_data['rewardtype'] = 'credit';
					$booking_id = $_data['_booking_id'];
					$_booking_info = Self::get_booking_info($booking_id);
					$_data['user_id'] = $_booking_info->user_id;

					if($_data['type']=="new_booking"){
						$_booking_amount = \TCG\Voyager\Models\Booking::find($booking_id)->price;

						if($_booking_amount<200){
							$rewards_earn = $reward_arr[$_data['type']];
							$_data['rewards_earn'] = $rewards_earn;
						}else{
							$rewards_earn = $reward_arr['new_booking_200'];
							$count_mul = floor($_booking_amount/200);
							$_data['rewards_earn'] = ($rewards_earn*$count_mul);
						}
						if(isset($_SESSION['redeem_points'])){
							$_SESSION['redeem_points']['earned_points'] = $_data['rewards_earn'];
						}
					}elseif($_data['type']=="review_added"){
						$_data['rewards_earn'] = $reward_arr[$_data['type']];
						$_data['rewards_earn'] = 5;
					}

					Self::update_rewards_($_data);

				}

			}
		}

		public static function _rewardpoints_earnedon_booking($booking_id){
			global $wpdb;
			$results = array();
			$results = \TCG\Voyager\Models\Reward::where('entry_type','new_booking')->where('relation',$booking_id)->get()->toArray();
			return $results;
		}

		public static function update_rewards_($_data){
			if(!empty($_data)){
				$user_id = isset(Auth::user()->id) ? Auth::user()->id : $_data['user_id'];
				$total_rewards = 0;
				$relation = '';
				$rewards_record = array();
				$rewards_record = Self::get_user_rewardpoints($user_id,1);

				if(!empty($rewards_record)){
					$total_rewards = end($rewards_record)['total_rewards'];
				}

				if($_data['rewardtype']=='credit'){
					$total_rewards += $_data['rewards_earn'];
				}else{
					$total_rewards -= $_data['rewards_earn'];
				}
// print_r($total_rewards);exit;
				if(isset($_data['_booking_id'])){
					$relation = $_data['_booking_id'];
				}
				$pre_res = \TCG\Voyager\Models\Reward::where('type',$_data['rewardtype'])->where('user_id',$user_id)->where('entry_type',$_data['type'])->where('relation',$relation)->get()->toArray();
				// echo '<pre>';print_r($pre_res);exit;
				if(empty($pre_res)){
					$data = array(
						'user_id' => $user_id,
						'rewards' => $_data['rewards_earn'],
						'type' => $_data['rewardtype'],
						'entry_type' => $_data['type'],
						'total_rewards' => $total_rewards,
						'relation' => $relation
					);
					\TCG\Voyager\Models\Reward::firstOrCreate($data);
					$user = User::find($user_id);
					if ($_data['rewardtype'] == 'credit') {
						$user->rewardpoint_balance = $user->rewardpoint_balance+$total_rewards;
					}else{
						$user->rewardpoint_balance = $user->rewardpoint_balance-$total_rewards;
					}
					$user->save();
				}
			}
		}

		public static function get_user_rewardpoints($user_id,$limit=0){
			$results = array();
			if(!empty($user_id)){
				if($limit==0){
					$limit_query = '';
				}else{
					$limit_query = ' LIMIT '.$limit;
				}
				$results = \TCG\Voyager\Models\Reward::where('user_id',$user_id)->orderBy('id', 'desc')->take($limit)->get()->toArray();
			}
			return $results;
		}

		public static function get_booking_info($id){
			return \TCG\Voyager\Models\Booking::find($id);
		}

		/* Sms Functions */
		public static function sanSendSms($_data){
			if(!empty($_data)){
				if($_data['type']=="new_register"){
					if(!empty($_data['contact_number'])){
						return Self::sendSms($_data);
					}
				}
				if($_data['type']=="booking_accepted" || $_data['type']=="booking_rejected" || $_data['type']=="booking_canceled" || $_data['type']=="new_booking"){

					if(!empty($_data['_booking_id'])){

						$services_msg = '';

						$booking_id = $_data['_booking_id'];
						$_booking_info = Self::get_booking_info($booking_id);

						$_date = $_booking_info->book_date;
						$_time = $_booking_info->time;
						$_booking_services = $_booking_info->service_ids;

						if(!empty($_booking_services)){
							$ser_names = \TCG\Voyager\Models\Service::whereIn('id',explode(',', $_booking_services))->pluck('name')->toArray();
							$service_count = count($ser_names);

							$service_name = implode(',', $ser_names);

							$services_msg = $service_name;

							if($service_count>1){
								$ncount = $service_count-1;
								$services_msg .= ' +'.$ncount .' service';
							}

						}
						$booking_user_id = $_booking_info->user_id;
						if (isset($_data['pro_id']) && is_array($_data['pro_id'])) {
							$_sallon_ids = $_data['pro_id'];
						}elseif (isset($_data['pro_id'])) {
							$_sallon_id = $_data['pro_id'];
						}else{
							$_sallon_id = app('request')->session()->get('pro_id');
						}
						if (is_array($_data['pro_id'])) {
							$salon_datas = \TCG\Voyager\Models\Provider::whereIn('id',$_data['pro_id'])->get();
							foreach ($salon_datas as $key => $salon_data) {
								$_sln_booking_phone = $salon_data->phone ? $salon_data->phone : User::find($_sallon_id)->phone;
								if($_data['type']=="booking_canceled" || $_data['type']=="new_booking" || $_data['type']=="new_order" ){
									$_sln_booking_phone = $salon_data->phone ? $salon_data->phone : User::find($_sallon_id)->phone;
									$_data['contact_number'] = User::find($booking_user_id)->phone ? User::find($booking_user_id)->phone : $_booking_info->phone;
								}

								if($_data['type']=="booking_accepted" || $_data['type']=="booking_rejected" || $_data['type']=="new_order"){
									$_sln_booking_phone = User::find($booking_user_id)->phone ? User::find($booking_user_id)->phone : $_booking_info->phone;
									$_data['contact_number'] = User::find($booking_user_id)->phone ? User::find($booking_user_id)->phone : $_booking_info->phone;
								}
								$_data['booking_user_id'] = $booking_user_id;
								$_data['_sallon_id'] = $salon_data->id;
								$_data['_sallon_name'] = $salon_data->name;
								$_data['_booking_date'] = $_date;
								$_data['_booking_time'] = $_time;
								$_data['_services'] = $services_msg;
								Self::sendSms($_data);
							}
						}else{
							$salon_data = \TCG\Voyager\Models\Provider::find($_sallon_id);
							$_sln_booking_phone = $salon_data->phone ? $salon_data->phone : User::find($_sallon_id)->phone;
							if($_data['type']=="booking_canceled" || $_data['type']=="new_booking" ){
								$_sln_booking_phone = $salon_data->phone ? $salon_data->phone : User::find($_sallon_id)->phone;
								$_data['contact_number'] = User::find($booking_user_id)->phone ? User::find($booking_user_id)->phone : $_booking_info->phone;
							}
							if($_data['type']=="booking_accepted" || $_data['type']=="booking_rejected"){
								$_sln_booking_phone = User::find($booking_user_id)->phone ? User::find($booking_user_id)->phone : $_booking_info->phone;
								$_data['contact_number'] = User::find($booking_user_id)->phone ? User::find($booking_user_id)->phone : $_booking_info->phone;
							}
							$_data['booking_user_id'] = $booking_user_id;
							$_data['_sallon_id'] = $_sallon_id;
							$_data['_sallon_name'] = $salon_data->name;
							$_data['_booking_date'] = $_date;
							$_data['_booking_time'] = $_time;
							$_data['_services'] = $services_msg;
							Self::sendSms($_data);
						}
						return 'done';
					}

				}

			}
		}

		public static function sendSms($_data_rc){
			$final = array();
			$msg = '';
			$url = config('maskfront.sms_url');
			$contact_number = $_data_rc['contact_number'];
			$type = $_data_rc['type'];
			$str = trim($contact_number);
			$contact_number = trim($contact_number);
			$substr = "+";
			if (strpos($str, $substr) === 0 ) {
			}else{
				$contact_number = '+'.$contact_number;
			}
			if($type=="new_register"){
				$otp = $_data_rc['otp'];
				$msg = urlencode('Hi, Please use this OTP to verify your number. '.$otp.' Thanks Mask Team');
			}
			if($type=="booking_accepted" || $type=="booking_rejected" || $type=="booking_canceled" || $type=="new_booking" || $type=="new_order"){
				$_sallon_name = $_data_rc['_sallon_name'];
				$_date = $_data_rc['_booking_date'];
				$_time = $_data_rc['_booking_time'];
				$services_msg = $_data_rc['_services'];
				if($type=="booking_accepted"){
					$msg = urlencode('Hi Your Booking has been Accepted by '.$_sallon_name.' for the '.$services_msg.', '.$_date.' '.$_time.'. Thanks Mask Team');
				}
				if($type=="booking_rejected"){
					$msg = urlencode('Hi, We apologise for inconvenience your booking has been rejected by '.$_sallon_name.' for the '.$services_msg.', '.$_date.' on '.$_time.'. Thanks Mask Team');
				}
				if($type=="booking_canceled"){
					$_user_id = $_data_rc['booking_user_id'];
					$_user_name = User::find($_user_id)->name;
					$msg = urlencode('Hi, We apologise for inconvenience your booking has been cancelled by '.$_user_name.' for the '.$services_msg.', '.$_date.' on '.$_time.'. Thanks Mask Team');
				}
				if($type=="new_booking"){
					$_user_id = $_data_rc['booking_user_id'];
					$_user_name = User::find($_user_id)->name;
					$msg = urlencode('Hi, New Booking Received By '.$_user_name.' for '.$_date.' on '.$_time.' for '.$services_msg.', Please accept/reject. Thanks Mask Team');
				}
			}
			// 'sid' => 'MASKIT',
			if ($msg !='') {
				$fields = array(
					'user' => 'mask-app',
					'password' =>'mask-app.com',
					'msisdn' => $contact_number,
					'sid' => 'MASK',
					'msg' => $msg,
					'fl' => 0,
					'dc' => 8
				);
				$final['headers'] = array(
					'Content-Type: 0'
				);
				$urldata = http_build_query($fields);
				$url = $url.$urldata;
				$final['json'] = array('from'=>'Mask');
				$res = Self::post($url,$final);
				return $res;
			}else{
				return false;
			}
		}
		private static function post($url , $data)
		{
			$client = new Client();
			try {
				$responseGuzzle = $client->request('post', $url, $data);
			} catch (ClientException $e) {
				$responseGuzzle = $e->getResponse();
			}
			return json_decode($responseGuzzle->getBody());
		}




		/* Fucntion to user send Email on any activity */
		public static function send_Email($type,$user_id,$msg_data){
			if (Auth::check()) {
				$user = Auth::user();
			}else{
				$user = User::find($user_id);
			}
			$subject = Self::email_subjects($type);
			$data = array('to' => $user->email,'from'=>'digittrix@gmail.com','name'=>$user->name, 'subject'=>$subject);
			Mail::send('maskFront::emails.bookinginfo', ['type' => $type,'user_id'=>$user_id,'msg_data'=>$msg_data], function ($m) use ($data) {
				$m->from($data['from'], 'Mask Admin');
				$m->to($data['to'], $data['name'])->cc('sandeep.digittrix@gmail.com',$data['name'])->subject($data['subject']);
			});
		}
		public static function send_NewBookingEmail($type,$_sallon_id,$booking_id){
			if ($type == 'new_order') {
				$subject = 'New Order Recieved';
			}else{
				$subject = 'New Booking Recieved';
			}
			if (is_array($_sallon_id)) {
				$users = User::whereIn('id',$_sallon_id)->get();
				foreach ($users as $key => $user) {
					$data = array('to' => $user->email,'from'=>'digittrix@gmail.com','name'=>$user->name, 'subject'=>$subject);
					Mail::send('maskFront::emails.new_booking', ['type' => $type,'booking_id'=>$booking_id], function ($m) use ($data) {
						$m->from($data['from'], 'Mask Admin');
						$m->to($data['to'], $data['name'])->cc('digittrix@gmail.com',$data['name'])->subject($data['subject']);
					});
				}
			}else{
				$user = User::find($_sallon_id);
				$data = array('to' => $user->email,'from'=>'digittrix@gmail.com','name'=>$user->name, 'subject'=>$subject);
				Mail::send('maskFront::emails.new_booking', ['type' => $type,'booking_id'=>$booking_id], function ($m) use ($data) {
					$m->from($data['from'], 'Mask Admin');
					$m->to($data['to'], $data['name'])->cc('digittrix@gmail.com',$data['name'])->subject($data['subject']);
				});
			}
		}

		public static function email_subjects($type){
			if($type=='user_register'){
				$subject = 'Successfully Register';
			}
			elseif($type =='adminuser_register'){
				$subject = 'Service Provider Successfully Register';
			}
			elseif($type =='simpleuser_register'){
				$subject = 'Account Register Successfully';
			}
			elseif($type =='adminsimpleuser_register'){
				$subject = 'New User Register Successfully';
			}
			elseif($type =='userpaid'){
				$subject = 'Account Approved';
			}
			elseif($type =='new_order'){
				$subject = 'New Order';
			}
			elseif($type =='forgot_password'){
				$subject = 'Password Reset Link';
			}
			elseif($type =='booking_accepted'){
				$subject = "Booking Accepted";
			}
			elseif($type =='booking_rejected'){
				$subject = "Booking Refused";
			}
			elseif($type =='booking_canceled'){
				$subject = "Booking Canceled";
			}
			else{
				$subject = '';
			}
			return $subject;
		}
		public static function sanLimited($string,$char = '500',$link,$show = true){
			$string = strip_tags($string);
			if (strlen($string) > $char) {
				$stringCut = substr($string, 0, $char);
				$endPoint = strrpos($stringCut, ' ');
				$string = $endPoint? substr($stringCut, 0, $endPoint) : substr($stringCut, 0);
				if ($show) {
					$string .= '.. <a href="'.$link.'">Read More</a>';
				}
			}
			return $string;
		}
	}
