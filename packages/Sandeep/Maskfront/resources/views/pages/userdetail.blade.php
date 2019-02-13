@php($page = 'user_account')
@extends('maskFront::layouts.app')
@section('main-content')
<link rel="stylesheet" href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" />
<link href="{{ San_Help::san_Asset('css/rating.css') }}" rel="stylesheet">
<input type="hidden" id="update_account" value="{{ url($locale.'/update_account') }}">
<input type="hidden" id="password_mismatch" value="{!!San_Help::sanLang('password mismatch')!!}">
<input type="hidden" id="reject_booking" value="{{ url($locale.'/rejectbooking') }}">
<div class="container" style="min-height:600px;">
  <div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">

      <article id="post-11" class="post-11 page type-page status-publish hentry">
        <header class="entry-header">
          <h1 class="entry-title">{!!San_Help::sanLang('My Account')!!}</h1>	</header><!-- .entry-header -->


          <div class="entry-content">
            <!-- algolplus -->
            <div id="sln-salon" class="sln-bootstrap">
              <div id="sln-salon-my-account">
                <div id="sln-salon-my-account-content"><!-- Nav tabs -->

                  <div class="welcome_user">{!!San_Help::sanLang('Welcome')!!} @if(isset($user->name)){{$user->name}}@endif!</div>

                  <ul class="nav nav-tabs" role="tablist">

                    <li class="col-xs-12 col-sm-3 col-md-3 active" role="presentation"><a href="#proupdate" aria-controls="proupdate" role="tab" data-toggle="tab">{!!San_Help::sanLang('Update Profile')!!}</a></li>

                    <li class="col-xs-12 col-sm-3 col-md-3" role="presentation"><a href="#new" aria-controls="new" role="tab" data-toggle="tab">{!!San_Help::sanLang('Next appointments')!!}</a></li>
                    <li class="col-xs-12 col-sm-3 col-md-3" role="presentation"><a href="#old" aria-controls="old" role="tab" data-toggle="tab">{!!San_Help::sanLang('Reservations history')!!}</a></li>
                    <li class="col-xs-12 col-sm-3 col-md-3" role="presentation"><a href="#order_history" aria-controls="old" role="tab" data-toggle="tab">{!!San_Help::sanLang('Order history')!!}</a></li>

                    <li class="col-xs-12 col-sm-3 col-md-3" role="presentation"><a href="#dmnd_hstry" aria-controls="dmnd_hstry" role="tab" data-toggle="tab">{!!San_Help::sanLang('Jewelries History')!!}</a></li>
                  </ul>

                  <!-- Tab panes -->
                  <div class="tab-content sln-salon-my-account-tab-content">

                    <div role="tabpanel" class="tab-pane sln-salon-my-account-tab-pane active" id="proupdate">
                      <div class="col-sm-12 pad-0 blk-gutter">

                        <div class="loader" style="display:none;"><img src="https://mask-app.com/wp-content/plugins/activity_managment/includes/img/loading_big_black.gif"></div>

                        <div class="personal_info_div">

                          <div class="_body">

                            <div id="_msg_block"></div>
                            <div id="_msg_success"></div>

                            <div class="row">
                              <div class="well form-well pad-0">
                                <form class="userprofile-form" method="POST" role="form" action="" enctype="multipart/form-data" id="form-uniq">
                                  <div class="col-sm-12 page-headd clearfix">
                                    <h1 class="text-uppercase text-center">{!!San_Help::sanLang('ACCOUNT DETAILS')!!}</h1>
                                  </div>
                                  <div class="col-sm-3 col-md-3">
                                    <div class="form-group">
                                      <div class="col-sm-12 pad-0 upload-img">
                                        <img id="_avatar_image" class="img-responsive img-circle" src="@if(isset($user->avatar) && $user->avatar !=''){{url('files/'.$user->avatar)}} @else {{ San_Help::san_Asset('images/member-1.jpg') }} @endif">
                                        <input name="avatar" class="file" id="img_avatar" type="file">
                                        <button class="upload-new" id="browse_avatar" type="button"><i class="fa fa-upload"></i></button>
                                      </div>
                                      <div class="upload-text col-sm-12 pad-0">
                                        <p><small>{!!San_Help::sanLang('choose Image')!!}</small></p>
                                      </div>
                                    </div>
                                  </div>
                                  <div class="col-md-8 col-md-offset-1 col-sm-9">
                                    <legend class="text-uppercase"><i class="fa fa-globe"></i>{!!San_Help::sanLang('Update Profile Information')!!}</legend>
                                    <div class="form-group">
                                      <label class="label-control">{!!San_Help::sanLang('First Name')!!}</label>
                                      <input id="user_fname" type="text" class="form-control" name="name" placeholder="{!!San_Help::sanLang('First Name')!!}" value="@if(isset($user->name)){{$user->name}}@endif">
                                    </div>
                                    <div class="form-group">
                                      <label class="label-control">{!!San_Help::sanLang('Last Name')!!}</label>
                                      <input id="user_lname" type="text" class="form-control" name="lname" placeholder="{!!San_Help::sanLang('Last Name')!!}" value="@if(isset($user->lname)){{$user->lname}}@endif">
                                    </div>
                                    <div class="form-group">
                                      <label class="label-control">{!!San_Help::sanLang('My e-mail')!!}</label>
                                      <input id="user_email" type="text" class="form-control" name="email" placeholder="{!!San_Help::sanLang('Email id')!!}" value="@if(isset($user->email)){{$user->email}}@endif">
                                    </div>
                                    <div class="form-group">
                                      <label class="label-control">{!!San_Help::sanLang('DOB')!!}</label>
                                      <input type="text" id="date_picker_date" name="date" class="form-control" value="@if(isset($user->dob)){{$user->dob}}@endif">
                                    </div>
                                    <div class="form-group">
                                      <label class="label-control">{!!San_Help::sanLang('Gender')!!}</label>
                                      <select id="_sln_gender_field" class="form-control" name="gender">
                                        <option @if(isset($user->gender) && $user->gender =='Male') selected="selected" @endif value="Male" >Male</option>
                                        <option @if(isset($user->gender) && $user->gender =='Female') selected="selected" @endif value="Female">Female</option>
                                      </select>
                                    </div>
                                    <div class="form-group">
                                      <label class="label-control">{!!San_Help::sanLang('contact no.')!!}</label>
                                      <input id="user_contact" type="text" class="form-control" name="phone" placeholder="{!!San_Help::sanLang('Contact Number with Country Code')!!}" value="@if(isset($user->phone)){{$user->phone}}@endif">
                                    </div>
                                    <div class="form-group gap-top-25">
                                      <legend class="text-uppercase"><i class="fa fa-lock"></i> {!!San_Help::sanLang('Update password')!!}:</legend>
                                    </div>
                                    <div class="form-group">
                                      <input class="form-control" type="password" id="user_password" name="password" placeholder="{!!San_Help::sanLang('Password')!!}" autocomplete="off">
                                    </div>
                                    <div class="form-group">
                                      <input class="form-control" type="password" placeholder="{!!San_Help::sanLang('Re-type New password')!!}" id="user_confpassword" name="confpassword" autocomplete="off">
                                    </div>
                                    <input type="hidden" value="{{Auth::user()->id}}" id="current_user_id">
                                    <div class="form-group gapt-top-20 text-left">
                                      <button name="save_personal_info" id="save_personal_info" class="btn yell-btn upate-btn text-uppercase">{!!San_Help::sanLang(' Update My Account')!!}</button>
                                    </div>
                                  </div><!--col-sm-6-->
                                </form>
                              </div><!--well form-well-->
                            </div><!--row-->
                          </div>
                        </div><!--personal_info_div-->
                      </div><!--col-sm-6-->
                    </div>
                    <div role="tabpanel" class="tab-pane sln-salon-my-account-tab-pane" id="dmnd_hstry">
                      <div class="col-sm-12 pad-0 blk-gutter">
                        <div class="rewards_info">
                          <div id="diamonds_history_wrapper" class="dataTables_wrapper">
                            <table id="diamonds_history" class="hover dataTable" width="100%" cellspacing="0" role="grid" aria-describedby="diamonds_history_info" style="width: 100%;">
                              <thead>
                                <tr role="row"><th class="sorting_asc" tabindex="0" aria-controls="diamonds_history" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Booking ID: activate to sort column descending" style="width: 0px;">{!!San_Help::sanLang('Booking ID')!!}</th><th class="sorting" tabindex="0" aria-controls="diamonds_history" rowspan="1" colspan="1" aria-label="Jewelries: activate to sort column ascending" style="width: 0px;">{!!San_Help::sanLang('Jewelries')!!}</th><th class="sorting" tabindex="0" aria-controls="diamonds_history" rowspan="1" colspan="1" aria-label="Credit/Debit: activate to sort column ascending" style="width: 0px;">{!!San_Help::sanLang('Credit/Debit')!!}</th><th class="sorting" tabindex="0" aria-controls="diamonds_history" rowspan="1" colspan="1" aria-label="Type: activate to sort column ascending" style="width: 0px;">{!!San_Help::sanLang('Type')!!}</th><th class="sorting" tabindex="0" aria-controls="diamonds_history" rowspan="1" colspan="1" aria-label="Date: activate to sort column ascending" style="width: 0px;">{!!San_Help::sanLang('Date')!!}</th></tr>
                              </thead>
                              <tbody>
                                <?php
                                $entry_type = array('en' => array(
                                  'new_register' => 'Jewelries Earned on signup',
                                  'new_booking' => 'Jewelries Earned on New booking',
                                  'review_added' => 'Jewelries Earned on Review Added',
                                  'booking_canceled' => 'Jewelries Reduced on booking cancellation',
                                  'booking_rejected' => 'Jewelries Reduced on booking Rejected by Provider',
                                  'redeem_points' => 'Jewelries Redeemed in booking',
                                ),
                                'ar' => array(
                                  'new_register' => 'المجوهرات المضافة عند التسجيل',
                                  'new_booking' => 'المجوهرات المضافة عند الحجز',
                                  'review_added' => '‎المجوهرات المضافة عند اضافة  التقييم',
                                  'booking_canceled' => 'المجوهرات المخصومة بسبب إلغاء الحجز',
                                  'booking_rejected' => '‎المجوهرات  المخصومة بسبب عدم قبول الحجز',
                                  'redeem_points' => 'Jewelries Redeemed in booking',
                                )
                              );
                              ?>
                              @if(isset($user->getRewards) && !empty($user->getRewards))
                              @foreach($user->getRewards as $reward)
                              <tr role="row" class="odd">
                                <td class="sorting_1">{{$reward->id}}</td>
                                <td>{{$reward->rewards}}</td>
                                <td>{{$reward->type}}</td>
                                <td>{{$entry_type[$locale][$reward->entry_type]}}</td>
                                <td>{{$reward->created_at}}</td>
                                @endforeach
                                @endif
                              </tbody>
                            </table></div>

                          </div><!--rewards_info-->
                        </div><!--col-sm-6-->
                      </div>
                      <div role="tabpanel" class="tab-pane sln-salon-my-account-tab-pane" id="new">
                        <p class="hint">{!!San_Help::sanLang("Here you have your next reservations with us, pay attention to the 'pending' reservations")!!}</p>
                        <div id="san_canceled_bookings" class="dataTables_wrapper no-footer">
                          @include('maskFront::includes.user_bookings')
                        </div>
                        <br>
                        <div class="row">
                          <div class="col-xs-10 col-sm-6 col-md-4">
                            <div class="sln-btn sln-btn--emphasis sln-btn--medium sln-btn--fullwidth">
                              <a href="{{url('search?type=services&cust_lat=&cust_long=&sr=&wr=')}}">{!!San_Help::sanLang('MAKE A NEW RESERVATION')!!}</a>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div role="tabpanel" class="tab-pane sln-salon-my-account-tab-pane" id="old">
                        <p class="hint">{!!San_Help::sanLang('Here you have your past reservations, you can submit a review or re-schedule an appointment')!!}</p>
                        <div id="sln-salon-my-account-history-content">
                          <table class="table">
                            <thead>
                              <tr>
                                <td>ID</td>
                                <td>{!!San_Help::sanLang('When')!!}</td>
                                <td>{!!San_Help::sanLang('Services')!!}</td>
                                <td>{!!San_Help::sanLang('Assistants')!!}</td>
                                <td>{!!San_Help::sanLang('Price')!!}</td>
                                <td>{!!San_Help::sanLang('Service Provider Name')!!}</td>
                                <td>{!!San_Help::sanLang('Status')!!}</td>
                                <td>{!!San_Help::sanLang('Action')!!}</td>
                              </tr>
                            </thead>
                            <tbody>

                              @if(isset($user->getBookings) && !empty($user->getBookings))
                              @foreach($user->getBookings as $booking)
                              @if($booking->status == 'Completed')
                              @php($assistants = \TCG\Voyager\Models\Assistant::whereIn('id',explode(',',$booking->assistent_ids))->pluck('name')->toArray())
                              @php($services = \TCG\Voyager\Models\Service::whereIn('id',explode(',',$booking->service_ids))->pluck('name')->toArray())
                              @php($provider = \TCG\Voyager\Models\Provider::find($booking->salon_id))
                              <tr>
                                <td data-th="ID">{{$booking->id}}</td>
                                <td data-th="When"><div>{{$booking->book_date}}</div><div>{{$booking->time}}</div></td>
                                <td data-th="Services">{{implode(',',$services)}}</td>
                                <td data-th="Assistants">{{implode(',',$assistants)}}</td>
                                <td data-th="Price"><nobr>{{$booking->price}}SAR</nobr></td>
                                <td data-th="Service Provider"><nobr><strong>{{$provider->name}}</strong></nobr></td>
                                <td data-th="Status">
                                  <div class="status">
                                    <nobr>
                                      <span class="glyphicon glyphicon-clock" aria-hidden="true"></span>
                                      <span class="glyphicon-class"><strong>{{$booking->status}}</strong></span>
                                    </nobr>
                                  </div>
                                  <div>
                                  </div>
                                </td>
                                <td data-th="Action" class="col-md-3">
                                  <div>
                                    <div class="col-xs-10 col-sm-6 col-md-12">
                                      <div class="sln-btn sln-btn--emphasis sln-btn--medium sln-btn--fullwidth danger">
                                        <a href="javascript:void(0);" data-id="{{$booking->id}}" class = "left_feedback_">{!!San_Help::sanLang('Leave a feedback')!!}</a>
                                      </div>
                                    </div>
                                    <div style="clear: both"></div>
                                    <!-- SECTION NEW END -->
                                  </div>
                                </td>
                              </tr>
                              @endif
                              @endforeach
                              @endif
                            </tbody>
                          </table>
                        </div>
                      </div>

                      <!-- Order History -->
                      <div role="tabpanel" class="tab-pane sln-salon-my-account-tab-pane" id="order_history">
                        <div id="sln-salon-my-account-history-content" class="order_history_tab">
                          @include('maskFront::includes.order_history')
                        </div>
                      </div>
                      <!-- *********** -->

                      <div id="ratingModal" class="modal fade" role="dialog" tabindex="-1">
                        <div class="modal-dialog">
                          <!-- Modal content-->
                          <div class="modal-content">
                            <div class="modal-header">
                              <button type="button" class="close" data-dismiss="modal">×</button>
                              <h4 class="modal-title"></h4>
                            </div>
                            <div class="modal-body">
                              <div id="step1">
                                <p>Hi @if(isset($user->name)){{$user->name}}@endif!</p>
                                <p>How was your expirience with us this time? (required)</p>
                                <p><textarea id="" placeholder="please, drop us some lines to understand if your expirience has been  in line  with your expectrations"></textarea></p>
                                <p>
                                </p><div class="rating" id="8200"></div>
                                <span>{!!San_Help::sanLang('Rate our service (required)')!!}</span>
                                <p></p>
                                <p>
                                  <button type="button" class="btn btn-primary" onclick="slnMyAccount.sendRate();">{!!San_Help::sanLang('Send your review')!!}</button>
                                  <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                </p>
                              </div>
                              <div id="step2">
                                <p>Thank you for your review. It will help us improving our services.</p>
                                <p>We hope to see you again at Test</p>
                              </div>
                            </div>
                            <div class="modal-footer"></div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

            </div><!-- .entry-content -->


          </article><!-- #post-## -->

        </main><!-- .site-main -->

        
      </div><!-- .content-area -->
    </div>
    @section('javascript')
    <script type="text/javascript">
    $(function(){
      $(".left_feedback_").on('click',function () {
        $('#new-review-formm #record_id__').val($(this).data('id'));
        $('#leave_feedback').modal('show');
      });
    });
    function showFeedback(id,type){
      $('#new-review-formm #record_id__').val(id);
      $('#new-review-formm #rating_on_').val(type);
      $('#leave_feedback').modal('show');
    }
  </script>
  @stop
  @push('scripts')
  <script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
  <script src="{{ San_Help::san_Asset('js/userdetail.js') }}"></script>
  @endpush
  @endsection
