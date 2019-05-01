@extends('layout.admin.layout')
@section('title', 'Company - RideApp Management')
@section('content')
<br>
<div class="row">
    <div class="col-lg-12">
        <div class="white-box">
            <div class="card-body">
            	<div class="display-block">
                   <h2>vehicle Categories</h2>
                    <a class="btn btn-info m-t-10 pull-right" href="{{ route('admin.add.vehicle_category') }}">Add Vehicle Categorys</a>
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
                                <th>Vehicle Type Name</th>
                                <th>Name</th>
                                <th>Min Person Capacity</th>
                                <th>Max Person Capacity</th>
                                <th>Status</th>
                                <th width="10">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $counter = 1;
                            @endphp
                            @foreach($vehicle_categorys as $vehiclecategorys)
                                <tr>
                                    <td>{{ $counter }}</td>
                                    <td>{{ $vehiclecategorys->vehicle_types_name }}</td>
                                    <td>{{ $vehiclecategorys->name }}</td>
                                    <td>{{ $vehiclecategorys->min_person_capacity}}</td>
                                    <td>{{ $vehiclecategorys->max_person_capacity}}</td>
                                    <td>
                                         @if($vehiclecategorys->status  == "active" )
                                            <span class="label label-success label-rouded">Active</span>
                                        @else
                                            <span class="label label-danger label-rouded">
                                            Inactive</span>
                                        @endif
                                     </td>

                                    <td>
                                     <a type="button" class="btn btn-secondary footable-edit green" href="{{route('admin.edit.vehicle_category',['id'=>$vehiclecategorys->id])}}">
                                        <span class="fas fa-pencil-alt" aria-hidden="true">
                                        </span>
                                       </a>
                                         <a type="button" class="btn btn-secondary footable-delete red" onclick="return confirm('Are you sure you want to delete vehicleCategorys?')" href="{{route('vehicle_categorys.delete',["did"=>$vehiclecategorys->id])}}">
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
                    {{$vehicle_categorys->links()}}
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

@endpush