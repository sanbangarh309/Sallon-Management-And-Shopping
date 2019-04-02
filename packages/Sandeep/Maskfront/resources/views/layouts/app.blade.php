<!DOCTYPE html>
<html lang="@if(isset($locale)){{$locale}}@else en @endif">
<head>
    @section('meta_tags')
        @include('maskFront::layouts.meta')
    @show
    <meta name="author" content="Sandeep Bangarh">
    <!-- <title>MASK {{ucwords(str_replace("_"," ",$page))}}</title> -->
    <title>Book Online with Mask | List Your Business Online In Riyadh | Mask</title>

    @section('style')
        @include('maskFront::layouts.style')
    @show
    @yield('custom_css')
 </head>
<body class="{{$page}} @if(isset($second_page)){{$second_page}}@endif">
    <div class="preloader">
        <i class="fa fa-circle-o-notch fa-spin"></i>
    </div>
    <div class="submit_catgry loading_">
		<i class="fa fa-spinner fa-spin"></i><span>Loading....</span>
   </div>
    @section('head')
        @include('maskFront::layouts.head')
    @show
    @yield('main-content')
    @section('footer')
        @include('maskFront::includes.footer')
    @show
    @section('bootstrap_models')
        @include('maskFront::includes.bootstrap_models')
    @show   
    @section('scripts')
        @include('maskFront::layouts.scripts')
    @show
    @if(session()->has('message'))
      <script type="text/javascript">
        swal("","{{ session()->get('message') }}", "{{ session()->get('alert-type') }}");
      </script>
      @php(session()->forget('message'))
      @php(session()->forget('alert-type'))
    @endif
    @yield('javascript')
</body>
</html>
