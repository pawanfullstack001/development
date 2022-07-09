
<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
  <meta charset="utf-8" />
  <title>Admin</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
  <link rel="icon" type="image/x-icon" href="{{  asset('assets/img/favicon.png') }}" />
  <link href="{{  asset('assets/css/pace-theme-flash.css') }}" rel="stylesheet" type="text/css" />
  <link href="{{  asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
  <link href="{{  asset('assets/css/font-awesome.css') }}" rel="stylesheet" type="text/css" />
  <link href="{{  asset('assets/css/jquery.scrollbar.css') }}" rel="stylesheet" type="text/css" media="screen" />
  <link href="{{  asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
  <link href="{{  asset('assets/css/iEdit.css') }}" rel="stylesheet" type="text/css" />
  <link href="{{  asset('assets/css/pages-icons.css') }}" rel="stylesheet" type="text/css">
  <link href="{{  asset('assets/css/style.css') }}" rel="stylesheet" type="text/css">
  <link class="main-stylesheet" href="{{  asset('assets/css/pages.css') }}" rel="stylesheet" type="text/css" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

  

  <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet"/>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>
         

</head>
<body class="fixed-header dashboard @if(session()->has('show_side_bar')) sidebar-visible menu-pin @endif">
<!-- BEGIN SIDEBPANEL-->
<nav class="page-sidebar"  data-pages="sidebar"  >
  <!-- BEGIN SIDEBAR MENU TOP TRAY CONTENT-->
  <div class="sidebar-overlay-slide from-top" id="appMenu">
    <div class="row">
      <div class="col-xs-6 no-padding">
        <a href="#" class="p-l-40"><img src="{{  asset('assets/img/demo/social_app.svg') }}" alt="socail">
        </a>
      </div>
      <div class="col-xs-6 no-padding">
        <a href="#" class="p-l-10"><img src="{{  asset('assets/img/demo/email_app.svg') }}" alt="socail">
        </a>
      </div>
    </div>
    <div class="row">
      <div class="col-xs-6 m-t-20 no-padding">
        <a href="#" class="p-l-40"><img src="{{  asset('assets/img/demo/calendar_app.svg') }}" alt="socail">
        </a>
      </div>
      <div class="col-xs-6 m-t-20 no-padding">
        <a href="#" class="p-l-10"><img src="{{  asset('assets/img/demo/add_more.svg') }}" alt="socail">
        </a>
      </div>
    </div>
  </div>
  <!-- END SIDEBAR MENU TOP TRAY CONTENT-->
  <!-- BEGIN SIDEBAR MENU HEADER-->
  <div class="sidebar-header">
    <img src="{{  asset('assets/img/logo_2x.png') }}" alt="logo" class="brand" data-src="{{  asset('assets/img/logo_2x.png') }}" data-src-retina="{{  asset('assets/img/logo_2x.png') }}" height="58">
    <div class="sidebar-header-controls">
      <!-- <button type="button" class="btn btn-xs sidebar-slide-toggle btn-link m-l-20" data-pages-toggle="#appMenu"><i class="fa fa-angle-down fs-16"></i>
      </button> -->
      <button type="button" onclick="set_side_barsession()" style="margin-left: 30px;" class="btn btn-link d-lg-inline-block d-xlg-inline-block d-md-inline-block d-sm-none d-none" data-toggle-pin="sidebar"><i class="fa fs-12"></i>
      </button>
    </div>
  </div>
  <!-- END SIDEBAR MENU HEADER-->
  <!-- START SIDEBAR MENU -->
  <div class="sidebar-menu">
    <!-- BEGIN SIDEBAR MENU ITEMS-->
    <ul class="menu-items">
      
      @if(\Auth::guard('subadmin')->user()->driver_management_role)
      <li class="{{ Request::is('subadmin/user_management/1') ? 'active' : '' }}">
        <a href="{{ url('subadmin/user_management/1') }}"><span class="title">Driver</span></a>
        <span class="icon-thumbnail {{ Request::is('subadmin/user_management/1') ? 'bg-success ' : '' }}"><i class="fa fa-user"></i></span>
      </li>
      @endif

      @if(\Auth::guard('subadmin')->user()->organization_management_role)
      <li class="{{ Request::is('subadmin/organization') ? 'active' : '' }}">
        <a href="{{ url('subadmin/organization') }}"><span class="title">Organization</span></a>
        <span class="icon-thumbnail  {{ Request::is('subadmin/organization') ? 'bg-success ' : '' }}"><i class="fa fa-car"></i></span>
      </li> 
      @endif 

      @if(\Auth::guard('subadmin')->user()->service_type_role)
      <li class="{{ Request::is('subadmin/service_type') ? 'active' : '' }}">
        <a href="{{ url('subadmin/service_type') }}"><span class="title ">Service Type</span></a>
        <span class="icon-thumbnail {{ Request::is('subadmin/service_type') ? 'bg-success ' : '' }}"><i class="fa fa-cogs"></i></span>
      </li>
      @endif 

      @if(\Auth::guard('subadmin')->user()->service_request_role)
      <li class="">
        <a href="service-request.php"><span class="title">Service Request</span></a>
        <span class="icon-thumbnail"><i class="fa fa-cog"></i></span>
      </li>
      @endif 

      @if(\Auth::guard('subadmin')->user()->subscription_plan_role)
      <li class="">
        <a href="subscription.php"><span class="title">Subscription Paln</span></a>
        <span class="icon-thumbnail"><i class="fa fa-bell"></i></span>
      </li>
      @endif 

      @if(\Auth::guard('subadmin')->user()->booking_role)
      <li class="">
        <a href="booking.php"><span class="title">Booking</span></a>
        <span class="icon-thumbnail"><i class="fa fa-book"></i></span>
      </li>
      @endif 

      @if(\Auth::guard('subadmin')->user()->sms_management_role)
      <li class="">
        <a href="sms.php"><span class="title">SMS Management</span></a>
        <span class="icon-thumbnail"><i class="fa fa-comment"></i></span>
      </li>
      @endif 

      @if(\Auth::guard('subadmin')->user()->price_and_eta_role)
      <li class="">
        <a href="price-eta.php"><span class="title">Price and ETA</span></a>
        <span class="icon-thumbnail"><i class="fa fa-money"></i></span>
      </li>
      @endif 

      @if(\Auth::guard('subadmin')->user()->notification_role)
      <li class="">
        <a href="notification.php"><span class="title">Notification</span></a>
        <span class="icon-thumbnail"><i class="fa fa-bell"></i></span>
      </li>
      @endif 

      @if(\Auth::guard('subadmin')->user()->report_generation_role)
      <li class="">
        <a href="report.php"><span class="title">Report Generation</span></a>
        <span class="icon-thumbnail"><i class="fa fa-line-chart"></i></span>
      </li>
      @endif 

      @if(\Auth::guard('subadmin')->user()->rating_and_review_role)
      <li class="">
        <a href="rating-review.php"><span class="title">Rating & Review</span></a>
        <span class="icon-thumbnail"><i class="fa fa-star"></i></span>
      </li>
      @endif 
      
      @if(\Auth::guard('subadmin')->user()->setting_role)
      <li class="">
        <a href="setting.php"><span class="title">Setting</span></a>
        <span class="icon-thumbnail"><i class="fa fa-wrench"></i></span>
      </li>
      @endif

    </ul>
    <div class="clearfix"></div>
  </div>
  <!-- END SIDEBAR MENU -->
