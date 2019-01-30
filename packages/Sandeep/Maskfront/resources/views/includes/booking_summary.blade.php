<div id="sln-salon" class="sln-bootstrap container-fluid sln-salon--l sln-step-summary">
												@if(Auth::check() && Auth::user()->role_id == 3)
												@php($user = Auth::user())

											    <form method="post" action="@if(isset($provider->id)){{url($locale.'/booking/'.$provider->id)}} @else {{url($locale.'/probooking/'.$product->id)}} @endif" role="form" id="salon-step-summary">
											    	{{ csrf_field() }}
											    	<input type="hidden" name="tab" value="payment">
											    	<input type="hidden" name="total_amount" id="san_ttl_amnt" value="@if(isset($total_amount)){{ number_format($total_amount, 2, '.', '') }} @else 0.00 @endif">
											    	<h2 class="salon-step-title">@if(isset($sum_type)){{$sum_type}} @else Booking @endif summary</h2>
												    <div class="row">
											        <div class="col-md-12">
											            <p class="sln-text--dark">
											                Dear <strong>{{$user->name}}</strong>
											            <br>Here are the details of your @if(isset($product->id))order @else booking @endif :</p>
											        </div>
											    </div>
											    <div class="sln-summary">
											    <div class="col-md-12 pad-0">
											        <div class="rows">
											            <div class="col-md-12 pad-0">
											            	@if(isset($provider->id))
											                <div class="sln-summary-row">
											                    <div class="col-sm-6 col-md-6 sln-data-descs">
											                        	<span class="label">Date and time booked</span>
												                    </div>
											                    <div class="col-sm-6 col-md-6 sln-data-vals">
											                        @if(session('book_date')){{session('book_date')}}@endif / @if(session('book_time')){{session('book_time')}}@endif</div>

											                </div>

											                <div class="sln-summary-row">
											                        <div class="col-sm-6 col-md-6 sln-data-descs">
											                            	<span class="label">Assistants</span>
												                        </div>
											                        <div class="col-sm-6 col-md-6 sln-data-vals">@if(isset($ass_names) && !empty($ass_names)){{implode(',',$ass_names)}} @elseif(isset(unserialize(session('aids'))[0])) {{unserialize(session('aids'))[0]}} @endif</div>

											                </div>
											                <div class="sln-summary-row">
											                    <div class="col-sm-6 col-md-6 sln-data-descs">
											                        	<span class="label">Services booked</span>
												                    </div>
											                    <div class="col-sm-6 col-md-6 sln-data-vals">
											                        <ul class="sln-list--dashed">
											                        	@if(isset($ser_names))
											                        	@foreach($ser_names as $ser_name)
											                            <li> <span class="service-label">{{$ser_name->name}}</span><small> ({{San_Help::money($ser_name->price)}} {!!$currency!!})</small></li>
											                            @endforeach
											                            @endif
											                            <!-- <input type="hidden" id="san_services" value="[6,112]"> -->
											                        </ul>
											                    </div>

											                </div>
											                <div class="sln-summary-row">
											            <div class="col-sm-6 col-md-6 pad-0 sln-data-descs">
											                <div class="editable">
											                    <span class="text text-min label">
											                        Redeem Jewelries
											                    </span>
											                    <div class="input input-min">
											                        <input class="sln-edit-text" id="redeem_jewelries" value="">
											                    </div>
											                    <i class="fa fa-gear fa-fw"></i>
											                </div>
											            </div>
											            <div class="col-sm-6 col-md-6 pad-0 sln-data-vals">
											                <span id="sln_redeem_jewelries">{{San_Help::money($user->rewardpoint_balance)}} {!!$currency!!}</span>
											            </div>

											        </div>
											        @endif
											        @if(isset($product->id))
															@php($total_amount = $product->price * (int) session('product_qty'))
															<div class="sln-summary-row">
											                        <div class="col-sm-6 col-md-6 sln-data-descs">
											                            	<span class="label">Product Booked</span>
												                        </div>
											                        <div class="col-sm-6 col-md-6 sln-data-vals">@if(isset($product->name)){!!$product->name!!}@endif</div>

											                    </div>
											                    <div class="sln-summary-row">
											                        <div class="col-sm-6 col-md-6 sln-data-descs">
											                            	<span class="label">Price</span>
												                        </div>
											                        <div class="col-sm-6 col-md-6 sln-data-vals">@if(isset($product->price)){{San_Help::money($product->price)}} * {{session('product_qty')}} = {{San_Help::money($product->price * (int) session('product_qty'))}}@endif {!!$currency!!}</div>

											                    </div>
											                    <div class="sln-summary-row">
											                        <div class="col-sm-6 col-md-6 sln-data-descs">
											                            	<span class="label">Color</span>
												                        </div>
											                        <div class="col-sm-6 col-md-6 sln-data-vals">{!!session('product_color')!!}</div>

											                    </div>
											                    <div class="sln-summary-row">
											                        <div class="col-sm-6 col-md-6 sln-data-descs">
											                            	<span class="label">Description</span>
												                        </div>
											                        <div class="col-sm-6 col-md-6 sln-data-vals">@if(isset($product->description)){!!$product->description!!}@endif</div>

											                    </div>
											        @endif

											        <div class="sln-summary-row">
												<div class="col-sm-6 col-md-6 sln-data-descs">
														<span class="label">Discount</span>
													</div>
												<div class="col-sm-6 col-md-6 sln-data-vals">
													<span id="sln_discount_value">{{San_Help::money(0)}}</span>

													<span class="discount_action">
																<button data-salon-toggle="discount" title="Remove Discount" id="sln_discount_btn2" type="button" onclick="removeDiscountCode();" class="hidden">
															<i class="fa fa-times"></i>
														</button>
													</span>
												</div>
											</div>
										</div>
											        </div>
															<div class="sln-total">
 															 <div class="col-md-12 pad-0">
 																											 <h3 class="col-xs-6 sln-total-labels">Total amount</h3>
 																			 <h3 class="col-xs-6 sln-total-prices">@if(isset($total_amount)){{ San_Help::money($total_amount) }} @else {{San_Help::money(0)}} @endif</h3>
 																							 </div>
 													 </div>
											       <?php //echo '<pre>';print_r(number_format($total_amount, 2));exit; ?>
											        <div class="sln-summary-row">

											            <div class="col-sm-12 sln-data-redeem_jewelries">
														<span class="redeempoint_span"><input type="checkbox" name="redeem_points" value="" id="redeempoint_checkbox" data_user_id="{{Auth::user()->id}}" data_diamonds="{{$user->rewardpoint_balance}}" data-total="@if(isset($total_amount)){{$total_amount}}@endif"></span>
											            <label>Please click checkbox to redeem jewelries. You have {{San_Help::money($user->rewardpoint_balance)}} jewelries </label>
											            <input type="hidden" name="reward_points" id="reward_points" value="">
											            </div>
											        </div>
													<div class="rows prod-btn">
														<article class="pro-summary">
															<div class="col-xs-12 col-sm-12 col-md-12 sln-input sln-input--simple">
																<label class="">Do you have any discount code?</label>
															</div>
															<div class="col-xs-12 col-sm-6 col-md-6 sln-input sln-input--simple">
																<input type="text" name="sln[discount]" id="dicount_code" value="" placeholder="key in your coupon code" class="sln-input sln-input--text">
															</div>
															<div class="col-xs-12 col-sm-6 col-md-6">
																<div class="sln-btn sln-btn--emphasis sln-btn--medium sln-btn--fullwidth">
																	<button class="btn btn-glow" data-salon-toggle="discount" id="sln_discount_btn" type="button" onclick="applyDiscountCode();">Apply</button>
																</div>
															</div>
															<div class="col-xs-12 col-sm-12 col-md-12">
																<div class="img_loader2"><i class="fa fa-circle-o-notch fa-spin"></i> Applying... </div>

																<div id="sln_discount_status"></div>
															</div>
														</article>
													</div>

												@if(isset($provider->id))
												<div class="rows">
											            <div class="col-md-12 sln-input sln-input--simple">
											                	<label class="">Do you have any message for us?</label>
												                        <textarea name="sln[note]" id="sln_note" placeholder="Leave a message" class="sln-input sln-input--textarea"></textarea>
											                </div>
											        </div>
											        @endif
											        @if(isset($provider->id))
											        <div class="rows">
											            <div class="col-md-12">
											                <p><strong>Terms &amp; conditions</strong></p>
											                <p>In case of delay we'll keep your "seat" for 15 minutes, after that you'll loose your priority.</p>
											            </div>
											        </div>
											         @endif
											    </div>
											    <div class="col-md-12 sln-input sln-input--action">
													<div class="sln-box--formactions form-actions row">
														<div class="col-sm-6">
															<a class="btn btn-glow sln-icon--back BackBtn4" href="@if(isset($provider->id)) javascript:void(0) @else {{url($locale.'/product/'.$product->id)}} @endif">
																<!-- @if(isset($provider->id)){{url($locale.'/booking/'.$provider->id)}} @else {{url($locale.'/probooking/'.$product->id)}} @endif -->
																<i class="glyphicon glyphicon-chevron-left"></i> Back</a>
														</div>
														<div class="col-sm-6">
															<button id="sln-step-submit" class="btn btn-glow" type="submit" name="submit_summary" value="next">
																Finalize <i class="glyphicon glyphicon-chevron-right"></i>
															</button>
														</div>
													</div>
											    </div>
											</div>    </form>
											@endif
											        <div id="sln-notifications"></div>
											</div>
