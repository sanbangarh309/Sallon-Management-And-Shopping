@if($status == 'Pending')
@php($btn1 = 'Accept')
@php($btn2 = 'Refuse')
@else
@php($btn1 = 'Complete')
@php($btn2 = 'No Visit')
@endif
<link href="{{ San_Help::san_Asset('css/rating.css') }}" rel="stylesheet">
<table id="confirmed_bookings" style="float: left; width: 100%;" class="display dataTable no-footer" role="grid" aria-describedby="confirmed_bookings_info" width="100%" cellspacing="0">
	<thead>
		<tr role="row">
			<th class="sorting_asc" tabindex="0" aria-controls="pending_bookings" rowspan="1" colspan="1" style="width: 1074px;" aria-sort="ascending" aria-label="Name: activate to sort column descending">Name</th>
		</tr>
	</thead>
	<tbody>
		@if(!$provider->getBookings->isEmpty())
		@foreach($provider->getBookings as $booking)
		@if(trim($booking->status) == $status)
		@php($user = \App\User::find($booking->user_id))
		@php($assistants = \TCG\Voyager\Models\Assistant::whereIn('id',explode(',',$booking->assistent_ids))->pluck('name')->toArray())
		@php($services = \TCG\Voyager\Models\Service::whereIn('id',explode(',',$booking->service_ids))->pluck('name')->toArray())
		@foreach($services as $key => $service)
		@php($services[$key] = San_Help::sanGetLang($service,$locale))
		@endforeach
		<tr>
			<td>
				<ol class="list-group gutter-list1">
					<li class="list-group-item">
						<div class="media">
							<figure class="pull-left">
								<img class="media-object img-circle img-responsive" src="@if(isset($user->avatar) && $user->image 	!=''){{url('files/'.$user->avatar)}} @else {{ San_Help::san_Asset('images/member-1.jpg') }} @endif" alt="">
							</figure>
						</div>
						<div class="col-sm-8 media-text">
							<h4 class="list-group-item-heading gutter-head">@if(isset($user->name)){{$user->name}}@endif
								<span class="pull-right bkng_act">{{$booking->status}}</span>
							</h4>
							<ul class="list-group-item-text">
								<li class="gutter-time">{{$booking->book_date}}</li>
								<li class="gutter-specialist">Stylist : {!!implode(',',$assistants)!!}</li>
								<li class="gutter-service">Service : {!!implode(',',$services)!!}</li>
								@if($status =='Completed')
								<li class="gutter-comments">
									@if(isset($reviews))
									<span class="pull-left"><a href="javascript:void(0);"><i class="fa fa-comments"></i> No Reviews</a></span>
									@else
									<span class="pull-left"><a href="javascript:void(0)" data-toggle="modal" data-target="#see_user_review_{{$booking->id}}" class="reviews-btn" data-id="bookid"><i class="fa fa-comments"></i> Reviews</a></span>
									<span class="pull-left"><a href="javascript:void(0)" data-toggle="modal" @if($user->user_reviews->isEmpty()) style="pointer-events: none;cursor: default;" @else onClick="showReplyWindow({{$user->user_reviews[0]->id}},'{!!$user->user_reviews[0]->reply!!}');return false;" @endif class="reply-btn" data-id="bookid"><i class="fa fa-mail-reply"></i> Reply</a></span>
									@endif
								</li>
								@else
								<button type="submit" class="btn btn-success submt-btn booking_accept" value="booking_accept" data-id="{{$booking->id}}" data-status="{{$status}}" data-uid="{{$user->id}}" id="{{$provider->id}}">
									{!!San_Help::sanLang($btn1)!!}</button>

									<button type="submit" class="btn btn-danger submt-btn booking_reject" value="booking_reject" data-id="{{$booking->id}}" data-status="{{$status}} data-uid="{{$user->id}}" id="booking_reject">{!!San_Help::sanLang($btn2)!!}</button>
									@endif
								</ul>
							</div>
							<div class="col-sm-2 amnt-txt text-right">
								<h2> Amount <small> SAR {{$booking->price}} </small></h2>
								<h2> Pay Method <small> {{ucfirst($booking->pay_method)}} </small></h2>
							</div>
						</li>
					</ol>
				</td>
			</tr>
			@endif
			@endforeach
			@else
			<tr style="text-align: center;">
				<td> No {{$status}} Bookings Exist Yet </td>
			</tr>
			@endif
		</tbody>
	</table>
