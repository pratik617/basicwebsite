@extends('layout.admin.layout')
@section('title', 'Country-Setting - RideApp Management')
@section('content')
<br>
<div class="row">
    <div class="col-lg-12">
        <div class="white-box">
            <div class="card-body">
            	<div class="display-block">
	            	<h2>Countries</h2>
                </div>

                <div class="table-responsive">
                    <table id="demo-foo-addrow" class="table table-bordered m-t-30 table-hover contact-list" data-paging="true" data-paging-size="7">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th>Country</th>
                                <th>Code</th>
                                <th>Unit</th>
                                <th>Currnecy</th>
                                <th>UTC-diff</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        	 @php
                                 $counter = 1;
                                 function GetUTCTime($utc_diff)
                                 {
                                 	try{
	                                 	if($utc_diff)
	                                 	{
	                                 		$out_utc_diff='(UTC';
	                                 		$out_utc_diff.=(strpos($utc_diff, 'hours') !== false)?explode("hours",$utc_diff)[0]:'00';
											$out_utc_diff.=':';
											$out_utc_diff.=(strpos($utc_diff, 'minutes') !== false)?substr($utc_diff,strpos($utc_diff, 'minutes')-2,2):'00';
											$out_utc_diff.=')';
											return $out_utc_diff;
	                                 	}
	                                 	else 
	                                 		return '';
	                                 }
	                                 catch (\Exception $e) {
	                                 	$Exceptions = New Exceptions;
							            $Exceptions->sendException($e);
							            return '';
							    	}
                                 }
                             @endphp
                        	 @forelse($countries as $data)
                                <tr>
                                    <td>{{ $counter }}</td>
                                    <td>{{ $data->name }}</td>
                                    <td>{{ $data->phone_code }}</td>
                                    <td>{{ $data->unit}}</td>
                                    <td>{{ $data->currency_code}}-{{ $data->currency_sign}}</td>
                                    <td>{{ GetUTCTime($data->utc_diff) }}</td>
                                    <td>
                                        @if($data->status  == "active" )
                                            <span class="label label-success label-rouded">Active</span>
                                        @else
                                            <span class="label label-danger label-rouded">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.edit.country-setting',['id'=>$data->id]) }}" class="btn btn-secondary footable-edit green"><span class="fas fa-pencil-alt" aria-hidden="true"></span></a>
                                    </td>
                                </tr>
                                @php
                                    $counter++;
                                @endphp
                            @empty
                                <tr align="center">
                                    <td colspan="10">Countries not found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
			  	<nav aria-label="...">
					@if ($countries->lastPage() > 1)
					    <ul class="pagination">
					        @if ($countries->currentPage() != 1 && $countries->lastPage() >= 5)
					            <li class="page-item"><a class="page-link" href="{{ $countries->url($countries->url(1)) }}" ><i class="fa fa-angle-double-left"></i></a></li>
					        @endif
					        @if($countries->currentPage() != 1)
					            <li class="page-item">
					                <a class="page-link" href="{{ $countries->url($countries->currentPage()-1) }}" >
					                    <
					                </a>
					            </li>
					        @endif
					        @for($i = max($countries->currentPage()-2, 1); $i <= min(max($countries->currentPage()-2, 1)+4,$countries->lastPage()); $i++)
					                <li class="page-item {{ ($countries->currentPage() == $i) ? 'active' : '' }}">
					                    <a class="page-link" href="{{ $countries->url($i) }}">{{ $i }}</a>
					                </li>
					        @endfor
					        @if ($countries->currentPage() != $countries->lastPage())
					            <li class="page-item">
					                <a class="page-link" href="{{ $countries->url($countries->currentPage()+1) }}" >
					                    >
					                </a>
					            </li>
					        @endif
					        @if ($countries->currentPage() != $countries->lastPage() && $countries->lastPage() >= 5)
					            <li class="page-item">
					                <a class="page-link" href="{{ $countries->url($countries->lastPage()) }}" >
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