</nav>
<!-- END SIDEBAR -->
<!-- END SIDEBPANEL-->
<!-- START HEADER -->
<div class="header ">
  <!-- START MOBILE SIDEBAR TOGGLE -->
  <a href="#" class="btn-link toggle-sidebar d-lg-none pg pg-menu" data-toggle="sidebar">
  </a>
  <!-- END MOBILE SIDEBAR TOGGLE -->
  <div class="">
    <div class="brand inline   ">
      <img src="{{  asset('assets/img/logo_2x.png') }}" alt="logo" data-src="{{  asset('assets/img/logo_2x.png') }}" data-src-retina="{{  asset('assets/img/logo_2x.png') }}"  height="50">
    </div>
    <!-- START NOTIFICATION LIST -->
    
    <!-- END NOTIFICATIONS LIST -->
    <a href="#" class="search-link d-lg-inline-block d-none" data-toggle="search"><i class="pg-search"></i>Type anywhere to <span class="bold">search</span></a>
  </div>
  <div class="d-flex align-items-center">
    <ul class="d-lg-inline-block d-none notification-list no-margin d-lg-inline-block b-grey b-l b-r no-style p-l-30 p-r-20">
      <li class="p-r-10 inline">
        <div class="dropdown">
          <a href="javascript:;" id="notification-center" class="header-icon pg pg-world" data-toggle="dropdown">
            <span class="bubble"></span>
          </a>
          <!-- START Notification Dropdown -->
          <div class="dropdown-menu notification-toggle" role="menu" aria-labelledby="notification-center">
            <!-- START Notification -->
            <div class="notification-panel">
              <!-- START Notification Body-->
              <div class="notification-body scrollable">
                <!-- START Notification Item-->
                <div class="notification-item unread clearfix">
                  <!-- START Notification Item-->
                  <div class="heading open">
                    <a href="#" class="text-complete pull-left">
                      <i class="pg-map fs-16 m-r-10"></i>
                      <span class="bold">Carrot Design</span>
                      <span class="fs-12 m-l-10">Fluper</span>
                    </a>
                    <div class="pull-right">
                      <div class="thumbnail-wrapper d16 circular inline m-t-15 m-r-10 toggle-more-details">
                        <div><i class="fa fa-angle-left"></i>
                        </div>
                      </div>
                      <span class=" time">few sec ago</span>
                    </div>
                    <div class="more-details">
                      <div class="more-details-inner">
                        <h5 class="semi-bold fs-16">“Apple’s Motivation - Innovation <br>
                                                      distinguishes between <br>
                                                      A leader and a follower.”</h5>
                        <p class="small hint-text">
                          Commented on john Smiths wall.
                          <br> via pages framework.
                        </p>
                      </div>
                    </div>
                  </div>
                  <!-- END Notification Item-->
                  <!-- START Notification Item Right Side-->
                  <div class="option" data-toggle="tooltip" data-placement="left" title="mark as read">
                    <a href="#" class="mark"></a>
                  </div>
                  <!-- END Notification Item Right Side-->
                </div>
                <!-- START Notification Body-->
                <!-- START Notification Item-->
                <div class="notification-item  clearfix">
                  <div class="heading">
                    <a href="#" class="text-danger pull-left">
                      <i class="fa fa-exclamation-triangle m-r-10"></i>
                      <span class="bold">98% Server Load</span>
                      <span class="fs-12 m-l-10">Take Action</span>
                    </a>
                    <span class="pull-right time">2 mins ago</span>
                  </div>
                  <!-- START Notification Item Right Side-->
                  <div class="option">
                    <a href="#" class="mark"></a>
                  </div>
                  <!-- END Notification Item Right Side-->
                </div>
                <!-- END Notification Item-->
                <!-- START Notification Item-->
                <div class="notification-item  clearfix">
                  <div class="heading">
                    <a href="#" class="text-warning-dark pull-left">
                      <i class="fa fa-exclamation-triangle m-r-10"></i>
                      <span class="bold">Warning Notification</span>
                      <span class="fs-12 m-l-10">Buy Now</span>
                    </a>
                    <span class="pull-right time">yesterday</span>
                  </div>
                  <!-- START Notification Item Right Side-->
                  <div class="option">
                    <a href="#" class="mark"></a>
                  </div>
                  <!-- END Notification Item Right Side-->
                </div>
                <!-- END Notification Item-->
                <!-- START Notification Item-->
                <div class="notification-item unread clearfix">
                  <div class="heading">
                    <div class="thumbnail-wrapper d24 circular b-white m-r-5 b-a b-white m-t-10 m-r-10">
                      <img width="30" height="30" data-src-retina="{{  asset('assets/img/profiles/1x.jpg') }}" data-src="{{  asset('assets/img/profiles/1.jpg') }}" alt="" src="{{  asset('assets/img/profiles/1.jpg') }}">
                    </div>
                    <a href="#" class="text-complete pull-left">
                      <span class="bold">Fluper Design Labs</span>
                      <span class="fs-12 m-l-10">Owners</span>
                    </a>
                    <span class="pull-right time">11:00pm</span>
                  </div>
                  <!-- START Notification Item Right Side-->
                  <div class="option" data-toggle="tooltip" data-placement="left" title="mark as read">
                    <a href="#" class="mark"></a>
                  </div>
                  <!-- END Notification Item Right Side-->
                </div>
                <!-- END Notification Item-->
              </div>
              <!-- END Notification Body-->
              <!-- START Notification Footer-->
              <div class="notification-footer text-center">
                <a href="#" class="">Read all notifications</a>
                <a data-toggle="refresh" class="portlet-refresh text-black pull-right" href="#">
                  <i class="pg-refresh_new"></i>
                </a>
              </div>
              <!-- START Notification Footer-->
            </div>
            <!-- END Notification -->
          </div>
          <!-- END Notification Dropdown -->
        </div>
      </li>
    </ul>
    <!-- START User Info-->
    <div class="pull-left p-r-10 fs-14 font-heading d-lg-block d-none">
      <span class="semi-bold p-l-10">John</span>
    </div>
    <div class="dropdown pull-right d-lg-block d-none">
    
      <button class="profile-dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <span class="thumbnail-wrapper d32 circular inline">
         
        <img src="{{  (!@(\Auth::guard('admin')->user()->image))?asset('assets/img/profiles/avatar.jpg'):asset('public/files').'/'.(\Auth::guard('admin')->user()->image) }}" alt="" data-src="{{  (!@(\Auth::guard('admin')->user()->image))?asset('assets/img/profiles/avatar.jpg'):asset('public/files').'/'.(\Auth::guard('admin')->user()->image) }}" data-src-retina="{{  asset('assets/img/profiles/avatar_small2x.jpg') }}" width="32" height="32">
        </span>
      </button>
      <div class="dropdown-menu dropdown-menu-right profile-dropdown" role="menu">
      
        <a href="{{ url('subadmin/logout') }}" class="clearfix bg-master-lighter dropdown-item">
          <span class="pull-left">Logout</span>
          <span class="pull-right"><i class="pg-power"></i></span>
        </a>
      </div>
    </div>
    <!-- END User Info-->
  </div>
</div>
<!-- END HEADER -->
<!-- START PAGE-CONTAINER -->
<div class="page-container ">
<!-- START PAGE CONTENT WRAPPER -->
<div class="page-content-wrapper ">

<script>
  function set_side_barsession(){
    $.ajax({
      url:"{{ url('admin/set_url_value') }}",
      success:function(){
        console.log("status changes successfully");
      }
    })
  }
</script>