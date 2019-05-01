@extends('layout.customer.simple')
@section('title', 'Customer forgot password - RideApp')
@section('content')
	
<section class="calculator">
    <div class="container">

	<div class="row">
		<div class="col-lg-6">
			@if(Session::has('success'))
			    <div class="alert alert-success alert-icon alert-close alert-dismissible" role="alert">
			        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
			            <span aria-hidden="true">×</span>
			        </button>
			        {{ Session::get('success') }}
			    </div>
			    @endif

			    @if(Session::has('error'))
			    <div class="alert alert-danger alert-icon alert-close alert-dismissible" role="alert">
			        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
			            <span aria-hidden="true">×</span>
			        </button>
			        {{ Session::get('error') }}
			    </div>
			@endif
		</div>
	</div>    	

	<form class="form-horizontal form-material" id="loginform" method="POST" action="{{ route('admin.forgot.sendlink') }}">
		@csrf
        {{-- @if(Session::has('error'))
          <div class="text-danger invalid-data">{{ Session::get('error') }}</div>
        @endif --}}
        <div class="form-group">
        	<div class="row">
        		<div class="col-lg-6">
        			<h4>Forgot Password</h4>		
        		</div>
        	</div>
			<div class="row">
				<div class="col-sm-6">
				  <div class="inner-addon left-addon">
				    <i class="glyphicon glyphicon-envelope"></i>
				    <input class="form-control" type="text" placeholder="Email" id="email_id" maxlength="60" name="email" value="{{ old('email') }}" required autofocus>
				  </div>
				  @if ($errors->has('email'))
				      <span class="invalid-feedback" role="alert">
				          <strong>{{ $errors->first('email') }}</strong>
				      </span>
				  @endif
				  <div id="emailError" class="text-danger"></div>
				</div>
			</div>
          <br>  
          <button class="btn btn-info btn-md text-uppercas" type="submit" id="save_button_id">Send Mail</button>
        </div>
	</form>

	</div>
</div>

@endsection
@push('css')
<style type="text/css">
	.site-logo{
		width: 250px;
	}
	.calculator {
	    padding: 50px 0px;
	}
</style>
@endpush
@push('script')
<script src="{{url('js/admin/forgot-script.js')}}" type="text/javascript" ></script>
<script type="text/javascript">
    $(document).ready(function(){
    });
</script>
@endpush

	
	