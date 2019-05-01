@extends('layout.admin.layout')
@section('title', 'Custome - RideApp Management')
@section('content')
<br>
<div class="row">
     <div class="col-lg-12">
        <div class="white-box">
            <div class="card-body">
            	<div class="display-block">
            		<center>
            		<i class="mdi mdi-account"></i>
	            	@if(isset($profile->id))
	            	   Edit Admin Profile
	            	 @else
	            	   Add Admin Profile 
	            	@endif
	            </center>
	            <hr class="hr-line">
                </div>
                <div class="row">
                <div class="col-lg-6 col-lg-offset-3">
                <form action="{{route('admin.add.profile')}}" method="post" enctype="multipart/form-data">
                   @csrf
                   @if(isset($profile->id))
                   <input type="hidden" name="id" value="{{$profile->id}}">
                    @else
                    <input type="hidden" name="id" value="">
                    @endif
                    <div class="form-group">
                     <label class="col-lg-12 form-title required">Firstname </label>
                     <div class="col-lg-12">
                     <input type="text" name="firstname" class="form-control" placeholder="Firstname" value="@if(isset($profile->firstname)){{$profile->firstname}}@elseif(old('firstname')!=''){{old('firstname')}} @endif" maxlength="30"> 
                       @if($errors->has('firstname'))    
                       <span class="text-danger">{{$errors->first('firstname')}}
                       </span>
                       @endif
                     
                       </div>

                     	<label class="col-lg-12 form-title required">Lastname </label>
                     	<div class="col-lg-12">
                     	<input type="text" name="lastname" class="form-control" placeholder="Lastname" id="lastname"value="@if(isset($profile->lastname)){{$profile->lastname}}@elseif(old('lastname')!=''){{old('lastname')}} @endif" maxlength="30">
                        @if($errors->has('lastname'))
                        <span class="text-danger">{{$errors->first('lastname')}}</span>
                        @endif
                        </div>

                        <label class="col-lg-12 form-title required">Contact No </label>
	                            <div class="col-lg-3 contact-code">
                                 <select class="form-control minimal" data-placeholder="" tabindex="1" name="country_code">
	                	        	        @if(count($country))
		                                	<option value="">Select Country Code</option>
		                                 @endif
		                                 @foreach($country as $data)

		                                    	<option value="{{ $data->phone_code }}"
		                                		@if(old('country_code') == $data->phone_code)
		                                			selected="true"
		                                		@endif
		                                		@if(isset($profile->country_code) && $data->phone_code == $profile->country_code)
		                                			selected="true"
		                                		@endif
		                                		>{{ $data->phone_code }}</option>
                                        @endforeach
                                     </select>

		                             @if ($errors->has('country_code'))
						                <span class="text-danger">{{ $errors->first('country_code') }}</span>
						            @endif 
	                            </div>
	                            <div class="col-lg-9 contact-number">
	                                <input type="text" class="form-control" placeholder="Number" id="mobile_no" name="mobile_no" value="@if(isset($profile->mobile_no)){{ $profile->mobile_no }}@elseif(old('contact_number')!=''){{ old('contact_number') }} @endif" maxlength="12"> 
	                            </div>

                     	<div class="col-lg-12">
		                            @if ($errors->has('country_code'))
						                <span class="text-danger">{{ $errors->first('country_code') }}</span>
						            @elseif ($errors->has('mobile_no'))
						                <span class="text-danger">{{ $errors->first('mobile_no') }}</span>
						            @endif
					      </div>

                     	<label class="col-lg-12 form-title">Profile </label>
                        <div class="col-lg-12">
                     	<input type="file" name="profile" id="profile_id"class="form-control" value="@if(isset($profile->profile)){{$profile->profile}}@elseif(old('profile')!=''){{old('profile')}} @endif" maxlength="100">
                     	@if ($errors->has('profile'))
						                <span class="text-danger">{{ $errors->first('profile') }}</span>
						            @endif
						       </div>
                     	@if(isset($profile->profile) && !empty($profile->profile))
                     	<img src="{{url($profile->profile)}}" width="100px" height="100px"> 
                     	@endif                   	
                     
                     	<label class="col-lg-12 form-title required">Email</label>
                     	<div class="col-lg-12">
                     	<input type="Email" name="email" class="form-control" value="@if(isset($profile->email)){{$profile->email}}@elseif(old('email')!=''){{old('email')}} @endif" maxlength="80">
                     	@if($errors->has('email'))
                     	<span class="text-danger">{{$errors->first('email')}}</span>
                     	@endif
                      </div>

                    <div class="col-lg-12 form-submit">
                    <input type="submit" name="submit" value="SAVE" class="btn btn-info col-lg-8">	
                    <input type="reset" class="btn btn btn-default col-lg-3 col-lg-offset-1" value="RESET">
                   </div>
                   </div> 

                </form>
            </div>
            </div>
         </div>
    </div>
 </div>
</div>
</div>

@endsection
@push('css')
<style type="text/css">
   .required:after{
 	     content: "*";
    	color:red;
    } 
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
	select::-ms-expand {
        display: none;
    }
    select {
        -webkit-appearance: none;
        -moz-appearance: none;
        text-indent: 1px;
        text-overflow: '';
    }
    select.minimal {
      background-image:
        linear-gradient(45deg, transparent 50%, black 50%),
        linear-gradient(135deg, black 50%, transparent 50%),
        linear-gradient(to right, #ccc, #ccc);
      background-position:
        calc(100% - 20px) calc(1em + 2px),
        calc(100% - 15px) calc(1em + 2px),
        calc(100% - 2.5em) 0.5em;
      background-size:
        5px 5px,
        5px 5px,
        1px 1.5em;
      background-repeat: no-repeat;
    }

    select.minimal:focus {
      background-image:
        linear-gradient(45deg, black 50%, transparent 50%),
        linear-gradient(135deg, transparent 50%, black 50%),
        linear-gradient(to right, #ccc, #ccc);
      background-position:
        calc(100% - 15px) 1em,
        calc(100% - 20px) 1em,
        calc(100% - 2.5em) 0.5em;
      background-size:
        5px 5px,
        5px 5px,
        1px 1.5em;
      background-repeat: no-repeat;
      border-color: black;
      outline: 0;
    }
</style>
@endpush
@push('script')
<script type="text/javascript">
	
</script>
@endpush