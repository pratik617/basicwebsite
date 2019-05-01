<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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

    <link rel="shortcut icon" type="image/x-icon" href="{{ url('plugins/images/icon/logo.png')}}">
    <link rel="stylesheet" href="{{ url('css/customer/animate.css') }}">
    <link rel="stylesheet" href="{{ url('css/customer/jquery-ui.min.css') }}">
    <link rel="stylesheet" href="{{ url('css/customer/meanmenu.min.css') }}">
    <link rel="stylesheet" href="{{ url('css/customer/owl.carousel.css') }}">
    <link rel="stylesheet" href="{{ url('css/customer/nivo-slider.css') }}">
    <link rel="stylesheet" href="{{ url('css/customer/preview.css') }}">
    <link rel="stylesheet" href="{{ url('css/customer/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ url('css/customer/flaticon.css') }}">
    <link rel="stylesheet" href="{{ url('css/customer/nice-select.css') }}">
    <link rel="stylesheet" href="{{ url('css/customer/responsive.css') }}">
    <link rel="stylesheet" href="{{ url('css/customer/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ url('css/customer/slick/slick.css') }}">
    <link rel="stylesheet" href="{{ url('css/customer/slick/slick-theme.css') }}">
    <link rel="stylesheet" href="{{ url('css/customer/styles.css') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ url('plugins/images/icon/logo.png')}}">
	<title>@yield('title')</title>
    <style type="text/css">
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
    </style>
	@stack('css')
