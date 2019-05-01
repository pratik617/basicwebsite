@extends('layout.admin.layout')
@section('title', 'Company - RideApp Management')
@section('content')
<br>
<div class="row">
    <div class="col-lg-12">
        <div class="white-box">
            <div class="card-body">
            	<div class="display-block">
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
                    <h2>Companies</h2>
                    <a class="btn btn-info m-t-10 pull-right" href="{{ route('admin.add.company') }}">Add Company</a>
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
                                <th width="5%">No</th>
                                <th>Name</th>
                                <th>City</th>
                                <th>Logo</th>
                                <th width="10%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $counter = 1;
                            @endphp
                            @forelse($companys as $data)
                                <tr>
                                    <td>{{ $counter }}</td>
                                    <td>{{ $data->name }}</td>
                                    <td>{{ $data->city }}</td>
                                    <td>@if($data->logo!="")<img src="{{ url($data->logo) }}" class="comany-logo"/>
                                    @endif</td>
                                    <td>
                                        <a href="{{ route('admin.edit.company',['id'=>$data->id]) }}" class="btn btn-secondary footable-edit green"><span class="fas fa-pencil-alt" aria-hidden="true"></span></a>
                                        <a type="button" class="btn btn-secondary footable-delete sa-warning red" name="{{ $data->id }}">
                                            <span class="fas fa-trash-alt" aria-hidden="true"></span>
                                        </a>
                                    </td>
                                </tr>
                                @php
                                    $counter++;
                                @endphp
                            @empty
                                <tr align="center">
                                    <td colspan="5">Company not found.</td>
                                </tr>
                            @endforelse
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

<!-- Sweet Alert -->
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