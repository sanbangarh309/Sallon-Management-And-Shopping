@php($page = 'home')
@extends('maskFront::layouts.app')
@section('main-content')
<section id="featured-service">
  <div class="container">
    <div class="col-sm-12 featured-serv pad-xs-0">
      <div class="owl-carousel owl-theme">
        @foreach($blogs as $blog)
        <div class="item single-gallery">
          <div class="thumb">
            <a href="{{url($locale.'/blog/'.$blog->id)}}">
              <div class="well feature-well img-well" style="background:url({{url('files/'.$blog->image)}})">
                <img src="{{ San_Help::san_Asset('images/spa.png') }}" alt="" class="hexa-icon">
              </div>
            </a>
          </div>
        </div>
        @endforeach
      </div>
    </div>
  </div>
</section>
<section id="services-pro">
  <div class="container">
    <div class="col-sm-12 featured-serv pad-xs-0">
      <div class="row">
        @if(app('request')->segment(2) == 'shop')
        @if(!$prods->isEmpty())
        @foreach($prods as $prod)
        <div class="col-sm-4 wow fadeInleft" data-wow-duration="500ms" data-wow-delay="150ms">
          <div class="well img-well img-well" style="background:url({{url('files/'.$prod->image)}})">
            <div class="fig-overlay"></div>
            <div class="caption">
              <div class="caption-inner cin-2">
                <h4>{!!San_Help::sanGetLang($prod->name)!!}<span class="small">{!!San_Help::sanLimited($prod->description,50,route('product' ,$prod->id))!!}</span></h4>
                <div class="col-sm-12 pad-0">
                  <ul class="list-inline rating-list">
                    @for ($i = 1; $i <= 5; $i ++)
                    @php($selected = "")
                    @if (isset($prod->rating) && $i <= $prod->rating)
                    @php($selected = "checked")
                    @endif
                    <li><span class="fa fa-star {{$selected}}"></span></li>
                    @endfor
                  </ul>
                </div>
                <div class="col-sm-12 pad-0">
                  <a href="{{url($locale.'/product/'.$prod->id)}}" class="btn view-btn">View</a>
                </div>
              </div>
            </div>
          </div>
        </div>
        @endforeach
        @else
        <div class="col-sm-12 wow fadeInleft" data-wow-duration="500ms" data-wow-delay="150ms">
            <h2 style="text-align:center">We are loading products..</h2>
        </div>
        @endif
        @if(!$prods->isEmpty())
        <div class="home_read_more"><a href="{{url($locale.'/search?type=products&pr=&wr=')}}">See More</a></div>
        @endif
        @else
        @foreach($sallons as $sallon)
        <div class="col-sm-4 wow fadeInleft" data-wow-duration="500ms" data-wow-delay="150ms">
          <div class="well img-well img-well" style="background:url({{url('files/'.$sallon->avatar)}})">
            <div class="fig-overlay"></div>
            <div class="caption">
              <div class="caption-inner cin-2">
                <h4>{!!San_Help::sanGetLang($sallon->name)!!}<span class="small">{!!$sallon->description!!}</span></h4>
                <div class="col-sm-12 pad-0">
                  <ul class="list-inline rating-list">
                    @for ($i = 1; $i <= 5; $i ++)
                    @php($selected = "")
                    @if (!empty($sallon->reviews) && $i <= $sallon['avg_rating'])
                    @php($selected = "checked")
                    @endif
                    <li><span class="fa fa-star {{$selected}}"></span></li>
                    @endfor
                    <!-- <li><i class="fa fa-star"></i></li>
                    <li><i class="fa fa-star"></i></li>
                    <li><i class="fa fa-star"></i></li>
                    <li><i class="fa fa-star"></i></li>
                    <li><i class="fa fa-star"></i></li> -->
                  </ul>
                </div>
                <div class="col-sm-12 pad-0">
                  <a href="{{url($locale.'/booking/'.$sallon->id.'?tab=profile')}}" class="btn view-btn">View</a>
                </div>
              </div>
            </div>
          </div>
        </div>
        @endforeach
        @if(!$sallons->isEmpty())
        <div class="home_read_more"><a href="{{url($locale.'/search?type=services&sr=&wr=')}}">See More</a></div>
        @endif
        @endif
      </div>
    </div>
  </div>
</section>
<section id="app-section" style="background:rgba(0, 0, 0, 0.7) url({{url('files/'.$home->image)}})" class="myParallax" data-speed="0.5">
  {!!str_replace('%content%',San_Help::sanLang('Are you a professional stylist ?'),$home->body)!!}
</section>
<section id="feedbacks" class="hidden-xs">
  <div class="container">
    <div class="col-sm-12 pad-0">
      <div class="row">
        <div class="col-sm-offset-2 col-sm-8">
          <div class="carousel slide" data-ride="carousel" id="quote-carousel">
            <div class="carousel-inner">
              @foreach($reviews as $ky => $review)
              <div class="item @if($ky == 0) active @endif">
                <blockquote>
                  <div class="row">
                    <div class="col-sm-12 text-center">
                      <img class="img-circle" src="@if(\App\User::find($review->user_id)){{url('files/'.\App\User::find($review->user_id)->avatar)}}@else {{url('files/users/default.png')}} @endif" style="width: 100px;height:100px;">
                    </div>
                    <div class="col-sm-12 text-center">
                      <p>“{!!$review->review!!}”</p>
                      <small>@if(\App\User::find($review->user_id)){{ \App\User::find($review->user_id)->name }}@endif</small>
                    </div>
                  </div>
                </blockquote>
              </div>
              @endforeach
              <!-- Carousel Buttons Next/Prev -->
              <a data-slide="prev" href="#quote-carousel" class="left carousel-control"><i class="fa fa-chevron-circle-left"></i></a>
              <a data-slide="next" href="#quote-carousel" class="right carousel-control"><i class="fa fa-chevron-circle-right"></i></a>
            </div>
          </div>
        </div>
      </div>
    </section>
    @endsection
