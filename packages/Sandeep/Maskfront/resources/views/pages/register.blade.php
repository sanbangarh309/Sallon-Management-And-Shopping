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
				<div class="col-lg-12 col-md-12">
				<div class="card">
				  <div class="card-body">
				     <h3>Register</h3>
					 {{ Form::open(array('action' =>'HomeController@doregister')) }}
					  <input type="hidden" name="role" value="CHAT_USER">
					  <input type="hidden" name="invited_by" value="@if(isset($_GET['inv'])) {{$_GET['inv']}} @endif">
					  <div class="form-group">
						<input type="text" class="form-control" required id="f_name" placeholder="Enter Full Name" name="name">
					  </div>
					<div class="form-group">
						<input type="email" class="form-control" required id="sanemail" placeholder="Enter Email Address" name="email">
					  </div>
					  <div class="form-group">
						<input type="password" class="form-control" required placeholder="Enter Password"  name="password">
					  </div>
					<div class="col-md-12 form-group">
					   <div class="custom-control custom-checkbox">
						 <input type="checkbox" required class="custom-control-input" id="customControlAutosizing">
						 <label class="custom-control-label" for="customControlAutosizing">I agree with all <a href="terms.html">Terms & Conditions</a></label>
					   </div>
					</div>
						 <button type="submit" id="reg_chat_user" class="btn btn-success btn-block register-btn">REGISTER</button>
							
					{{ Form::close() }}
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
<script>

$( "#reg_chat_user" ).click(function() {
	if(!jQuery('#sanemail').val()) {
	 alert('Enter Your Email'); return false; 
	}
	if (!ValidateEmail(jQuery('#sanemail').val())) {
            alert("Invalid email address."); return false;
    }
    if(!jQuery('#customControlAutosizing').prop('checked')) {
	 alert('Accept Term and condition'); return false; 
	}
});
function ValidateEmail(email) {
        var expr = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
        return expr.test(email);
    };
</script>
</body>
</html>