@extends('layout.customer.simple')
@section('title', 'Customer forgot password - RideApp')
@section('content')

<section class="calculator">
    <div class="container">
    	<!-- <center><h1>Forgot Password</h1></center> -->
    	@if(isset($token) && !empty($token) )
        <form method="POST" action="{{ route('driver.password.save') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <div class="row mb-5">
                <div class="col-sm-3">
                </div>
                <div class="col-sm-6">
                    <div class="text-center header-text1 mb-3">
                        <h4>Set New Password</h4>
                    </div>
                    <div class="calculator-iteam">
                        <input class="form-control mb-3" name="password" id="password_id" value="" type="text" placeholder="New Password *">
                        @if ($errors->has('password'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                        <input class="form-control" name="cpassword" id="cpassword_id" type="password" placeholder="Confirm Password *">
                        @if ($errors->has('cpassword'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('cpassword') }}</strong>
                            </span>
                        @endif
                    </div>
                    @if(Session::has('error'))
                      <div class="text-danger invalid-data">{{ Session::get('error') }}</div>
                    <br>
                    @endif
                    <div class="text-center mt-3">
                        <input type="submit" class="btn btn-custome" name="SAVE" value="SAVE">
                    </div>
                </div>
                <div class="col-sm-3">
                </div>
            </div>
        </form>
        @else
        	<h3 class="text-danger">Link expired.</h3>
        @endif
    </div>
</section>


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