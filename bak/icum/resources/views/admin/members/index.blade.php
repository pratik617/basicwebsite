@extends('admin.layouts.app')
@section('title', 'Manage Members')

@section('content')
<div class="row bg-title">
  <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
      <h4 class="page-title">Members</h4>
  </div>
  <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
      <ol class="breadcrumb">
          <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
          <li class="active">Members</li>
      </ol>
  </div>
</div>
<!-- /.col-lg-12 -->
@include('admin.inc.messages')
<!-- /row -->
<div class="row">
    <div class="col-sm-12">
        <div class="white-box">
          <h3 class="box-title m-b-30">Manage Members
            <a href="{{ route('admin.members.create') }}" class="btn btn-success btn-sm btn-flat pull-right" title="Add New Member">
              <i class="fa fa-plus" aria-hidden="true"></i> Add New
            </a>
          </h3>


            <div class="table-responsive">
                <table id="members-table" class="table table-striped">
                    <thead>
                        <tr>
        									<th>Name</th>
        									<th>Email</th>
        									<th>Status</th>
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
    $('#members-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{!! route('admin.members.data') !!}',
        columns: [
            { data: 'name', name: 'name' },
            { data: 'email', name: 'email' },
      			{ data: 'status', name: 'status', orderable: false, searchable: false,
      				render: function (status) {
      					return (status==1)?'<span class="text-success">Enable</span>':'<span class="text-danger">Disable</span>'
      				}
      			},
			      { data: 'action', name: 'actions', orderable: false, searchable: false }
        ]
    });
  });

</script>
@endpush
