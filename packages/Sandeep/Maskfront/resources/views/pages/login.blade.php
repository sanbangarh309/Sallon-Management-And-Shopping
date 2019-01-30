<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="">
  <meta name="author" content="">
  <title>Dashboard {{$page}}</title>
  <link href="{{ asset('sb-assets/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('sb-assets/css/animate.min.css') }}" rel="stylesheet"> 
  <link href="{{ asset('sb-assets/css/font-awesome.min.css') }}" rel="stylesheet">
  <link href="{{ asset('sb-assets/css/main.css') }}" rel="stylesheet">
  <link href="{{ asset('sb-assets/css/responsive.css') }}" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet"> 
  <!-- <link rel="shortcut icon" href="images/favicon.ico"> -->

</head>

<body>
     <section id="login_page">
	    <div class="container">
           <div class="row">
                <div class="col-md-12">
                @if(session()->has('err'))
                <h2 class="well abcd" style="text-align: center;"><div class="alert alert-danger">
                            <button type="button" style="background: transparent;float: right;" class="closes" data-dismiss="alert" onclick="jQuery('.abcd').css('display', 'none');">&times;</button>
                            Email Or Password Invalid! 
                        </div></h2>
				@endif
				@if(session()->has('success'))
				    <h2 class="well abcd" style="text-align: center;"><div class="alert alert-success">
                            <button type="button" style="background: transparent;float: right;" class="closes" data-dismiss="alert" onclick="jQuery('.abcd').css('display', 'none');">&times;</button>
                            Registration Successfully. Please Login 
                        </div></h2>
				@endif
				    <div class="main-content area"> 
						 <div class="panel panel-default">
							  <div class="panel-heading">
								<span class="glyphicon glyphicon-lock"></span> Login</div>
							  <div class="panel-body">
							{{ Form::open(array('action'=>'HomeController@dologin')) }}
							<div class="form-group">
								<div class="col-sm-12">
									<input type="email" class="form-control" id="inputEmail3" placeholder="Email" name="email" required>
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-12">
									<input type="password" class="form-control" id="inputPassword3" placeholder="Password" name="password" required>
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-12 text-center">
									<div class="checkbox">
										<label>
											<input type="checkbox"/>
											Remember me
										</label>
									</div>
								</div>
							</div>
							<div class="form-group last">
								<div class="col-sm-12 text-center">
									<button type="submit" class="btn btn-success btn-sm signing">
										Sign in</button>
										<!--  <button type="reset" class="btn btn-default btn-sm">
										Reset</button> -->
								</div>
							</div>
							{{ Form::close() }}
						</div>
						<div class="panel-footer">
							Not Registred? <a href="{{url('/fregister')}}">Register here</a></div>
					</div>
                </div>
			</div>
    </div>
</div>

	 </section>

<script type="text/javascript" src="{{ asset('sb-assets/js/jquery.js') }}"></script>
<script type="text/javascript" src="{{ asset('sb-assets/js/wow.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('sb-assets/js/main.js') }}"></script>
<script type="text/javascript" src="{{ asset('sb-assets/js/custom.js') }}"></script>
<script type="text/javascript" src="{{ asset('sb-assets/js/bootstrap.min.js') }}"></script>
</body>
</html>