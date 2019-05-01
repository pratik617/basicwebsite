@extends('layout.admin.layout')
@section('title', 'Taxes - RideApp Management')
@section('content')
<br>
<div class="row">
    <div class="col-lg-12">
        <div class="white-box">
            <div class="card-body">
            	<div class="display-block">
                   <h2>Taxes</h2>
                    <a class="btn btn-info m-t-10 pull-right" href="{{route('admin.add.taxes')}}">Add Tax</a>
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
                                <th>Country Name</th>
                                <th>Tax Name</th>
                                <th>percent</th>
                                <th width="10">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $counter = 1;
                            @endphp
                            @foreach($taxes as $taxes)
                                <tr>
                                    <td>{{ $counter }}</td>
                                    <td>{{ $taxes->countrys_name }}</td>
                                    <td>{{ $taxes->tax_name }}</td>
                                    <td>{{ $taxes->percent}}</td>
                                    <td>
                                     <a type="button" class="btn btn-secondary footable-edit green" href="{{ route('admin.edit.taxes',['id'=>$taxes->id])}}">
                                     <span class="fas fa-pencil-alt" aria-hidden="true">
                                     </span>
                                     </a>
                                     <a type="button" class="btn btn-secondary footable-delete red" onclick="return confirm('Are you sure want to delete Taxes?')" href="{{ route('taxes.delete',['id'=>$taxes->id]) }}">
                                     <span class="fas fa-trash-alt" aria-hidden="true">
                                     </span>
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

@endpush