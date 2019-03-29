<?php
namespace Sandeep\Maskfront\Controllers;
use App\Http\Controllers\Controller;
use App\User;
use San_Help;
use TCG\Voyager\Models\Provider;
use TCG\Voyager\Models\Booking;
use Sandeep\Maskfront\Models\FCMUser;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Validator;

class NotificationController extends Controller
{

    public $san_title;
	public $san_body;
	public $san_token;
	public $gurus;
	public $sent_user_id;
	public $type;
	public $notify_type;
	public $url;
	protected $client = null;

	public function __construct()
	{
		// $action = $request->route()->getAction();
		// if (isset($action['type']) && $action['type'] !='') {
		// 	$this->type = $action['type'];
		// 	$this->san_title = 'Test Title One';
		// 	$this->san_body = array('msg'=>'Test Body Two');
		// 	// $this->sent_user_id = [158,2];
		// 	$this->notify_type = '';
		// }
		// $this->client = new Client();
		$this->url = 'https://fcm.googleapis.com/fcm/send';
    }
    

    function sb_notification_fucntions($booking_id,$type){

    	if(!empty($booking_id)){

            $device_type = '';

    		$_booking_info = Booking::find($booking_id);

    		if($type=="booking_accepted" || $type=="booking_rejected"){

                if(!empty($_booking_info)){

                    $booking_user_id = $_booking_info->user_id;

                    $device_type = User::find($booking_user_id) ? User::find($booking_user_id)->source : 'web';
                    // print_r($device_type);exit;
                    // echo '<br/>booking_id - '.$booking_id;
                    // echo '<br/>device_type_arr - '.$device_type;
                    // echo '<br/>_booking_info - ';
                    // pr($_booking_info);
                    // die();

                    if($device_type=='android'){

                        return $this->send_android_notification($_booking_info,$type);

                    }
                    if($device_type=='ios'){

                        return $this->send_ios_notification($_booking_info,$type);

                    }

                }

            }

            if($type=="booking_canceled"){

    			if(!empty($_booking_info)){

                    $_sallon_id = Booking::find($booking_id) ? Booking::find($booking_id)->salon_id :'';
                    if(empty($_sallon_id) && isset($_booking_info->salon_id)){
                        $_sallon_id = $_booking_info->salon_id;
                    }
                   
                    $device_type = User::find($_booking_info ->user_id) ? User::find($_sallon_id)->source : 'web';

    				if($device_type=='android'){

    					return $this->send_android_notification($_booking_info,$type);

    				}
                    if($device_type=='ios'){

                        return $this->send_ios_notification($_booking_info,$type);

                    }

    			}

    		}

            if($type=="new_booking"){

                if(!empty($_booking_info)){

                    $_sallon_id = Booking::find($booking_id) ? Booking::find($booking_id)->salon_id :'';

                    if(empty($_sallon_id) && isset($_booking_info->salon_id)){
                        $_sallon_id = $_booking_info->salon_id;
                    }
                    $device_type = User::find($_booking_info ->user_id) ? User::find($_sallon_id)->source : 'web';

                    if($device_type=='android'){

                        return $this->send_android_notification($_booking_info,$type);

                    }
                    if($device_type=='ios'){

                        return $this->send_ios_notification($_booking_info,$type);

                    }

                }

            }

    	}

    }




// Function to send Push Notification for Android Phone
	function send_android_notification($_booking_info,$type){
		$body = $title = $device_token = '';
		$booking_user_id = $_booking_info->user_id;
        $booking_id = $_booking_info->id;
        
        $_sallon_id = Booking::find($booking_id) ? Booking::find($booking_id)->salon_id :'';
		if(empty($_sallon_id) && isset($_booking_info->salon_id)){
			$_sallon_id = $_booking_info->salon_id;
		}
		
		if(!empty($_sallon_id)){
			$sallon_name = Provider::find($_sallon_id) ? Provider::find($_sallon_id)->name : '';
		}
		if($type=="booking_accepted" || $type=="booking_rejected"){
            $device_token = User::find($booking_user_id)->device_token;
			if($type=="booking_accepted"){
				$body = 'Your Booking with ID '.$booking_id.' has been accepted by '.$sallon_name;
				$title = 'Booking Accepted';
			}
			if($type=="booking_rejected"){
				$body = 'Your Booking with ID '.$booking_id.' has been rejected by '.$sallon_name;
                $title = 'Booking Rejected';
                $device_token = User::find($_sallon_id)->device_token;
			}
		}
		if($type=="booking_canceled"){
			$body = 'Hi '.$sallon_name.' Your Booking with ID '.$booking_id.' has been Canceled by Customer';
			$title = 'Booking Canceled';
            $device_token = User::find($_sallon_id)->device_token;
            
        }
        if($type=="booking_completed"){
			$body = 'Hi '.$sallon_name.' Your Booking with ID '.$booking_id.' has completed';
			$title = 'Booking Completed';
            $device_token = User::find($booking_user_id)->device_token;
            
		}
		if($type=="new_booking"){
			$body = 'Hi '.$sallon_name.' You recieved New Booking with ID '.$booking_id;
			$title = 'New Booking Recieved';
            $device_token = User::find($_sallon_id)->device_token;
		}
	// echo '<br/>send_android_notification';
	// echo '<br/>device_token';
	// pr($device_token);
	// echo '<br/>$_sallon_id';
	// pr($_sallon_id);
	// 	echo '<br/> body - '.$body;
	// echo '<br/> title - '.$title;
	// echo '<br/>_booking_info';
	// pr($_booking_info);
	// die();
		define( 'API_ACCESS_KEY', 'AAAAOdfnfzc:APA91bEhA_OxbvW6nyG_Dfdo59_Xq3oOGVyJZ6et4hOqgIB4bkyt0V-KHgbRUK1Scmz6N7Q5YFbXsvUdA0BOnxeO5Nr15uzSFDsdm5waP1Qu-NwQ24D0rhb-fs6xtZh5PaR2148YtzJ_' );

		$singleID = $device_token;
		
		if(!empty($singleID) && $singleID!='(null)' ){

		// 'vibrate' available in GCM, but not in FCM
			$fcmMsg = array(
				'body' => $body,
				'title' => $title,
				'sound' => "default",
				'color' => "#203E78" 
			);

			$fcmFields = array(
				'to' => $singleID,
                'priority' => 'high',
                'data' => array('booking_id'=>'','title'=>$title,'body'=>$body),
				// 'notification' => $fcmMsg
            );
            if($type=="booking_accepted"){
                $fcmFields['data']['booking_id'] = $booking_id;
            }

            // echo '<pre>';print_r($fcmFields);exit;

			$headers = array(
				'Authorization: key=' . API_ACCESS_KEY,
				'Content-Type: application/json'
			);
			
			$ch = curl_init();
			curl_setopt( $ch,CURLOPT_URL, $this->url );
			curl_setopt( $ch,CURLOPT_POST, true );
			curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
			curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
			curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
			curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fcmFields ) );
			$result = curl_exec($ch );
			curl_close( $ch );
            return json_decode($result,true);
		}

	}


