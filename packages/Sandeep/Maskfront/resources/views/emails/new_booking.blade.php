@if($type == 'new_order')
  <?php 
  $order_data = \TCG\Voyager\Models\Order::find($booking_id);
  if (!is_null($order_data->product_ids)) {
      $products = \TCG\Voyager\Models\Product::where('id',$order_data->product_ids)->select('name','image')->get();
  }else{
      $product_ids = array();
      $pids = unserialize($order_data->provider_id);
      foreach ($pids as $providerid => $productids) {
          $product_ids = $productids;
      }
      $products = \TCG\Voyager\Models\Product::whereIn('id',$product_ids)->select('name','image')->get();
      $colors = explode(',', $order_data->color);
  }
  if (isset($order_data->order_user_id)) {
    $user = \App\User::find($order_data->order_user_id);
    if ($user) {
      $name = $user->name.' '.$user->lname;
      $address = $user->address;
      $email = $user->email;
      $phone = $user->phone;
      $gender = $user->gender;
    }
  }
  ?>
@else
  @php($booking_data = \TCG\Voyager\Models\Booking::find($booking_id))
  @php($services = \TCG\Voyager\Models\Service::whereIn('id',explode(',',$booking_data->service_ids))->get())
  @php($attendents = \TCG\Voyager\Models\Assistant::whereIn('id',explode(',',$booking_data->assistent_ids))->get())
  <?php 
  $name = $booking_data->first_name.' '.$booking_data->last_name;
  $address = $booking_data->address;
  $email = $booking_data->email;
  $phone = $booking_data->phone;
  $gender = $booking_data->gender;
  ?>
