<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>@yield('title') | {{ config('app.name', 'RideApp') }}</title>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="">
    <meta name="author" content="">

    <link rel="stylesheet" href="{{ url('css/customer/responsive.css') }}">
    <link rel="stylesheet" href="{{ url('css/customer/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ url('css/customer/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/customer/intlTelInput.css') }}">
    <style type="text/css" media="screen">
      .btn-social {
        background-repeat: no-repeat;
        width: 220px;
        height: 34px;
        display: inline-block;
      }
      .btn-google {
        background-image: url('../../images/google.png');
      }
      .btn-facebook {
        background-image: url('../../images/facebook.png');
      }
      .btn-linkedin {
        background-image: url('../../images/linkedin.png');
      }
  		.foot-app-logo {
  		    width: 42px !important;
  		    display: block;
  		    float: left;
  		    margin-right: 10px;
  		}
  		.foot-app-name {
  			margin-top: 9px;
  			color: white;
  			display: inline-block;
  		}
  		.app-logo {
  		    width: 42px !important;
  		    display: block;
  		    float: left;
  		    margin-right: 10px;
  		}
  		.app-name {
  		    margin-top: 13px;
  		}
  		#search{
  		    padding-right: 22px !important;
  		}
  		.header-title{
  			color: black;
  		}
  		.header-title:hover{
  			text-decoration: none;
  		}
      .iti-flag {background-image: url("./images/flags.png");}
      @media (-webkit-min-device-pixel-ratio: 2), (min-resolution: 192dpi) {
        .iti-flag {background-image: url("./images/flags@2x.png");}
      }
      .intl-tel-input {
        width: 100%;
      }
    </style>

    @stack('css')

    {{--<script src="{{ url('js/customer/jquery.min.js') }}"></script>--}}
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    @stack('header_scripts')
</head>
<body>

  @include('inc.header')

  @yield('content')

  @include('inc.footer')

  @stack('footer_scripts')
</body>
</html>
