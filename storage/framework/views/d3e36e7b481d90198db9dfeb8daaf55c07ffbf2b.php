

<!-- MODAL-STARTS -->
<?php if(Auth::check()): ?>
<div id="shopping-cart" class="cart-box">
  <div class="col-sm-12 segment">
    <div class="row">
      <div class="col-sm-2">
        <button class="closs-btn" onclick="jQuery('.cart-box').removeClass('open-menu')">&times;</button>
      </div>
      <div class="col-sm-8 ui padded center aligned inverted segment sc-logo hidden-xs">
        <img class="ui fluid centered image" style="height:65px;" src="<?php echo e(San_Help::san_Asset('images/logo.png')); ?>" alt="cheaplolboost logo">
      </div>
    </div>
  </div>
  <form class="form-horizontal " action="<?php if(Auth::check()): ?><?php echo e(url($locale.'/postbook/'.Auth::user()->id.'?tab=cartsummary')); ?><?php endif; ?>" method="post" id="cart_form">
    <?php echo e(csrf_field()); ?>

    <div class="col-sm-12 pad-0 refreshdiv">
      <table id="product-table" class="ui single line celled unstackable small inverted table">
        <thead class="full-width">
          <tr>
            <th><span class="h3-body">Product</span></th>
            <th colspan="2"><span class="h3-body" style="font-size: 1em;">Price</span></th>
          </tr>
        </thead>
        <tbody id="append_cart_data">
          <?php if((isset($cart_data) && $cart_data->isEmpty()) || !isset($cart_data)): ?>
          <?php ($disable = 1); ?>
          <tr style="text-align: center; font-size: 1.1em;" id="no-items-in-cart">
            <td height="60">
              <div class="ui hidden divider"></div>
              <span style="font-style: italic;">Your Shopping Cart is Currently Empty...</span>
              <br>
              <a href="<?php echo e(url($locale.'/search?type=products&wr=&pr=')); ?>">Click here to browse our accounts</a>
              <div class="ui hidden divider"></div>
            </td>
            <td class="collapsing currency-target left aligned"></td>
            <td></td>
          </tr>
          <?php elseif(isset($cart_data)): ?>
          <?php ($total = 0); ?>
          <?php $__currentLoopData = $cart_data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cart): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <?php ($total = $total+$cart->total); ?>
          <?php ($disable = 0); ?>
          <tr>
            <td height="30">
              <h5 class="item-name"><?php echo e($cart->product->name); ?></h5>
            </td>
            <td height="30">
              <h4 class="ui header" style="color: white;">SAR <?php echo e($cart->total); ?></h4>
            </td>
            <td><a href="javascript:void(0);" data-id="<?php echo e($cart->id); ?>" class="ui icon button action-remove-from-cart">&times;</a></td>
          </tr>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          <?php endif; ?>
        </tbody>
        <tfoot class="full-width">
          <tr>
            <th class="">
              <h3 class="ui header">
                <span class="h3-body" style="font-size: 1em;">Total</span>
              </h3>
            </th>
            <th class="currency-target" data-entity="yes" data-usd="0" colspan="2">
              <h3 class="ui header total-price" ><b id="cart_total_amnt">SAR <?php if(isset($total)): ?><?php echo e($total); ?><?php else: ?> 0 <?php endif; ?></b>
              </h3>
            </th>
          </tr>
        </tfoot>
      </table>
    </div>
    <div class="col-sm-12 pad-0">
      <div id="cart-action-box" class="ui basic center aligned segment" style="background: none !important;">
        <div id="cart-checkout-box" class="two ui text-center stacking buttons mar-top-30">
          <button id="checkout" class="ui yellow button checkout_san_btn" <?php if(isset($disable) && $disable ==1): ?> disabled="disabled" <?php endif; ?> style="padding-top: 11px;padding-bottom: 11px;">
            Checkout <i class="fa fa-arrow-right"></i>
          </button>
        </div>
      </div>
    </div>
  </form>
</div>
<?php endif; ?>
<!-- BEGIN # MODAL LOGIN -->
<div class="modal fade" id="login-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
  <div class="modal-dialog modal-lg">
    <div class="modal-content col-sm-12 pad-xs-0">
      <div class="modal-header" align="center">
        <center>
          <h2 class="skyblue-txt bline-btm pad-btm-10"><?php echo San_Help::sanLang('Login'); ?></h2>
        </center>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span class="close-hairline"></span></button>
      </div>
      <!-- Begin # DIV Form -->
      <div id="div-forms">
        <!-- Begin # Login Form -->
        <form class="custom_signup_form" rel="" method="POST" role="form" action="<?php echo e(url($locale.'/login')); ?>" id="login-form" autocomplete="off">
          <?php echo e(csrf_field()); ?>

          <div class="modal-body">
            <div id="msg_block"></div>
            <div id="msg_block_success" class="success-msg"></div>
            <div class="form-group">
              <label class="skyblue-txt"><i class="fa fa-user"></i> <?php echo San_Help::sanLang('Email'); ?></label>
              <input id="login_username" class="form-control cst-control" type="text" name="email" placeholder="<?php echo San_Help::sanLang('Email'); ?> " required>
            </div>
            <div class="form-group">
              <label class="skyblue-txt"><i class="fa fa-lock"></i> <?php echo San_Help::sanLang('Password'); ?></label>
              <input id="login_password" class="form-control cst-control" type="password" name="password" placeholder="<?php echo San_Help::sanLang('Password'); ?>" required>
            </div>
            <div class="form-group">
              <div class="checkbox">
                <label class="skyblue-txt">
                  <input type="checkbox"> <?php echo San_Help::sanLang('Remember me'); ?>

                </label>
              </div>
            </div>
            <div class="form-groups">
              <a href="javascript:void(0)" id="login_btnn" class="btn btn-5 img-100"><span><?php echo San_Help::sanLang('Login'); ?></span></a>
            </div>
          </div>
          <div class="modal-footer">
            <div class="form-group text-center">
              <a href="javascript:void(0)" class="cd-signin lostform"><?php echo San_Help::sanLang('Forget'); ?> <?php echo San_Help::sanLang('Password'); ?></a><br>
              <span class=""><?php echo San_Help::sanLang('New User'); ?></span> <a href="javascript:void(0)" role="button" class="myloginpage1"><?php echo San_Help::sanLang('Register Now'); ?></a>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php if(!Auth::check()): ?>
