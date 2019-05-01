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

    <title>@yield('title')</title>
    <!-- Bootstrap Core CSS -->
    <link href="{{url('bootstrap/dist/css/bootstrap.min.css')}}" rel="stylesheet">
    <!-- Menu CSS -->
    <link href="{{url('plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.css')}}" rel="stylesheet">
    <!-- toast CSS -->
    <link href="{{url('plugins/bower_components/toast-master/css/jquery.toast.css')}}" rel="stylesheet">
    <!-- morris CSS -->
    <link href="{{url('plugins/bower_components/morrisjs/morris.css')}}" rel="stylesheet">
    <!-- chartist CSS -->
    <link href="{{url('plugins/bower_components/chartist-js/dist/chartist.min.css')}}" rel="stylesheet">
    <link href="{{url('plugins/bower_components/chartist-plugin-tooltip-master/dist/chartist-plugin-tooltip.css')}}" rel="stylesheet">
    <!-- Calendar CSS -->
    <link href="{{url('plugins/bower_components/calendar/dist/fullcalendar.css')}}" rel="stylesheet" />
    <!-- animation CSS -->
    <link href="{{url('css/animate.css')}}" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="{{url('css/style.css')}}" rel="stylesheet">
    <!-- color CSS -->
    <link href="{{url('css/colors/default.css')}}" id="theme" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style type="text/css">
    .light-logo{
        width: 31px;
    }
    .dark-logo{
        width: 31px;
    }
    </style>

    @stack('css')
</head>

