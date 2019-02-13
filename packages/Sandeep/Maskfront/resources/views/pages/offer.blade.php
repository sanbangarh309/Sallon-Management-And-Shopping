 @php($page = 'offer')
@extends('maskFront::layouts.app')
@section('main-content')
	<main class="container-fluid p-0 content-main">
		<div class="container">
			<div class="col-sm-12 text-center">
				<h1 class="page-title">{!!San_Help::sanLang("Get MASK's Beauty & Salon Offers")!!}</h1>
			</div>
			<div class="col-sm-12 offers-blocks">
				<div class="row">
					@foreach($offers as $offer)
					<div class="col-sm-4">
						<div class="card offers-card">
							<div class="fig-mask">
								<div class="offer-figure" style="background:url(@if($offer->offer_type =='provider'){{url('files/'.$offer->provider->avatar)}} @elseif($offer->offer_type =='product') {{url('files/'.\TCG\Voyager\Models\Product::find($offer->pro_id)->image)}} @endif)">
									
								</div>
								<h5 class="offer-service-head">@if($offer->offer_type =='provider'){{$offer->provider->name}} @elseif($offer->offer_type =='product') {{\TCG\Voyager\Models\Product::find($offer->pro_id)->name}} @endif</h5>
							</div>
							<div class="panel-body">
								<div class="discount-code text-center">
									<h5 class="code-num"><span class="text-num" id="copied_text_{{$offer->id}}">{{$offer->code}}</span><a href="javascript:void(0);" onclick="copyToClipboard(document.getElementById('copied_text_{{$offer->id}}'));$(this).tooltip('toggle');" title="Copied!" id="copy_textt" class="btn copy-btn">COPY</a></h5>
								</div>
								<div class="valid-offers text-center">
									<p class="offer-validity">Valid till {{ Carbon\Carbon::parse($offer->valid_to)->format('d F Y') }}</p>
								</div>
							</div>
						</div>
					</div>
					@endforeach
				</div>
			</div>
		</div>
	</main>
@endsection
@section('javascript')
<script type="text/javascript">
	// $(function(){
	// 	$('#copy_textt').tooltip({
	// 	  trigger: 'click',
	// 	  placement: 'bottom',
	// 	  toggle : true
	// 	});
	// });
function copyToClipboard(elem) {
	  // create hidden text element, if it doesn't already exist
    var targetId = "_hiddenCopyText_";
    var isInput = elem.tagName === "INPUT" || elem.tagName === "TEXTAREA";
    var origSelectionStart, origSelectionEnd;
    if (isInput) {
        // can just use the original source element for the selection and copy
        target = elem;
        origSelectionStart = elem.selectionStart;
        origSelectionEnd = elem.selectionEnd;
    } else {
        // must use a temporary form element for the selection and copy
        target = document.getElementById(targetId);
        if (!target) {
            var target = document.createElement("textarea");
            target.style.position = "absolute";
            target.style.left = "-9999px";
            target.style.top = "0";
            target.id = targetId;
            document.body.appendChild(target);
        }
        target.textContent = elem.textContent;
    }
    // select the content
    var currentFocus = document.activeElement;
    target.focus();
    target.setSelectionRange(0, target.value.length);
    
    // copy the selection
    var succeed;
    try {
    	  succeed = document.execCommand("copy");
    } catch(e) {
        succeed = false;
    }
    // restore original focus
    if (currentFocus && typeof currentFocus.focus === "function") {
        currentFocus.focus();
    }
    
    if (isInput) {
        // restore prior selection
        elem.setSelectionRange(origSelectionStart, origSelectionEnd);
    } else {
        // clear temporary content
        target.textContent = "";
    }
    return succeed;
} 
</script>
@stop