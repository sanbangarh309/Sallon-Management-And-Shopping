 @php($page = 'addteam')
@extends('maskFront::layouts.app')
@section('main-content')
<section id="profile-section">
		<div class="container">
			<div class="col-sm-12"> 
				<div class="row">
					<div class="col-sm-12 pad-0">
						<ul class="nav nav-tabs nav-justified profile-tabs">
						  <li class="active"><a data-toggle="tab" href="#profile-tb">Profile</a></li>
						  <li><a data-toggle="tab" href="#booking-tb">My Bookings</a></li>
						  <li><a data-toggle="tab" href="#gallery-tb">Gallery</a></li>
						  <li><a data-toggle="tab" href="#myservices-tb">My Services</a></li>
						  <li><a data-toggle="tab" href="#settings-tb">Settings</a></li>
						</ul>
						<div class="col-sm-12 pad-0">
							<div class="tab-content pro-tabcontent">
								<div id="profile-tb" class="tab-pane fade  in active">
									<div class="outer_div">
    <div class="col-sm-12 pad-xs-0 outer-gutterbx">

                <div class="add_team_div">
                
            <h3>Our Team</h3>
                        <a href="https://mask-app.com/dashboard/?ad_team=1">Add Team</a>
        </div>
        <div class="col-sm-12 pad-0">
            <div class="well tabs-well">
                <div class="col-sm-12">
                    
                    	

