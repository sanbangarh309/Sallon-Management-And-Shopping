@if($page !='search')
<div class="back-top" title="Top of Page"><i class="fa fa-arrow-up"></i></div>
<div class="offers"><a href="{{url($locale.'/offers')}}" class="text-uppercase">Offers</a></div>
<footer id="footer">
    <div class="footer-top wow fadeInUp" data-wow-duration="1000ms" data-wow-delay="300ms">
    <div class="container">
      <div class="col-sm-12 pad-0 line-btm">
        <div class="row">
          <div class="col-sm-4 box-foot wow fadeInLeft" data-wow-duration="500ms" data-wow-delay="100ms">
            <h4 class="foot-hd visible-xs">Company <a data-target=".bottom-menu1" data-toggle="collapse" class="pull-right fa fa-angle-down" type=""></a></h4>
            <div id="bs-example-navbar-collapse-4" class="bottom-menu1 navbar-collapse collapse" role="navigation" aria-expanded="true" style="">
              <ol class="bottom-menu">
                <li><a href="{{url($locale.'/search?type=services&sr=&wr=')}}">{!!San_Help::sanLang('FIND PROFESSIONALS')!!}</a></li>
                <li><a href="{{route('business')}}">{!!San_Help::sanLang('GET LISTED')!!}</a></li>
                <li><a href="{{route('team')}}">{!!San_Help::sanLang('TEAM')!!}</a></li>
                <li><a href="{{route('career')}}">{!!San_Help::sanLang('CAREERS')!!}</a></li>
                <li><a href="{{route('terms-and-conditions')}}">{!!San_Help::sanLang('Terms & Conditions')!!}</a></li>
                <li><a href="{{route('privacy')}}">{!!San_Help::sanLang('PRIVACY')!!}</a></li>
                <li><a href="{{route('sitemap')}}">{!!San_Help::sanLang('SITEMAP')!!}</a></li>
              </ol>
            </div>
          </div><!--col-sm-4 close-->
          <div class="col-sm-3 box-foot wow fadeInLeft" data-wow-duration="500ms" data-wow-delay="100ms">
            <h4 class="foot-hd visible-xs">{!!San_Help::sanLang('Media Links')!!} <a data-target=".bottom-menu2" data-toggle="collapse" class="pull-right fa fa-angle-down" type=""></a></h4>
            <div id="bs-example-navbar-collapse-4" class="bottom-menu2 navbar-collapse collapse" role="navigation" aria-expanded="true" style="">
              <h3>{!!San_Help::sanLang('MEDIA')!!}</h3>
              <ol class="bottom-menu">
                <li><a href="{{route('blog')}}">{!!San_Help::sanLang('Mask Blog')!!}</a></li>
                <li><a href="{{route('videos')}}">{!!San_Help::sanLang('Videos')!!}</a></li>
                <h3 class="marg5">{!!San_Help::sanLang('TALK TO US')!!}</h3>
                <li><a href="mailto:info@mask.com">info@mask.com</a></li>
                <li><a href="{{route('help-center')}}">{!!San_Help::sanLang('MASK Help Center')!!}</a></li>
              </ol>
            </div>
          </div><!--col-sm-4 close-->
          <div class="col-sm-5 box-foot wow fadeInLeft" data-wow-duration="500ms" data-wow-delay="100ms">
            @php($about = \TCG\Voyager\Models\Page::where('slug', 'about-mask')->first())
            <h4 class="foot-hd visible-xs">@if($locale == 'en'){!!$about->title!!}@else{!!$about->excerpt!!}@endif<a data-target=".bottom-menu5" data-toggle="collapse" class="pull-right fa fa-angle-down" type=""></a></h4>
            <div id="bs-example-navbar-collapse-4" class="bottom-menu5 navbar-collapse collapse" role="navigation" aria-expanded="true" style="">
              <h3 class="hidden-xs">@if($locale == 'en'){!!$about->title!!}@else{!!$about->excerpt!!}@endif</h3>
              <p>@if($locale == 'en'){!!$about->body!!}@else{!!$about->body_ar!!}@endif</p>
            </div>
          </div>
        </div>
      </div>
    </div>
    </div>
    <div class="footer-bottom">
      <div class="container">
        <div class="row">
          <div class="col-sm-6 wow fadeInLeft">
            <p>{!!San_Help::sanLang('rights reserved')!!}</p>
          </div>
          <div class="col-sm-6">
        <div class="scl-box pull-right wow fadeInLeft">
          <ul class="list-inline social">
            <li><a href="#"><i class="fa fa-facebook-square"></i></a></li>
            <li><a href="#"><i class="fa fa-twitter-square"></i></a></li>
            <li><a href="#"><i class="fa fa-pinterest-square"></i></a></li>
            <li><a href="#"><i class="fa fa-instagram"></i></a></li>
          </ul>
        </div>
          </div>
        </div>
      </div>
    </div>
</footer>
@endif
