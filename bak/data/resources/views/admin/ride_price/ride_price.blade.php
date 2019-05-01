@extends('layout.admin.layout')
@section('title', 'Company - RideApp Management')
@section('content')
<br>
<div class="row">
    <div class="col-lg-12">
        <div class="white-box"
            <div class="card-body">
                <div class="display-block">
                    <h2>Ride Price</h2>
                      <a class="btn btn-info m-t-10 pull-right" href="{{ route('admin.add.rideprice') }}">Add RidePrice</a>
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
                                <th>VehicleTypeName</th>
                                <th>VehicleCategoryName</th>
                                <th>Unit</th>
                                <th>Price</th>
                                <th>PerMinute</th>
                                <th width="10">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $counter = 1;
                            @endphp
                            @foreach($ride_price as $ride_prices)
                                <tr>
                                   <td>{{$counter}}</td>
                                   <td>{{$ride_prices->vehicle_types_name}}</td>
                                   <td>{{$ride_prices->vehicle_categorys_name}}</td>
                                   <td>{{$ride_prices->unit}}</td>
                                   <td>{{$ride_prices->price}}</td>
                                   <td>{{$ride_prices->perminute}}</td>
                                   <td><a type="button" href="{{route('admin.edit.ride_price',['id'=>$ride_prices->id])}}" class="btn btn-secondary footable-edit green">
                                   <span class="fas fa-pencil-alt"></span>    
                                   </a>
                                   <a type="button" onclick="return confirm('Are you sure you want to Delete Price?')" href="{{route('admin.delete.ride_price',['id'=>$ride_prices->id])}}" class="btn btn-secondary footable-delete red">
                                    <span class="fas fa-trash alt"></span>
                                  </td>
                                </tr>
                                @php
                                    $counter++;
                                @endphp
                            @endforeach
                        </tbody>
                    </table>
                    {{$ride_price->links()}}
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