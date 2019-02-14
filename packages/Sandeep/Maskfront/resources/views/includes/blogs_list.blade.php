<ol class="list-inline pro--list2 content mCustomScrollbar _mCS_1 mCS-autoHide">
	@foreach($posts as $post)
	<li class="product_box col-md-4">
		<div class="well pr-well">
			@php($img = url('files/'.$post->image))
			<a href="{{url($locale.'/blog/'.$post->id)}}" class="product_image_link">
				<div class="thumb list-group-image item_thumb" style="background:url({{$img}})">
				</div>
			</a>
			<div class="captions">
				<div class="col-sm-12 pad-0 user-areas">
					<div class="img-sec">
						<h5>{{$post->title}}</h5>
					</div>
				</div>
				<p class="group inner list-group-item-text">{!!San_Help::sanLimited($post->body,100,'',false)!!}</p>
			</div>
			<div class="col-sm-12 text-center read_more_section">
				<a href="{{url($locale.'/blog/'.$post->id)}}">Read More</a>
			</div>
		</div>
	</li>
	@endforeach
</ol>