
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


  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css">
    <link href="{{asset('assets/plugins/rating/css/star-rating.css')}}" media="all" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/plugins/rating/themes/krajee-fas/theme.css')}}" media="all" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/plugins/rating/themes/krajee-svg/theme.css')}}" media="all" rel="stylesheet" type="text/css"/>
   

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

  <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet"/>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>

  <script src="{{asset('assets/plugins/rating/js/star-rating.min.js')}}" type="text/javascript"></script>
  <script src="{{asset('assets/plugins/rating/themes/krajee-fas/theme.js')}}" type="text/javascript"></script>
  <script src="{{asset('assets/plugins/rating/themes/krajee-svg/theme.js')}}" type="text/javascript"></script>
  
       @yield('map_scripts')  

</head>
<body class="fixed-header dashboard @if(session()->has('show_side_bar')) sidebar-visible menu-pin @endif">
<!-- BEGIN SIDEBPANEL-->

<!-- END SIDEBAR -->
<!-- END SIDEBPANEL-->
<!-- START PAGE-CONTAINER -->
<div class="page-container" style="padding-left: 0px;">
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