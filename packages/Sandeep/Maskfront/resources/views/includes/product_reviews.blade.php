<style>
.product_star_rating ul {
	margin: 0;
	padding: 5px;
}
.ratings-box ul {
	margin: 0;
	padding: 5px;
}

.product_star_rating li, .ratings-box li{
	cursor: pointer;
	list-style-type: none;
	display: inline-block;
	color: #F0F0F0;
	text-shadow: 0 0 1px #666666;
	font-size: 20px;
}

.product_star_rating .highlight, .product_star_rating .selected {
	color: #F4B30A;
	text-shadow: 0 0 1px #F48F0A;
}
.ratings-box .highlight, .ratings-box .selected {
	color: #F4B30A;
	text-shadow: 0 0 1px #F48F0A;
}
#display_order_reviews_93 ul li:first-child {
    padding-top: 0;
    margin-top: 0;
}
#display_order_reviews_93 ul {
    display: flex;
}
#display_order_reviews_93 ul li {
    width: 27px;
}
#confirmed_bookings tr {
    background: transparent;
}
#confirmed_bookings td {
    padding: 0;
    border: none;
    margin: 0;
}
</style>
@php($review_book_id = !$user->user_reviews->isEmpty() ? $user->user_reviews[0]->record_id : '')
<table class="table">
	@if(isset($user->user_reviews[0]) && count($user->user_reviews) > 0 )
	<thead>
		<tr>
			<td>Rating</td>
			<td>Description</td>
		</tr>
	</thead>
	@endif
	<tbody>
    @if($review_book_id == $booking->id)
            <tr>
			<td data-th="ID">
				<div class="spr-form-input spr-starrating ratings-box product_star_rating">
				 <ul>
				 @for ($i = 1; $i <= 5; $i ++)
					@php($selected = "")
					@if (isset($user->user_reviews[0]->rating) && $i <= $user->user_reviews[0]->rating)
						@php($selected = "selected")
					@endif
					<li class='{{$selected}}'>&#9733;</li>  
				 @endfor
				</ul>	
			</div>
		   </td>
			<td data-th="When"><div>{!!$user->user_reviews[0]->review!!}</div><div>{!!$user->user_reviews[0]->updated_at!!}</div></td>
		</tr>
            @else
            <tr>
              <td colspan="4" style="text-align:center">Reviews Not Exist</td>
            </tr>
            @endif 	
	</tbody>
</table>

<div class="spr-content form-btm">
										<div class="spr-form" id="form_1398662365302" style="">
										<form method="post" action="#" id="new-review-formm_{{$user->user_reviews[0]->id}}" class="new-review-form" onsubmit="submitReview(this);return false;">
										<input name="rating" id="rating" type="hidden" value="@if(isset($user->reviews[0]->rating)){{$user->reviews[0]->rating}}@endif">
                                        <input name="record_id" id="record_id__" value="" type="hidden">
                                        <input name="rating_on" value="booking" id="rating_on_" type="hidden">
										<fieldset class="spr-form-review col-12 p-0">
                                        <input name="reply_on" value="" class="reply_on" id="reply_on" type="hidden">
											<div class="form-group">
											  <label class="spr-form-label">{!!San_Help::sanLang('Body of Review')!!}<span class="spr-form-review-body-charactersremaining">(1500)</span></label>
											  <div class="spr-form-input">
												<textarea class="spr-form-input spr-form-input-textarea review_body" id="review_body" name="review" rows="5" placeholder="{!!San_Help::sanLang('Write your comments here')!!}">@if(isset($user->user_reviews[0]->review)){!!$user->user_reviews[0]->review!!}@endif</textarea>
											  </div>
											</div>
										  </fieldset>
										  <fieldset class="spr-form-actions">
                                          <input class="spr-button spr-button-primary button button-primary btn btn-primary" value="Submit Review" type="submit">
										  </fieldset></form></div>
									  </div>

                                      <!-- <form method="post" action="#" id="new-review-formm" class="new-review-form" onsubmit="submitReview(this);return false;">
            <input name="rating" id="rating" type="hidden" value="@if(isset($user->reviews[0]->rating)){{$user->reviews[0]->rating}}@endif">
            <input name="record_id" id="record_id__" value="" type="hidden">
            <input name="rating_on" value="booking" id="rating_on_" type="hidden">
            @if($page =='user_account')
            <div class="form-group">
              <label class="spr-form-label" for="review[rating]">{!!San_Help::sanLang('Rating')!!}</label>
              <div class="spr-form-input spr-starrating " id="product_star_rating">
                <ul>
                  @for ($i = 1; $i <= 5; $i ++)
                  @php($selected = "")
                  @if (isset($user->reviews[0]->rating) && $i <= $user->reviews[0]->rating)
                  @php($selected = "selected")
                  @endif
                  <li class='{{$selected}}' onClick="addRating(this);">&#9733;</li>
                  @endfor
                </ul>
              </div>
            </div>
            @else
            <input name="reply_on" value="" id="reply_on" type="hidden">
            @endif
            <div class="form-group">
              @if($page =='user_account')
              <label class="spr-form-label">{!!San_Help::sanLang('Body of Review')!!}<span class="spr-form-review-body-charactersremaining">(1500)</span></label>
              @endif
              <div class="spr-form-input">
                <textarea class="spr-form-input spr-form-input-textarea " id="review_body" name="review" rows="5" placeholder="{!!San_Help::sanLang('Write your comments here')!!}">@if(isset($user->reviews[0]->review)){!!$user->reviews[0]->review!!}@endif</textarea>
              </div>
            </div>
            <div class="form-group">
              <input class="spr-button spr-button-primary button button-primary btn btn-primary" value="@if($page =='user_account')Submit Review @else Submit Reply @endif" type="submit">
            </div>
          </form> -->