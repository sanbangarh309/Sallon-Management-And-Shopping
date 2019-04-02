@php($page = 'dashboard')
@extends('maskFront::layouts.app')
@section('main-content')
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css" />
<link rel="stylesheet" href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" />
<style type="text/css">
table.dataTable tbody tr {
  background : transparent;
}
table.dataTable.stripe tbody tr.odd, table.dataTable.display tbody tr.odd {
  background : transparent;
}
#profile-tb button.dt-button {
  /*float: right;*/
  color: #b4a895;
  font-size: 16px;
  border: solid 1px #b4a895;
  padding: 5px 12px;
  margin-top: 8px;
  width: 140px;
  text-align: center;
  background: transparent;
}
#myservices-tb button.dt-button {
  /*float: right;*/
  color: #c38d40;
  font-size: 16px;
  border: solid 1px #c38d40;
  background: #000;
  padding: 5px 12px;
  margin-top: 8px;
  width: 140px;
  text-align: center;
}
</style>
<section id="profile-section">
  <input type="hidden" id="accept_booking" value="{{ url($locale.'/acceptbooking') }}">
  <input type="hidden" id="reject_booking" value="{{ url($locale.'/rejectbooking') }}">
  <div class="container">
    <div class="col-sm-12">
      <div class="row">
        <div class="col-sm-12 pad-0">
          <ul class="nav nav-tabs nav-justified profile-tabs">
            <li @if(!isset($_GET['tab'])) class="active" @endif><a data-toggle="tab" href="#profile-tb">{!!San_Help::sanLang('Profile')!!}</a></li>
            <li><a data-toggle="tab" href="#booking-tb">{!!San_Help::sanLang('My Bookings')!!}</a></li>
            <li @if(isset($_GET['tab']) && $_GET['tab'] =='product') class="active" @endif><a data-toggle="tab" href="#products-tb">{!!San_Help::sanLang('Products')!!}</a></li>
            <li @if(isset($_GET['tab']) && $_GET['tab'] =='order_history') class="active" @endif><a data-toggle="tab" href="#orders-tb">{!!San_Help::sanLang('Order history')!!}</a></li>
            <li @if(isset($_GET['tab']) && $_GET['tab'] =='gallary') class="active" @endif><a data-toggle="tab" href="#gallery-tb">{!!San_Help::sanLang('Gallery')!!}</a></li>
            <li @if(isset($_GET['tab']) && $_GET['tab'] =='service') class="active" @endif><a data-toggle="tab" href="#myservices-tb">{!!San_Help::sanLang('My Services')!!}</a></li>
            <li @if(isset($_GET['tab']) && $_GET['tab'] =='setting') class="active" @endif><a data-toggle="tab" href="#settings-tb">{!!San_Help::sanLang('Settings')!!}</a></li>
          </ul>
          <div class="col-sm-12 pad-0">
            <div class="tab-content pro-tabcontent">
              <div id="profile-tb" class="tab-pane fade @if(!isset($_GET['tab'])) in active @endif">
                <div class="outer_div">
                  <div class="col-sm-12 pad-xs-0 outer-gutterbx">
                    <div class="col-sm-12 pad-0 profile-edittr">
						  <div class="col-sm-3 pad-0">
							<h3>@if(isset($provider->name)){{$provider->name}}@endif<span class="aside"></span></h3>
							<p>@if(isset($provider->description)){!!$provider->description!!}@endif</p>
						  </div>
						  <div class="col-sm-9 add_team_div flex_box pad-0 text-right">
							<h5>Wallet Amount<span class="aside">{{Auth::user()->rewardpoint_balance}}SAR</span></h5>
							<a href="#" type="button" class="btn" data-toggle="modal" style="width: 153px;" data-target="#update_des_Modal">{!!San_Help::sanLang('Withdraw Wallet')!!}</a>
						  </div>
                    </div>
                    <div class="add_team_div">
                      <h3>{!!San_Help::sanLang('Our Team')!!}</h3>
                      <input type="hidden" id="check_for_services" value="@if(!$provider->getServices->isEmpty())1 @endif">
                      <a href="javascript:void(0)" type="button" class="btn edit_assistant">{!!San_Help::sanLang('Add Team')!!}</a>
                    </div>
                    @if(isset($provider->getAssistants))
                    <div class="col-sm-12 pad-xs-0 products-gutterbx">
                      <div class="table-responsives col-xs-12 pad-0">
						<div class="col-md-12 team-members list-servicesbox">
							<div class="col-md-8 content" id="content-7">
								<ol class="list-inline verticle-list">
									@if(!$provider->getAssistants->isEmpty())
									@foreach($provider->getAssistants as $assistant)
									<li>
										<span class="product-fig" style="background:url(@if(isset($assistant->image) && $assistant->image !=''){{url('files/'.$assistant->image)}} @else {{ San_Help::san_Asset('images/user-img.jpg') }} @endif)"></span>
										  <div class="serv-info">
											  @if(isset($assistant->name)){{$assistant->name}}@endif
										  </div>
										  <div class="right_buttons">
											  <a href="javascript:void(0)" class="edit-item edit_assistant" data-id="{{$assistant->id}}" data-sids="{{json_encode(unserialize($assistant->service_ids))}}" data-name="{{$assistant->name}}" title="Edit Assistant">Edit</a>
										  </div>
									</li>
									@endforeach
									@else
										<li><div class="no-data">No Team Members Added Yet ,<a href="javascript:void(0)" type="button" class="btn edit_assistant">Click Here</a> To Add Team Member</div><li>
									@endif 
								</ol>
							</div>
						</div>
                   
                      </div>
                    </div>
                    @else
                    <div class="col-sm-12 pad-0">
                      <div class="well tabs-well">
                        <div class="col-sm-12">
                          <span class="no_data">{!!San_Help::sanLang('No Team Member')!!}</span>
                        </div>
                      </div>
                    </div>
                    @endif

                  </div>
                </div>
              </div>
              <div id="booking-tb" class="tab-pane fade">
                <div class="tab-inner2">
                  <div id="bs-example-navbar-collapse-7" class="bottom-menu7  pad-0 navbar-collapse collapse" role="navigation" aria-expanded="true" style="">
                    <ul class="nav nav-tabs pull-left catts-tableft nav-justified catt-tab catts-tab">
                      <li class="active"><a data-toggle="tab" href="#catts-1">{!!San_Help::sanLang('New Bookings')!!}</a></li>
                      <li><a data-toggle="tab" href="#catts-2">{!!San_Help::sanLang('Confirmed Bookings')!!}</a></li>
                      <li class=""><a data-toggle="tab" href="#catts-3">{!!San_Help::sanLang('Completed Bookings')!!}</a></li>
                    </ul>
                    <ul class="nav nav-tabs pull-right catts-tab catts-tabright">
                      <li><a href="#" type="button" class="btn rev-btn" data-toggle="modal" data-target="#revchart_Modal"><i class="fa fa-line-chart"></i></a></li>
                      <li><a data-toggle="tab" href="#catts-5"><i class="fa fa-dollar"></i> {!!San_Help::sanLang('My Revenue')!!}</a></li>
                    </ul>
                  </div>
                  <div class="col-sm-12 pad-0 booking-content">
                    <div class="tab-content tab-content2">
                      <div id="catts-1" class="tab-pane fade active in">
                        <div id="pending_bookings_wrapper" class="dataTables_wrapper no-footer">
                          @include('maskFront::includes.booking_list', ['status' => "Pending"])
                        </div>
                      </div>
                      <!-- INNTER_TAB_CONTENT_END -->
                      <div id="catts-2" class="tab-pane fade">
                        <div id="confirmed_bookings_wrapper" class="dataTables_wrapper no-footer">
                          @include('maskFront::includes.booking_list', ['status' => "Confirmed"])
                        </div>
                      </div>
                      <!-- INNTER_TAB_CONTENT_END -->
                      <div id="catts-3" class="tab-pane fade">
                        <div id="completed_bookings_wrapper" class="dataTables_wrapper no-footer">
                          @include('maskFront::includes.booking_list', ['status' => "Completed"])
                        </div>
                      </div>
                      <!-- INNTER_TAB_CONTENT_END -->
                      <div id="catts-5" class="tab-pane fade">
                        <div class="col-sm-12">
                          <div id="earn_booking_reports_wrapper" class="dataTables_wrapper no-footer">
                            <div class="dataTables_length" id="earn_booking_reports_length">
                              <label>
                                Show
                                <select name="earn_booking_reports_length" aria-controls="earn_booking_reports" class="">
                                  <option value="10">10</option>
                                  <option value="25">25</option>
                                  <option value="50">50</option>
                                  <option value="100">100</option>
                                </select>
                                entries
                              </label>
                            </div>
                            <div id="earn_booking_reports_filter" class="dataTables_filter"><label>Search:<input type="search" class="" placeholder="" aria-controls="earn_booking_reports"></label></div>
                            <table id="earn_booking_reports" class="display dataTable no-footer earn_booking_reports" role="grid" aria-describedby="earn_booking_reports_info" style="width: 100%;" width="100%">
                              <thead>
                                <tr role="row">
                                  <th class="sorting_asc" tabindex="0" aria-controls="earn_booking_reports" rowspan="1" colspan="1" style="width: 0px;" aria-sort="ascending" aria-label="Date: activate to sort column descending">Date</th>
                                  <th class="sorting" tabindex="0" aria-controls="earn_booking_reports" rowspan="1" colspan="1" style="width: 0px;" aria-label="Name: activate to sort column ascending">Name</th>
                                  <th class="sorting" tabindex="0" aria-controls="earn_booking_reports" rowspan="1" colspan="1" style="width: 0px;" aria-label="Service: activate to sort column ascending">Service</th>
                                  <th class="sorting" tabindex="0" aria-controls="earn_booking_reports" rowspan="1" colspan="1" style="width: 0px;" aria-label="Assistant: activate to sort column ascending">Assistant</th>
                                  <th class="sorting" tabindex="0" aria-controls="earn_booking_reports" rowspan="1" colspan="1" style="width: 0px;" aria-label="Total Pay: activate to sort column ascending">Total Pay</th>
                                  <th class="sorting" tabindex="0" aria-controls="earn_booking_reports" rowspan="1" colspan="1" style="width: 0px;" aria-label="Commission: activate to sort column ascending">Commission</th>
                                  <th class="sorting" tabindex="0" aria-controls="earn_booking_reports" rowspan="1" colspan="1" style="width: 0px;" aria-label="Earned: activate to sort column ascending">Earned</th>
                                </tr>
                              </thead>
                              <tbody>
                                <tr class="odd">
                                  <td colspan="7" class="dataTables_empty" valign="top">No data available in table</td>
                                </tr>
                              </tbody>
                            </table>
                            <div class="col-sm-12 foot-label">
                              <div class="row">
                                <div class="col-sm-6">
                                  <div class="dataTables_info" id="earn_booking_reports_info" role="status" aria-live="polite">Showing 0 to 0 of 0 entries</div>
                                </div>
                                <div class="col-sm-6 right-navigator">
                                  <div class="dataTables_paginate paging_simple_numbers" id="earn_booking_reports_paginate"><a class="paginate_button previous disabled" aria-controls="earn_booking_reports" data-dt-idx="0" tabindex="0" id="earn_booking_reports_previous">Previous</a><span></span><a class="paginate_button next disabled" aria-controls="earn_booking_reports" data-dt-idx="1" tabindex="0" id="earn_booking_reports_next">Next</a></div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <!-- INNTER_TAB_CONTENT_END -->
                    </div>
                    <!--tab-content tab-content2-->
                  </div>
                  <!--col-sm-12 pad-0-->
                </div>


              </div>
              <div id="products-tb" class="tab-pane fade @if(isset($_GET['tab']) && $_GET['tab'] =='product') in active @endif">
                <div class="well filter-well">
                  <div class="add_team_div-2 col-sm-12 gap-btm-10">
                    <h3 class="text-uppercase">Products</h3>
                    <a href="#" type="button" class="btn edit_products">Add Product</a>
                  </div>
                  <!-- <div class="col-sm-5">
                  <div id="completed_bookings_filter" class="dataTables_filter">
                  <label>Search:<input type="search" class="" placeholder="" aria-controls="completed_bookings"></label>
                </div>
              </div> -->
            </div><!--- FILETR-ENDS-HERE--->
            <div class="col-sm-12 pad-xs-0 products-gutterbx">
              <div class="table-responsive">
                <table class="product-table" id="product-table">
                  <thead>
                    <tr>
                      <th class="item-image">Image</th>
                      <th class="item-name">Name</th>
                      <th class="item-category">Category</th>
                      <th class="item-info">Description</th>
                      <th class="item-price">Price</th>
                      <th class="item-color">Color</th>
                      <th class="item-delete">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    @if(isset($provider->getProducts) && !$provider->getProducts->isEmpty())
                    @foreach($provider->getProducts as $product)
                    @php($cat = \TCG\Voyager\Models\Category::find($product->category_id))
                    <tr>
                      <td><div class="product-fig" style="background:url(@if(isset($product->image) && $product->image !=''){{url('files/'.$product->image)}} @else {{ San_Help::san_Asset('images/user-img.jpg') }} @endif)"></div></td>
                      <td>{{$product->name}}</td>
                      <td>@if(isset($cat->name)){!!San_Help::sanGetLang($cat->name,$locale)!!}@endif</td>
                      <td>{{$product->description}}</td>
                      <td>${{$product->price}}</td>
                      <td>{{$product->color}}</td>
                      <!-- <td>
                      <div class="filter filter2">
                      <select name="wcpt_filter_pa_color" aria-label="Color">
                      <option @if(isset($product->active) && $product->active ==1) selected="selected" @endif value="1">Active</option>
                      <option @if(isset($product->active) && $product->active ==0) selected="selected" @endif value="0">Inactive</option>
                    </select>
                    <span class="arow">
                    <svg viewBox="0 0 18 18" role="presentation" aria-hidden="true" focusable="false" style="height: 16px; width: 16px; display: block; fill: rgba(187,171,148,0.4);"><path d="m16.29 4.3a1 1 0 1 1 1.41 1.42l-8 8a1 1 0 0 1 -1.41 0l-8-8a1 1 0 1 1 1.41-1.42l7.29 7.29z" fill-rule="evenodd"></path></svg>
                  </span>
                </div>
              </td> -->
              <td><a href="javascript:void(0)" data-id="{{$product->id}}" class="edit-item edit_products" data-product="{{json_encode($product)}}" title="Edit Product">Edit</a></td>
              <td><a href="{{url($locale.'/del_pro/'.$product->id)}}" class="delete-item">&times;</a></td>
            </tr>
            @endforeach
            @else
            <tr style="text-align: center;">
              <td> No Products Exist Yet </td>
            </tr>
            @endif
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <div id="orders-tb" class="tab-pane fade @if(isset($_GET['tab']) && $_GET['tab'] =='order_history') in active @endif">
      <div class="well filter-well">  
            <div class="col-sm-12 pad-xs-0 products-gutterbx">
              <div class="table-responsive">
              @include('maskFront::includes.provider_orders')
      </div>
    </div>
  </div>
