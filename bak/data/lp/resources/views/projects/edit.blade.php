@extends('layouts.app')
@section('title', 'Edit Project')

@section('content')
<div class="row bg-title">
  <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
      <h4 class="page-title">Projects</h4>
  </div>
  <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
      <ol class="breadcrumb">
          <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
          <li><a href="{{ route('projects.index') }}">Projects</a></li>
          <li class="active">{{ ucwords($project->name) }}</li>
      </ol>
  </div>
</div>
<!-- /.col-lg-12 -->

<!-- .row -->
<div class="row">
    <div class="col-sm-12">
        <div class="white-box">
            <h3 class="box-title m-b-30">Edit Project #{{ $project->id }}</h3>
            <form class="form-horizontal" method="POST" action="{{ route('projects.update', $project->id) }}" enctype="multipart/form-data">
              {{ method_field('PATCH') }}
              {{ csrf_field() }}

              @include ('projects.form', ['formMode' => 'edit'])

            </form>
        </div>
    </div>
</div>
<!-- /.row -->
@endsection
