<table class="table">
	@if(isset($product_reviews) && count($product_reviews) > 0 )
	<thead>
		<tr>
			<td>Rating</td>
			<td>Description</td>
		</tr>
	</thead>
	@endif
	<tbody>	
	@if(isset($product_reviews) && count($product_reviews) > 0 )
	  @foreach($product_reviews as $review)								
		<tr>
			<td data-th="ID">
				<div class="spr-form-input spr-starrating ratings-box">
				 <ul>
				 @for ($i = 1; $i <= 5; $i ++)
					@php($selected = "")
					@if (isset($review->rating) && $i <= $review->rating)
						@php($selected = "selected")
					@endif
					<li class='{{$selected}}'>&#9733;</li>  
				 @endfor
				</ul>	
			</div>
		   </td>
			<td data-th="When"><div>{!!$review->review!!}</div><div>{!!$review->updated_at!!}</div></td>
		</tr>
	  @endforeach
	 @else
	 <tr style="text-align: center;">
	 	<td>{!!San_Help::sanLang('No Reviews Yet')!!}</td>
	 </tr>
	 @endif	
	</tbody>
</table>