@extends('layout.admin.layout')
@section('title', 'Promotions - RideApp Management')
@section('content')

<h1>Admin Trip Detail</h1>

@endsection

@push('css')
<style type="text/css">
    
</style>
@endpush
@push('script')
<script type="text/javascript">
    $(document).ready(function(){

    });
</script>
@endpush
<!--@extends('layout.admin.layout')
@section('title', 'Trip-Details - RideApp Management')
@section('content')
<br>
<div class="row">
    <div class="col-lg-12">
        <div class="white-box">
            <div class="card-body">
            	<div class="display-block">
	            	<h2>Trip Details</h2>
                </div>
                <div class="table-responsive">
                    <table id="demo-foo-addrow" class="table table-bordered m-t-30 table-hover contact-list" data-paging="true" data-paging-size="7">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th>PickUp Address</th>
                                <th>Drop Address</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                             @php
                                 $counter = 1;
                             @endphp
                             @forelse($trips as $data)
                                <tr>
                                    <td>{{ $counter }}</td>
                                    <td>{{ $data->pickup_address }}</td>
                                    <td>{{ $data->drop_address }}</td>
                                    <td>
                                        @if($data->status  == "complete" )
                                            <span class="label label-success label-rouded">
                                        @else
                                            <span class="label label-danger label-rouded">
                                        @endif
                                        {{ucfirst($data->status)}}</span>
                                    </td>
                                </tr>
                                @php
                                    $counter++;
                                @endphp
                            @empty
                                <tr align="center">
                                    <td colspan="10">trips not found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <nav aria-label="...">
                    @if ($trips->lastPage() > 1)
                        <ul class="pagination">
                            @if ($trips->currentPage() != 1 && $trips->lastPage() >= 5)
                                <li class="page-item"><a class="page-link" href="{{ $trips->url($trips->url(1)) }}" ><i class="fa fa-angle-double-left"></i></a></li>
                            @endif
                            @if($trips->currentPage() != 1)
                                <li class="page-item">
                                    <a class="page-link" href="{{ $trips->url($trips->currentPage()-1) }}" >
                                        <
                                    </a>
                                </li>
                            @endif
                            @for($i = max($trips->currentPage()-2, 1); $i <= min(max($trips->currentPage()-2, 1)+4,$trips->lastPage()); $i++)
                                    <li class="page-item {{ ($trips->currentPage() == $i) ? 'active' : '' }}">
                                        <a class="page-link" href="{{ $trips->url($i) }}">{{ $i }}</a>
                                    </li>
                            @endfor
                            @if ($trips->currentPage() != $trips->lastPage())
                                <li class="page-item">
                                    <a class="page-link" href="{{ $trips->url($trips->currentPage()+1) }}" >
                                        >
                                    </a>
                                </li>
                            @endif
                            @if ($trips->currentPage() != $countripstries->lastPage() && $trips->lastPage() >= 5)
                                <li class="page-item">
                                    <a class="page-link" href="{{ $trips->url($trips->lastPage()) }}" >
                                        >>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    @endif
                    </nav>
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
.img-circle {
    border-radius: 50%;
    height: 30px;
}
</style>
@endpush
@push('script')
-->

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