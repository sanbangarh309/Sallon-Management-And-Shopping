 @php($page = 'business')
@extends('maskFront::layouts.app')
@section('main-content')
<style type="text/css">
	input.error_red {
		border: 1px solid red !important;
	}
	select.error_red {
		border: 1px solid red !important;
	}
</style>
<link href="{{ San_Help::san_Asset('css/custom.css') }}" rel="stylesheet">
<section id="featured-service" class="smartgutter-one">
		<div class="container">
			<div class="col-sm-12 text-center text-uppercase"><?php //echo '<pre>';print_r($business->excerpt);exit;?>
				<h2 class="page-heading">@if(isset($locale) && $locale == 'en'){!!$business->title!!}@else {!!$business->excerpt!!}@endif</h2>
        <!-- <span class="aside-bx">ماذا يعني لك المستقبل؟</span> -->
			</div>
			<div class="col-sm-12 featured-serv pad-xs-0">
				<ul class="list-inline list-gutter">
					@if(isset($locale) && $locale == 'en'){!!$business->body!!}@else {!!$business->body_ar!!}@endif
					<!-- <li>موقع انترنت احترافي</li><li>تقارير تشمل الحجوزات والعملاء واكثر</li><li>حجوزات اونلاين</li><li>الدفع عن طريق البطاقات الائتمانية</li><li>قائمة بالخدمات الخاصة بك</li><li>الاطلاع على تقييم العملاء وتعليقاتهم </li><li>العروض الخاصة للعملاء</li><li>متابعة المبيعات وكيفية نموها</li><li>جداول زمنية ... اوقات العمل ... وادارة الوقت</li><li>الاطلاع على تقارير الدخل اليومي</li> -->				</ul>
			</div>
			<div class="col-sm-12 text-center">
				<!-- <a href="#" class="btn yell-btn">Signup Now</a> -->
				<button type="button" class="btn btn-lg yell-btn" @if(Auth::check()) disabled="disabled" @endif data-toggle="modal" data-target="#provider_register">{!!San_Help::sanLang('Signup Now')!!}</button>
			</div>
		</div>
	</section>
    	<section class="smart-pro">
			<div class="container">
				<div class="col-sm-12 text-center text-uppercase">
					<h2 class="page-heading">لماذا ماسك؟<span class="aside-bx">كوني حرة في فعل ماتريدين</span></h2>
				</div>
				<div class="col-sm-12 pad-xs-0 text-center gap-30">
					<img src="https://mask-app.com/wp-content/uploads/2018/04/devices1-img.png" class="img-responsive" alt="">
					<p>With Mask application you can develop and grow your business, in which you can manage time effectively and organize appointments easily and fastly.</p>
				</div>
			</div>
		</section>
    	<!-- Download Banner Section -->
	   <section class="app-space" style="background:rgba(0, 0, 0, 0.7) url(https://mask-app.com/wp-content/uploads/2018/04/app-gutter.jpg)">
			<div class="box--overlay"></div>
			<div class="container">
				<div class="col-sm-12">
					<div class="row">
						<div class="col-sm-6 col-sm-offset-6 apptxt-btm text-right wow fadeInDown animated" data-wow-duration="500ms" data-wow-delay="100ms" style="visibility: visible; animation-duration: 500ms; animation-delay: 100ms; animation-name: fadeInDown;">
							<h3 class="text-uppercase">@if(isset($locale) && $locale == 'en'){!!$business->slider_title_one!!}@else {!!$business->slider_title_one_ar!!}@endif</h3>
							<p class="text-right">@if(isset($locale) && $locale == 'en'){!!$business->slider_title_two!!}@else {!!$business->slider_title_two_ar!!}@endif</p>
							<a href="http://localhost/site/sallon/#" class="btn app-btn">Download App</a>
							<img src="https://mask-app.com/wp-content/themes/mask-sallon/images/app-imgs.png" class="app-icons" alt="">
						</div>
					</div>
				</div>
			</div>
		</section>
		    @endsection
