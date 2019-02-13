 @php($page = $page_name)
@extends('maskFront::layouts.app')
@section('main-content')
<style type="text/css">
	.profile-dash {
	padding: 135px 0 60px;
	background-size: cover !important;
	background-position: center center !important;
}
.gutterspice-box h3 {
	text-transform: uppercase;
	color: #fff;
	font-size: 30px;
	font-weight: 300;
	text-align: center;
}
.gutterspice-box h3 .small {
	display: block;
	text-transform: none;
	color: #fff;
}
</style>
<div class="profile-dash gutterspice-box" style="background:url({{url('files/'.$page_data->image)}})">
		<div class="container">
				<div class="col-sm-12 texd-center pad-0">
					<a href="#">
						<h3>@if(isset($locale) && $locale == 'en'){!!$page_data->title!!}@else {!!$page_data->excerpt!!}@endif
							<!-- <span class="small">{!!San_Help::sanLang($page_data->excerpt)!!}</span> -->
						</h3>
					</a>
				</div>
			</div>
</div>
<div class="container">
<section class="spinz-section">
	@if(isset($posts))
		@include('maskFront::includes.blogs_list')
	@elseif($page_name == 'help-center')
	   @php($body = str_replace('%action_url%',route('help-center'),$page_data->body))
		{!!str_replace('%csrf_field%',csrf_field(),$body)!!}
	@else
    @if(isset($locale) && $locale == 'en')
		  {!!$page_data->body!!}
    @else
      {!!$page_data->body_ar!!}
    @endif
	@endif
</section>
</div>
@endsection
