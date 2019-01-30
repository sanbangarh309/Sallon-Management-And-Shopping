<table class="table">
	<thead>
		<tr>
		<td>ID</td>
		<td>{!!San_Help::sanLang('When')!!}</td>
		<td>{!!San_Help::sanLang('Services')!!}</td>
		<td>{!!San_Help::sanLang('Assistants')!!}</td>
		<td>{!!San_Help::sanLang('Price')!!}</td>
		<td>{!!San_Help::sanLang('Service Provider Name')!!}</td>
		<td>{!!San_Help::sanLang('Status')!!}</td>
		<td>{!!San_Help::sanLang('Action')!!}</td>
		</tr>
	</thead>
	<tbody>
		@if(isset($user->getBookings) && !empty($user->getBookings))
		@foreach($user->getBookings as $booking)
		@if($booking->status == 'Pending' || $booking->status == 'Confirmed')
		@php($assistants = \TCG\Voyager\Models\Assistant::whereIn('id',explode(',',$booking->assistent_ids))->pluck('name')->toArray())
		@php($services = \TCG\Voyager\Models\Service::whereIn('id',explode(',',$booking->service_ids))->pluck('name')->toArray())
		@php($provider = \TCG\Voyager\Models\Provider::find($booking->salon_id))
		<tr>
			<td data-th="ID">{{$booking->id}}</td>
			<td data-th="When"><div>{{$booking->book_date}}</div><div>{{$booking->time}}</div></td>
			<td data-th="Services">{{implode(',',$services)}}</td>
			<td data-th="Assistants">{{implode(',',$assistants)}}</td>
			<td data-th="Price"><nobr>{{$booking->price}}SAR</nobr></td>
			<td data-th="Service Provider"><nobr><strong>{{$provider->name}}</strong></nobr></td>
			<td data-th="Status">
				<div class="status">
					<nobr>
						<span class="glyphicon glyphicon-clock" aria-hidden="true"></span>
						<span class="glyphicon-class"><strong>{{$booking->status}}</strong></span>
					</nobr>
				</div>
				<div>
				</div>
			</td>
			<td data-th="Action" class="col-md-3">
				<div>
					<div class="col-xs-10 col-sm-6 col-md-12">
						<div class="sln-btn sln-btn--emphasis sln-btn--medium sln-btn--fullwidth danger">
							<button onclick="cancelBooking({{$booking->id}},{{$booking->user_id}});">
							Cancel booking									</button>
						</div>
					</div>
					<div style="clear: both"></div>
					<!-- SECTION NEW END -->
				</div>
			</td>
		</tr>
		@endif
		@endforeach
		@endif
	</tbody>
</table>