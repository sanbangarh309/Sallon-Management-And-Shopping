<div class="spr-content form-btm" style="display:none;">
										<div class="spr-form" id="form_1398662365302" style="">
										<form method="post" action="#" id="new-review-form" class="new-review-form" onsubmit="submitReview(this);return false;">
										<input name="rating" id="rating" type="hidden" value="@if(isset($user_review->rating)){{$user_review->rating}}@endif">
										<input name="rating_on" value="product" type="hidden">
										<input name="record_id" value="@if(isset($product->id)){{$product->id}}@endif" type="hidden">
										<fieldset class="spr-form-review col-12 p-0">
											<div class="form-group">
											  <label class="spr-form-label" for="review[rating]">{!!San_Help::sanLang('Rating')!!}</label>
											  <div class="spr-form-input spr-starrating " id="product_star_rating">
												<ul>
											  @for ($i = 1; $i <= 5; $i ++)
											    @php($selected = "")
											    @if (isset($user_review->rating) && $i <= $user_review->rating)
											    	@php($selected = "selected")
											  	@endif
											  	<!-- onmouseout="removeHighlight({{$product->id}});" -->
											  	<!-- onmouseover="highlightStar(this,{{$product->id}});" -->
											  <li class='{{$selected}}' onClick="addRating(this,{{$product->id}});">&#9733;</li>  
											  @endfor
											</ul>	
											  </div>
											</div>
											<div class="form-group">
											  <label class="spr-form-label">{!!San_Help::sanLang('Body of Review')!!}<span class="spr-form-review-body-charactersremaining">(1500)</span></label>
											  <div class="spr-form-input">
												<textarea class="spr-form-input spr-form-input-textarea " id="review_body" name="review" rows="5" placeholder="{!!San_Help::sanLang('Write your comments here')!!}">@if(isset($user_review->review)){!!$user_review->review!!}@endif</textarea>
											  </div>
											</div>
										  </fieldset>
										  <fieldset class="spr-form-actions">
											<input class="spr-button spr-button-primary button button-primary btn btn-primary" value="Submit Review" type="submit">
										  </fieldset></form></div>
									  </div>