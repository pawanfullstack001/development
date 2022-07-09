
<!DOCTYPE html>
<html  lang="es">
<head>
  <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
  <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
  <meta charset="utf-8" />
  <title>Radar.Taxi</title>
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
  <style type="text/css">
    #blink_me {
      animation: blinker 1s linear infinite;
    }

    @keyframes blinker {
      50% {
        opacity: 0;
      }
    }
    .notification-panel .notification-body .notification-item{
      padding: 5px;
      margin: 0px;
    }
  </style>       

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
    <img src="{{  asset('assets/img/logo_2x.png') }}" alt="logo" class="brand" data-src="{{  asset('assets/img/logo_2x.png') }}" data-src-retina="{{  asset('assets/img/logo_2x.png') }}" width="111px;" height="46px">
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
      
      <li class="m-t-30 {{ Request::is('admin/home') ? 'active' : '' }}">
        <a href="{{ url('admin/home') }}" class="detailed">
          <span class="title">Dashboard</span>
        </a>
        <span class="icon-thumbnail {{ Request::is('admin/home') ? 'bg-success ' : '' }}"><i class="pg-home"></i></span>
      </li>
      @if(Auth::guard('admin')->user()->hasPermission('driver management',Auth::guard('admin')->user()->id))
      <li class="{{ Request::is('admin/user_management/1') ? 'active' : '' }}">
        <a href="{{ url('admin/user_management/1') }}"><span class="title">Driver</span></a>
        <span class="icon-thumbnail {{ Request::is('admin/user_management/1') ? 'bg-success ' : '' }}"><i class="fa fa-user"></i></span>
      </li>
      @endif
      @if(Auth::guard('admin')->user()->is_sub_admin!=1)
      @if(Auth::guard('admin')->user()->hasPermission('subadmin management',Auth::guard('admin')->user()->id))
      <li class="{{ Request::is('admin/sub_admin') ? 'active' : '' }}">
        <a href="{{ url('admin/subadmin') }}"><span class="title">Sub admin</span></a>
        <span class="icon-thumbnail"><i class="fa fa-cog"></i></span>
      </li>
      @endif
      @endif

     
      @if(Auth::guard('admin')->user()->hasPermission('organisation management',Auth::guard('admin')->user()->id))
      <li class="{{ Request::is('admin/organization') ? 'active' : '' }}">
        <a href="{{ url('admin/organization') }}"><span class="title">Organization</span></a>
        <span class="icon-thumbnail  {{ Request::is('admin/organization') ? 'bg-success ' : '' }}"><i class="fa fa-car"></i></span>
      </li>
      @endif

      <!-- <li class="{{ Request::is('admin/organization_type') ? 'active' : '' }}">
        <a href="{{ url('admin/organization_type') }}"><span class="title">Organization Type</span></a>
        <span class="icon-thumbnail {{ Request::is('admin/organization_type') ? 'bg-success ' : '' }}"><i class="fa fa-cogs"></i></span>
      </li> -->
      @if(Auth::guard('admin')->user()->hasPermission('service type management',Auth::guard('admin')->user()->id))
      <li class="{{ Request::is('admin/service_type') ? 'active' : '' }}">
        <a href="{{ url('admin/service_type') }}"><span class="title ">Service Type</span></a>
        <span class="icon-thumbnail {{ Request::is('admin/service_type') ? 'bg-success ' : '' }}"><i class="fa fa-cogs"></i></span>
      </li>
      @endif

      <!-- <li class="">
        <a href="{{ url('admin/vehicle_type') }}"><span class="title">Vehicle Type</span></a>
        <span class="icon-thumbnail"><i class="fa fa-car"></i></span>
      </li> -->
 
      @if(Auth::guard('admin')->user()->hasPermission('subscription plan management',Auth::guard('admin')->user()->id))
      <li class="">
        <a href="{{url('admin/subscription-plan')}}"><span class="title">Subscription Plan</span></a>
        <span class="icon-thumbnail"><i class="fa fa-bell"></i></span>
      </li>
      @endif

      @if(Auth::guard('admin')->user()->hasPermission('booking management',Auth::guard('admin')->user()->id))
      <li class="">
        <a href="{{url('admin/service-request')}}"><span class="title">Booking</span></a>
        <span class="icon-thumbnail"><i class="fa fa-book"></i></span>
      </li>
      @endif

      @if(Auth::guard('admin')->user()->hasPermission('SMS management',Auth::guard('admin')->user()->id))
      <li class="">
        <a href="#"><span class="title">SMS Management</span></a>
        <span class="icon-thumbnail"><i class="fa fa-comment"></i></span>
      </li>
      @endif

      @if(Auth::guard('admin')->user()->hasPermission('price management',Auth::guard('admin')->user()->id))
      <li class="">
        <a href="{{url('admin/price-mgnt')}}"><span class="title">Price Management</span></a>
        <span class="icon-thumbnail"><i class="fa fa-money"></i></span>
      </li>
      @endif

      @if(Auth::guard('admin')->user()->hasPermission('notification management',Auth::guard('admin')->user()->id))
      <li class="">
        <a href="#"><span class="title">Notification</span></a>
        <span class="icon-thumbnail"><i class="fa fa-bell"></i></span>
      </li>
      @endif

      @if(Auth::guard('admin')->user()->hasPermission('report generation management',Auth::guard('admin')->user()->id))
      <li class="">
        <a href="#"><span class="title">Report Generation</span></a>
        <span class="icon-thumbnail"><i class="fa fa-line-chart"></i></span>
      </li>
      @endif
      @if(Auth::guard('admin')->user()->hasPermission('rating and review management',Auth::guard('admin')->user()->id))
      <li class="">
        <a href="#"><span class="title">Rating & Review</span></a>
        <span class="icon-thumbnail"><i class="fa fa-star"></i></span>
      </li>
      @endif

      @if(Auth::guard('admin')->user()->hasPermission('content management',Auth::guard('admin')->user()->id))
      <li class="">
        <a href="{{ url('admin/content_management') }}"><span class="title">Terms & Policy</span></a>
        <span class="icon-thumbnail"><i class="fa fa-star"></i></span>
      </li>
      @endif
      @if(Auth::guard('admin')->user()->hasPermission('settings',Auth::guard('admin')->user()->id))
      <li class="">
        <a href="#"><span class="title">Setting</span></a>
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
    <!-- <div class="brand inline   ">
      <img src="{{  asset('assets/img/logo_2x.png') }}" alt="logo" data-src="{{  asset('assets/img/logo_2x.png') }}" data-src-retina="{{  asset('assets/img/logo_2x.png') }}"  height="50">
    </div> -->
    <!-- START NOTIFICATION LIST -->
    
    <!-- END NOTIFICATIONS LIST -->
    <!-- <a href="#" class="search-link d-lg-inline-block d-none" data-toggle="search"><i class="pg-search"></i>Type anywhere to <span class="bold">search</span></a> -->
  </div>
  <div class="d-flex align-items-center">
    <ul class="d-lg-inline-block d-none notification-list no-margin d-lg-inline-block b-grey b-l b-r no-style p-l-30 p-r-20">
      <li class="p-r-10 inline">
        <div class="dropdown">
          <a href="javascript:;" id="notification-center" class="header-icon pg pg-world" data-toggle="dropdown">
            <span class="bubble" style="font-size: 10px;
    font-weight: 800;padding-left: 3px;" @if(count($countappUser)>0) id="blink_me" @endif>{{count($countappUser)}}</span>
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
                @foreach($countappUser as $data)
                <div class="notification-item  clearfix">
                  <div class="">
                    <a href="{{url('read-all')}}" class="text-warning-dark pull-left">
                      <span class="bold">{{$data['name']}} wants to become a driver please verify the profile</span>
                    </a>
                    <span class="pull-right time" style="margin-top: 4px;font-size: 10px;">{{ \Carbon\Carbon::parse($data['created_at'])->diffForhumans()}}</span>
                  </div>
                  <!-- END Notification Item Right Side-->
                </div>
                @endforeach
              </div>
              <!-- END Notification Body-->
              <!-- START Notification Footer-->
              <div class="notification-footer text-center">
                @if(count($countappUser)>0)
                  <a href="{{url('read-all')}}" class="">Read all</a>
                @else
                  <a href="#" class="">No New Registration</a>
                @endif
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
      
    </div>
    <div class="dropdown pull-right d-lg-block d-none">
    
      <button class="profile-dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
     
         
        <span >{{@Auth::guard('admin')->user()->username}}</span>
       
      </button>
      <div class="dropdown-menu dropdown-menu-right profile-dropdown" role="menu">
        <a href="{{ url('admin/profile') }}" class="dropdown-item"><i class="pg-outdent"></i> My Profile</a>
        <a href="{{url('admin/changepassword')}}" class="dropdown-item"><i class="pg-settings_small"></i> Change Password</a>
        <a href="{{ url('admin/logout') }}" class="clearfix bg-master-lighter dropdown-item">
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
