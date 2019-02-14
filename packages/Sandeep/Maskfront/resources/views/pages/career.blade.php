 @php($page = 'career')
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
<div class="profile-dash gutterspice-box" style="background:url({{url('files/'.$career->image)}})">
		<div class="container">
				<div class="col-sm-12 texd-center pad-0">
					<a href="#">
						<h3>
							{{$career->title}}
							<span class="small">{{$career->excerpt}}</span>
						</h3>
					</a>
				</div>
			</div>
</div>
<div class="container">
<section class="spinz-section">
	{!!$career->body!!}
</section>

</div>
@endsection