@endif
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
  <head>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
      <title>Untitled Document</title>
      <style type="text/css">
          body {
              margin-left: 0px;
              margin-top: 0px;
              margin-right: 0px;
              margin-bottom: 0px;
          }
      </style>
  </head>
  <body>
    <table width="600" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td height="105" align="center" valign="middle" bgcolor="#f2f2f2" style="border-bottom:2px solid #fff;">
          <table border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td valign="top">
                  <a href="#"><img src="{{ San_Help::san_Asset('images/summary.png') }}" alt="img1" border="0"></a>
              </td>
              <td align="left" valign="top"><table width="100%" border="0" align="left" cellpadding="0" cellspacing="0">
                <tr>
                  <td height="20" align="left" valign="bottom" style="font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#cccccc; font-weight:bold;">@if(isset($order_data)) Order @else Booking @endif ID<b style="color:#666666;">{{$booking_id}}</b></td>
                </tr>
                <tr>
                  <td height="25" align="left" valign="bottom" style="font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#cccccc; font-weight:bold;">Status<b style="color:#666666;">@if(isset($order_data)) {{$order_data->order_status}} @else {{$booking_data->status}}@endif</b></td>
                </tr>
              </table></td>
            </tr>
          </table>
        </td>
      </tr>

      <tr>
        <td align="center" valign="top" bgcolor="#f2f2f2">
            <table width="502" border="0" align="center" cellpadding="0" cellspacing="0">
                <tr>
                    <td height="25">&nbsp;</td>
                </tr>
                <tr>
                    <td align="left" valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:16px; color:#666666; font-weight:normal;">
                      New @if($type == 'new_order') Order @else Booking @endif is Recieved, Please Check your Account to Approve or Refuse
                    </td>
                </tr>
                <tr>
                    <td align="left" valign="top">&nbsp;</td>
                </tr>
                @if($type == 'new_order')
                  <tr>
                    <td align="left" valign="top" bgcolor="#ffffff">
                      <table width="502" border="0" align="left" cellpadding="0" cellspacing="0">
                        <tbody>
                          <tr>
                            <td width="157" align="center" valign="top" style="border-right:2px solid #f2f2f2;">
                              <table width="112" border="0" align="center" cellpadding="0" cellspacing="0">
                                <tbody>
                                  <tr>
                                    <td align="left" valign="top">&nbsp;</td>
                                  </tr>
                                  <tr>
                                    <td height="25" align="left" valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#cc3333; font-weight:normal;">Date & Time</td>
                                  </tr>
                                  <tr>
                                    <td height="36" align="left" valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:18px; color:#666666; font-weight:bold;">{{\Carbon\ Carbon::parse($order_data->created_at)->format('Y-m-d H:i')}}</td>
                                  </tr>
                                </tbody>
                              </table>
                            </td>
                            <td>
                              <table width="200" border="0" align="center" cellpadding="0" cellspacing="0">
                                  <thead class="full-width">
                                   <tr>
                                      <th height="25" align="left" valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#cc3333; font-weight:normal;"><span class="h3-body">Product</span></th>
                                      <th height="25" align="left" valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#cc3333; font-weight:normal;"><span class="h3-body" style="font-size: 1em;">Color</span></th>
                                   </tr>
                                </thead>
                                <tbody id="append_cart_data">
                                  @foreach($products as $key => $product)
                                  <tr>
                                    <td height="36" align="left" valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#666666; font-weight:bold;">{!!San_Help::sanGetLang($product->name,app('request')->segment(1))!!}</td>
                                    <td height="36" align="left" valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#666666; font-weight:bold;">{{$colors[$key]}}</td>
                                  </tr>
                                  @endforeach
                                </tbody>
                              </table>
                            </td>
                            <td width="147" align="center" valign="top">
                                <table width="112" border="0" align="center" cellpadding="0" cellspacing="0">
                                      <tbody>
                                      <tr>
                                          <td align="left" valign="top">&nbsp;</td>
                                      </tr>
                                      <tr>
                                          <td height="25" align="left" valign="top"
                                              style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#cc3333; font-weight:normal;">Total amount</td>
                                      </tr>
                                      <tr>
                                          <td height="36" align="left" valign="top"
                                              style="font-family:Arial, Helvetica, sans-serif; font-size:18px; color:#666666; font-weight:bold;">SAR {{$order_data->price}}</td>
                                      </tr>
                                      <tr>
                                          <td align="left" valign="top">&nbsp;</td>
                                      </tr>
                                      </tbody>
                                  </table>
                              </td>
                          </tr>
                        </tbody>
                      </table>
                    </td>
                  </tr>
                @else
                  <!-- Sallon Services -->
                  <tr>
                    <td align="left" valign="top" bgcolor="#ffffff">
                  
                      <table width="502" border="0" align="left" cellpadding="0" cellspacing="0">
                        <tbody>
                          <tr>
                            <td width="157" align="center" valign="top" style="border-right:2px solid #f2f2f2;">
                              <table width="112" border="0" align="center" cellpadding="0" cellspacing="0">
                                <tbody>
                                  <tr>
                                    <td align="left" valign="top">&nbsp;</td>
                                  </tr>
                                  <tr>
                                    <td height="25" align="left" valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#cc3333; font-weight:normal;">Date</td>
                                  </tr>
                                  <tr>
                                    <td height="36" align="left" valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:18px; color:#666666; font-weight:bold;">{{$booking_data->book_date}}</td>
                                  </tr>
                                  <tr>
                                    <td height="25" align="left" valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#cc3333; font-weight:normal;">Time</td>
                                  </tr>
                                  <tr>
                                    <td height="25" align="left" valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:18px; color:#666666; font-weight:bold;">{{$booking_data->time}}</td>
                                  </tr>

                                </tbody>
                              </table>
                            </td>
                              <td width="194" align="center" valign="top" style="border-right:2px solid #f2f2f2;">
                                <table width="160" border="0" align="center" cellpadding="0" cellspacing="0">
                                  <tbody>
                                    <tr>
                                      <td align="left" valign="top">&nbsp;</td>
                                    </tr>
                                    <tr>
                                      <td height="25" align="left" valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#cc3333; font-weight:normal;">Service</td>
                                      <td height="25" align="left" valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#cc3333; font-weight:normal;">Attendant</td>
                                    </tr>
                                       @if(!empty($services))
                                          @foreach($services as $key => $Ser)
                                          <tr>
                                            <td height="20" align="left" valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#666666; font-weight:bold;">{!!San_Help::sanGetLang($Ser->name,app('request')->segment(1))!!}</td>
                                            <td height="20" align="left" valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#666666; font-weight:bold;">
                                                @if(isset($attendents[$key]->name)){{$attendents[$key]->name}} @else All @endif
                                            </td>
                                          </tr>
                                        @endforeach
                                      @endif
                                        <tr>
                                        <td align="left" valign="top">&nbsp;</td>
                                    </tr>
                                  </tbody>
                                </table>
                              </td>
                              <td width="147" align="center" valign="top">
                                <table width="112" border="0" align="center" cellpadding="0" cellspacing="0">
                                      <tbody>
                                      <tr>
                                          <td align="left" valign="top">&nbsp;</td>
                                      </tr>
                                      <tr>
                                          <td height="25" align="left" valign="top"
                                              style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#cc3333; font-weight:normal;">Total amount</td>
                                      </tr>
                                      <tr>
                                          <td height="36" align="left" valign="top"
                                              style="font-family:Arial, Helvetica, sans-serif; font-size:18px; color:#666666; font-weight:bold;">SAR{{$booking_data->price}}</td>
                                      </tr>
                                      <tr>
                                          <td align="left" valign="top">&nbsp;</td>
                                      </tr>
                                      </tbody>
                                  </table>
                              </td>
                          </tr>
                        </tbody>
                      </table>
                        

                    </td>
                  </tr>
                  <!-- Sallon Services End -->
                @endif
                <tr>
                    <td height="25" align="left" valign="top">&nbsp;</td>
                </tr>

                <tr>
                  <td align="center" valign="top">
                    <table width="460" border="0" align="center" cellpadding="0" cellspacing="0">
                      <tbody>
                        <tr>
                          <td width="242" align="left" valign="middle" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#cc3333; font-weight:normal;">Customer address</td>
                          <td align="left" valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:16px; color:#666666; font-weight:normal;">
                          {{$name}}
                          <br/>
                          {{$address}}
                        </tr>
                        <tr>
                          <td width="242" align="left" valign="middle" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#cc3333; font-weight:normal;">Customer Email</td>
                          <td align="left" valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:16px; color:#666666; font-weight:normal;">
                          {{$email}}
                          </td>
                        </tr>
                        <tr>
                          <td width="242" align="left" valign="middle" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#cc3333; font-weight:normal;">Customer Phone</td>
                          <td align="left" valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:16px; color:#666666; font-weight:normal;">
                          {{$phone}}
                          </td>
                        </tr>
                        <tr>
                          <td width="242" align="left" valign="middle" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#cc3333; font-weight:normal;">Customer Gender</td>
                          <td align="left" valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:16px; color:#666666; font-weight:normal;">
                          {{$gender}}
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </td>
                </tr>
                <tr>
                    <td height="25" align="left" valign="top">&nbsp;</td>
                </tr>
            </table>
        </td>
      </tr>
    </table>
  </body>
</html>