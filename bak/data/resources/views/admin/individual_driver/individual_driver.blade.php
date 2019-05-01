@extends('layout.admin.layout')
@section('title', 'Individual Driver - RideApp Management')
@section('content')
<br>
<div class="row">
    <div class="col-lg-12">
        <div class="white-box">
            <div class="card-body">
            	<div class="display-block">
	            	<h2>Individual Driver</h2>
               <a class="btn btn-info m-t-10 pull-right" href="{{ route('admin.add.driver') }}">Add Individual Driver</a>
                </div>

                <div class="table-responsive">
                    <table id="demo-foo-addrow" class="table table-bordered m-t-30 table-hover contact-list" data-paging="true" data-paging-size="7">
                        <thead>
                        <!-- <tr>
                                <th width="5%">*</th>
                                <th width="20%"><input class="form-control" type="text" name="name" placeholder="Search Name"></th>
                                <th width="15%"><input class="form-control" type="text" name="postcode" placeholder="Search Postcode"></th>
                                <th width="50%"><input class="form-control" type="text" name="address" placeholder="Search Address"></th>
                                <th width="10%"><input class="btn btn-default" type="submit" name="search" value="Search"></th>
                            </tr> -->
                            <tr>
                            	<th width="5">No</th>
                                <th>Vehicle Type</th>
                                <th>Vehicle Category</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Contact No</th>
                                <th>Profile</th>
                                <th>Status</th>
                                <th width="">Action</th>
                             </tr>
                        </thead>
                        <tbody>
                        	@php
                        	$counter=1;
                        	@endphp
                        	 @foreach($drivers as $data)
                                <tr>
                                	<td>{{$counter}}</td>
                                    <td>{{$data->vehicle_types_name}}</td>
                                    <td>{{$data->vehicle_categorys_name}}</td>
                                    <td>{{ $data->name }}</td>
                                    <td>{{ $data->email }}</td>
                                    <td>{{$data->country_code.$data->contact_no}}</td>
                                    <td>
                                        @if(isset($data->profile))
                                        <img src="{{url($data->profile)}}" width="30px" height="50px">
                                        @endif
                                    </td>
                                    <td>
                                      @if($data->status=='active')
                                      <span class="label label-success label rounded">Active</span>
                                      @elseif($data->status=='inactive')
                                      <span class="label label-danger label rounded">InActive</span>
                                      @elseif($data->status=='inprocess')
                                      <span class="label label-info label rounded">Inprocess</span>
                                      @elseif($data->status=='pending')
                                      <span class="label label-warning label rounded">Pending</span>
                                      @endif
                                    </td>

                                    <td>
                                    <a type="button" class="btn btn-secondary footable-edit green" href="{{ route('admin.edit.driver',['id'=>$data->id]) }}">
                                            <span class="fas fa-pencil-alt" aria-hidden="true"></span>
                                        </a>
                                        <a type="button" class="btn btn-secondary footable-delete  red" onclick="return confirm('Are you sure you want to delete drivers?')" href="{{route('admin.driver.delete',["id"=>$data->id]) }}">
                                            <span class="fas fa-trash-alt" aria-hidden="true"></span>
                                        </a>
                                    </td>
                                 </tr>
                                 @php
                                 $counter++;
                                 @endphp
                             @endforeach
                        </tbody>
                    </table>
                    {{$drivers->links()}}
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('css')
<style type="text/css">
	.display-block h2{
    display: inline-block;
}
.comany-logo{
    width: 60px !important;
    height: auto;
}
</style>
@endpush
@push('script')
<script <script src="{{url('plugins/bower_components/sweetalert/sweetalert.min.js')}}"></script>
<script type="text/javascript">
	$(document).ready(function(){
     $('.sa-warning').click(function(){
            swal({   
                title: "Are you sure?",   
                text: "You will not be able to recover this company!",   
                type: "warning",   
                showCancelButton: true,   
                confirmButtonColor: "#DD6B55",   
                confirmButtonText: "Yes, delete it!",   
                closeOnConfirm: false 
            }, function(){   
                swal("Deleted!", "Your company has been deleted.", "success"); 
            });
        });      
	});
</script>
@endpush