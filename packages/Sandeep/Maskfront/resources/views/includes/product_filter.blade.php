<div class="filter_panel">
	<div class="well filter-well">
		<h5>Product Filters</h5>
		<div class="form-group">
			<div class="custom-select">
				@if(app('request')->type == 'services')
					<select class="form-control selectpicker" required="" onchange="searchSallon(this.value,'services')" data-show-subtext="true" data-live-search="true" name="cat">
						<option value="">Search Category</option>
						@foreach($categories as $category)
						<option @if(isset($_GET['sr']) && ($_GET['sr'] == $category->id || urldecode($_GET['sr']) == $category->slug || urldecode($_GET['sr']) == $category->name)) selected = "selected" @endif value="{{ $category->id }}">{!!San_Help::sanGetLang($category->name)!!}</option>
						@endforeach
					</select>
					<span class="select-helper"><svg viewBox="0 0 18 18" role="presentation" aria-hidden="true" focusable="false" style="height: 16px; width: 16px; display: block; fill: rgb(72, 72, 72);"><path d="m16.29 4.3a1 1 0 1 1 1.41 1.42l-8 8a1 1 0 0 1 -1.41 0l-8-8a1 1 0 1 1 1.41-1.42l7.29 7.29z" fill-rule="evenodd"></path></svg></span>
					@else
					<select class="form-control selectpicker" required="" onchange="searchProduct(this.value,'products','category','{{json_encode($_GET)}}')" data-show-subtext="true" data-live-search="true" name="cat">
						<option value="">Search Category</option>
						@foreach($categories as $category)
						<option @if(isset($_GET['pr']) && ($_GET['pr'] == $category->id || urldecode($_GET['pr']) == $category->slug || urldecode($_GET['pr']) == $category->name)) selected = "selected" @endif value="{{ $category->id }}">{!!San_Help::sanGetLang($category->name)!!}</option>
						@endforeach
					</select>
					<span class="select-helper"><svg viewBox="0 0 18 18" role="presentation" aria-hidden="true" focusable="false" style="height: 16px; width: 16px; display: block; fill: rgb(72, 72, 72);"><path d="m16.29 4.3a1 1 0 1 1 1.41 1.42l-8 8a1 1 0 0 1 -1.41 0l-8-8a1 1 0 1 1 1.41-1.42l7.29 7.29z" fill-rule="evenodd"></path></svg></span>
					@endif
			</div>
		</div>
		@if(app('request')->type == 'products')
		<div class="form-group filter_1 p-0">
			<label>Select a color</label>
			<ul class="list-inline colors-list">
				<li class="black"><input name="color" class="custom-radio" type="radio"><label></label></li>
				<li class="green"><input name="color" class="custom-radio" type="radio"><label></label></li>
				<li class="silver"><input name="color" class="custom-radio" type="radio"><label></label></li>
				<li class="lime"><input name="color" class="custom-radio" type="radio"><label></label></li>
				<li class="gray"><input name="color" class="custom-radio" type="radio"><label></label></li>
				<li class="olive"><input name="color" class="custom-radio" type="radio"><label></label></li>
				<li class="white"><input name="color" class="custom-radio" type="radio"><label></label></li>
				<li class="yellow"><input name="color" class="custom-radio" type="radio"><label></label></li>
				<li class="maroon"><input name="color" class="custom-radio" type="radio"><label></label></li>
				<li class="navy"><input name="color" class="custom-radio" type="radio"><label></label></li>
				<li class="red"><input name="color" class="custom-radio" type="radio"><label></label></li>
				<li class="blue"><input name="color" class="custom-radio" type="radio"><label></label></li>
				<li class="purple"><input name="color" class="custom-radio" type="radio"><label></label></li>
				<li class="teal"><input name="color" class="custom-radio" type="radio"><label></label></li>
				<li class="fuchsia"><input name="color" class="custom-radio" type="radio"><label></label></li>
				<li class="aqua"><input name="color" class="custom-radio" type="radio"><label></label></li>
				<li class="orange"><input name="color" class="custom-radio" type="radio"><label></label></li>
				<li class="brown"><input name="color" class="custom-radio" type="radio"><label></label></li>
			</ul>
		</div>
		<!--div class="form-group filter_1 p-0">
			<select class="form-control selectpicker" required="" onchange="searchProduct(this.value,'products','color','{{json_encode($_GET)}}')" data-show-subtext="true" data-live-search="true" name="pro">
				<option value="">Select Color</option>
				@foreach(config('maskfront.colors') as $color_name => $color_code)
					<option @if(isset($_GET['clr']) && $_GET['clr'] == $color_name) selected="selected" @endif value="{{$color_name}}">{{$color_name}}</option>
				@endforeach
			</select>
		</div-->
		<div class="col-sm-12 p-0">
			<div class="row">
				<div class="col-sm-4">
					<div class="form-group">
						<label>Min</label>
						<input type="number" @if(isset($price_filter[0])) value ="{{$price_filter[0]}}" @endif class="form-control" id="min_input" placeholder="$0">
					</div>
				</div>
				<div class="col-sm-4">
					<div class="form-group">
					  <label>Max</label>
					  <input type="number" @if(isset($price_filter[1])) value ="{{$price_filter[1]}}" @endif class="form-control" placeholder="$1,0000" id="max_input">
					</div>
				</div>
				<div class="col-sm-4 btnn_filter">
					<div class="form-group">
						<label></label>
						<?php //echo "<pre>";print_r($_GET['price']); ?>
						<button type="button" class="btn btn-success btn-xs" onclick="searchProduct('price','products','price','{{json_encode($_GET)}}')">Go</button>
					</div>
				</div>
			</div>
		</div>
		<div class="form-group filter_1 p-0">
			<div class="custom-select">
				<select class="form-control selectpicker" required="" onchange="searchProduct(this.value,'products','seller','{{json_encode($_GET)}}')" data-show-subtext="true" data-live-search="true" name="pro">
					<option value="">Select Provider</option>
					@foreach(\TCG\Voyager\Models\Provider::has('getProducts')->get() as $pro_key => $pro_value)
						<option @if(isset($_GET['provider']) && $_GET['provider'] == $pro_value->id) selected="selected" @endif value="{{$pro_value->id}}">{!!San_Help::sanGetLang($pro_value->name)!!}</option>
					@endforeach
				</select>
				<span class="select-helper"><svg viewBox="0 0 18 18" role="presentation" aria-hidden="true" focusable="false" style="height: 16px; width: 16px; display: block; fill: rgb(72, 72, 72);"><path d="m16.29 4.3a1 1 0 1 1 1.41 1.42l-8 8a1 1 0 0 1 -1.41 0l-8-8a1 1 0 1 1 1.41-1.42l7.29 7.29z" fill-rule="evenodd"></path></svg></span>
			</div>
		</div>
		<div class="form-group text-center">
			<button type="button" class="btn btn-primary btn-reset" onclick="searchProduct('reset','products','reset','{{json_encode($_GET)}}')">Reset</button>
		</div>
		@endif
		@if($type == 'services')
		<div class="form-group filter_1 p-0">
			<!-- <input class="form-control" placeholder="Search Category" id="search_category" name="service" type="text" autocomplete="off" value=""> -->
		</div>
		@endif
	</div>
</div>
