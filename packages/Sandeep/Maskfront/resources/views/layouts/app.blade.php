<!DOCTYPE html>
<html lang="@if(isset($locale)){{$locale}}@else en @endif">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Mask Provides Sallons Management and ecommerce facilities">
    <meta name="keywords" content="mask,sallon,hair products,massage,beauty">
    <meta name="Robots" content="none">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="author" content="Sandeep Bangarh">
    <title>MASK {{ucwords(str_replace("_"," ",$page))}}</title>
    @section('style')
        @include('maskFront::layouts.style')
    @show
    @yield('custom_css')
 </head>
<body class="{{$page}}">
    <div class="preloader">
        <i class="fa fa-circle-o-notch fa-spin"></i>
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
