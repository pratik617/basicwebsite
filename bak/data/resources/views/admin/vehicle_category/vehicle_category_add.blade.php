@extends('layout.admin.layout')
@if(isset($vehicle_categorys->id))
	@section('title', 'Edit vehicle Category - RideApp Management')
@else
	@section('title', 'Add vehicle Category - RideApp Management')
@endif
@section('content')
<br>
<div class="row">
    <div class="col-lg-12">
        <div class="white-box">
            <div class="card-body">
            	<div class="display-block">
	            	<center>
	            		<i class="mdi mdi-subway"></i>
	            		<h2 class="company-title"> 
		            		@if(isset($vehicle_categorys->id))
		            			Edit Vehicle Category 
	            			@else
	            				Add Vehicle Categorys
	            			@endif
		            	</h2>
		            </center>
	            	<hr class="hr-line">
                </div>

                <div class="row">
                	<div class="col-lg-6 col-lg-offset-3">

                		<form action="{{ route('admin.store.vehiclecategory') }}" id="form" method="post" enctype="multipart/form-data">
                			@csrf
                			@if(isset($vehicle_categorys->id))
		            			<input type="hidden" name="id" value="{{$vehicle_categorys->id}}">
		            		@else
		            			<input type="hidden" name="id" value="">
	            			@endif
	                		<div class="form-group">
	                	        <label class="col-lg-12 form-title">Vechile Name </label> 
	                	        <div class="col-lg-12">
	                	        <select class="form-control minimal" data-placeholder="Choose Company" tabindex="1" name="vehicle_type_id">
	                	        	        @if(count($vehicle_type))
		                                	<option value="">Select Company</option>
		                                @endif
		                                @foreach($vehicle_type as $data)

		                                    	<option value="{{ $data->id }}"
		                                		@if(old('vehicle_type_id') == $data->id)
		                                			selected="true"
		                                		@endif
		                                		@if(isset($vehicle_categorys->vehicle_type_id) && $data->id == $vehicle_categorys->vehicle_type_id)
		                                			selected="true"
		                                		@endif
		                                		>{{ $data->name }}</option>
                                        @endforeach
                                     </select>
		                             @if ($errors->has('vehicle_type_id'))
						                <span class="text-danger">{{ $errors->first('vehicle_type_id') }}</span>
						            @endif
                                 </div>
                                
	                			<label class="col-lg-12 form-title">Name </label>
	                            <div class="col-lg-12">
	                            	<input type="text" class="form-control"  placeholder="name" id="vehiclecategory_name" name="name" maxlength="30" value="@if(isset($vehicle_categorys->name)){{$vehicle_categorys->name}}@elseif(old('name')!=''){{old('name')}} @endif">

                                       @if($errors->has('name'))
                                       <span class="text-danger">{{$errors->first('name')}}</span>
                                       @endif	 

	                              </div>
	                              <label class="col-lg-12 form-title">Minimum Person Capacity </label>
	                            <div class="col-lg-12">
	                            	<input type="text" class="form-control"  placeholder="Minimum Person Capacity" id="vehiclecategory_min_capacity"  maxlength="3" name="min_person_capacity" value="@if(isset($vehicle_categorys->min_person_capacity)){{$vehicle_categorys->min_person_capacity}}@elseif(old('min_person_capacity')!=''){{old('min_capacity')}} @endif">

                                       @if($errors->has('min_person_capacity'))
                                       <span class="text-danger">{{$errors->first('min_person_capacity')}}</span>
                                       
                                       @endif

                                        </div>
	                              <label class="col-lg-12 form-title">Maximum Person Capacity </label>
	                            <div class="col-lg-12">
	                            	<input type="text" class="form-control"  placeholder="Maximum Person Capacity" id="vehiclecategory_max_capacity" maxlength="3" name="max_person_capacity" value="@if(isset($vehicle_categorys->max_person_capacity)){{$vehicle_categorys->max_person_capacity}}@elseif(old('max_person_capacity')!=''){{old('max_capacity')}} @endif">

                                       @if($errors->has('max_person_capacity'))
                                       <span class="text-danger">{{$errors->first('max_person_capacity')}}</span>
                                       @endif

                                   </div>

	                          
	                        <label class="col-lg-12 form-title">Status </label>
	            				<div class="col-lg-12">
		            				<select class="form-control minimal" name="status">
		                                <option value="active" 
		                                @if(isset($vehicle_categorys->status))
		                                	@if($vehicle_categorys->status=="active")
		                                		selected="true"
		                                	@endif
		                                @endif
		                                >Active</option>
		                               	<option value="in-active" 
		                               	@if(isset($vehicle_categorys->status))
		                               		@if($vehicle_categorys->status=="in-active")
		                                		selected="true"
	                                		@endif
		                                @endif>InActive</option>
		                            </select>
		                            @if ($errors->has('status'))
						                <span class="text-danger">{{ $errors->first('status') }}</span>
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

	                            <div class="col-lg-12 form-submit ">
		                            <input type="submit" class="btn btn-info col-lg-8" name="" value="SAVE">
		                            <input type="reset" id="reset" class="btn btn-default col-lg-3 col-lg-offset-1" name="" value="Reset">
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
.form-title:after{
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
	document.getElementById('form').reset();
</script>
@endpush