<form class="form-horizontal add_team_form" enctype="multipart/form-data" id="add_team_form" rel="" method="POST" role="form" action="">

	<div class="row">
		<input type="hidden" name="_service_sallon" id="_service_sallon" value="4607" class="form-control">
		<input type="hidden" name="_sln_user_id" id="_sln_user_id" value="831" class="form-control">

		<div class="col-sm-6 col-md-6">
			<div class="form-group">
				<input type="text" name="_sln_attendant_name" id="_sln_attendant_name" value="" class="form-control" placeholder="Employee Name" required="required">
			</div>
	    </div>
	    <div class="col-sm-6 col-md-6">
	    	<div class="form-group">
				<select name="_sln_attendant_services[]" id="_sln_attendant_services" multiple="multiple" class="form-control">
					<option value="">Specialized In</option>
									</select>
			</div>
	    </div>

	    <div class="col-sm-6 col-md-6">
			<div class="form-group">
				<input type="text" name="_sln_attendant_email" id="_sln_attendant_email" value="" class="form-control" placeholder="Email" required="required">
			</div>
	    </div>
	    <div class="col-sm-6 col-md-6">
			<div class="form-group">
				<input type="text" name="_sln_attendant_phone" id="_sln_attendant_phone" class="form-control" placeholder="Contact No.">
			</div>
	    </div>


	    <div class="sln-calendar--wrapper">
	        <div class="sln-checkbutton-group form-group">
	        	<label class="control-label">Availability</label>
	            <div class="sln-checkbutton">
	                <input type="checkbox" class="big-check-base big-check-onoff" name="_sln_attendant_availabilities[1][days][1]" id="_sln_attendant_availabilities___new___days_1" value="1">
	               <label for="_sln_attendant_availabilities___new___days_1">Sunday</label>
	            </div><!-- sln-checkbutton -->

	            <div class="sln-checkbutton">
	                <input type="checkbox" class="big-check-base big-check-onoff" name="_sln_attendant_availabilities[1][days][2]" id="_sln_attendant_availabilities___new___days_2" value="1">
	               	<label for="_sln_attendant_availabilities___new___days_2">Monday</label>
	            </div><!-- sln-checkbutton -->

	            <div class="sln-checkbutton">
	                <input type="checkbox" class="big-check-base big-check-onoff" name="_sln_attendant_availabilities[1][days][3]" id="_sln_attendant_availabilities___new___days_3" value="1">
	               	<label for="_sln_attendant_availabilities___new___days_3">Tuesday</label>
	            </div><!-- sln-checkbutton -->

	            <div class="sln-checkbutton">
	                        <input type="checkbox" class="big-check-base big-check-onoff" name="_sln_attendant_availabilities[1][days][4]" id="_sln_attendant_availabilities___new___days_4" value="1">
	               <label for="_sln_attendant_availabilities___new___days_4">Wednesday</label>
	            </div><!-- sln-checkbutton -->

	            <div class="sln-checkbutton">
	                        <input type="checkbox" class="big-check-base big-check-onoff" name="_sln_attendant_availabilities[1][days][5]" id="_sln_attendant_availabilities___new___days_5" value="1">
	               <label for="_sln_attendant_availabilities___new___days_5">Thursday</label>
	            </div><!-- sln-checkbutton -->
	        	
	        	<div class="sln-checkbutton">
	                <input type="checkbox" class="big-check-base big-check-onoff" name="_sln_attendant_availabilities[1][days][6]" id="_sln_attendant_availabilities___new___days_6" value="1">
	               <label for="_sln_attendant_availabilities___new___days_6">Friday</label>
	            </div><!-- sln-checkbutton -->

	            <div class="sln-checkbutton">
	                <input type="checkbox" class="big-check-base big-check-onoff" name="_sln_attendant_availabilities[1][days][7]" id="_sln_attendant_availabilities___new___days_7" value="1">
	               	<label for="_sln_attendant_availabilities___new___days_7">Saturday</label>
	            </div><!-- sln-checkbutton -->
	            <div class="clearfix"></div>

	    	</div><!-- sln-checkbutton-group -->
    	</div><!-- sln-calendar-wrapper -->

	    <div class="row" id="sln-salon--admin">
	        <div class="col-xs-12 col-md-12 sln-slider-wrapper">

	        	<div class="col-xs-12 col-md-6">
					
		            <h4 class="">First Shift</h4>
					<div class="form-group">
						<div class="sln-slider">
							<div class="sliders_step1 col col-slider">
								<div class="slider-range ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all"><div class="ui-slider-range ui-widget-header ui-corner-all" style="left: 37.5%; width: 16.6667%;"></div><span class="ui-slider-handle ui-state-default ui-corner-all" tabindex="0" style="left: 37.5%;"></span><span class="ui-slider-handle ui-state-default ui-corner-all" tabindex="0" style="left: 54.1667%;"></span></div>
							</div>
							<div class="col col-time">
								<span class="slider-time-from">9:00</span>
								to <span class="slider-time-to">13:00</span>
								<input type="text" name="_sln_attendant_availabilities[1][from][0]" id="" value="9:00" class="slider-time-input-from hidden">
								<input type="text" name="_sln_attendant_availabilities[1][to][0]" id="" value="13:00" class="slider-time-input-to hidden">
							</div>
							<div class="clearfix"></div>
						</div><!-- sln-slider -->
					</div>
	            </div>

	            <div class="col-xs-12 col-md-6">
		            <h4 class="">Second Shift</h4>
					<div class="form-group">
						<div class="sln-slider">
							<div class="sliders_step1 col col-slider">
								<div class="slider-range ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all"><div class="ui-slider-range ui-widget-header ui-corner-all" style="left: 58.3333%; width: 20.8333%;"></div><span class="ui-slider-handle ui-state-default ui-corner-all" tabindex="0" style="left: 58.3333%;"></span><span class="ui-slider-handle ui-state-default ui-corner-all" tabindex="0" style="left: 79.1667%;"></span></div>
							</div>
							<div class="col col-time">
								<span class="slider-time-from">14:00</span> to <span class="slider-time-to">19:00</span>
								<input type="text" name="_sln_attendant_availabilities[1][from][1]" id="" value="14:00" class="slider-time-input-from hidden">
								<input type="text" name="_sln_attendant_availabilities[1][to][1]" id="" value="19:00" class="slider-time-input-to hidden">
							</div>
							<div class="clearfix"></div>
						</div>
					</div>
	            </div><!-- sln-slider -->
	        </div><!-- sln-slider-wrapper -->

	    </div><!-- row -->


	    <div class="col-sm-6 col-md-6"> 
			<div class="form-group">
				<section class="cstm-upload">
					<label for="file" class="input input-file">
						<div class="button"><input type="file" class="form-control" name="_sln_attendant_image" id="" onchange="this.parentNode.nextSibling.value = this.value">Browse</div><input placeholder="Add Profile Image" readonly="" type="text">
					</label>
				</section>
			</div>
	    	
      	</div>


	    <div class="col-sm-6 col-md-6">
			<div class="form-group">
				<textarea rows="2" class="form-control" cols="40" name="excerpt" id="excerpt" placeholder="Description"></textarea>
			</div>
      	</div>

      	<div class="form-group">
	    	<button type="submit" class="btn yell-btn" value="add_team" name="add_team">Save</button>
      	</div>

	</div>