// Function to send Push Notification for IOS Phone
	function send_ios_notification($_booking_info,$type){
		$body = $title = $device_token = $sallon_name = '';
		
		$booking_user_id = $_booking_info->user_id;
        $booking_id = $_booking_info->id;
        $_sallon_id = Booking::find($booking_id) ? Booking::find($booking_id)->salon_id :'';

		if(empty($_sallon_id) && isset($_booking_info->salon_id)){
			$_sallon_id = $_booking_info->salon_id;
		}
	
		if(!empty($_sallon_id)){
            $sallon_name = Provider::find($_sallon_id) ? Provider::find($_sallon_id)->name : '';
            $sallon_name = San_Help::sanGetLang($sallon_name, $this->lng);
		}

		if($type=="booking_accepted" || $type=="booking_rejected"){

			$device_token = User::find($booking_user_id)->device_token;

			if($type=="booking_accepted"){
				$body = 'Your Booking with ID '.$booking_id.' has been accepted by '.$sallon_name;
				$title = 'Booking Accepted';
			}

			if($type=="booking_rejected"){
				$body = 'Your Booking with ID '.$booking_id.' has been rejected by '.$sallon_name;
                $title = 'Booking Rejected';
				$device_token = User::find($booking_user_id)->device_token;
			}

		}

		if($type=="booking_canceled"){
			$body = 'Hi '.$sallon_name.' Your Booking with ID '.$booking_id.' has been Canceled by Customer';
			$title = 'Booking Canceled';

			$device_token = User::find($_sallon_id)->device_token;

		}
		if($type=="new_booking"){
			$body = 'Hi '.$sallon_name.' You recieved New Booking with ID '.$booking_id;
			$title = 'New Booking Recieved';
            $device_token = User::find($_sallon_id)->device_token;

		}

		// define( 'API_ACCESS_KEY1', 'AAAAPsMwoLs:APA91bHmIgPG7_L-Ura9djKnRQK1jhLffWI1zF_5EYoRyQj5ymPu7WnNLErjnd0QBttq2wkT6_yhbXAJ_euPEEuQUwWsbDZJlWFVpnByAOGfv9VOEPm7fS4b6rbjibe4n8iCvacNSOsa2Ou0v1n6llMUF08F503KUw' );
		define( 'API_ACCESS_KEY1', 'AAAA1fwAGbc:APA91bF62M88mJaukXxTs5KSn03tRkYWuSKgHPGHn82dC4MTL9wSdEeFLdVuP5xOf-NFrGI0xIA0yF2GNxQyxxqA2cUmTqimTIfjT7uykGd0Rcg_kkHAH8jUrW6p6AM2upT-1NowoXeX' );
	// $device_token = 'd4KAOIE0lm4:APA91bFF_Dy8tSSdwzL4KpuyyqagtzFjocwfuesT9LO-4vQemNlcvaeO9OdipSqk8rrke5e3TsmPBkdyJg6etV12aurq1semYgybd4GU06TM6pYCaHmJOq8_MFUTxz6PKryYf-SV8FK0-qt_d0R0muUBj603Xis-fw';

		$singleID = $device_token;
		
		if(!empty($singleID) && $singleID!='(null)' ){

		// 'vibrate' available in GCM, but not in FCM
			$fcmMsg = array(
				'body' => $body,
				'title' => $title,
				'sound' => "default",
				'color' => "#203E78" 
			);

			$fcmFields = array(
				'to' => $singleID,
				'priority' => 'high',
				'notification' => $fcmMsg
			);

			$headers = array(
				'Authorization: key=' . API_ACCESS_KEY1,
				'Content-Type: application/json'
			);
			
			$ch = curl_init();
			curl_setopt( $ch,CURLOPT_URL, $this->url );
			curl_setopt( $ch,CURLOPT_POST, true );
			curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
			curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
			curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
			curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fcmFields ) );
			$result = curl_exec($ch );
			curl_close( $ch );
            return json_decode($result,true);
		}

	}


    public function sanAndroid(){
		$this->sanGetTokens();
		// print_r($this->san_token);exit;
		if ($this->san_token &&  !empty($this->san_token)) {
			$final = array();
			$msg['vibrate'] = 1;
			$msg['sound'] = 'default';
			$msg['title'] = $this->san_title;
			$msg['body'] = $this->san_body;
			$fields = array(
				'registration_ids' => array_values($this->san_token),
				// 'notification' =>$msg,
				'data' => $this->san_body,
				'time_to_live' => 1200,
				'priority'=>'high'
				);
			$final['headers'] = array(
				'Authorization' => 'key='.env('FCM_SERVER_KEY'),
				'Content-Type'=>'application/json',
				'project_id' => env('FCM_SENDER_ID')
				);
			$final['json'] = $fields;
			// print_r($final);exit;
			$result = $this->post($final);
			/* Delete Unregistered Tokens */
			$del_key = 0;
			$del_arr = array();
			foreach ($this->san_token as $key => $token) {
				if (isset($result->results[$del_key]->error)) {
					FCMUser::where('device_type',$this->type)->where('id', $key)->delete();
				}
				$del_key++;
			}
			return json_encode($result);
		}
		
	}


