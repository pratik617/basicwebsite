@extends('layout.customer.layout')

@section('title', 'Customer Registration - RideApp')
@section('content')

<div class="container">
	<h2>Verification</h2>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
            	<br>
                <div class="card-body">
                    <form method="POST" action="{{ route('customer.otp.verification') }}">
                        @csrf                        

                        <div class="form-group row">
                            <label for="verification_otp" class="col-md-4 col-form-label text-md-right">OTP</label>

                            <div class="col-md-6">
                                <input id="verification_otp" type="verification_otp" class="form-control{{ $errors->has('verification_otp') ? ' is-invalid' : '' }}" name="verification_otp" required maxlength="6">

                                @if ($errors->has('verification_otp'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('verification_otp') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    Send
                                </button>
                            </div>
                        </div>
                    </form>

                </div>

            </div>
        </div>
    </div>
</div>

@endsection
@push('css')
<style type="text/css">
</style>
@endpush
@push('script')
<script type="text/javascript">
    $(document).ready(function(){

    });
</script>
@endpush
