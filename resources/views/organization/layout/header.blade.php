
<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
  <meta charset="utf-8" />
  <title>Organization</title>
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

  <script type="text/javascript" src='https://maps.google.com/maps/api/js?key=AIzaSyBfphPoFQQN_yrnuEYX108y5IxU1SWNR0A&sensor=false&libraries=places&callback=loadMap'></script>

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
  @if(Auth::guard('organization')->user()->organization_logo!='')
    <img src="{{asset('public/files/')}}/{{Auth::guard('organization')->user()->organization_logo}}" alt="logo" class="brand" data-src="{{asset('public/files/')}}/{{Auth::guard('organization')->user()->organization_logo}}" data-src-retina="{{asset('public/files/')}}/{{Auth::guard('organization')->user()->organization_logo}}" height="58" style="width: 111px;
    height: 46px;">
  @else
  <img src="{{  asset('assets/img/logo_2x.png') }}" alt="logo" class="brand" data-src="{{  asset('assets/img/logo_2x.png') }}" data-src-retina="{{  asset('assets/img/logo_2x.png') }}" height="58" style="width: 111px;
    height: 46px;">
  @endif
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
      
      <li class="m-t-30 {{ Request::is('organization/home') ? 'active' : '' }}">
        <a href="{{ url('organization/home') }}" class="detailed">
          <span class="title">Dashboard</span>
        </a>
        <span class=" icon-thumbnail {{ Request::is('organization/home') ? 'bg-success' : '' }}"><i class="pg-home"></i></span>
      </li>
  
      <li class="{{ Request::is('organization/user_management/1') ? 'active' : '' }}">
        <a href="{{ url('organization/user_management/1') }}"><span class="title">Driver</span></a>
        <span class="icon-thumbnail {{ Request::is('organization/user_management/1') ? 'bg-success' : '' }}"><i class="fa fa-user"></i></span>
      </li>
     
      {{--<li class="{{ Request::is('organization/service_type') ? 'active' : '' }}">
        <a href="{{ url('organization/service_type') }}"><span class="title ">Service Type</span></a>
        <span class="icon-thumbnail {{ Request::is('organization/service_type') ? 'bg-success ' : '' }}"><i class="fa fa-cogs"></i></span>
      </li>--}}

      <li class="{{ Request::is('organization/service-request') ? 'active' : '' }}">
        <a href="{{ url('organization/service-request') }}"><span class="title ">Bookings</span></a>
        <span class="icon-thumbnail {{ Request::is('organization/service-request') ? 'bg-success ' : '' }}"><i class="fa fa-book"></i></span>
      </li>

    
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
    {{--<div class="brand inline   ">
      <img src="{{  asset('assets/img/logo_2x.png') }}" alt="logo" data-src="{{  asset('assets/img/logo_2x.png') }}" data-src-retina="{{  asset('assets/img/logo_2x.png') }}"  height="50">
    </div>--}}
    <!-- START NOTIFICATION LIST -->
    
    <!-- END NOTIFICATIONS LIST -->
    
  </div>
  <div class="d-flex align-items-center">
  
    <!-- START User Info-->
    <div class="pull-left p-r-10 fs-14 font-heading d-lg-block d-none">
      
    </div>
    <div class="dropdown pull-right d-lg-block d-none">
    
      <button class="profile-dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <span>
        {{Auth::guard('organization')->user()->login_name}}
        {{--<img src="{{  (!@(\Auth::guard('organization')->user()->image))?asset('assets/img/profiles/avatar.jpg'):asset('public/files').'/'.(\Auth::guard('organization')->user()->image) }}" alt="" data-src="{{  (!@(\Auth::guard('organization')->user()->image))?asset('assets/img/profiles/avatar.jpg'):asset('public/files').'/'.(\Auth::guard('organization')->user()->image) }}" data-src-retina="{{  asset('assets/img/profiles/avatar_small2x.jpg') }}" width="32" height="32">--}}
        </span>
      </button>
      <div class="dropdown-menu dropdown-menu-right profile-dropdown" role="menu">
        <a href="{{ url('organization/profile') }}" class="dropdown-item"><i class="pg-outdent"></i> My Profile</a>
        <a href="javascript:void(0);" class="dropdown-item"><i class="pg-settings_small"></i> Change Password</a>
        <a href="{{ url('organization/logout') }}" class="clearfix bg-master-lighter dropdown-item">
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
      url:"{{ url('organization/set_url_value') }}",
      success:function(){
        console.log("status changes successfully");
      }
    })
  }
</script>