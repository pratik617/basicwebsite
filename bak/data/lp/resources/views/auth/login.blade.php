<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="">
<meta name="author" content="">

<link rel="icon" type="image/png" sizes="16x16" href="plugins/images/favicon.png">

<title>Login - {{config('app.name')}} </title>
<!-- Bootstrap Core CSS -->
<link href="{{url('bootstrap/dist/css/bootstrap.min.css')}}" rel="stylesheet">
<!-- animation CSS -->
<link href="{{url('css/animate.css')}}" rel="stylesheet">
<!-- Custom CSS -->
<link href="{{url('css/style.css')}}" rel="stylesheet">
<!-- color CSS -->
<link href="{{url('css/colors/default.css')}}" id="theme"  rel="stylesheet">

<link href="//cdnjs.cloudflare.com/ajax/libs/jquery-form-validator/2.3.26/theme-default.min.css"
    rel="stylesheet" type="text/css" />

<link href="{{url('css/login.css')}}" rel="stylesheet">
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
      <form id="frm-login" class="form-horizontal form-material" method="POST" action="{{ route('login') }}">
        @csrf
        <div class="form-group logo-box">
          <div class="col-xs-12 text-center">
            <div class="user-thumb text-center">
              <img alt="thumbnail" src="{{ asset('plugins/images/logo.png') }}" width="150">
            </div>
          </div>
        </div>
        <br>
        @include('inc.messages')
        <div class="form-group">
          <div class="row">
            <div class="col-sm-10 col-sm-offset-1">
              <div class="inner-addon left-addon">
                <i class="glyphicon glyphicon-envelope"></i>
                <input type="text" id="email" name="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"  placeholder="Email"  value="{{ old('email') }}" autofocus data-validation="required|email" data-validation-error-msg-required="Please enter your email">
              </div>
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col-sm-10 col-sm-offset-1">
              <div class="inner-addon left-addon">
                <i class="glyphicon glyphicon-lock"></i>
                <input type="password" id="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" placeholder="password" name="password" data-validation="required" data-validation-error-msg="Please enter password">
              </div>
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col-sm-10 col-sm-offset-1">
              <button class="btn btn-info btn-lg btn-block text-uppercase waves-effect waves-light" type="submit">Login</button>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</section>
<!-- jQuery -->
<!--<script src="{{url('plugins/bower_components/jquery/dist/jquery.min.js')}}"></script>-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<!--<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>-->
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

<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-form-validator/2.3.26/jquery.form-validator.min.js"></script>

<script>
  $(document).ready(function() {
    $.validate({

    });
  });
</script>

</body>

</html>
