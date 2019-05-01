<!DOCTYPE html>  
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="">
<meta name="author" content="">

<link rel="apple-touch-icon" sizes="57x57" href="{{ url('plugins/images/icon/apple-icon-57x57.png') }}">
<link rel="apple-touch-icon" sizes="60x60" href="{{ url('plugins/images/icon/apple-icon-60x60.png') }}">
<link rel="apple-touch-icon" sizes="72x72" href="{{ url('plugins/images/icon/apple-icon-72x72.png') }}">
<link rel="apple-touch-icon" sizes="76x76" href="{{ url('plugins/images/icon/apple-icon-76x76.png') }}">
<link rel="apple-touch-icon" sizes="114x114" href="{{ url('plugins/images/icon/apple-icon-114x114.png') }}">
<link rel="apple-touch-icon" sizes="120x120" href="{{ url('plugins/images/icon/apple-icon-120x120.png') }}">
<link rel="apple-touch-icon" sizes="144x144" href="{{ url('plugins/images/icon/apple-icon-144x144.png') }}">
<link rel="apple-touch-icon" sizes="152x152" href="{{ url('plugins/images/icon/apple-icon-152x152.png') }}">
<link rel="apple-touch-icon" sizes="180x180" href="{{ url('plugins/images/icon/apple-icon-180x180.png') }}">
<link rel="icon" type="image/png" sizes="192x192"  href="{{ url('plugins/images/icon/android-icon-192x192.png') }}">
<link rel="icon" type="image/png" sizes="32x32" href="{{ url('plugins/images/icon/favicon-32x32.png') }}">
<link rel="icon" type="image/png" sizes="96x96" href="{{ url('plugins/images/icon/favicon-96x96.png') }}">
<link rel="icon" type="image/png" sizes="16x16" href="{{ url('plugins/images/icon/favicon-16x16.png') }}">
<link rel="manifest" href="{{ url('plugins/images/icon/manifest.json') }}">
<meta name="msapplication-TileColor" content="#ffffff">
<meta name="msapplication-TileImage" content="{{ url('plugins/images/icon/ms-icon-144x144.png') }}">
<meta name="theme-color" content="#ffffff">

<title>Login - Rideapp </title>
<!-- Bootstrap Core CSS -->
<link href="{{url('bootstrap/dist/css/bootstrap.min.css')}}" rel="stylesheet">
<!-- animation CSS -->
<link href="{{url('css/animate.css')}}" rel="stylesheet">
<!-- Custom CSS -->
<link href="{{url('css/style.css')}}" rel="stylesheet">
<!-- color CSS -->
<link href="{{url('css/colors/default.css')}}" id="theme"  rel="stylesheet">
<link href="{{url('css/admin/login-style.css')}}" rel="stylesheet">
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->

</head>
<body>
<!-- Preloader -->
<div class="preloader">
  <div class="cssload-speeding-wheel"></div>
</div>
<section id="wrapper" class="login-register">
  <div class="login-box">
    <div class="white-box">
      <form class="form-horizontal form-material" id="loginform" method="POST" action="{{ route('admin.login') }}">
        @csrf
        <div class="form-group logo-box">
          <div class="col-xs-12 text-center">
            <div class="user-thumb text-center"> 
              <img alt="thumbnail" class="img-circle site-logo" src="plugins/images/icon/logo.png">
              <!-- <h3>Genelia</h3> -->
            </div>
          </div>
        </div>
        <br>
        <!--@if(Session::has('error'))
          <div class="text-danger invalid-data">{{ Session::get('error') }}</div>
        @endif-->
        <div class="form-group">
          <div class="row">
            <div class="col-sm-10 col-sm-offset-1">
              <div class="inner-addon left-addon">
                <i class="glyphicon glyphicon-envelope"></i>
                <input class="form-control" type="text" placeholder="Email" id="email_id" maxlength="60" name="email" value="{{ old('email') }}" required autofocus>
              </div>
              @if ($errors->has('email'))
                  <span class="invalid-feedback" role="alert">
                      <strong>{{ $errors->first('email') }}</strong>
                  </span>
              @endif 
              <div id="emailError" class="text-danger"></div>
            </div>
          </div>
          <br>  
          <div class="row">
            <div class="col-sm-10 col-sm-offset-1">
              <div class="inner-addon left-addon">
                <i class="glyphicon glyphicon-lock"></i>
                <input class="form-control" type="password" placeholder="Password" id="password_id" maxlength="40" name="password" required>
              </div>
              @if ($errors->has('password'))
                  <span class="invalid-feedback" role="alert">
                      <strong>{{ $errors->first('password') }}</strong>
                  </span>
              @endif 
              <div id="passwordError" class="text-danger"></div>
            </div>
          </div>
          <br>
            @if(Session::has('error'))
          <div class="text-danger invalid-data">{{ Session::get('error') }}</div>
        @endif
          <div class="row">
            <div class="col-sm-10 col-sm-offset-1">
              <button class="btn btn-info btn-lg btn-block text-uppercase waves-effect waves-light" type="submit" id="save_button_id">Login</button>
            </div>
          </div>
        </div>   
      </form>
      <a href="{{ route('admin.forgot.password') }}">Forgot password?</a>
    </div>
  </div>
</section>
<!-- jQuery -->
<script src="{{url('plugins/bower_components/jquery/dist/jquery.min.js')}}"></script>
<!-- Bootstrap Core JavaScript -->
<script src="{{url('bootstrap/dist/js/bootstrap.min.js')}}"></script>
<!-- Menu Plugin JavaScript -->
<script src="{{url('plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.js')}}"></script>

<!--slimscroll JavaScript -->
<script src="{{url('js/jquery.slimscroll.js')}}"></script>
<!--Wave Effects -->
<script src="{{url('js/waves.js')}}"></script>
<!-- Custom Theme JavaScript -->
<script src="{{url('js/custom.min.js')}}"></script>
<!--Style Switcher -->
<script src="{{url('plugins/bower_components/styleswitcher/jQuery.style.switcher.js')}}"></script>
<script src="{{url('js/admin/login-script.js')}}" type="text/javascript" ></script>
</body>

</html>
