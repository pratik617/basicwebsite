@extends('layout.admin.layout')
@section('title', 'Dashboard - RideApp Management')
@section('page-title', 'Dashboard')
@section('content')

<h1>Admin Dashboard</h1>

@endsection

@push('css')
<link rel="stylesheet" type="text/css" href="{{url('css/admin/dashboard.css')}}"> 
@endpush

@push('script')
<!-- Chart JS -->
<script src="{{url('plugins/bower_components/flot/jquery.flot.js')}}"></script>
<script src="{{url('plugins/bower_components/flot/jquery.flot.pie.js')}}"></script>
<script src="{{url('plugins/bower_components/flot/jquery.flot.time.js')}}"></script>
<script src="{{url('plugins/bower_components/flot.tooltip/js/jquery.flot.tooltip.min.js')}}"></script>
<script src="{{url('js/flot-data.js')}}"></script>

<script type="text/javascript" src="{{url('js/admin/dashboard.js')}}"></script>
@endpush