@extends('layout.customer.auth')
@section('title', 'Registration')

@section('content')

  <section class="calculator">
      <div class="container">

          <form method="POST" action="{{ route('customer.register') }}" autocomplete="off">
              @csrf
              <div class="row mb-5">
                  <div class="col-sm-3">
                  </div>
                  <div class="col-sm-6">
                      <div class="text-center header-text1 mb-3">
                          <h4>Registration Form</h4>
                      </div>
                      @include('inc.messages')
                      @if(session()->get('s_id'))
                        <input type="hidden" name="s_id" value="{{ session()->get('s_id') }}">
                        <input type="hidden" name="s_type" value="{{ session()->get('s_type') }}">
                      @endif
                      <div class="calculator-iteam">

                          <div class="form-group">
                              <!-- User Name -->
                              <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ (session()->get('s_name'))?session()->get('s_name'):old('name') }}" placeholder="Name" autofocus>
                              @if ($errors->has('name'))
                                  <span class="invalid-feedback" role="alert">
                                      <strong>{{ $errors->first('name') }}</strong>
                                  </span>
                              @endif
                          </div>

                          <div class="form-group">
                            <!-- User Email -->
                            <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ (session()->get('s_email'))?session()->get('s_email'):old('email') }}" placeholder="Email">
                            @if ($errors->has('email'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                          </div>

                          <div class="form-group">
                            <input id="phone" type="tel" class="form-control" style="width:100%;">
                          </div>

                            <!-- User Contaic -->
                            {{--
                            <div class="box">
                                <select name="country_code" class=" wide mb-3">
                                    @foreach($country as $data)
                                        <option value="{{ $data->phone_code }}" name="{{ $data->code }}" {{ (old('country_code') == $data->phone_code)?'selected':'' }}>{{ $data->phone_code}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="contact_box">
                              <div class="form-group">
                                <input id="contact_no" type="contact_no" class="form-control{{ $errors->has('contact_no') ? ' is-invalid' : '' }}" name="contact_no" value="{{ old('contact_no') }}" placeholder="Contact Number" maxlength="15">
                                @if ($errors->has('contact_no'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('contact_no') }}</strong>
                                    </span>
                                @endif
                            </div>

                            </div>
                          --}}
                          <div class="form-group">
                            <!-- User Password  -->
                            <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }} " name="password_confirmation" placeholder="Password">
                            @if ($errors->has('password'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            @endif
                          </div>

                          <div class="form-group">
                            <!-- User Confirm Password -->
                            <input id="password-confirm" type="password" class="form-control" name="password" placeholder="Confirm Password">
                          </div>


                      </div>
                      <div class="text-center mt-3">
                          <!-- Register Button -->
                          <input type="submit" class="btn btn-custome" value="REGISTER">
                      </div>

                      <div class="text-center font-weight-bold text-muted mt-3 mb-3">
                        or
                      </div>
                      <div class="row">
                        <div class="col-md-12 text-center">
                          <a href="{{ route('register.google') }}" class="btn-social btn-google text-center"></a>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-12 text-center">
                          <a href="{{ route('register.facebook') }}" class="btn-social btn-facebook"></a>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-12 text-center">
                          <a href="{{ route('register.linkedin') }}" class="btn-social btn-linkedin"></a>
                        </div>
                      </div>

                  </div>
                  <div class="col-sm-3">
                  </div>
              </div>
          </form>

      </div>
  </section>

@endsection

@push('css')
<link href="//cdnjs.cloudflare.com/ajax/libs/jquery-form-validator/2.3.26/theme-default.min.css"
    rel="stylesheet" type="text/css" />

<style type="text/css">
.box {
    width: 20%;
}
.contact_box{
    width: 80%;
    float: left;
}
.nice-select.wide .list {
    overflow-y: scroll;
    max-height: 235px;
    width: 213px;
}
</style>
@endpush


@push('header_scripts')
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-form-validator/2.3.26/jquery.form-validator.min.js"></script>

@endpush

@push('footer_scripts')

<!--<script type="text/javascript" src="https://js.maxmind.com/js/apis/geoip2/v2.1/geoip2.js"></script>-->
<script src="{{ asset('js/customer/intlTelInput.min.js') }}"></script>
<script type="text/javascript">
  $(document).ready(function() {
    var input = document.querySelector("#phone");
    //window.intlTelInput(input);
    window.intlTelInput(input, {
      initialCountry: "auto",
      geoIpLookup: function(callback) {

        $.get('https://ipinfo.io', function() {}, "jsonp").always(function(resp) {
          var countryCode = (resp && resp.country) ? resp.country : "";
          callback(countryCode);
        });

      },
      //utilsScript: "http://localhost:8000/js/utils.js?1551697588835" // just for formatting/placeholders etc
    });

  });


  /*
  $(document).ready(function() {
    var input = document.querySelector("#phone");
    window.intlTelInput(input, {
      initialCountry: "auto",
      geoIpLookup: function(callback) {
        $.get('https://ipinfo.io', function() {}, "jsonp").always(function(resp) {
          var countryCode = (resp && resp.country) ? resp.country : "";
          callback(countryCode);
        });
      },
      utilsScript: "js/customer/utils.js" // just for formatting/placeholders etc
    });

  });
  */


//}

</script>

@endpush
