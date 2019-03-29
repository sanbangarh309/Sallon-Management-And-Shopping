@php($page = 'blog_detail')
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
<div class="profile-dash gutterspice-box" style="background:url({{url('files/'.$post->image)}})">
		<div class="container">
				<div class="col-sm-12 texd-center pad-0">
					<a href="#">
						<h3>
							{!!San_Help::sanGetLang($post->title)!!}
							<span class="small">{{$post->excerpt}}</span>
						</h3>
					</a>
				</div>
		</div>
</div>
<main class="post-main container-fluid">
	<div class="col-md-12 page-inner">
		<div class="container">
			<section class="spinz-section">
				<!-- {!!$post->body!!} -->
				<div class="col-md-12 content_area">
					<figure class="feature-img" style="background:url({{url('files/'.$post->image)}})"></figure>
					{!!San_Help::sanGetLang($post->body)!!}
				</div>
			</section>
			<div class="col-sm-12 sharing-section">
				<div class="sharethis-inline-share-buttons"></div>
			</div>
			<div class="col-sm-12 comment-posted">
				<ol class="list-inline commnt-list">
					<li>
						<article id="comid-7" class="comment-body">
							<div class="comment-meta">
								<div class="comment-author vcard">
									<img alt="" src="https://secure.gravatar.com/avatar/4e403d50ef58b137f6690cb60d11f644?s=56&amp;d=mm&amp;r=g" class="avatar avatar-56 photo" width="56" height="56">
									<div class="coment-info">
										<b class="fn">demotesting_7</b> <span class="says">says:</span>	
										<div class="comment-metadata">
											<a href="#">
												<time datetime="2015-05-04T08:23:28+00:00">May 4, 2015 at 8:23 am</time>
											</a>
										</div><!-- .comment-metadata -->
									</div>
								</div><!-- .comment-author -->
							</div><!-- .comment-meta -->

							<div class="comment-content">
								<p>Ut nisi nulla, consequat iaculis mollis non, fringilla ut justo. Vestibulum pharetra molestie fringilla. Donec elementum ligula sed turpis commodo tristique. Suspendisse cursus posuere eros at auctor. Nulla facilisi. Sed vitae aliquam orci. Ut lobortis, felis sed viverra egestas, sem velit dapibus maur.</p>
							</div><!-- .comment-content -->
						</article>
					</li>
				</ol>
			</div>
			<div class="col-md-12 form-area">
				<h3 id="reply-title" class="comment-reply-title">Leave a Reply</h3>
				<p class="comment-notes"><span id="email-notes">Your email address will not be published.</span> Required fields are marked <span class="required">*</span></p>
				<div class="form-section col-sm-8 p-0">
					<form id="feedback-form" class="feedback-form">
						<div class="col-sm-12 p-0">
							<div class="row">
								<div class="col-sm-6">
									<div class="form-group">
										<label class="control-label">Name <sup>*</sup></label>
										<input class="form-control" type="text" name="username" required>
									</div>
								</div>
								<div class="col-sm-6">
									<div class="form-group">
										<label class="control-label">Email <sup>*</sup></label>
										<input class="form-control" type="email" name="user-email" required>
									</div>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label">Comment <sup>*</sup></label>
							<textarea rows="5" class="form-control" type="text" name="user-comment" required></textarea>
						</div>
						<div class="form-group">
							<button class="btn btn-primary submit--btn">Submit</button>
						</div>
					</form>
				</div>
			</div>
			<div class="col-md-12 related-posts p-0">
				<h2 class="text-center text-uppercase">Related Posts</h2>
				<div class="col-md-12 p-0">
					<div class="row">
						<div class="col-md-4">
							<div class="card card-blog">
								<div class="card-image">
									<a href="#">
										<img width="360" height="240" src="https://mllj2j8xvfl0.i.optimole.com/w:360/h:240/q:90/rt:fill/g:ce/https://s20206.pcdn.co/wp-content/uploads/sites/129/2016/10/blog3-1.jpg" class="wp-post-image" alt="" />
									</a>
								</div>
								<div class="post--content">
									<h4 class="card-title">
										<a class="blog-item-title-link" href="#">Admiration prosperous now</a>
									</h4>
									<p class="card-description">Extremity direction existence as dashwoods do up. Securing marianne led welcomed offended but offering six raptures. Conveying do newspaper rapturous oh at. Two indeed suffer saw beyond far former mrs remain. Occasional continuing possession we <a class="moretag" href="#"> Read more…</a></p>
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<div class="card card-blog">
								<div class="card-image">
									<a href="#">
										<img width="360" height="240" src="https://mllj2j8xvfl0.i.optimole.com/w:360/h:240/q:90/rt:fill/g:ce/https://s20206.pcdn.co/wp-content/uploads/sites/129/2016/10/blog3-1.jpg" class="wp-post-image" alt="" />
									</a>
								</div>
								<div class="post--content">
									<h4 class="card-title">
										<a class="blog-item-title-link" href="#">Admiration prosperous now</a>
									</h4>
									<p class="card-description">Extremity direction existence as dashwoods do up. Securing marianne led welcomed offended but offering six raptures. Conveying do newspaper rapturous oh at. Two indeed suffer saw beyond far former mrs remain. Occasional continuing possession we <a class="moretag" href="#"> Read more…</a></p>
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<div class="card card-blog">
								<div class="card-image">
									<a href="#">
										<img width="360" height="240" src="https://mllj2j8xvfl0.i.optimole.com/w:360/h:240/q:90/rt:fill/g:ce/https://s20206.pcdn.co/wp-content/uploads/sites/129/2016/10/blog3-1.jpg" class="wp-post-image" alt="" />
									</a>
								</div>
								<div class="post--content">
									<h4 class="card-title">
										<a class="blog-item-title-link" href="#">Admiration prosperous now</a>
									</h4>
									<p class="card-description">Extremity direction existence as dashwoods do up. Securing marianne led welcomed offended but offering six raptures. Conveying do newspaper rapturous oh at. Two indeed suffer saw beyond far former mrs remain. Occasional continuing possession we <a class="moretag" href="#"> Read more…</a></p>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>
@endsection