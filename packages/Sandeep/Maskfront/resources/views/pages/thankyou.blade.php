@php($page = 'thankyou')
@extends('maskFront::layouts.app')
@section('main-content')
@php($user = Auth::user())
@if(Auth::check() && isset($data['book_date']))
@php($data_reward = array(
'type' => 'new_booking',
'_booking_id' => $data['bookid'],
))
@php(San_Help::updateRewardPoints($data_reward))
@if(isset($data['reward_points']))
@php($data_reward = array(
'type' => 'redeem_points',
'redeemed_points' => $data['reward_points'],
'_booking_id' => $data['bookid'],
))
@php(San_Help::updateRewardPoints($data_reward))
@endif
<?php //echo "<pre>";print_r($data);exit; ?>
<div class="container">
    <table class="body-wrap" style="max-width:600px; margin:0 auto;margin: 7em auto 0; font-family:'Helvetica', Arial, sans-serif; position:relative; border-collapse:collapse;"  border="0" cellpadding="0" cellspacing="0">
        <tr>
            <td class="container" style="margin:0 padding:0;">

                <!-- Message start -->
                <table border="0" cellpadding="0" cellspacing="0">
                    <tr>
                        <td align="center" class="masthead" style="padding:50px 0;color:#000; background:#000 url({{ San_Help::san_Asset('images/top-banner.jpg') }}); background-size: cover; background-position:center center; position:relative;" border="0">

                            <h2 style="margin: 0 auto !important;max-width: 90%;font-weight:400">@if(isset($data['booking_type']) && $data['booking_type'] == 'services')Booking @else Order @endif Recieved.</h2>
							<div class="col-sm-12 pad-0">
							 @if(isset($data['booking_type']) && $data['booking_type'] == 'services')
							<div class="rows sln-thankyou--okbox  sln-bkg--attention">
								<div class="col-md-8 col-md-offset-2 wrap-outer booking_status">
									<h1 class="sln-icon-wrapper">
										<i class="sln-icon sln-icon--time"></i>
										{!!San_Help::sanLang('Your booking is pending')!!}
									</h1>
								</div>
								<div class="col-md-8 col-md-offset-2 wrap-content-btm text-center booking_status_no">
									<div class="col-sm-12">
										<div class="well wr-well">
											<h2 class="salon-step-title">{!!San_Help::sanLang('Booking number')!!}</h2>
											<h3>{{$data['bookid']}}</h3>
										</div>
									</div>
								</div>
							</div>
							@endif
                            </div>
                            <img src="{{ San_Help::san_Asset('images/logo2.jpg') }}" class="logo-img" alt="" style="position: absolute;left: 0;top: 0;">

                        </td>
                    </tr>
                    <tr>
                        <td class="content" style="background: url({{ San_Help::san_Asset('images/main-bg.jpg') }}); background-size: cover; background-position:center center;  padding: 30px 35px;" border="0">
                          <div class="inner-box" style="border: solid 1px #ccc;padding: 5%;background:url({{ San_Help::san_Asset('images/sml-icon.png') }}); background-repeat:no-repeat; background-position:right bottom;">
                             <h4 style="color: #eee;">Hi,</h4>

                             <p style="color: #eee; font-style:italic;">Your @if(isset($data['booking_type']) && $data['booking_type'] == 'services')Booking @else Order @endif Recieved Succesfully</p>

                             <div class="col-md-12">
                                <p class="sln-text--dark">
                                    Dear <strong>{{$user->name}}</strong>
                                    <br>
                                Here are the details of your @if(isset($data['booking_type']) && $data['booking_type'] == 'services')Booking @else Order @endif:</p>
                            </div>

                            <div class="rows">
                                <div class="col-md-12 pad-0">
                                    @if(isset($data['booking_type']) && $data['booking_type'] == 'services')
                                    <div class="sln-summary-row">
                                        <div class="col-sm-6 col-md-6 sln-data-descs">
                                            <span class="text text-min label" style="color: #fff;">
                                                Date and time booked
                                            </span>
                                        </div>
                                        <div class="col-sm-6 col-md-6 sln-data-vals">
                                            {{$data['book_date']}} / {{$data['book_time']}}
                                        </div>
                                        <div class="col-sm-12 col-md-12"><hr></div>
                                    </div>
                                    @endif
                                    <div class="sln-summary-row">
                                        <div class="col-sm-6 col-md-6 sln-data-descs">
                                            <span class="text text-min label" style="color: #fff;">
                                                @if(isset($data['booking_type']) && $data['booking_type'] == 'products' || (isset($data['booking_type']) && $data['booking_type'] == 'cart_book'))
                                                Product booked
                                                @else
                                                Services booked
                                                @endif
                                            </span>
                                        </div>
                                        <div class="col-sm-6 col-md-6 sln-data-vals">
                                            <ul class="sln-list--dashed">
                                                @php($total = 0)
                                                @if(isset($data['booking_type']) && $data['booking_type'] == 'services')
                                                @php($sids = unserialize($data['sids']))
                                                @if(!empty($sids))
                                                @php($ass = \TCG\Voyager\Models\Service::whereIn('id',$sids)->get(array('name','price')))
                                                @if(isset($ass) && !empty($ass))
                                                @foreach($ass as $assss)
                                                @php($total = (float) $total + (float) $assss->price)
                                                <li> <span class="service-label">{{$assss->name}}</span>
                                                    <small>({{San_Help::money($assss->price)}} {!!$currency!!})</small>
                                                </li>
                                                @endforeach
                                                @else
                                                <li> <span class="service-label">All</span>
                                                    <small>()</small>
                                                </li>
                                                @endif
                                                @endif
                                                @elseif(isset($data['product_id']))
                                                        @php($product = \TCG\Voyager\Models\Product::find($data['product_id']))
                                                        @php($total = $data['total_amnt'])
                                                        <li> <span class="service-label">{{$product->name}}</span>
                                                            <small>({{$data['total_amnt']}})</small>
                                                        </li>
                                                @elseif(isset($data['booking_type']) && $data['booking_type'] == 'cart_book')
                                                @php($cartdata = \TCG\Voyager\Models\Cart::with('product')->where('user_id', Auth::user()->id)->get())
                                                  @foreach($cartdata as $cart)
                                                    <li> <span class="service-label">{{$cart->product->name}}</span>
                                                         <small>({{San_Help::money($cart->total)}} {!!$currency!!})</small>
                                                    </li>
                                                  @endforeach
                                                @endif
                                            </ul>
                                        </div>
                                        <div class="col-sm-6 col-md-6 sln-data-descs">
                                            <span class="text text-min label" style="color: #fff;">
                                               Total Amount
                                            </span>
                                        </div>
                                        <div class="col-sm-6 col-md-6 sln-data-vals">
                                            <ul class="sln-list--dashed">
                                                <li>
                                                    <span class="service-label">{{San_Help::money($data['total_amnt'])}} {!!$currency!!}</span>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="col-sm-12 col-md-12"><hr></div>
                                    </div>
                                    <?php
                                    if(!empty($_rewardpoints_) && isset($_rewardpoints_['0']->rewards)){
                                        ?>
                                        <div class="sln-summary-row" style="margin-bottom: 25px;">
                                            <div class="col-sm-6 col-md-6 sln-data-descs">
                                                <span class="text text-min label" style="color: #fff;">
                                                    Jewelleries Earned on this booking
                                                </span>
                                            </div>
                                            <div class="col-sm-6 col-md-6 sln-data-vals">
                                                <span id="sln_discount_value"><?php //echo $_rewardpoints_['0']->rewards;?></span>
                                            </div>
                                            <div class="col-sm-12 col-md-12"><hr></div>
                                        </div>
                                    <?php }
                                    ?>
                                </div>
                            </div>
                            <p style="margin:35px 0;color: #eee; ">
                                Thanks<br>Mask Team
                            </p>
                        </div>

                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</div>
@endif
@endsection
