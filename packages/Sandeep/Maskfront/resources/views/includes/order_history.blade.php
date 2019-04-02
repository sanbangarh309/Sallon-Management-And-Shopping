<table class="table">
	<thead>
		<tr>
			<td>ID</td>
			<td>{!!San_Help::sanLang('When')!!}</td>
			<td>{!!San_Help::sanLang('Products')!!}</td>
			<td>{!!San_Help::sanLang('Price')!!}</td>
			<td>{!!San_Help::sanLang('Service Provider Name')!!}</td>
			<td>{!!San_Help::sanLang('Status')!!}</td>
			<td>{!!San_Help::sanLang('Method')!!}</td>
			<td>{!!San_Help::sanLang('Action')!!}</td>
		</tr>
	</thead>
	<tbody>
		@if(isset($user->orders) && !$user->orders->isEmpty())
		@foreach($user->orders as $order)
		<tr>
			@php($names = array())
			@php($pro_names = array())
			@if($order->product_ids == null && $order->provider_id)
				@php($id_data = unserialize($order->provider_id))

				@if(isset($id_data))
				@foreach($id_data as $provider => $product)
					@php($names[] = \TCG\Voyager\Models\Product::whereIn('id',$product)->pluck('name')->toArray())
					@php(array_push($pro_names,\TCG\Voyager\Models\Provider::find($provider)->name))
				@endforeach
				@endif
			@else
				@php(array_push($names,\TCG\Voyager\Models\Product::find($order->product_ids)->name))
				@php(array_push($pro_names,\TCG\Voyager\Models\Provider::find($order->provider_id)->name))
			@endif
			
			<td data-th="ID">{{$order->id}}</td>
			<td data-th="When"><div>{{$order->created_at}}</div></td>
			<td data-th="Product">
				@foreach($names as $name)
					{{implode(',',$name)}},
				@endforeach
			</td>
			<td data-th="Price"><nobr>{{$order->price}}SAR</nobr></td>
			<td data-th="Service Provider"><nobr><strong>{{implode(',',$pro_names)}}</strong></nobr></td>
			<td data-th="Status">
				<div class="status">
					<nobr>
						<span class="glyphicon glyphicon-clock" aria-hidden="true"></span>
						<span class="glyphicon-class"><strong>{{$order->order_status}}</strong></span>
					</nobr>
				</div>
				<div>
				</div>
			</td>
			<td>{{$order->payment_method}}</td>
			@if($order->order_status == 'pending')
			<td data-th="Action" class="col-md-3">
				<div>
					<div class="col-xs-10 col-sm-6 col-md-12">
						<div class="sln-btn sln-btn--emphasis sln-btn--medium sln-btn--fullwidth danger">
							<button onclick="cancelOrder({{$order->id}},{{$user->id}});">Cancel Order</button>
						</div>
					</div>
					<div style="clear: both"></div>
					<!-- SECTION NEW END -->
				</div>
			</td>
			@else
			<td data-th="Action" class="col-md-3">
				<div>
					<div class="col-xs-10 col-sm-6 col-md-12">
						<div class="sln-btn sln-btn--emphasis sln-btn--medium sln-btn--fullwidth danger">
							<a href="javascript:void(0);" data-id="{{$order->id}}" onclick="showFeedback({{$order->id}},'order');return false;">{!!San_Help::sanLang('Leave a feedback')!!}</a>
						</div>
					</div>
					<div style="clear: both"></div>
				</div>
	   </td>
			@endif
		</tr>
		@endforeach
		@endif
	</tbody>
</table>