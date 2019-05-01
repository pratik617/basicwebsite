@extends('layout.admin.layout')
@section('title', 'Custome - RideApp Management')
@section('content')
<br>
<div class="row">
    <div class="col-lg-12">
        <div class="white-box">
            <div class="card-body">
            	<div class="display-block">
                @if (\Request::is('admin/customer-delete-soft'))  
             <h2>Deleted Customer</h2>
             @else
             <h2>Customer</h2>
             @endif
                </div>

                <div class="table-responsive">
                    <table id="demo-foo-addrow" class="table table-bordered m-t-30 table-hover contact-list" data-paging="true" data-paging-size="7">
                        <thead>
                            <tr>
                            		<th>No</th>
                               		 <th>Name</th>
                               		 <th>Email</th>
                               		 <th>Contact No</th>
                                 	<th>Profile</th>
                                    <th>status</th>
                                	<th>Action</th>
                             </tr>
                        </thead>
                        <tbody>
                        	@php
                        	$counter=1;
                        	@endphp
                        	 @foreach($customer as $data)
                                <tr>
                                	<td>{{$counter}}</td>
                                    <td>{{ $data->name }}</td>
                                    <td>{{ $data->email }}</td>
                                    <td>{{$data->country_code.$data->contact_no}}</td>
                                    <td><img src="{{$data->profile}}"></td>
                                      
                                      <td> 
                                        @if($data->deleted_at=="")
                                        <a href="{{ route('customer.status',['status'=>($data->status=='in-active') ? 'in-active' : 'active','id'=>$data->id]) }}"> 
                                             @if(($data->status=="in-active")) 
                                            <span class="label label-danger">in-active</span>
                                            @else
                                            <span class="label label-success">active</span>
                                            @endif
                                        </a>
                                       @else
                                       <span class=""></span>
                                       @endif    
                                    </td>

                                     <td>
                                    @if($data->deleted_at=="")
                                   <a type="button" class="btn btn-secondary footable-delete red" onclick="return confirm('Are you sure you want to delete customer?')" href="{{route('admin.customer.delete',["id"=>$data->id])}}">
                                            <span class="fas fa-trash-alt" aria-hidden="true"></span>
                                     @else
                                     <span class=""></span>
                                     @endif       
                                        </a>
                                      </td> 
                                    </tr>
                                 @php
                                 $counter++;
                                 @endphp
                             @endforeach
                        </tbody>
                    </table>
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
<script src="{{url('plugins/bower_components/sweetalert/sweetalert.min.js')}}"></script>
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