</div>


  <div id="gallery-tb" class="tab-pane fade @if(isset($_GET['tab']) && $_GET['tab'] =='gallary')in active @endif">
    <div class="add_team_div-2 col-sm-12 gap-btm-10">
      <h3 class="text-uppercase">Our Gallery</h3>
      <a href="#" type="button" class="btn" data-toggle="modal" data-target="#update_gallary_images">Add Images</a>
    </div>
    <div class="col-sm-12 pad-0">
    <?php //echo '<pre>';print_r($provider->provider_images);exit; ?>
      <div class="well gallery-well">
        <ul class="list-inline hair-list" id="content-7">
          @isset($provider->provider_images)
          @foreach($provider->provider_images as $provider_image)
          @php($img = url('files/'.$provider_image->filename))
          <li id="{{$provider_image->id}}">
            <a href="javascript:void(0)" class="hair-style">
              <div class="cross_mark"><a href="javascript:void(0)" class="cross_mark_" data-id="{{$provider_image->id}}"><i class="fa fa-trash fa-lg"></i></a></div>
              <div class="hair-image" style="background:url({{$img}})"></div>
            </a>
          </li>
          @endforeach
          @endif
        </ul>
      </div>
    </div>
  </div>
  <div id="myservices-tb" class="tab-pane fade @if(isset($_GET['tab']) && $_GET['tab'] =='service') in active @endif">
    <div class="add_team_div-2 col-sm-12 gap-btm-10">
      <h3 style="text-align: center;">My Services</h3>
      <a href="#" type="button" class="btn edit_services">Add Service</a>
    </div>
    @if(isset($provider->getServices) && $provider->getServices)
    <div class="col-sm-12 pad-xs-0 products-gutterbx">
      <div class="table-responsives col-xs-12 pad-0">
        <!-- <table class="product-table" id="service_table">
          <thead>
            <tr>
              <th class="item-image">Image</th>
              <th class="item-name">Name</th>
              <th class="item-satus">Actions</th>
              <th class="item-delete"></th>
            </tr>
          </thead>
          <tbody>
            @foreach($provider->getServices as $service)
            <tr>
              <td><</td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
            @endforeach
          </tbody>
        </table> -->

        <div class="col-sm-12 list-servicesbox">
            <div class="col-md-8 content"  id="content-6">
              <ol class="list-inline verticle-list">
              @foreach($provider->getServices as $service)
                  <li>
                      <span class="product-fig" style="background:url(@if(isset($service->image) && $service->image !=''){{url('files/'.$service->image)}} @else {{ San_Help::san_Asset('images/user-img.jpg') }} @endif)"></span>
                      <div class="serv-info">
                          <p>@if(isset($service->name)){!!San_Help::sanGetLang($service->name,$locale)!!}@endif</p>
                      </div>
                      <div class="serv-info">
                          <p>@if(isset($service->name)){!!San_Help::moneyApi($service->price,$currency)!!}@endif</p>
                      </div>
                      <div class="right_buttons">
                          <a href="javascript:void(0)" data-id="{{$service->id}}" class="edit-item edit_services" data-service="{{json_encode($service)}}" title="Edit Service">Edit</a>
                           <a href="{{url($locale.'/del_ser/'.$service->id)}}" class="delete-item">×</a>
                      </div>
                  </li>
                  @endforeach
              </ol>
            </div>
        </div>
      </div>
    </div>
    @else
    <div class="col-sm-12 tabb_gutter pad-0 serv-gutter">
      <div class="tab-content tab-content2">
        <ol class="list-group gutter-list1">
          <div class="well gallery-well"><span class="no_data">{!!San_Help::sanLang('No Service Found')!!}</span></div>
        </ol>
      </div>
    </div>
    @endif

  </div>
  <div id="settings-tb" class="tab-pane fade @if(isset($_GET['tab']) && $_GET['tab'] =='setting') in active @endif">
    <div class="add_team_div col-sm-12 gap-btm-10"><h3>{!!San_Help::sanLang('Settings')!!}</h3></div>
    <div class="outer_div">
      <form class="form-gutter setting_form" enctype="multipart/form-data" id="setting_form" rel="" method="POST" role="form" action="{{route('update_profile')}}">
        {{ csrf_field() }}
        <div class="col-sm-12 pad-0 setting-section">
          <h2 class="sln-box-title">{!!San_Help::sanLang('Salon General information')!!}</h2>
          <div class="col-sm-12">
            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <input name="name" id="name" value="@if(isset($provider->name)){{$provider->name}}@endif" placeholder="Service provider Name" class="form-control" type="text">
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <section class="cstm-upload">
                    <label for="file" class="input input-file">
                      <div class="button"><input name="image" id="salon_image" value="" class="form-control" onchange="getValue(this);" type="file">{!!San_Help::sanLang('Browse')!!}</div>
                      <input placeholder="Add Profile Image" readonly="" type="text" class="profile_image_section">
                    </label>
                  </section>
                </div>
              </div>
            </div>
          </div>
          <div class="col-sm-12">
            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <input name="email" id="salon_email" value="@if(isset($provider->name)){{$provider->email}}@endif" placeholder="Email" class="form-control" type="text">
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <input name="phone" id="salon_phone" value="@if(isset($provider->phone)){{$provider->phone}}@endif" placeholder="Contact Number with Country Code" class="form-control" type="text">
                </div>
              </div>
            </div>
          </div>
          <div class="col-sm-12 form-group">
            <div class="row">
              <div class="col-sm-12">
                <input name="address" id="salon_address" value="@if(isset($provider->address)){{$provider->address}}@endif" placeholder="Fetch your street address" class="form-control" type="text" autocomplete="off">
              </div>
            </div>
            <div class="row">
              <div class="col-sm-12">
                <div id="googleMap" style="display:none;width:100%;height:400px;"></div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-sm-12 btm--bx">
          <h2 class="sln-box-title">Extra Features</h2>
          <input type="hidden" id="extra_features_san" value="@if(isset($provider->getAvail->extra)){{json_encode(unserialize($provider->getAvail->extra))}}@endif">
          <div class="row">
            <div class="col-xs-12">
              <div class="col-xs-6 col-sm-4 col-md-4 form-group cstm-checkbox">
                <input name="extra_features[welcome_drink]" class="san_chks" id="welcome_drink" value="1" type="checkbox">
                <label for="extra_featureswelcome_drink">Welcome Drink</label>
              </div>
              <div class="col-xs-6 col-sm-4 col-md-4 form-group cstm-checkbox">
                <input name="extra_features[wifi]" class="san_chks" id="wifi" value="1" type="checkbox">
                <label for="extra_featureswifi">Wifi</label>
              </div>
              <div class="col-xs-6 col-sm-4 col-md-4 form-group cstm-checkbox">
                <input name="extra_features[kids_care]" class="san_chks" id="kids_care" value="1" type="checkbox">
                <label for="extra_featureskids_care">Kids Care</label>
              </div>
              <div class="col-xs-6 col-sm-4 col-md-4 form-group cstm-checkbox">
                <input name="extra_features[pets]" class="san_chks" id="pets" value="1" type="checkbox">
                <label for="extra_featurespets">Pets</label>
              </div>
              <div class="col-xs-6 col-sm-4 col-md-4 form-group cstm-checkbox">
                <input name="extra_features[cash]" class="san_chks" id="cash" value="1" type="checkbox">
                <label for="extra_featurescash">Accept Payment by Cash</label>
              </div>
              <div class="col-xs-6 col-sm-4 col-md-4 form-group cstm-checkbox">
                <input name="extra_features[card]" class="san_chks" id="card" value="1" type="checkbox">
                <label for="extra_featurescard">Accept Payment by Card</label>
              </div>
            </div>
          </div>
        </div>
        <!--BOTTOM-SECTION_STARTS--->
        <div class="col-sm-12 btm--bx">
          <input type="hidden" id="availability_san" value="@if(isset($provider->getAvail->availability)){{json_encode(unserialize($provider->getAvail->availability))}}@endif">
          <h2 class="sln-box-title">Availability</h2>
          <div class="sln-calendar--wrapper pad-0">
            <div class="sln-checkbutton-group form-group">
              <div class="sln-checkbutton">
                <input type="checkbox" class="big-check-base big-check-onoff" name="salon_settings[availabilities][1][days][1]" id="_sln_attendant_availabilities___new___days_1" value="1">
                <label for="_sln_attendant_availabilities___new___days_1">Sunday</label>
              </div>
              <!-- sln-checkbutton -->
              <div class="sln-checkbutton">
                <input type="checkbox" class="big-check-base big-check-onoff" name="salon_settings[availabilities][1][days][2]" id="_sln_attendant_availabilities___new___days_2" value="1">
                <label for="_sln_attendant_availabilities___new___days_2">Monday</label>
              </div>
              <!-- sln-checkbutton -->
              <div class="sln-checkbutton">
                <input type="checkbox" class="big-check-base big-check-onoff" name="salon_settings[availabilities][1][days][3]" id="_sln_attendant_availabilities___new___days_3" value="1">
                <label for="_sln_attendant_availabilities___new___days_3">Tuesday</label>
              </div>
              <!-- sln-checkbutton -->
              <div class="sln-checkbutton">
                <input type="checkbox" class="big-check-base big-check-onoff" name="salon_settings[availabilities][1][days][4]" id="_sln_attendant_availabilities___new___days_4" value="1">
                <label for="_sln_attendant_availabilities___new___days_4">Wednesday</label>
              </div>
              <!-- sln-checkbutton -->
              <div class="sln-checkbutton">
                <input type="checkbox" class="big-check-base big-check-onoff" name="salon_settings[availabilities][1][days][5]" id="_sln_attendant_availabilities___new___days_5" value="1">
                <label for="_sln_attendant_availabilities___new___days_5">Thursday</label>
              </div>
              <!-- sln-checkbutton -->
              <div class="sln-checkbutton">
                <input type="checkbox" class="big-check-base big-check-onoff" name="salon_settings[availabilities][1][days][6]" id="_sln_attendant_availabilities___new___days_6" value="1">
                <label for="_sln_attendant_availabilities___new___days_6">Friday</label>
              </div>
              <!-- sln-checkbutton -->
              <div class="sln-checkbutton">
                <input type="checkbox" class="big-check-base big-check-onoff" name="salon_settings[availabilities][1][days][7]" id="_sln_attendant_availabilities___new___days_7" value="1">
                <label for="_sln_attendant_availabilities___new___days_7">Saturday</label>
              </div>
              <!-- sln-checkbutton -->
              <div class="clearfix"></div>
            </div>
            <!-- sln-checkbutton-group -->
          </div>
          <!-- sln-calendar--wrapper -->
          <div class="row" id="sln-salon--admin">
            <div class="col-xs-12 col-md-12 sln-slider-wrapper pad-0">
              <div class="col-xs-12 col-md-6">
                <h4 class="">First Shift</h4>
                <div class="form-group">
                  <div class="sln-slider">
                    <div class="sliders_step1 col col-slider">
                      <div class="slider-range ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all">
                        <div class="ui-slider-range ui-widget-header ui-corner-all" style="left: 33.3333%; width: 20.8333%;"></div>
                        <span class="ui-slider-handle ui-state-default ui-corner-all" tabindex="0" style="left: 33.3333%;"></span><span class="ui-slider-handle ui-state-default ui-corner-all" tabindex="0" style="left: 54.1667%;"></span>
                      </div>
                    </div>
                    <div class="col col-time">
                      <span class="slider-time-from" id="san_slider-time-from">08:00</span>
                      to <span class="slider-time-to" id="san_slider-time-to">13:00</span>
                      <input type="text" name="salon_settings[availabilities][1][from][0]" id="input1_slider-time-from" value="08:00" class="slider-time-input-from hidden">
                      <input type="text" name="salon_settings[availabilities][1][to][0]" id="input1_slider-time-to" value="13:00" class="slider-time-input-to hidden">
                    </div>
                    <div class="clearfix"></div>
                  </div>
                  <!-- sln-slider -->
                </div>
              </div>
              <div class="col-xs-12 col-md-6">
                <h4 class="">Second Shift</h4>
                <div class="form-group">
                  <div class="sln-slider">
                    <div class="sliders_step1 col col-slider">
                      <div class="slider-range ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all">
                        <div class="ui-slider-range ui-widget-header ui-corner-all" style="left: 54.1667%; width: 29.1667%;"></div>
                        <span class="ui-slider-handle ui-state-default ui-corner-all" tabindex="0" style="left: 54.1667%;"></span><span class="ui-slider-handle ui-state-default ui-corner-all" tabindex="0" style="left: 83.3333%;"></span>
                      </div>
                    </div>
                    <div class="col col-time">
                      <span class="slider-time-from" id="san2_slider-time-from">13:00</span> to <span class="slider-time-to" id="san2_slider-time-to">20:00</span>
                      <input type="text" name="salon_settings[availabilities][1][from][1]" id="input_slider-time-from" value="13:00" class="slider-time-input-from hidden">
                      <input type="text" name="salon_settings[availabilities][1][to][1]" id="input_slider-time-to" value="20:00" class="slider-time-input-to hidden">
                    </div>
                    <div class="clearfix"></div>
                  </div>
                </div>
              </div>
              <!-- sln-slider -->
            </div>
            <!-- sln-slider-wrapper -->
          </div>
          <!-- row -->
        </div>
        <div class="form-group col-sm-12">
          <button type="submit" class="btn yell-btn" value="profile_update" name="profile_update">Save</button>
        </div>
        <!--BOTTOM-SECTION_END-->

      </form>
    </div>
  </div>
