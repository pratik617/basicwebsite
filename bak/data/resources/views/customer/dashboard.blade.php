@extends('layout.customer.app')
@section('title', 'Dashboard')
@section('content')

<div id="map-wrapper">
  <div id="map"></div>

  <div id="over_map">
    <div class="row address-box">

      <div class="col-md-1" style="padding-right: 0px;">
        <img src="{{ asset('images/route.png') }}" alt="" style="width: 100%; height: 100%;">
      </div>
      <div class="col-md-11">
        <div class="bg-dark">
            <input id="pickup_location" class="form-control form-control-dark w-100" type="text" placeholder="Pick Up Location" aria-label="Search">
        </div>
        <div class="bg-dark mt-2">
            <input id="destination" class="form-control form-control-dark w-100" type="text" placeholder="Destination" aria-label="Search">
        </div>
      </div>

    </div>

    <!--<div id="nearby_vehicle_types">-->
    <div id="nearby_vehicle_types" class="">
      <div class="row">
        <div class="col-md-11 offset-md-1">
          <ul id="nearby_vehicle_type_btns">

            <li>
              <a href="#">Car</a>
            </li>
            <li>
              <a href="#">Truck</a>
            </li>
            <li>
              <a href="#">Motorbike</a>
            </li>
          </ul>
        </div>
      </div>
      <div class="row">
        <div class="col-md-10">


          <div class="row">
            <div class="col-md-12">
              <ul id="nearby_vehicle_type_details">

                <li>
                  <div class="mb-1 font-weight-bold category_name">
                    407
                  </div>
                  <div class="">
                    <img src="{{ asset('images/Auto_x.png') }}" alt="" width="50">
                  </div>
                  <div class="price">
                    $ 153.00<br>07:01pm
                  </div>
                </li>
              </ul>
            </div>
          </div>
        </div>


        <div class="col-md-2">
          <div class="form-group">
            <select class="custom-select custom-select-sm" name="">
              <option value="" selected>Cash</option>
              <option value="">Wallet</option>
              <option value="">Paypal</option>
              <option value="">Mpesa</option>
            </select>
          </div>
          <div class="pr-2 text-right">
              <span data-feather="user"></span>
              <span class="ml-2" id="vehicle-person-capacity">0</span>
          </div>
          <hr>
          <div class="mt-3">
            <a href="#" class="btn btn-custom" id="btn-ride-book"></a>
          </div>
        </div>
      </div>
    </div>

    <!--</div>-->

    <!--
    <div id="nearby_vehicle_types">
      <ul id="nearby_vehicle_type_btns">
      </ul>

      <ul id="nearby_vehicle_type_details">
      </ul>
    </div>
    -->

    <div class="row title-box">
      <div class="col-md-12">
        <span>The most convenient way to get around your city. Enter destination to get fare esimates.</span>
      </div>
    </div>
  </div>
</div>

<div id="info"></div>
@endsection

@push('css')

@endpush

@push('header_scripts')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
@endpush

@push('footer_scripts')
<script type="text/javascript">
  var iconBase = '{{ url("images") }}';
  const axios_instance = axios.create({
    //baseURL: 'http://localhost:8000/api/v1/',
    baseURL: 'http://54.186.22.208/api/v1/',
    timeout: 10000,
    headers: {'Accept': 'application/json', 'Authorization': 'Bearer {{session("token")}}'}
  });
</script>

<script type="text/javascript" src="{{ asset('js/customer/dashboard.js') }}"></script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC6NJR2j3dlZdZNh_n7FZDghYULahe-eKM&libraries=places&callback=initMap"
async defer></script>

<script>
  $(document).ready(function() {

 });
</script>
@endpush