<body class="fix-header">
    <!-- ============================================================== -->
    <!-- Preloader -->
    <!-- ============================================================== -->
    <div class="preloader">
        <svg class="circular" viewBox="25 25 50 50">
            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" />
        </svg>
    </div>

    <div id="wrapper">
        <!-- ============================================================== -->
        <!-- Topbar header - style you can find in pages.scss -->
        <!-- ============================================================== -->
        <nav class="navbar navbar-default navbar-static-top m-b-0">
            <div class="navbar-header">
                <div class="top-left-part">
                    <!-- Logo -->
                    <a class="logo" href="{{ route('company.dashboard') }}">
                        <b>
                            <img src="{{url('plugins/images/icon/logo.png')}}" alt="home" class="dark-logo" />
                            <img src="{{url('plugins/images/icon/logo.png')}}" alt="home" class="light-logo" />
                        </b>
                        <span class="hidden-xs black" >
                            RideApp
                            <!-- <img src="{{url('plugins/images/admin-text.png')}}" alt="home" class="dark-logo" />
                            <img src="{{url('plugins/images/admin-text-dark.png')}}" alt="home" class="light-logo" /> -->
                        </span>
                    </a>
                </div>
                <!-- /Logo -->
                <!-- Search input and Toggle icon -->
                <ul class="nav navbar-top-links navbar-left">
                    <li>
                        <a href="javascript:void(0)" class="open-close waves-effect waves-light">
                            <i class="ti-menu"></i>
                        </a>
                    </li>
                </ul>
                <ul class="nav navbar-top-links navbar-right pull-right">
                    <li class="dropdown">
                        <a class="dropdown-toggle profile-pic" data-toggle="dropdown" href="#">
                            <img src="@if(Auth::guard('company_admin')->user()->profile!='')
                                                {{ url(Auth::guard('company_admin')->user()->profile) }}
                                            @else
                                                {{ url('plugins/images/icon/boy1.png') }}
                                            @endif" alt="user-img" width="36" class="img-circle">
                            <b class="hidden-xs">{{ Auth::guard('company_admin')->user()->firstname }}</b>
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu dropdown-user animated flipInY">
                            <li>
                                <div class="dw-user-box">
                                    <div class="u-img">
                                        <img src="@if(Auth::guard('company_admin')->user()->profile!='')
                                                {{ url(Auth::guard('company_admin')->user()->profile) }}
                                            @else
                                                {{ url('plugins/images/icon/boy1.png') }}
                                            @endif" alt="user" />
                                    </div>
                                    <div class="u-text">
                                        <h4>{{ Auth::guard('company_admin')->user()->firstname.' '.Auth::guard('company_admin')->user()->lastname }}</h4>
                                        <p class="text-muted">{{ Auth::guard('company_admin')->user()->email }}</p><a href="profile.html" class="btn btn-rounded btn-danger btn-sm">View Profile</a>
                                    </div>
                                </div>
                            </li>
                            <li><a href="{{ route('company.logout') }}"><i class="fa fa-power-off"></i> Logout</a></li>
                        </ul>
                        <!-- /.dropdown-user -->
                    </li>
                    <!-- /.dropdown -->
                </ul>
            </div>
        </nav>

        <!-- Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <div class="navbar-default sidebar" role="navigation">
            <div class="sidebar-nav slimscrollsidebar">
                <div class="sidebar-head">
                    <h3>
                        <span class="fa-fw open-close"><i class="ti-close ti-menu"></i></span>
                        <span class="hide-menu">Navigation</span>
                    </h3>
                </div>
                <ul class="nav" id="side-menu">
                    <li class="active">
                        <a href="{{ route('company.dashboard') }}" class="waves-effect
                        @if(Request::segment(2) == '' || Request::segment(2) == 'company' || Request::segment(2) == 'dashboard' )
                            active
                        @endif">
                            <i class="mdi mdi-av-timer fa-fw"></i>
                            <span class="hide-menu">Dashboard</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('company.driver') }}" class="waves-effect
                        @if(Request::segment(2) == 'driver' || Request::segment(2) == 'add-driver' )
                            active
                        @endif
                        ">
                            <i class="mdi mdi-car fa-fw"></i>
                            <span class="hide-menu">Driver</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('company.company-profile') }}" class="waves-effect
                        @if( Request::segment(2) == 'company-profile' )
                            active
                        @endif
                        ">
                            <i class="mdi mdi-factory fa-fw"></i>
                            <span class="hide-menu">Company Profile</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('company.customer_feedback') }}" class="waves-effect
                        @if(Request::segment(2) == 'customer-feedback')
                            active
                        @endif
                        ">
                            <i class="mdi mdi-message-text fa-fw"></i>
                            <span class="hide-menu">Customer Feedback</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('company.cabs') }}" class="waves-effect
                        @if(Request::segment(2) == 'cabs')
                            active
                        @endif
                        ">
                            <i class="mdi mdi-car-connected fa-fw"></i>
                            <span class="hide-menu">Cabs</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('company.reports') }}" class="waves-effect
                        @if(Request::segment(2) == 'reports')
                            active
                        @endif
                        ">
                            <i class="mdi mdi-file-document-box fa-fw"></i>
                            <span class="hide-menu">Reports</span>
                        </a>
                    </li>


                    <!-- DROP DOWN -->
                    <!-- <li>
                        <a href="#" class="waves-effect">
                            <i class="mdi mdi-emoticon fa-fw"></i>
                            <span class="hide-menu">Icons
                                <span class="fa arrow"></span>
                            </span>
                        </a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="fontawesome.html"><i class="fa-fw">F</i>
                                    <span class="hide-menu">Font awesome</span>
                                </a>
                            </li>
                            <li>
                                <a href="themifyicon.html"><i class="fa-fw">T</i>
                                    <span class="hide-menu">Themify Icons</span>
                                </a>
                            </li>
                            <li>
                                <a href="simple-line.html"><i class="fa-fw">S</i>
                                    <span class="hide-menu">Simple line Icons</span>
                                </a>
                            </li>
                            <li>
                                <a href="material-icons.html"><i class="fa-fw">M</i>
                                    <span class="hide-menu">Material Icons</span>
                                </a>
                            </li>
                            <li>
                                <a href="linea-icon.html"><i class="fa-fw">L</i>
                                    <span class="hide-menu">Linea Icons</span>
                                </a>
                            </li>
                            <li>
                                <a href="weather.html"><i class="fa-fw">W</i>
                                    <span class="hide-menu">Weather Icons</span>
                                </a>
                            </li>
                        </ul>
                    </li> -->
                </ul>
            </div>
        </div>

        <!-- Page Content -->
        <!-- ============================================================== -->
        <div id="page-wrapper">
            <div class="container-fluid">
                <!-- <div class="row bg-title">
                    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                        <h4 class="page-title">@yield('page-title')</h4>
                    </div>
                    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                        <ol class="breadcrumb">
                            <li><a href="{{ url('home') }}">Dashboard</a></li>
                            @yield('head-breadcrumb')
                            <li class="active">Inbox</li>
                        </ol>
                    </div>
                </div> -->

                <!-- Main Content -->
                @yield('content')

            </div>
        </div>
    </div>

    <!-- All Jquery -->
    <!-- ============================================================== -->
    <script src="{{url('plugins/bower_components/jquery/dist/jquery.min.js')}}"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="{{url('bootstrap/dist/js/bootstrap.min.js')}}"></script>
    <!-- Menu Plugin JavaScript -->
    <script src="{{url('plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.js')}}"></script>
    <!--slimscroll JavaScript -->
    <script src="{{url('js/jquery.slimscroll.js')}}"></script>
    <!--Wave Effects -->
    <script src="{{url('js/waves.js')}}"></script>
    <!-- chartist chart -->
    <script src="{{url('plugins/bower_components/chartist-js/dist/chartist.min.js')}}"></script>
    <script src="{{url('plugins/bower_components/chartist-plugin-tooltip-master/dist/chartist-plugin-tooltip.min.js')}}"></script>
    <!-- Sparkline chart JavaScript -->
    <script src="{{url('plugins/bower_components/jquery-sparkline/jquery.sparkline.min.js')}}"></script>
    <!-- Custom Theme JavaScript -->
    <script src="{{url('js/custom.min.js')}}"></script>
    <script src="{{url('js/dashboard1.js')}}"></script>
    <script src="{{url('plugins/bower_components/toast-master/js/jquery.toast.js')}}"></script>
    <!--Style Switcher -->
    <script src="{{url('plugins/bower_components/styleswitcher/jQuery.style.switcher.js')}}"></script>

    <!-- DATEPICKER -->
    <script src="{{url('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>

    @stack('script')

</body>
</html>