</form>


                </div>
            </div>
        </div>

    </div>
    
    <div id="update_des_Modal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-md">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close_addgallary close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <h3 class="text-center modl-head col-sm-12">Update Description</h3>
                </div>

                <div class="modal-body">

                    <form class="form-horizontal form-gutter custom_signup_form" enctype="multipart/form-data" id="custom_signup_form" rel="" method="POST" role="form" action="">
                        <input type="hidden" name="sallon_id" value="4607">
                        <div class="form-group">
                            <div id="wp-post-submission-content-wrap" class="wp-core-ui wp-editor-wrap tmce-active"><link rel="stylesheet" id="dashicons-css" href="https://mask-app.com/wp-includes/css/dashicons.min.css?ver=4.8.7" type="text/css" media="all">
<link rel="stylesheet" id="editor-buttons-css" href="https://mask-app.com/wp-includes/css/editor.min.css?ver=4.8.7" type="text/css" media="all">
<div id="wp-post-submission-content-editor-container" class="wp-editor-container"><div id="mceu_25" class="mce-tinymce mce-container mce-panel" hidefocus="1" tabindex="-1" role="application" style="visibility: hidden; border-width: 1px; width: 100%;"><div id="mceu_25-body" class="mce-container-body mce-stack-layout"><div id="mceu_26" class="mce-toolbar-grp mce-container mce-panel mce-stack-layout-item mce-first" hidefocus="1" tabindex="-1" role="group"><div id="mceu_26-body" class="mce-container-body mce-stack-layout"><div id="mceu_27" class="mce-container mce-toolbar mce-stack-layout-item mce-first" role="toolbar"><div id="mceu_27-body" class="mce-container-body mce-flow-layout"><div id="mceu_28" class="mce-container mce-flow-layout-item mce-first mce-last mce-btn-group" role="group"><div id="mceu_28-body"><div id="mceu_0" class="mce-widget mce-btn mce-menubtn mce-fixed-width mce-listbox mce-first mce-btn-has-text" tabindex="-1" aria-labelledby="mceu_0" role="button" aria-haspopup="true"><button id="mceu_0-open" role="presentation" type="button" tabindex="-1"><span class="mce-txt">Paragraph</span> <i class="mce-caret"></i></button></div><div id="mceu_1" class="mce-widget mce-btn" tabindex="-1" role="button" aria-label="Bold"><button role="presentation" type="button" tabindex="-1"><i class="mce-ico mce-i-bold"></i></button></div><div id="mceu_2" class="mce-widget mce-btn" tabindex="-1" role="button" aria-label="Italic"><button role="presentation" type="button" tabindex="-1"><i class="mce-ico mce-i-italic"></i></button></div><div id="mceu_3" class="mce-widget mce-btn" tabindex="-1" role="button" aria-label="Bulleted list"><button role="presentation" type="button" tabindex="-1"><i class="mce-ico mce-i-bullist"></i></button></div><div id="mceu_4" class="mce-widget mce-btn" tabindex="-1" role="button" aria-label="Numbered list"><button role="presentation" type="button" tabindex="-1"><i class="mce-ico mce-i-numlist"></i></button></div><div id="mceu_5" class="mce-widget mce-btn" tabindex="-1" role="button" aria-label="Blockquote"><button role="presentation" type="button" tabindex="-1"><i class="mce-ico mce-i-blockquote"></i></button></div><div id="mceu_6" class="mce-widget mce-btn" tabindex="-1" role="button" aria-label="Align left"><button role="presentation" type="button" tabindex="-1"><i class="mce-ico mce-i-alignleft"></i></button></div><div id="mceu_7" class="mce-widget mce-btn" tabindex="-1" role="button" aria-label="Align center"><button role="presentation" type="button" tabindex="-1"><i class="mce-ico mce-i-aligncenter"></i></button></div><div id="mceu_8" class="mce-widget mce-btn" tabindex="-1" role="button" aria-label="Align right"><button role="presentation" type="button" tabindex="-1"><i class="mce-ico mce-i-alignright"></i></button></div><div id="mceu_9" class="mce-widget mce-btn" tabindex="-1" role="button" aria-label="Insert/edit link"><button role="presentation" type="button" tabindex="-1"><i class="mce-ico mce-i-link"></i></button></div><div id="mceu_10" class="mce-widget mce-btn" tabindex="-1" role="button" aria-label="Remove link"><button role="presentation" type="button" tabindex="-1"><i class="mce-ico mce-i-unlink"></i></button></div><div id="mceu_11" class="mce-widget mce-btn" tabindex="-1" role="button" aria-label="Insert Read More tag"><button role="presentation" type="button" tabindex="-1"><i class="mce-ico mce-i-wp_more"></i></button></div><div id="mceu_12" class="mce-widget mce-btn" tabindex="-1" role="button" aria-label="Fullscreen"><button role="presentation" type="button" tabindex="-1"><i class="mce-ico mce-i-fullscreen"></i></button></div><div id="mceu_13" class="mce-widget mce-btn mce-last" tabindex="-1" role="button" aria-label="Toolbar Toggle"><button role="presentation" type="button" tabindex="-1"><i class="mce-ico mce-i-wp_adv"></i></button></div></div></div></div></div><div id="mceu_29" class="mce-container mce-toolbar mce-stack-layout-item mce-last" role="toolbar" style="display: none;"><div id="mceu_29-body" class="mce-container-body mce-flow-layout"><div id="mceu_30" class="mce-container mce-flow-layout-item mce-first mce-last mce-btn-group" role="group"><div id="mceu_30-body"><div id="mceu_14" class="mce-widget mce-btn mce-first" tabindex="-1" role="button" aria-label="Strikethrough"><button role="presentation" type="button" tabindex="-1"><i class="mce-ico mce-i-strikethrough"></i></button></div><div id="mceu_15" class="mce-widget mce-btn" tabindex="-1" role="button" aria-label="Horizontal line"><button role="presentation" type="button" tabindex="-1"><i class="mce-ico mce-i-hr"></i></button></div><div id="mceu_16" class="mce-widget mce-btn mce-colorbutton" role="button" tabindex="-1" aria-haspopup="true" aria-label="Text color"><button role="presentation" hidefocus="1" type="button" tabindex="-1"><i class="mce-ico mce-i-forecolor"></i><span id="mceu_16-preview" class="mce-preview"></span></button><button type="button" class="mce-open" hidefocus="1" tabindex="-1"> <i class="mce-caret"></i></button></div><div id="mceu_17" class="mce-widget mce-btn" tabindex="-1" role="button" aria-label="Paste as text"><button role="presentation" type="button" tabindex="-1"><i class="mce-ico mce-i-pastetext"></i></button></div><div id="mceu_18" class="mce-widget mce-btn" tabindex="-1" role="button" aria-label="Clear formatting"><button role="presentation" type="button" tabindex="-1"><i class="mce-ico mce-i-removeformat"></i></button></div><div id="mceu_19" class="mce-widget mce-btn" tabindex="-1" role="button" aria-label="Special character"><button role="presentation" type="button" tabindex="-1"><i class="mce-ico mce-i-charmap"></i></button></div><div id="mceu_20" class="mce-widget mce-btn" tabindex="-1" role="button" aria-label="Decrease indent"><button role="presentation" type="button" tabindex="-1"><i class="mce-ico mce-i-outdent"></i></button></div><div id="mceu_21" class="mce-widget mce-btn" tabindex="-1" role="button" aria-label="Increase indent"><button role="presentation" type="button" tabindex="-1"><i class="mce-ico mce-i-indent"></i></button></div><div id="mceu_22" class="mce-widget mce-btn mce-disabled" tabindex="-1" role="button" aria-label="Undo" aria-disabled="true"><button role="presentation" type="button" tabindex="-1"><i class="mce-ico mce-i-undo"></i></button></div><div id="mceu_23" class="mce-widget mce-btn mce-disabled" tabindex="-1" role="button" aria-label="Redo" aria-disabled="true"><button role="presentation" type="button" tabindex="-1"><i class="mce-ico mce-i-redo"></i></button></div><div id="mceu_24" class="mce-widget mce-btn mce-last" tabindex="-1" role="button" aria-label="Keyboard Shortcuts"><button role="presentation" type="button" tabindex="-1"><i class="mce-ico mce-i-wp_help"></i></button></div></div></div></div></div></div></div><div id="mceu_31" class="mce-edit-area mce-container mce-panel mce-stack-layout-item" hidefocus="1" tabindex="-1" role="group" style="border-width: 1px 0px 0px;"><iframe id="post-submission-content_ifr" allowtransparency="true" title="Rich Text Area. Press Alt-Shift-H for help." style="width: 100%; height: 100px; display: block;" frameborder="0"></iframe></div><div id="mceu_32" class="mce-statusbar mce-container mce-panel mce-stack-layout-item mce-last" hidefocus="1" tabindex="-1" role="group" style="border-width: 1px 0px 0px;"><div id="mceu_32-body" class="mce-container-body mce-flow-layout"><div id="mceu_33" class="mce-path mce-flow-layout-item mce-first"><div class="mce-path-item">&nbsp;</div></div><div id="mceu_34" class="mce-flow-layout-item mce-last mce-resizehandle"><i class="mce-ico mce-i-resize"></i></div></div></div></div></div><textarea class="wp-editor-area" rows="20" autocomplete="off" cols="40" name="post-submission-content" id="post-submission-content" style="display: none;" aria-hidden="true">Lorelei ipsum is dummy</textarea></div>
</div>

                        </div>
                        
                        <div class="form-group text-center">
                            <button type="submit" class="btn yell-btn submt-btn" value="update_description" name="update_description">Update</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
								</div>
								<div id="booking-tb" class="tab-pane fade">
									
								</div>
								<div id="gallery-tb" class="tab-pane fade">
									<h3 class="text-uppercase">Our Gallery</h3>
									<div class="col-sm-12 pad-0">
										<div class="well gallery-well">
											<ul class="list-inline hair-list">
												<li>
													<a href="#" class="hair-style">
														<div class="hair-image" style="background:url(../packages/Sandeep/Maskfront/resources/assets/images/gallery-1.jpg)"></div>
														<span class="close-btn" title="Remove this item">&times;</span>
													</a>
												</li>
												<li>
													<a href="#" class="hair-style">
														<div class="hair-image" style="background:url(../packages/Sandeep/Maskfront/resources/assets/images/gallery-2.jpg)"></div>
														<span class="close-btn" title="Remove this item">&times;</span>
													</a>
												</li>
												<li>
													<a href="#" class="hair-style">
														<div class="hair-image" style="background:url(../packages/Sandeep/Maskfront/resources/assets/images/gallery-3.jpg)"></div>
														<span class="close-btn" title="Remove this item">&times;</span>
													</a>
												</li>
												<li>
													<a href="#" class="hair-style">
														<div class="hair-image" style="background:url(../packages/Sandeep/Maskfront/resources/assets/images/gallery-4.jpg)"></div>
														<span class="close-btn" title="Remove this item">&times;</span>
													</a>
												</li>
												<li>
													<a href="#" class="hair-style">
														<div class="hair-image" style="background:url(../packages/Sandeep/Maskfront/resources/assets/images/gallery-5.jpg)"></div>
														<span class="close-btn" title="Remove this item">&times;</span>
													</a>
												</li>
												<li>
													<a href="#" class="hair-style">
														<div class="hair-image" style="background:url(../packages/Sandeep/Maskfront/resources/assets/images/gallery-1.jpg)"></div>
														<span class="close-btn" title="Remove this item">&times;</span>
													</a>
												</li>
												<li>
													<a href="#" class="hair-style">
														<div class="hair-image" style="background:url(../packages/Sandeep/Maskfront/resources/assets/images/gallery-2.jpg)"></div>
														<span class="close-btn" title="Remove this item">&times;</span>
													</a>
												</li>
												<li>
													<a href="#" class="hair-style">
														<div class="hair-image" style="background:url(../packages/Sandeep/Maskfront/resources/assets/images/gallery-3.jpg)"></div>
														<span class="close-btn" title="Remove this item">&times;</span>
													</a>
												</li>
												<li>
													<a href="#" class="hair-style">
														<div class="hair-image" style="background:url(../packages/Sandeep/Maskfront/resources/assets/images/gallery-4.jpg)"></div>
														<span class="close-btn" title="Remove this item">&times;</span>
													</a>
												</li>
												<li>
													<a href="#" class="hair-style">
														<div class="hair-image" style="background:url(../packages/Sandeep/Maskfront/resources/assets/images/gallery-5.jpg)"></div>
														<span class="close-btn" title="Remove this item">&times;</span>
													</a>
												</li>
												<li>
													<a href="#" class="hair-style">
														<div class="hair-image" style="background:url(../packages/Sandeep/Maskfront/resources/assets/images/gallery-1.jpg)"></div>
														<span class="close-btn" title="Remove this item">&times;</span>
													</a>
												</li>
												<li>
													<a href="#" class="hair-style">
														<div class="hair-image" style="background:url(../packages/Sandeep/Maskfront/resources/assets/images/gallery-2.jpg)"></div>
														<span class="close-btn" title="Remove this item">&times;</span>
													</a>
												</li>
												<li>
													<a href="#" class="hair-style">
														<div class="hair-image" style="background:url(../packages/Sandeep/Maskfront/resources/assets/images/gallery-3.jpg)"></div>
														<span class="close-btn" title="Remove this item">&times;</span>
													</a>
												</li>
												<li>
													<a href="#" class="hair-style">
														<div class="hair-image" style="background:url(../packages/Sandeep/Maskfront/resources/assets/images/gallery-4.jpg)"></div>
														<span class="close-btn" title="Remove this item">&times;</span>
													</a>
												</li>
												<li>
													<a href="#" class="hair-style">
														<div class="hair-image" style="background:url(../packages/Sandeep/Maskfront/resources/assets/images/gallery-5.jpg)"></div>
														<span class="close-btn" title="Remove this item">&times;</span>
													</a>
												</li>
												<li>
													<a href="#" class="hair-style">
														<div class="hair-image" style="background:url(../packages/Sandeep/Maskfront/resources/assets/images/gallery-1.jpg)"></div>
														<span class="close-btn" title="Remove this item">&times;</span>
													</a>
												</li>
											</ul>
										</div>
									</div>
								</div>
								<div id="protab-4" class="tab-pane fade">
									<ul class="list-inline rev-list">
										<li>
											<a href="#" class="list-group-item active">
												<div class="media col-md-2 col-xs-2 pad-0">
													<figure class="pull-left">
														<img class="media-object img-circle img-responsive"  src="images/user-img.jpg" alt="" >
													</figure>
												</div>
												<div class="col-md-10 col-xs-10">
													<p class="list-group-item-text"> You add a comment anywhere on a Company, this is a fast and easy way together input & proposed changes from your company.
													</p>
													<div class="col-sm-12">
														<div class="feed col-sm-6 pad-0 pull-left">
															<ul class="list-inline rating-list">
																<li><i class="fa fa-star"></i></li>
																<li><i class="fa fa-star"></i></li>
																<li><i class="fa fa-star"></i></li>
																<li><i class="fa fa-star"></i></li>
																<li><i class="fa fa-star"></i></li>
															</ul>
														</div>
														<div class="author-des col-sm-6 pad-0 text-right">
															<p>Zara Luise, 01 March 2018</p>
														</div>
													</div>
												</div>
												
											</a>
										</li>
										<li>
											<a href="#" class="list-group-item active">
												<div class="media col-md-2 col-xs-2 pad-0">
													<figure class="pull-left">
														<img class="media-object img-circle img-responsive"  src="images/user-img.jpg" alt="" >
													</figure>
												</div>
												<div class="col-md-10 col-xs-10">
													<p class="list-group-item-text"> You add a comment anywhere on a Company, this is a fast and easy way together input & proposed changes from your company.
													</p>
													<div class="col-sm-12">
														<div class="feed col-sm-6 pad-0 pull-left">
															<ul class="list-inline rating-list">
																<li><i class="fa fa-star"></i></li>
																<li><i class="fa fa-star"></i></li>
																<li><i class="fa fa-star"></i></li>
																<li><i class="fa fa-star"></i></li>
																<li><i class="fa fa-star"></i></li>
															</ul>
														</div>
														<div class="author-des col-sm-6 pad-0 text-right">
															<p>Zara Luise, 01 March 2018</p>
														</div>
													</div>
												</div>
												
											</a>
										</li>
										<li>
											<a href="#" class="list-group-item active">
												<div class="media col-md-2 pad-0 col-xs-2">
													<figure class="pull-left">
														<img class="media-object img-circle img-responsive"  src="images/user-img.jpg" alt="" >
													</figure>
												</div>
												<div class="col-md-10 col-xs-10">
													<p class="list-group-item-text"> You add a comment anywhere on a Company, this is a fast and easy way together input & proposed changes from your company.
													</p>
													<div class="col-sm-12">
														<div class="feed col-sm-6 pad-0 pull-left">
															<ul class="list-inline rating-list">
																<li><i class="fa fa-star"></i></li>
																<li><i class="fa fa-star"></i></li>
																<li><i class="fa fa-star"></i></li>
																<li><i class="fa fa-star"></i></li>
																<li><i class="fa fa-star"></i></li>
															</ul>
														</div>
														<div class="author-des col-sm-6 pad-0 text-right">
															<p>Zara Luise, 01 March 2018</p>
														</div>
													</div>
												</div>
												
											</a>
										</li>
										<li>
											<a href="#" class="list-group-item active">
												<div class="media col-md-2 pad-0 col-xs-2">
													<figure class="pull-left">
														<img class="media-object img-circle img-responsive"  src="images/user-img.jpg" alt="" >
													</figure>
												</div>
												<div class="col-md-10 col-xs-10">
													<p class="list-group-item-text"> You add a comment anywhere on a Company, this is a fast and easy way together input & proposed changes from your company.
													</p>
													<div class="col-sm-12">
														<div class="feed col-sm-6 pad-0 pull-left">
															<ul class="list-inline rating-list">
																<li><i class="fa fa-star"></i></li>
																<li><i class="fa fa-star"></i></li>
																<li><i class="fa fa-star"></i></li>
																<li><i class="fa fa-star"></i></li>
																<li><i class="fa fa-star"></i></li>
															</ul>
														</div>
														<div class="author-des col-sm-6 pad-0 text-right">
															<p>Zara Luise, 01 March 2018</p>
														</div>
													</div>
												</div>
												
											</a>
										</li>
										<li>
											<a href="#" class="list-group-item active">
												<div class="media col-md-2 pad-0 col-xs-2">
													<figure class="pull-left">
														<img class="media-object img-circle img-responsive"  src="images/user-img.jpg" alt="" >
													</figure>
												</div>
												<div class="col-md-10 col-xs-10">
													<p class="list-group-item-text"> You add a comment anywhere on a Company, this is a fast and easy way together input & proposed changes from your company.
													</p>
													<div class="col-sm-12">
														<div class="feed col-sm-6 pad-0 pull-left">
															<ul class="list-inline rating-list">
																<li><i class="fa fa-star"></i></li>
																<li><i class="fa fa-star"></i></li>
																<li><i class="fa fa-star"></i></li>
																<li><i class="fa fa-star"></i></li>
																<li><i class="fa fa-star"></i></li>
															</ul>
														</div>
														<div class="author-des col-sm-6 pad-0 text-right">
															<p>Zara Luise, 01 March 2018</p>
														</div>
													</div>
												</div>
											</a>
										</li>
									</ul>
								</div>
							</div>
						</div>
					</div>
					
					
				</div>
			</div>
		</div>
	</section>
@endsection