</head>
<body>
	<section class="navbar-slider">
        <div class="container">
            <div class="row @if(Request::route()->getName() == 'dashboard') nav-row-slidre @endif">
                <div class="col-sm-12">
                    <nav class="navbar navbar-toggleable-md navbar-expand-lg">
                        <div class="col-sm-3">
                            <a href="{{ route('dashboard') }}" class="header-title">
                                <img class="app-logo" src="{{url('plugins/images/icon/logo.png')}}">
                                <h4 class="app-name">Rideapp</h4>
                            </a>
                        </div>
                        <div class="col-sm-9">
                            <button class="navbar-toggler navbar-toggler-right navbar-light" type="button" data-toggle="collapse"
                                    data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                                    aria-expanded="false" aria-label="Toggle navigation">
                                <span class="navbar-toggler-icon"></span>
                            </button>
                            <div class="collapse navbar-collapse main-menu" id="navbarSupportedContent">
                                <ul class="navbar-nav mr-auto">
                                    <li class="nav-item active">
                                        <a class="nav-link" href="{{ route('dashboard') }}"><strong>HOME</strong><span class="sr-only">(current</span></a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="about.html"><strong>ABOUT</strong></a>
                                    </li>
                                    <li class="nav-item dropdown">
                                        <a class="nav-link" href="services.html" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><strong>SERVICES+</strong></a>
                                                <ul class="sub-menu  list-unstyled" aria-labelledby="dropdownMenuButton">
                                                    <li><a href="services.html">SERVICES</a></li>
                                                    <li><a href="servicesdetails.html">SERVICES DETAILS</a></li>
                                                </ul>
                                    </li>
                                    <!-- <li class="nav-item">
                                        <a class="nav-link" href="ourfleet.html"><strong>FLEET</strong></a>
                                    </li> -->
                                    <!-- <li class="nav-item">
                                        <a class="nav-link" href="caculator.html"><strong>CALCULATOR</strong></a>
                                    </li> -->
                                    <!-- <li class="nav-item dropdown">
                                        <a class="nav-link" href="blog.html" id="dropdownMenuButton1" data-toggle="dropdown"><strong>BLOG+</strong></a>
                                        <ul class="sub-menu list-unstyled"  aria-labelledby="dropdownMenuButton1">
                                            <li><a href="blog.html">BLOG</a></li>
                                            <li><a href="singleblog.html">SINGLE BLOG</a></li>
                                        </ul>
                                    </li> -->
                                    <!-- <li class="nav-item dropdown">
                                        <a class="nav-link" href="#" id="dropdownMenuButton2" data-toggle="dropdown"><strong>PAGES+</strong></a>
                                        <ul class="sub-menu list-unstyled" aria-labelledby="dropdownMenuButton2">
                                            <li><a href="booking.html">Booking</a></li>
                                            <li><a href="404.html">ERROR</a></li>
                                        </ul>
                                    </li> -->
                                    <li class="nav-item">
                                        <a class="nav-link" href="contactus.html"><strong>CONTACT</strong></a>
                                    </li>
                                    @if(Auth::user())
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('customer.logout') }}"><strong>LOGOUT</strong></a>
                                    </li>   
                                    @else
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('customer.login') }}"><strong>LOGIN</strong></a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('customer.register') }}">
                                            <strong>REGISTER</strong>
                                        </a>
                                    </li>
                                    @endif
                                    <li class="nav-item" id="wrap">
                                        <form action="#" autocomplete="on">
                                            <input id="search" name="search" type="text" placeholder="What're we looking for ?">
                                        <a class="nav-link" href="#"><strong><i class="fa fa-search" aria-hidden="true"></i></strong></a>
                                        </form>
                                    </li>
                                    <li class="nav-item" id="slideBotton">
                                        <a class="nav-link" href="#"><i class="fa fa-bars" aria-hidden="true"></i></a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </nav>
                </div>
            </div>
            @if(Request::route()->getName() == 'dashboard')
                <div class="slide-header-navbar">
                    <div class="text-center">
                        <div class="contant-text wow slideInLeft" data-wow-delay="0.1s">
                            <h1>
                                A RELIABLE WAY TO TRAVEL
                            </h1>
                        </div>
                        <div class="contant-text wow zoomIn" data-wow-delay="0.1s">
                            <p>
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quare conare,<br> quaeso. Idemque diviserunt naturam hominis in animum et corpus.
                            </p>
                        </div>
                        <div class=" wow fadeInDownBig" data-wow-delay="0.1s">
                            <button class="btn btn-primary">READ MORE</button>
                        </div>
                    </div>
                    <div class="text-center">
                        <div class="contant-text wow slideInLeft" data-wow-delay="0.1s">
                            <h1>
                                A RELIABLE WAY TO TRAVEL
                            </h1>
                        </div>
                        <div class="contant-text wow zoomIn" data-wow-delay="0.1s">
                            <p>
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quare conare,<br> quaeso. Idemque diviserunt naturam hominis in animum et corpus.
                            </p>
                        </div>
                        <div class=" wow fadeInDownBig" data-wow-delay="0.1s">
                            <button class="btn btn-primary">READ MORE</button>
                        </div>
                    </div>
                </div>
                <div class="row wigets-row">
                    <div class="col-sm-4">
                        <input class="form-control mb-3" type="text" placeholder="Travel From">
                        <input class="form-control mb-3" type="text" placeholder="Travel To">
                    </div>
                    <div class="col-sm-5 mb-3">
                        <ul class="list-unstyled">
                            <li>
                                <a href="#">
                                    <div class="glyph-icon flaticon-coupe-car"></div>
                                    <strong>STANDARD</strong>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <div class="glyph-icon flaticon-car-black-side-view-pointing-left"></div>
                                    <strong>SUV</strong>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <div class="glyph-icon flaticon-minivan-car"></div>
                                    <strong>MINIVAN</strong>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-sm-3 text-center">
                        <div class="mb-3">
                            <h2>
                                $39.50
                            </h2>
                        </div>
                        <div class="clearfix">
                            <button class="btn btn-primary">BOOK MY RIDE</button>
                        </div>
                    </div>
                </div>
            @endif
            <!-- @if(Request::route()->getName() == 'customer.login')
                <div class="text-center about-test-1">
                    <div>
                        <h2>
                            <strong>
                                LOGIN
                            </strong>
                        </h2>
                    </div>
                    <div>
                        <a href="#">HOME /</a>
                        <span><a href="#">LOGIN</a> </span>
                    </div>
                </div>
            @endif -->
        </div>
    </section>


	@yield('content')


    <!-- start of Footer  aboutme ;-->
    <section class="footer">
        <div class="container">
            <div class="row">
                <div class="col-sm-3">
                    <div class="contant-image">
                        <!-- <img src="images/footerlogo.png"> -->
                        <a href="{{ route('dashboard') }}">
                            <img class="foot-app-logo" src="{{url('plugins/images/icon/logo.png')}}">
                            <h4 class="foot-app-name">Rideapp</h4>
                        </a>
                    </div>
                    <div class="contant-text">
                        Sed mehercule pergrata mihi oratio tua. Quodsi vultum tibi, si incessum fingeres, quo gravior viderere, non esses tui similis; Haec quo modo conveniant.
                    </div>

                </div>
                <div class="col-sm-3">
                    <div>
                        <h5>
                            Information
                        </h5>
                    </div>
                    <div>
                        <i class="fa fa-phone" aria-hidden="true"></i> <span>(123) 456-7890</span>
                    </div>
                    <div>
                        <i class="fa fa-envelope" aria-hidden="true"></i> <span>taxicab@gmail.com</span>
                    </div>
                    <div>
                        <i class="fa fa-map" aria-hidden="true"></i> <span>Badda Link Road, Dhaka1212</span>
                    </div>
                    <div>
                        <i class="fa fa-fax" aria-hidden="true"></i> <span>12356900297</span>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div>
                        <h5>
                            Useful Links
                        </h5>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <ul class="list-unstyled">
                                <li>
                                    <a href="#">
                                        <i class="fa fa-angle-right" aria-hidden="true"></i><span>About Us</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <i class="fa fa-angle-right" aria-hidden="true"></i><span>Documentation</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <i class="fa fa-angle-right" aria-hidden="true"></i><span>Feedback</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <i class="fa fa-angle-right" aria-hidden="true"></i><span>Support</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="col-sm-6">
                            <ul class="list-unstyled">
                                <li>
                                    <a href="#">
                                        <i class="fa fa-angle-right" aria-hidden="true"></i><span>Latest Blog</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <i class="fa fa-angle-right" aria-hidden="true"></i><span>HTML5</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <i class="fa fa-angle-right" aria-hidden="true"></i><span>Privacy Policy</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <i class="fa fa-angle-right" aria-hidden="true"></i><span>Terms</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div>
                        <h5>
                            Flickr Gallery
                        </h5>
                    </div>
                    <div class="contant-images">
                        <img src="images/footerimage.png">
                        <img src="images/footerimage.png">
                        <img src="images/footerimage.png">
                        <img src="images/footerimage.png">
                        <img src="images/footerimage.png">
                        <img src="images/footerimage.png">
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /. End of Footer -->
    <!-- start of Last-Footer : aboutme ;-->
    <section class="last-footer">
        <div class="container">
            <div class="text-center mb-5">
                <a href="#">
                    <i class="fa fa-facebook" aria-hidden="true"></i>
                </a>
                <a href="#">
                    <i class="fa fa-twitter" aria-hidden="true"></i>
                </a>
                <a href="#">
                    <i class="fa fa-linkedin" aria-hidden="true"></i>
                </a>
                <a href="#">
                    <i class="fa fa-pinterest-p" aria-hidden="true"></i>
                </a>
                <a href="#">
                    <i class="fa fa-google-plus" aria-hidden="true"></i>
                </a>
            </div>
            <div class="contant-border1 mb-4"></div>
            <div class="text-center">
                <!-- <span class="contant-text"> © 2017 Taxi Cab All Rights Reserved.   Designed By</span> <span>creativeON</span> -->
            </div>
        </div>
    </section>
    <!-- /. End of last-Footer-->

    <!-- </div> -->

<a id="scrollUp" href="#top"><i class="fa fa-angle-up"></i></a>
<script src="{{ url('js/customer/jquery.min.js') }}"></script>
<script src="{{ url('js/customer/tether.min.js') }}"></script>
<script src="{{ url('js/customer/bootstrap.min.js') }}"></script>
<script src="{{ url('css/customer/slick/slick.min.js') }}"></script>
<script src="{{ url('js/customer/scripts.js') }}"></script>
<script src="{{ url('js/customer/vendor/jquery-1.12.0.min.js') }}"></script>
<script src="{{ url('js/customer/owl.carousel.min.js') }}"></script>
<script src="{{ url('js/customer/jquery.meanmenu.js') }}"></script>
<script src="{{ url('js/customer/jquery-ui.min.js') }}"></script>
<script src="{{ url('js/customer/wow.min.js') }}"></script>
<script src="{{ url('js/customer/plugins.js') }}"></script>
<script src="{{ url('js/customer/jquery.nivo.slider.js') }}"></script>
<script src="{{ url('js/customer/jquery.nice-select.js') }}"></script>
<script src="{{ url('js/customer/main.js') }}"></script>
@stack('script')
</body>
</html>