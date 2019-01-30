 <link rel="stylesheet" href="{{ San_Help::san_Asset('css/normalize.css') }}" />
 <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:400,700" />
 <link rel="stylesheet" href="{{ San_Help::san_Asset('css/fontello.css') }}" />
 <link rel="stylesheet" href="{{ San_Help::san_Asset('css/style.css') }}" />
 <style type="text/css">
     h2.salon-step-title {
        padding: 20px;
        text-align: center;
        color: #fff !important;
        font-weight: bold !important;
        background: #131313;
        font-size: 20px !important;
    }
    .sln-thankyou--okbox .sln-icon-wrapper .sln-icon {
        font-size: 2em;
        line-height: 2em;
    }
    #step-6 .sln-btn.sln-btn--noheight {
        display: inline-block;
        position: relative;
        top: initial;
        top: auto;
        right: initial;
        right: auto;
        bottom: initial;
        bottom: auto;
        left: initial;
        left: auto;
        padding: 1em .5em;
        text-transform: uppercase;
    }
 </style>
<div class="payfort_form">
    <section class="payment-method">
    <label class="lead" for="">
        Choose a Payment Method
    </label>
    <ul class="paymethod-list">
        <li>
			<input id="po_cc_merchantpage2" type="radio" checked="checked" name="payment_option" value="cc_merchantpage2" style="display: none" class="">
			<label class="payment-option" for="po_cc_merchantpage2" style="font-weight: normal;">
				<div class="img_logo">
					<img src="{{ San_Help::san_Asset('images/cc1.png') }}" alt="">
					<img src="'{{ San_Help::san_Asset('images/cc2.png') }}" alt="">
					@if($locale=='en')
					   <span class="pay-text">Pay with mada Debit Card</span>
					@else
						<span  class="pay-text">بطاقة مدى البنكية</span>
					@endif
				</div>
				
				<em class="seperator hidden"></em>
				<div class="demo-container hidden">
					<iframe src="" frameborder="0"></iframe>
				</div>
			</label>
			<input type="hidden" id="payment_url" value="{{ url($locale.'/payment?r=getPaymentPage') }}">
			<div class="details" style="">
			<form id="frm_payfort_payment_merchant_page2" class="form-horizontal">
				{{ csrf_field() }}
				<div class="form-group">
					<label class="col-sm-3 control-label" for="payfort_fort_mp2_card_holder_name">Name on Card</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" name="card_holder_name" id="payfort_fort_mp2_card_holder_name" placeholder="Card Holder`s Name" maxlength="50">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label" for="payfort_fort_mp2_card_number">Card Number</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" name="card)number" id="payfort_fort_mp2_card_number" placeholder="Debit/Credit Card Number" maxlength="16">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label" for="payfort_fort_mp2_expiry_month">Expiration Date</label>
					<div class="col-sm-9">
						<div class="row">
							<div class="col-xs-3">
								<select class="form-control" name="expiry_month" id="payfort_fort_mp2_expiry_month">
									<option value="01">Jan (01)</option>
									<option value="02">Feb (02)</option>
									<option value="03">Mar (03)</option>
									<option value="04">Apr (04)</option>
									<option value="05">May (05)</option>
									<option value="06">June (06)</option>
									<option value="07">July (07)</option>
									<option value="08">Aug (08)</option>
									<option value="09">Sep (09)</option>
									<option value="10">Oct (10)</option>
									<option value="11">Nov (11)</option>
									<option value="12">Dec (12)</option>
								</select>
							</div>
							<div class="col-xs-3">
								<select class="form-control" name="expiry_year" id="payfort_fort_mp2_expiry_year">';
									@php($today = getdate())
									@php($year_expire = array())
									@for ($i = $today['year']; $i < $today['year'] + 11; $i++)
										@php($year_expire[] = array(
											'text'  => strftime('%Y', mktime(0, 0, 0, 1, 1, $i)),
											'value' => strftime('%y', mktime(0, 0, 0, 1, 1, $i)) 
											))
									@endfor
									@foreach($year_expire as $year) {
										<option value="{{$year['value']}}">{{$year['text']}}</option>
									@endforeach
									</select>
							</div>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label" for="payfort_fort_mp2_cvv">Card CVV</label>
					<div class="col-sm-3">
						<input type="text" class="form-control" name="cvv" id="payfort_fort_mp2_cvv" placeholder="Security Code" maxlength="4">
					</div>
				</div>
				</form>
				<div class="form-group actions">
					<div class="col-sm-3 col-sm-offset-3">
						<a class="continue" id="btn_continue" href="javascript:void(0)">Continue</a>
					</div>
				</div>
			</div>
		</li>
		<li>
			<input id="po_cc_cash" type="radio" name="payment_option" value="cc_cash" style="display: none" class="">
			<label class="payment-option" for="po_cc_cash" style="font-weight: normal;">
				<img src="{{ San_Help::san_Asset('images/cash.png') }}" alt="">
				<span class="name">Pay with cash</span>
				<em class="seperator hidden"></em>
				<div class="demo-container hidden">
					<iframe src="" frameborder="0"></iframe>
				</div>
			</label>
		</li>
        </section>
</div>
			<div class="col-md-12 sln-form-actions-wrapper sln-input--action">
				<div class="sln-form-actions sln-payment-actions row">
						<div class="_pay_later col-sm-12 text-center" style="display: none;">
							<a href="@if(isset($provider->id)){{url($locale.'/paylater/'.$provider->id)}} @elseif(isset($product->id)) {{url($locale.'/paylater/'.$product->id)}} @else {{url($locale.'/paylater')}} @endif" class="sln-btn sln-btn--noheight btn-later-pay">I'll pay later </a>
						</div>
				 </div>
			</div>
             @push('scripts')
                <script src="{{ San_Help::san_Asset('js/jquery.creditCardValidator.js') }}"></script>
                <script src="{{ San_Help::san_Asset('js/checkout.js') }}"></script>
                <script src="{{ San_Help::san_Asset('js/payfort_payment.js') }}"></script>
            @endpush