</div>
</div>
</div>

<div id="form-template" class="hidden">
  <form>
    <div class="row">
      <div class="col-sm-12">
        <input name="username" placeholder="Username" class="swal-content__input" type="text">
      </div>
    </div>
    <div class="row">
      <div class="col-sm-12">
        <input name="password" placeholder="Password" class="swal-content__input" type="password">
      </div>
    </div>
  </form>    
</div>

</div>
</div>
</div>
</section>
@push('boot_scripts')
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>
<script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>
<script src="{{ San_Help::san_Asset('js/jquery.mCustomScrollbar.concat.min.js') }}"></script>
<script src="{{ San_Help::san_Asset('js/userdetail.js') }}"></script>

<script type="text/javascript">
function showReplyWindow(id,reply){
  $('#new-review-formm #reply_on').val(id);
  $('#new-review-formm #review_body').val(reply);
  $('#leave_feedback').modal('show');
}

$('.cross_mark_').on('click',function(e){
  var crnt = this;
  var id = $(this).data('id');
  e.preventDefault();
  $.ajax({
      type : "get",
      url  : $('#ajax_url').val()+'/gallary/del/'+id,
      cache : false,
      success  : function(data) {
          if (typeof data == 'string' || data instanceof String) {
              alert('something went wrong'); 
          }else{
            $('li#'+id).remove();
          }
      }
  })
});
$('.provider_order_status').on('change',function(e){
  e.preventDefault();
  var crnt = this;
  var id = $(this).data('id');
  var val = $(this).val();
  $.ajax({
    headers: {
      'X-CSRF-TOKEN': $('#csrf_token').val()
    },
    type : "POST",
    url  : $('#ajax_url').val()+'/orderstatus',
    data : {order_id:id,status:val},
    cache : false,
    success  : function(data) {
      swal({
        title: "Updated",
        text: data.msg,
        icon: "success",
        button: "close",
      });
    }
   });
});

$("#content-6").mCustomScrollbar({
		autoHideScrollbar:true,
		theme:"rounded"
});
$("#content-7").mCustomScrollbar({
		autoHideScrollbar:true,
		theme:"rounded"
});
function showReviewWindow_(id,reviewid,reply){
  $('#new-review-formm_'+reviewid+' .reply_on').val(reviewid);
  $('#new-review-formm_'+reviewid+' .review_body').val(reply);
  $("#display_order_reviews_"+id).slideToggle();
}
// $(".spr-summary-actions-newreview").click(function(){
// 	$("#display_order_reviews_"+$(this).data('id')).slideToggle();
// });
</script>
@endpush
@endsection
