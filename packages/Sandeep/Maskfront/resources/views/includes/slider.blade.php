<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css" />
<div id="home-slider">
  <!-- New Slider -->
  <div id="myslider2" class="juicyslider hidden-xs">
    <ul>
      @foreach(San_Help::getSliderImages() as $ky => $slider)
      <li><img src="{{$slider}}" alt=""></li>
      @endforeach
    </ul>
    <div class="nav next"></div>
    <div class="nav prev"></div>
    <div class="mask"></div>
  </div>
  <!-- ***** -->
  <div id="banner" class="visible-xs">
    <img src="https://mask-app.com/wp-content/themes/mask-sallon/images/mobile_slider.jpg" class="img-responsive banner-img" alt="">
  </div>
  @if($page !='business')
  <div id="home-bannerinner">
    <div class="container">
      <div class="col-sm-8 col-sm-offset-2 ex-head tp-texts">
        <div class="row">
          <h1>@if($locale == 'en' && (!app('request')->segment(2) || app('request')->segment(2) == 'book')){!!$home->slider_title_one!!}@elseif($locale == 'ar' && (!app('request')->segment(2) || app('request')->segment(2) == 'book')){!!$home->slider_title_one_ar!!}@elseif($locale == 'en' && (!app('request')->segment(2) || app('request')->segment(2) == 'shop')){!!$home->slider_title_one_shop!!}@else{!!$home->slider_title_one_shop_ar!!}@endif</h1>
          <h4 class="lp-banner-browse-txt text-uppercase">@if($locale == 'en' && (!app('request')->segment(2) || app('request')->segment(2) == 'book')){!!$home->slider_title_two!!}@elseif($locale == 'ar' && (!app('request')->segment(2) || app('request')->segment(2) == 'book')){!!$home->slider_title_two_ar!!}@elseif($locale == 'en' && (!app('request')->segment(2) || app('request')->segment(2) == 'shop')){!!$home->slider_title_two_shop!!}@else{!!$home->slider_title_two_shop_ar!!}@endif</h4>
        </div>
      </div>
      <div class="col-md-8 col-md-offset-2 col-sm-12 ex-head selection-btm">
        <div class="row">
          <div class="caption-bottom">
            <!--TABS-SECTION-->
            <div class="type-selection">
              <ul class="nav nav-pills">
                <!-- <li class="active"><a data-toggle="tab" href="#book">{!!San_Help::sanLang('Book')!!}</a></li>
                <li><a data-toggle="tab" href="#buypro" style="width: 113px;">{!!San_Help::sanLang('Shopping')!!}</a></li> -->
                <li @if(!app('request')->segment(2) || app('request')->segment(2) == 'book') class="active" @endif><a href="{{route('book')}}">{!!San_Help::sanLang('Book')!!}</a></li>
                <li @if(app('request')->segment(2) == 'shop') class="active" @endif><a  href="{{route('shop')}}" style="width: 113px;">{!!San_Help::sanLang('Shopping')!!}</a></li>
              </ul>
            </div>
            <div class="col-sm-12 pad-0">
              <div class="panel form-panel">
                <div class="tab-content">
                  <div id="book" class="tab-pane fade @if(!app('request')->segment(2) || app('request')->segment(2) == 'book') in active @endif">
                    <form id="search_form" rel="" autocomplete="off" method="GET" action="{{route('search')}}">
                      <input type="hidden" name="type" value="services">
                      <input type="hidden" id="cust_lat" name="cust_lat" value="">
                      <input type="hidden" id="cust_long" name="cust_long" value="">
                      <div class="col-sm-12 inputt-grp">
                        <div class="row">
                          <div class="col-sm-5 pad-0">
                            <div class="form-group">
                              <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-search"></i></span>
                                <input class="form-control" list="serv_cats" placeholder="{!!San_Help::sanLang('Search Service')!!}" type="text" id="service_srch_list">
                                <datalist id="serv_cats">
                                  @foreach($services as $service)
                                  <option data-id="{{ $service->id }}" value="{{ $service->name }}">
                                    @endforeach
                                  </datalist>
                                </div>
                              </div>
                            </div>
                            @if(app('request')->segment(2) != 'shop')
                            <div class="col-sm-3 pad-0">
                              <div class="form-group">
                                <div class="input-group">
                                  <span class="input-group-addon"><i class="fa fa-map-marker"></i></span>
                                  <input class="form-control location_wr" id="location_wr1" placeholder="{!!San_Help::sanLang('Where')!!}" name="wr" type="text">
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-2 pad-0">
                              <div class="form-group">
                                <div class="input-group cstm--checkbox">
                                  <input name="near-me" id="near_me" type="checkbox">
                                  <label class="control-label">{!!San_Help::sanLang('Near Me')!!}</label>
                                </div>
                              </div>
                            </div>
                            @endif
                            <div class="col-sm-2 custom_btn_search pad-xs-0">
                              <div class="form-group">
                                <a href="javascript:void(0)" id="submit_search" class="btn search-btn"><i class="fa fa-search"></i> {!!San_Help::sanLang('Search')!!}</a>
                              </div>
                            </div>
                          </div>
                        </div>
                      </form>
                    </div>
                    <div id="buypro" class="tab-pane fade @if(app('request')->segment(2) == 'shop') in active @endif">
                      <form id="product_buy_form" rel="" autocomplete="off" method="GET" action="{{route('search')}}">
                        <input type="hidden" name="type" value="products">
                        <div class="col-sm-12 inputt-grp">
                          <div class="row">
                            <div class="col-sm-9 pad-0">
                              <div class="form-group">
                                <div class="input-group">
                                  <span class="input-group-addon"><i class="fa fa-search"></i></span>
                                  <input class="form-control location_wr" placeholder="{!!San_Help::sanLang('Search Product')!!}" id="product_srch_list" type="text" list="prrr_cats">
                                  <!-- <select class="selectpicker" required="" data-show-subtext="true" data-live-search="true" name="pr">
                                  <option value="">{!!San_Help::sanLang('Search Product')!!}</option>
                                  @foreach($products as $product)
                                  <option value="{{ $product->id }}">{{ $product->name }}</option>
                                  @endforeach
                                </select> -->
                                <datalist id="prrr_cats">
                                  @foreach($products as $product)
                                  <option value="{{ $product->name }}" data-id="{{ $product->id }}">
                                    @endforeach
                                  </datalist>
                                </div>
                              </div>
                            </div>
                            @if(app('request')->segment(2) != 'shop')
                            <div class="col-sm-3 pad-0">
                              <div class="form-group">
                                <div class="input-group">
                                  <span class="input-group-addon"><i class="fa fa-map-marker"></i></span>
                                  <input class="form-control" placeholder="{!!San_Help::sanLang('Where')!!}" name="wr" id="location_wr4" type="text">
                                </div>
                              </div>
                            </div>

                            <div class="col-sm-2 pad-0">
                              <div class="form-group">
                                <div class="input-group cstm--checkbox">
                                  <input name="near-me" type="checkbox">
                                  <label class="control-label">{!!San_Help::sanLang('Near Me')!!}</label>
                                </div>
                              </div>
                            </div>
                            @endif
                            <div class="@if(app('request')->segment(2) == 'shop')col-sm-3 @else col-sm-2 @endif pad-xs-0 custom_btn_search">
                              <div class="form-group">
                                <a href="javascript:void(0)" id="submit_pro_search" class="btn search-btn"><i class="fa fa-search"></i> {!!San_Help::sanLang('Search')!!}</a>
                              </div>
                            </div>
                          </div>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
              <!--TABS-SECTION-ENDS-->
            </div>
          </div>
        </div>
      </div>
    </div>
    @endif
  </div><!--/#home-slider-->
  @push('scripts')
  <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>
  <script src="https://maps.googleapis.com/maps/api/js?key={{config('maskfront.google_key')}}&libraries=places"></script>
  <script type="text/javascript">
  var input = document.getElementById('location_wr1');
  var input2 = document.getElementById('product_srch_list');
  var input4 = document.getElementById('location_wr4');
  if (typeof google.maps.places === 'object'){
    new google.maps.places.Autocomplete(input);
    new google.maps.places.Autocomplete(input2);
    new google.maps.places.Autocomplete(input4);
  }
</script>
@endpush
