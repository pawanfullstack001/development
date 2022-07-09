<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="author" content="sumit kumar">
<title>Admin-Panel</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, shrink-to-fit=no" />
<link rel="apple-touch-icon" href="pages/ico/60.png">
<link rel="apple-touch-icon" sizes="76x76" href="pages/ico/76.png">
<link rel="apple-touch-icon" sizes="120x120" href="pages/ico/120.png">
<link rel="apple-touch-icon" sizes="152x152" href="pages/ico/152.png">
<link rel="icon" type="image/x-icon" href="favicon.ico" />
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-touch-fullscreen" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="default">
<meta content="" name="description" />
<meta content="" name="author" />
<link rel="icon" type="image/x-icon" href="{ asset('assets/assets/img/favicon.png') }}" />
<link href="{{ asset('assets/css/pace-theme-flash.css')}}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/css/font-awesome.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/css/jquery.scrollbar.css') }}" rel="stylesheet" type="text/css" media="screen" />
<link href="{{ asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/css/iEdit.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/css/pages-icons.css') }}" rel="stylesheet" type="text/css">
<link class="main-stylesheet" href="{{ asset('assets/css/pages.css') }}" rel="stylesheet" type="text/css" />
</head>
<body class="fixed-header ">
<div class="login-wrapper ">
  <div class="bg-pic">
    <img src="{{ asset('assets/img/demo/new-york-city-buildings-sunrise-morning-hd-wallpaper.jpg') }}" data-src="{{ asset('assets/img/demo/new-york-city-buildings-sunrise-morning-hd-wallpaper.jpg') }}" data-src-retina="{{ asset('assets/img/demo/new-york-city-buildings-sunrise-morning-hd-wallpaper.jpg') }}" alt="" class="lazy">
    <!-- <div class="bg-caption pull-bottom sm-pull-bottom text-white p-l-20 m-b-20">
      <h2 class="semi-bold text-white">
      Pages make it easy to enjoy what matters the most in the life</h2>
      <p class="small">
        images Displayed are solely for representation purposes only, All work copyright of respective owner, otherwise © 2013-2014 Fluper.
      </p>
    </div> -->
  </div>
  <div class="login-container bg-white">
    <div class="p-l-50 m-l-20 p-r-50 m-r-20 p-t-50 m-t-30 sm-p-l-15 sm-p-r-15 sm-p-t-40">
      <img src="{{ asset('assets/img/logo.png') }}" alt="logo" data-src="{{ asset('assets/img/logo.png') }}" data-src-retina="{{ asset('assets/img/logo_2x.png') }}" height="40">
      <p class="p-t-35">Forgot Password</p>
  
      @if(session()->has('message'))
        <p class="text-success">{{ session()->get('message') }}</p>
      @endif

      @if(session()->has('error'))
        <p class="text-danger">{{ session()->get('error') }}</p>
      @endif

      <form id="form-login" method="post"  class="p-t-15" >
      {{ csrf_field() }}
        <!-- START Form Control-->
        <div class="form-group form-group-default">
          <label>Enter Your Email</label>
          <div class="controls">
            <input type="email" name="email" class="form-control" required>
            @if($errors)
              <p>{{ $errors->first('email') }}</p>
            @endif
          </div>
        </div>
        <!-- END Form Control-->
        <button class="btn btn-primary btn-cons m-t-10 m-b-20" type="submit" >Send Link</button>
        <p><a href="{{ url('admin/login') }}">Back To Login</a></p>
      </form>
      <!-- <div class="pull-bottom sm-pull-bottom">
        <div class="m-b-30 p-r-80 sm-m-t-20 sm-p-r-15 sm-p-b-20 clearfix">
          <div class="col-sm-3 col-md-2 no-padding">
            <img alt="" class="m-t-5" data-src="assets/img/demo/pages_icon.png" data-src-retina="assets/img/demo/pages_icon_2x.png" height="60" src="assets/img/demo/pages_icon.png" width="60">
          </div>
          <div class="col-sm-9 no-padding m-t-10">
            <p>
              <small>
              Create a pages account. If you have a facebook account, log into it for this
              process. Sign in with <a href="#" class="text-info">Facebook</a> or <a href="#" class="text-info">Google</a>
            </small>
            </p>
          </div>
        </div>
      </div> -->
    </div>
  </div>
</div>
<script src="{{ asset('assets/js/jquery-3.2.1.min.js') }}"></script>
<script src="{{ asset('assets/js/modernizr.custom.js') }}"></script>
<script src="{{ asset('assets/js/jquery-ui.min.js') }}"></script>
<script src="{{ asset('assets/js/popper.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery.scrollbar.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap-datepicker.js') }}"></script>
<script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/js/iEdit.js') }}"></script>
<script src="{{ asset('assets/js/pages.js') }}"></script>
<script src="{{ asset('assets/js/Chart.min.js') }}"></script>
<script src="{{ asset('assets/js/scripts.js') }}"></script>
<script>
  $(function()    {
    $('#form-login').validate();
  });
</script>
<!-- Modal -->
<div class="modal fade" id="linkSend">
  <div class="modal-dialog modal-sm" data-dismiss="modal">
    <div class="modal-content">      
      <div class="modal-body model_text" style="text-align: center;">
        <h2><i class="fa fa-link text-success"></i></h2>
        <h5>Link Successfully Sent</h5>
        <div class="successful-icon">
      </div>
      </div> 
    </div>
  </div>
</div>
</body>