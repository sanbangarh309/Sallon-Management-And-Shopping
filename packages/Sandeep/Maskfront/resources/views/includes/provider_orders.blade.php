<table class="table">
	<thead>
		<tr>
			<td>ID</td>
			<td>{!!San_Help::sanLang('When')!!}</td>
			<td>{!!San_Help::sanLang('Products')!!}</td>
			<td>{!!San_Help::sanLang('Color')!!}</td>
			<td>{!!San_Help::sanLang('Quantity')!!}</td>
			<td>{!!San_Help::sanLang('Payment Method')!!}</td>
			<td>{!!San_Help::sanLang('Price')!!}</td>
			<td>{!!San_Help::sanLang('Status')!!}</td>
			<!-- <td>{!!San_Help::sanLang('Action')!!}</td> -->
		</tr>
	</thead>
	<tbody>
    <?php //echo '<pre>';print_r($provider);exit; ?>
    @php($order_ids = array())
		@foreach(\TCG\Voyager\Models\Order::all() as $order)
			@if($order->product_ids == null && $order->provider_id)
				@php($id_data = unserialize($order->provider_id))
				@if(isset($id_data))
				@foreach($id_data as $providerr => $product)
				<?php //echo '<pre>';print_r('db id:= '.$providerr);echo '<pre>';print_r('provider id:= '.$provider->id); ?>
                    @if(trim($providerr) == $provider->id)
                        @php(array_push($order_ids,$order->id))
                    @endif
				@endforeach
				@endif
			@else
			  @if($provider->id == $order->product_ids)
			  @php(array_push($order_ids,$order->id))
			  @endif
			@endif
         @endforeach
         <?php //exit;?>
         @foreach(\TCG\Voyager\Models\Order::whereIn('id',array_unique($order_ids))->get() as $order)
         @php($names = array())
			@if($order->product_ids == null && $order->provider_id)
				@php($id_data = unserialize($order->provider_id))
				@if(isset($id_data))
				@foreach($id_data as $providerr => $product)
				
		
					@php($names = array_merge($names,\TCG\Voyager\Models\Product::whereIn('id',$product)->pluck('name')->toArray()))
				@endforeach
				@endif
			@else
				@php(array_push($names,\TCG\Voyager\Models\Product::find($order->product_ids)->name))
			@endif
			<?php //echo '<pre>';print_r($names); ?>
			<tr>
			<td data-th="ID">{{$order->id}}</td>
			<td data-th="When"><div>{{$order->created_at}}</div></td>
			<td data-th="Product">
                  @if(is_array($names))
					{{implode(',',array_unique($names))}},
                  @endif
			</td>
			<td data-th="color">{{$order->color}}</td>
			<td data-th="qty">{{$order->qty}}</td>
			<td data-th="qty">{{$order->payment_method}}</td>
			<td data-th="color">{{$order->price}}</td>
			<td data-th="Status">
				<select class="form-control select2 select2-hidden-accessible provider_order_status" data-id="{{$order->id}}" id="provider_order_status">
                    <option @if($order->order_status == 'processing') selected="selected" @endif value="processing">Processing</option>
                    <option @if($order->order_status == 'shipped') selected="selected" @endif value="shipped">Shipped</option>
                    <option @if($order->order_status == 'delivered') selected="selected" @endif value="delivered">Delivered</option>
                    <option @if($order->order_status == 'cancelled') selected="selected" @endif value="cancelled">Cancelled</option>
                </select>
				<div>
				</div>
			</td>
		</tr>
		@endforeach
        
	</tbody>
</table>