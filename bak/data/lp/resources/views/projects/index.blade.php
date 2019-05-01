@extends('layouts.app')
@section('title', 'Projects')

@section('content')
<div class="row bg-title">
  <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
      <h4 class="page-title">Projects</h4>
  </div>
  <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
      <ol class="breadcrumb">
          <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
          <li class="active">Projects</li>
      </ol>
  </div>
</div>
<!-- /.col-lg-12 -->
@include('inc.messages')
<!-- /row -->
<div class="row">
    <div class="col-sm-12">
        <div class="white-box">
          <h3 class="box-title m-b-30">Manage Projects
            <a href="{{ route('projects.create') }}" class="btn btn-success btn-sm btn-flat pull-right" title="Add New Project">
              <i class="fa fa-plus" aria-hidden="true"></i> Add New
            </a>
          </h3>


            <div class="table-responsive">
                <table id="projects-table" class="table table-striped">
                    <thead>
                        <tr>
                          <th>Code</th>
        									<th>Name</th>
        									<th>Title</th>
                          <!--<th>Address</th>-->
                          <th>Photo Count</th>
        									<th>Status</th>
                          <th>Images</th>
        									<th>Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('footer_scripts')
<script type="text/javascript">
  $(function() {
    $('#projects-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{!! route('projects.data') !!}',
        columns: [
            { data: 'code', name: 'code' },
            { data: 'name', name: 'name' },
            { data: 'title', name: 'title' },
            /*{ data: 'address', name: 'address', orderable: false, searchable: false },*/
            { data: 'photos_count', name: 'photos_count', orderable: false, searchable: false },
      			{ data: 'status', name: 'status', orderable: false, searchable: false,
      				render: function (status) {
      					return (status==1)?'<span class="text-success">Enable</span>':'<span class="text-danger">Disable</span>'
      				}
      			},
            { data: 'images', name: 'images', orderable: false, searchable: false },
			      { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });

  });

</script>
@endpush
