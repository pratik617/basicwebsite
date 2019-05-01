<!-- ============================================================== -->
<!-- Topbar header - style you can find in pages.scss -->
<!-- ============================================================== -->
<nav class="navbar navbar-default navbar-static-top m-b-0">
    <div class="navbar-header">
        <div class="top-left-part">
            <!-- Logo -->
            <a class="logo" href="{{ route('admin.dashboard') }}">

                <img src="{{ asset('images/logo.png') }}" alt="home" class="light-logo" />
                <!-- Logo icon image, you can use font-icon also --><b>
             </b>
                <!-- Logo text image you can use text also --><span class="hidden-xs">
                <!--This is dark logo text-->
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
                <a class="dropdown-toggle profile-pic" data-toggle="dropdown" href="#"> <img src="{{ asset('images/user.png') }}" alt="user-img" width="36" class="img-circle"><b class="hidden-xs">{{-- Auth::user()->name --}}</b><span class="caret"></span> </a>
                <ul class="dropdown-menu dropdown-user animated flipInY">
                    <li>
                        <div class="dw-user-box">
                            <div class="u-img"><img src="{{ asset('images/user.png') }}" alt="{{-- Auth::guard('admin')->email --}}" /></div>
                            <div class="u-text">
                              <h4>{{-- Auth::guard('admin')->user()->name --}}</h4>
                              <p class="text-muted">{{-- Auth::guard('admin')->user()->email --}}</p></div>
                        </div>
                    </li>
                    <li role="separator" class="divider"></li>
                    <li>
                      <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fa fa-power-off"></i> {{ __('Logout') }}
                      </a>
                      <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
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