/* Custom Code For Ios */
public function sanIos(){
    $this->sanGetTokens();
    if ($this->san_token && !empty($this->san_token)) {
        $final = array();
        $msg['vibrate'] = 1;
        $msg['badge'] = 5;
        $msg['sound'] = 'default';
        $msg['title'] = $this->san_title;
        $msg['body'] = $this->san_body;
        $msg['type'] = $this->notify_type;
        $fields = array(
            'registration_ids' => array_values($this->san_token),
            'notification' =>$msg,
            'data' => $this->san_body,
            'time_to_live' => 1200,
            'content_available' => true,
            'mutable_content' => true,
            'priority'=>'high'
        );
        // $fields['aps'] = array(
        // 	'alert' => array(
        // 	    'title' => $this->san_title,
  //               'body' => $this->san_body,
  //               'type' => 'job_new'
        // 	 ),
        // 	'sound' => 'default',
        // 	'badge' => 5
        // );
        // $fields['registration_ids'] = array_values($this->san_token);
        // $fields['data'] = $this->san_body;
        $final['headers'] = array(
            'Authorization' => 'key='.env('FCM_SERVER_IOS_KEY'),
            'Content-Type'=>'application/json',
            'project_id' => env('FCM_SENDER_ID_IOS')
            );
        $final['json'] = $fields;
        $result = $this->post($final);
        // print_r($result);exit;
        /* Delete Unregistered Tokens */
        $del_key = 0;
        $del_arr = array();
        foreach ($this->san_token as $key => $token) {
            if (isset($result->results[$del_key]->error)) {
                FCMUser::where('device_type',$this->type)->where('id', $key)->delete();
            }
            $del_key++;
        }
        return json_encode($result);
    }
}
    
    public function sanGetTokens(){
		$tokens = array();
		if (isset($this->sent_user_id) && $this->sent_user_id !='' && !is_array($this->sent_user_id)) {
			// where('user_type','User')->
			$this->datas = FCMUser::where('user_id',$this->sent_user_id)->where('device_type',$this->type)->get();
		}elseif(is_array($this->sent_user_id) && !empty($this->sent_user_id)){
			// where('user_type','Guru')->
			$this->datas = FCMUser::where('device_type',$this->type)->whereIn('user_id', $this->sent_user_id)->get();
		}else{
			$this->datas = FCMUser::where('device_type',$this->type)->get();
		}
		foreach ($this->datas as $key => $data) {
			if (!in_array($data->fcm_token, $tokens)) {
				$tokens[$data->id] = $data->fcm_token;
				// array_push($tokens, $data->fcm_token);
			}
		}
		$this->san_token = $tokens;
    }
    
    private function post($data)
	{
		try {
			$responseGuzzle = $this->client->request('post', $this->url, $data);
		} catch (ClientException $e) {
			$responseGuzzle = $e->getResponse();
		}
		return json_decode($responseGuzzle->getBody());
	}

	function chkNotification(){
		define( 'API_ACCESS_KEY1', 'AAAA1fwAGbc:APA91bF62M88mJaukXxTs5KSn03tRkYWuSKgHPGHn82dC4MTL9wSdEeFLdVuP5xOf-NFrGI0xIA0yF2GNxQyxxqA2cUmTqimTIfjT7uykGd0Rcg_kkHAH8jUrW6p6AM2upT-1NowoXeX' );
		$device_token = 'cpLkm8ERwl0:APA91bG-8q8g1YwYFKnkW-pSOjqJJpNbmIYO1p6jEbHTG4rKeqB5gnrG-q0m1ZUd6pT1k4DouYR0_CWuJLCrKoxL-NznwuWX5SSYesHEv8XgC1MdIugL1Qm08q9KKhzUhAOk1hd5b_Sj';

		$singleID = $device_token;
		
		if(!empty($singleID) && $singleID!='(null)' ){

		// 'vibrate' available in GCM, but not in FCM
			$fcmMsg = array(
				'body' => 'Sample Body',
				'title' => 'Sample Title',
				'sound' => "default",
				'color' => "#203E78" 
			);

			$fcmFields = array(
				'to' => $singleID,
				'priority' => 'high',
				'data' => array('booking_id'=>'80','title'=>'Sample Body','body'=>'Sample Title'),
				'notification' => $fcmMsg
			);

			$headers = array(
				'Authorization: key=' . API_ACCESS_KEY1,
				'Content-Type: application/json'
			);
			

			// print_r(json_encode($fcmFields));exit;
			
			$ch = curl_init();
			curl_setopt( $ch,CURLOPT_URL, $this->url );
			curl_setopt( $ch,CURLOPT_POST, true );
			curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
			curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
			curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
			curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fcmFields ) );
			$result = curl_exec($ch );
			curl_close( $ch );
            return json_decode($result,true);
		}
	}

}