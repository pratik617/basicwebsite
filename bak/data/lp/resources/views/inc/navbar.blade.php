<!-- ============================================================== -->
<!-- Topbar header - style you can find in pages.scss -->
<!-- ============================================================== -->
<nav class="navbar navbar-default navbar-static-top m-b-0">
    <div class="navbar-header">
        <div class="top-left-part">
            <!-- Logo -->
            <a class="logo" href="{{ route('dashboard') }}">

                <img src="{{ asset('plugins/images/logo.png') }}" alt="home" class="light-logo" />
                <!-- Logo icon image, you can use font-icon also --><b>

                <!--This is dark logo icon--><img src="{{ asset('plugins/images/logo.png') }}" alt="home" class="dark-logo" /><!--This is light logo icon-->
                <!--<img src="{{ asset('plugins/images/admin-logo-dark.png') }}" alt="home" class="light-logo" />-->

             </b>
                <!-- Logo text image you can use text also --><span class="hidden-xs">
                <!--This is dark logo text-->
                <img src="{{ asset('plugins/images/admin-text.png') }}" alt="home" class="dark-logo" /><!--This is light logo text-->
                <!--<img src="{{ asset('plugins/images/admin-text-dark.png') }}" alt="home" class="light-logo" />-->
             </span> </a>
        </div>
        <!-- /Logo -->
        <!-- Search input and Toggle icon -->
        <ul class="nav navbar-top-links navbar-left">
            <li><a href="javascript:void(0)" class="open-close waves-effect waves-light"><i class="ti-menu"></i></a></li>
        </ul>
        <ul class="nav navbar-top-links navbar-right pull-right">
            <li class="dropdown">
                <a class="dropdown-toggle profile-pic" data-toggle="dropdown" href="#"> <img src="{{ asset('plugins/images/585e4bf3cb11b227491c339a.png') }}" alt="user-img" width="36" class="img-circle"><b class="hidden-xs">{{ Auth::user()->name }}</b><span class="caret"></span> </a>
                <ul class="dropdown-menu dropdown-user animated flipInY">
                    <li>
                        <div class="dw-user-box">
                            <div class="u-img"><img src="{{ asset('plugins/images/585e4bf3cb11b227491c339a.png') }}" alt="{{ Auth::user()->name }}" /></div>
                            <div class="u-text">
                                <h4>{{ Auth::user()->name }}</h4>
                                <p class="text-muted">{{ Auth::user()->email }}</p></div>
                        </div>
                    </li>
                    <li role="separator" class="divider"></li>
                    <li>
                      <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fa fa-power-off"></i> {{ __('Logout') }}
                      </a>
                      <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                          @csrf
                      </form>
                    </li>
                </ul>
                <!-- /.dropdown-user -->
            </li>
            <!-- /.dropdown -->
        </ul>
    </div>
    <!-- /.navbar-header -->
    <!-- /.navbar-top-links -->
    <!-- /.navbar-static-side -->
</nav>
<!-- End Top Navigation -->
