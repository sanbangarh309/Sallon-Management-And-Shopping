<div id="sln-salon" class="sln-bootstrap container-fluid sln-salon--l sln-step-summary">
	@if(Auth::check() && Auth::user()->role_id == 3)
	@php($user = Auth::user())
	<?php //echo '<pre>';print_r($user); ?>
		<form method="post" action="{{url($locale.'/cart_book')}}" role="form" id="salon-step-summary">
			{{ csrf_field() }}
			<input type="hidden" name="tab" value="payment">
			<input type="hidden" name="total_amount" id="san_ttl_amnt" value="@if(!$cartdata->isEmpty()){{ number_format($cartdata->sum('total'), 2) }} @else 0.00 @endif">
			<h2 class="salon-step-title">Order summary</h2>
			<div class="row">
				<div class="col-md-12">
					<p class="sln-text--dark">
						Dear <strong>{{$user->name}}</strong>
						<br>Here are the details of your order:</p>
					</div>
				</div>
				<!-- @if(!$cartdata->isEmpty())
				@php($pronames = \TCG\Voyager\Models\Product::whereIn('id',$cartdata->pluck('product_id')->toArray())->pluck('name')->toArray())
				@endif
				@if(isset($pronames)){implode(',',$pronames)}@endif -->
				<div class="sln-summary">
					<div class="col-md-12 pad-0">
						<div class="rows">
							<div class="col-md-12 pad-0">
								@if(!$cartdata->isEmpty())
								@foreach($cartdata as $cart)
								<div class="seperate_product">
									<div class="sln-summary-row">
										<div class="col-sm-6 col-md-6 sln-data-descs">
											<span class="label">Product Booked</span>
										</div>
										<div class="col-sm-6 col-md-6 sln-data-vals">{{\TCG\Voyager\Models\Product::find($cart->product_id)->name}}</div>

									</div>
									<div class="sln-summary-row">
										<div class="col-sm-6 col-md-6 sln-data-descs">
											<span class="label">Price</span>
										</div>
										<div class="col-sm-6 col-md-6 sln-data-vals">@if(isset($cart->total)){!!$cart->total!!}@endif SAR</div>

									</div>
									<div class="sln-summary-row">
										<div class="col-sm-6 col-md-6 sln-data-descs">
											<span class="label">Quantity</span>
										</div>
										<div class="col-sm-6 col-md-6 sln-data-vals">@if(isset($cart->qty)){!!$cart->qty!!}@endif</div>

									</div>
									<div class="sln-summary-row">
										<div class="col-sm-6 col-md-6 sln-data-descs">
											<span class="label">Color</span>
										</div>
										<div class="col-sm-6 col-md-6 sln-data-vals">{!!$cart->color!!}</div>

									</div>
									<!--  <div class="sln-summary-row">
									<div class="col-sm-6 col-md-6 sln-data-descs">
									<span class="label">Description</span>
								</div>
								<div class="col-sm-6 col-md-6 sln-data-vals">{!!\TCG\Voyager\Models\Product::find($cart->product_id)->description!!}</div>

							</div> -->
						</div>

						@endforeach
						@endif
						<div class="sln-summary-row">
							<div class="col-sm-6 col-md-6 sln-data-descs">
								<span class="label">Discount</span>
							</div>
							<div class="col-sm-6 col-md-6 sln-data-vals">
								<span id="sln_discount_value">0SAR</span>

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
						<h3 class="col-xs-6 sln-total-prices">@if(!$cartdata->isEmpty()){{ number_format($cartdata->sum('total'), 2) }}SAR @else 0SAR @endif</h3>
					</div>
				</div>
				@endif

				<div class="sln-summary-row">

					<div class="col-sm-12 sln-data-redeem_jewelries">
						<span class="redeempoint_span"><input type="checkbox" name="redeem_points" value="" id="redeempoint_checkbox" data_user_id="{{Auth::user()->id}}" data_diamonds="{{$user->rewardpoint_balance}}" data-total="@if(!$cartdata->isEmpty()){{$cartdata->sum('total')}}@endif"></span>
						<label>Please click checkbox to redeem jewelries. You have {{$user->rewardpoint_balance}} jewelries </label>
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
			</div>
			<div class="col-md-12 sln-input sln-input--action">
				<div class="sln-box--formactions form-actions row">
					<div class="col-sm-6">
						<button id="sln-step-submit" class="btn btn-glow" type="submit" name="submit_summary" value="next">
							Finalize <i class="glyphicon glyphicon-chevron-right"></i>
						</button>
					</div>
				</div>
			</div>
		</div>    </form>
		<div id="sln-notifications"></div>
	</div>
