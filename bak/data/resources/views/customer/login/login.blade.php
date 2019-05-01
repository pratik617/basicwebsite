@extends('layout.customer.layout')

@section('title', 'Customer Login - RideApp')
@section('content')

<section class="calculator">
    <div class="container">
        <form method="POST" action="{{ route('customer.login') }}" autocomplete="off">
            @csrf
            <div class="row mb-5">
                <div class="col-sm-3">
                </div>
                <div class="col-sm-6">
                    <div class="text-center header-text1 mb-3">
                        <h4>Login Form</h4>
                    </div>
                    @include('inc.messages')
                    {{--
                    @if(Session::has('error'))
                      <div class="text-danger invalid-data text-center">{{ Session::get('error') }}</div>
                    <br>
                    @endif
                    --}}
                    <div class="calculator-iteam">
                        <div class="form-group">
                          <input class="form-control" name="email" value="{{ old('email') }}" type="text" placeholder="Email Address *" data-validation="required|email" data-validation-error-msg-required="Please enter your email address" data-validation-error-msg-email="Please enter correct email address">
                          {{--
                          @if ($errors->has('email'))
                              <span class="invalid-feedback" role="alert">
                                  <strong>{{ $errors->first('email') }}</strong>
                              </span>
                          @endif
                          --}}
                        </div>

                        <div class="form-group">
                          <input class="form-control" name="password" type="password" placeholder="Password *" data-validation="required" data-validation-error-msg-required="Please enter your password">
                          {{--
                          @if ($errors->has('password'))
                              <span class="invalid-feedback" role="alert">
                                  <strong>{{ $errors->first('password') }}</strong>
                              </span>
                          @endif
                          --}}
                        </div>
                    </div>
                    <div class="text-center mt-3">
                        <input type="submit" class="btn btn-custome" name="LOGIN" value="LOGIN">
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

</style>
@endpush
@push('script')
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-form-validator/2.3.26/jquery.form-validator.min.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
      $.validate({

      });

    });
</script>
@endpush
