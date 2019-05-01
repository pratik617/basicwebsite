@extends('layout.customer.layout')

@section('title', 'Customer Registration - RideApp')
@section('content')

<!-- <section class="navbar-slider">
    <div class="container">
        <div class="row nav-row-slidre">
            <div class="col-sm-12">
                <nav class="navbar navbar-toggleable-md navbar-expand-lg">
                    <div class="col-sm-3"><img class="app-logo" src="{{url('plugins/images/icon/logo.png')}}"><h4 class="app-name">Rideapp</h4></div>
                    <button class="navbar-toggler navbar-toggler-right navbar-light" type="button" data-toggle="collapse"
                            data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                            aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse main-menu" id="navbarSupportedContent">
                        <ul class="navbar-nav mr-auto">
                            <li class="nav-item active">
                                <a class="nav-link" href="index.html"><strong>HOME</strong><span class="sr-only">(current</span></a>
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
                            <li class="nav-item">
                                <a class="nav-link" href="ourfleet.html"><strong>FLEET</strong></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="caculator.html"><strong>CALCULATOR</strong></a>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link" href="blog.html" id="dropdownMenuButton1" data-toggle="dropdown"><strong>BLOG+</strong></a>
                                <ul class="sub-menu list-unstyled"  aria-labelledby="dropdownMenuButton1">
                                    <li><a href="blog.html">BLOG</a></li>
                                    <li><a href="singleblog.html">SINGLE BLOG</a></li>
                                </ul>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link" href="#" id="dropdownMenuButton2" data-toggle="dropdown"><strong>PAGES+</strong></a>
                                <ul class="sub-menu list-unstyled" aria-labelledby="dropdownMenuButton2">
                                    <li><a href="booking.html">Booking</a></li>
                                    <li><a href="404.html">ERROR</a></li>
                                </ul>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="contactus.html"><strong>CONTACT</strong></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('customer.login') }}"><strong>LOGIN</strong></a>
                            </li>
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
                </nav>
            </div>
        </div>
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
    </div>
</section> -->

<div class="container">
    <h1>Homepage</h1>
</div>

@endsection
@push('css')
<style type="text/css">
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
</style>
@endpush
@push('script')
<script type="text/javascript">
    $(document).ready(function(){

    });
</script>
@endpush