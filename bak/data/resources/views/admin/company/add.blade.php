@extends('layout.admin.layout')
@if(isset($company->id))
	@section('title', 'Edit Company - RideApp Management')
@else
	@section('title', 'Add Company - RideApp Management')
@endif
@section('content')
<br>
<div class="row">
    <div class="col-lg-12">
        <div class="white-box">
            <div class="card-body">
            	<div class="display-block">
	            	<center>
	            		<i class="mdi mdi-factory fa-fw factory-icon"></i>
	            		<h2 class="company-title"> 
		            		@if(isset($company->id))
		            			Edit Company
	            			@else
	            				Add Company
	            			@endif
		            	</h2>
		            </center>
	            	<hr class="hr-line">
                </div>

                <div class="row">
                	<div class="col-lg-6 col-lg-offset-3">

                		<form action="{{ route('admin.store.company') }}" method="post" enctype="multipart/form-data">
                			@csrf
                			@if(isset($company->id))
		            			<input type="hidden" name="id" value="{{ $company->id }}">
		            		@else
		            			<input type="hidden" name="id" value="">
	            			@endif
	                		<div class="form-group">

	                            <label class="col-lg-12 form-title">Name</label>
	                            <div class="col-lg-12">
	                                <input type="text" class="form-control" placeholder="Name" id="company_name_id" name="company_name" value="@if(isset($company->name)){{ $company->name }}@elseif(old('company_name')!=''){{ old('company_name') }} @endif"> 
		                            @if ($errors->has('company_name'))
						                <span class="text-danger">{{ $errors->first('company_name') }}</span>
						            @endif
	                            </div>

	                            <label class="col-lg-12 form-title">Email</label>
	                            <div class="col-lg-12">
	                                <input type="text" class="form-control" placeholder="Email" id="company_email_id" name="company_email" value="@if(isset($company->email)){{ $company->email }}@elseif(old('company_email')!=''){{ old('company_email') }} @endif"> 
	                                @if ($errors->has('company_email'))
						                <span class="text-danger">{{ $errors->first('company_email') }}</span>
						            @endif
	                            </div>

	                                <label class="col-lg-12 form-title">Contact No </label>
	                            <div class="col-lg-3 contact-code">
                                 <select class="form-control minimal" data-placeholder="" tabindex="1" name="company_contact_code">
	                	        	        @if(count($country))
		                                	<option value="">Select Country Code</option>
		                                 @endif
		                                 @foreach($country as $data)

		                                    	<option value="{{ $data->phone_code }}"
		                                		@if(old('country_code') == $data->phone_code)
		                                			selected="true"
		                                		@endif
		                                		@if(isset($company->country_code) && $data->phone_code == $company->country_code)
		                                			selected="true"
		                                		@endif
		                                		>{{ $data->phone_code }}</option>
                                        @endforeach
                                     </select> 
	                            </div>
	                            <div class="col-lg-9 contact-number">
	                                <input type="text" class="form-control" placeholder="Number" id="mobile_no" name="company_contact_number" value="@if(isset($company->contact_no)){{ $company->contact_no }}@elseif(old('contact_no')!=''){{ old('contact_no') }} @endif" maxlength="12"> 
	                            </div>

                     			<div class="col-lg-12">
		                            @if ($errors->has('country_code'))
						                <span class="text-danger">{{ $errors->first('company_contact_code') }}</span>
						            @elseif ($errors->has('contact_no'))
						                <span class="text-danger">{{ $errors->first('company_contact_number') }}</span>
						            @endif
					      </div>


	                            <label class="col-lg-12 form-title">City</label>
	                            <div class="col-lg-12 ">
	                                <input type="text" class="form-control" placeholder="City" id="company_city_id" name="company_city" value="@if(isset($company->city)){{ $company->city }}@elseif(old('company_city')!=''){{ old('company_city') }} @endif"> 
	                                @if ($errors->has('company_city'))
						                <span class="text-danger">{{ $errors->first('company_city') }}</span>
						            @endif
	                            </div>
	                            
								<label class="col-lg-12 form-title">Address</label>
	                            <div class="col-lg-12 ">
	                                <input type="text" class="form-control" placeholder="Address" id="company_address_id" name="company_address" value="@if(isset($company->address)){{ $company->address }}@elseif(old('company_address')!=''){{ old('company_address') }} @endif" > 
	                                @if($errors->has('company_address'))
						                <span class="text-danger">{{ $errors->first('company_address') }}</span>
						            @endif
	                            </div>
	                            
	                            <!-- Company Active In-active  -->
	                        <!--<label class="col-lg-12">Status</label>
	                            <div class="col-lg-12 ">
		                            <div class="switch-field">
		                              <input type="radio" id="switch_3_left" name="switch_3" value="yes" checked="">
		                              <label for="switch_3_left">ACTIVE</label>
		                              <input type="radio" id="switch_3_center" name="switch_3" value="maybe">
		                              <label for="switch_3_center">IN-ACTIVE</label>
		                            </div>
		                        </div>
	                        -->

	                        	<label class="col-lg-12 form-title">Logo</label>
	                            <div class="col-lg-12 ">
	                                <input type="file" class="form-control company-logo" id="company_logo_id" name="company_logo"> 
	                                @if(isset($company->logo) && !empty($company->logo))
	                                	<img src="{{ url($company->logo) }}" width="100px" height="auto" style="margin-top: 10px;">
                                	@endif
	                                @if ($errors->has('company_logo'))
						                <span class="text-danger">{{ $errors->first('company_logo') }}</span>
						            @endif
	                            </div>

	                            <div class="col-lg-12 form-submit ">
		                            <input type="submit" class="btn btn-info col-lg-8" name="" value="SAVE">
		                            <input type="reset" class="btn btn-default col-lg-3 col-lg-offset-1" name="" value="RESET">
		                        </div>

	                        </div>

                    	</form>
                	</div>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection
@push('css')
<style type="text/css">
	.contact-code{
		padding-right: 0px;
	}
	.contact-number{
		padding-left: 5px;
	}
	.form-title {
		margin-top: 10px;
	}
	.form-submit {
		padding-top: 30px;
	}
	.factory-icon{
		font-size: 25px;
		padding-right: 35px;
	}
	.company-title{
		display: inline-block;
	}
	.hr-line{
		width: 65%;
		margin-bottom: 0px;
	}

	/* Active in-active Radio Button */
	.switch-field {
	  font-family: "Lucida Grande", Tahoma, Verdana, sans-serif;
	  padding: 0px;
		overflow: hidden;
	  width: 101%;
	}

	.switch-title {
	  margin-bottom: 6px;
	}

	.switch-field input {
	    position: absolute !important;
	    clip: rect(0, 0, 0, 0);
	    height: 1px;
	    width: 1px;
	    border: 0;
	    overflow: hidden;
	}

	.switch-field label {
	  float: left;
	}

	.switch-field label {
	  display: inline-block;
	  width: 50%;
	  height: 36px;
	  background-color: #fff;
	  color: rgba(0, 0, 0, 0.6);
	  font-size: 14px;
	  font-weight: normal;
	  text-align: center;
	  text-shadow: none;
	  padding: 7px 14px;
	  border: 1px solid rgba(0, 0, 0, 0.2);
	  /*-webkit-box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.3), 0 1px rgba(255, 255, 255, 0.1);
	  box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.3), 0 1px rgba(255, 255, 255, 0.1);
	  -webkit-transition: all 0.1s ease-in-out;
	  -moz-transition:    all 0.1s ease-in-out;
	  -ms-transition:     all 0.1s ease-in-out;
	  -o-transition:      all 0.1s ease-in-out;*/
	  transition:         all 0.1s ease-in-out;
	}

	.switch-field label:hover {
		cursor: pointer;
	}

	/*.switch-field input:checked + label {
	  background-color: #41b3f9;
	  -webkit-box-shadow: none;
	  box-shadow: none;
	  color: white;
	}
	*/
	.switch-field input:checked + label {
	  background-color: #41b3f9;
	  -webkit-box-shadow: none;
	  box-shadow: none;
	  color: white;
	  width: 50%;
	  height: 36px;
	}

	.switch-field label:first-of-type {
	  border-radius: 4px 0 0 4px;
	}

	.switch-field label:last-of-type {
	  border-radius: 0 4px 4px 0;
	}
	.company-logo{
		padding-top: 0px;
		height: 38px;
	}

</style>
@endpush
@push('script')
<script type="text/javascript">
	
</script>
@endpush