<?php ($cntries = \Sandeep\Maskfront\Models\Country::all()); ?>
<!--  Register Model -->
<div id="provider_register" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close_signupModal close" data-dismiss="modal" aria-label="Close"><span class="close-hairline"></span></button>
        <h3 class="text-center text-uppercase"><?php echo San_Help::sanLang('Sign Up'); ?></h3>
      </div>
      <div class="modal-body">
        <div class="container-fluid">
          <div id="signup_msg_block"></div>
          <div id="signup_msg_success" class="success-msg"></div>
          <form class="pro_signup_form" id="pro_signup_form" rel="" autocomplete="off" method="POST" role="form" action="<?php echo e(route('provider_register')); ?>">
            <?php echo e(csrf_field()); ?>

            <input type="hidden" name="user_type" value="provider">
            <div class="form-group">
              <div class="input-container">
                <input id="username" type="text" class="form-control input-field" name="name" placeholder="<?php echo San_Help::sanLang('Username'); ?>">
                <i class="fa fa-user icon"></i>
              </div>
            </div>
            <div class="form-group">
              <div class="input-container">
                <input id="manager_name" type="text" class="form-control input-field" name="manager_name" placeholder="<?php echo San_Help::sanLang('Manager Name'); ?>">
                <i class="fa fa-user icon"></i>
              </div>
            </div>
            <div class="form-group">
              <div class="input-container">
                <select name="country" class="form-control input-field">
                  <?php $__currentLoopData = $cntries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <option value="<?php echo e($value->phonecode); ?>" <?php if($value->phonecode == '966'): ?> selected = "selected" <?php endif; ?>><?php echo e($value->name); ?>(<?php echo e($value->phonecode); ?>)</option>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <!-- <i class="fa fa-caret-down" aria-hidden="true"></i> -->
                <input id="contact_number" type="text" class="form-control input-field" name="phone" placeholder="<?php echo San_Help::sanLang('Contact Number with Country Code'); ?>">
                <i class="fa fa-phone icon"></i>
              </div>
            </div>
            <div class="form-group">
              <div class="input-container">
                <input id="s_email" type="text" class="form-control input-field" name="email" placeholder="<?php echo San_Help::sanLang('email'); ?>">
                <i class="fa fa-envelope icon"></i>
              </div>
            </div>
            <div class="form-group">
              <div class="input-container cst-selectt">
                <select name="_type_of_service" id="_type_of_service" class="form-control input-field">
                  <option value="">Type of Service</option>
                  ';
                  <?php $__currentLoopData = config('maskfront.dropdown_fixed'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <option value="<?php echo e($key); ?>"><?php echo e($value); ?></option>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
              </div>
            </div>
            <div class="form-group">
              <div class="input-container">
                <input id="latitude" type="hidden" class="form-control" name="latitude">
                <input id="longitude" type="hidden" class="form-control" name="longitude">
                <input id="city_country" type="hidden" class="form-control" name="city_country">
                <input id="street_address" type="text" class="form-control input-field" name="street_address" onclick="showMap()" placeholder="<?php echo San_Help::sanLang('fetch your street address'); ?>" autocomplete="off" value="">
                <i class="fa fa-map-marker icon"></i>
              </div>
              <div id="googleMap" class="map-box" style="display:none;width:100%;height:250px;"></div>
            </div>
            <div class="form-group">
              <div class="input-container">
                <input id="user_password" type="text" class="form-control input-field" name="password" placeholder="<?php echo San_Help::sanLang('Password'); ?>" autocomplete="off">
                <i class="fa fa-key icon"></i>
              </div>
            </div>
            <div class="form-group">
              <div class="input-container">
                <input id="user_conrfpassword" type="text" class="form-control input-field" name="user_conrfpassword" placeholder="<?php echo San_Help::sanLang('Confirm Password'); ?>" autocomplete="off">
                <i class="fa fa-key icon"></i>
              </div>
            </div>
            <div class="form-group">
              <div class="input-container cst-selectt">
                <select name="duration" id="_duration" class="form-control input-field">
                  <option value=""><?php echo San_Help::sanLang('Duration'); ?></option>
                  <option value="1_month">1 Month</option>
                  <option value="3_month">3 Month</option>
                  <option value="4_month">6 Month</option>
                  <option value="1_year">1 Year</option>
                </select>
              </div>
            </div>
            <div class="form-group">
              <ul class="plan-list list-inline">
                <li class="plan_box">
                  <input class="membership_plan" type="radio" name="membership" value="134" checked="checked">
                  <span class="aside">
                    <img src="https://mask-app.com/wp-content/uploads/2018/04/signup_icon04.png" class="plan_img"> <br>
                    Elite
                  </span>
                </li>
                <li class="plan_box">
                  <input class="membership_plan" type="radio" name="membership" value="135">
                  <span class="aside">
                    <img src="https://mask-app.com/wp-content/uploads/2018/04/signup_icon03.png" class="plan_img"> <br>
                    Elegance
                  </span>
                </li>
                <li class="plan_box">
                  <input class="membership_plan" type="radio" name="membership" value="136">
                  <span class="aside">
                    <img src="https://mask-app.com/wp-content/uploads/2018/04/signup_icon02.png" class="plan_img"> <br>
                    Standard
                  </span>
                </li>
                <li class="plan_box">
                  <input class="membership_plan" type="radio" name="membership" value="137">
                  <span class="aside">
                    <img src="https://mask-app.com/wp-content/uploads/2018/04/signup_icon01.png" class="plan_img"> <br>
                    Free
                  </span>
                </li>
              </ul>
            </div>
            <div class="form-group">
              <div class="forgetmenot custom-check" id="custom-check">
                <input name="terms_condition" type="checkbox" id="terms_condition" value="" />
                <label for="terms_condition"><span class="rem-text" id="_terms_label"><?php echo San_Help::sanLang('Terms and Conditions'); ?></span></label>
              </div>
            </div>
            <div class="form-group text-center gap-btm-35">
              <!-- onclick="submitRegister()" -->
              <a href="javascript:void(0)" id="submit_signup" class="btn yell-btn submt-btn"><?php echo San_Help::sanLang('Sign Up'); ?></a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<div id="customer_register" class="modal fade"  role="dialog" tabindex="-1" aria-labelledby="signupModal" aria-hidden="false">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close_signupModal close" data-dismiss="modal" aria-label="Close"><span class="close-hairline"></span></button>
        <h3 class="text-center text-uppercase memyet"><?php echo San_Help::sanLang('Not a member yet?'); ?></h3>
      </div>
      <div class="modal-body">
        <div id="reg_msg_block"></div>
        <div id="reg_msg_block_success" class="success-msg"></div>
        <form class="custom_signup_form" rel="" method="POST" role="form" action="<?php echo e(route('member_register')); ?>" id="customer_form" autocomplete="off">
          <?php echo e(csrf_field()); ?>

          <div class="form-group">
            <div class="input-container">
              <input id="c_username" type="text" class="form-control input-field" name="name" placeholder="<?php echo San_Help::sanLang('Full Name'); ?>">
              <i class="fa fa-user icon"></i>
            </div>
          </div>
          <div class="form-group">
            <div class="input-container">
              <input id="c_user_email" type="text" class="form-control input-field" name="email" placeholder="<?php echo San_Help::sanLang('Email'); ?>">
              <i class="fa fa-envelope icon"></i>
            </div>
          </div>
          <div class="form-group">
            <div class="input-container">
              <select name="country" id="c_country_code" class="form-control input-field">
                <?php $__currentLoopData = $cntries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($value->phonecode); ?>" <?php if($value->phonecode == '966'): ?> selected = "selected" <?php endif; ?>><?php echo e($value->name); ?>(<?php echo e($value->phonecode); ?>)</option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </select>
              <input id="c_contact_number" type="text" class="form-control input-field" name="phone" placeholder="<?php echo San_Help::sanLang('Contact Number with Country Code'); ?>">
              <i class="fa fa-phone icon"></i>
            </div>
          </div>
          <!-- <input type="hidden" value="'.get_site_url()}}" id="site_url"> -->
          <div class="form-group">
            <div class="input-container cst-selectt">
              <select name="gender" name="gender" id="gender_field">
                <option value="Male"><?php echo San_Help::sanLang('Male'); ?></option>
                <option value="Female"><?php echo San_Help::sanLang('Female'); ?></option>
              </select>
            </div>
          </div>
          <div class="form-group">
            <div class="input-container">
              <input id="c_user_password" type="text" class="form-control input-field" name="password" placeholder="<?php echo San_Help::sanLang('Password'); ?>">
              <i class="fa fa-key icon"></i>
            </div>
          </div>
          <div class="form-group">
            <div class="input-container">
              <input id="c_user_conrfpassword" type="text" class="form-control input-field" name="user_conrfpassword" placeholder="<?php echo San_Help::sanLang('Confirm Password'); ?>">
              <i class="fa fa-key icon"></i>
            </div>
          </div>
          <div class="form-group">
            <div class="forgetmenot custom-check" id="custom-check_cust">
              <input name="terms_condition" type="checkbox" id="c_terms_condition" value="1" />
              <label for="c_terms_condition" id="c_terms_label"><span class="rem-text"><?php echo San_Help::sanLang('Terms and Conditions'); ?></span></label>
            </div>
          </div>
          <div class="form-group text-center">
            <!-- onclick="submitCustRegister()" -->
            <a href="javascript:void(0)" id="submit_customer_signup" class="btn yell-btn submt-btn"><?php echo San_Help::sanLang('Sign Up'); ?></a>
          </div>
          <div class="modal-footer">
            <div class="col-sm-12 text-center">
              <p><?php echo San_Help::sanLang('Are you signed UP?'); ?> <a class="reg-link" id="_login_show" href="javascript:void(0)"><?php echo San_Help::sanLang('Login'); ?></a></p>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>
<!-- END # MODAL LOGIN -->
<div class="modal fade" id="lost-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content col-sm-12 pad-xs-0">
      <div class="modal-header" align="center">
        <h2>Lost Your Password</h2>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span class="close-hairline"></span>
        </button>
      </div>
      <!-- Begin # DIV Form -->
      <div id="div-forms">
        <!-- Begin | Lost Password Form -->
        <form id="lost-form" action="<?php echo e(url($locale.'/user_forgot_pass')); ?>" method="post">
          <?php echo e(csrf_field()); ?>

          <div class="modal-body">
            <div class="container-fluid">
              <div class="form-group">
                <input id="lost_email" class="form-control cst-control" name="email" type="text" placeholder="<?php echo San_Help::sanLang('Email'); ?>" required>
              </div>
              <div class="form-group">
                <button type="submit" class="btn btn-5"><?php echo San_Help::sanLang('Send'); ?></button>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <div class="last-btngroup text-center">
              <a class="cd-signin myloginpage" href="javascript:void(0)"><?php echo San_Help::sanLang('Log In'); ?></a>
              <a href="javascript:void(0)" role="button" class="myloginpage1"><?php echo San_Help::sanLang('Register'); ?></a>
            </div>
          </div>
        </form>
        <!-- End | Lost Password Form -->
      </div>
      <!-- End # DIV Form -->
    </div>
  </div>
</div>
<?php if($page =='user_account' || $page == 'dashboard'): ?>
<div id="leave_feedback" class="modal fade" role="dialog" aria-hidden="false">
  <div class="modal-dialog modal-md">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close_addgallary close" data-dismiss="modal" aria-label="Close"><span class="close-hairline"></span></button>
        <h3 class="text-center modl-head col-sm-12"><?php if($page == 'dashboard'): ?> Reply <?php else: ?><?php echo San_Help::sanLang('Leave Feedback'); ?><?php endif; ?></h3>
      </div>
      <div class="modal-body">
        <div class="container-fluid">
          <form method="post" action="#" id="new-review-formm" class="new-review-form" onsubmit="submitReview(this);return false;">
            <input name="rating" id="rating" type="hidden" value="<?php if(isset($user->reviews->rating)): ?><?php echo e($user->reviews->rating); ?><?php endif; ?>">
            <input name="record_id" id="record_id__" value="" type="hidden">
            <input name="rating_on" value="booking" id="rating_on_" type="hidden">
            <?php if($page =='user_account'): ?>
            <div class="form-group">
              <label class="spr-form-label" for="review[rating]"><?php echo San_Help::sanLang('Rating'); ?></label>
              <div class="spr-form-input spr-starrating " id="product_star_rating">
                <ul>
                  <?php for($i = 1; $i <= 5; $i ++): ?>
                  <?php ($selected = ""); ?>
                  <?php if(isset($user->reviews->rating) && $i <= $user->reviews->rating): ?>
                  <?php ($selected = "selected"); ?>
                  <?php endif; ?>
                  <li class='<?php echo e($selected); ?>' onClick="addRating(this);">&#9733;</li>
                  <?php endfor; ?>
                </ul>
              </div>
            </div>
            <?php else: ?>
            <input name="reply_on" value="" id="reply_on" type="hidden">
            <?php endif; ?>
            <div class="form-group">
              <?php if($page =='user_account'): ?>
              <label class="spr-form-label"><?php echo San_Help::sanLang('Body of Review'); ?><span class="spr-form-review-body-charactersremaining">(1500)</span></label>
              <?php endif; ?>
              <div class="spr-form-input">
                <textarea class="spr-form-input spr-form-input-textarea " id="review_body" name="review" rows="5" placeholder="<?php echo San_Help::sanLang('Write your comments here'); ?>"><?php if(isset($user->reviews->review)): ?><?php echo $user->reviews->review; ?><?php endif; ?></textarea>
              </div>
            </div>
            <div class="form-group">
              <input class="spr-button spr-button-primary button button-primary btn btn-primary" value="<?php if($page =='user_account'): ?>Submit Review <?php else: ?> Submit Reply <?php endif; ?>" type="submit">
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>
<?php if($page == 'dashboard'): ?>
<!-- MODAL-ENDS -->
<div id="update_banner_Modal" class="modal fade" role="dialog" aria-hidden="false">
  <div class="modal-dialog modal-md">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close_addgallary close" data-dismiss="modal" aria-label="Close"><span class="close-hairline"></span></button>
        <h3 class="text-center modl-head col-sm-12"><?php echo San_Help::sanLang('Update Banner Image'); ?></h3>
      </div>
      <div class="modal-body">
        <div class="container-fluid">
          <form class="form-horizontal custom_image_form" enctype="multipart/form-data" id="update_banner_form" rel="" method="POST" role="form" action="<?php echo e(route('upload_image')); ?>">
            <?php echo e(csrf_field()); ?>

            <div class="form-group">
              <input name="type" value="banner" type="hidden">
              <section class="cstm-upload">
                <label for="file" class="input input-file">
                  <div class="button"><input id="banner_img" onchange="this.parentNode.nextSibling.value = this.value" type="file" class="form-control" name="image"><?php echo San_Help::sanLang('Browse'); ?></div>
                  <input placeholder="Add Profile Image" readonly="" type="text">
                </label>
              </section>
            </div>
            <div class="form-group text-center">
              <button type="submit" class="btn yell-btn submt-btn" value="update_banner_img" name="update_banner_img"><?php echo San_Help::sanLang('Submit'); ?></button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<div id="update_image_Modal" class="modal fade" role="dialog" aria-hidden="false">
  <div class="modal-dialog modal-md">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close_addgallary close" data-dismiss="modal" aria-label="Close"><span class="close-hairline"></span></button>
        <h3 class="text-center modl-head col-sm-12"><?php echo San_Help::sanLang('Update Featured Image'); ?></h3>
      </div>
      <div class="modal-body">
        <div class="container-fluid">
          <form class="form-horizontal custom_image_form" enctype="multipart/form-data" id="update_image_form" rel="" method="POST" role="form" action="<?php echo e(route('upload_image')); ?>">
            <?php echo e(csrf_field()); ?>

            <div class="form-group">
              <input name="type" value="profile" type="hidden">
              <section class="cstm-upload">
                <label for="file" class="input input-file">
                  <div class="button"><input id="featured_img" onchange="this.parentNode.nextSibling.value = this.value" type="file" class="form-control" name="image"><?php echo San_Help::sanLang('Browse'); ?></div>
                  <input placeholder="Add Profile Image" readonly="" type="text">
                </label>
              </section>
            </div>
            <div class="form-group text-center">
              <button type="submit" class="btn yell-btn submt-btn" value="add_fet_img" name="add_fet_img"><?php echo San_Help::sanLang('Submit'); ?></button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<div id="update_gallary_images" class="modal fade" role="dialog" aria-hidden="false">
  <div class="modal-dialog modal-md">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close_addgallary close" data-dismiss="modal" aria-label="Close"><span class="close-hairline"></span></button>
        <h3 class="text-center modl-head col-sm-12"><?php echo San_Help::sanLang('Update Gallary Images'); ?></h3>
      </div>
      <div class="modal-body">
        <div class="container-fluid">
          <form class="form-horizontal custom_image_form" enctype="multipart/form-data" id="update_image_form" rel="" method="POST" role="form" action="<?php echo e(url($locale.'/upload_gallary/'.$provider->id.'/providers')); ?>">
            <?php echo e(csrf_field()); ?>

            <div class="form-group">
              <section class="cstm-upload">
                <label for="file" class="input input-file">
                  <div class="button"><input id="featured_img" multiple onchange="this.parentNode.nextSibling.value = this.value" type="file" class="form-control" name="providers_images[]"><?php echo San_Help::sanLang('Browse'); ?></div>
                  <input placeholder="Add Gallary Images" readonly="" type="text">
                </label>
              </section>
            </div>
            <div class="form-group text-center">
              <button type="submit" class="btn yell-btn submt-btn" value="add_fet_img" name="add_fet_img"><?php echo San_Help::sanLang('Submit'); ?></button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<!--UPDATE-PROFILE-MODAL-STARTS-->
<div id="update_des_Modal" class="modal fade" role="dialog"  aria-hidden="false">
  <div class="modal-dialog modal-md">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close_addgallary close" data-dismiss="modal" aria-label="Close"><span class="close-hairline"></span></button>
        <h3 class="text-center modl-head col-sm-12"><?php echo San_Help::sanLang('Wallet Balance'); ?></h3>
      </div>
      <div class="modal-body">
        <div class="container-fluid">
          <form class="form-horizontal form-gutter custom_signup_form" enctype="multipart/form-data" id="custom_signup_form" rel="" method="POST" role="form" action="">
            <input type="hidden" name="sallon_id" value="4607">
            <div class="form-group">
              <input name="name" id="name" value="<?php if(isset($provider->wallet)): ?><?php echo e($provider->wallet); ?><?php endif; ?>" placeholder="Enter Amount" class="form-control" type="text">
            </div>
            <div class="form-group text-center">
              <button type="submit" class="btn yell-btn submt-btn" value="update_description" name="update_description"><?php echo San_Help::sanLang('Withdraw'); ?></button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<!--UPDATE-PROFILE-MODAL-ENDS-->
<!--=========ADD-TEAM-MODAL-STARTS=========-->
<div id="addteam_Modal" class="modal fade" role="dialog" aria-hidden="false">
  <div class="modal-dialog modal-md">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close_addgallary close" data-dismiss="modal" aria-label="Close"><span class="close-hairline"></span></button>
        <h3 class="text-center modl-head text-uppercase col-sm-12" id="add_team_head"><?php echo San_Help::sanLang('Add Team Member'); ?></h3>
      </div>
      <div class="modal-body">
        <div class="container-fluid">
          <form class="addteam_form" id="addteam_form" rel="" autocomplete="off" method="POST" enctype="multipart/form-data" role="form" action="<?php echo e(route('addteam')); ?>">
            <?php echo e(csrf_field()); ?>

            <div class="form-group">
              <input type="text" name="ass_name" id="_sln_attendant_name" value="" class="form-control" placeholder="Assistant Name" required="required">
            </div>
            <div class="form-group">
              <!--label class="control-label">Select Service</label-->
              <div class="col-sm-12 pad-0 multi-opt">
                <select class="" multiple required="" data-show-subtext="true" data-live-search="true" id="san_servicess" name="service_ids[]">
                  <option value="">Select Services</option>
                  <?php if(isset($provider->getServices)): ?>
                  <?php $__currentLoopData = $provider->getServices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <option value="<?php echo e($service->id); ?>"><?php echo e($service->name); ?></option>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                  <?php endif; ?>
                </select>
              </div>
            </div>
            <div class="form-group">
              <section class="cstm-upload pic--upload">
                <label for="file" class="input input-file">
                  <div class="button"><input id="featured_img" onchange="this.parentNode.nextSibling.value = this.value" type="file" class="form-control" name="image">Browse</div>
                  <input placeholder="Add Profile Image" readonly="" type="text">
                </label>
              </section>
            </div>
            <div class="form-group text-center">
              <button type="submit" class="btn yell-btn submt-btn" value="add_fet_img" name="add_fet_img"><?php echo San_Help::sanLang('Submit'); ?></button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<!--=========ADD-TEAM-MODAL-ENDS=========-->
<!-- User Review -->
<?php $__currentLoopData = $provider->getBookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<?php ($user = \App\User::with('user_reviews')->find($booking->user_id)); ?>
<?php ($review_book_id = !$user->user_reviews->isEmpty() ? $user->user_reviews[0]->record_id : ''); ?>
<div id="see_user_review_<?php echo e($booking->id); ?>" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="classInfo" aria-hidden="true">
  <div class="modal-dialog" style="max-width:900px;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
          ×
        </button>
        <h4 class="modal-title" id="classModalLabel" style="text-align:center">
          User Review
        </h4>
      </div>
      <div class="modal-body" style="overflow-x: auto;">
        <table id="classTable" class="table table-bordered">
          <thead>
            <tr>
              <th>User</th>
              <th>Rating</th>
              <th>Review</th>
              <th>Review Date</th>
            </tr>
          </thead>
          <tbody>
            <?php if($review_book_id == $booking->id): ?>
            <tr>
              <td><?php echo e($user->name); ?></td>
              <td><?php echo e($user->user_reviews[0]->rating); ?></td>
              <td><?php echo e($user->user_reviews[0]->review); ?></td>
              <td><?php echo Carbon\Carbon::parse($user->user_reviews[0]->created_at)->format('d F Y'); ?></td>
            </tr>
            <?php else: ?>
            <tr>
              <td colspan="4" style="text-align:center">Reviews Not Exist</td>
            </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">
          Close
        </button>
      </div>
    </div>
  </div>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<!-- End -->
<?php endif; ?>
<!--=========ADD-REVENUE-MODAL-STARTS=========-->
<div id="revchart_Modal" class="modal fade" role="dialog" aria-hidden="false">
  <div class="modal-dialog modal-md">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close_addgallary close" data-dismiss="modal" aria-label="Close"><span class="close-hairline"></span></button>
        <h3 class="text-center modl-head col-sm-12"><?php echo San_Help::sanLang('Monthly Revenue Chart'); ?></h3>
      </div>
      <div class="modal-body">
        <div class="container-fluid">
          <form class="form-horizontal custom_revform" enctype="multipart/form-data" id="update_image_form" rel="" method="POST" role="form" action="">
            <div class="well rev-well">
              <div class="form-group">
                <div class="input-container cst-selectt">
                  <select name="_opt" id="_opt_type" class="form-control input-field">
                    <option value="">Select Option</option>
                    <option value="_month">Month</option>
                    <option value="_quater" selected="selected">Quater</option>
                    <option value="_year">Year</option>
                  </select>
                </div>
              </div>
              <div class="form-group active">
                <div class="input-container cst-selectt">
                  <select name="quater_opt" id="quater_opt" class="form-control input-field">
                    <option value="">Select Quater</option>
                    <option value="1" selected="selected">First</option>
                    <option value="2">Second</option>
                    <option value="3">Third</option>
                    <option value="4">Fourth</option>
                  </select>
                </div>
              </div>
              <div class="form-group text-center col-sm-3">
                <button type="submit" class="btn yell-btn submt-btn filter_graph" value="filter_graph" name="filter_graph">Filter</button>
              </div>
              <div id="graph-wrapper" class="col-sm-12 pad-0 text-center graph-sector">
                <img src="<?php echo e(San_Help::san_Asset('images/rev-chart.png')); ?>" class="img-responsive" alt="">
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<!--=========ADD-REVENUE-MODAL-ENDS=========-->
<?php if($page == 'dashboard'): ?>
<!--=========ADD-SERVICE-MODAL-STARTS=========-->
<div id="add_service_Modal" class="modal fade" role="dialog"  aria-hidden="false">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close_addgallary close" data-dismiss="modal" aria-label="Close"><span class="close-hairline"></span></button>
        <h3 class="text-center text-uppercase" id="add_services"><?php echo San_Help::sanLang('Add Services'); ?></h3>
        <h3 class="text-center text-uppercase" style="display: none;" id="edt_services"><?php echo San_Help::sanLang('Edit Services'); ?></h3>
      </div>
      <div class="modal-body">
        <div class="container-fluid">
          <form class="form-gutter custom_signup_form" enctype="multipart/form-data" id="servicee_form_add" rel="" method="POST" role="form" action="<?php echo e(route('add_service')); ?>">
            <?php echo e(csrf_field()); ?>

            <input type="hidden" id="service_url" value="<?php echo e(url('admin/get_services')); ?>">
            <div class="form-group">
              <div class="input-container cst-selectt">
                <input id="post_name" type="hidden" name="name">
                <select class="form-control selectpicker san_change_category" name="category_id" required="" data-show-subtext="true" data-live-search="true" onchange="show_services(this.value)" id="san_change_category">
                  <option data-subtext="">Select Category</option>
                  <?php $__currentLoopData = \TCG\Voyager\Models\Category::whereNull('parent_id')->where('type','category')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <option value="<?php echo e($category->id); ?>"><?php echo e($category->name); ?></option>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <i class="fa fa-cogs icon"></i>
              </div>
            </div>
            <div class="form-group">
              <div class="input-container">
                <select class="form-control selectpicker" required="" data-show-subtext="true" data-live-search="true" id="changed_services" name="parent_service">
                  <option value="">Select Service</option>
                  <?php $__currentLoopData = \TCG\Voyager\Models\Category::whereNotNull('parent_id')->where('type','category')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <option value="<?php echo e($category->id); ?>"><?php echo e($category->name); ?></option>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
              </div>
            </div>
            <div class="form-group">
              <div class="input-container">
                <input id="_sln_service_price" type="text" class="form-control input-field" name="price" required="required" placeholder="Price (SAR)">
                <i class="sr-simbol">SR</i>
              </div>
            </div>
            <div class="form-group">
              <div class="input-container cst-selectt">
                <select name="per_hour" id="_sln_service_unit" class="form-control input-field" required="required">
                  <option value="">Select Service Units</option>
                  ';
                  <option value="1">1</option>
                  <option value="2">2</option>
                  <option value="3">3</option>
                  <option value="4">4</option>
                  <option value="5">5</option>
                  <option value="6">6</option>
                  <option value="7">7</option>
                  <option value="8">8</option>
                  <option value="9">9</option>
                  <option value="10">10</option>
                  <option value="11">11</option>
                  <option value="12">12</option>
                  <option value="13">13</option>
                  <option value="14">14</option>
                  <option value="15">15</option>
                  <option value="16">16</option>
                  <option value="17">17</option>
                  <option value="18">18</option>
                  <option value="19">19</option>
                  <option value="20">20</option>
                </select>
              </div>
              <span class="">No. of services you can take in this duration <sup>*</sup></span>
            </div>
            <div class="form-group">
              <div class="input-container cst-selectt">
                <select name="duration" id="_sln_service_duration" class="form-control input-field" required="required">
                  <option value="">Service Duration</option>
                  ';
                  <option value="00:30">00:30</option>
                  <option value="01:00">01:00</option>
                  <option value="01:30">01:30</option>
                  <option value="02:00">02:00</option>
                  <option value="02:30">02:30</option>
                  <option value="03:00">03:00</option>
                  <option value="03:30">03:30</option>
                  <option value="04:00">04:00</option>
                  <option value="04:30">04:30</option>
                  <option value="05:00">05:00</option>
                  <option value="05:30">05:30</option>
                  <option value="06:00">06:00</option>
                  <option value="06:30">06:30</option>
                  <option value="07:00">07:00</option>
                  <option value="07:30">07:30</option>
                  <option value="08:00">08:00</option>
                  <option value="08:30">08:30</option>
                  <option value="09:00">09:00</option>
                  <option value="09:30">09:30</option>
                  <option value="10:00">10:00</option>
                  <option value="10:30">10:30</option>
                  <option value="11:00">11:00</option>
                  <option value="11:30">11:30</option>
                  <option value="12:00">12:00</option>
                  <option value="12:30">12:30</option>
                  <option value="13:00">13:00</option>
                  <option value="13:30">13:30</option>
                  <option value="14:00">14:00</option>
                  <option value="14:30">14:30</option>
                  <option value="15:00">15:00</option>
                  <option value="15:30">15:30</option>
                  <option value="16:00">16:00</option>
                  <option value="16:30">16:30</option>
                  <option value="17:00">17:00</option>
                  <option value="17:30">17:30</option>
                  <option value="18:00">18:00</option>
                  <option value="18:30">18:30</option>
                  <option value="19:00">19:00</option>
                  <option value="19:30">19:30</option>
                  <option value="20:00">20:00</option>
                  <option value="20:30">20:30</option>
                  <option value="21:00">21:00</option>
                  <option value="21:30">21:30</option>
                  <option value="22:00">22:00</option>
                  <option value="22:30">22:30</option>
                  <option value="23:00">23:00</option>
                  <option value="23:30">23:30</option>
                  <option value="24:00">24:00</option>
                </select>
              </div>
            </div>
            <div class="form-group">
              <div class="input-container">
                <textarea rows="2" class="form-control input-field" cols="20" name="description" id="post_excerpt" placeholder="Description"></textarea>
              </div>
            </div>
            <div class="form-group">
              <div class="input-container">
                <section class="cstm-upload">
                  <label for="file" class="input input-file">
                    <div class="button"><input id="service_image" type="file" class="form-control input-field" name="image" onchange="this.parentNode.nextSibling.value = this.value">Browse</div>
                    <input placeholder="include some files" class="input-field" readonly="" type="text">
                  </label>
                </section>
              </div>
            </div>
            <div class="form-group text-center">
              <button type="submit" class="btn yell-btn submt-btn" value="add_service" name="add_service" id="add_service_btn"><?php echo San_Help::sanLang('Add Service'); ?></button>
              <button type="submit" class="btn yell-btn submt-btn" value="add_service"  id="edit_service_btn" style="display: none;" name="add_service"><?php echo San_Help::sanLang('Edit Service'); ?></button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<!--=========ADD-SERVICE-MODAL-ENDS=========-->
<?php endif; ?>
<div id="add-newproduct" class="modal fade" role="dialog" aria-hidden="false">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close_addgallary close" data-dismiss="modal" aria-label="Close"><span class="close-hairline"></span></button>
        <h3 class="text-center modl-head col-sm-12" id="add_productss"><?php echo San_Help::sanLang('Add New Product'); ?></h3>
        <h3 class="text-center modl-head col-sm-12" id="edit_productss" style="display: none;"><?php echo San_Help::sanLang('Edit Product'); ?></h3>
      </div>
      <div class="modal-body">
        <div class="container-fluid add-form">
          <form class="form-horizontal custom_revform" enctype="multipart/form-data" id="product_form_edit_add" rel="" method="POST" role="form" action="<?php echo e(route('add_product')); ?>">
            <?php echo e(csrf_field()); ?>

            <div class="well rev-well">
              <div class="form-group">
                <input class="form-control" id="product_name" type="text" name="name" placeholder="Product Name">
              </div>
              <div class="form-group">
                <div class="input-container cst-selectt">
                  <select name="category" id="product_category" class="form-control input-field selectpicker">
                    <option value="">Select Categories</option>
                    <?php $__currentLoopData = \TCG\Voyager\Models\Category::whereNull('parent_id')->where('type','procategory')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($category->id); ?>"><?php echo e($category->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <input class="form-control" type="text" id="product_price" name="price" placeholder="Product Price">
              </div>
              <div class="form-group">
                <textarea class="form-control" rows="4" type="text" id="product_desc" name="description" placeholder="Product Description"></textarea>
              </div>
              <div class="form-group">
                <input class="form-control" type="text" id="colornames" name="color" placeholder="Enter Color Name Seperated By Comma">
              </div>
              <div class="form-group">
                <div class="input-container">
                  <section class="cstm-upload">
                    <label for="file" class="input input-file">
                      <div class="button"><input id="service_image" type="file" class="form-control input-field" name="image" onchange="this.parentNode.nextSibling.value = this.value"><?php echo San_Help::sanLang('Browse'); ?></div>
                      <input placeholder="include some files" class="input-field" readonly="" type="text">
                    </label>
                  </section>
                </div>
              </div>
              <div class="form-group quater_opt active">
                <div class="input-container cst-selectt">
                  <select name="active" class="form-control input-field">
                    <option value="">Status</option>
                    <option value="1">active</option>
                    <option value="0">Inactive</option>
                  </select>
                </div>
              </div>
              <div class="form-group quater_opt active">
                <input class="form-control" type="text" id="product_stck" name="qty" placeholder="Product Stock">
                <div class="form-group text-center col-sm-3">
                  <button type="submit" class="btn yell-btn submt-btn filter_graph" value="filter_graph" name="filter_graph">Save</button>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<div id="signup_success" class="modal fade" role="dialog" aria-hidden="false">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close_signupModal close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
        <h3 class="text-center text-uppercase">Sign Up</h3>
      </div>
      <div class="modal-body">
        <div id="signup_msg_block" style="display: none;"></div>
        <div id="signup_msg_success" class="msg_success">
          <h3>successfully registered</h3>
          We have successfully registered your account, our sales team contact you shortly.
        </div>
      </div>
    </div>
  </div>
</div>
<?php if(isset($_GET['uid'])): ?>
<div id="forgot_pwd" class="modal fade" role="dialog" aria-hidden="false">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4>Update password</h4>
        <button type="button" class="close_actModal close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
      </div>
      <div class="modal-body">
        <div id="update_msg_block"></div>
        <div id="update_msg_success"></div>
        <form class="form-horizontal custom_forgot_pass_form" rel="" autocomplete="off" method="POST" role="form" action="">
          <input type="hidden" id = "forget_user_id" value="<?php echo e($_GET['uid']); ?>">
          <div class="form-group">
            <input id="new_pass" type="password" class="form-control" name="new_pass" placeholder="New Password">
          </div>
          <div class="form-group">
            <input id="conf_new_pass" type="password" class="form-control" name="conf_new_pass" placeholder="Re-type New password">
          </div>
          <div class="form-group">
            <a href="javascript:void(0)" id="update_pass" class="btn btn-primary submt-btn">Update</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>
