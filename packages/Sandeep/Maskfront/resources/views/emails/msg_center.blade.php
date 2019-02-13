@php($bookingdata = \TCG\Voyager\Models\Booking::find($booking_id))
@php($services = \TCG\Voyager\Models\Service::whereIn('id',explode(',',$bookingdata->service_ids))->get())
@php($attendents = \TCG\Voyager\Models\Assistant::whereIn('id',explode(',',$bookingdata->assistent_ids))->get())
<table width="502" border="0" align="left" cellpadding="0" cellspacing="0" style=" margin-bottom: 20px;">
    <tbody>
        <tr>
          <td height="36" align="left" valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:18px; color:#666666; font-weight:bold;">Booking ID : {{$booking_id}}</td>
        </tr>
        <tr>
          <td align="left" valign="top">&nbsp;</td>
        </tr>
      <tr>
        <td width="157" align="center" valign="top" style="border-right:2px solid #f2f2f2;">
          <table width="112" border="0" align="center" cellpadding="0" cellspacing="0">
            <tbody>
              <tr>
                <td align="left" valign="top">&nbsp;</td>
              </tr>
              <tr>
                <td height="25" align="left" valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#fff; font-weight:normal;">Date</td>
              </tr>
              <tr>
                <td height="36" align="left" valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:18px; color:#666666; font-weight:bold;">@if(isset($bookingdata->book_date)){{$bookingdata->book_date}}@endif</td>
              </tr>
              <tr>
                <td height="25" align="left" valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#fff; font-weight:normal;">Time</td>
              </tr>
              <tr>
                <td height="25" align="left" valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:18px; color:#666666; font-weight:bold;">@if(isset($bookingdata->time)){{$bookingdata->time}}@endif</td>
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
                  <td height="25" align="left" valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#fff; font-weight:normal;">Service</td>
                  <td height="25" align="left" valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#fff; font-weight:normal;">Attendant</td>
                </tr>
                  @if(!empty($services))
                    @foreach($services as $key => $Ser)
                    <tr>
                      <td height="20" align="left" valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#666666; font-weight:bold;">{{$Ser->name}}</td>
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
                          style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#fff; font-weight:normal;">Total amount</td>
                  </tr>
                  <tr>
                      <td height="36" align="left" valign="top"
                          style="font-family:Arial, Helvetica, sans-serif; font-size:18px; color:#666666; font-weight:bold;">SAR{{$bookingdata->price}}</td>
                  </tr>
                  <tr>
                      <td align="left" valign="top">&nbsp;</td>
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
  <br/><br/>