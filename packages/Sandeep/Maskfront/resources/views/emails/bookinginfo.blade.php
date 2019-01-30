    <?php
    $get_userdata = \App\User::find($user_id);
    $_email = $get_userdata->email;
    $username = $get_userdata->name;
    $key = $msg_data['key'];
    $subject = \Sandeep\Maskfront\San_Help::email_subjects($type);
    $msg_center = 'We have succesfully received your application for Service provider. Our sales team will shortly review your profile and contact you back within 24 hours.';
    $Inner_title = 'Your Application Recieved.';
    if (isset($msg_data['sallon_id']) ) {
      $slndata = \TCG\Voyager\Models\Provider::find($msg_data['sallon_id']);
      $sallon_name = $slndata->name;
    }
    if (isset($msg_data['_booking_id'])) {
      $bookingdata = \TCG\Voyager\Models\Provider::find($msg_data['_booking_id']);
      $_booking_id = $msg_data['_booking_id'];
    }

    if($type=='forgot_password'){
      $url = url('en/?uid='.$user_id.'&key='.$key);
      $msg_center = '<a target="_blank" href="'.$url.'">Reset Password</a>';
      $Inner_title = "Reset Your Password.";
    }

    if($type=='new_booking'){
      $msg_center = '<h1>Your Booking Received By Mask Team</h1>';
      $Inner_title = "New Booking";
    }
    if($type=='new_order'){
      $msg_center = '<h1>Your Order Received By Mask Team</h1>';
      $Inner_title = "New Order";
    }
   if($type=='booking_accepted'){
      $msg_center = 'Your Booking has been accepted by '.$sallon_name.'<br/>';
      $msg_center .= View('maskFront::emails.msg_center', ['booking_id' => $msg_data['_booking_id']])->render();
      $Inner_title = "Booking Accepted";
    }

     if($type=='booking_rejected'){
      $msg_center = 'Your Booking has been Refused by '.$sallon_name.'<br/>';
      $msg_center .= View('maskFront::emails.msg_center', ['booking_id' => $msg_data['_booking_id']])->render();
      $Inner_title = "Booking Refused";
    }

    if($type=='booking_canceled'){
      $msg_center = 'Booking has been Canceled by '.$username.'<br/>';
      $msg_center .= View('maskFront::emails.msg_center', ['booking_id' => $msg_data['_booking_id']])->render();
      $Inner_title = "Booking Canceled";
    }
    $to  = $_email;
    if($type=='booking_canceled'){
      $to = $slndata->email;
    }
    if (isset($bookingdata->service_ids) && $bookingdata->service_ids!='') {
      $services = \TCG\Voyager\Models\Service::whereIn('id',explode(',', $bookingdata->service_ids))->get();
    }
    
    ?>
<html>
      <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width"/>
        <style type="text/css">
        .body-wrap{max-width:600px; margin:0 auto; font-family:"Helvetica", Arial, sans-serif; position:relative; border-collapse:collapse;}
        </style>
      </head>
      <body style="width: 100% !important;height: 100%;background: #f8f8f8;margin: 0;padding: 0; " >
        <table class="body-wrap" style=""  border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td class="container" style="margin:0 padding:0;">
                <!-- Message start -->
                <table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
                        <td align="center" class="masthead" style="color:#000; background:#000 url({{ San_Help::san_Asset('images/top-banner.jpg') }}); background-size: cover; background-position:center center; position:relative;" border="0">
                <div style="width:100%;">
                  <span style="text-align: left;  z-index: 1;  text-align: left;float: left;  width:100%;"><img src="{{ San_Help::san_Asset('images/logo.jpg') }}" class="logo-img" alt=""></span>
                  <div style="width: 100%; padding: 80px 0px; position:relative;">
                    <h2 style="margin: 0 auto !important;max-width: 90%;font-weight:400;letter-spacing:2px; text-transform:uppercase; color:#000;">{{$Inner_title}}</h2>
                  </div>
                </div>
              </td>
                    </tr>
                    <tr>
                        <td class="content" style="background: url({{ San_Help::san_Asset('images/main-bg.jpg') }}); background-size: cover; background-position:center center;  padding: 30px 35px;" border="0">
                <div class="inner-box" style="border: solid 1px #ccc;padding: 5%;background:url({{ San_Help::san_Asset('images/sml-icon.png') }}); background-repeat:no-repeat; background-position:right bottom;">
                  <h4 style="color: #eee;">Hi, {{$username}}</h4>

                  <p style="color: #eee; font-style:italic;">{!!$msg_center!!}
</p>
                  <p style="margin:35px 0;color: #eee; ">
                    Thanks<br>Mask Team
                  </p>
                  </div>
                        </td>
                    </tr>
                </table>

            </td>
        </tr>
        <tr style="margin:0; padding:0;">
          <td class="container" style="width:100%;background:#000 url({{ San_Help::san_Asset('images/footer-bg.jpg') }}); background-size: cover; background-position:center center; padding:55px 35px 30px;" border="0" >
            <!-- Message start -->
            <table style="width:100%;"  border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td class="content footer" style="background:#transparent; font-size:13px; margin:0;" border="0">
                  <h5 style="color:#7e7e7e; margin:12px 0;">ABOUT MASK</h5>
                  <p style="color:#7e7e7e; margin:0; font-size:12px;">Mask is the online destination for beauty & wellness professionals and clients. Professionals can showcase their work, connect with new and existing clients, & build their business. Clients can discover new services and providers, book appointments online, and get inspired.</p>
                </td>
              </tr>
            </table>
          </td>
        </tr>
      <tr style="margin:0; padding:0;">
        <td class="container" style="background:#0e0e0e; border:none;">
          <div class="foot-panel" style="width: 100%;border-top: solid 1px #ccc;padding: 15px 0;float: left;">
            <p style="float: left;padding: 0;margin: 0 0 0 6%;color:#bc883f;font-size: 13px;">@ 2018 East Union Company Ltd. All rights reserved.</p>
            <div style="float:right;margin-right:6%;">
              <div class="social-list" style="margin:0; padding:0;">
              <a style="margin-right:5px;" href="https://twitter.com/MASK_APP"><img src="{{ San_Help::san_Asset('images/t1.png') }}" alt=""></a>
            <a style="margin-right:5px;" href="https://www.youtube.com/channel/UCAlGTOmoO9ZigEQs_yTtJWQ"><img src="{{ San_Help::san_Asset('images/utube.png') }}" alt=""></a>
            <a style="margin-right:5px;" href="https://www.instagram.com/mask_app_/"><img src="{{ San_Help::san_Asset('images/insta1.png') }}" alt=""></a>
              </div>
            </div>
          </div>
        </td>
      </tr>
    </table>
    </body>
    </html>